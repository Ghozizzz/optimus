<?php 
namespace Modules\Logistic\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\LOGISTIC\Models\logisticModel;
use Illuminate\Http\Request;
use Validator;
use Session;
use App\Http\Controllers\API;



class LOGISTICController extends Controller {
  private $optimus = [];
  
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

        $db = logisticModel::portCharge($getMax, $descasc, $page);
    
      return view('logistic::portcharge', compact('db','getMax','descasc'))->with($this->optimus);
    }	
    public function portChargeSave(Request $request){
      $params = $request->all();
        $validator = Validator::make([
            'newPortDischarge' => $params['newPortDischarge'],
            'newPortDestinaton' => $params['newPortDestination'],
            'newPrice' => isset($params['newPrice'])?$params['newPrice']:null,
            'newVolumePrice' => isset($params['newVolumePrice'])?$params['newVolumePrice']:null,
        ], [
            'newPortDischarge' => 'required',
            'newPortDestinaton' => 'required',
        ]);
        if ($validator->passes()) {
            $data = array();
            $data = [
                'discharge_id' => $params['newPortDischarge'],
                'destination_id' => $params['newPortDestination'],
                'price' => $params['newPrice'],
                'volume_price' => $params['newVolumePrice']
            ];

            $db = logisticModel::portChargeSave($data);
            if($db){
              return response()->json(array('error' => 0,'message'=>'data have been save'));
            }else{
              return response()->json(array('error' => 1,'message'=>'failed to save'));
            }
            
            
        }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }
    }
    public function portChargeUpdate(Request $request){
        $params = $request->all();
        $validator = Validator::make([
            'editPortDischarge' => $params['editPortDischarge'],
            'editPortDestinaton' => $params['editPortDestination'],
            'id' => $params['id'],
        ], [
            'editPortDischarge' => 'required',
            'editPortDestinaton' => 'required',
        ]);
        
        if ($validator->passes()) {
            if (!empty($params['editPortDischarge']))$data['discharge_id'] = $params['editPortDischarge'];
            if (!empty($params['editPortDestination']))$data['destination_id'] = $params['editPortDestination'];
            if (isset($params['editPrice']))$data['price'] = $params['editPrice'];
            if (isset($params['editVolumePrice']))$data['volume_price'] = $params['editVolumePrice'];
            
            $db = logisticModel::portChargeUpdate($params['id'], $data);
            
            if($db){
              return [
                  'error' => 'data have been save',
              ];
            }else{
              return [
                  'error' => 'no changes',
              ];
            }
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