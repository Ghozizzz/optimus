<?php

namespace Modules\Dealers\Models;

use DB;
use Session;
use Route;

class dealersModel {
		// Login
	public static function checkLogin($email, $password){
		$db = DB::table('seller')->where([
		    ['email', '=', $email],
		    ['password', '=', $password],
		    ['status', '=', 1]
		])->get();
		return $db;
	}
	public static function checkSession($email){
		$db = DB::table('seller')->where([
			['email', '=', $email]
		])->get();
		return $db;
	}
	/*Car*/
	public static function car($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('car')->where('car.isdeleted','=', '0')->count();
		$db['data'] = DB::table('car')
                ->orderBy('car.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('seller', 'seller.id', '=', 'car.seller')
              	->join('car_model', 'car_model.id', '=', 'car.model_id')
              	->join('user', 'user.id', '=', 'car.agent')
              	->select('car.*', 'seller.pic_name', 'car_model.model', 'user.name')
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
  
  public static function getAllNegotiation($car_id){
    $result = DB::table('negotiation as N')->whereIn('car_id','=',$car_id);
    
    return $result;
  }
  
	public static function carView($id){
		$db = DB::table('car')
		->where('id', '=', $id)
		->first();
		return $db;
	}
	//Negotiation	
	public static function negotiation($limit = 20, $descasc = "asc", $skip, $car_id = ''){
    if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$q = DB::table('negotiation as N')
                ->orderBy('N.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('car', 'car.id', '=', 'N.car_id')
                ->join('user', 'user.id', '=', 'N.user_id')
                ->join('negotiation_status as NS','N.status','=','NS.id')
                ->select('N.*', 'car.description', 'car.serial', 'car.distance', 'car.keyword', 'car.price', 'car.picture','car.vin','user.name as name_sales','NS.description as status_description','N.price as negotiation_price')
                ->where('car.seller','=',Session::get('user_id'));
    
    if($car_id !== ''){
      $q->where('N.car_id','=', $car_id);
    }
    
    $db['data'] = $q->get();
    $db['total'] = $q->count();   
    
		return $db;
	}
	
  public static function negotiationGroup($limit = 20, $descasc = "asc", $skip, $filter = ''){
		$db = [];
		$q = DB::table('negotiation as N')
                ->orderBy('N.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('car', 'car.id', '=', 'N.car_id')
                ->join('user', 'user.id', '=', 'N.user_id')
                ->join('negotiation_status as NS','N.status','=','NS.id')
                ->select('N.*', 'car.description', 'car.serial', 'car.distance', 'car.price', 'car.keyword', 'car.picture','car.vin','user.name as name_sales','NS.description as status_description', DB::raw('count(*) as total'), DB::raw('max(N.price) as highest_price'))
                ->where('car.seller','=',Session::get('user_id'));
    
      if($filter !== ''){  
        $q->whereRaw("(car.description like '%". $filter ."%' or car.serial like '%". $filter ."%' or car.vin like '%". $filter ."%' or customer.email like '%". $filter ."%' )");        
      }
      
      $q->groupby('N.car_id');
      
      $db['data'] = $q->get();  
      $db['total'] = count($db['data']);
      
		return $db;
	}
  
  public static function negotiationView($id){
		$db = DB::table('negotiation')
		->join('negotiation_line', 'negotiation_line.negotiation_id', '=', 'negotiation.id')
		->join('customer', 'customer.id', '=', 'negotiation.customer_id')
		->join('user', 'user.id', '=', 'negotiation.user_id')
		->select('negotiation.*' , 'negotiation_line.chat', 'negotiation_line.file', 'negotiation_line.user_chat_id', 'negotiation_line.createdon as chatdate','customer.name as customer_name', 'user.name as sales_name', 'negotiation_line.negotiation_id')
		->where('negotiation_line.negotiation_id', $id)
		->get();
		return $db;
	}
}