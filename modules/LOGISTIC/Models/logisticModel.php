<?php

namespace Modules\LOGISTIC\Models;

use DB;
use Session;
use Route;

class logisticModel {
	// Login
	public static function checkLogin($email, $password){
		$db = DB::table('logistic')->where([
		    ['email', '=', $email],
		    ['password', '=', $password]
		])->get();
		return $db;
	}
	public static function checkSession($email){
		$db = DB::table('logistic')->where([
			['email', '=', $email]
		])->get();
		return $db;
	}
	public static function portCharge($limit = 20, $descasc = "asc", $skip){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('port_price')->count();
		$db['data'] = DB::table('port_price')
                ->orderBy('port_price.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('port_discharge', 'port_discharge.id', '=', 'port_price.discharge_id')
                ->join('port_destination', 'port_destination.id', '=', 'port_price.destination_id')
                ->select('port_price.*', 'port_discharge.country_name as discharge_country', 'port_discharge.port_name as discharge_port_name', 'port_destination.country_name as port_destination_country_name', 'port_destination.port_name as port_destination_port_name')
                ->get();
        $db['port_discharge'] = DB::table('port_discharge')->orderBy('port_name','asc')->get();
        $db['port_destination'] = DB::table('port_destination')->orderBy('port_name','asc')->get();
		return $db;
	}
	public static function portChargeNew($limit = 20, $descasc = "asc", $skip, $filter = null){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];
		$db['total'] = DB::table('port_price')->count();
		$db['data'] = DB::table('port_price')
                ->orderBy('port_price.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('port_discharge', 'port_discharge.id', '=', 'port_price.discharge_id')
                ->join('port_destination', 'port_destination.id', '=', 'port_price.destination_id')
                ->select('port_price.*', 'port_discharge.country_name as discharge_country', 'port_discharge.port_name as discharge_port_name', 'port_destination.country_name as port_destination_country_name', 'port_destination.port_name as port_destination_port_name')
                ->get();
		if ($filter !== null || $filter !== '') {
			$db['data'] = DB::table('port_price')
                ->orderBy('port_price.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->join('port_discharge', 'port_discharge.id', '=', 'port_price.discharge_id')
                ->join('port_destination', 'port_destination.id', '=', 'port_price.destination_id')
                ->select('port_price.*', 'port_discharge.country_name as discharge_country', 'port_discharge.port_name as discharge_port_name', 'port_destination.country_name as port_destination_country_name', 'port_destination.port_name as port_destination_port_name')
                ->where('port_destination.country_name', 'LIKE', '%'.$filter.'%')
        		->orWhere('port_destination.port_name', 'LIKE', '%'.$filter.'%')
        		->get();
        }                
        $db['port_discharge'] = DB::table('port_discharge')->orderBy('port_name','asc')->get();
        $db['port_destination'] = DB::table('port_destination')->orderBy('port_name','asc')->get();
		return $db;
	}
	public static function portChargeSave($data = array()){
			try{
        $params = [
          'discharge_id' => $data['discharge_id'],
		   		'destination_id' => $data['destination_id'],
		   		'price' => isset($data['price']) ? $data['price'] : 0,
          'volume_price' => isset($data['volume_price']) ? $data['volume_price'] : 0,
          'logistic_id' => Session::get('user_id'), 
        ];

			$db = DB::table('port_price')->insert($params);
      return $db;
      
		}Catch(\Illuminate\Database\QueryException $e){
			$errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return 'Duplicate Entry';
            }
            return $e;
		}
	}
	public static function portChargeView($id){
		$db = DB::table('port_price')
		->where('id', '=', $id)
		->first();
		return $db;
	}	
	public static function portChargeUpdate($id, $data = array()){
		try{

			$datas = [];
			if (isset($data['discharge_id']))$datas['discharge_id'] = $data['discharge_id'];
 			if (isset($data['destination_id']))$datas['destination_id'] = $data['destination_id'];
 			if (isset($data['price']))$datas['price'] = $data['price'];
 			if (isset($data['volume_price']))$datas['volume_price'] = $data['volume_price'];
		$db = DB::table('port_price')
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
	public static function deletePortCharge($id){
		$db = DB::table('port_price')->where('id', '=', $id)->delete();
		return $db;
	}

}