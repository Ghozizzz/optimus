<?php 

namespace Modules\LOGISTIC\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\LOGISTIC\Models\logisticModel;
use Illuminate\Http\Request;
use Validator;
use Session;
class loginController extends Controller {
		// Login
	public function loginIndex(){
		return view('logistic::login');
	}
	public function loginProcess(Request $request){
		$data = [
			'email' => $request->email,
			'password' => $request->password
		];
		$rule = [
			'email' => 'required',
			'password' => 'required'
		];
		$validator = Validator::make($data, $rule);
		if ($validator->passes()) {
			$db = logisticModel::checkLogin($data['email'], md5($data['password']));
			if(count($db) === 1){
        Session::put('user_id', $db[0]->id);
				$store = Session::put('email', $data['email']);
				return redirect('logistic/portcharge');
			}else{
				session()->flash('faileds', 'Incorrect email/password!');
				return redirect()->back();
			}
		}
		if ($validator->fails()) {
			session()->flash('faileds', 'Incorrect email/password!');
			return redirect()->back();
		}
	}
	public function checkSession(){
		$getter = Session::get('email');
		if ($getter !== null) {
			$db = logisticModel::checkSession($getter);
			if(count($db) !== 1) {
				return redirect('logistic/signin');
			}
		}else{
			return redirect('logistic/signin');
		}
	}
	public function loginLogout(){
		session()->flush();
		return redirect('logistic/signin');
	}

}
