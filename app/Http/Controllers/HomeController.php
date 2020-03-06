<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Models\Front_model;
use Modules\Admin\Models\adminModel;
use Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\API as API;
use Illuminate\Http\Request as NewRequest;
use Mail as NewEmail;

class HomeController extends Controller {

  //function to show home page
  public function index() {
    $session = Session::get(null);
    // unset($session['recently_view'][7]);
    // Session::forget('recently_view'[7]);
    // dd($session['recently_view']);
    if(isset($session['recently_view'])){
      foreach ($session['recently_view'] as $key => $value) {
        $car = Front_model::getOneCar($value->id);
        if($car->status == 2 || $car->status == 4) {
          unset($session['recently_view'][$value->id]);
        }
      }
    }else{
      $session['recently_view'] = Front_model::getOneCar(23);
    }

    $make_array = Front_model::getAllMake();
    $body_type_array = Front_model::getAllBodyType();
    $car_engine_array = Front_model::getAllCarEngine();

    $hot_car = Front_model::getAllCar('', 'new-arrival', false, 4, 0,array(1,3));
    $total_hot_car = Front_model::getAllCar('', 'new-arrival', true, '', '',array(1,3));
    
    $recommendation_car = Front_model::getAllCar('', 'recommended', false, 4, 0, array(1,3));
    $total_recommendation_car = Front_model::getAllCar('', 'recommended', true, '', '', array(1,3));
    $best_seller_car = Front_model::getAllCar('', 'best-seller', false, 4, 0, array(1,3));
    $total_best_seller_car = Front_model::getAllCar('', 'best-seller', true, '', '', array(1,3));
    $best_deal_car = Front_model::getAllCar('', 'clearance-sale', false, 4, 0, array(1,3));
    $total_best_deal_car = Front_model::getAllCar('', 'clearance-sale', true, '', '', array(1,3));
//    $hot_car = Front_model::getAllCar('', 'hot-car', false, 4, 0);
    $all_car = Front_model::getAllCar('', '', false, '', '', array(1,3));
    $total_all_car = Front_model::getAllCar('', '', true, '', '', array(1,3));
    
    
    $optimus['banner'] = Front_model::getAllBanner('', 3, 1);
    $optimus['config'] = API::getDefaultConfig();

    $optimus['car_filter_option'] = [
        'make' => $make_array,
        'body_type' => $body_type_array,
        'car_engine' => $car_engine_array,
    ];

    $optimus['car_list'] = [
        'best_seller' => array(
            'items' => $best_seller_car,
            'total' => $total_best_seller_car,
        ),
        'best_deal' => array(
            'items' => $best_deal_car,
            'total' => $total_best_deal_car,
        ),
        'hot_car' => array(
            'items' => $hot_car,
            'total' => $total_hot_car,
        ),
        'recommended' => array(
            'items' => $recommendation_car,
            'total' => $total_recommendation_car,
        ),
        'all_car' => array(
            'items' => $all_car,
            'total' => $total_all_car,
        ),
    ];
    // print_r($optimus['car_list']);die();
    $optimus['active_menu'] = 'home';
    
    $car_price = Front_model::getMaxMinCarPrice();
    
    if(count($car_price)>0){
      $optimus['car_max_price'] = $car_price[0]->max_price;
      $optimus['car_min_price'] = $car_price[0]->min_price;
    }else{
      $optimus['car_max_price'] = 1000000;
      $optimus['car_min_price'] = 0;
    }
    
    return view('home', compact('session'))->with($optimus);
  }

  public function getCarModel(Request $request) {
    $param = $request::all();
    $make_id = isset($param['make_id']) ? $param['make_id'] : '';
    $model_array = array();
    if ($make_id !== '') {
      $model_array = Front_model::getAllCarModel('', $make_id);
    }
    return $model_array;
  }

  public function getCar(Request $request) {
    $param = $request::all();
    $criteria = isset($param['criteria']) ? $param['criteria'] : '';
    $limit = isset($param['limit']) ? $param['limit'] : 5;
    $offset = isset($param['offset']) ? $param['offset'] : 0;
    $car_array = array();
    if ($criteria !== 'best_deal') {
      $car_array = Front_model::getAllCar('', $criteria, false, $limit, $offset);
    }
    return $car_array;
  }

  public function about() {
    $session = Session::get(null);
    $optimus['about'] = API::getSetting('information');
    $optimus['active_menu'] = 'about';
    $optimus['config'] = API::getDefaultConfig();

    return view('about', compact('session'))->with($optimus);
  }

  public function gallery() {
    $session = Session::get(null);
    $make_array = Front_model::getAllMake();
    $body_type_array = Front_model::getAllBodyType();
    $car_engine_array = Front_model::getAllCarEngine();
    $optimus['config'] = API::getDefaultConfig();

    $optimus['car_filter_option'] = [
        'make' => $make_array,
        'body_type' => $body_type_array,
        'car_engine' => $car_engine_array,
    ];

    $page = isset($_GET['page']) ? $_GET['page'] : '1';
    $make = isset($_GET['make']) ? $_GET['make'] : '';
    $body_type = isset($_GET['body_type']) ? $_GET['body_type'] : '';
    $price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';
    $price1 = isset($_GET['price1']) ? $_GET['price1'] : '';
    $price2 = isset($_GET['price2']) ? $_GET['price2'] : '';
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $model = isset($_GET['model']) ? $_GET['model'] : '';
    $car_engine = isset($_GET['car_engine']) ? $_GET['car_engine'] : '';
    $car_year = isset($_GET['car_year']) ? $_GET['car_year'] : '';
    $car_end_year = isset($_GET['car_end_year']) ? $_GET['car_end_year'] : '';
    $criteria = isset($_GET['criteria']) ? $_GET['criteria'] : '';
    $new_car = isset($_GET['new_car_cb']) ? 1 : '';
    $used_car = isset($_GET['used_car_cb']) ? 1 : '';
    $promotion = isset($_GET['promotion_cb']) ? 1 : '';    
    $car_status = '';
    if ($new_car == 1 && $used_car == 1) {
      $car_status = '';
    } elseif ($new_car == 1) {
      $car_status = 1;
    } elseif ($used_car == 1) {
      $car_status = 2;
    }

    if ($price1 !== '' && $price2 !== '') {
      $price_range = $price1 . '-' . $price2;
    }

    $limit = 12;
    $offset = ($page - 1) * $limit;

    $optimus['car'] = Front_model::getAllCar('', $criteria, false, $limit, $offset, array(1,3), $make, $body_type, $price_range, $keyword, $model, $car_engine, array($car_year,$car_end_year), $car_status, $promotion);
    
    $optimus['total_car'] = Front_model::getAllCar('', $criteria, true, '', '', array(1,3), $make, $body_type, $price_range, $keyword, $model, $car_engine, array($car_year,$car_end_year), $car_status, $promotion);
    $optimus['active_menu'] = 'gallery';
    
    return view('gallery', compact('session'))->with($optimus);
  }

  public function review($country = '', $make = '', $model = '') {
    $session = Session::get(null);
    $avg_ratings = Front_model::getRating('', '');
    
    $optimus['page'] =isset($_GET['page'])?$_GET['page']:1;
    $optimus['limit'] = 5;
    $optimus['offset'] = ($optimus['page']-1) * $optimus['limit'];
    $ratings_all = adminModel::getAllReview('all','desc','','','1');
    $ratings = adminModel::getAllReview($optimus['limit'],'desc',$optimus['offset'],'','1', $country, $make, $model);
    
    if (count($avg_ratings) > 0) {
      $optimus['speed_rating'] = round($avg_ratings[0]->speed_rating);
      $optimus['accuration_rating'] = round($avg_ratings[0]->accuration_rating);
      $optimus['satisfication_rating'] = round($avg_ratings[0]->satisfication_rating);
    }
    
    $optimus['ratings'] = $ratings;
    $optimus['ratings']['total_page'] = ceil($optimus['ratings']['total']/$optimus['limit']);
    $optimus['config'] = API::getDefaultConfig();

    $make_array = Front_model::getAllMake();
    $body_type_array = Front_model::getAllBodyType();
    $countries = Front_model::getAllCountry();
    if($make !== ''){
      $model_array = Front_model::getAllCarModel('',$make);
    }else{
      $model_array = [];
    }
    $optimus['car_filter_option'] = [
        'make' => $make_array,
        'body_type' => $body_type_array,
        'country' => $countries,
        'model' => $model_array,
    ];
    
    $optimus['selected_filter'] = [
        'country_code' => $country,
        'make_id' => $make,
        'model_id' => $model,
    ];
    
    return view('review', compact('session'))->with($optimus);
  }

  public function logout() {
    session()->flush();
    return redirect('/');
  }

  public function checkLogin(Request $request) {
    $param = $request::all();
    $result = Front_model::getAllCustomer('', $param['email'], md5($param['password']));
    
    if (count($result) > 0) {
      $data_customer = $result[0];
      Session::set('user_id', $data_customer->id);
      Session::set('name', $data_customer->name);
      Session::set('email', $data_customer->email);
      Session::set('login_type', 'customer');
      return array('error' => 0, 'message' => 'login success', 'icon' => '<a class="nav-icon admin" href="' . route('customer.customerDashboard') . '"><span>Admin</span></a>', 'email_login' => $data_customer->email, 'login_type' =>Session::get('login_type'));
    } else {
      return array('error' => 1, 'message' => 'Please insert the correct email and password');
    }
  }

  public function checkLoginSeller(Request $request) {
    $param = $request::all();
    $result = Front_model::getAllSeller('', $param['email'], md5($param['password']));

    if (count($result) > 0) {
      $data_seller = $result[0];
      Session::set('user_id', $data_seller->id);
      Session::set('name', $data_seller->pic_name);
      Session::set('email', $data_seller->email);
      Session::set('login_type', 'dealer');
      return array('error' => 0, 'message' => 'login success', 'icon' => '<a class="nav-icon admin" href="' . route('dealers.car') . '"><span>Admin</span></a>', 'email_login' => $data_seller->email);
    } else {
      return array('error' => 1, 'message' => 'Please insert the correct email and password');
    }
  }

  public function signup(Request $request) {
    $param = $request::all();
    if($param['email'] == ''){
      return array('error' => 1, 'message' => 'Email required');
    }
    if($param['password'] == ''){
      return array('error' => 1, 'message' => 'Password required');
    }
    if($param['country'] == ''){
      return array('error' => 1, 'message' => 'Country required');
    }
    $result = Front_model::getAllCustomer('', $param['email']);

    if (count($result) > 0) {
      if ($result[0]->status == 2) {
        return array('error' => 1, 'message' => 'Email have been registered, please activate your account');
      } else {
        return array('error' => 1, 'message' => 'Email have been registered');
      }
    } else {
      $arr = [
          'email' => isset($param['email']) ? $param['email'] : '',
          'name' => isset($param['name']) ? $param['name'] : '',
          'password' => isset($param['password']) ? md5($param['password']) : '',
          'phone_number' => isset($param['phone_number']) ? $param['phone_number'] : '',
          'country_code' => isset($param['country']) ? $param['country'] : '',
      ];

      $result = Front_model::insertCustomer($arr);
      $arr['link'] = route('front.signupVerification') . "/" . base64_encode($param['email']);

      try {
        $this->sentEmailSignup($arr);
      } catch (Exception $ex) {
        return array('error' => 0, 'message' => 'Account have been create', 'error message' => $ex);
      }


      if ($result) {
        return array('error' => 0, 'message' => 'Account have been create, please check inbox');
      } else {
        return array('error' => 1, 'message' => 'Failed signup, please contact admin');
      }
    }
  }

  public function signupseller(Request $request) {
    $param = $request::all();
    $result = Front_model::getAllSeller('', $param['email']);

    if (count($result) > 0) {
      if ($result[0]->status == 2) {
        return array('error' => 1, 'message' => 'Email have been registered, please activate your account');
      } else {
        return array('error' => 1, 'message' => 'Email have been registered');
      }
    } else {
      $arr = [
          'email' => isset($param['email']) ? $param['email'] : '',
          'name' => isset($param['name']) ? $param['name'] : '',
          'password' => isset($param['password']) ? md5($param['password']) : '',
          'phone_number' => isset($param['phone_number']) ? $param['phone_number'] : '',
      ];

      $result = Front_model::insertDealer($arr);
      $arr['link'] = "";
      try {
        $this->sentEmailSignup($arr);
      } catch (Exception $ex) {
        return array('error' => 0, 'message' => 'Account have been create', 'error message' => $ex);
      }

      if ($result) {
        return array('error' => 0, 'message' => 'Account have been create, please check inbox');
      } else {
        return array('error' => 1, 'message' => 'Failed signup, please contact admin');
      }
    }
  }

  public function sentEmailSignup($arr = []) {
    $arr['template'] = 'emails.signup';
    $arr['from_email'] = 'optimus@funedge.co.id';
    $arr['subject'] = 'Sign up verification';
    API::sendEmail($arr);
  }

  public function resetPassword(Request $request) {
    $input = $request::all();
    if ($input['type'] == 'seller') {
      $user = Front_model::getAllSeller('', $input['email'], '', false, 1);
      if (count($user) > 0) {
        $input['name'] = $user[0]->pic_name;
        $input['link'] = route('front.resetPasswordSeller') . "/" . base64_encode($input['email']);
      } else {
        return array('error' => 1, 'message' => 'can\'t find user with this email address');
      }
    } else {
      $user = Front_model::getAllCustomer('', $input['email'], '', false, 1);
      if (count($user) > 0) {
        $input['name'] = $user[0]->name;
        $input['link'] = route('front.resetPasswordCustomer') . "/" . base64_encode($input['email']);
      } else {
        return array('error' => 1, 'message' => 'can\'t find user with this email address');
      }
    }

    $arr['template'] = 'emails.resetpassword';
    $arr['from_email'] = 'optimus@funedge.co.id';
    $arr['subject'] = 'Reset Password';
    $arr['email'] = $input['email'];

    try {
      $sent = API::sendEmail($arr);
    } catch (Exception $ex) {

    }

    if (!$sent) {
      return array('error' => 1, 'message' => 'Error Sending Email');
    } else {
      return array('error' => 0, 'message' => 'Email have been sent, please check your email');
    }
  }

  public function resetPasswordCustomer($email) {
    $decode_email = base64_decode($email);
    $type = 'customer';

    return view('forgetpassword', compact('decode_email', 'type'));
  }

  public function resetPasswordSeller($email) {
    $decode_email = base64_decode($email);
    $type = 'seller';

    return view('forgetpassword', compact('decode_email', 'type'));
  }

  public function updatePassword(Request $request) {
    $input = $request::all();
    $email = $input['email'];
    $password = $input['password'];
    $retype_password = $input['retype_password'];
    $type = $input['type'];

    if ($password !== $retype_password) {
      return redirect()->back()->with('message', 'both of password are different');
    } else {
      if ($type == 'customer') {
        Front_model::updateCustomerPassword(array('email' => $email, 'password' => md5($password)));
      } else {
        Front_model::updateSellerPassword(array('email' => $email, 'password' => md5($password)));
      }
      return redirect()->back()->with('message', 'paswword have been change');
    }
  }

  public function signupVerification($email) {
    $decoded_email = base64_decode($email);
    Front_model::updateCustomerStatus($decoded_email);
    return redirect('/');
  }

  function wishlist(Request $request) {
    $session = Session::get(null);
    $param = $request::all();
    $wishlist = Front_model::getAllWishlist('', $session['user_id']);
    if (count($wishlist) > 0) {
      $car_id_array = json_decode($wishlist[0]->car_id);
      if (in_array($param['car_id'], $car_id_array)) {
        return array('error' => 1, 'message' => 'car already in wishlist');
      } else {
        array_push($car_id_array, $param['car_id']);
        $arr = [
            'customer_id' => $session['user_id'],
            'car_id' => json_encode($car_id_array),
            'id' => $wishlist[0]->id,
        ];
        $result = Front_model::updateWishlist($arr);
      }
    } else {
      $arr = [
          'customer_id' => $session['user_id'],
          'car_id' => json_encode([$param['car_id']])
      ];
      $result = Front_model::insertWishlist($arr);
    }

    if ($result) {
      return array('error' => 0, 'message' => 'car have been added to wishlist');
    } else {
      return array('error' => 1, 'message' => 'car failed to add wishlist');
    }
  }

  public function term() {
    $session = Session::get(null);
    $optimus['captcha_src'] = captcha_src('flat');
    $optimus['config'] = API::getDefaultConfig();
    $optimus['term_condition'] = API::getSetting('term');

    return view('term', compact('session'))->with($optimus);
  }

  public function contact() {
    $session = Session::get(null);
    $optimus['captcha_src'] = captcha_src('flat');
    $optimus['config'] = API::getDefaultConfig();
    $optimus['contact_info'] = API::getSetting('contact');

    return view('contact', compact('session'))->with($optimus);
  }

  protected function refreshCaptcha(Request $request) {
    return json_encode(array('source' => captcha_src('flat')));
  }

  public function sendContact(Request $request) {
    $param = $request::all();

    if ($param['name'] !== '' && $param['email'] !== '' && $param['subject'] !== '' && $param['message'] !== '' && captcha_check($param['captcha']) !== false) {

      $arr['template'] = 'emails.contact';
      $arr['from_email'] = $param['email'];
      $arr['name'] = $param['name'];
      $arr['subject'] = $param['subject'];
      $arr['message'] = $param['message'];
      $arr['email'] = API::getSetting('contact email');

      try {
        API::sendEmail($arr);
      } catch (Exception $ex) {
        
      }


      $arr = array(
          'error' => 0,
          'message' => 'Email have been sent',
      );
    } else {
      if (captcha_check($param['captcha']) === false) {
        $message = 'The captcha is incorrect';
      } else {
        $message = 'Please check the field again';
      }

      $arr = array(
          'error' => 1,
          'message' => $message,
      );
    }
    return $arr;
  }

  public function productDetail($id) {
    $session = Session::get(null);
    $make_array = Front_model::getAllMake();
    $body_type_array = Front_model::getAllBodyType();
    $car_engine_array = Front_model::getAllCarEngine();
    $optimus['config'] = API::getDefaultConfig();
    
    $optimus['car_filter_option'] = [
        'make' => $make_array,
        'body_type' => $body_type_array,
        'car_engine' => $car_engine_array,
    ];

    $car_array = Front_model::getAllCar([$id], '');
    
    if(count($car_array)== 0){
      return redirect('/');
    }

    if($car_array[0]->status == 2){
      echo "
          <script>
            alert('Car already sold');
            window.location.href = '/';
          </script>
        ";
      // return redirect('/');
    }

    if($car_array[0]->status == 4){
      echo "
          <script>
            alert('Car not available');
            window.location.href = '/';
          </script>
        ";
      // return redirect('/');
    }
    
    $car_images = Front_model::getAllCarImage('', $id, false, 4);
    $car = [];
    if (count($car_array) > 0) {
      $car = $car_array[0];
      $car->images = $car_images;
    }

    $recently_view = Session::get('recently_view');
    $recently_view[$id] = $car_array[0];
    Session::set('recently_view', $recently_view);

    $optimus['insurance_fee'] = trim(API::getSetting('insurance'));
    $optimus['inspection_fee'] = trim(API::getSetting('inspection'));
    $optimus['ocean_freight'] = trim(API::getSetting('ocean_freight'));
    $optimus['destination_country'] = Front_model::getAllDestinationCountry();
    $optimus['accessories'] = Front_model::getAllAccessories();
    $optimus['active_menu'] = 'gallery';
    $optimus['month_array'] = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    return view('productdetail', compact('session', 'car'))->with($optimus);
  }

  public function getDestinationPort(Request $request) {
    $session = Session::get(null);
    $param = $request::all();
    $destination_array = Front_model::getDestinationPort('', $param['destination_country'], false, $param['city']);

    echo json_encode($destination_array);
  }

  public function notify($car_id)
  {
    #only customer can get notification
    if (Session::get('login_type') != 'customer') {
      echo "<script>alert('Please use customer account to get notification'); window.location.href='/'</script>";
    }
    $customer_id = Session::get('user_id');

    DB::beginTransaction();

    #customer can't waiting same car
    $check = Front_model::getAllNotification(true, array('customer_id' => $customer_id, 'car_id' => $car_id, 'sent' => '0'));
    
    if ($check > 0) {
      echo "<script>alert('This car already in your notify lists.'); window.location.href='/'</script>";
    }

    $data = [
      'customer_id' => $customer_id,
      'car_id' => $car_id,
    ];
    Front_model::insertNotification($data);

    if ($check > 0) {
      DB::rollBack();
    } else {
      DB::commit();
    }

    echo "<script>alert('You will be notified by email when this vehicle is available, Thanks!'); window.location.href='/'</script>";
  }

  public function cancelNegotiation(NewRequest $request)
  {
    $return = [];
    DB::beginTransaction();
    try {
      $negotiations = DB::table('negotiation AS n')
        ->select('n.*', 'i.due_date')
        ->leftJoin('invoice AS i', 'i.negotiation_id', '=', 'n.id')
        ->where('n.car_id', $request->car_id)
        ->whereRaw('i.due_date <= ?', [$request->due_date])
        ->get();
      foreach ($negotiations as $key => $negotiation) {
        #update status negotiation
        $data = [
          'status' => 0,
          'id' => $negotiation->id,
        ];
        Front_model::updateNegotiationStatus($data);

        #insert negotiationline / chat
        $chat = 'The negotiation has been canceled, because there is no settlement.';
        $data_chat = array(
          'negotiation_id' => $negotiation->id,
          'chat' => $chat,
          'file' => '',
          'user_chat_id' => '',
        );
        Front_model::insertNegotiationLine($data_chat);  
      }

      #update car status
      $data_car = [
        'status' => 1,
        'flag_payment' => 0,
        'due_date' => null,
        'id' => $request->car_id,
      ];
      Front_model::updateCarStatus($data_car);
      Front_model::updateCarPayment($data_car);
      Front_model::updateCarDueDate($data_car);


      $param = [
        'car_id' => $request->car_id,
        'customer_id' => $negotiation->customer_id,
        'sent' => '0',
        'subject' => 'Reminder',
      ];
      $notification = Front_model::getAllNotification(false, $param);
      foreach ($notification as $key => $row) {
        $sent = API::newSendEmail($param);
        $data_notif = [
          'sent' => 1,
          'updatedon' => date('Y-m-d H:i:s'),
        ];
        Front_model::updateNotification($data_notif, $row->id);
      }
      DB::commit();

      $return['response'] = 1;
      return json_encode($return);
    } catch (\Exception $e) {
      DB::rollBack();
      $return['response'] = 0;
      $return['message'] = [$e->getMessage()];
      return json_encode($return);
    }
  }

  
  public function test() {
    echo '<!DOCTYPE html> 
      <html> 
      <body> 

      <video width="400" controls>
        <source src="http://optimus.funedge.co.id/test.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
      </video>

      
      </body> 
      </html>';

  }

  public function testemail()
  {
    $data = [
      'nama' => 'Tester',
      'email' => 'hendryrafdi@gmail.com',
    ];
    
    $sent = Mail::send('emails.testemail', ['data' => $data], function ($message) {
      $message->from('optimuscartrade@gmail.com', 'Optimus')->subject('Hello World');
      $message->to('hendryrafdi@gmail.com', 'Hendry');
      // $message->getSwiftMessage();
      // $type = $message->getHeaders()->get('Content-Type');
      //         $type->setParameter('charset', 'iso-8859-1');
    });
    if (!$sent) {
      echo "error";
    } else {
      return "sent";
    }
  }
}
