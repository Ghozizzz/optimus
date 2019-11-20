<?php 
namespace Modules\Dealers\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\DEALERS\Models\dealersModel;
use Illuminate\Http\Request;
use Validator;
use Session;

class loginController extends Controller {
	
public function loginIndex(){
		return view('dealers::login');
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
			$db = dealersModel::checkLogin($data['email'], md5($data['password']));
			if(count($db) === 1){
		        Session::set('user_id',$db[0]->id);
		        Session::set('login_type','dealer');
				$store = Session::put('email', $data['email']);
				return redirect('dealers/car');
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
			$db = dealersModel::checkSession($getter);
			if(count($db) !== 1) {
				return redirect('dealers/signin');
			}
		}else{
			return redirect('dealers/signin');
		}
	}
	public function loginLogout(){
		session()->flush();
		return redirect('/');
	}
	
}