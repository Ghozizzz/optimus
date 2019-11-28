<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Models\Front_model;
use Input;
use App\Http\Controllers\API as API;
use Carbon\Carbon;
use Validator;
use URL;
use Modules\Admin\Models\adminModel;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class DashboardController extends Controller {

  private $optimus = [];
  
  public function __construct() {
      $this->session = Session::get(null);

      if (!session()->has('user_id')) {
          $this->middleware('authCustomer', ['except' => 'getLogout']);
      }
      
      $this->optimus['total_unread_message'] = Front_model::getUnreadMessage();
      $this->optimus['total_unread_order'] = Front_model::getUnreadMessage(array('3','4','5','6','7','8'));
  }
    
  //function to show home page
  public function index()
  {
    $session = Session::get(null);
    $this->optimus['config'] = API::getDefaultConfig();
    
    $active_menu = 'dashboard';
    return view('customer.dashboard', compact('session'))->with($this->optimus);
  }
  
  public function account(){
    $session = Session::get(null);
    $account = [];
    $this->optimus['accounts'] = Front_model::getAllCustomer($session['user_id']);
    $this->optimus['config'] = API::getDefaultConfig();
    if(count($this->optimus['accounts'])>0){
      $this->optimus['account'] = $this->optimus['accounts'][0];
    }
    $this->optimus['active_menu'] = 'dashboard';
    $this->optimus['countries'] = API::getAllCountry();
    return view('customer.account', compact('session'))->with($this->optimus);
  }
  
  public function saveAccount(Request $request){
    $params = $request->all();
    
    $file = $request->file('photo');

    if($file !== null){
      $fileName = rand(1,100).time().'.'. $file->getClientOriginalExtension();    
      $thumbnailPath = public_path('uploads/customer/'.$fileName);
      Image::make($file->getRealPath())->fit(400, 400)->save($thumbnailPath);
    }
    
    $arr = [
      'id' => $params['id'],
      'name' => isset($params['name'])?$params['name']:'',
      'phone' => isset($params['phone'])?$params['phone']:'',
      'birthday' => isset($params['birthday'])?$params['birthday']:'',
      'gender' => isset($params['gender'])?$params['gender']:'',
      'address' => isset($params['address'])?$params['address']:'',
      'password' => isset($params['password'])?$params['password']:'',
      'country_code' => isset($params['country'])?$params['country']:'',
    ];
    
    if(isset($fileName)){
      $arr['photo'] = $fileName;
    }
    $result = Front_model::updateCustomer($arr);
    
    return redirect()->back()->with('message','success update account');
  }
  
  public function createNegotiate(Request $request){
    $session = Session::get(null);

    $param = $request->all();

    if($param['vprice'] > 0){
      $car_price = $param['price'] - ($param['inspection'] != 0 ?  API::getSetting('inspection') : 0) - ($param['insurance'] != 0 ? API::getSetting('insurance') : 0) - (isset($param['shipping_price'])?$param['shipping_price'] : 0) - $param['ocean_freight'];    
    } else {
      $car_price = $param['price'] - ($param['inspection'] != 0 ?  API::getSetting('inspection') : 0) - ($param['insurance'] != 0 ? API::getSetting('insurance') : 0) - (isset($param['shipping_price'])?$param['shipping_price'] : 0);    
    }
      $arr = [
        'customer_id' => $session['user_id'],
        'car_id' => $param['car_id'],
        'price' => isset($param['price'])?$param['price']:0, 
        'currency' => isset($param['currency'])?$param['currency']:'$',
        'insurance' => isset($param['insurance'])?$param['insurance']:0,
        'inspection' => isset($param['inspection'])?$param['inspection']:0, 
        'logistic_id' => isset($param['logistic_id'])?$param['logistic_id']:'', 
        'user_id' => null,
        'destination_id' => isset($param['destination_port'])?$param['destination_port']:'',   
        'shipping_price' => isset($param['shipping_price'])?$param['shipping_price']:'',   
        'ocean_freight_fee' => isset($param['ocean_freight'])?$param['ocean_freight'] : 0,   
        'car_price' => $car_price,
        'inspection_fee' => $param['inspection'] != 0 ?  API::getSetting('inspection') : 0,  
        'insurance_fee' => $param['insurance'] != 0 ? API::getSetting('insurance') : 0
      ];

      $destination_ports = Front_model::getDestinationPort($param['destination_port']);
      
      if(count($destination_ports)>0){
          $destination_port = $destination_ports[0];
          $port_name = $destination_port->port_name;
          $country_name = $destination_port->country_name;
      }else{
          $port_name = $country_name = '';
      }
      
      $negotiation_id = Front_model::insertNegotiation($arr);
      
      if($param['chat'] != ''){
        $data = array(
          'negotiation_id' => $negotiation_id,
          'chat' => $param['chat'],
          'file' => '',
          'customer_chat_id' => Session::get('user_id'),
        );
        Front_model::insertNegotiationLine($data);
      }

      $original_price = $param['original_price'];
      if ($param['insurance'] == 1) {
        $original_price = $original_price + API::getSetting('insurance');
      }
      if ($param['inspection'] == 1) {
        $original_price = $original_price + API::getSetting('inspection');
      }
      if ($param['shipping_price'] > 0) {
        if ($param['vprice'] > 0) {
          $original_price = $original_price + $param['shipping_price'] + $param['ocean_freight'];
        } else {
          $original_price = $original_price + $param['shipping_price'];
        }
      }

      $admin_chat = 'Dear Sir/Madam<br><br>Thanks for your enquiry.<br>' .
                'The price is ';
        
      if($param['insurance'] == 1){
          $admin_chat .= 'CIF ' .$port_name. ' ' .$country_name. ' USD '. API::currency_format($original_price).' with Marine Insurance';
          if($param['inspection'] == 1){
              $admin_chat .= ' and Pre-shipment inspection';
          }        
      }else{
          $admin_chat .= 'C&F ' .$port_name. ' ' .$country_name. ' USD '. API::currency_format($original_price);
          if($param['inspection'] == 1){
              $admin_chat .= ' with Pre-shipment inspection';
          }
      }
      
      $admin_chat .= ' include:<br>';
  
      if($param['inspection'] == 1){
        $admin_chat .= '- Car Inspection : USD '. API::getSetting('inspection').'<br>';
      }    
      
      if($param['insurance'] == 1){
        $admin_chat .= '- Car Insurance : USD '. API::getSetting('insurance').'<br>';
      }
      
      if($param['shipping_price'] >0 ){
        if($param['vprice'] > 0){
          $admin_chat .= '- Ocean freight : USD '. API::currency_format($param['shipping_price'] + $param['ocean_freight']).'<br>';
        } else {
          $admin_chat .= '- Ocean freight : USD '. API::currency_format($param['shipping_price']).'<br>';
        }
      }
              
      $admin_chat .= '- Car price : USD '. API::currency_format($param['original_price']).'<br>';
      
      $admin_chat .= '<br><br>Hope to hear from you soon<br><br>Thanks<br>Best Regards,<br>Optimus Auto Trading Pte Ltd';
      
      $data = array(
        'negotiation_id' => $negotiation_id,
        'chat' => $admin_chat,
        'file' => '',
        'customer_chat_id' => '',
      );
      Front_model::insertNegotiationLine($data);    

        $chat = 'I want to negotiate for the price ';
        
        if($param['insurance'] == 1){
            $chat .= 'CIF ' .$port_name. ' ' .$country_name. ' USD '. API::currency_format($param['price']).' with Marine Insurance';
            if($param['inspection'] == 1){
                $chat .= ' and Pre-shipment inspection';
            }        
        }else{
            $chat .= 'C&F ' .$port_name. ' ' .$country_name. ' USD '. API::currency_format($param['price']);
            if($param['inspection'] == 1){
                $chat .= ' with Pre-shipment inspection';
            }
        }
        
        $chat .= ' include:<br>';
    
        if($param['inspection'] == 1){
          $chat .= '- Car Inspection : USD '. API::getSetting('inspection').'<br>';
        }    
        
        if($param['insurance'] == 1){
          $chat .= '- Car Insurance : USD '. API::getSetting('insurance').'<br>';
        }
        
        if($param['shipping_price'] >0 ){
          if($param['vprice'] > 0){
            $chat .= '- Ocean freight : USD '. API::currency_format($param['shipping_price'] + $param['ocean_freight']).'<br>';
          } else {
            $chat .= '- Ocean freight : USD '. API::currency_format($param['shipping_price']).'<br>';
          }
        }
                
        $chat .= '- Car price : USD '. API::currency_format($car_price).'<br>';
        
        $data = array(
          'negotiation_id' => $negotiation_id,
          'chat' => $chat,
          'file' => '',
          'customer_chat_id' => Session::get('user_id'),
        );
        Front_model::insertNegotiationLine($data);      
      $negotiations = Front_model::getNegotiation($negotiation_id);     
      
    
    $negotiation = $negotiations[0];
    
    $active_menu = 'dashboard';
    return redirect()->route('customer.negotiation',['id'=>$negotiation->id]);
  }
  
  
  
  public function negotiation($id){
    try{
      $negotiations = Front_model::getNegotiation($id);  
      $this->optimus['negotiation'] = $negotiations[0];
      $this->optimus['config'] = API::getDefaultConfig();
      $this->optimus['negotiation_line'] = Front_model::getNegotiationLine('',$this->optimus['negotiation']->id);  
      $unread_ids = [];
      if(is_array($this->optimus['negotiation_line']) && count($this->optimus['negotiation_line'])>0){        
        foreach($this->optimus['negotiation_line'] as $negotiation_line_detail){
          if($negotiation_line_detail->isread == 0) {
            array_push($unread_ids,$negotiation_line_detail->negotiation_line_id);
          }          
        }
        Front_model::updateAllNotification($this->optimus['negotiation']->id);
      }
      $this->optimus['unread_message'] = $unread_ids;
      $invoices = Front_model::getInvoice('',$id);

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

      Session::set('negotiation',$this->optimus['negotiation']);
      Session::set('negotiation_line',$this->optimus['negotiation_line']);


      $session = Session::get(null);
      $active_menu = 'dashboard';    
      return view('customer.new_negotiation', compact('session','active_menu'))->with($this->optimus);
    } catch (Exception $ex) {

    }
    
  }
  
  public function saveComment(Request $request){
    $session = Session::get(null);
    $input = $request->all();
    $file = array('image' => Input::file('picture'));
    $fileName = "";
    if ($file["image"] !== "" && $file["image"] !== null) {
        if (Input::file('picture')->isValid()) {
            $destinationPath = 'uploads/negotiation/'; // upload path
            $extension = Input::file('picture')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            Input::file('picture')->move($destinationPath, $fileName); // uploading file to given path
        }
    }

      $data = array(
          'negotiation_id' => isset($input['negotiation_id'])?$input['negotiation_id']:'',
          'chat' => isset($input['chat-content'])?$input['chat-content']:'',
          'file' => $fileName,
          'customer_chat_id' => Session::get('user_id'),
      );

      Front_model::insertNegotiationLine($data);
        
      return redirect()->route('customer.negotiation',['id'=>$input['negotiation_id']]);
  }
  
  public function documentCopies(){
    $session = Session::get(null);
    $this->optimus['documents'] = Front_model::getDocumentCopy('',$session['invoice']->id);
    if ($this->optimus['documents'] !== null) {
        $document = [];
        foreach ($this->optimus['documents'] as $key => $value) {
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
    
    return view('customer.document_copies', compact('session', 'document'))->with($this->optimus);
  }
  
  public function originalDocument(){
    $session = Session::get(null);
    $documents = adminModel::getDocumentOriginal($session['negotiation']->id);
    if(count($documents) == 1){
      $document = $documents[0];
    }else{
      $document = [];
    }

    $this->optimus['config'] = API::getDefaultConfig();
    return view('customer.original_document', compact('session','document'))->with($this->optimus);
  }
  
  public function negotiate(Request $request){
    $session = Session::get(null);
    $param = $request->all();
    $car = [];
    $cars = Front_model::getAllCar([$param['car_id']]);
    
//    $negotiations = Front_model::getNegotiation('',$session['user_id'],'',$param['car_id']);
    
    if(count($cars)>0){
      $car = $cars[0];
//      if(count($negotiations) > 0){
//        $negotiation = $negotiations[0];
//        $picture_arr = json_decode($car->picture);
//        $car->picture = $picture_arr[0]->picture;
//        $car->price = $negotiation->price;
//        $car->currency = $negotiation->currency;
//        $car->inspection = $negotiation->insurance;
//        $car->insurance = $negotiation->inspection;
//        $car->logistic_id = $negotiation->logistic_id;
//        $car->destination_port = $negotiation->port_destination_id;
//        $car->new_negotiation = 0;
//        
//      }else{        
        $picture_arr = json_decode($car->picture);
        $car->picture = $picture_arr[0]->picture;
        $car->original_price = isset($param['original_car_price']) ? $param['original_car_price'] : $car->price;
        $car->price = $param['car_price'];
        $car->currency = $param['currency'];
        $car->inspection = isset($param['inspection'])?$param['inspection']:0;
        $car->insurance = isset($param['insurance'])?$param['insurance']:0;
        $car->logistic_id = isset($param['logistic_id'])?$param['logistic_id']:'';
        $car->destination_port = isset($param['destination_port'])?$param['destination_port']:0;
        $car->shipping_price = isset($param['shipping_price'])?$param['shipping_price']:0;
        $car->insurance_fee = $car->insurance == 1 ? API::getSetting('insurance') : 0; 
        $car->inspection_fee = $car->inspection == 1 ? API::getSetting('inspection') : 0;
        $car->ocean_freight = API::getSetting('ocean_freight');
        $car->vprice = isset($param['vprice']) ? $param['vprice'] : 0;
        $car->chat = isset($param['chat'])?$param['chat']:'';
        $car->new_negotiation = 1;        
//      }   
    }
    $this->optimus['config'] = API::getDefaultConfig();    
    $this->optimus['active_menu'] = 'dashboard';
    
    return view('customer.new_negotiation', compact('session','car'))->with($this->optimus);
  }
  
  public function sendChat(Request $request){
    $param = $request->all();
    $session = Session::get(null);
    Front_model::getAllChat([$param['car_id']]);
    
  }
  
  public function getChat(Request $request){
    $param = $request->all();
    $session = Session::get(null);
  }
  
  public function wishlist()
  {
    $session = Session::get(null);
    $this->optimus['config'] = API::getDefaultConfig(); 
    
    $page = isset($_GET['page'])?$_GET['page']:1;
    $limit = 5;
    $offset = ($page-1)*$limit;
    $wishlist_car = array();
    $wishlist = Front_model::getAllWishlist('',$session['user_id']);
    $car_array = array();
    if(count($wishlist)>0){
      $car_array = json_decode($wishlist[0]->car_id);
    }

    $car = [];
    $max_offset = $offset + $limit;
    for($i=$offset;$i<$max_offset;$i++){
      if(isset($car_array[$i])){
        array_push($car,$car_array[$i]);
      }      
    }
    $this->optimus['wishlist_car'] = [
        'car' => Front_model::getAllCar($car),
        'total' => count($car_array),
    ];
    $this->optimus['active_menu'] = 'dashboard';
    return view('customer.wishlist', compact('session'))->with($this->optimus);
  }
  
  public function negotiationlist(){
    $session = Session::get(null);
    $this->optimus['config'] = API::getDefaultConfig();
    
    $thispage = isset($_GET['page'])?$_GET['page']:1;
    $descasc = isset($_GET['descasc'])?'createdon-'.$_GET['descasc']:'createdon-desc';
    $filter = isset($_GET['filter'])?$_GET['filter']:'';
    
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $offset = ($thispage-1) * $getMax;
    $negotiations_list = Front_model::getNegotiation('',Session::get('user_id'),'','',false,$getMax,$offset,$descasc,$filter);
    $negotiations_total = Front_model::getNegotiation('',Session::get('user_id'),'','',true,'','','',$filter);
    $unread_negotiation = Front_model::getUnreadMessageId();
    
    $this->optimus['negotiations'] = [
        'negotiation' => $negotiations_list,
        'total' => $negotiations_total,
        'unread_negotiation' => $unread_negotiation,
    ];
    
    $this->optimus['page_type'] = 'negotiation_list';
    
    $active_menu = 'dashboard';
    return view('customer.negotiationlist', compact('session','getMax','descasc'))->with($this->optimus);
  }
  
  public function orderlist(){
    $session = Session::get(null);
    $this->optimus['config'] = API::getDefaultConfig();
    
    $thispage = isset($_GET['page'])?$_GET['page']:1;
    $descasc = isset($_GET['descasc'])?'createdon-'.$_GET['descasc']:'createdon-desc';
    $filter = isset($_GET['filter'])?$_GET['filter']:'';
    
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    
    $offset = ($thispage-1) * $getMax ;
    $negotiations_list = Front_model::getNegotiation('',Session::get('user_id'),'','',false,$getMax,$offset,$descasc,$filter,['3','4','5','6','7','8']);
    
    $negotiations_total = Front_model::getNegotiation('',Session::get('user_id'),'','',true,'','','',$filter, ['3','4','5','6','7','8']);
    $unread_negotiation = Front_model::getUnreadMessageId();
    $this->optimus['negotiations'] = [
        'negotiation' => $negotiations_list,
        'total' => $negotiations_total,
        'unread_negotiation' => $unread_negotiation,
    ];
    $this->optimus['page_type'] = 'order_list';
    $active_menu = 'dashboard';
    return view('customer.negotiationlist', compact('session','descasc','getMax'))->with($this->optimus);
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

      $shipping_price = isset($destination_port[0]) && ($destination_port[0]->price != null || $destination_port[0]->price != 0 ) ? $destination_port[0]->price : $session['negotiation']->shipping_price;

      
      $params = [
        'proforma_invoice_number' => $SOnbr, 
        'negotiation_id' => $negotiation_id,
        'created_by'  => Session::get('name'),  
        'currency' => $session['negotiation']->currency,
        'shipping_price' => $shipping_price,
        'total_amount' => $session['negotiation']->price,
        'car_id' => $session['negotiation']->car_id,  
        'port_destination' => $session['negotiation']->port_destination_id, 
        'inspection' => $session['negotiation']->inspection 
      ];
      $invoice_id = Front_model::insertProformaInvoice($params);
      $this->updateStatus(2);
    }
    
    return redirect()->route('customer.proformainvoice',['id'=>$invoice_id]);        
  }
  
  public function proformainvoice($id){
    
    $invoices = Front_model::getProformaInvoice($id);

    if(!is_array($invoices) || count($invoices)== 0){
      return redirect()->back()->with('message','somethig wrong');
    }
    
    $cars = Front_model::getAllCar([Session::get('negotiation')->car_id]);
    $this->optimus['countries'] = Front_model::getAllCountry();    
    $destination = Front_model::getDestinationPort($invoices[0]->port_destination);    
    if(count($destination)>0){
      Session::set('destination_port',$destination[0]);
    }
    Session::set('proforma_invoice',$invoices[0]);
    $session = Session::get(null);
    $this->optimus['config'] = API::getDefaultConfig();
    
    if(count($invoices)>0){
      $this->optimus['invoice'] = $invoices[0];
    }else{
      return redirect()->route('customer.negotiationlist');
    }
    
    if(count($cars)>0){
      $this->optimus['car'] = $cars[0];
    }
    
    $this->optimus['logistic'] =[
      'destination_country' => Front_model::getAllDestinationCountry(),
      'destination_port' => Front_model::getDestinationPort('','SG'),
      'departure_country' => Front_model::getAllDepartureCountry(),  
      'departure_port' => Front_model::getDeparturePort('','SG'),  
    ];
    
   
    return view('customer.proforma_invoice', compact('session'))->with($this->optimus);     
  }
  
  public function cancelProformaInvoice($invoice_id){
    $session = Session::get(null);
    $proforma_invoice = $session['proforma_invoice'];
    if(isset($proforma_invoice->id)){
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
          'chat' => 'Proforma Invoice have been rejected, and wait admin to edit proforma invoice at <a href="'.route('admin.proformainvoice',['id'=> $proforma_invoice->id]).'">here</a>',
          'file' => '',
          'customer_chat_id' => Session::get('user_id'),
    );

    Front_model::insertNegotiationLine($data);
      
    return redirect()->route('customer.negotiation',['id'=> $session['negotiation']->id])->with('message','Proforma Invoice have been updated');
  }
  
  public function saveProformaInvoice(Request $request){
    $param = $request->all();
    $session = Session::get(null);
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
      $dimension_arr = explode(',',$car_volume);
      if(count($dimension_arr)== 3){
        $car_volume = $dimension_arr[0] * $dimension_arr[1] * $dimension_arr[2] / 1000000;
      }
    }
     
    
    $destination_port = isset($param['destination_port']) && $param['destination_port'] != '' ? $param['destination_port'] : $session['proforma_invoice']->port_destination;    
    $departure_port = isset($param['departure_port']) && $param['departure_port'] != '' ? $param['departure_port'] : $session['proforma_invoice']->port_departure;    
    
    $destination_ports = Front_model::getDestinationPort($destination_port);
    $negotiation = Session::get('negotiation');
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
    
      
    if(isset($destination_ports[0]->volume_price) && $destination_ports[0]->volume_price != null && $destination_ports[0]->volume_price != 0 && $car_volume != 0){
      $shipping_price = $arr_negotiation['shipping_price'] = $destination_ports[0]->volume_price * $car_volume;
    }else if(isset($destination_ports[0]->price) && $destination_ports[0]->price != null && $destination_ports[0]->price != 0){
      $shipping_price = $arr_negotiation['shipping_price'] = $destination_ports[0]->price;
    }else{
      $shipping_price = 0;
    }
    
    if($param['incoterms'] == 'CIF'){
      if(isset($param['inspection']) && $param['inspection'] == 1){
        $inspection_fee = $arr_negotiation['inspection_fee'] = API::getSetting('inspection');
        $arr_negotiation['inspection'] = 1;
      }elseif(isset($negotiation->inspection) && $negotiation->inspection == 1){
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
      }elseif(isset($negotiation->inspection) && $negotiation->inspection == 1){
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
        'port_departure' => $departure_port, 
        'port_destination' => $destination_port, 
        'country_code' => isset($param['country'])?$param['country']:'', 
        'total_amount' => isset($param['total_amount']) ? $param['total_amount']:'',
        'shipping_price' => $shipping_price,
        'currency' => isset($param['currency'])?$param['currency']:'',        
        'detail' => json_encode($detail),
        'incoterm' => isset($param['incoterms'])?$param['incoterms'] : '',
        'sales_agreement' => isset($param['sales_agreement'])?$param['sales_agreement']:'',
        'status' => isset($param['status'])?$param['status']:2,
        'inspection' => isset($param['inspection'])?$param['inspection']: $session['proforma_invoice']->inspection,
        'inspection_value' => isset($param['inspection_value'])?$param['inspection_value']:'',
        'payment_due' => isset($param['due_date'])?$param['due_date']: $session['proforma_invoice']->payment_due,
        'customer_approval' => 1,
        'approval' => 0,
    ];
    
    $result = Front_model::updateProformaInvoice($arr);
    $result = Front_model::saveProformanceInvoiceLog($arr);
    
    $data = array(
          'negotiation_id' => $session['negotiation']->id,
          'chat' => 'Proforma Invoice have been Submit please check it at <a href="'.route('admin.proformainvoice',['id'=> $param['id']]).'">here</a>',
          'file' => '',
          'customer_chat_id' => Session::get('user_id'),
    );

    Front_model::insertNegotiationLine($data);
    
    return redirect()->route('customer.negotiation',['id'=> $session['negotiation']->id])->with('message','Proforma Invoice have been updated');
  }
  
  public function createInvoice(){
    $session = Session::get(null);
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
          'consignee_country' => $consignee_destinaton_country->country_code,  
          'final_destination' => $consignee_destinaton_country->port_name,
          'total_amount' => $session['proforma_invoice']->total_amount, 
          'currency' => $session['proforma_invoice']->currency, 
          'proforma_invoice_id' =>  $session['proforma_invoice']->id,
          'status' => null,  
          'due_date' => $session['proforma_invoice']->payment_due,  
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
        return redirect()->route('customer.negotiationlist'); 
      }
    }
    
    return redirect()->route('customer.negotiation',['id'=> $session['negotiation']->id])->with('message','Invoice has beedn created');     
  }
  
  public function invoice($id){
    
    $session = Session::get(null);
    $invoices = Front_model::getInvoice($id);
    $countries = Front_model::getAllCountry();
    $car = array();
    
    $cars = Front_model::getAllCar([Session::get('negotiation')->car_id]);
   
    if(count($cars)>0){
      $car = $cars[0];
    }
    $this->optimus['logistic'] =[
      'destination_country' => Front_model::getAllDestinationCountry(),
      'destination_port' => Front_model::getDestinationPort('','SG'),
      'departure_country' => Front_model::getAllDepartureCountry(),  
      'departure_port' => Front_model::getDeparturePort('','SG'),  
    ];
    
    $this->optimus['config'] = API::getDefaultConfig();
    
    if(count($invoices)>0){
      $invoice = $invoices[0];
      $departure_port_details = Front_model::getDeparturePort($session['proforma_invoice']->port_departure);
      $invoice->departure_port_detail = isset($departure_port_details[0])?$departure_port_details[0]:[];
      $destination_port_details = Front_model::getDestinationPort($session['proforma_invoice']->port_destination);
      $invoice->destination_port_detail = isset($destination_port_details[0])?$destination_port_details[0]:[];
      Session::set('invoice', $invoice);
    }else{
      return redirect()->route('customer.negotiationlist');
    }
    $session = Session::get(null);

    $this->optimus['accounts'] = Front_model::getAllCustomer($session['user_id']);

    return view('customer.invoice', compact('session','invoice','countries','car'))->with($this->optimus);     
  }
  
  function uploadPhoto(Request $request){
    $session = Session::get(null);
    
    $file = $request->file('photo');
    $fileName = rand(1,100).time().'.'. $file->getClientOriginalExtension();    
    $thumbnailPath = public_path('uploads/customer/'.$fileName);
    Image::make($file->getRealPath())->fit(400, 400)->save($thumbnailPath);
                
                
                
    $result = Front_model::updateCustomerPhoto($session['user_id'], $fileName);
    
    return $result;
    
  }
  
  function getCustomerData(){
    $session = Session::get(null);
    $data = Front_model::getAllCustomer($session['user_id']);
    if(count($data)>0){
      $result = [
        'data' => $data[0],
        'error' => 0,
        'message' => '',  
      ];
    }else{
      $result = [
        'data' => [],
        'error' => 1,
        'message' => 'session expired',  
      ];
    }
    return json_encode($result);
  }
  
  function updateInvoice(Request $request){
    $param = $request->all();
    $negotiation = Session::get('negotiation');
    
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
    
    $link = '';
    if($arr['status'] == 1){
      
      $data_chat = array(
        'negotiation_id' => $negotiation->id,
        'chat' => 'Your Invoice have been Create, please click <a href="'.route('admin.negotiationInvoice',['id'=>$param['id']]).'">here</a> to see and download your invoice.<br>Please do payment by click <a href='.route('admin.paymentConfirmation',['invoice_id'=>$param['id']]).'">this link</a>',
        'file' => '',
        'user_chat_id' => Session::get('user_id'),
      );
      Front_model::insertNegotiationLine($data_chat);
      $link = route('customer.negotiation',['id'=> $negotiation->id]);
    }
    
    return array('error'=>0,'message'=>'Data haved been updated','link'=>$link,'data'=> $arr);
  }
  
  function paymentConfirmation($invoice_id){
    $session = Session::get(null);
    $invoices = Front_model::getInvoice($invoice_id);    
    if(count($invoices)>0){
      $invoice = $invoices[0];
    }else{
      return redirect()->route('customer.negotiationlist');
    }
    $payments = Front_model::getInvoicePayment($invoice->id);
    $this->optimus['config'] = API::getDefaultConfig();
    if(count($payments)>0){
      $payment = $payments[0];
    }else{
      $payment = [];
    }
    
    return view('customer.payment_confirmation', compact('session','invoice','payment'))->with($this->optimus);
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
      
      $data = array(
        'negotiation_id' => $session['negotiation']->id,
        'chat' => 'Payment have been submit at '.date('d M Y', strtotime($input['transfer-date'])).' with total amount USD ' . number_format($input['total-payment'],'0','.',',').' click <a href="'.URL::to('/').'/uploads/payment/'.$fileName.'" target="blank">here</a> to see transfer payment' ,
        'file' => '',
        'customer_chat_id' => Session::get('user_id'),
      );

      Front_model::insertNegotiationLine($data);
    
      return redirect()->route('customer.negotiation',['id'=> $session['negotiation']->id])->with('message','Payment have been submit');
  }
  
  function trackInvoice(){
    $session = Session::get(null);  
    $this->optimus['config'] = API::getDefaultConfig();
    return view('customer.tracking_invoice', compact('session'))->with($this->optimus);
  }
  
  function receiveItem(){
    $session = Session::get(null);  
    
    $reviews = Front_model::getReview($session['invoice']->id);
    
    if(count($reviews)== 0){
      $this->optimus['review'] = [];
    }else{
      $this->optimus['review'] = $reviews[0];
    }
    
    $this->optimus['config'] = API::getDefaultConfig();
    
    return view('customer.receive_item', compact('session'))->with($this->optimus);
  }
  
  function session(){
    dd(Session::get(null));
  }
  
  function negotiationStatus(Request $request){
    $input = $request->all();
    
    $arr = [
      'id' => $input['negotiation_id'],
      'status' => $input['status'],
    ];
    Front_model::updateNegotiationStatus($arr);
    
    $negotiations = Front_model::getNegotiation($input['negotiation_id']);  
    $negotiation = $negotiations[0];
    
    Session::set('negotiation',$negotiation);
    
    if($input['status'] == 8){
      $data = array(
        'negotiation_id' => $input['negotiation_id'],
        'chat' => 'Item received' ,
        'file' => '',
        'customer_chat_id' => Session::get('user_id'),
      );

      Front_model::insertNegotiationLine($data);
    }
    
    return array('error'=>0,'status'=>'success');
  }
  
  function getTracking(Request $request){
    $session = Session::get(null);
    $input = $request->all();
    $invoice = Front_model::getInvoice('','',$input['invoice_number']);
    
    if(count($invoice)>0){
      $invoice = [
          'status' => $invoice[0]->invoice_description,
          'negotiation_status_description' => $invoice[0]->negotiation_status_description,
          'negotiation_status' => $invoice[0]->negotiation_status,
          'resi_number' => $invoice[0]->resi_number,
      ];
    }else{
      $invoice = [
          'status' => 'not found',
      ];
    }
    
    $this->updateStatus(5);    
      
    return json_encode($invoice);
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
  
  public function saveRating(Request $request){
    $input = $request->all();
    
    $rating = ($input['speed_rating_star'] + $input['accuration_rating_star'] + $input['satisfication_rating_star']) / 3;
    $rating = ceil($rating);
    
    $session = Session::get(null);
    
    $arr = [
      'invoiceid' => $input['invoiceid'],
      'rating_comment' => $input['rating_comment'],
      'rating_star' => $rating,  
      'speed_rating_star' => $input['speed_rating_star'],
      'accuration_rating_star' => $input['accuration_rating_star'],
      'satisfication_rating_star' => $input['satisfication_rating_star'],
    ]; 
    Front_model::saveRating($arr);
    
    $data = array(
      'negotiation_id' => $session['negotiation']->id,
      'chat' => 'Item reviewed' ,
      'file' => '',
      'customer_chat_id' => Session::get('user_id'),
    );

    Front_model::insertNegotiationLine($data);
      
    return json_encode(array('error'=>0,'message'=>'Thanks for review','link'=>route('customer.negotiation',['id'=> $session['negotiation']->id ])));
  }
}
