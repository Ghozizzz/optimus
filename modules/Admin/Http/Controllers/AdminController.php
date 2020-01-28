<?php 

namespace Modules\Admin\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Admin\Models\adminModel;
use Illuminate\Http\Request;
use Validator;
use Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Front_model;
use App\Http\Controllers\API;
use Input;
use Intervention\Image\ImageManagerStatic as Image;
use URL;

class AdminController extends Controller {
  private $optimus = [];
  
	public function __construct() {
        $this->session = Session::get(null);
        if (Session::get('login_type') != 'customer') {
        	$checkAuth = adminModel::checkSession(Session::get('email'));
        
	        if (empty($checkAuth)) {
	        	$this->middleware('authAdmin', ['except' => 'getLogout']);
	        	session()->forget('email');
	        }
	        if (!session()->has('email')) {
	            $this->middleware('authAdmin', ['except' => 'getLogout']);
	        	session()->forget('email');
	        }

	        $this->optimus['total_unread_message'] = adminModel::getUnreadMessage();
	        $this->optimus['total_unread_order'] = adminModel::getUnreadMessage(array('3','4','5','6','7','8'));
	        $this->optimus['active_payment'] = adminModel::getPaymentNotification();
	        $this->optimus['total_active_payment'] = $this->optimus['active_payment']['total'];
	        
	        $review = adminModel::getAllReview('all', '', '', '', 0);
	        $review_arr = [];
	        if($review['total']>0){
	          foreach($review['data'] as $data_detail){
	            $review_arr[] = $data_detail->invoice_id;
	          }
	        }
	        $this->optimus['moderated_review'] = $review_arr;
	        $this->optimus['total_moderated_review'] = count($review_arr);
        } else {
        	$this->optimus['total_unread_message'] = adminModel::getUnreadMessage();
	        $this->optimus['total_unread_order'] = adminModel::getUnreadMessage(array('3','4','5','6','7','8'));
	        $this->optimus['active_payment'] = adminModel::getPaymentNotification();
	        $this->optimus['total_active_payment'] = $this->optimus['active_payment']['total'];
	        
	        $review = adminModel::getAllReview('all', '', '', '', 0);
	        $review_arr = [];
	        if($review['total']>0){
	          foreach($review['data'] as $data_detail){
	            $review_arr[] = $data_detail->invoice_id;
	          }
	        }
	        $this->optimus['moderated_review'] = $review_arr;
	        $this->optimus['total_moderated_review'] = count($review_arr);
        }
        
        
        
    }

	public function index()
	{
		$this->optimus['config'] = API::getDefaultConfig();
		return view('admin::index')->with($this->optimus);
	}
	/*Smtp*/
	public function emailSmtp($message, $to, $from){
		$mail = new PHPMailer(true);    
	    try {
	        $mail->isSMTP(); // tell to use smtp
	        $mail->Host = "mail.rcorp.org";
	        $mail->CharSet = "utf-8"; // set charset to utf8
	        $mail->SMTPAuth = true;  // use smpt auth
	        $mail->SMTPSecure = "ssl"; // or ssl
	        $mail->Port = 465; // most likely something different for you. This is the mailtrap.io port i use for testing. 
	        $mail->Username = "optimus@rcorp.org";
	        $mail->Password = "Testing12345@";
	        $mail->setFrom("optimus@rcorp.org", "OPTIMUS Auto Trading");
	        $mail->Subject = "Notification: New message from $from";
	        $mail->MsgHTML($message);
	        $mail->addAddress($to, "Optimus Customer");
	        $mail->send();
	    } catch (phpmailerException $e) {
//	        dd($e);
	    } catch (Exception $e) {
//	        dd($e);
	    }
	    return $mail;
	}
	/*Privilege*/
	public function privilege($email){
		$db = adminModel::privilege($email);
		return $db;
	}
	/*Dealer Controller*/
	public function dealerIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::dealer($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::dealer', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function dealerSave(Request $request){
		$img = $request->newImage;
    $name = '';
		if($request->newImage == 'undefined') $img = null;
		$validator = Validator::make([
			'newEmail' => $request->newEmail,
			'newPassword' => $request->newPassword,
			'newImage' => $img,
			'newPicName' => $request->newPicName
		], [
			'newEmail' => 'required',
			'newPassword' => 'required',
			'newImage' => 'mimes:jpeg,jpg,png',
			'newPicName' => 'required'
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('newImage')) {
				$image = $request->file('newImage');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/dealer');
				$image->move($destinationPath, $name);
				$img = '/uploads/dealer/'.time().'.'.$image->getClientOriginalExtension();
			}

			$data = [
				'email' => $request->newEmail, 
				'password' => md5($request->newPassword),
				'status' => $request->newStatus,
				'country' => $request->newCountry,
				'address' => $request->newAddress,
				'telephone' => $request->newTelephone,
				'fax' => $request->newFax,
				'contact' => $request->newContact,
				'company_name' => $request->newCompanyName,
				'pic_name' => $request->newPicName,
				'bank_name' => $request->newBankName,
				'account_number' => $request->newAccountNumber,
				'account_name' => $request->newAccountName,
				'photo' => $name
			];
			$db = adminModel::saveDealer($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()], 400);
		}
	}
	public function dealerUpdate($id, Request $request){
		$img = $request->editImage;
		if($request->editImage == 'undefined') $img = '';
		$validator = Validator::make([
			'editEmail' => $request->editEmail,
			'editImage' => $img,
			'editPicName' => $request->editPicName
		], [
			'editEmail' => 'required',
			'editImage' => 'mimes:jpeg,jpg,png',
			'editPicName' => 'required'
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('editImage')) {
				$image = $request->file('editImage');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/dealer');
				$image->move($destinationPath, $name);
				$img = '/uploads/dealer/'.time().'.'.$image->getClientOriginalExtension();
			}
			if (isset($request->editEmail))$data['email'] = $request->editEmail;
			if (!empty($request->editPassword))$data['password'] = md5($request->editPassword);
			if (isset($request->editStatus))$data['status'] = $request->editStatus;
			if (isset($request->editCountry))$data['country'] = $request->editCountry;
			if (isset($request->editAddress))$data['address'] = $request->editAddress;
			if (isset($request->editTelephone))$data['telephone'] = $request->editTelephone;
			if (isset($request->editFax))$data['fax'] = $request->editFax;
			if (isset($request->editContact))$data['contact'] = $request->editContact;
			if (isset($request->editCompanyName))$data['company_name'] = $request->editCompanyName;
			if (isset($request->editPicName))$data['pic_name'] = $request->editPicName;
			if (isset($request->editBankName))$data['bank_name'] = $request->editBankName;
			if (isset($request->editAccountNumber))$data['account_number'] = $request->editAccountNumber;
			if (isset($request->editAccountName))$data['account_name'] = $request->editAccountName;
			if (!empty($img))$data['photo'] = $name;

			$db = adminModel::updateDealer($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function dealerView($id){
		$db = adminModel::dealerView($id);
		return response()->json($db);
	}
	public function dealerDelete($id){
		$db = adminModel::deleteDealer($id);
		return $db;
	}
  /*banner */
  public function bannerIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $db = adminModel::banner($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::banner', compact('db','getMax','descasc'))->with($this->optimus);;
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function bannerSave(Request $request){
		$img = $request->newImage;
		if($request->newImage == 'undefined') $img = null;
		$validator = Validator::make([
			'newName' => $request->newName,
			'newImage' => $img,
		], [
			'newName' => 'required',
			'newImage' => 'mimes:jpeg,jpg,png'
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('newImage')) {
				$image = $request->file('newImage');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/banner');
				$image->move($destinationPath, $name);
				$img = '/uploads/banner/'.time().'.'.$image->getClientOriginalExtension();
			}

			$data = [
				'name' => $request->newName, 
				'url' => $request->newUrl,
				'status' => $request->newStatus,
				'picture' => $name				
			];
			$db = adminModel::saveBanner($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function bannerUpdate($id, Request $request){
		$img = $request->editImage;
		if($request->editImage == 'undefined') $img = '';
		$validator = Validator::make([
			'editName' => $request->editName,
			'editImage' => $img,
		], [
			'editName' => 'required',
			'editImage' => 'mimes:jpeg,jpg,png',
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('editImage')) {
				$image = $request->file('editImage');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/banner');
				$image->move($destinationPath, $name);
				$img = '/uploads/banner/'.time().'.'.$image->getClientOriginalExtension();
			}
			if (isset($request->editName))$data['name'] = $request->editName;
			if (isset($request->editStatus))$data['status'] = $request->editStatus;
			if (isset($request->editUrl))$data['url'] = $request->editUrl;
			if (!empty($img))$data['picture'] = $img;

			$db = adminModel::updateBanner($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function bannerView($id){
		$db = adminModel::bannerView($id);
		return response()->json($db);
	}
	public function bannerDelete($id){
		$db = adminModel::deleteBanner($id);
		return $db;
	}
  
  /*port destination*/
	public function portDestinationIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$this->optimus['db'] = adminModel::portDestination($getMax, $descasc, $page, $filter);
		$this->optimus['country'] = adminModel::country();
    $this->optimus['type'] = 'port_destination';
    
    if(in_array(Session::get('login_type'),['admin','super admin','sales'])) {
			return view('admin::port_destination', compact('getMax','descasc'))->with($this->optimus);;
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function portDestinationSave(Request $request){
		$validator = Validator::make([
			'id_code' => $request->id_code,
			'country_code' => $request->country_code,
			'port_code' => $request->port_code,
			'port_name' => $request->port_name
		], [
			'id_code' => 'required',
			'country_code' => 'required',
			'port_code' => 'required',
			'port_name' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'id_code' => $request->id_code, 
				'country_code' => $request->country_code,
				'country_name' => strtoupper($request->country_name),
				'port_code' => $request->port_code,
				'port_name' => $request->port_name
			];
			$db = adminModel::savePortDestination($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function portDestinationUpdate($id, Request $request){
		$validator = Validator::make([
			'id_code' => $request->id_code,
			'country_code' => $request->country_code,
			'port_code' => $request->port_code,
			'port_name' => $request->port_name
		], [
			'id_code' => 'required',
			'country_code' => 'required',
			'port_code' => 'required',
			'port_name' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->id))$id = $request->id;
			if (!empty($request->id_code))$data['id_code'] = $request->id_code;
			if (!empty($request->country_code))$data['country_code'] = $request->country_code;
			if (!empty($request->country_name))$data['country_name'] = strtoupper ($request->country_name);
			if (!empty($request->port_code))$data['port_code'] = $request->port_code;
			if (!empty($request->port_name))$data['port_name'] = $request->port_name;
      $db = adminModel::updatePortDestination($id, $data);
      return $db;
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
  
  public function portDestinationView($id){
		$db = adminModel::portDestinationView($id);
		return response()->json($db);
	}
	public function portDestinationDelete($id){
		$db = adminModel::deletePortDestination($id);
		return $db;
	}
  
  /*port discharge*/
	public function portDischargeIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$this->optimus['db'] = adminModel::portDischarge($getMax, $descasc, $page, $filter);
		$this->optimus['country'] = adminModel::country();
    $this->optimus['type'] = 'port_discharge';
    
    if(in_array(Session::get('login_type'),['admin','super admin','sales'])) {
			return view('admin::port_destination', compact('getMax','descasc'))->with($this->optimus);;
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function portDischargeSave(Request $request){
		$validator = Validator::make([
			'id_code' => $request->id_code,
			'country_code' => $request->country_code,
			'port_code' => $request->port_code,
			'port_name' => $request->port_name
		], [
			'id_code' => 'required',
			'country_code' => 'required',
			'port_code' => 'required',
			'port_name' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'id_code' => $request->id_code, 
				'country_code' => $request->country_code,
				'country_name' => strtoupper($request->country_name),
				'port_code' => $request->port_code,
				'port_name' => $request->port_name
			];
			$db = adminModel::savePortDischarge($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function portDischargeUpdate($id, Request $request){
		$validator = Validator::make([
			'id_code' => $request->id_code,
			'country_code' => $request->country_code,
			'port_code' => $request->port_code,
			'port_name' => $request->port_name
		], [
			'id_code' => 'required',
			'country_code' => 'required',
			'port_code' => 'required',
			'port_name' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->id))$id = $request->id;
			if (!empty($request->id_code))$data['id_code'] = $request->id_code;
			if (!empty($request->country_code))$data['country_code'] = $request->country_code;
			if (!empty($request->country_name))$data['country_name'] = strtoupper($request->country_name);
			if (!empty($request->port_code))$data['port_code'] = $request->port_code;
			if (!empty($request->port_name))$data['port_name'] = $request->port_name;
      $db = adminModel::updatePortDischarge($id, $data);
      return $db;
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
  
  public function portDischargeView($id){
		$db = adminModel::portDischargeView($id);
		return response()->json($db);
	}
	public function portDischargeDelete($id){
		$db = adminModel::deletePortDischarge($id);
		return $db;
	}
  
	/*Customer*/
	public function customerIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::customer($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin','sales'])) {
			return view('admin::customer', compact('db','getMax','descasc'))->with($this->optimus);;
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function customerSave(Request $request){
		$validator = Validator::make([
			'newEmail' => $request->newEmail,
			'newPassword' => $request->newPassword
		], [
			'newEmail' => 'required',
			'newPassword' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'email' => $request->newEmail,
				'password' => md5($request->newPassword),
				'status' => $request->newStatus,
				'name' => $request->newName,
				'address' => $request->newAddress,
				'phone' => $request->newPhone,
				'birthday' => $request->newBirthday
			];
			$db = adminModel::saveCustomer($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function customerUpdate($id, Request $request){
		if($request->editImage == 'undefined') $img = '';
		$validator = Validator::make([
			'editEmail' => $request->editEmail
		], [
			'editEmail' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editEmail))$data['email'] = $request->editEmail;
			if (!empty($request->editPassword))$data['password'] = md5($request->editPassword);
			if (isset($request->editStatus))$data['status'] = $request->editStatus;
			if (!empty($request->editName))$data['name'] = $request->editName;
			if (!empty($request->editAddress))$data['address'] = $request->editAddress;
			if (!empty($request->editPhone))$data['phone'] = $request->editPhone;
			if (!empty($request->editBirthday))$data['birthday'] = $request->editBirthday;
			$db = adminModel::updateCustomer($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function customerView($id){
		$db = adminModel::customerView($id);
		return response()->json($db);
	}
	public function customerDelete($id){
		$db = adminModel::deleteCustomer($id);
		return $db;
	}

  /* country */
  public function countryIndex(){
    $this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::country($getMax, $descasc, $page, $filter);

		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::country', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
  }
  
  public function CountryView($id){
		$db = adminModel::countryView($id);
		return response()->json($db);
	}
  
  public function countryUpdate($id, Request $request){
    $validator = Validator::make([
			'editCode' => $request->editCode,
			'editName' => $request->editName,
		], [
			'editCode' => 'required',
			'editName' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editCode))$data['code'] = $request->editCode;
			if (!empty($request->editName))$data['name'] = $request->editName;
			$db = adminModel::updateCountry($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
  }  
  
  public function countrySave(Request $request){
		$validator = Validator::make([
			'newCode' => $request->newCode,
			'newName' => $request->newName
		], [
			'newCode' => 'required',
			'newName' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'code' => $request->newCode,
				'name' => $request->newName,
			];
			$db = adminModel::saveCountry($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
  
  public function countryDelete($id){
		$db = adminModel::deleteCountry($id);
		return $db;
	}
  
	/*Sales*/
	public function salesIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::sales($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::sales', compact('db','getMax','descasc'))->with($this->optimus);;
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function salesSave(Request $request){
		$validator = Validator::make([
			'newEmail' => $request->newEmail,
			'newPassword' => $request->newPassword
		], [
			'newEmail' => 'required',
			'newPassword' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'email' => $request->newEmail, 
				'password' => md5($request->newPassword),
				'status' => $request->newStatus,
				'name' => $request->newName,
				'address' => $request->newAddress,
				'phone' => $request->newPhone,
				'birthday' => $request->newBirthday
			];
			$db = adminModel::saveSales($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function salesUpdate($id, Request $request){
		$validator = Validator::make([
			'editEmail' => $request->editEmail
		], [
			'editEmail' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editEmail))$data['email'] = $request->editEmail;
			if (!empty($request->editPassword))$data['password'] = md5($request->editPassword);
			if (isset($request->editStatus))$data['status'] = $request->editStatus;
			if (!empty($request->editName))$data['name'] = $request->editName;
			if (!empty($request->editAddress))$data['address'] = $request->editAddress;
			if (!empty($request->editPhone))$data['phone'] = $request->editPhone;
			if (!empty($request->editBirthday))$data['birthday'] = $request->editBirthday;
			$db = adminModel::updateSales($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function salesView($id){
		$db = adminModel::SalesView($id);
		return response()->json($db);
	}
	public function salesDelete($id){
		$db = adminModel::deleteSales($id);
		return $db;
	}
	//Logistic
	public function logisticIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $db = adminModel::logistic($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::logistic', compact('db','getMax','descasc'))->with($this->optimus);;
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function logisticSave(Request $request){
		$img = $request->newImage;
		if($request->newImage == 'undefined') $img = null;
		$validator = Validator::make([
			'newEmail' => $request->newEmail,
			'newPassword' => $request->newPassword,
			'newImage' => $img,
			'newPicName' => $request->newPicName
		], [
			'newEmail' => 'required',
			'newPassword' => 'required',
			'newImage' => 'mimes:jpeg,jpg,png',
			'newPicName' => 'required'
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('newImage')) {
				$image = $request->file('newImage');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/images/logistic');
				$image->move($destinationPath, $name);
				$img = '/images/logistic/'.time().'.'.$image->getClientOriginalExtension();
			}

			$data = [
				'email' => $request->newEmail, 
				'password' => md5($request->newPassword),
				'status' => $request->newStatus,
				'country' => $request->newCountry,
				'address' => $request->newAddress,
				'telephone' => $request->newTelephone,
				'fax' => $request->newFax,
				'contact' => $request->newContact,
				'company_name' => $request->newCompanyName,
				'pic_name' => $request->newPicName,
				'bank_name' => $request->newBankName,
				'account_number' => $request->newAccountNumber,
				'account_name' => $request->newAccountName,
				'photo' => $img
			];
			$db = adminModel::saveLogistic($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()],400);
		}
	}
	public function logisticUpdate($id, Request $request){
		$img = $request->editImage;
		if($request->editImage == 'undefined') $img = '';
		$validator = Validator::make([
			'editEmail' => $request->editEmail,
			'editImage' => $img,
			'editPicName' => $request->editPicName
		], [
			'editEmail' => 'required',
			'editImage' => 'mimes:jpeg,jpg,png',
			'editPicName' => 'required'
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('editImage')) {
				$image = $request->file('editImage');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/logistic');
				$image->move($destinationPath, $name);
				$img = time().'.'.$image->getClientOriginalExtension();
			}
			if (isset($request->editEmail))$data['email'] = $request->editEmail;
			if (!empty($request->editPassword))$data['password'] = md5($request->editPassword);
			if (isset($request->editStatus))$data['status'] = $request->editStatus;
			if (isset($request->editCountry))$data['country'] = $request->editCountry;
			if (isset($request->editAddress))$data['address'] = $request->editAddress;
			if (isset($request->editTelephone))$data['telephone'] = $request->editTelephone;
			if (isset($request->editFax))$data['fax'] = $request->editFax;
			if (isset($request->editContact))$data['contact'] = $request->editContact;
			if (isset($request->editCompanyName))$data['company_name'] = $request->editCompanyName;
			if (isset($request->editPicName))$data['pic_name'] = $request->editPicName;
			if (isset($request->editBankName))$data['bank_name'] = $request->editBankName;
			if (isset($request->editAccountNumber))$data['account_number'] = $request->editAccountNumber;
			if (isset($request->editAccountName))$data['account_name'] = $request->editAccountName;
			if (!empty($img))$data['photo'] = $img;

			$db = adminModel::updateLogistic($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function logisticView($id){
		$db = adminModel::logisticView($id);
		return response()->json($db);
	}
	public function logisticDelete($id){
		$db = adminModel::deleteLogistic($id);
		return $db;
	}


	// Finance
	public function userIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : "asc";
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $filter = isset($_GET['filter'])?str_slug($_GET['filter'],' '):'';
		$db = adminModel::user($getMax, $descasc, $page, $filter);
    
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::user', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function userSave(Request $request){
		$validator = Validator::make([
			'newEmail' => $request->newEmail,
			'newPassword' => $request->newPassword
		], [
			'newEmail' => 'required',
			'newPassword' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'email' => $request->newEmail, 
				'password' => md5($request->newPassword),
				'status' => $request->newStatus,
				'name' => $request->newName,
				'address' => $request->newAddress,
				'phone' => $request->newPhone,
				'birthday' => $request->newBirthday,
        'privilege' => isset($request->newPrivilege) && $request->newPrivilege!== '' ? $request->newPrivilege: null,  
			];
			$db = adminModel::saveUser($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function userUpdate($id, Request $request){
		$validator = Validator::make([
			'editEmail' => $request->editEmail
		], [
			'editEmail' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editEmail))$data['email'] = $request->editEmail;
			if (!empty($request->editPassword))$data['password'] = md5($request->editPassword);
			if (isset($request->editStatus))$data['status'] = $request->editStatus;
			if (!empty($request->editName))$data['name'] = $request->editName;
			if (!empty($request->editAddress))$data['address'] = $request->editAddress;
			if (!empty($request->editPhone))$data['phone'] = $request->editPhone;
			if (!empty($request->editBirthday))$data['birthday'] = $request->editBirthday;
			if (!empty($request->privilege))$data['privilege'] = $request->privilege;
			$db = adminModel::updateUser($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function userView($id){
		$db = adminModel::userView($id);
		return response()->json($db);
	}
	public function userDelete($id){
		$db = adminModel::deleteUser($id);
		return $db;
	}

	// Car
	public function carIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter'])? str_slug($_GET['filter'],' '):'';
    $descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
    $status = isset($_GET['status']) ? $_GET['status'] : '1';

    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }

		$db = adminModel::car($getMax, $descasc, $page, $filter, '', $status);
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::car', compact('db','getMax','descasc','status'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
  
  public function recommendation(Request $request){
    try{
      $request = $request->all();
      $car_id = $request['car_id'];
      $negotiation_id = $request['negotiation_id'];
      $negotiations = Front_model::getNegotiation($negotiation_id);  

      $car = adminModel::carView($car_id);
      $price = $car->price;
      if(isset($negotiations[0])){
        $negotiation = $negotiations[0];
        $logistic = adminModel::portPrice('5763', $negotiation->port_destination_id, $negotiation->logistic_id);

        if(count($logistic)>0){
          $price += $logistic[0]->price;
        }

        if($negotiation->insurance == 1){
          $price += 50;   
        }
        if($negotiation->inspection == 1){
          $price += 350;
        }

        $arr = [
          'customer_id' => $negotiation->customer_id,
          'car_id' => $car_id,
          'price' => $price, 
          'currency' => $negotiation->currency,
          'insurance' => $negotiation->insurance,
          'inspection' => $negotiation->inspection, 
          'logistic_id' => $negotiation->logistic_id, 
          'user_id' => Session::get('user_id'),
          'destination_id' => $negotiation->port_destination_id,   
        ];

        $negotiation_id = Front_model::insertNegotiation($arr);

        //notification for old negotiation
        $data = array(
          'negotiation_id' => $negotiation->id,
          'chat' => 'New car recommendation for you, please click <a href="'.route('admin.negotiation.view',['id'=> $negotiation_id]).'">here</a>',
          'file' => '',
          'user_chat_id' => Session::get('user_id'),
        );       

        Front_model::insertNegotiationLine($data);
        
        //notification for new negotiation
        $data = array(
          'negotiation_id' => $negotiation_id,
          'chat' => 'Hi, there is a new recommendation car from us',
          'file' => '',
          'user_chat_id' => Session::get('user_id'),
        );       

        Front_model::insertNegotiationLine($data);
      
        $result = [
            'error' => 0,
            'negotiation_id' => $negotiation_id,
        ];
        return json_encode($result);
      }

      $result = [
        'error' => 1,
        'negotiation_id' => '',
      ];
      return json_encode($result);
    } catch (Exception $ex) {
      $result = [
        'error' => 1,
        'negotiation_id' => '',
        'message' => $ex,
      ];
      return json_encode($result);
    }
    
  }
  
  public function carList(Request $request){
    $optimus = [];
    $request = $request->all();
    $make = $request['make'];
    $model = $request['model'];
    $start_year = $request['start_year'];
    $end_year = $request['end_year'];
    $car_year = [$start_year,$end_year];

    
    $cars = Front_model::getAllCar('', '', false, '', '', array(1,2), $make, '', '', '', $model, '', $car_year, '', '');
    
    if(count($cars)>0){
      return [
          'total' => count($cars),
          'data' => $cars
        ];
    }else{
      return [];
    }
    
  }
    
  public function postUpload(Request $request){
    $file = $request->file('file');
    $imageName = rand(1,100).time().'.'.$request->file->getClientOriginalExtension();    
    $thumbnailPath = public_path('uploads/car/'.$imageName);
    $watermark = 'Optimus Auto Trading Pte Ltd';
    // $img = Image::make($file->getRealPath())->fit(1000, 1000);
    $img = Image::make($file->getRealPath());
    $width  = $img->width();
	$height = $img->height();
	$dimension_w = 800;
	$dimension_h = 600;
	$vertical   = (($width < $height) ? true : false);
	$horizontal = (($width > $height) ? true : false);
	$square     = (($width = $height) ? true : false);
	if ($vertical) {
	    $top = $bottom = 400;
	    $newHeight = ($dimension_w + $dimension_h) - ($bottom + $top);
	    $img->resize(null, $newHeight, function ($constraint) {
	        $constraint->aspectRatio();
	    });

	} else if ($horizontal) {
	    $right = $left = 300;
	    $newWidth = ($dimension_w + $dimension_h) - ($right + $left);
	    $img->resize($newWidth, null, function ($constraint) {
	        $constraint->aspectRatio();
	    });

	} else if ($square) {
	    $right = $left = 400;
	    $newWidth = ($dimension_w + $dimension_h) - ($left + $right);
	    $img->resize($newWidth, null, function ($constraint) {
	        $constraint->aspectRatio();
	    });

	}
	$img->resizeCanvas($dimension_w, $dimension_h, 'center', false, '#0000');
    $img->insert(public_path('uploads/watermark/optimus.png'), 'bottom-center', 0, 0);
    // $img->text($watermark, 450, 800, function($font) {
    //                 $font->size(30);
    //                 $font->file(public_path('assets/webfonts/HelveticaNeue.ttf'));
    //                 $font->color(array(255,255,255, 0.3));
    //                 $font->align('center');
    //                 $font->valign('top');
    //              });
    $img->save($thumbnailPath);
    return response()->json([
        'error'=>0,
        'filename'=>$imageName]);
  }

  public function deleteCarPicture(Request $request){
    $deleted_picture = $request->deleted_picture;
    $uploaded_picture = $request->uploaded_picture;
    $id = $request->id;
    $data = [
        'picture' => json_encode($uploaded_picture)
    ];
    $db = adminModel::updateCar($id, $data);
    
    if($db){
      unlink(public_path('uploads/car/'.$deleted_picture));
      return array('error' => 0, 'uploaded_picture'=> $uploaded_picture);
    }else{
      return array('error' => 1,'uploaded_picture'=> '');
    }
    

  }
  
  public function deleteUpload(Request $request)
  {
    if(file_exists(public_path('uploads/car/'.$request->id))){
      unlink(public_path('uploads/car/'.$request->id));
    }
    
    return response()->json(array('error' => 0,'filename' =>$request->id));
  }
    
	public function carSave(Request $request){
		$validator = Validator::make([
			'newMake' => $request->newMake,
      'newVin' => $request->newVin  
		], [
			'newMake' => 'required',
      'newVin' => 'required',
		]);
		if ($validator->passes()){			
			$data = [
				'make' => $request->newMake,
				'model' => $request->newModel,
				'vin' => $request->newVin,
				'serial' => $request->newSerial,
				'registration_date' => $request->registrationDate,
				'plate_number' => $request->plate_number,
				'motor_number' => $request->motor_number,
				'distance' => $request->newDistance,
				'type' => $request->newType,
				'colour' => $request->newColour,
				'engine' => $request->newEngine,
				'price' => $request->newPrice,
				'currency' => $request->newCurrencySymbol,
				'status' => $request->newStatus,
				'promotion' => $request->newPromotion,
				'state' => $request->newState,
				'fuel' => $request->newFuel,
				'steering' => $request->newSteering,
				'manufacture_year' => $request->newManufactureYear,
				'manufacture_month' => $request->newManufactureMonth,
				'type2' => $request->newType2,
				'exteriorcolor' => $request->newExteriorColor,
				'drive' => $request->newDrive,
				'transmission' => $request->newTransmission,
				'door' => $request->newDoor,
				'seat' => $request->newSeat,
				'options' => $request->newOptions,
				'description' => $request->newDescription,
				'remark' => $request->newRemark,
				'comment' => $request->newComment,
				'keyword' => $request->newKeyword,
				'seller' => $request->newSeller,
				'agent' => $request->newAgent,
				'buyer' => $request->newBuyer,
				'recommendation' => $request->newRecommendation,
				'bestdeal' => $request->newBestDeal,
				'bestseller' => $request->newBestSeller,
				'hotcar' => $request->newHotCar,
				'interiorcolor'=>$request->newInteriorColor,
				'dimension' => $request->newDimension,
				'weight' => $request->newWeight,
        'created_by' => Session::get('user_id'),  
        'youtube' => $request->newYoutube,  
        'accessories' => $request->newAccessories,  
				'picture' => $request->newPicture,
        'selling_point' => $request->newSellingPoint,  
			];
      
			$db = adminModel::saveCar($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
  
  public function checkChasis(Request $request){
    $params = $request->all();
    $duplicate = adminModel::car('all', '', '', '', '', '', $request->newVin);
    if(count($duplicate['data'])>0){
      return response()->json(['error' => "Duplicate chasis number"]);
    }
  }
  
  public function checkChasis2($vin){

    $duplicate = adminModel::car('all', '', '', '', '', '', $vin);
    return $duplicate;
    if(count($duplicate['data'])>0){
      return response()->json(['error' => "Duplicate chasis number"]);
    }
  }
  
	public function carUpdate($id, Request $request){
	$validator = Validator::make([
			'editMake' => $request->editMake,
      'editVin' => $request->editVin
		], [
			'editMake' => 'required',
			'editVin' => 'required'
		]);
		if ($validator->passes()) {
			 
			$data = [];
			if (!empty($request->editMake))$data['make_id'] = $request->editMake;
			if (!empty($request->editModel))$data['model_id'] = $request->editModel;
			if (!empty($request->editVin))$data['vin'] = $request->editVin;
			if (!empty($request->editSerial))$data['serial'] = $request->editSerial;
			if (!empty($request->registrationDate))$data['registration_date'] = $request->registrationDate;
			if (!empty($request->editDistance))$data['distance'] = $request->editDistance;
			if (!empty($request->editType))$data['type'] = $request->editType;
			if (!empty($request->editColour))$data['colour'] = $request->editColour;
			if (!empty($request->editEngine))$data['engine'] = $request->editEngine;
			if (!empty($request->editPrice))$data['price'] = $request->editPrice;
			if (!empty($request->editCurrencySymbol))$data['currency_symbol'] = $request->editCurrencySymbol;
			if (!empty($request->editStatus))$data['status'] = $request->editStatus;
			if (!empty($request->editPromotion))$data['promotion'] = $request->editPromotion;
			if (!empty($request->editState))$data['state'] = $request->editState;
			if (!empty($request->editFuel))$data['fuel'] = $request->editFuel;
			if (!empty($request->editSteering))$data['steering'] = $request->editSteering;
			if (!empty($request->editManufactureYear))$data['manufacture_year'] = $request->editManufactureYear;
			if (!empty($request->editType2))$data['type2'] = $request->editType2;
			if (!empty($request->editExteriorColor))$data['exterior_color'] = $request->editExteriorColor;
			if (!empty($request->editDrive))$data['drive'] = $request->editDrive;
			if (!empty($request->editTransmission))$data['transmission'] = $request->editTransmission;
			if (!empty($request->editDistance))$data['door'] = $request->editDoor;
			if (!empty($request->editSeat))$data['seat'] = $request->editSeat;
			if (!empty($request->editDescription))$data['description'] = $request->editDescription;
			if (!empty($request->editRemark))$data['remark'] = $request->editRemark;
			if (!empty($request->editComment))$data['comment'] = $request->editComment;
			if (!empty($request->editKeyword))$data['keyword'] = $request->editKeyword;
			if (!empty($request->editSeller))$data['seller'] = $request->editSeller;
			if (!empty($request->editAgent))$data['agent'] = $request->editAgent;
			if (!empty($request->editBuyer))$data['buyer'] = $request->editBuyer;
			if (!empty($request->editRecommendation))$data['recommendation'] = $request->editRecommendation;
			if (!empty($request->editBestDeal))$data['best_deal'] = $request->editBestDeal;
			if (!empty($request->editBestSeller))$data['best_seller'] = $request->editBestSeller;
			if (!empty($request->editHotCar))$data['hot_car'] = $request->editHotCar;
			if (!empty($request->editInteriorColor))$data['interior_color'] = $request->editInteriorColor;
			if (!empty($request->editDimension))$data['dimension'] = $request->editDimension;
			if (!empty($request->editWeight))$data['weight'] = $request->editWeight;
			if (!empty($request->plate_number))$data['plate_number'] = $request->plate_number;
			if (!empty($request->motor_number))$data['motor_number'] = $request->motor_number;
			if (!empty($request->editManufactureMonth))$data['manufacture_month'] = $request->editManufactureMonth;
			if (!empty($request->editYoutube))$data['youtube'] = $request->editYoutube;
			if (!empty($request->editAccessories))$data['accessories'] = $request->editAccessories;
			if (!empty($request->editSellingPoint))$data['selling_point'] = $request->editSellingPoint;
      if (!empty($request->editPicture))$data['picture'] = $request->editPicture;
     
      $db = adminModel::updateCar($id, $data);
      if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carView($id){
		$db = adminModel::carView($id);
		return response()->json($db);
	}
	public function carDelete($id){
		$db = adminModel::deleteCar($id);
		return $db;
	}
  
	public function carUnavailable($id){
    $ids = explode(',',$id);
    
    foreach($ids as $id){
      $db = adminModel::unavailableCar($id);
    }
		return $db;
	}

	public function carAvailable($id){
    $ids = explode(',',$id);
    
    foreach($ids as $id){
      $db = adminModel::AvailableCar($id);
    }
		return $db;
	}

  
  //car color
  public function carColorIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }

		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : "asc";		
    $filter = isset($_GET['filter'])?str_slug($_GET['filter'],' '):'';
    
    $db = adminModel::carColor($getMax, $descasc, $page, $filter);
		
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::carcolor', compact('db','getMax','descasc', 'page'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function carColorSave(Request $request){
		$validator = Validator::make([
			'color' => $request->color
		], [
			'color' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'color' => $request->color
			];
			$db = adminModel::saveCarColor($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carColorUpdate($id, Request $request){
		$validator = Validator::make([
			'color' => $request->color
		], [
			'color' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->id))$data['id'] = $request->id;
			if (!empty($request->color))$data['color'] = $request->color;
			$db = adminModel::updateCarColor($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carColorView($id){
		$db = adminModel::carColorView($id);
		return response()->json($db);
	}
	public function carColorDelete($id){
		$db = adminModel::deleteCarColor($id);
		return $db;
	}
  
  
  //car body type
  public function carTypeIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }

		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : "asc";		
    $filter = isset($_GET['filter'])?str_slug($_GET['filter'],' '):'';
    
    $db = adminModel::carType($getMax, $descasc, $page, $filter);
		
    
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::cartype', compact('db','getMax','descasc', 'page'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function carTypeSave(Request $request){
		$validator = Validator::make([
			'description' => $request->description
		], [
			'description' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'description' => $request->description
			];
			$db = adminModel::saveCarType($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carTypeUpdate($id, Request $request){
		$validator = Validator::make([
			'description' => $request->description
		], [
			'description' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->id))$data['id'] = $request->id;
			if (!empty($request->description))$data['description'] = $request->description;
			$db = adminModel::updateCarType($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carTypeView($id){
		$db = adminModel::carTypeView($id);
		return response()->json($db);
	}
	public function carTypeDelete($id){
		$db = adminModel::deleteCarType($id);
		return $db;
	}
  
  //accessories
  public function accessoriesIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }

		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : "asc";		
    $filter = isset($_GET['filter'])?str_slug($_GET['filter'],' '):'';
    
    $db = adminModel::accessories($getMax, $descasc, $page, $filter);
		
    
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::accessories', compact('db','getMax','descasc', 'page'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function accessoriesSave(Request $request){
		$validator = Validator::make([
			'description' => $request->description
		], [
			'description' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'description' => $request->description
			];
			$db = adminModel::saveAccessories($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function AccessoriesUpdate($id, Request $request){
		$validator = Validator::make([
			'description' => $request->description
		], [
			'description' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->id))$data['id'] = $request->id;
			if (!empty($request->description))$data['description'] = $request->description;
			$db = adminModel::updateAccessories($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function accessoriesView($id){
		$db = adminModel::accessoriesView($id);
		return response()->json($db);
	}
	public function accessoriesDelete($id){
		$db = adminModel::deleteAccessories($id);
		return $db;
	}
  
	/*Car Model*/
	public function carModelIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }

		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : "asc";		
    $filter = isset($_GET['filter'])?str_slug($_GET['filter'],' '):'';
    
    $db = adminModel::carModel($getMax, $descasc, $page, $filter);
		
    $this->optimus['make'] = adminModel::carMake('99999');
    
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::carmodel', compact('db','getMax','descasc', 'page'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
		
	}
	public function carModelSave(Request $request){
		$validator = Validator::make([
			'newModel' => $request->newModel
		], [
			'newModel' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'make_id' => $request->newMakeId, 
				'model' => $request->newModel
			];
			$db = adminModel::saveCarModel($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carModelUpdate($id, Request $request){
		$validator = Validator::make([
			'editModel' => $request->editModel
		], [
			'editModel' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editMakeId))$data['make_id'] = $request->editMakeId;
			if (!empty($request->editModel))$data['model'] = $request->editModel;
			$db = adminModel::updateCarModel($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carModelView($id){
		$db = adminModel::carModelView($id);
		return response()->json($db);
	}
	public function carModelDelete($id){
		$db = adminModel::deleteCarModel($id);
		return $db;
	}
	/*Car Make*/
	public function carMakeIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $db = adminModel::carMake($getMax, $descasc, $page, $filter);
		
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::carmake', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function carMakeSave(Request $request){
		$validator = Validator::make([
			'newMake' => $request->newMake
		], [
			'newMake' => 'required'
		]);
		if ($validator->passes()) {		    
			$img = '';
			if ($request->hasFile('newLogo')) {
				$image = $request->file('newLogo');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/car_logo');
				$image->move($destinationPath, $name);
				$img = time().'.'.$image->getClientOriginalExtension();
			}
			$data = [
				'make' => $request->newMake,
				'logo' => $img
			];
			$db = adminModel::saveCarMake($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carMakeUpdate($id, Request $request){
		$validator = Validator::make([
			'editMake' => $request->editMake
		], [
			'editMake' => 'required'
		]);
		if ($validator->passes()) {
			$img = '';
			if ($request->hasFile('editLogo')) {
				$image = $request->file('editLogo');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/car_logo');
				$image->move($destinationPath, $name);
				$img = time().'.'.$image->getClientOriginalExtension();
			}
			if (!empty($request->editMake))$data['make'] = $request->editMake;
			if (!empty($img))$data['logo'] = $img;
			$db = adminModel::updateCarMake($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carMakeView($id){
		$db = adminModel::carMakeView($id);
		return response()->json($db);
	}
	public function carMakeDelete($id){
		$db = adminModel::deleteCarMake($id);
		return $db;
	}


	/*Car Engine*/
	public function carEngineIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::carEngine($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::carengine', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function carEngineSave(Request $request){
		$validator = Validator::make([
			'newDescription' => $request->newDescription
		], [
			'newDescription' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'description' => $request->newDescription
			];
			$db = adminModel::saveCarEngine($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carEngineUpdate($id, Request $request){
		$validator = Validator::make([
			'editDescription' => $request->editDescription
		], [
			'editDescription' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editDescription))$data['description'] = $request->editDescription;
			$db = adminModel::updateCarEngine($id, $data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function carEngineView($id){
		$db = adminModel::carEngineView($id);
		return response()->json($db);
	}
	public function carEngineDelete($id){
		$db = adminModel::deleteCarEngine($id);
		return $db;
	}
	/*Negotiation*/
	public function negotiationIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    $filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
        
		$db = adminModel::negotiation($getMax, $descasc, $page, '',$filter);

    $this->optimus['make_array'] = Front_model::getAllMake();
    $this->optimus['page_type'] = 'negotiation_list';
    
    if(in_array(Session::get('login_type'),['admin','super admin','sales','super sales'])) {
      return view('admin::negotiation', compact('db','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
  public function orderlistIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    $filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
    
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $db = adminModel::negotiation($getMax, $descasc, $page, ['3','4','5','6','7','8'], $filter);
    $this->optimus['make_array'] = Front_model::getAllMake();
    $this->optimus['page_type'] = 'order_list';
    
		
    
    if(in_array(Session::get('login_type'),['admin','super admin','sales','super sales'])) {
      return view('admin::negotiation', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
  
	public function negotiationSave(Request $request){
		$validator = Validator::make([
			'newDescription' => $request->newDescription
		], [
			'newDescription' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'description' => $request->newDescription
			];
			$db = adminModel::saveNegotiation($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function negotiationUpdate($id, Request $request){
		$validator = Validator::make([
			'editUserId' => $request->editUserId,
		], [
			'editUserId' => 'required'
		]);
		if ($validator->passes()) {
			if (!empty($request->editUserId))$data['userId'] = $request->editUserId;
			if ($request->status != ''){
        $data['status'] = $request->status;
        
        if($data['status'] == '0'){
          $this->sendChat(
            $id,
            'Negotiation have been canceled',
            '',
            Session::get('user_id'),
            ''
            );
        }
      }
      $db = adminModel::updateNegotiation($id, $data);

      $data_car = [
      	'flag_payment' => 0,
      	'due_date' => null,
      	'status' => 1,
      	'id' => $request->car_id,
      ];
      adminModel::updateCarStatus($request->car_id, 1);
      Front_model::updateCarPayment($data_car);
      Front_model::updateCarDueDate($data_car);

		if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function negotiationView($id){
	if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.negotiation', ['id' => $id]);
    }
		$negotiations = Front_model::getNegotiation($id); 
    $negotiation = $negotiations[0]; 	
    
    $negotiation_line = Front_model::getNegotiationLine('',$negotiation->id);  
    
    $unread_ids = [];
    if(is_array($negotiation_line) && count($negotiation_line)>0){
      
      foreach($negotiation_line as $negotiation_line_detail){
        if($negotiation_line_detail->isread == 0) {
          array_push($unread_ids,$negotiation_line_detail->negotiation_line_id);
        }          
      }
      adminModel::updateAllNotification($negotiation->id);
    }
    
    $this->optimus['unread_message'] = $unread_ids;
    
    $invoices = Front_model::getInvoice('',$id);
    
    $this->optimus['config'] = API::getDefaultConfig();
    if(count($invoices)>0){      
      Session::set('invoice',$invoices[0]);
    }else{
      Session::forget('invoice');
    }
    
    $proforma_invoices = Front_model::getProformaInvoice('',$id);
    if(count($proforma_invoices)>0){      
      Session::set('proforma_invoice',$proforma_invoices[0]);
    }else{
      Session::forget('proforma_invoice');
    }
    
    Session::set('negotiation',$negotiation);
    Session::set('negotiation_line',$negotiation_line);
    
    $session = Session::get(null);
    $active_menu = 'dashboard';

		return view('admin::negotiationdetail', compact('session','active_menu','negotiation','negotiation_line'))->with($this->optimus);
	}
  
  public function negotiationChangeStatus($id='', $status=''){    
    $arr = [
      'id' => $id,
      'status' => $status,
    ];
    Front_model::updateNegotiationStatus($arr);
    return redirect()->back();
  }
  public function negotiationDeal(Request $request){  
    $session = Session::get(null);
    $param = $request->all();

    $arr = [
      'id' => $param['id'],
      'status' => $param['status'],
      'price' =>  $param['negotiation_price'],
      'shipping_price' =>  isset($param['shipping_price']) ? $param['shipping_price']: '', 
      'car_price' =>  isset($param['car_price'])?$param['car_price']:'', 
      'user_id' => Session::get('user_id'), 
    ];
    Front_model::updateNegotiationStatus($arr);
    
    Front_model::updateCarStatus(array('id'=>$session['negotiation']->car_id,'status'=>3));
    
    //notification for old negotiation
    $this->sendChat(
            $param['id'],
            'Your negotiation have been deal for USD '. API::currency_format($param['negotiation_price']),
            '',
            Session::get('user_id'),
            ''
            );
      
    return redirect()->back();
  }
  
  public function sendChat($negotiation_id = '', $chat = '', $file = '', $user_chat = '', $customer_id = ''){
    $data = array(
      'negotiation_id' => $negotiation_id,
      'chat' => $chat,
      'file' => $file,
    );       
    
    if($user_chat !== ''){
      $data['user_chat_id'] = $user_chat;
    }else{
      $data['customer_chat_id'] = $customer_id;
    }
    
    Front_model::insertNegotiationLine($data);
  }
  
  public function documentCopies(){
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.documentCopies');
    }
    $session = Session::get(null);
    $documents = Front_model::getDocumentCopy('', $session['invoice']->id); 
    if ($documents !== null) {
    	$document = [];
    	foreach ($documents as $key => $value) {
    		if ($value->category == 'bl') {
    			$document['bl'] = $value->filename;
    		}
    		if ($value->category == 'invoice') {
    			$document['invoice'] = $value->filename;
    		}
    		if ($value->category == 'registration_certificate') {
    			$document['registration_certificate'] = $value->filename;
    		}
    		if ($value->category == 'inspection_certificate') {
    			$document['inspection_certificate'] = $value->filename;
    		}
    		if ($value->category == 'marine_insurance') {
    			$document['marine_insurance'] = $value->filename;
    		}
    	}
    }

    $this->optimus['config'] = API::getDefaultConfig();
    return view('admin::document_copies', compact('session','document'))->with($this->optimus);
  }
  
  public function originalDocument(){
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.originalDocument');
    }
    $session = Session::get(null);
    $documents = adminModel::getDocumentOriginal($session['negotiation']->id);
    if(count($documents) == 1){
      $document = $documents[0];
    }else{
      $document = [];
    }

    $this->optimus['config'] = API::getDefaultConfig();
    return view('admin::original_document', compact('session','document'))->with($this->optimus);
  }
  
  public function documentOriginalSubmit(Request $request){
    $session = Session::get(null);
    $param = $request->all();
    $dataPath = '';
    if ($request->hasFile('filename')) {
      $dataAttachment = $request->file('filename');
      $name = time().'.'.$dataAttachment->getClientOriginalExtension();
      $destinationPath = public_path('/uploads/documents');
      $dataAttachment->move($destinationPath, $name);
      $dataPath = $name;
    }
    
    $arr = [
      'file' => $dataPath, 
      'negotiation_id' => Session::get('negotiation')->id,  
      'is_bl' => isset($param['bl'])? 1 : 0,  
      'is_invoice' => isset($param['invoice'])? 1 : 0,  
      'is_registration_certificate' => isset($param['registration_certificate'])? 1 : 0,  
      'is_inspection' => isset($param['inspection_certificate'])? 1 : 0,  
      'is_insurance' => isset($param['marine_insurance'])? 1 : 0,  
      'shipping_logistic' => isset($param['shipping']) ? $param['shipping'] : '',  
      'tracking_number' => isset($param['tracking_no'])? $param['tracking_no'] : '',  
      'comment' => isset($param['comment'])? $param['comment'] : '',  
    ];
    
    $db = adminModel::insertDocumentOriginal($arr);
    $this->updateStatus(7);
    
    if($session['negotiation']->status == '6'){
      $this->sendChat(
            $session['negotiation']->id,
            'Document Original have been submit, click <a href="'.route('admin.originalDocument') .'">here</a> to see the file.<br>Please confirm if you have been receive the car and give a review at <a href="'.route('admin.receivedItem',['invoice_id' => Session::get('negotiation')->id]).'">this link</a>',
            '',
            Session::get('user_id'),
            ''
            );
    }
    return redirect()->route('admin.negotiation.view',['id'=> $session['negotiation']->id]);
  }
  
  public function documentUpload(Request $request){
    $session = Session::get(null);
    // dd($session['negotiation']->id);
    
    // if ($request->hasFile('filename')) {
    //   $dataAttachment = $request->file('filename');
    //   $name = time().'.'.$dataAttachment->getClientOriginalExtension();
    //   $destinationPath = public_path('/uploads/documents');
    //   $dataAttachment->move($destinationPath, $name);
    //   $dataPath = $name;
    // }else{
    //   return redirect()->back()->withMessage('Please upload the file');
    // }
    if (!$request->hasFile('bl') && !$request->hasFile('invoice') && !$request->hasFile('registration_certificate') && !$request->hasFile('inspection_certificate') && !$request->hasFile('marine_insurance')) {
    	return redirect()->back()->withMessage('Please upload the file');
    }

    if ($request->hasFile('bl')) {
      $dataAttachment = $request->file('bl');
      $name = 'bl_'.$session['negotiation']->id.'.'.$dataAttachment->getClientOriginalExtension();
      $destinationPath = public_path('/uploads/documents');
      $dataAttachment->move($destinationPath, $name);
      $dataPath = $name;
	  $arr = [
	    'filename' => $dataPath, 
	    'invoice_id' => Session::get('invoice')->id,
	    'category' => 'bl',  
	  ];
	  $db = adminModel::insertDocumentCopy($arr);
  	}

  	if ($request->hasFile('invoice')) {
      $dataAttachment = $request->file('invoice');
      $name = 'inv_'.$session['negotiation']->id.'.'.$dataAttachment->getClientOriginalExtension();
      $destinationPath = public_path('/uploads/documents');
      $dataAttachment->move($destinationPath, $name);
      $dataPath = $name;
	  $arr = [
	    'filename' => $dataPath, 
	    'invoice_id' => Session::get('invoice')->id,
	    'category' => 'invoice',  
	  ];
	  $db = adminModel::insertDocumentCopy($arr);
  	}

  	if ($request->hasFile('registration_certificate')) {
      $dataAttachment = $request->file('registration_certificate');
      $name = 'rc_'.$session['negotiation']->id.'.'.$dataAttachment->getClientOriginalExtension();
      $destinationPath = public_path('/uploads/documents');
      $dataAttachment->move($destinationPath, $name);
      $dataPath = $name;
	  $arr = [
	    'filename' => $dataPath, 
	    'invoice_id' => Session::get('invoice')->id,
	    'category' => 'registration_certificate',  
	  ];
	  $db = adminModel::insertDocumentCopy($arr);
  	}

  	if ($request->hasFile('inspection_certificate')) {
      $dataAttachment = $request->file('inspection_certificate');
      $name = 'ic_'.$session['negotiation']->id.'.'.$dataAttachment->getClientOriginalExtension();
      $destinationPath = public_path('/uploads/documents');
      $dataAttachment->move($destinationPath, $name);
      $dataPath = $name;
	  $arr = [
	    'filename' => $dataPath, 
	    'invoice_id' => Session::get('invoice')->id,
	    'category' => 'inspection_certificate',  
	  ];
	  $db = adminModel::insertDocumentCopy($arr);
  	}

  	if ($request->hasFile('marine_insurance')) {
      $dataAttachment = $request->file('marine_insurance');
      $name = 'mi_'.$session['negotiation']->id.'.'.$dataAttachment->getClientOriginalExtension();
      $destinationPath = public_path('/uploads/documents');
      $dataAttachment->move($destinationPath, $name);
      $dataPath = $name;
	  $arr = [
	    'filename' => $dataPath, 
	    'invoice_id' => Session::get('invoice')->id,
	    'category' => 'marine_insurance',  
	  ];
	  $db = adminModel::insertDocumentCopy($arr);
  	}
    
    $this->updateStatus(6);
    
    if($session['negotiation']->status == '5'){
      $this->sendChat(
            $session['negotiation']->id,
            'Document have been submit, click <a href="'. route('admin.documentCopies') .'">here</a> to see the file<br>'
              . 'Check the original document at <a href="'. route('admin.originalDocument') .'">here</a>',
            '',
            Session::get('user_id'),
            ''
            );
    }
    return redirect()->route('admin.negotiation.view',['id'=> $session['negotiation']->id]);
  }
  
	public function negotiationViewSales($id){
		$db = adminModel::negotiationViewSales($id);
		return response()->json($db);
	}
	public function negotiationReplyChat(Request $request, $id, $customerId){
		$validator = Validator::make([
			'messageChat' => $request->messageChat,
			'attachFile' => $request->attachFile
		], [
			'messageChat' => 'required',
			'attachFile' => 'mimes:jpeg,bmp,png,tiff,zip,pdf,xls,xlsx,doc,docx|max:5120'
		]);
		if ($validator->passes()) {
			$dataPath = '';
			if ($request->hasFile('attachFile')) {
				$dataAttachment = $request->file('attachFile');
				$name = time().'.'.$dataAttachment->getClientOriginalExtension();
				$destinationPath = public_path('/uploads/negotiation');
				$dataAttachment->move($destinationPath, $name);
				$dataPath = $name;
			}
			$user = adminModel::checkSession(Session::get('email'));
			$customer = adminModel::checkCustomerById($customerId);
			$data = [
				'chat' => $request->messageChat,
				'user_chat_id' => $user->id,
				'file' => $dataPath
			];
			$db = adminModel::negotiationReplyChat($id, $data);
			$this->emailSmtp($request->messageChat, $customer->email, $user->name);
			return redirect()->back();
		}
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
	}

	/*Invoice*/
	public function invoiceIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
		$status = isset($_GET['status']) ? $_GET['status'] : '';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$this->optimus['db'] = adminModel::paymentInvoiceGroup($getMax, $descasc, $page, '', $status, $filter);
    $this->optimus['invoice'] = [];
    $invoice_list = [];
    if(count($this->optimus['db']['data'])>0){
      foreach($this->optimus['db']['data'] as $detail){
        array_push($invoice_list, $detail->id);
        $this->optimus['invoice'][$detail->id]['data'] = $detail;
      }
    }
    $this->optimus['invoice_detail'] = adminModel::paymentInvoiceDetail($invoice_list);
    if(count($this->optimus['invoice_detail'])>0){
      foreach($this->optimus['invoice_detail'] as $detail){
        $this->optimus['invoice'][$detail->id]['invoice'][] = $detail;
      }
    }

		if(in_array(Session::get('login_type'),['admin','super admin', 'finance'])) {
			return view('admin::invoice', compact('getMax','descasc','status'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
	public function invoiceSave(Request $request){
		$validator = Validator::make([
			'newDescription' => $request->newDescription
		], [
			'newDescription' => 'required'
		]);
		if ($validator->passes()) {		    
			$data = [
				'description' => $request->newDescription
			];
			$db = adminModel::saveInvoice($data);
			if($db === "Duplicate Entry") return response()->json(['error' => "Duplicate Entry"]);
			return response()->json($db);
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
	public function invoiceUpdate(Request $request){
		$session = Session::get(null);
		$validator = Validator::make([
			'editStatus' => $request->editStatus
		], [
			'editStatus' => 'required'
		]);
    
		if ($validator->passes()) {
			if (!empty($request->editStatus))$data['status'] = $request->editStatus;
			if (!empty($request->payment_id))$data['payment_id'] = $request->payment_id;
      
			$db = adminModel::updateInvoicePayment($data);
      
      $invoices = adminModel::paymentInvoiceGroup('all', '', '', $data['payment_id']);
      $invoice = $invoices['data'][0];      
      $total_amount = $invoice->total_amount;
      $negotiation_id = $invoice->negotiation_id;
      
      $payment_detail = adminModel::paymentInvoiceDetail([$invoice->id]);
      
      $total_payment = 0;
      foreach($payment_detail as $detail){
        if($detail->payment_status == 1){
          $total_payment += $detail->total_payment;
        }
      }
      
      if($total_payment >= $total_amount){
        adminModel::updateInvoice($invoice->id,array('status'=>3));
        $negotiation = AdminModel::negotiationView($negotiation_id);
        
        //update car status
        $car_id = $negotiation[0]->car_id;
        AdminModel::updateCarStatus($car_id, 2);
        unset($session['recently_view'][$car_id]);
        
        $message = 'Invoice have been Paid';
        
        if($data['status'] == '2'){
          //reject
          $text = 'Invoice payment has been rejected.<br> Please input next payment using <a href='.route('admin.paymentConfirmation',['invoice_id'=>$invoice->id]).'>this link</a> before : '. date('d M Y', strtotime($invoice->due_date));
        }
        else{
          $text = 'Invoice payment have been confirm and have been completely paid. We will ship your car soon and inform the shipping.<br>You can track your shipment at <a href="'.route('admin.trackInvoice').'">this link</a>';
        }
        
        $this->sendChat(
            $negotiation_id,
            $text,
            '',
            Session::get('user_id'),
            ''
            );
        
      }
      else{
        adminModel::updateInvoice($invoice->id,array('status'=>2));
        $negotiation = AdminModel::negotiationView($negotiation_id);
        
        //update car status
        $car_id = $negotiation[0]->car_id;
        AdminModel::updateCarStatus($car_id, 2);
        unset($session['recently_view'][$car_id]);
        
        $message = 'Invoice have been partial Paid';
      
        if($data['status'] == '2'){
          //reject
          $text = 'Invoice payment has been rejected.<br> Please input next payment using <a href='.route('admin.paymentConfirmation',['invoice_id'=>$invoice->id]).'>this link</a> before : '. date('d M Y', strtotime($invoice->due_date));
        }else{
          $text = 'Invoice payment have been confirm and have been completely paid. Balance leave : USD '. number_format(($total_amount - $total_payment),0,'.',',') .' We will waiting for your next payment.<br> Please input next payment using <a href='.route('admin.paymentConfirmation',['invoice_id'=>$invoice->id]).'>this link</a> before : '. date('d M Y', strtotime($invoice->due_date));
        }
        
        $this->sendChat(
            $negotiation_id,
            $text,
            '',
            Session::get('user_id'),
            ''
            );
        
      }

      $arr = [
        'error' => 0,
        'message' => $message,
      ];
      return response()->json($arr);
			
		}
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()]);
		}
	}
  
	public function reviewIndex(){
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::getAllReview($getMax, $descasc, $page, $filter);
    if(in_array(Session::get('login_type'),['admin','super admin'])) {
      return view('admin::reviewlist', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
	}
  
  public function reviewUpdate(Request $request){
    $param = $request->all();
    
    $result = adminModel::updateReviewStatus($param['invoice_id'], $param['status']);
    
    return json_encode($result);
  }
  
  public function invoiceView($id){
		$db['invoice'] = adminModel::invoiceView($id);    
		$db['payment'] = adminModel::paymentInvoiceDetail([$id]);    
		return response()->json($db);
	}
  
	public function invoiceDelete($id){
		$db = adminModel::deleteInvoice($id);
		return $db;
	}
  
  public function createProformaInvoice($negotiation_id){
    $session = Session::get(null);
    $invoices = Front_model::getProformaInvoice('',$negotiation_id);
    $destination_port = Front_model::getDestinationPort($session['negotiation']->port_destination_id);
    
    if(count($invoices)>0){
      $invoice_id = $invoices[0]->id;
    }else{    
      $today = date('Y-m-d');
      $transaction_date_arr = explode('-',$today);
      $SOnbr= "INV-PRO".$transaction_date_arr['0'].$transaction_date_arr['1'];
      $last_nbr = Front_model::getLastAutoNumber($SOnbr, 'proforma_invoice_number', 'proforma_invoice');
      if(count($last_nbr)== 0){
          $SOnbr=$SOnbr."0001";
      }else{
          $last_nbr_db = $last_nbr[0]->proforma_invoice_number;

          $jml=strlen($last_nbr_db);
          if($jml=="1"){
          $SOnbr=$SOnbr."000".$last_nbr_db;
          }else if($jml=="2"){
          $SOnbr=$SOnbr."00".$last_nbr_db;
          }else if($jml=="3"){
          $SOnbr=$SOnbr."0".$last_nbr_db;
          }else if($jml=="4"){
          $SOnbr=$SOnbr.$last_nbr_db;
          }            
      }
      
      $shipping_price = isset($destination_port[0]) && ($destination_port[0]->price != null || $destination_port[0]->price != 0) ? $destination_port[0]->price : $session['negotiation']->shipping_price;
            
      $params = [
        'proforma_invoice_number' => $SOnbr, 
        'negotiation_id' => $negotiation_id,
        'created_by'  => Session::get('name'),  
        'currency' => $session['negotiation']->currency,
        'shipping_price' => $shipping_price,
        'total_amount' => $session['negotiation']->price,
        'car_id' => $session['negotiation']->car_id,  
        'port_destination' => $session['negotiation']->port_destination_id,   
        'inspection' => $session['negotiation']->inspection,   
      ];
      $invoice_id = Front_model::insertProformaInvoice($params);
      $arr = [
        'status' => 2,  
        'id' => $negotiation_id,  
      ];      
      
      if($shipping_price>0){
        $arr['shipping_price'] = $shipping_price;
      }
      
            
      Front_model::updateNegotiationStatus($arr);
      
      //notification for old negotiation
      $data = array(
        'negotiation_id' => $negotiation_id,
        'chat' => 'Your proforma Invoice have been deal and will be create for your proforma invoice, please click <a href="'.route('admin.proformainvoice',['id'=> $invoice_id]).'">here</a> to see your updated proforma invoice',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );       

      Front_model::insertNegotiationLine($data);
        
      $this->updateStatus(2);
    }
    
    return redirect()->route('admin.proformainvoice',['id'=>$invoice_id]);        
  }
  
  public function proformainvoice($id){
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.proformainvoice',['id'=>$id]);
    }
    $invoices = Front_model::getProformaInvoice($id);
    $cars = Front_model::getAllCar([Session::get('negotiation')->car_id]);
    $countries = Front_model::getAllCountry();    
    $destination = Front_model::getDestinationPort($invoices[0]->port_destination);
    $destination_port = '';
    $this->optimus['config'] = API::getDefaultConfig();
    if(count($destination)>0){
      $destination_port = $destination[0];      
    }
    Session::set('destination_port',$destination_port);
    Session::set('proforma_invoice',$invoices[0]);
    $session = Session::get(null);
    
    if(count($invoices)>0){
      $invoice = $invoices[0];
    }else{
      return redirect()->route('admin.negotiation');
    }
    
    if(count($cars)>0){
      $car = $cars[0];
    }
    
    $destination_country = Front_model::getAllDestinationCountry();
    $dst_country = [];
    foreach($destination_country as $detail){
      $dst_country[$detail->country_code] = $detail;
    }
        
    $destination_ports = Front_model::getDestinationPort('','');
    $dst_port = [];
    foreach($destination_ports as $detail){
      $dst_port[$detail->id] = $detail;
    }
    
    $departure_country = Front_model::getAllDepartureCountry();
    $dpt_country = [];
    foreach($departure_country as $detail){
      $dpt_country[$detail->country_code] = $detail;
    }
    
    $departure_ports = Front_model::getDeparturePort('','SG');
    $dpt_port = [];
    foreach($departure_ports as $detail){
      $dpt_port[$detail->id] = $detail;
    }
    
    $logistic =[
      'destination_country' => $dst_country,
      'destination_port' => $dst_port,
      'departure_country' => $dpt_country,  
      'departure_port' => $dpt_port,  
    ];

    $this->optimus['proformance_invoice_log'] = Front_model::getProformanceLog($id);
    
    return view('admin::proforma_invoice', compact('session','invoice','car','countries','logistic'))->with($this->optimus);     
  }

	public function cancelProformaInvoice($invoice_id){
    $session = Session::get(null);
    $proforma_invoice = $session['proforma_invoice'];

    if($proforma_invoice !== ''){
      $arr = [
          'id' => $proforma_invoice->id,
          'company_name' => $proforma_invoice->company_name,
          'address' => $proforma_invoice->address,
          'city' => $proforma_invoice->city,
          'telephone' => $proforma_invoice->telephone,
          'fax' => $proforma_invoice->fax,
          'car_width'=> $proforma_invoice->car_width,
          'car_height'=> $proforma_invoice->car_height,
          'car_length'=> $proforma_invoice->car_length,     
          'port_departure' => $proforma_invoice->port_departure, 
          'port_destination' => $proforma_invoice->port_destination, 
          'country_code' => $proforma_invoice->country_code, 
          'total_amount' => $proforma_invoice->total_amount,
          'currency' => $proforma_invoice->currency,
          'inspection' => $proforma_invoice->inspection,
          'inspection_value' => $proforma_invoice->inspection_value,
          'detail' => $proforma_invoice->detail,
          'incoterm' => $proforma_invoice->incoterm,
          'sales_agreement' => $proforma_invoice->sales_agreement,
          'payment_due' => $proforma_invoice->payment_due,
          'status' => 1,          
          'approval' => 0,
          'customer_approval' => 0,
      ];
    }
    
    
    $result = Front_model::updateProformaInvoice($arr);
    
    $data = array(
          'negotiation_id' => $session['negotiation']->id,
          'chat' => 'Proforma Invoice have been rejected  please click <a href="'.route('admin.proformainvoice',['id'=> $proforma_invoice->id]).'">here</a>',
          'file' => '',
          'user_chat_id' => Session::get('user_id'),
    );

    Front_model::insertNegotiationLine($data);
    
    return redirect()->route('admin.negotiation.view',['id'=> $session['negotiation']->id]);
  }
  
  public function saveProformaInvoice(Request $request){
    $param = $request->all();
    
    $negotiation = Session::get('negotiation');
    $detail = array();
    if(count($param['item_name'])>0){
      $i = 0;
      foreach($param['item_name'] as $item){
        if($item === ''){
          continue;
        }
        $detail[] = [
            'item_name' => $item ,
            'item_price' => $param['item_price'][$i],
        ];
        
        $i++;
      }
    }

    $car_volume = 0;
    $cars = Front_model::getAllCar([Session::get('negotiation')->car_id]);
    if(count($cars)>0){
      $car = $cars[0];
      $dimension = $car->dimension;
      $dimension_arr = explode(',',$dimension);
      
      if(count($dimension_arr)== 3){
        $car_volume = (int)$dimension_arr[0] * (int)$dimension_arr[1] * (int)$dimension_arr[2] / 1000000;
      }
    }
    
    
    
    $destination_ports = Front_model::getDestinationPort($param['destination_port']);
    
    $arr_negotiation = [
      'status' => 2,  
      'id' => $negotiation->id,  
    ];
    
    $insurance_fee = $inspection_fee = $shipping_price =  $total_price = $arr_negotiation['shipping_price'] = $arr_negotiation['inspection_fee'] = $arr_negotiation['insurance_fee'] = $arr_negotiation['inspection'] = $arr_negotiation['insurance'] = 0;
    
    $total_price = isset($param['total_amount']) ? $param['total_amount'] : 0;      
    
    if(isset($param['total_amount']) && $param['total_amount'] > 0){
      $total_price = $arr_negotiation['price'] = $param['total_amount'];
    }else{
      $total_price = $arr_negotiation['price'] = $negotiation->price;
    }
    
    if(isset($destination_ports[0]->volume_price) && $destination_ports[0]->volume_price != null && $destination_ports[0]->volume_price >0 && $car_volume != 0){
      $shipping_price = $arr_negotiation['shipping_price'] = isset($destination_ports[0])?$destination_ports[0]->volume_price * $car_volume:0;
    }
    elseif(isset($destination_ports[0]->price) && $destination_ports[0]->price >0){
      $shipping_price = $arr_negotiation['shipping_price'] = isset($destination_ports[0])?$destination_ports[0]->price:0;
    }else{
      $shipping_price = 0;
    }

    
    if($param['incoterms'] == 'CIF'){
      if(isset($param['inspection']) && $param['inspection'] == 1){
        $inspection_fee = $arr_negotiation['inspection_fee'] = API::getSetting('inspection');
        $arr_negotiation['inspection'] = 1;
      }
      elseif(isset($negotiation->inspection) && $negotiation->inspection == 1){
        $inspection_fee = $arr_negotiation['inspection_fee'] = $negotiation->inspection_fee;
        $arr_negotiation['inspection'] = 1;
      }
      $insurance_fee = $arr_negotiation['insurance_fee'] = API::getSetting('insurance');
      $arr_negotiation['insurance'] = 1;
      $car_price = $total_price - $shipping_price - $inspection_fee - $insurance_fee;
      
    }elseif($param['incoterms'] == 'C&F'){
      if(isset($param['inspection']) && $param['inspection'] == 1){
        $inspection_fee = $arr_negotiation['inspection_fee'] = API::getSetting('inspection');
        $arr_negotiation['inspection'] = 1;        
      }
      elseif(isset($negotiation->inspection) && $negotiation->inspection == 1){
        $inspection_fee = $arr_negotiation['inspection_fee'] = $negotiation->inspection_fee;
        $arr_negotiation['inspection'] = 1;
      }
      $arr_negotiation['insurance'] = 0;
      $arr_negotiation['insurance_fee'] = 0;
      $car_price = $total_price - $shipping_price - $inspection_fee;
      
    }elseif($param['incoterms'] == 'FOB'){      
      $shipping_price = $arr_negotiation['shipping_price'] = 0;
      $arr_negotiation['insurance'] = 0;
      $arr_negotiation['insurance_fee'] = 0;
      $car_price = $total_price;
    }else{
      return redirect()->back()->with('message','Incoterm required');
    }
    
    $arr = [
        'id' => $param['id'],
        'company_name' => isset($param['company_name'])?$param['company_name']:'',
        'address' => isset($param['address'])?$param['address']:'',
        'city' => isset($param['city'])?$param['city']:'',
        'telephone' => isset($param['telephone'])?$param['telephone']:'',
        'fax' => isset($param['fax'])?$param['fax']:'',
        'car_width'=> isset($param['width'])?$param['width']:'',
        'car_height'=> isset($param['height'])?$param['height']:'',
        'car_length'=> isset($param['length'])?$param['length']:'',     
        'port_departure' => isset($param['departure_port'])?$param['departure_port']:'', 
        'port_destination' => isset($param['destination_port'])?$param['destination_port']:'', 
        'country_code' => isset($param['country'])?$param['country']:'', 
        'total_amount' => $total_price,
        'shipping_price' => $shipping_price,
        'currency' => isset($param['currency'])?$param['currency']:'',
        'inspection' => isset($param['inspection'])?$param['inspection']:'',
        'detail' => json_encode($detail),
        'incoterm' => isset($param['incoterms'])?$param['incoterms']:'',
        'sales_agreement' => isset($param['sales_agreement'])?$param['sales_agreement']:'',
        'status' => isset($param['status'])?$param['status']:2,
        'inspection_value' => isset($param['inspection_value'])?$param['inspection_value']:'',
        'payment_due' => isset($param['due_date'])?$param['due_date']:'',
        'approval' => 1,
        'customer_approval' => 0,
    ];
    
    $result = Front_model::updateProformaInvoice($arr);
    $result = Front_model::saveProformanceInvoiceLog($arr);
    
    
    
    $arr_negotiation['car_price'] = $car_price;
    
    //update negotiation
    Front_model::updateNegotiationStatus($arr_negotiation);
    //update negotiation
    
    
    //notification for old negotiation
    $data = array(
      'negotiation_id' => $negotiation->id,
      'chat' => 'Your proforma Invoice have been updated, please click <a href="'.route('admin.proformainvoice',['id'=> $param['id']]).'">here</a> to see your updated proforma invoice',
      'file' => '',
      'user_chat_id' => Session::get('user_id'),
    );       

    Front_model::insertNegotiationLine($data);
      
    return redirect()->route('admin.negotiation.view',['id'=> $negotiation->id])->with('message','Proforma Invoice have been updated');
  }
  
  public function approveProformaInvoice(Request $request){
    $param = $request->all();
 	$array = json_decode(json_encode(Session::get('proforma_invoice')), true);
 	$new_arr = array_merge($array,['due_date'=>$param['due_date']]);
    // print_r($new_arr);die();
 	Session::set('proforma_invoice', (object)$new_arr);

    if($param['user_type'] == 'customer'){
      $data = [
          'id' => $param['id'],
          'payment_due' => $param['due_date'],
          'customer_approval' => 1,
      ];
      $data_chat = array(
        'negotiation_id' => $param['negotiation_id'],
        'chat' => 'Your proforma Invoice have been Approved, please click <a href="'.route('report.proformaInvoice').'" target="_blank">here</a> to see and download your proforma invoice.',
        'file' => '',
        'customer_chat_id' => Session::get('user_id'),
      );       

    }else{
      $data = [
          'id' => $param['id'],
          'payment_due' => $param['due_date'],
          'approval' => 1,
      ];
      
      $data_chat = array(
        'negotiation_id' => $param['negotiation_id'],
        'chat' => 'Your proforma Invoice have been Approved, please click <a href="'.route('report.proformaInvoice').'" target="_blank">here</a> to see and download your proforma invoice',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );
    }
    // print_r(Session::get('proforma_invoice'));
    // print_r($param);
    // die();
    Front_model::approveProformaInvoice($data);
    Front_model::insertNegotiationLine($data_chat);
    
    return $param;
  }
  
  public function createInvoice(){
    $session = Session::get(null);
    // print_r($session);die();
    $invoices = Front_model::getInvoice('',$session['negotiation']->id);
    $consignee_destinaton_countries = Front_model::getDestinationPort($session['proforma_invoice']->port_destination);

    if(count($consignee_destinaton_countries)>0){
      $consignee_destinaton_country = $consignee_destinaton_countries[0];
    }
    
    if(count($invoices)>0){
      $invoice_id = $invoices[0]->id;
    }else{    
      $proforma_invoices = Front_model::getProformaInvoice($session['proforma_invoice']->id);
      if(count($proforma_invoices) > 0){
        $proforma_invoice = $proforma_invoices[0];
        $SOnbr = str_replace('PRO','',$proforma_invoice->proforma_invoice_number);

        $params = [
          'invoice_number' => $SOnbr, 
          'created_by'  => Session::get('name'),
          'negotiation_id' => $session['negotiation']->id,  
          'consignee_country' => isset($consignee_destinaton_country->country_code)?$consignee_destinaton_country->country_code:'',  
          'final_destination' => isset($consignee_destinaton_country->port_name)?$consignee_destinaton_country->port_name:'',
          'total_amount' => $session['proforma_invoice']->total_amount, 
          'currency' => $session['proforma_invoice']->currency, 
          'proforma_invoice_id' =>  $session['proforma_invoice']->id,
          'due_date'=> $session['proforma_invoice']->due_date,
          'status' => null,  
        ];
        $invoice_id = Front_model::insertInvoice($params);
        $this->updateStatus(3);
        

        $data_chat = array(
          'negotiation_id' => $session['negotiation']->id,
          'chat' => 'Please fill data for negotiation invoice at <a href="'.route('admin.negotiationInvoice',['id'=>$invoice_id]).'">here</a>',
          'file' => '',
          'user_chat_id' => Session::get('user_id'),
        );       
        Front_model::insertNegotiationLine($data_chat);
        
      }else{
        return redirect()->route('admin.negotiationlist'); 
      }
    }
    
    return redirect()->route('admin.negotiation.view',['id'=> $session['negotiation']->id])->with('message','Invoice has been created');       
  }
  
  public function invoice($id){
    
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.invoice',['id'=>$id]);
    }
    
    $session = Session::get(null);
    $invoices = Front_model::getInvoice($id);
    
    if(count($invoices) == 0){
      echo 'invoice is not available';
      return;
    }
    
    $proforma_invoice = Front_model::getProformaInvoice($invoices[0]->proforma_invoice_id);
    $session['proforma_invoice'] = $proforma_invoice[0];    
    $countries = Front_model::getAllCountry();
    $car = array();
    $this->optimus['config'] = API::getDefaultConfig();
    $cars = Front_model::getAllCar([Session::get('negotiation')->car_id]);
    $this->optimus['accounts'] = Front_model::getAllCustomer($session['negotiation']->customer_id);
   
    if(count($cars)>0){
      $car = $cars[0];
    }
    $logistic =[
      'destination_country' => Front_model::getAllDestinationCountry(),
      'destination_port' => Front_model::getDestinationPort('','SG'),
      'departure_country' => Front_model::getAllDepartureCountry(),  
      'departure_port' => Front_model::getDeparturePort('','SG'),  
    ];
    
    
    
    if(count($invoices)>0){
      $invoice = $invoices[0];
      $departure_port_details = Front_model::getDeparturePort($session['proforma_invoice']->port_departure);
      $invoice->departure_port_detail = isset($departure_port_details[0])?$departure_port_details[0]:[];
      $destination_port_details = Front_model::getDestinationPort($session['proforma_invoice']->port_destination);
      $invoice->destination_port_detail = isset($destination_port_details[0])?$destination_port_details[0]:[];
      Session::put('invoice', $invoice);
    }else{
      return redirect()->route('admin.negotiation');
    }
    $session = Session::get(null);

    return view('admin::negotiation_invoice', compact('session','invoice','countries','logistic','car'))->with($this->optimus);     
  }
  
  
  function extendDate(Request $request){
    try{
      $param = $request->all();
      $invoices = Front_model::getInvoice($param['id']);
      
      $arr = [
          'id' => $param['id'],
          'due_date' => $param['due_date'],
      ];


      $result = Front_model::updateInvoice($arr);

      $car = [
      	'due_date' => $param['due_date'],
      	'id' => $param['car_id'],
      ];
      Front_model::updateCarDueDate($car);

      $data_chat = array(
        'negotiation_id' => $invoices[0]->negotiation_id,
        'chat' => 'Due Date have been Update',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );
      Front_model::insertNegotiationLine($data_chat);

      return array('error'=>0,'message'=>'Data haved been updated');
    } catch (Exception $ex) {
      return array('error'=>0,'message'=> $ex);
    }
    
  }
  
  
  function updateInvoice(Request $request){
    $param = $request->all();
    $negotiation = Session::get('negotiation');
    $invoice = Session::get('invoice');
    
    $other_data = [];
    $other_data['other_name'] = isset($param['other_name'])?$param['other_name']:'';
    $other_data['other_address'] = isset($param['other_address'])?$param['other_address']:'';
    $other_data['other_city'] = isset($param['other_city'])?$param['other_city']:'';
    $other_data['other_province'] = isset($param['other_province'])?$param['other_province']:'';
    $other_data['other_zip'] = isset($param['other_zip'])?$param['other_zip']:'';
    $other_data['other_tel'] = isset($param['other_tel'])?$param['other_tel']:'';
    $other_data['other_fax'] = isset($param['other_fax'])?$param['other_fax']:'';
    $other_data['other_country'] = isset($param['other_country'])?$param['other_country']:'';
    $action = isset($param['action'])?$param['action']:'';
    
    $arr = [
        'id' => $param['id'],
        'consignee_country' => isset($param['consignee_country'])?$param['consignee_country']:'',
        'final_destination' => isset($param['final_destination'])?$param['final_destination']:'',
        'name' => isset($param['consignee_name'])?$param['consignee_name']:'',
        'street_address' => isset($param['address'])?$param['address']:'',
        'city' => isset($param['city'])?$param['city']:'',
        'province'=> isset($param['province'])?$param['province']:'',
        'zip'=> isset($param['zip'])?$param['zip']:'',
        'country'=> isset($param['country'])?$param['country']:'',     
        'telephone' => isset($param['tel'])?$param['tel']:'', 
        'fax' => isset($param['fax'])?$param['fax']:'', 
        'country' => isset($param['country'])?$param['country']:'', 
        'notify_party' => isset($param['notify_party'])?$param['notify_party']:'',
        'remitter_name' => isset($param['remitter_name'])?$param['remitter_name']:'',
        'status' => isset($param['status'])?$param['status']:0,
        'other_data' => json_encode($other_data),
    ];
    
    
    $result = Front_model::updateInvoice($arr);
    $invoices = Front_model::getInvoice($param['id']);
    if(count($invoices)>0){
      Session::set('invoice',$invoices[0]);
    }
    
    if($arr['status'] == 1){
      $data_chat = array(
        'negotiation_id' => $negotiation->id,
        'chat' => 'Your Invoice have been Create, please click <a href="'.route('admin.negotiationInvoice',['id'=>$param['id']]).'">here</a> to see and download your invoice.<br>Please do payment by click <a href='.route('admin.paymentConfirmation',['invoice_id'=>$param['id']]).'">this link</a>',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );
      Front_model::insertNegotiationLine($data_chat);

      $data_car = [
        'flag_payment' => 1,
        'id' => $negotiation->car_id,
      ];
      Front_model::updateCarPayment($data_car);

      $data_car = [
      	'due_date' => $invoice->due_date,
      	'id' => $negotiation->car_id,
      ];
      Front_model::updateCarDueDate($data_car);

    }
    $link = '';
    if($action == 'cancel'){
      $data_chat = array(
        'negotiation_id' => $negotiation->id,
        'chat' => 'Your Invoice have been Cancel',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );
      Front_model::insertNegotiationLine($data_chat);
      $link = route('admin.negotiation.view',['id'=> $negotiation->id]);
    }
    
    return array('error'=>0,'message'=>'Data haved been updated','link' => $link, 'data'=> $arr);
  }
  
  function paymentConfirmation($invoice_id){
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.paymentConfirmation',['invoice_id'=>$invoice_id]);
    }
    $session = Session::get(null);
    $invoices = Front_model::getInvoice($invoice_id);    
    $this->optimus['config'] = API::getDefaultConfig();
    if(count($invoices)>0){
      $invoice = $invoices[0];
    }else{
      return redirect()->route('admin.negotiation');
    }
    $payments = Front_model::getInvoicePayment($invoice->id);
    if(count($payments)>0){
      $payment = $payments[0];
    }else{
      $payment = [];
    }
    
    return view('admin::payment_confirmation', compact('session','invoice','payment'))->with($this->optimus);
  }
  
  function paymentConfirmationSave(Request $request){
    $input = $request->all();
    $validator = Validator::make([
			'total-payment' => $input['total-payment'],
			'transfer-date' => $input['transfer-date'],
      'bank' => $input['destination-bank'],
      'bank_account' => $input['account-number'],
      'account_name' => $input['account-name'],
		], [
			'total-payment' => 'required',
			'transfer-date' => 'required',
			'bank' => 'required',
			'bank_account' => 'required',
			'account_name' => 'required',
		]);
    if ($validator->fails()) {
      return redirect()->back()->with('message',$validator->errors()->first());
		}
    $session = Session::get(null);
    
    $invoices = Front_model::getInvoice('','',$input['invoice-number']);
    
    if(count($invoices)>0){
      $invoice = $invoices[0];
    }else{
      return redirect()->back()->with('message','Invoice Number is not registered on system');
    }
    
    $file = array('image' => Input::file('picture'));
    $fileName = "";
    if ($file["image"] !== "" && $file["image"] !== null) {
        if (Input::file('picture')->isValid()) {
            $destinationPath = public_path('/uploads/payment/'); // upload path
            $extension = Input::file('picture')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            Input::file('picture')->move($destinationPath, $fileName); // uploading file to given path
        }
    }

      $data = array(
          'invoice_number' => isset($input['invoice-number'])?$input['invoice-number']:'',
          'transfer_date' => isset($input['transfer-date'])?$input['transfer-date']:'',
          'bank' => isset($input['destination-bank'])?$input['destination-bank']:'',
          'bank_account' => isset($input['account-number'])?$input['account-number']:'',
          'account_name' => isset($input['account-name'])?$input['account-name']:'',
          'total_payment' => isset($input['total-payment'])?$input['total-payment']:'',
          'file' => $fileName,
      );

      Front_model::insertPaymentInvoice($data);
      $this->updateStatus(4);
        
      $data_inv = [
        'id' => $invoice->id,
        'status' => 1,  
      ];
      Front_model::updateInvoice($data_inv);
      
      $data_chat = array(
        'negotiation_id' => $session['negotiation']->id,
        'chat' => 'Payment Have been submit at '.date('d M Y', strtotime($input['transfer-date'])).' with total amount USD ' . number_format($input['total-payment'],'0','.',',').' click <a href="'.URL::to('/').'/uploads/payment/'.$fileName.'" target="blank">here</a> to see transfer payment',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );
      Front_model::insertNegotiationLine($data_chat);
    
      return redirect()->route('admin.negotiation.view',['id'=> $session['negotiation']->id]);
  }
  
  function trackInvoice(){
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.trackInvoice');
    }
    $session = Session::get(null);  
    $this->optimus['config'] = API::getDefaultConfig();
    $this->optimus['logistics'] = adminModel::logistic('50');
    return view('admin::tracking_invoice', compact('session'))->with($this->optimus);
  }
  
  public function submitResi(Request $request){
    $session = Session::get(null);
    $input = $request->all();
    
    $vessel_name = isset($input['vessel_name']) ? $input['vessel_name'] : '';
    $shipment_date = isset($input['shipment_date']) ? $input['shipment_date'] : '';
    
    
    $file = array('image' => Input::file('shipment_file'));
    $fileName = "";
    if ($file["image"] !== "" && $file["image"] !== null) {
        if (Input::file('shipment_file')->isValid()) {
            $destinationPath = public_path('/uploads/shipment/'); // upload path
            $extension = Input::file('shipment_file')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            Input::file('shipment_file')->move($destinationPath, $fileName); // uploading file to given path
        }
    }

      $data = array(
          'vessel_name' => $vessel_name,
          'shipment_date' => $shipment_date,
          'shipment_file' => $fileName,
      );
      
    $result = adminModel::updateNegotiation($session['negotiation']->id,$data);
    
    $negotiations = Front_model::getNegotiation($session['negotiation']->id);  
    $negotiation = $negotiations[0];
    Session::set('negotiation',$negotiation);

    if(isset($negotiations[0]->status) && $negotiations[0]->status <= 5){
      $this->updateStatus(5);
    }    
    
    $data_chat = array(
      'negotiation_id' => $session['negotiation']->id,
      'chat' => 'Shipping have been input with vessel name : '.$vessel_name.' and shipping date : '.date('d M Y', strtotime($shipment_date)).' with shipment file <a href="'.URL::to('/').'/uploads/shipment/'.$fileName .'" target="_blank">here</a>.<br>for detail you can visit this <a href="'.route('admin.trackInvoice').'?invoice_id='.$session['invoice']->id.'">page</a><br>'
        . 'Next step, we will upload all document, to preview it you can visit this <a href="'.route('admin.documentCopies').'">link</a>',
      'file' => '',
      'user_chat_id' => Session::get('user_id'),
    );
    Front_model::insertNegotiationLine($data_chat);
 
    return redirect()->route('admin.negotiation.view',['id'=> $negotiation->id])->with('message','Shipping have been update');
  }
  
  public function updateStatus($status){
    $session = Session::get(null);
    $arr = [
      'status' => $status,  
      'id' => $session['negotiation']->id,  
    ];
    Front_model::updateNegotiationStatus($arr);

    //reload negotiation session
    $negotiations = Front_model::getNegotiation($session['negotiation']->id);  
    $negotiation = $negotiations[0];
    Session::set('negotiation',$negotiation);
  }
  
  function getTracking(Request $request){
    $input = $request->all();
    $invoice = Front_model::getInvoice('','',$input['invoice_number']);
    
    if(count($invoice)>0){
      $invoice = [
          'status' => $invoice[0]->invoice_description,
      ];
    }else{
      $invoice = [
          'status' => 'not found',
      ];
    }
    
    return json_encode($invoice);
  }
  function settingIndex(){
    $this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter']) ? str_slug($_GET['filter'],' ') : '';
		$descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 'all';
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
		$db = adminModel::settingIndex($getMax, $descasc, $page, $filter);
		if(in_array(Session::get('login_type'),['admin','super admin'])) {
			return view('admin::setting', compact('db','getMax','descasc'))->with($this->optimus);
		}else{
			echo '<script>alert("You dont have permission here");</script>';
			return redirect('admin');
		}
  }
  function settingView($id){
  	$data = adminModel::settingView($id);
  	$v = [
  		'field' => $data->field,
  		'type' => $data->type,
  		'value' => str_replace('<br>', "\n", $data->value)
  	];
  	return response()->json($v);
  }
  function settingSave($id){
  	$data = [
  		'field' => $_POST['editField'],
  		'type' => $_POST['editType'],
  		'value' => $_POST['editValue']
  	];
  	adminModel::settingSave($id, $data);
  	return redirect()->back();
  }
  
  function receiveItem(){
    $session = Session::get(null);  
    if(Session::get('login_type') == 'customer'){
      //redirect to front end      
      return redirect()->route('customer.receivedItem',['invoice_id'=>$session['invoice']->id]);
    }
    
    $reviews = Front_model::getReview($session['invoice']->id);
    $this->optimus['config'] = API::getDefaultConfig();
    
    if(count($reviews)== 0){
      $this->optimus['review'] = [];
    }else{
      $this->optimus['review'] = $reviews[0];
    }

    return view('admin::receive_item', compact('session'))->with($this->optimus);
  }
  
  function deleteRating(Request $request){
    $input = $request->all();
    
    $result = Front_model::removeReview($input['id']);
    
    if($result == true){
      $v = [
        'error' => 0,
      ];
      return response()->json($v);  
    }else{
      $v = [
        'error' => 1,
        'message' => $result,
      ];
      return response()->json($v);  
    }
  }
  
}
