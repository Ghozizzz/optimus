<?php

namespace Modules\Admin\Models;

use DB;
use Session;
use Route;

class adminModel {

	// Login
	public static function checkLogin($email, $password){
		$db = DB::table('user')->where([
		    ['email', '=', $email],
		    ['password', '=', $password]
		])
            ->where('isdeleted', '=', '0')
            ->where('status', '=', '1')
            ->get();
		return $db;
	}
	public static function checkSession($email){
		$db = DB::table('user')
		->join('privilege', 'privilege.id', '=', 'user.type')
		->select('user.*', 'privilege.name as p')
		->where([
			['email', '=', $email]
		])
		->first();
		return $db;
	}
	//Cbeck user id
	public static function checkCustomerById($id){
		$db = DB::table('customer')
		->where('id', $id)
		->first();
		return $db;
	}
	public static function checkUserById($id){
		$db = DB::table('user')
		->where('id', $id)
		->first();
		return $db;
	}
	//Privilege
	public static function privilege($email){
		$db = DB::table('user')->where([
		    ['email', '=', $email]
		])->first();
		return $db;
	}
	//Dealer
	public static function dealer($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('seller')->count();
		$db['data'] = DB::table('seller')
                ->orderBy('seller.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->get();
		return $db;
	}
	public static function saveDealer($data = array()){
		try{
			$db = DB::table('seller')->insert(
		    [
		    	'email' => $data['email'], 
		    	'password' => $data['password'],
		    	'status' => $data['status'],
		    	'country' => $data['country'],
		    	'address' => $data['address'],
		    	'telephone' => $data['telephone'],
		    	'fax' => $data['fax'],
		    	'contact' => $data['contact'],
		    	'company_name' => $data['company_name'],
		    	'pic_name' => $data['pic_name'],
		    	'bank_name' => $data['bank_name'],
		    	'account_number' => $data['account_number'],
		    	'account_name' => $data['account_name'],
		    	'photo' => $data['photo']
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateDealer($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['email']))$datas['email'] = $data['email'];
 			if (isset($data['password']))$datas['password'] = $data['password'];
 			if (isset($data['status']))$datas['status'] = $data['status'];
 			if (isset($data['country']))$datas['country'] = $data['country'];
 			if (isset($data['address']))$datas['address'] = $data['address'];
 			if (isset($data['telephone']))$datas['telephone'] = $data['telephone'];
 			if (isset($data['fax']))$datas['fax'] = $data['fax'];
 			if (isset($data['contact']))$datas['contact'] = $data['contact'];
 			if (isset($data['company_name']))$datas['company_name'] = $data['company_name'];
 			if (isset($data['pic_name']))$datas['pic_name'] = $data['pic_name'];
 			if (isset($data['bank_name']))$datas['bank_name'] = $data['bank_name'];
 			if (isset($data['account_number']))$datas['account_number'] = $data['account_number'];
 			if (isset($data['account_name']))$datas['account_name'] = $data['account_name'];
 			if (isset($data['photo']))$datas['photo'] = $data['photo'];
		$db = DB::table('seller')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteDealer($id){
		$db = DB::table('seller')->where('id', '=', $id)->delete();
		return $db;
	}
	public static function dealerView($id){
		$db = DB::table('seller')->where('id', '=', $id)->first();
		return $db;
	}
  
  //Banner
	public static function banner($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('banner')->count();
		$db['data'] = DB::table('banner')
                ->orderBy('banner.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->get();
		return $db;
	}
	public static function saveBanner($data = array()){
		try{
			$db = DB::table('banner')->insert(
		    [
		    	'name' => $data['name'], 
		    	'url' => $data['url'],
		    	'isactive' => $data['status'],
		    	'picture' => $data['picture']
        ] 
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateBanner($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['name']))$datas['name'] = $data['name'];
 			if (isset($data['url']))$datas['url'] = $data['url'];
 			if (isset($data['status']))$datas['isactive'] = $data['status'];
 			if (isset($data['picture']))$datas['picture'] = $data['picture'];
		$db = DB::table('banner')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteBanner($id){
		$db = DB::table('banner')->where('id', '=', $id)->delete();
		return $db;
	}
	public static function bannerView($id){
		$db = DB::table('banner')->where('id', '=', $id)->first();
		return $db;
	}
  
  public static function country($limit = '', $descasc = "asc", $skip = ''){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('country')->count();
		$db['data'] = DB::table('country')
                ->orderBy('country_name', $descasc);
    
    if($skip !== ''){
      $db['data']->skip($skip);
    }
    
    if($limit !== ''){
      $db['data']->take($limit);
    }
                
    $db['data'] = $db['data']->get();
		return $db;
	}
  
  //Port Destination
	public static function portDestination($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('port_destination')->count();
		$db['data'] = DB::table('port_destination')
                ->orderBy('port_name', $descasc)
                ->skip($skip)
                ->take($limit)
                ->get();
		return $db;
	}
  
  public static function savePortDestination($data = array()){
			try{
			$db = DB::table('port_destination')->insert(
		    [
		    	'id_code' => $data['id_code'], 
		    	'country_code' => $data['country_code'],
		    	'country_name' => $data['country_name'],
		    	'port_code' => $data['port_code'],
		    	'port_name' => $data['port_name'],		    	
        ]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updatePortDestination($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['id_code']))$datas['id_code'] = $data['id_code'];
 			if (isset($data['country_code']))$datas['country_code'] = $data['country_code'];
 			if (isset($data['country_name']))$datas['country_name'] = $data['country_name'];
 			if (isset($data['port_code']))$datas['port_code'] = $data['port_code'];
 			if (isset($data['port_name']))$datas['port_name'] = $data['port_name'];
 	
      
		$db = DB::table('port_destination')
            ->where('id', $id)
            ->update(
            	$datas
            );
    return $db;       
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
  
  public static function deletePortDestination($id){
		$db = DB::table('port_destination')->where('id', '=', $id)->delete();
    
    return $db;
	}
	public static function portDestinationView($id){
		$db = DB::table('port_destination')
		->where('id', '=', $id)
		->first();
		return $db;
	}
  
  //Port Discharge
	public static function portDischarge($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('port_discharge')->count();
		$db['data'] = DB::table('port_discharge')
                ->orderBy('port_name', $descasc)
                ->skip($skip)
                ->take($limit)
                ->get();
		return $db;
	}
  
  public static function savePortDischarge($data = array()){
			try{
			$db = DB::table('port_discharge')->insert(
		    [
		    	'id_code' => $data['id_code'], 
		    	'country_code' => $data['country_code'],
		    	'country_name' => $data['country_name'],
		    	'port_code' => $data['port_code'],
		    	'port_name' => $data['port_name'],		    	
        ]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updatePortDischarge($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['id_code']))$datas['id_code'] = $data['id_code'];
 			if (isset($data['country_code']))$datas['country_code'] = $data['country_code'];
 			if (isset($data['country_name']))$datas['country_name'] = $data['country_name'];
 			if (isset($data['port_code']))$datas['port_code'] = $data['port_code'];
 			if (isset($data['port_name']))$datas['port_name'] = $data['port_name'];
 	
      
		$db = DB::table('port_discharge')
            ->where('id', $id)
            ->update(
            	$datas
            );
    return $db;       
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
  
  public static function deletePortDischarge($id){
		$db = DB::table('port_discharge')->where('id', '=', $id)->delete();
    
    return $db;
	}
	public static function portDischargeView($id){
		$db = DB::table('port_discharge')
		->where('id', '=', $id)
		->first();
		return $db;
	}
  
	//Customer
	public static function customer($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('customer')->where('isdeleted', '0')->count();
		$db['data'] = DB::table('customer')
                ->orderBy('customer.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->where('isdeleted', '0')
                ->get();
		return $db;
	}
	public static function saveCustomer($data = array()){
			try{
			$db = DB::table('customer')->insert(
		    [
		    	'email' => $data['email'], 
		    	'password' => $data['password'],
		    	'status' => $data['status'],
		    	'name' => $data['name'],
		    	'address' => $data['address'],
		    	'phone' => $data['phone'],
		    	'birthday' => $data['birthday'],
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateCustomer($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['email']))$datas['email'] = $data['email'];
 			if (isset($data['password']))$datas['password'] = $data['password'];
 			if (isset($data['status']))$datas['status'] = $data['status'];
 			if (isset($data['name']))$datas['name'] = $data['name'];
 			if (isset($data['address']))$datas['address'] = $data['address'];
 			if (isset($data['phone']))$datas['phone'] = $data['phone'];
 			if (isset($data['birthday']))$datas['birthday'] = $data['birthday'];
		$db = DB::table('customer')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteCustomer($id){
		$db = DB::table('customer')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function customerView($id){
		$db = DB::table('customer')
		->where('id', '=', $id)
		->first();
		return $db;
	}

	// Sales

	public static function sales($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
			$db = [];
			$db['privilege'] = DB::table('privilege')->where('name', 'sales')->first();
			$db['total'] = DB::table('user')->where('isdeleted', '0')->where('type', $db['privilege']->id)->count();
			$db['data'] = DB::table('user')
	                ->orderBy('user.id', $descasc)
	                ->skip($skip)
	                ->take($limit)
	                ->where('isdeleted', '0')
	                ->where('type', $db['privilege']->id)
	                ->get();
			return $db;
	}
	public static function saveSales($data = array()){
			try{
			$db['privilege'] = DB::table('privilege')->where('name', 'sales')->first();
			$db = DB::table('user')->insert(
		    [
		    	'email' => $data['email'], 
		    	'password' => $data['password'],
		    	'status' => $data['status'],
		    	'name' => $data['name'],
		    	'address' => $data['address'],
		    	'phone' => $data['phone'],
		    	'birthday' => $data['birthday'],
		    	'type' => $db['privilege']->id
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateSales($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['email']))$datas['email'] = $data['email'];
 			if (isset($data['password']))$datas['password'] = $data['password'];
 			if (isset($data['status']))$datas['status'] = $data['status'];
 			if (isset($data['name']))$datas['name'] = $data['name'];
 			if (isset($data['address']))$datas['address'] = $data['address'];
 			if (isset($data['phone']))$datas['phone'] = $data['phone'];
 			if (isset($data['birthday']))$datas['birthday'] = $data['birthday'];
		$db = DB::table('user')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteSales($id){
		$db = DB::table('user')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function salesView($id){
		$db = DB::table('user')
		->where('id', '=', $id)
		->first();
		return $db;
	}
	//Logistic
	public static function logistic($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('logistic')->count();
		$db['data'] = DB::table('logistic')
                ->orderBy('logistic.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->get();
		return $db;
	}
	public static function saveLogistic($data = array()){
		try{
			$db = DB::table('logistic')->insert(
		    [
		    	'email' => $data['email'], 
		    	'password' => $data['password'],
		    	'status' => $data['status'],
		    	'country' => $data['country'],
		    	'address' => $data['address'],
		    	'telephone' => $data['telephone'],
		    	'fax' => $data['fax'],
		    	'contact' => $data['contact'],
		    	'company_name' => $data['company_name'],
		    	'pic_name' => $data['pic_name'],
		    	'bank_name' => $data['bank_name'],
		    	'account_number' => $data['account_number'],
		    	'account_name' => $data['account_name'],
		    	'photo' => $data['photo']
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateLogistic($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['email']))$datas['email'] = $data['email'];
 			if (isset($data['password']))$datas['password'] = $data['password'];
 			if (isset($data['status']))$datas['status'] = $data['status'];
 			if (isset($data['country']))$datas['country'] = $data['country'];
 			if (isset($data['address']))$datas['address'] = $data['address'];
 			if (isset($data['telephone']))$datas['telephone'] = $data['telephone'];
 			if (isset($data['fax']))$datas['fax'] = $data['fax'];
 			if (isset($data['contact']))$datas['contact'] = $data['contact'];
 			if (isset($data['company_name']))$datas['company_name'] = $data['company_name'];
 			if (isset($data['pic_name']))$datas['pic_name'] = $data['pic_name'];
 			if (isset($data['bank_name']))$datas['bank_name'] = $data['bank_name'];
 			if (isset($data['account_number']))$datas['account_number'] = $data['account_number'];
 			if (isset($data['account_name']))$datas['account_name'] = $data['account_name'];
 			if (isset($data['photo']))$datas['photo'] = $data['photo'];
		$db = DB::table('logistic')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteLogistic($id){
		$db = DB::table('logistic')->where('id', '=', $id)->delete();
		return $db;
	}
	public static function logisticView($id){
		$db = DB::table('logistic')->where('id', '=', $id)->first();
		return $db;
	}
	//Finance

	public static function user($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
			$db = [];
			$db['privilege'] = DB::table('privilege')->get();
			$db['total'] = DB::table('user')->where('isdeleted', '0')->count();
			$db['data'] = DB::table('user')
	                ->orderBy('user.id', $descasc)
	                ->skip($skip)
	                ->take($limit)
	                ->where('isdeleted', '0')
	                ->get();
			return $db;
	}
	public static function saveUser($data = array()){
			try{
			$db['privilege'] = DB::table('privilege')->get();
			$db = DB::table('user')->insert(
		    [
		    	'email' => $data['email'], 
		    	'password' => $data['password'],
		    	'status' => $data['status'],
		    	'name' => $data['name'],
		    	'address' => $data['address'],
		    	'phone' => $data['phone'],
		    	'birthday' => $data['birthday'],
		    	'type' => $data['privilege'],
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateUser($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['email']))$datas['email'] = $data['email'];
 			if (isset($data['password']))$datas['password'] = $data['password'];
 			if (isset($data['status']))$datas['status'] = $data['status'];
 			if (isset($data['name']))$datas['name'] = $data['name'];
 			if (isset($data['address']))$datas['address'] = $data['address'];
 			if (isset($data['phone']))$datas['phone'] = $data['phone'];
 			if (isset($data['birthday']))$datas['birthday'] = $data['birthday'];
 			if (isset($data['privilege'])){
        $datas['type'] = $data['privilege'];
      }else{
        $datas['type'] = null;
      }
      $db = DB::table('user')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
      if($errorCode == '1062'){
          return 'Duplicate Entry';
      }
		}
	}
	public static function deleteUser($id){
		$db = DB::table('user')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function userView($id){
		$db = DB::table('user')
		->where('id', '=', $id)
		->first();
		return $db;
	}
//Car
	public static function car($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('car')->where('car.isdeleted','=', '0')->count();
		$db['data'] = DB::table('car')
                ->orderBy('car.id', $descasc)
                ->skip($skip)
                ->take($limit)
              	->Join('car_make','car_make.id','=','car.make_id')
                ->Join('car_model','car_model.id','=','car.model_id')
              	->leftJoin('user', 'user.id', '=', 'car.agent')
              	->leftJoin('seller', 'seller.id', '=', 'car.seller')
              	->select('car.*', 'seller.pic_name','car_model.model', 'user.name')
                ->where('car.isdeleted','=', '0')
                ->get();
         $db['carmake'] = DB::table('car_make')->get();
         $db['carmodel'] = DB::table('car_model')->get();
         $db['carengine'] = DB::table('car_engine')->get();
         $db['caroption'] = DB::table('car_option')->get();
         $db['carprice'] = DB::table('car_price')->get();
         $db['seller'] = DB::table('seller')->get();
         $db['agent'] = DB::table('user')->get();
		return $db;
	}
	public static function saveCar($data = array()){
			try{
			$db = DB::table('car')->insert(
		    [
		    	'make_id' => $data['make'],
				'model_id' => $data['model'],
				'vin' => $data['vin'],
				'serial' => $data['serial'],
				'registration_year' => $data['registration_year'],
				'registration_month' => $data['registration_month'],
				'distance' => $data['distance'],
				'type' => $data['type'],
				'colour' => $data['colour'],
				'engine' => $data['engine'],
				'price' => $data['price'],
				'currency' => $data['currency'],
				'status' => $data['status'],
				'promotion' => $data['promotion'],
				'state' => $data['state'],
				'fuel' => $data['fuel'],
				'steering' => $data['steering'],
				'manufacture_year' => $data['manufacture_year'],
				'manufacture_month' => $data['manufacture_month'],
				'type2' => $data['type2'],
				'exterior_color' => $data['exteriorcolor'],
				'drive' => $data['drive'],
				'transmission' => $data['transmission'],
				'door' => $data['door'],
				'seat' => $data['seat'],
				'options' => $data['options'],
				'description' => $data['description'],
				'remark' => $data['remark'],
				'comment' => $data['comment'],
				'keyword' => $data['keyword'],
				'seller' => $data['seller'],
				'agent' => $data['agent'],
				'buyer' => $data['buyer'],
				'recommendation' => $data['recommendation'],
				'best_deal' => $data['bestdeal'],
				'best_seller' => $data['bestseller'],
				'hot_car' => $data['hotcar'],
				'interior_color' => $data['interiorcolor'],
				'dimension' => $data['dimension'],
				'picture' => $data['picture'],
        'weight' => $data['weight'],
        'created_by' => $data['created_by'],  
        'youtube' => $data['youtube'],  
        'accessories' => $data['accessories'],  
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
            return $e;
		}
	}
	public static function updateCar($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['make_id']))$datas['make_id'] = $data['make_id'];
			if (isset($data['model_id']))$datas['model_id'] = $data['model_id'];
			if (isset($data['vin']))$datas['vin'] = $data['vin'];
			if (isset($data['serial']))$datas['serial'] = $data['serial'];
			if (isset($data['registration_year']))$datas['registration_year'] = $data['registration_year'];
			if (isset($data['registration_month']))$datas['registration_month'] = $data['registration_month'];
			if (isset($data['distance']))$datas['distance'] = $data['distance'];
			if (isset($data['type']))$datas['type'] = $data['type'];
			if (isset($data['colour']))$datas['colour'] = $data['colour'];
			if (isset($data['engine']))$datas['engine'] = $data['engine'];
			if (isset($data['price']))$datas['price'] = $data['price'];
			if (isset($data['currency_symbol']))$datas['currency'] = $data['currency_symbol'];
			if (isset($data['status']))$datas['status'] = $data['status'];
			if (isset($data['promotion']))$datas['promotion'] = $data['promotion'];
			if (isset($data['state']))$datas['state'] = $data['state'];
			if (isset($data['fuel']))$datas['fuel'] = $data['fuel'];
			if (isset($data['steering']))$datas['steering'] = $data['steering'];
			if (isset($data['manufacture_year']))$datas['manufacture_year'] = $data['manufacture_year'];
			if (isset($data['manufacture_month']))$datas['manufacture_month'] = $data['manufacture_month'];
			if (isset($data['type2']))$datas['type2'] = $data['type2'];
			if (isset($data['exterior_color']))$datas['exterior_color'] = $data['exterior_color'];
			if (isset($data['drive']))$datas['drive'] = $data['drive'];
			if (isset($data['transmission']))$datas['transmission'] = $data['transmission'];
			if (isset($data['door']))$datas['door'] = $data['door'];
			if (isset($data['seat']))$datas['seat'] = $data['seat'];
			if (isset($data['description']))$datas['description'] = $data['description'];
			if (isset($data['remark']))$datas['remark'] = $data['remark'];
			if (isset($data['comment']))$datas['comment'] = $data['comment'];
			if (isset($data['keyword']))$datas['keyword'] = $data['keyword'];
			if (isset($data['seller']))$datas['seller'] = $data['seller'];
			if (isset($data['agent']))$datas['agent'] = $data['agent'];
			if (isset($data['buyer']))$datas['buyer'] = $data['buyer'];
			if (isset($data['recommendation']))$datas['recommendation'] = $data['recommendation'];
			if (isset($data['best_deal']))$datas['best_deal'] = $data['best_deal'];
			if (isset($data['best_seller']))$datas['best_seller'] = $data['best_seller'];
			if (isset($data['hot_car']))$datas['hot_car'] = $data['hot_car'];
			if (isset($data['interior_color']))$datas['interior_color'] = $data['interior_color'];
			if (isset($data['dimension']))$datas['dimension'] = $data['dimension'];
			if (isset($data['picture']))$datas['picture'] = $data['picture'];
			if (isset($data['weight']))$datas['weight'] = $data['weight'];
			if (isset($data['youtube']))$datas['youtube'] = $data['youtube'];
			if (isset($data['accessories']))$datas['accessories'] = $data['accessories'];
			if (isset($data['picture']))$datas['picture'] = $data['picture'];
			
		$db = DB::table('car')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
            
      return $e;      
		}
	}
	public static function deleteCar($id){
		$db = DB::table('car')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function carView($id){
		$db = DB::table('car')
		->where('id', '=', $id)
		->first();
		return $db;
	}	


// Car Model
	public static function carModel($limit = 20, $descasc = "asc", $skip = 0){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('car_model')->where('isdeleted', '0')->count();
		$db['data'] = DB::table('car_model')
                ->orderBy('car_model.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->where('isdeleted', '0')
                ->get();
		return $db;
	}
	public static function saveCarModel($data = array()){
			try{
			$db = DB::table('car_model')->insert(
		    [
		    	'make_id' => $data['make_id'], 
		    	'model' => $data['model']
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateCarModel($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['make_id']))$datas['make_id'] = $data['make_id'];
 			if (isset($data['model']))$datas['model'] = $data['model'];
		$db = DB::table('car_model')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteCarModel($id){
		$db = DB::table('car_model')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function carModelView($id){
		$db = DB::table('car_model')
		->where('id', '=', $id)
		->first();
		return $db;
	}

// Car Make

public static function carMake($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('car_make')->where('isdeleted', '0')->count();
		$db['data'] = DB::table('car_make')
                ->skip($skip)
                ->take($limit)
                ->where('isdeleted', '0')
                ->orderBy('make','ASC')
                ->get();
		return $db;
	}
	public static function saveCarMake($data = array()){
			try{
			$db = DB::table('car_make')->insert(
		    [
		    	'make' => $data['make'], 
		    	'logo' => $data['logo'],
		    	'corporate' => $data['corporate']
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateCarMake($id, $data = array()){
		try{
			$datas = [];
 			if (isset($data['make']))$datas['make'] = $data['make'];
 			if (isset($data['logo']))$datas['logo'] = $data['logo'];
 			if (isset($data['corporate']))$datas['corporate'] = $data['corporate'];
		$db = DB::table('car_make')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteCarMake($id){
		$db = DB::table('car_make')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function carMakeView($id){
		$db = DB::table('car_make')
		->where('id', '=', $id)
		->first();
		return $db;
	}

	/*Car Engine*/

	public static function carEngine($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('car_engine')->where('isdeleted', '0')->count();
		$db['data'] = DB::table('car_engine')
                ->orderBy('car_engine.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->where('isdeleted', '0')
                ->get();
		return $db;
	}
	public static function saveCarEngine($data = array()){
			try{
			$db = DB::table('car_engine')->insert(
		    [
		    	'description' => $data['description']
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function updateCarEngine($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['description']))$datas['description'] = $data['description'];
		$db = DB::table('car_engine')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function deleteCarEngine($id){
		$db = DB::table('car_engine')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function carEngineView($id){
		$db = DB::table('car_engine')
		->where('id', '=', $id)
		->first();
		return $db;
	}
  
  public static function updateCarStatus($id, $status){
		try{
		$db = DB::table('car')
            ->where('id', $id)
            ->update(
            	[
                  'status' => $status
              ]
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
  
	/*Negotiation*/
	public static function negotiation($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$typeLogin = adminModel::checkSession(Session::get('email'))->p;
		$db = [];
		$db['total'] = DB::table('negotiation')->count();
		$db['privilege'] = DB::table('privilege')->where('name', 'sales')->first();
		$db['sales'] = DB::table('user')->where('type', $db['privilege']->id)->get();
		$db['data'] = [];
		if($typeLogin === 'sales'){
			$db['data'] = DB::table('negotiation')
                ->orderBy('negotiation.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->leftjoin('car', 'car.id', '=', 'negotiation.car_id')
                ->leftjoin('user', 'user.id', '=', 'negotiation.user_id')
                ->leftjoin('logistic as L', 'L.id', '=', 'negotiation.logistic_id')
                ->select('negotiation.*', 'car.description', 'car.serial', 'car.distance', 'car.price as car_price', 'car.keyword', 'car.vin', 'car.picture','user.name as name_sales','customer.address as customer_address','customer.email as customer_email', 'customer.name as customer_name', 'NS.description as status_description','L.id as logistic_id','L.company_name as logistic_name')
                ->where('negotiation.user_id', adminModel::checkSession(Session::get('email'))->id)
                ->get();
		}
		if(in_array($typeLogin, ['admin','super admin','super sales'])){
			$db['data'] = DB::table('negotiation')
                ->orderBy('negotiation.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->leftjoin('car', 'car.id', '=', 'negotiation.car_id')
                ->leftjoin('user', 'user.id', '=', 'negotiation.user_id')
                ->leftjoin('customer', 'customer.id', '=', 'negotiation.customer_id')
                ->leftJoin('negotiation_status as NS','NS.id','=','negotiation.status') 
                ->leftjoin('logistic as L', 'L.id', '=', 'negotiation.logistic_id')
                ->select('negotiation.*', 'car.description', 'car.serial', 'car.distance', 'car.price as car_price', 'car.keyword', 'car.vin', 'car.picture','user.name as name_sales', 'customer.address as customer_address','customer.email as customer_email', 'customer.name as customer_name','NS.description as status_description','L.id as logistic_id','L.company_name as logistic_name')
                ->get();
		}
		return $db;
	}
	public static function negotiationViewSales($id){
		$db = DB::table('negotiation')
		->where('id', $id)
		->first();		
		return $db;
	}
	public static function negotiationView($id){
		$db = DB::table('negotiation')
		->leftjoin('negotiation_line', 'negotiation_line.negotiation_id', '=', 'negotiation.id')
		->leftjoin('customer', 'customer.id', '=', 'negotiation_line.customer_chat_id')
		->leftjoin('user', 'user.id', '=', 'negotiation_line.user_chat_id')
		->select('negotiation.*' , 'negotiation_line.chat', 'negotiation_line.file', 'negotiation_line.customer_chat_id', 'negotiation_line.createdon as chatdate','customer.name as customer_name', 'user.name as sales_name', 'negotiation_line.negotiation_id')
		->where('negotiation.id', $id)
		->orderBy('negotiation_line.createdon', "DESC")
		->get();		
    return $db;
	}
	public static function updateNegotiation($id, $data = array()){
		try{
			if (isset($data['userId']))$datas['user_id'] = $data['userId'];
		$db = DB::table('negotiation')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
	public static function negotiationReplyChat($id, $data = array()){

		$db = DB::table('negotiation_line')->insert([
			'negotiation_id' => $id,
			'chat' => $data['chat'],
			'user_chat_id' => $data['user_chat_id'],
			'createdon' => $data['createdon'],
			'file' => $data['file']
		]);
		return $db;
	}
  
  public static function insertDocumentCopy($arr = array()){

		$db = DB::table('document_copy')->insert([
			'filename' => $arr['filename'],
			'invoice_id' => $arr['invoice_id']
		]);
		return $db;
	}
  
	//Invoice
	public static function paymentInvoiceGroup($limit = 20, $descasc = "desc", $skip = 0, $id = ''){
		if($limit == 'all') $limit = 9999999;
		
    $db = [];
		$sql = DB::table('invoice')
                ->orderBy('invoice.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('invoice_payment', 'invoice_payment.invoice_number', '=', 'invoice.invoice_number')
                ->join('invoice_status', 'invoice_status.id', '=', 'invoice.status')
                ->select('invoice.*', 'invoice_status.description', 'invoice_payment.transfer_date', 'invoice_payment.bank')
                ->where('isdeleted', '0');
    
    if($id !== ''){
      $sql->where('invoice_payment.id', $id);
    }
    
    $sql->groupBy('invoice.id');
    
    $db['total'] = $sql->count();
    $db['data'] = $sql->get();
		return $db;
	}
  
	public static function paymentInvoiceDetail($id = []){
		$db = [];
		$sql = DB::table('invoice')
                ->join('invoice_payment', 'invoice_payment.invoice_number', '=', 'invoice.invoice_number')
                ->join('invoice_status', 'invoice_status.id', '=', 'invoice.status')
                ->select('invoice.*', 'invoice_status.description', 'invoice_payment.transfer_date', 'invoice_payment.bank','invoice_payment.attachment', 'invoice_payment.account_name','invoice_payment.bank_account','invoice_payment.total_payment','invoice_payment.id as payment_id','invoice_payment.status as payment_status')
                ->where('isdeleted', '0')
                ->whereIn('invoice.id', $id);
    
    $db = $sql->get();
		return $db;
	}
  
	public static function saveInvoice($data = array()){
			try{
			$db = DB::table('car_model')->insert(
		    [
		    	'make_id' => $data['make_id'], 
		    	'model' => $data['model']
 			]
		);
		return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
	}
  
  public static function updateInvoicePayment($fields = array()){
		try{
 			if (isset($fields['status']))$field['status'] = $fields['status'];
 			if (isset($fields['payment_id']))$id = $fields['payment_id'];
		$db = DB::table('invoice_payment')
            ->where('id', $id)
            ->update(
            	$field
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
  }
  
	public static function updateInvoice($id, $data = array()){
		try{
			$datas = [];
 			if (isset($data['status']))$datas['status'] = $data['status'];
		$db = DB::table('invoice')
            ->where('id', $id)
            ->update(
            	$datas
            );
           return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
		}
    
    $negotiation = Session::get('negotiation');
    $negotiation = AdminModel::negotiationView($negotiation->id);
    if($negotiation>0){
      Session::set('negotiation',$negotiation[0]);
    }
	}
  
	public static function deleteInvoice($id){
		$db = DB::table('car_model')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function invoiceView($id){
		$db = DB::table('invoice')
		->join('invoice_payment', 'invoice_payment.invoice_number', '=', 'invoice.invoice_number')
        ->join('invoice_status', 'invoice_status.id', '=', 'invoice.status')
        ->select('invoice.*', 'invoice_status.description', 'invoice_payment.transfer_date', 'invoice_payment.bank', 'invoice_payment.bank_account')
        ->where('isdeleted', '0')
        ->where('invoice.id', '=', $id)
        ->first();
		return $db;
	}
	public static function settingIndex($limit = 20, $descasc = "asc", $skip = 0){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('setting')->count();
		$db['data'] = DB::table('setting')
                ->orderBy('setting.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->get();
		return $db;
	}
	public static function settingView($id){
		$db = DB::table('setting')->where('id', $id)->first();
		return $db;
	}
	public static function settingSave($id, $data = array()){
		try {
			$db = DB::table('setting')->where('id', $id)->update($data);	
			return 'ok';
		} catch (Exception $e) {
			return $e;
		}
	}

}

?>
