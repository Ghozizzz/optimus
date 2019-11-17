<?php 
namespace Modules\Logistic\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\LOGISTIC\Models\logisticModel;
use Illuminate\Http\Request;
use Validator;
use Session;
use App\Http\Controllers\API;



class LOGISTICController extends Controller {

	public function __construct() {
        $this->session = Session::get(null);
        $checkAuth = logisticModel::checkSession(Session::get('email'));

        if (count($checkAuth) === 0) {
        	$this->middleware('authLOGISTIC', ['except' => 'getLogout']);
        	session()->forget('email');
        }
        if (!session()->has('email')) {
            $this->middleware('authLOGISTIC', ['except' => 'getLogout']);
        }
    }
	public function index()
	{
    $optimus['config'] = API::getDefaultConfig();

		return view('logistic::index')->with($optimus);
	}
    public function portCharge(){
        $getMax = 1;
        $descasc = "asc";
        $page = 0;
        if(isset($_GET['max'])) $getMax = $_GET['max'];
        if(isset($_GET['descasc'])) $descasc = $_GET['descasc'];
        if(isset($_GET['page'])) $page = ($_GET['page'] - 1) * $getMax;
        $db = logisticModel::portCharge($getMax, $descasc, $page);
        $optimus['config'] = API::getDefaultConfig();

        return view('logistic::portcharge', compact('db','getMax','descasc'))->with($optimus);
    }	
    public function portChargeSave(Request $request){
        $validator = Validator::make([
            'newPortDischarge' => $request->newPortDischarge,
            'newPortDestinaton' => $request->newPortDestinaton,
            'newPrice' => $request->newPrice,
        ], [
            'newPortDischarge' => 'required',
            'newPortDestinaton' => 'required',
            'newPrice' => 'required'
        ]);
        if ($validator->passes()) {
            $data = array();
            $data = [
                'discharge_id' => $request->newPortDischarge,
                'destination_id' => $request->newPortDestinaton,
                'price' => $request->newPrice
            ];
            $db = logisticModel::portChargeSave($data);
            return $db;
        }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }
    }
    public function portChargeUpdate($id, Request $request){
           $validator = Validator::make([
            'editPortDischarge' => $request->editPortDischarge,
            'editPortDestinaton' => $request->editPortDestinaton,
            'editPrice' => $request->editPrice,
        ], [
            'editPortDischarge' => 'required',
            'editPortDestinaton' => 'required',
            'editPrice' => 'required'
        ]);
        if ($validator->passes()) {
            if (!empty($request->editPortDischarge))$data['discharge_id'] = $request->editPortDischarge;
            if (!empty($request->editPortDestinaton))$data['destination_id'] = $request->editPortDestinaton;
            if (!empty($request->editPrice))$data['price'] = $request->editPrice;
            $db = logisticModel::portChargeUpdate($id, $data);
            return $db;
        }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }
    }
    public function portChargeView($id){
        $db = logisticModel::portChargeView($id);
        return response()->json($db);
    }
    public function portChargeDelete($id){
        $db = logisticModel::deletePortCharge($id);
        return $db;
    }
}