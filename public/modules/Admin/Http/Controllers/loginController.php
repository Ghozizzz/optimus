<?php 

namespace Modules\Admin\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Admin\Models\adminModel;
use Illuminate\Http\Request;
use Validator;
use Session;
use DB;
class loginController extends Controller {
		// Login
	public function loginIndex(){
		return view('admin::login');
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
			$db = adminModel::checkLogin($data['email'], md5($data['password']));    
			if(count($db) === 1){
		$db['privilege'] = DB::table('privilege')->where('id', $db[0]->type)->first();
        Session::put('user_id',$db[0]->id);
        Session::put('login_type',$db['privilege']->name);      
        $store = Session::put('email', $data['email']);
				return redirect('admin');
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
			$db = adminModel::checkSession($getter);
			if(count($db) !== 1) {
				return redirect('admin/signin');
			}
		}else{
			return redirect('admin/signin');
		}
	}
	public function loginLogout(){
		session()->flush();
		return redirect('admin/signin');
	}
	

}
