<?php

namespace Modules\Admin\Models;

use DB;
use Session;
use Route;
use Carbon\Carbon;

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
	public static function dealer($limit = 5, $descasc = "asc", $skip, $filter = ''){

		$db = [];
		$sql = DB::table('seller');		
    
    if($filter !== ''){
      $sql->whereRaw("(pic_name like '%". $filter ."%' or email like '%". $filter ."%')");
    }
    
    $db['total'] = count($sql->get());
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
    $db['data'] = $sql->orderBy('seller.id', $descasc)->get();
    
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
  
	public static function getAllReview($limit = 5, $descasc = "desc", $skip = 0, $filter = '', $isdisplayed = '', $country = '', $make = '', $model = '' ){
		$db = [];
		
		$sql = DB::table('review as R')
                ->join('invoice as I','R.invoice_id','=','I.id')
                ->join('negotiation as N','N.id','=','I.negotiation_id')
                ->join('car as Ca','Ca.id','=','N.car_id')
                ->leftjoin('car_make as CM','Ca.make_id','=','CM.id')
                ->leftjoin('car_model as CMO','Ca.model_id','=','CMO.id')
                ->join('customer as C','C.id','=','N.customer_id')
                ->leftjoin('country as CO',DB::raw('BINARY CO.country_code'),'=',DB::raw('BINARY C.country_code'))
                ->orderBy('R.id', $descasc)
                ->select('R.*','I.invoice_number','Ca.picture','C.name', 'C.photo as profile_picture','CM.make','CMO.model','CO.country_name','C.country_code');
    if($isdisplayed !== ''){
      $sql->where('isdisplayed', $isdisplayed);
    }
    
    if($filter !== ''){
      $sql->whereRaw("(I.invoice_number like '%". $filter ."%' or C.email like '%". $filter ."%')");      
    }
    
    if($country !== '' && $country !== 'all'){
      $sql->where('C.country_code', $country);
    }
    
    if($make !== '' && $make !== 'all'){
      $sql->where('Ca.make_id', $make);
    }
    
    if($model !== '' && $model !== 'all'){
      $sql->where('Ca.model_id', $model);
    }
    
    $db['total'] = count($sql->get());
 
    if($limit !== 'all'){
      $sql->skip($skip)
      ->take($limit);
    }
    $db['data'] = $sql->get();   
    
    return $db;
	}
  
  public static function updateReviewStatus($invoice_id, $status){
    try{
			$db = DB::table('review')
            ->where('invoice_id', $invoice_id)
            ->update([
                'isdisplayed' => $status
            ]);
      return $db;
		}Catch(\Illuminate\Database\QueryException $e){
			return 'connection failed';
		}
  }
  
  //Banner
	public static function banner($limit = 5, $descasc = "asc", $skip , $filter = ''){
		$db = [];
		$sql = DB::table('banner');
            
    if($filter !== ''){
      $sql->whereRaw("(name like '%". $filter ."%')");
    }
      
    $db['total'] = count($sql->get());
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
		$db['data'] = $sql->orderBy('banner.id', $descasc)
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
  
  public static function country($limit = '5', $descasc = "asc", $skip = '', $filter =''){
		$db = [];
		$db['total'] = DB::table('country')->count();
		$db['data'] = DB::table('country')
                ->orderBy('country_name', $descasc);
    
    if($filter !== ''){
      $db['data']->orwhere('country_name','like','%'.$filter.'%');
      $db['data']->orwhere('country_code','like','%'.$filter.'%');
    }
    
    if($skip !== ''){
      $db['data']->skip($skip);
    }
    
    if($limit !== '' || $limit !== 'all'){
      $db['data']->take($limit);
    }
                
    $db['data'] = $db['data']->get();
		return $db;
	}
  
  public static function countryView($id){
		$db = DB::table('country')->where('id', '=', $id)->first();
		return $db;
	}
  
  public static function updateCountry($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['code']))$datas['country_code'] = $data['code'];
 			if (isset($data['name']))$datas['country_name'] = $data['name'];

		$db = DB::table('country')
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
  
  public static function saveCountry($data = array()){
		try{
			$db = DB::table('country')->insert(
		    [
		    	'country_code' => $data['code'], 
		    	'country_name' => $data['name'],
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
  
  public static function deleteCountry($id){
		$db = DB::table('country')->where('id', '=', $id)->delete();
		return $db;
	}
  
  //Port Destination
	public static function portDestination($limit = '5', $descasc = "asc", $skip, $filter = ''){
		$db = [];
    $sql = DB::table('port_destination');
    
    if($filter !== ''){
      $sql->whereRaw("(port_name like '%". $filter ."%' or port_code like '%". $filter ."%' or country_name like '%". $filter ."%' or id_code like '%". $filter ."%')");
    }
    
		$db['total'] = count($sql->get());
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
		$db['data'] = $sql->orderBy('port_name', $descasc)
                ->get();
		return $db;
	}
  
  public static function savePortDestination($data = array()){
			try{
        $checked = DB::table('port_discharge')->where('id_code',$data['id_code'])->count();
      
        if($checked > 0){
          return 'Duplicate Entry';
        }
      
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
	public static function portDischarge($limit = 20, $descasc = "asc", $skip, $filter = ''){
		$db = [];
    
    $sql = DB::table('port_discharge');
    
    if($filter !== ''){
      $sql->whereRaw("(port_name like '%". $filter ."%' or port_code like '%". $filter ."%' or country_name like '%". $filter ."%' or id_code like '%". $filter ."%')");
    }
    
		$db['total'] = $sql->count();
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
		$db['data'] = $sql
                ->orderBy('port_name', $descasc)
                ->get();
		return $db;
	}
  
  public static function savePortDischarge($data = array()){
			try{
      $checked = DB::table('port_discharge')->where('id_code',$data['id_code'])->count();
      
      if($checked > 0){
        return 'Duplicate Entry';
      }
      
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
	public static function customer($limit = 5, $descasc = "asc", $skip, $filter = ''){
		$db = [];
    
    $sql = DB::table('customer')->where('isdeleted', '0');
    
    if($filter !== ''){
      $sql->whereRaw("(email like '%". $filter ."%' or name like '%". $filter ."%' )");
    }
    
		$db['total'] = count($sql->get());
    
    if($limit !== 'all'){
      $sql = $sql->skip($skip)->take($limit);
    }
    
		$db['data'] = $sql
                ->orderBy('customer.id', $descasc)                                
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
		$db = [];
    $db['data'] = DB::table('customer')
		->where('id', '=', $id)
		->first();
    
    $db['negotiation_history'] = DB::table('negotiation as N')
                    ->join('car as C','C.id','=', 'N.car_id')
                    ->join('car_model as M','M.id','=', 'C.model_id')
                    ->join('negotiation_status as NS','NS.id','=', 'N.status')
                    ->where('customer_id', '=', $id)
                    ->whereIn('N.status', ['1','2'])
                    ->limit(10)
                    ->orderBy('id','desc')
                    ->select('N.*','C.id as car_id','C.picture','M.model','NS.description as status_description')
                    ->get(); 
    $db['invoice_history'] = DB::table('negotiation as N')
                    ->join('car as C','C.id','=', 'N.car_id')
                    ->join('car_model as M','M.id','=', 'C.model_id')
                    ->join('negotiation_status as NS','NS.id','=', 'N.status')
                    ->where('customer_id', '=', $id)
                    ->where('N.status','>=','3')  
                    ->limit(10)
                    ->orderBy('id','desc')
                    ->select('N.*','C.id as car_id','C.picture','M.model','NS.description as status_description')
                    ->get(); 
    
    $db['total_history'] = DB::table('negotiation as N')
                    ->join('negotiation_status as NS','NS.id','=', 'N.status')
                    ->where('customer_id', '=', $id)                    
                    ->groupBy('N.status')
                    ->select('NS.description',DB::Raw('count(*) as total'))
                    ->get(); 
    
		return $db;
	}

	// Sales

	public static function sales($limit = 5, $descasc = "desc", $skip, $filter = ''){
			$db = [];
      $db['privilege'] = DB::table('privilege')->where('name', 'sales')->first();
			$sql = DB::table('user')->where('isdeleted', '0')->where('type', $db['privilege']->id);
      
      if($filter !== ''){
        $sql->whereRaw("(user.name like '%". $filter ."%' or user.phone like '%". $filter ."%' or user.email like '%". $filter ."%')");
      }
      
			$db['total'] = $sql->count();
			
      if($limit !== 'all'){
        $sql->skip($skip)->take($limit);
      }
      
      $db['data'] = $sql
	                ->orderBy('user.id', $descasc)
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
	public static function logistic($limit = 20, $descasc = "asc", $skip = '', $filter = ''){
		$db = [];
		$sql = DB::table('logistic');
    
    if($filter !== ''){
      $sql->whereRaw("(email like '%". $filter ."%' or company_name like '%". $filter ."%')");
    }
      
		$db['total'] = $sql->count();
		$q = $sql->orderBy('logistic.id', $descasc);
    if($limit !== 'all'){
      $q->take($limit);
      
      if($skip!== ''){
        $q->skip($skip);
      }  
    }   
                
    $db['data'] = $q->get();            
 
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
  
  public static function portPrice($discharge_id ='', $destination_id = '', $logistic_id = ''){
		$result = DB::table('port_price');
    
    if($discharge_id !== ''){
      $result->where('discharge_id',$discharge_id);
    }
    
    if($destination_id !== ''){
      $result->where('destination_id',$destination_id);
    }
    
    if($logistic_id !== ''){
      $result->where('logistic_id',$logistic_id);
    }
	                
			return $result->get();
	}
  
	//Finance

	public static function user($limit = 20, $descasc = "asc", $skip = '' , $filter = ''){
			$db = [];
			$db['privilege'] = DB::table('privilege')->get();
			$sql = DB::table('user')->where('isdeleted', '0');
      
      if($filter !== ''){
        $sql->whereRaw("(user.name like '%". $filter ."%' or user.phone like '%". $filter ."%' or user.email like '%". $filter ."%')");
      }
      
      $db['total'] = $sql->count();
			$db['data'] = $sql->orderBy('user.id', $descasc)
	                ->skip($skip)
	                ->take($limit)
	                ->get();
      
			return $db;
	}
	public static function saveUser($data = array()){
			try{
        Session::set('dddd',$data);
        
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
	public static function car($limit = 20, $descasc = "asc", $skip, $filter = '', $seller_id = '', $status = '', $chasis = ''){
		if($limit == 'all') $limit = 9999999;
		if($limit == 1) $limit = 5;
		$db = [];


		$sql = DB::table('car')
                ->orderBy('car.id', $descasc)                
              	->Join('car_make','car_make.id','=','car.make_id')
                ->Join('car_model','car_model.id','=','car.model_id')
              	->leftJoin('user', 'user.id', '=', 'car.agent')
              	->leftJoin('seller', 'seller.id', '=', 'car.seller'           )
              	->select('car.*', 'seller.pic_name','car_model.model', 'user.name', 'car_make.make',
                  DB::raw('(select proforma_invoice_number from negotiation as N inner join proforma_invoice PI on PI.negotiation_id = N.id  where N.car_id = car.id limit 1 ) as proforma_invoice'))
                ->where('car.isdeleted','=', '0');
    
         if($filter !== ''){
           $sql->whereRaw("(car.vin like '%". $filter ."%' or car_model.model like '%". $filter ."%' or car.serial like '%". $filter ."%' or car.description like '%". $filter ."%' or car.keyword like '%". $filter ."%' or car.plate_number like '%". $filter ."%')");
         }
         if($seller_id !== ''){
           $sql->where("car.seller", "=", $seller_id);
         }

          if($status !== ''){
            if($status == '1'){
              $sql->whereIn("car.status", array('1','3'));
            }else{
              $sql->where("car.status", "=", $status);
            }            
          }
         
          if($chasis !== ''){
            $sql->where("car.vin", "=", $chasis);
          }

         $db['total'] = $sql->count();
         $db['data'] = $sql->skip($skip)
                            ->take($limit)->get();
         

         $db['carmake'] = DB::table('car_make')->where('isdeleted','0')->orderBy('make','asc')->get();
         $db['carmodel'] = DB::table('car_model')->where('isdeleted','0')->orderBy('model')->get();
         $db['carengine'] = DB::table('car_engine')->where('isdeleted','0')->get();
         $db['caroption'] = DB::table('car_option')->where('isdeleted','0')->orderBy('description','asc')->get();
         $db['carprice'] = DB::table('car_price')->get();
         $db['car_color'] = DB::table('car_color')->where('isdeleted','0')->orderBy('description','asc')->get();
         $db['car_type'] = DB::table('car_body_type')->where('isdeleted','0')->orderBy('description','asc')->get();
         $db['car_transmission'] = DB::table('car_transmission')->get();
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
				'registration_date' => $data['registration_date'],
				'plate_number' => $data['plate_number'],
				'motor_number' => $data['motor_number'],
				'distance' => $data['distance'],
				'type' => $data['type'],
				'colour' => $data['colour'],
				'engine' => $data['engine'],
				'price' => $data['price'],
				'currency' => 'USD',
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
        'selling_point' => $data['selling_point'],  
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
			if (isset($data['registration_date']))$datas['registration_date'] = $data['registration_date'];
			if (isset($data['plate_number']))$datas['plate_number'] = $data['plate_number'];
			if (isset($data['motor_number']))$datas['motor_number'] = $data['motor_number'];
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
			if (isset($data['selling_point']))$datas['selling_point'] = $data['selling_point'];
			
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
  
  public static function unavailableCar($id){
		$db = DB::table('car')
            ->where('id', $id)
            ->update(
            	['status' => '4']
            );
	}

	public static function AvailableCar($id){
		$db = DB::table('car')
            ->where('id', $id)
            ->update(
            	['status' => '1']
            );
	}
  
	public static function carView($id){
		$db = DB::table('car')
		->where('id', '=', $id)
		->first();
		return $db;
	}	

  //car color
	public static function carColor($limit = 20, $descasc = "asc", $skip = 0 , $filter = ''){
		$db = [];
		
    $sql = DB::table('car_color as CC')
                ->where('CC.isdeleted', '0');
  
    if($filter !== ''){
      $sql->whereRaw("(CC.description like '%". $filter ."%')");
    }

    $db['total'] = count($sql->get());
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
    $db['data'] = $sql->orderBy('CC.id', $descasc)
                ->select('CC.*')
                ->get();
                
    return $db;
	}
	public static function saveCarColor($data = array()){
			try{
			$db = DB::table('car_color')->insert(
		    [
		    	'description' => $data['color'],
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
	public static function updateCarColor($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['color']))$datas['description'] = $data['color']; 			
		$db = DB::table('car_color')
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
	public static function deleteCarColor($id){
		$db = DB::table('car_color')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function carColorView($id){
		$db = DB::table('car_color')
		->where('id', '=', $id)
		->first();
		return $db;
	}
  

  //car Type
	public static function carType($limit = 20, $descasc = "asc", $skip = 0 , $filter = ''){
		$db = [];
		
    $sql = DB::table('car_body_type as CBT')
                ->where('CBT.isdeleted', '0');
  
    if($filter !== ''){
      $sql->whereRaw("(CBT.description like '%". $filter ."%')");
    }

    $db['total'] = $sql->count();
    
    $db['data'] = $sql->orderBy('CBT.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->select('CBT.*')
                ->get();
                
    return $db;
	}
	public static function saveCarType($data = array()){
			try{
			$db = DB::table('car_body_type')->insert(
		    [
		    	'description' => $data['description'],
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
	public static function updateCarType($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['description']))$datas['description'] = $data['description']; 			
		$db = DB::table('car_body_type')
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
	public static function deleteCarType($id){
		$db = DB::table('car_body_type')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function carTypeView($id){
		$db = DB::table('car_body_type')
		->where('id', '=', $id)
		->first();
		return $db;
	}
  
  //accessories
	public static function accessories($limit = 20, $descasc = "asc", $skip = 0 , $filter = ''){
		$db = [];
		
    $sql = DB::table('car_option as CO')
                ->where('CO.isdeleted', '0');
  
    if($filter !== ''){
      $sql->whereRaw("(CO.description like '%". $filter ."%')");
    }

    $db['total'] = $sql->count();
    
    $db['data'] = $sql->orderBy('CO.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->select('CO.*')
                ->get();
                
    return $db;
	}
	public static function saveAccessories($data = array()){
			try{
			$db = DB::table('car_option')->insert(
		    [
		    	'description' => $data['description'],
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
	public static function updateAccessories($id, $data = array()){
		try{
			$datas = [];
			if (isset($data['description']))$datas['description'] = $data['description']; 			
		$db = DB::table('car_option')
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
	public static function deleteAccessories($id){
		$db = DB::table('car_option')
            ->where('id', $id)
            ->update(
            	['isdeleted' => '1']
            );
	}
	public static function accessoriesView($id){
		$db = DB::table('car_option')
		->where('id', '=', $id)
		->first();
		return $db;
	}
  
// Car Model
	public static function carModel($limit = 20, $descasc = "asc", $skip = 0 , $filter = ''){
		$db = [];
		
    $sql = DB::table('car_model as CM')
                ->join('car_make as MA', 'CM.make_id', '=','MA.id')
                ->where('CM.isdeleted', '0');
  
    if($filter !== ''){
      $sql->whereRaw("(CM.model like '%". $filter ."%' or MA.make like '%". $filter ."%')");
    }

    $db['total'] = $sql->count();
    
    $db['data'] = $sql->orderBy('CM.id', $descasc)
                ->skip($skip)
                ->take($limit)
                ->select('CM.*', 'MA.id as make_id', 'MA.make' )
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
public static function carMake($limit = 20, $descasc = "asc", $skip = '', $filter = ''){
		$db = [];
    $sql = DB::table('car_make')->where('isdeleted', '0');
    
    if($filter !== ''){
      $sql->whereRaw("(make like '%". $filter ."%' )");
    }
    
		$db['total'] = $sql->count();
		$db['data'] = $sql
                ->skip($skip)
                ->take($limit)
                ->orderBy('make','ASC')
                ->get();
		return $db;
	}
	public static function saveCarMake($data = array()){
			try{
			$db = DB::table('car_make')->insert(
		    [
		    	'make' => $data['make'], 
		    	'logo' => $data['logo']
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

	public static function carEngine($limit = 5, $descasc = "asc", $skip, $filter = ''){
		$db = [];
		$sql = DB::table('car_engine')->where('isdeleted', '0');
    
    if($filter !== ''){
      $sql->whereRaw("(description like '%". $filter ."%' )");
    }
    
		$db['total'] = $sql->count();
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
		$db['data'] = $sql->orderBy('car_engine.id', $descasc)
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
	public static function negotiation($limit = 5, $descasc = "asc", $skip, $status = '', $filter = ''){
    
		$typeLogin = adminModel::checkSession(Session::get('email'))->p;
		$db = [];
		
		$db['privilege'] = DB::table('privilege')->where('name', 'sales')->first();
		$db['sales'] = DB::table('user')->where('type', $db['privilege']->id)->get();
		$db['data'] = [];    
		if($typeLogin === 'sales'){
			$sql = DB::table('negotiation')
                ->orderBy('negotiation.id', $descasc)                
                ->leftjoin('car', 'car.id', '=', 'negotiation.car_id')
                ->leftjoin('user', 'user.id', '=', 'negotiation.user_id')
                ->leftjoin('logistic as L', 'L.id', '=', 'negotiation.logistic_id')
                ->leftjoin('invoice as I', 'I.negotiation_id', '=', 'negotiation.id')
                ->select('negotiation.*', 'car.description', 'car.serial', 'car.distance', 'car.price as car_price', 'car.keyword', 'car.vin', 'car.picture','user.name as name_sales','customer.address as customer_address','customer.email as customer_email', 'customer.name as customer_name', 'NS.description as status_description','L.id as logistic_id','L.company_name as logistic_name','I.due_date', 'I.id as invoice_id')
                ->where('negotiation.user_id', adminModel::checkSession(Session::get('email'))->id);
    
    if($status !== ''){
      $sql->whereIn('negotiation.status',$status);
    }          
    
    if($filter !== ''){
      $sql->whereRaw("(car.description like '%". $filter ."%' or car.serial like '%". $filter ."%' or car.vin like '%". $filter ."%' or customer.email like '%". $filter ."%' or i.invoice_number like '%". $filter ."%' )");
    }
    
      if(isset($_GET['debug'])){
        echo $sql->toSql();
      }
      $db['total'] = $sql->count();
      
      if($limit !== 'all'){
        $sql->skip($skip)->take($limit);
      }
      
      $db['data'] = $sql->get();
		}
		if(in_array($typeLogin, ['admin','super admin','super sales'])){
		$sql = DB::table('negotiation')
                ->orderBy('negotiation.id', $descasc)               
                ->leftjoin('car', 'car.id', '=', 'negotiation.car_id')
                ->leftjoin('user', 'user.id', '=', 'negotiation.user_id')
                ->leftjoin('customer', 'customer.id', '=', 'negotiation.customer_id')
                ->leftJoin('negotiation_status as NS','NS.id','=','negotiation.status') 
                ->leftjoin('logistic as L', 'L.id', '=', 'negotiation.logistic_id')
                ->leftjoin('invoice as I', 'I.negotiation_id', '=', 'negotiation.id')
                ->select('negotiation.*', 'car.description', 'car.serial', 'car.distance', 'car.price as car_price', 'car.keyword', 'car.vin', 'car.picture','user.name as name_sales', 'customer.address as customer_address','customer.email as customer_email', 'customer.name as customer_name','NS.description as status_description','L.id as logistic_id','L.company_name as logistic_name','I.due_date', 'I.id as invoice_id');
    
    if($status !== ''){
      $sql->whereIn('negotiation.status',$status);
    }
    
    if($filter !== ''){
      $sql->whereRaw("(car.description like '%". $filter ."%' or car.serial like '%". $filter ."%' or car.vin like '%". $filter ."%' or customer.email like '%". $filter ."%' or i.invoice_number like '%". $filter ."%' )");
    } 
    
      if(isset($_GET['debug'])){
        echo $sql->toSql();
      }
      
       $db['total'] = count($sql->get());     
      
      if($limit !== 'all'){
        $sql->skip($skip)->take($limit);
      }
      
      $db['data'] = $sql->get();
		}
    
    $unread_negotiation = [];
    
    $q = DB::table('negotiation_line as NL')
          ->join('negotiation as N','N.id','=','NL.negotiation_id')
          ->where('NL.isread', '0')
          ->where('NL.user_chat_id', null)
          ->groupBy('N.id')  
          ->select('N.id')->get();
    foreach($q as $detail){
      array_push($unread_negotiation, $detail->id);
    }
    $db['unread_negotiation']= $unread_negotiation;
		return $db;
	}
  
  public static function updateAllNotification($negotiation_id){
    try{
      $result = DB::table('negotiation_line as NL')
        ->join('negotiation as N', 'N.id','=','NL.negotiation_id')      
        ->where('NL.negotiation_id', '=', $negotiation_id)
        ->where('NL.user_chat_id', null)
        ->update(['NL.isread'=>1]);
      return $result;
    } catch (Exception $ex) {
      Throw new \Exception('update notification failed'.print_r($ex));
    }      
  }
    
  public static function getUnreadMessage($status = ''){
    try{
      if(Session::get('email') !== null){
        $q = DB::table('negotiation_line as NL')
            ->join('negotiation as N','N.id','=','NL.negotiation_id')
            ->where('NL.isread', '0')
            ->where('NL.user_chat_id', null);
      
        if($status !== ''){
          $q->whereIn('N.status',$status);
        }
      
        $typeLogin = adminModel::checkSession(Session::get('email'))->p;
        
        if($typeLogin == 'sales'){
          $q = $q->where('N.user_id','=',Session::get('user_id'));
        }

        $q->groupBy('N.id');
        
        $db['total']  = count($q->get());
        return $db['total'];
      }      
    } catch (Exception $ex) {

    }
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
			if (isset($data['logistic_id']))$datas['logistic_id'] = $data['logistic_id'];
			if (isset($data['resi_number']))$datas['resi_number'] = $data['resi_number'];
			if (isset($data['status']))$datas['status'] = $data['status'];
			if (isset($data['vessel_name']))$datas['vessel_name'] = $data['vessel_name'];
			if (isset($data['shipment_date']))$datas['shipment_date'] = $data['shipment_date'];
			if (isset($data['shipment_file']))$datas['shipment_file'] = $data['shipment_file'];
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
			'file' => $data['file']
		]);
		return $db;
	}
  
  public static function insertDocumentCopy($arr = array()){

		$db = DB::table('document_copy')->insert([
			'filename' => $arr['filename'],
			'invoice_id' => $arr['invoice_id'],
			'category' => $arr['category'],
		]);
		return $db;
	}

  public static function getDocumentOriginal($negotiation_id){
    $db = DB::table('document_original')->where('negotiation_id',$negotiation_id)->get();
    return $db;
  }
  
  public static function insertDocumentOriginal($arr = array()){


    $db = DB::table('document_original')->where('negotiation_id',$arr['negotiation_id'])->get();
    if(count($db)>0){
      
      $field = [
        'is_bl' => $arr['is_bl'],  
        'is_invoice' => $arr['is_invoice'],  
        'is_registration_certificate' => $arr['is_registration_certificate'],  
        'is_inspection' => $arr['is_inspection'],  
        'is_insurance' => $arr['is_insurance'],  
        'shipping_logistic' => $arr['shipping_logistic'],  
        'tracking_number' => $arr['tracking_number'],  
        'comment' => $arr['comment'],  
      ];
      
      if (isset($arr['file']) && $arr['file'] !== '')$field['file'] = $arr['file'];
      
      $db = DB::table('document_original')
            ->where('negotiation_id', $arr['negotiation_id'])
            ->update(
            	$field
            );
    }else{
      $db = DB::table('document_original')->insert([
        'file' => $arr['file'],
        'negotiation_id' => $arr['negotiation_id'],
        'is_bl' => $arr['is_bl'],
        'is_invoice' => $arr['is_invoice'],
        'is_registration_certificate' => $arr['is_registration_certificate'],
        'is_inspection' => $arr['is_inspection'],
        'is_insurance' => $arr['is_insurance'],
        'shipping_logistic' => $arr['shipping_logistic'],
        'tracking_number' => $arr['tracking_number'],
        'comment' => $arr['comment'],
      ]);     
    }
		return $db;
	}
  
  public static function array_index($arr, $indexes, $objResult = false){
    if(!is_array($arr)) return null;
    $result = array();

    for($i = 0 ; $i < count($arr) ; $i++){
      $obj = $arr[$i];

      switch(count($indexes)){
        case 1 :
          $idx0 = $indexes[0];
          if(!isset($obj[$idx0])) continue 2;
          if(!isset($result[$obj[$idx0]])) $result[$obj[$idx0]] = array();
          $result[$obj[$idx0]][] = $obj;
          break;
        case 2 :
          $idx0 = $indexes[0];
          $idx1 = $indexes[1];
          if(!isset($obj[$idx0]) || !isset($obj[$idx1])) continue 2;
          $key0 = $obj[$idx0];
          $key1 = $obj[$idx1];
          if(!isset($result[$key0])) $result[$key0] = array();
          if(!isset($result[$key0][$key1])) $result[$key0][$key1] = array();
          $result[$key0][$key1][] = $obj;
          break;
        case 3 :
          $idx0 = $indexes[0];
          $idx1 = $indexes[1];
          $idx2 = $indexes[2];
          if(!isset($obj[$idx0]) || !isset($obj[$idx1]) || !isset($obj[$idx2])) continue 2;
          $key0 = $obj[$idx0];
          $key1 = $obj[$idx1];
          $key2 = $obj[$idx2];
          if(!isset($result[$key0])) $result[$key0] = array();
          if(!isset($result[$key0][$key1])) $result[$key0][$key1] = array();
          $result[$key0][$key1][$key2] = $obj;
          break;
        default:
          throw new Exception("Unsupported index level.");
      }
    }
  }
  
  public static function getPaymentNotification(){
    //count invoice with status == 1 without payment
    $invoice_no_payment = DB::table('invoice as I')
            ->leftjoin('invoice_payment as IP','IP.invoice_number','=','I.invoice_number')
            ->where('IP.id','=',null)
            ->where('I.status','!=','4')
            ->select('I.invoice_number')
            ->get();
    
   
    $payment_not_confirm = DB::table('invoice_payment as IP')
            ->where('IP.status','=','0')
            ->groupby('IP.invoice_number')
            ->select('IP.invoice_number')
            ->get();

    
    $result['total'] = count($invoice_no_payment) + count($payment_not_confirm);
    $data = array_merge($payment_not_confirm, $invoice_no_payment);
    
    $result['data'] = [];
    if(count($data)>0){
      foreach($data as $data_detail){
        $result['data'][] = $data_detail->invoice_number;
      }
    }

    return $result;
  }
  
	//Invoice
	public static function paymentInvoiceGroup($limit = 20, $descasc = "desc", $skip = 0, $id = '', $status = '',$filter = ''){
		
    $db = [];
		$sql = DB::table('invoice')
                ->orderBy('invoice.id', $descasc)
                ->leftjoin('invoice_payment', 'invoice_payment.invoice_number', '=', 'invoice.invoice_number')
                ->leftjoin('invoice_status', 'invoice_status.id', '=', 'invoice.status')
                ->select('invoice.*', 'invoice_status.description', 'invoice_payment.transfer_date', 'invoice_payment.bank',DB::raw('(select SUM(IP2.total_payment) from invoice_payment IP2 '
. ' where IP2.invoice_number = invoice.invoice_number and IP2.status= 1 '
. ' group by IP2.invoice_number) as total_payment'))
                ->where('isdeleted', '0')
                ->orWhere('invoice_payment.id', '=', null);
    
    if($id !== ''){
      $sql->where('invoice_payment.id', $id);
    }
    
    if($status !== ''){
      $sql->where('invoice.status', $status);
    }
    
    if($filter !== ''){
      $sql->whereRaw("(invoice.invoice_number like '%". $filter ."%'   )");
    }
    
    $sql->groupBy('invoice.id');
    $db['total'] = count($sql->get());
    
    if($limit !== 'all'){
      $sql->take($limit)->skip($skip);
    }
                
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
        ->leftjoin('invoice_payment', 'invoice_payment.invoice_number', '=', 'invoice.invoice_number')
        ->leftjoin('invoice_status', 'invoice_status.id', '=', 'invoice.status')
        ->select('invoice.*', 'invoice_status.description', 'invoice_payment.transfer_date', 'invoice_payment.bank', 'invoice_payment.bank_account')
        ->where('isdeleted', '0')
        ->where('invoice.id', '=', $id)
        ->first();
		return $db;
	}
	public static function settingIndex($limit = 5, $descasc = "asc", $skip = 0, $filter = ''){
		$db = [];
		$db['total'] = DB::table('setting')->count();
		$sql = DB::table('setting')
                ->orderBy('setting.id', $descasc);
    if($filter !== ''){
      $sql->where('field','like','%'.$filter.'%');
    }
    
    if($limit !== 'all'){
      $sql->skip($skip)->take($limit);
    }
    
    $db['data'] = $sql->get();
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
