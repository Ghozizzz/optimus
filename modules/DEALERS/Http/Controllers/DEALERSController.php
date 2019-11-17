<?php
namespace Modules\Dealers\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\DEALERS\Models\dealersModel;
use Modules\Admin\Models\adminModel;
use Illuminate\Http\Request;
use Validator;
use Session;
use App\Http\Controllers\API;

class DEALERSController extends Controller {
	private $optimus = [];
  
	public function __construct() {
        $this->session = Session::get(null);
        $checkAuth = dealersModel::checkSession(Session::get('email'));

        if (count($checkAuth) === 0) {
        	$this->middleware('authDEALERS', ['except' => 'getLogout']);
        	session()->forget('email');
        }
        if (!session()->has('email')) {
            $this->middleware('authDEALERS', ['except' => 'getLogout']);
        }
    }

	public function index(){
    $optimus['config'] = API::getDefaultConfig();

		return view('dealers::index')->with($optimus);
	}
	// Car
	public function carIndex(){
    $this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter'])? str_slug($_GET['filter'],' '):'';
    $descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
		$seller_id = Session::get('user_id');
    $db = adminModel::car($getMax, $descasc, $page, $filter, $seller_id);
    
    return view('dealers::car', compact('db','getMax','descasc'))->with($this->optimus);
	}
	public function carView($id){
		$db = dealersModel::carView($id);
		return response()->json($db);
	}
	// Negotiation
	public function negotiationIndex(){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter'])? str_slug($_GET['filter'],' '):'';
    $descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
		$db = dealersModel::negotiationGroup($getMax, $descasc, $page, $filter);
    
    return view('dealers::negotiation', compact('db','getMax','descasc'))->with($this->optimus);
	}
  
  public function negotiationCar($car_id){
		$this->optimus['config'] = API::getDefaultConfig();
		$filter = isset($_GET['filter'])? str_slug($_GET['filter'],' '):'';
    $descasc = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
		
    if(isset($_GET['max']) && $_GET['max'] == 'all'){
      $getMax = 99999;
      $page = 0;
    }else{
      $getMax = isset($_GET['max']) ? $_GET['max'] : 5;
      $page = isset($_GET['page']) ? ($_GET['page'] - 1) * $getMax : 0;
    }
    $db = dealersModel::negotiation($getMax, $descasc, $page, $car_id);
    $optimus['config'] = API::getDefaultConfig();
		return view('dealers::negotiation_per_car', compact('db','getMax','descasc'))->with($optimus);
	}
  
	public function negotiationView($id){
		$db = dealersModel::negotiationView($id);
    $optimus['config'] = API::getDefaultConfig();
		return view('dealers::negotiationdetail', compact('db', 'id'))->with($optimus);
	}
}