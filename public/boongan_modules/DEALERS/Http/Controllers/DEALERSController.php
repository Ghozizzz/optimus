<?php
namespace Modules\Dealers\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\DEALERS\Models\dealersModel;
use Illuminate\Http\Request;
use Validator;
use Session;
use App\Http\Controllers\API;

class DEALERSController extends Controller {
	
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
		$getMax = 1;
		$descasc = "asc";
		$page = 0;
		if(isset($_GET['max'])) $getMax = $_GET['max'];
		if(isset($_GET['descasc'])) $descasc = $_GET['descasc'];
		if(isset($_GET['page'])) $page = ($_GET['page'] - 1) * $getMax;
		$db = dealersModel::car($getMax, $descasc, $page);
    
    $car_ids = [];
    if(is_array($db['data']) && count($db['data'])>0){
      foreach($db['data'] as $car_detail){
      
      }
    }
    
    
		$optimus['negotiation'] = dealersModel::getAllNegotiation($getMax, $descasc, $page);
    $optimus['config'] = API::getDefaultConfig();
		return view('dealers::car', compact('db','getMax','descasc'))->with($optimus);
	}
	public function carView($id){
		$db = dealersModel::carView($id);
		return response()->json($db);
	}
	// Negotiation
	public function negotiationIndex(){
		$getMax = 1;
		$descasc = "asc";
		$page = 0;
		if(isset($_GET['max'])) $getMax = $_GET['max'];
		if(isset($_GET['descasc'])) $descasc = $_GET['descasc'];
		if(isset($_GET['page'])) $page = ($_GET['page'] - 1) * $getMax;
		$db = dealersModel::negotiationGroup($getMax, $descasc, $page);
    $optimus['config'] = API::getDefaultConfig();
		return view('dealers::negotiation', compact('db','getMax','descasc'))->with($optimus);
	}
  
  public function negotiationCar($car_id){
		$getMax = 1;
		$descasc = "asc";
		$page = 0;
		if(isset($_GET['max'])) $getMax = $_GET['max'];
		if(isset($_GET['descasc'])) $descasc = $_GET['descasc'];
		if(isset($_GET['page'])) $page = ($_GET['page'] - 1) * $getMax;
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