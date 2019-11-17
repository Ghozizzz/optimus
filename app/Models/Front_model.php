<?php

namespace App\Models;

use DB;
use Session;
use Route;
use Carbon\Carbon;

class Front_model {

    public static function getAllMake($id = '', $total = false, $isdeleted = 0) {
        $sql = DB::table('car_make AS CM');
        
        
        if($id !== ''){
            $sql->where('CM.id', '=', $id);
        }
        
        $sql->where('CM.isdeleted', '=', $isdeleted);
        $sql->orderBy('make','asc'); 
       if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getAllBodyType($id = '', $total = false, $isdeleted = 0) {
        $sql = DB::table('car_body_type AS CBT');
        
        
        if($id !== ''){
            $sql->where('CBT.id', '=', $id);
        }
        
        $sql->where('CBT.isdeleted', '=', $isdeleted);
        $sql->orderBy('description','asc');
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getAllCarEngine($id = '', $total = false, $isdeleted = 0) {
        $sql = DB::table('car_engine AS CE');        
        
        if($id !== ''){
            $sql->where('CE.id', '=', $id);
        }
        
        $sql->where('CE.isdeleted', '=', $isdeleted);
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }        
    }
    
    public static function getAllChat($id = '', $make_id = '', $total = false) {
        $sql = DB::table('car_model AS CM');
        
        
        if($id !== ''){
            $sql->where('CM.id', '=', $id);
        }
        
        if($make_id !== ''){
            $sql->where('CM.make_id', '=', $make_id);
        }
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    
    public static function getAllCarModel($id = '', $make_id = '', $total = false) {
        $sql = DB::table('car_model AS CM');
        
        
        if($id !== ''){
            $sql->where('CM.id', '=', $id);
        }
        
        if($make_id !== ''){
            $sql->where('CM.make_id', '=', $make_id);
        }
        $sql->orderBy('model','asc');
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getAllAccessories(){
      $sql = DB::table('car_option');
      
      return $sql->get();
    }
    
    /**
     * 
     * @param type $id
     * @param type $criteria
     * @param type $total
     * @param type $limit
     * @param type $offset
     * @param type $status ()
     * @param type $make
     * @param type $body_type
     * @param type $price_range
     * @param type $keyword
     * @param type $model
     * @param type $car_engine
     * @param type $car_year
     * @param type $car_condition (field state : new : 1, used : 2, broken : 3)
     * @param type $promotion
     * @return type
     */
    
    public static function getAllCar($id = '', $criteria = '', $total = false, $limit = '', $offset = '', $status = '', $make ='', $body_type = '', $price_range = '', $keyword = '', $model = '', $car_engine = '', $car_year = '', $car_condition = '', $promotion = '') {
        $sql = DB::table('car AS C')
                ->Join('car_make as CM','CM.id','=','C.make_id')
                ->Join('car_model as CMO','CMO.id','=','C.model_id')
                ->Join('car_status as S','S.id','=','C.status')
                ->leftJoin('car_body_type as CT','CT.id','=','C.type')
                ->leftJoin('car_body_type as CT2','CT.id','=','C.type2')
                ->leftJoin('car_transmission as CT3','CT3.id','=','C.transmission')
                ->select('C.*','CM.make','CM.logo','CMO.model','CT.description as type_description','CT2.description as type_description2', 'CT3.id as car_transmission_id', 'CT3.description as car_transmission_description');
        
        if($id !== ''){
            $sql->whereIn('C.id', $id);
        }
        
        if($make !== ''){
            $sql->where('CM.make', '=', str_slug($make,' '));
        }
        
        if($body_type !== ''){
            $sql->where('CT.description', '=', str_replace('-',' ',$body_type));
        }
        
        if($model !== ''){
            $sql->where('CMO.id', '=', $model);
        }
        
        if($car_engine !== ''){
            $sql->where('C.engine', '=', $car_engine);
        }
        
        if($car_year !== ''){
          if(is_array($car_year)){
            if(isset($car_year[0]) && $car_year[0] !== ''){
              $sql->whereRaw('year(C.registration_date) >= "'.$car_year[0].'"');
            }
            if(isset($car_year[1]) && $car_year[1] !== ''){
              $sql->whereRaw('year(C.registration_date) <= "'.$car_year[1].'"');
            }            
          }else{
            $sql->whereRaw('year(C.registration_date)  = "'.$car_year.'"');
          }            
        }
        
        if($promotion !== ''){
            $sql->where('C.promotion', '=', $promotion);
        }
        
        if($status !== ''){ 
          if(is_array($status)){
            $sql->whereIn('C.status', $status);
          }else{
              $sql->where('C.status', '=', $status);
          }
        }
        
        if($car_condition !== ''){
            $sql->where('C.state', '=', $car_condition);
        }
        
        if($criteria === 'hot-car'){
            $sql->where('C.hot_car', '=', 1);
        }elseif($criteria === 'best-seller'){
            $sql->where('C.best_deal', '=', 1);
        }elseif($criteria === 'recommended'){
            $sql->where('C.recommendation', '=', 1);
        }elseif($criteria === 'clearance-sale'){
            $sql->where('C.promotion', '=', 1);
        }
        
        if($keyword !== ''){
          $sql->where('C.description', 'like', '%'.$keyword.'%');
        }
        
        if($price_range !== ''){
          $price_range_arr = explode('-',$price_range);
          $sql->where('C.price', '>=', $price_range_arr[0]);
          if(isset($price_range_arr[1])){
            $sql->where('C.price', '<=', $price_range_arr[1]);
          }          
        }
        
        if($limit !== '' && $offset !==''){
          $sql->limit($limit);
          $sql->offset($offset);
        }
        $sql->where('C.isdeleted', '=', 0);
        $sql->orderBy('C.id','desc');  
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getMaxMinCarPrice() {
        $sql = DB::table('car AS C')
                ->Join('car_make as CM','CM.id','=','C.make_id')
                ->Join('car_model as CMO','CMO.id','=','C.model_id')
                ->Join('car_status as S','S.id','=','C.status')
                ->leftJoin('car_body_type as CT','CT.id','=','C.type')
                ->leftJoin('car_body_type as CT2','CT.id','=','C.type2')
                ->select(DB::raw('max(C.price) as max_price'),DB::raw('min(C.price) as min_price'));
        
        $sql->where('C.isdeleted', '=', 0);
        $sql->orderBy('C.id','desc');
        
        return $sql->get();
        
    }
    
    public static function getAllCarImage($id = '',$car_id = '', $total = false, $limit = '') {
        $sql = DB::table('car_image AS CI');
                       
        if($id !== ''){
            $sql->where('CI.id', '=', $id);
        }
        
        if($car_id !== ''){
            $sql->where('CI.car_id', '=', $car_id);
        }
        
        $sql->orderby('isprimary','DESC');
        
        if($limit !== ''){
            $sql->limit($limit,0);
        }
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getSetting($field = '') {
        $sql = DB::table('setting AS S');
                       
        if($field !== '' && is_array($field)){
          $sql->whereIn('S.field', $field);
        }elseif($field !== ''){
          $sql->where('S.field', '=', $field);
        }
        
        return $sql->get();
        
    }
    
    public static function getRating($id = '', $email = '', $total = false) {
        $sql = DB::table('review AS R');
                       
        if($id !== ''){
            $sql->where('R.id', '=', $id);
        }
        
        if($email !== ''){
            $sql->where('R.email', '=', $email);
        }
        
        $sql->where('R.isdisplayed', '=', 1);        
        $sql->select(DB::raw('avg(speed) as speed_rating'),DB::raw('avg(satisfication) as satisfication_rating'),DB::raw('avg(accuration) as accuration_rating'));        
        $sql->orderby('id','DESC');
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getAllReview($id = '', $email = '', $total = false, $limit = '') {
        $sql = DB::table('review AS R');
                       
        if($id !== ''){
            $sql->where('R.id', '=', $id);
        }
        
        if($email !== ''){
            $sql->where('R.email', '=', $email);
        }
        
                        
        $sql->orderby('id','DESC');
        
        if($limit !== ''){
          $sql->limit($limit);
        }
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    
    public static function getDestinationPort($id = '', $country_code = '', $total = false, $city = '') {
        $sql = DB::table('port_destination as PD')
                ->select('id','country_name','country_code','port_name', 
                        DB::raw('(select price  from port_price as PP where PP.destination_id = PD.id and PP.discharge_id = 5763 and price > 0 order by PP.price ASC limit 1) as price'),
                        DB::raw('(select volume_price  from port_price as PP where PP.destination_id = PD.id and PP.discharge_id = 5763 and volume_price > 0 order by PP.price ASC limit 1) as volume_price'),  
                        DB::raw('(select logistic_id  from port_price as PP where PP.destination_id = PD.id and PP.discharge_id = 5763 order by PP.price ASC limit 1) as logistic_id')
                        );
              
        
        if($id !== ''){
            $sql->where('id', '=', $id);
        }
        
        if($country_code !== ''){
            $sql->where('PD.country_code', '=', $country_code);
        }
        
//        if($city !== ''){
//          $sql->where('PD.city', 'like', '%'.$city.'%');
//          $sql->orWhereNull('PD.city');
//        }


        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getAllDestinationCountry($id = '', $total = false) {
        $sql = DB::table('port_destination as PD')
                ->groupby('country_name','country_code')
              ->select('country_name','country_code');
              
        
        if($id !== ''){
            $sql->where('id', '=', $id);
        }

        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getDeparturePort($id = '', $country_code = '', $total = false) {
        $sql = DB::table('port_discharge as PD')
                ->select('id','country_name','country_code','port_name', DB::raw('(select price  from port_price as PP where PP.destination_id = PD.id and PP.discharge_id = 5763 limit 1) as price'));
              
        
        if($id !== ''){
            $sql->where('id', '=', $id);
        }
        
        if($country_code !== ''){
            $sql->where('PD.country_code', '=', $country_code);
        }

        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getAllDepartureCountry($id = '', $total = false) {
        $sql = DB::table('port_discharge as PD')
                ->groupby('country_name','country_code')
              ->select('country_name','country_code');
              
        
        if($id !== ''){
            $sql->where('id', '=', $id);
        }

        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function updateCustomerPhoto($customer_id, $photo){
      $sql = DB::table('customer AS C')->where('id', '=', $customer_id)
                          ->update(['photo'=>$photo]);
      return $sql;
    }
    
    public static function getAllCustomer($id = '', $email = '', $password= '', $total = false, $status = array()) {
        $sql = DB::table('customer AS C');
        
        if($id !== ''){
            $sql->where('C.id', '=', $id);
        }
        
        if($email !== ''){
            $sql->where('C.email', '=', $email);
        }
        
        if($password !== ''){
            $sql->where('C.password', '=', $password);
        }
        
        $sql->where('C.isdeleted', '=', 0);
        
        if(count($status) == 0){
          $sql->whereIn('C.status', [1,2]);
        }else{
          $sql->whereIn('C.status', $status);
        }
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
    }
    
    public static function getAllSeller($id = '', $email = '', $password= '', $total = false, $status = array()) {
        $sql = DB::table('seller AS S');
        
        if($id !== ''){
            $sql->where('S.id', '=', $id);
        }
        
        if($email !== ''){
            $sql->where('S.email', '=', $email);
        }
        
        if($password !== ''){
            $sql->where('S.password', '=', $password);
        }
        
        $sql->where('S.isdeleted', '=', 0);
        
        if(count($status) == 0){
          $sql->whereIn('S.status', [1,2]);
        }else{
          $sql->whereIn('S.status', $status);
        }        
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
    }
    
     public static function getAllWishlist($id = '', $customer_id = '', $car_id = '') {
        $sql = DB::table('wishlist AS W');
        
        if($id !== ''){
            $sql->where('W.id', '=', $id);
        }
        
        if($customer_id !== ''){
            $sql->where('W.customer_id', '=', $customer_id);
        }
        
        if($car_id !== ''){
            $sql->where('W.car_id', 'like', '%'.$car_id.'%');
        }
        
        return $sql->get();
    }
    
    public static function insertWishlist($array = array()) {
                
        $sql = 'insert into wishlist ('
                . ' customer_id, '
                . ' car_id )'
                . ' values (?, ?)';
                
        $sqlValue = [
            $array['customer_id'],
            $array['car_id']            
        ];
        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function updateWishlist($array = array()) {
        $sqlValue = [
            'customer_id' => $array['customer_id'],
            'car_id' => $array['car_id'],           
        ];
        DB::table('wishlist')->where('id', '=', $array['id'])
                          ->update($sqlValue); 
        return true;
    }
    
    public static function updateCustomer($array = array()) {
        $sqlValue = [
            'name' => $array['name'],
            'phone' => $array['phone'],           
            'gender' => $array['gender'],           
            'birthday' => $array['birthday'],           
            'address' => $array['address'],           
            'country_code' => $array['country_code'],           
        ];
        if(isset($array['password'])&& $array['password'] !== ''){
          $sqlValue['password'] = md5($array['password']);
        }
        if(isset($array['photo'])&& $array['photo'] !== ''){
          $sqlValue['photo'] = $array['photo'];
        }
        DB::table('customer')->where('id', '=', $array['id'])
                          ->update($sqlValue); 
        return true;
    }
    
    public static function getUnreadMessage($status = ''){
      try{
        $q = DB::table('negotiation_line as NL')
            ->join('negotiation as N','N.id','=','NL.negotiation_id')
            ->where('NL.isread', '0')
            ->where('N.customer_id','=',Session::get('user_id'))    
            ->where('NL.customer_chat_id', null);

        if($status !== ''){
          $q->whereIn('N.status',$status);
        }
        $q->groupBy('N.id');
        $db['total']  = count($q->get());
        
        return $db['total'];
      } catch (Exception $ex) {

      }
    }
    
    public static function getUnreadMessageId(){
      try{
        $unread_negotiation = [];
    
        $q = DB::table('negotiation_line as NL')
              ->join('negotiation as N','N.id','=','NL.negotiation_id')
              ->where('NL.isread', '0')
              ->where('N.customer_id','=',Session::get('user_id'))    
              ->where('NL.customer_chat_id', null)
              ->groupBy('N.id')  
              ->select('N.id')->get();
        foreach($q as $detail){
          array_push($unread_negotiation, $detail->id);
        }
        
      return $unread_negotiation;
      } catch (Exception $ex) {
        Throw new \Exception('error unread message'. print_r($ex));
      }
    }
    
    
    public static function getNegotiation($id = '', $customer_id = '', $user_id = '', $car_id = '', $total = false, $limit ='', $offset ='', $orderby = '', $filter = '', $status = '') {
                
        $sql = DB::table('negotiation AS N')
                ->Join('car as CA','N.car_id','=','CA.id')
                ->leftJoin('user as U','N.user_id','=','U.id')
                ->leftJoin('customer as C','N.customer_id','=','C.id')
                ->leftJoin('car_model as CM','CM.id','=','CA.model_id')
                ->leftJoin('car_make as CMA','CMA.id','=','CA.make_id')
                ->leftJoin('negotiation_status as NS','NS.id','=','N.status')
                ->leftJoin('port_price as PP','PP.destination_id','=','N.port_destination_id')
                ->leftJoin('logistic as L','L.id','=','N.logistic_id')
                ->leftJoin('invoice as I','I.negotiation_id','=','N.id')
                ->select('N.*','CA.serial','CA.vin','CA.keyword','CA.description',
                        'C.name as customer_name','C.address as customer_address','C.email as customer_email',
                        'U.name as sales_name','NS.description as status_description','CA.picture','L.company_name','I.due_date','I.id as invoice_id','CM.model','CMA.make','PP.price AS port_price','PP.volume_price');
        
        if($id !== ''){
            $sql->where('N.id', '=', $id);
        }
        
        if($customer_id !== ''){
            $sql->where('N.customer_id', '=', $customer_id);
        }
        
        if($user_id !== ''){
            $sql->where('N.user_id', '=', $user_id);
        }
        
        if($car_id !== ''){
            $sql->where('N.car_id', '=', $car_id);
        }
        
        if($status !== ''){
            $sql->whereIn('N.status',$status);
        }
        
        if($filter !== ''){
          $sql->whereRaw("(CA.description like '%". $filter ."%' or CA.serial like '%". $filter ."%' or CA.vin like '%". $filter ."%' or C.email like '%". $filter ."%' )");
        }
        
        if($limit !=='' && $offset !==''){
          $sql->limit($limit);
          $sql->offset($offset);
        }
        
        if($orderby !== ''){
          $order_arr = explode('-',$orderby);
          $sql->orderby($order_arr[0],$order_arr[1]);
        }
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getDocumentCopy($id = '', $invoice_id = '') {
                
        $sql = DB::table('document_copy AS DC');
                
        
        if($id !== ''){
            $sql->where('DC.id', '=', $id);
        }
        
        if($invoice_id !== ''){
            $sql->where('DC.invoice_id', '=', $invoice_id);
        }
        
        return $sql->get();
    }
    
    public static function updateCustomerStatus($email){
      $sqlValue = [
            'status' => 1,
        ];
        DB::table('customer')->where('email', '=', $email)
                          ->update($sqlValue);
    }
    
    public static function getNegotiationLine($id = '', $negotiation_id = '', $user_chat_id = '') {
                
        $sql = DB::table('negotiation_line AS NL')
                ->leftjoin('customer as C','C.id','=','NL.customer_chat_id')
                ->leftjoin('user as U','U.id','=','NL.user_chat_id')
                ->select('NL.*','C.name as customer_name','U.name as sales_name');
        
        if($id !== ''){
            $sql->where('NL.id', '=', $id);
        }
        
        if($negotiation_id !== ''){
            $sql->where('NL.negotiation_id', '=', $negotiation_id);
        }
        
        if($user_chat_id !== ''){
            $sql->where('NL.user_chat_id', '=', $user_chat_id);
        }
        $sql->orderBy('NL.negotiation_line_id','desc');
        
        return $sql->get();
    }
   
    public static function updateAllNotification($negotiation_id){
      try{
        $result = DB::table('negotiation_line')
          ->where('negotiation_id', '=', $negotiation_id)
          ->where('customer_chat_id', null)
          ->update(['isread'=>1]);
        return $result;
      } catch (Exception $ex) {
        Throw new \Exception('update notification failed'.print_r($ex));
      }      
    }
    
    public static function updateNegotiationStatus($array = array()) {
                
      $sqlValue = [
          'status' => $array['status'],
      ];
      if(isset($array['user_id']) && $array['user_id'] != ''){
        $sqlValue['user_id'] = $array['user_id'];
      }
      if(isset($array['price']) && $array['price'] !== ''){
        $sqlValue['price'] = $array['price'];
      }
      if(isset($array['shipping_price']) && $array['shipping_price'] !== ''){
        $sqlValue['shipping_price'] = $array['shipping_price'];
      }
      if(isset($array['car_price']) && $array['car_price'] !== ''){
        $sqlValue['car_price'] = $array['car_price'];
      }
      if(isset($array['insurance_fee']) && $array['insurance_fee'] !== ''){
        $sqlValue['insurance_fee'] = $array['insurance_fee'];
      }
      if(isset($array['inspection_fee']) && $array['inspection_fee'] !== ''){
        $sqlValue['inspection_fee'] = $array['inspection_fee'];
      }
      DB::table('negotiation')->where('id', '=', $array['id'])
                          ->update($sqlValue);
      return true;
    }
    
    public static function updateCarStatus($array = array()) {
                
      $sqlValue = [
          'status' => $array['status'],
      ];
      DB::table('car')->where('id', '=', $array['id'])
                          ->update($sqlValue); 
      return true;
    }
    
    public static function insertNegotiation($array = array()) {
                
        $sql = 'insert into negotiation ('
                . ' car_id, '
                . ' user_id, '
                . ' customer_id, '
                . ' price, '
                . ' status, '
                . ' currency, '
                . ' insurance, '
                . ' inspection,'
                . ' port_destination_id, '
                . ' logistic_id,'
                . ' shipping_price,'
                . ' car_price,'
                . ' insurance_fee,'
                . ' inspection_fee,'
                . ' ocean_freight_fee'
                . ' )'
                . ' values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
               
        $sqlValue = [
            $array['car_id'],
            $array['user_id'],
            $array['customer_id'],
            $array['price'],
            1,
            $array['currency'],
            $array['insurance'],
            $array['inspection'],
            $array['destination_id'],
            $array['logistic_id'],
            $array['shipping_price'],
            $array['car_price'],
            $array['insurance_fee'],
            $array['inspection_fee'],
            $array['ocean_freight_fee']
        ];

        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function insertNegotiationLine($array = array()) {
      $param = [
        'negotiation_id', 'chat', 'file'
      ];  
      
      $value = [
        '?' , '?' , '?'
      ];
      
      $sqlValue = [
            $array['negotiation_id'],
            $array['chat'],
            $array['file']
        ];
      
      if(isset($array['customer_chat_id'])){
        array_push($param, 'customer_chat_id');
        array_push($value, '?');
        array_push($sqlValue,$array['customer_chat_id']);
      }elseif(isset($array['user_chat_id'])){
        array_push($param, 'user_chat_id');
        array_push($value, '?');
        array_push($sqlValue,$array['user_chat_id']);
      }


        $sql = 'insert into negotiation_line ('.implode($param,',').') values ('.implode($value,',').')';
        
        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function insertChat($array = array()) {
                
        $sql = 'insert into negotiation_line ('
                . ' chat_id, '
                . ' chat, '
                . ' file, '
                . ' user_chat_id'
                . ' )'
                . ' values (?, ?, ?)';
               
        $sqlValue = [
            $array['chat'],
            $array['file'],
            $array['user_chat_id']            
        ];

        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function getLastAutoNumber($nbr = '',$column ='', $table = '') {
        try {
            
            $sql = "select right(".$column.",4)+1 as ".$column." from ".$table." where ".$column." like '%" . $nbr . "%' order by id DESC limit 1";
            $q = DB::select($sql);
            
            return $q;
        } catch (Exception $ex) {
            return $ex;
        }
    }
    
    public static function insertInvoice($array = array()) {
                
        $sql = 'insert into invoice ('
                . ' invoice_number, '
                . ' negotiation_id, '
                . ' consignee_country, '
                . ' final_destination, '
                . ' total_amount, '
                . ' currency, '
                . ' proforma_invoice_id, '                
                . ' createdby, '
                . ' createdon, '
                . ' due_date '                
                . ' )'
                . ' values (?, ?, ?, ?, ?, ?, ?, ?, now(),?)';
               
        $sqlValue = [
            $array['invoice_number'],
            $array['negotiation_id'],
            $array['consignee_country'],
            $array['final_destination'],
            $array['total_amount'],
            $array['currency'],
            $array['proforma_invoice_id'],
            $array['created_by'],
            $array['due_date'],
        ];

        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function getInvoice($id = '', $negotiation_id = '', $invoice_number = '', $status = '', $total = false) {
                
        $sql = DB::table('invoice AS I')
                ->leftjoin('invoice_status AS IS','IS.id','=','I.status')
                ->leftjoin('negotiation AS N','N.id','=','I.negotiation_id')
                ->leftjoin('negotiation_status AS NS','N.status','=','NS.id')
                ->select('I.*','IS.description as invoice_description','N.status as negotiation_status','NS.description as negotiation_status_description','N.resi_number');
        
        if($id !== ''){
            $sql->where('I.id', '=', $id);
        }
        
        if($negotiation_id !== ''){
            $sql->where('I.negotiation_id', '=', $negotiation_id);
        }
        
        if($invoice_number !== ''){
            $sql->where('I.invoice_number', '=', $invoice_number);
        }
        
        if($status !== ''){
          if(is_array($status)){
            $sql->whereIn('I.status', $status);
          }else{
            $sql->where('I.status', '=', $status);
          }            
        }
        
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    public static function getInvoicePayment($id = '', $invoice_number = '') {
                
        $sql = DB::table('invoice_payment AS IP');
        
        if($id !== ''){
            $sql->where('IP.id', '=', $id);
        }
        
        if($invoice_number !== ''){
            $sql->where('IP.invoice_number', '=', $invoice_number);
        }
                
        return $sql->get();
    }
    
    
    public static function insertPaymentInvoice($array = array()) {
                
        $sql = 'insert into invoice_payment ('
                . ' invoice_number, '
                . ' bank, '
                . ' bank_account, '
                . ' account_name, '
                . ' transfer_date, ' 
                . ' total_payment, ' 
                . ' attachment '
                . ' )'
                . ' values (?, ?, ?, ?, ?, ?, ?)';
               
        $sqlValue = [
            $array['invoice_number'],
            $array['bank'],
            $array['bank_account'],
            $array['account_name'],
            $array['transfer_date'],
            $array['total_payment'],
            $array['file']
        ];

        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function insertProformaInvoice($array = array()) {
                
        $sql = 'insert into proforma_invoice ('
                . ' proforma_invoice_number, '
                . ' negotiation_id, '
                . ' createdby, '
                . ' createdon, '
                . ' currency, ' 
                . ' shipping_price, '
                . ' total_amount , '
                . ' port_destination, '
                . ' inspection '
                . ' )'
                . ' values (?, ?, ?, now(), ?, ?, ?, ?, ?)';
               
        $sqlValue = [
            $array['proforma_invoice_number'],
            $array['negotiation_id'],
            $array['created_by'],
            $array['currency'],
            $array['shipping_price'],
            $array['total_amount'],
            $array['port_destination'],
            $array['inspection']
        ];

        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function updateProformaInvoice($array = array()) {
      $sqlValue = [
          'company_name' => $array['company_name'],
          'address' => $array['address'],
          'city' => $array['city'],
          'telephone' => $array['telephone'],
          'fax' => $array['fax'],
          'car_width'=> $array['car_width'],
          'car_height'=> $array['car_height'],
          'car_length'=> $array['car_length'],     
          'port_departure' => $array['port_departure'], 
          'port_destination' => $array['port_destination'], 
          'country_code' => $array['country_code'],
          'total_amount' => $array['total_amount'],
          'shipping_price' => isset($array['shipping_price'])?$array['shipping_price']:0,
          'currency' => $array['currency'],
          'inspection' => $array['inspection'],
          'detail' => $array['detail'],
          'incoterm' => $array['incoterm'],
          'sales_agreement' => $array['sales_agreement'],
          'status' => $array['status'],
          'inspection_value' => isset($array['inspection_value'])?$array['inspection_value']:'',
          'payment_due' => isset($array['payment_due'])?$array['payment_due']:'',
          'approval' => isset($array['approval'])?$array['approval']: 0,
          'customer_approval' => isset($array['customer_approval'])?$array['customer_approval']: 0,
      ];
      $result = DB::table('proforma_invoice')->where('id', '=', $array['id'])
                          ->update($sqlValue); 
      
      return $result;
    }
    
    public static function approveProformaInvoice($array = array()) {
      if(isset($array['approval'])){
        $sqlValue['approval'] = 1;
      }elseif(isset($array['customer_approval'])){
        $sqlValue['customer_approval'] = 1;
      }else{
        return false;
      }
      $result = DB::table('proforma_invoice')->where('id', '=', $array['id'])
                          ->update($sqlValue); 
      
      return $result;
    }
    
    public static function getProformanceLog($id){
      $sql = DB::table('proforma_invoice_log AS L')
             ->where('L.proforma_invoice_id', '=', $id)
             ->orderBy('id','desc');
             
      return $sql->get();
    }
    
    public static function saveProformanceInvoiceLog($arr) {
      $sql = 'insert into proforma_invoice_log ('
                . ' proforma_invoice_id, '
                . ' content, '
                . ' createdon, '
                . ' createdby '
                . ' )'
                . ' values (?, ?, ?, ?)';
               
        $sqlValue2 = [
            $arr['id'],
            json_encode($arr),
            Carbon::now()->toDateTimeString(),
            Session::get('name')
        ];

        $result = DB::insert($sql ,$sqlValue2);
        
        return $result;
    }
    
    public static function getProformaInvoice($id = '', $negotiation_id = '', $invoice_number = '') {
                
        $sql = DB::table('proforma_invoice AS I')
        ->join('negotiation as N','I.negotiation_id','=','N.id')
        ->join('customer as C','C.id','=','N.customer_id')
        ->select('I.*','N.insurance','N.inspection as negotiation_inspection','N.port_destination_id','N.price as negotiation_price','N.currency as negotiation_currency','N.id as negotiation_id','C.name');
        
        if($id !== ''){
            $sql->where('I.id', '=', $id);
        }
        
        if($negotiation_id !== ''){
            $sql->where('I.negotiation_id', '=', $negotiation_id);
        }
        
        if($invoice_number !== ''){
            $sql->where('I.proforma_invoice_number', '=', $invoice_number);
        }
        
        return $sql->get();
    }
    
    
    public static function getAllCountry() {
                
        $sql = DB::table('country');
        return $sql->get();
    }
    
    public static function updateInvoice($array) {
        
        $sqlValue = $array;
        
        DB::table('invoice')->where('id', '=', $array['id'])
                          ->update($sqlValue); 
      return true;
    }
    
    public static function updateSellerPassword($passwordArray = []) {
        DB::table('seller')->where('email', '=', $passwordArray['email'])->update(['password' => $passwordArray['password']]); 
        return true;
    }
    
    public static function updateCustomerPassword($passwordArray = []) {
        DB::table('customer')->where('email', '=', $passwordArray['email'])->update(['password' => $passwordArray['password']]); 
        return true;
    }
    
    public static function saveRating($array = array()) {
                
        $sql = 'insert into review ('
                . ' email, '
                . ' rating, '
                . ' invoice_id, '
                . ' comment, '
                . ' speed, '
                . ' accuration, '
                . ' satisfication, '
                . ' isdisplayed,'
                . ' createdon '
                . ' )'
                . ' values (?, ?, ?, ?, ?, ?, ?, 0, now())';
               
        $sqlValue = [
            Session::get('email'),
            $array['rating_star'],
            $array['invoiceid'],
            $array['rating_comment'],
            $array['speed_rating_star'],
            $array['accuration_rating_star'],
            $array['satisfication_rating_star']
        ];

        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function getReview($invoiceid = '') {
                
        $sql = DB::table('review AS R');        
        
        if($invoiceid !== ''){
            $sql->where('R.invoice_id', '=', $invoiceid);
        }       
        
        return $sql->get();
    }
    
    public static function removeReview($id = '') {
      try{
        if($id !== ''){
          $result = DB::table('review')->where('id', '=', $id)->delete();
        }else{
          $result = false;
        }
        return $result;
      } catch (Exception $ex) {
        return $ex->getMessage();
      } 
    }
    
    public static function getAllBanner($id = '', $limit = '', $isactive = '', $total = false) {
        $sql = DB::table('banner AS B');
                       
        if($id !== ''){
            $sql->where('B.id', '=', $id);
        }
        
        if($isactive !== ''){
            $sql->where('B.isactive', '=', $isactive);
        }
        
        $sql->orderby('id','DESC');
        
        if($limit !== ''){
            $sql->limit($limit,0);
        }
        
        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public static function insertPrivilege($privilegeArray = array()) {

        DB::insert('insert into privilege (privilege_name) values (?)', 
                    [
                        $privilegeArray['privilegeName'],
                    ]);

        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function updatePrivilege($privilegeArray = array()) {
        DB::table('privilege')->where('id', '=', $privilegeArray['privilegeID'])
                          ->update(['description' => $privilegeArray['privilegeName'],
                              ]); 
        return true;
    }
    
    
    public static function insertInvoiceStatus($statusArray = array()) {
                
        $sql = 'insert into oms_invoice_status ('
                . 'marketplace_id, '
                . 'delivery_provider, '
                . 'tracking_number, '
                . 'cancel_reason, '
                . 'invoice_number, '
                . 'status) '
                . 'values (?, ?, ?, ?, ? , ?)';
                
        $sqlValue = [
            $statusArray['marketPlaceID'],
            $statusArray['deliveryProvider'],
            $statusArray['trackingNumber'],
            $statusArray['cancelReason'],
            $statusArray['soStoreNumber'],
            $statusArray['status'],
            
        ];
        
        DB::insert($sql ,$sqlValue);
        
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function updateInvoiceStatus($statusArray = array()) {
        $sqlValue = [
            'delivery_provider' => $statusArray['deliveryProvider'],
            'tracking_number' => $statusArray['trackingNumber'],
            'cancel_reason' => $statusArray['cancelReason'],
            'status' => $statusArray['status'],                       
        ];
        DB::table('oms_invoice_status')->where('invoice_number', '=', $statusArray['soStoreNumber'])
                            ->where('marketplace_id', '=', $statusArray['marketPlaceID'])
                          ->update($sqlValue); 
        return true;
    }
    
    public static function getAllPurchaseOrder($purchaseorderID = '', $businessunitid = '', $date = '', $total = false) {
        $sql = DB::table('purchase_order AS PO')
        ->join('status AS S','S.id','=', 'PO.status')
        ->leftJoin('supplier AS SP','SP.id','=', 'PO.supplier_id')    
        ->join('business_unit AS BU', 'BU.id', '=', 'PO.business_unit_id')        
        ->select('PO.purchase_order_number','PO.total','PO.transaction_date','PO.status','S.description as status_description',
                'SP.name','PO.supplier_id', 'PO.id','PO.business_unit_id', 'BU.name as business_unit_name');
        
        if($purchaseorderID !== ''){
            $sql->where('PO.id', '=', $purchaseorderID);
        }
        
        if($businessunitid !== ''){
            $sql->where('PO.business_unit_id', '=', $businessunitid);
        }
        
        if($date !== ''){
            $sql->where('PO.transaction_date', '=', $date);
        }

        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
    }
    
    public static function getAllPurchaseOrderLine($id = '',$purchaseorderID = '') {
        $sql = DB::table('purchase_order_line AS POL')
        ->leftJoin('product AS P','P.id','=', 'POL.product_id')
        ->select('POL.*','P.description as product_description','P.barcode_code');
        
        if($id !== ''){
            $sql->where('POL.id', '=', $id);
        }
        
        if($purchaseorderID !== ''){
            $sql->where('POL.purchase_order_id', '=', $purchaseorderID);
        }
        
        return $sql->get();
    }
    
    public static function getAllSalesOrder($salesorderID = '', $businessunitid = '', $date = '', $total = false) {
        $sql = DB::table('sales_order AS SO')
        ->join('status AS S','S.id','=', 'SO.status')
        ->leftJoin('customer AS C','C.id','=', 'SO.customer_id')   
        ->join('business_unit AS BU', 'BU.id', '=', 'SO.business_unit_id')        
        ->select('SO.sales_order_number','SO.total_before_discount','SO.total_discount','SO.total_after_discount', 'SO.status', 'SO.transaction_date',
                'S.description as status_description','C.name','SO.id', 'BU.name as business_unit_name', 'BU.id as business_unit_id', 'SO.customer_id');
        
        if($salesorderID !== ''){
            $sql->where('SO.id', '=', $salesorderID);
        }
        
        if($businessunitid !== ''){
            $sql->where('SO.business_unit_id', '=', $businessunitid);
        }
        
        if($date !== ''){
            $sql->where('SO.transaction_date', '=', $date);
        }

        if($total){
            return $sql->count();
        }else{
            return $sql->get();
        }
    }
    
    public static function getAllSalesOrderLine($id = '',$salesorderID = '') {
        $sql = DB::table('sales_order_line AS SOL')
        ->leftJoin('product AS P','P.id','=', 'SOL.product_id')
        ->select('SOL.*','P.description as product_description', 'P.barcode_code');
        
        if($id !== ''){
            $sql->where('SOL.id', '=', $id);
        }
        
        if($salesorderID !== ''){
            $sql->where('SOL.sales_order_id', '=', $salesorderID);
        }
        
        return $sql->get();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public static function getAllConfig($configID = '', $configName = '') {

        $config = DB::table('configuration');
        
        if ($configID !== "") {
            $config->where('configurationID', '=', $configID);
        }

        if ($configName !== "") {
            $config->where('field', '=', $configName);
        }
        
        return $config->get();
    }

    public static function updateConfiguration($configID = '', $value='') {

        DB::table('configuration')->where('configurationID', '=', $configID)->update(['value' => $value]); 
        return true;
    }
    
    
    
    public static function updatePasswordMerchant($passwordArray = []) {

        DB::table('vendor')->where('vendor_id', '=', $passwordArray['userID'])->update(['password' => $passwordArray['password']]); 
        return true;
    }
    
    public static function updatePasswordMember($passwordArray = []) {

        DB::table('users')->where('id', '=', $passwordArray['userID'])->update(['password' => $passwordArray['password']]); 
        return true;
    }
    public static function getAllCategory($categoryID = '', $active = '') {

        $category = DB::table('product_type');
        
        if ($categoryID !== "") {
            $category->where('product_type_id', '=', $categoryID);
        }

        if ($active !== "") {
            $category->where('active', '=', $active);
        }
        
        return $category->get();
    }
    
    public static function insertCategory($categoryArray = array()) {

        DB::insert('insert into product_type (description, active) values (?, ?)', 
                    [$categoryArray['description'], 
                    '1']);

        return true;
    }
    
    public static function updateCategory($categoryArray = array()) {

        DB::table('product_type')->where('product_type_id', '=', $categoryArray['productTypeID'])->update(['description' => $categoryArray['description']]); 
        return true;
    }
    
    public static function insertSubCategory($categoryArray = array()) {

        DB::insert('insert into product_sub_type (name, active, product_type_id) values (?, ?, ?)', 
                    [
                        $categoryArray['description'], 
                        '1',
                        $categoryArray['productCategoryID']    
                    ]);
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function updateSubCategory($categoryArray = array()) {

        DB::table('product_sub_type')->where('product_sub_type_id', '=', $categoryArray['productSubCategoryID'])
                ->update(['name' => $categoryArray['description'],
                    'product_type_id' => $categoryArray['productCategoryID']]); 
        return true;
    }
    
    public static function insertSubCategoryDetail($categoryArray = array()) {

        DB::insert('insert into product_sub_type_detail (name, active, product_sub_type_id) values (?, ?, ?)', 
                    [
                        $categoryArray['description'], 
                        '1',
                        $categoryArray['productSubType']
                        ]);
        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }
    
    public static function updateSubCategoryDetail($categoryArray = array()) {

        DB::table('product_sub_type_detail')
                ->where('product_sub_type_detail_id', '=', $categoryArray['productSubDetailCategoryID'])
                ->update(['name' => $categoryArray['description'],'product_sub_type_id' => $categoryArray['productSubType']]); 
        return true;
    }
    
    public static function getAllCommision ($commisionID = '',$productType = '', $vendorTypeID='', $commisionType = '') {

        $commision = DB::table('commision AS C')
                ->leftJoin('vendor_type AS VP','C.vendor_type_id','=', 'VP.vendor_type_id')
                ->leftJoin('product_type AS PT','PT.product_type_id','=', 'C.product_type_id')
                ->leftJoin('commision_type AS CT','CT.commision_type_id','=','C.commision_type_id')
                ->select('C.*','VP.vendor_type_description as desc','PT.description AS product_type_desc','CT.description as commision_type_desc');
                
        if ($commisionID !== "") {
            $commision->where('C.commision_id', '=', $commisionID);
        }
        
        if ($productType !== "") {
            $commision->where('C.product_type_id', '=', $productType);
        }
        
        if ($vendorTypeID !== "") {
            $commision->where('C.vendor_type_id', '=', $vendorTypeID);
        }

        if ($commisionType !== "") {
            $commision->where('C.commision_type_id', '=', $commisionType);
        }
        return $commision->get();
    }
    
    public static function getAllCommisionType() {
        $commision = DB::table('commision_type')->select('commision_type_id','description');
       
        return $commision->get();
    }
    
    public static function getAllVendorType ($vendorTypeID = '') {

        $sql = DB::table('vendor_type as VP')                
                ->select('VP.vendor_type_id','VP.vendor_type_description as desc');
                
        if ($vendorTypeID !== "") {
            $sql->where('VP.vendor_type_id', '=', $vendorTypeID);
        }

        return $sql->get();
    }
    
    public static function deactiveItem($ID,$primaryKey,$table,$value) {

        DB::table($table)->where($primaryKey, '=', $ID)->update(['active' => $value]); 
        return true;
    }
    
    public static function deleteItem($ID,$primaryKey,$table) {
        
        DB::table($table)->where($primaryKey, '=', $ID)->delete();
        return true;
    }
    
    public static function deleteSoftItem($ID,$primaryKey,$table,$value) {
        
        DB::table($table)->where($primaryKey, '=', $ID)->update(['isdelete' => $value]); 
        return true;
    }
        
    public static function updateCommision($commisionArray = array()) {

        DB::table('commision')->where('commision_id', '=', $commisionArray['commisionID'])
                ->update(['start_price' => $commisionArray['startPrice'],
                    'end_price' => $commisionArray['endPrice'],
                    'percentage' => $commisionArray['percentage'],
                    'vendor_type_id' => $commisionArray['vendorTypeID'],
                    'product_type_id' => $commisionArray['productTypeID'],
                    'commision_type_id' => $commisionArray['commisionTypeID']]); 
        return true;
    }
    
    public static function insertCommision($commisionArray = array()) {

        DB::insert('insert into commision (start_price, end_price, percentage, vendor_type_id, product_type_id, commision_type_id) values (?, ?, ?, ?, ?, ?)', 
                    [$commisionArray['startPrice'], 
                    $commisionArray['endPrice'],
                    $commisionArray['percentage'], 
                    $commisionArray['vendorTypeID'], 
                    $commisionArray['productTypeID'],
                    $commisionArray['commisionTypeID']]
                );

        return true;
    }
    
    public static function getAllPaymentType ($paymentTypeID = '') {

        $sql = DB::table('payment_type as PT')                
                ->select('PT.payment_type_id','PT.description as desc', 'PT.active');
                
        if ($paymentTypeID !== "") {
            $sql->where('PT.payment_type_id', '=', $paymentTypeID);
        }

        return $sql->get();
    }
    
    public static function insertPayment($paymentArray = array()) {

        DB::insert('insert into payment_type (description, active) values (?, ?)', 
                    [$paymentArray['description'], 
                    '1']);

        return true;
    }
    
    public static function updatePayment($paymentArray = array()) {

        DB::table('payment_type')->where('payment_type_id', '=', $paymentArray['paymentTypeID'])->update(['description' => $paymentArray['description']]); 
        return true;
    }
    
    public static function getAllLogin($userID = '', $isdelete= '', $category = '') {

        $sql = DB::table('login AS L')                
                ->leftJoin('kategori AS K','K.code','=','L.kategori_code')
                ->select('user_id','name','hp','telepon','email','password','kategori_code','photo','active','birthday','address', 'status', 'gender', 'photo', 'sallary', 'region', 'createdon','K.description as kategori_description','note');
                
                
        if ($userID !== "") {
            $sql->where('user_id', '=', $userID);
        }
        
        if ($isdelete !== "") {
            $sql->where('isdelete', '=', $isdelete);
        }
        
        if ($category !== "") {
            $sql->where('kategori_code', '=', $category);
        }
        
        return $sql->get();
    }
    
    public static function insertOfficeMember($memberArray = array()) {

        DB::insert('insert into login (name, address, hp, telepon, email, password, kategori_code, birthday, status, region, sallary,gender, photo, createdon,isdelete,note) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), 0, ?)', 
                    [
                        $memberArray['name'], 
                        $memberArray['address'],
                        $memberArray['hp'],
                        $memberArray['telepon'],
                        $memberArray['email'],
                        $memberArray['password'],
                        $memberArray['privilege'],
                        $memberArray['birthdate'],
                        $memberArray['status'],
                        $memberArray['region'],
                        $memberArray['sallary'],
                        $memberArray['gender'],
                        $memberArray['photo'],
                        $memberArray['note'],
                    ]);

        return true;
    }
    
    
    
    
    
    
    
    public static function getAllLoginMerchant($vendorID = '', $isdelete= '' , $count = false) {

        $sql = DB::table('vendor')                
                ->select('vendor_id','name','address','password','email','active','created_at','phone','contact_person','note','photo');
                
        if ($vendorID !== "") {
            $sql->where('vendor_id', '=', $vendorID);
        }
        
        if ($isdelete !== "") {
            $sql->where('isdelete', '=', $isdelete);
        }
        
        $sql->orderBy('name', 'asc');
        
        if(!$count) {
            return $sql->get();
        }else{
            return $sql->count();
        }
    }
    
    public static function insertMemberMerchant($memberArray = array()) {

        DB::insert('insert into vendor (name, address, phone, email, password, created_at ,isdelete, contact_person, note, photo) values (?, ?, ?, ?, ?, ?,  0, ?, ?, ?)', 
                    [
                        $memberArray['name'], 
                        $memberArray['address'],
                        $memberArray['phone'],
                        $memberArray['email'],
                        $memberArray['password'],
                        $memberArray['joinDate'],
                        $memberArray['contactPerson'],                        
                        $memberArray['note'],
                        $memberArray['photo'],
                    ]);

        $lastId = DB::getPdo()->lastInsertId();
        return $lastId;
    }

    public static function getMerchantBranch($vendorID = '', $isDelete = ''){
        $sql = DB::table('vendor_branch')                
                ->select('vendor_branch_id','description','address', 'phone');
                
        if ($vendorID !== "") {
            $sql->where('vendor_id', '=', $vendorID);
        }
        
        if ($isDelete !== "") {
            $sql->where('isdelete', '=', $isDelete);
        }
        
        return $sql->get();        
    }
    
    public static function insertMerchantBranch($branchArray = array()) {
        
        $sql = 'insert into vendor_branch (vendor_id, description, address, isdelete, phone) '
                . 'values (?, ?, ?, 0, ?)';
        
        $sqlValue = [
            $branchArray['vendorID'],
            $branchArray['description'],
            $branchArray['address'],
            $branchArray['phone'],    
        ];
        DB::insert($sql ,$sqlValue);
        return true;
    }
    
    public static function updateMemberMerchant($memberArray = array()) {

        DB::table('vendor')->where('vendor_id', '=', $memberArray['vendorID'])
                          ->update(['name' => $memberArray['name'],
                            'address' => $memberArray['address'],
                            'phone' => $memberArray['phone'],
                            'contact_person' => $memberArray['contactPerson'],
                            'photo' => $memberArray['photo'],
                            'note' => $memberArray['note'],
                              ]); 
        return true;
    }
    
    
    public static function getRandomProduct($exceptproductID = '', $limit = '') {

        $sql = DB::table('product AS P')
                ->leftJoin('product_type AS PT','PT.product_type_id','=', 'P.product_type_id')
                ->leftJoin('login AS L','L.user_id','=', 'P.salesby')
                ->leftJoin('login AS L2','L2.user_id','=', 'P.createdby')
                ->leftJoin('product_sub_type AS PST','PST.product_sub_type_id','=','P.product_sub_type_id')
                ->leftJoin('product_sub_type_detail AS PSTD','PSTD.product_sub_type_detail_id','=','P.product_sub_type_detail_id')
                ->select('P.*','PT.description AS productTypeDesc', 'PSTD.name AS productSubTypeDetailName', 'PST.name as productSubTypeName' , 'L.name AS salesName', 'L2.name AS createdby')
                ->inRandomOrder();
        
        if ($exceptproductID !== "") {
            $sql->where('P.product_id', '<>', $exceptproductID);
        }
        
        $sql->where('P.isdelete', '=', 0)
            ->where('P.active','=',1);
       
        $sql->where('P.startdate', '<=', date("Y-m-d"));
        $sql->where('P.enddate', '>=', date("Y-m-d"));
            
        if ($limit !== "") {
            $sql->take($limit);
        }
        
        
        
            return $sql->get();
        
    }
    
    
    
    public static function getAllProductSubType($productSubTypeID = '', $active = '', $productTypeID ='') {

        $sql = DB::table('product_sub_type AS PS')                
                ->select('PS.product_sub_type_id', 'PS.product_type_id', 'PS.name', 'PS.active', 'PS.url', 'PS.order_index');
                
        if ($productSubTypeID !== "") {
            $sql->where('PS.product_sub_type_id', '=', $productSubTypeID);
        }
        
        if ($productTypeID !== "") {
            $sql->where('PS.product_type_id', '=', $productTypeID);
        }
        
        if ($active !== "") {
            $sql->where('PS.active', '=', $active);
        }

        return $sql->get();
    }
    
    public static function getAllProductSubTypeDetail($productSubTypeDetailID = '', $active = '', $productSubTypeID ='') {

        $sql = DB::table('product_sub_type_detail AS PSD')                
                ->select('PSD.product_sub_type_detail_id', 'PSD.product_sub_type_id', 'PSD.name', 'PSD.active', 'PSD.url', 'PSD.order_index');
                
        if ($productSubTypeDetailID !== "") {
            $sql->where('PSD.product_sub_type_detail_id', '=', $productSubTypeDetailID);
        }
        
        if ($productSubTypeID !== "") {
            $sql->where('PSD.product_sub_type_id', '=', $productSubTypeID);
        }
        
        if ($active !== "") {
            $sql->where('PSD.active', '=', $active);
        }

        return $sql->get();
    }
    
    public static function getAllPhotoProduct($productID = '') {

        $sql = DB::table('photo AS P')
                ->where('P.product_id', '=', $productID)
                ->select('P.name','P.product_id as product_ID', 'P.photo_id as photo_ID');        

        return $sql->get();
    }
    
    public static function savePhotoProduct($imageArray = array()) {

        DB::insert('insert into photo (product_id, name) values (?, ?)', 
                    [
                        $imageArray['productID'], 
                        $imageArray['imageName'],
                    ]);

        return true;
    }
    
    public static function getAllProductLine($productID = '', $isdelete = '') {

        $sql = DB::table('product_line AS PL')
                ->leftJoin('vendor_branch AS VB','VB.vendor_branch_id','=','PL.branch' )
                ->where('PL.product_id', '=', $productID)
                ->where('PL.isdelete', '=', $isdelete)
                ->select('PL.product_line_id','PL.name', 'PL.description', 'PL.qty', 'PL.price','PL.promo_price', 'PL.weight', 'PL.active', 'PL.promo_price','VB.description as branch');        

        return $sql->get();
    }
    
    public static function getAllProductLineByID($productLineID = '') {

        $sql = DB::table('product_line AS PL')
                ->where('PL.product_line_id', '=', $productLineID)
                ->select('PL.product_line_id','PL.name', 'PL.description', 'PL.qty', 'PL.price','PL.promo_price', 'PL.weight', 'PL.active', 'PL.promo_price','PL.branch','PL.max_buying');        

        return $sql->get();
    }
    
    public static function saveProductLine($productLineArray = array()) {

        DB::insert('insert into product_line (product_id, name, description, qty, price, promo_price, weight, branch, active, isdelete, max_buying) values (?, ?, ?, ?, ?, ?, ?, ?, 1, 0,?)', 
                    [
                        $productLineArray['productID'], 
                        $productLineArray['name'],
                        $productLineArray['description'],
                        $productLineArray['qty'],
                        $productLineArray['price'],
                        $productLineArray['promoPrice'],
                        $productLineArray['weight'],
                        $productLineArray['branch'],
                        $productLineArray['maxBuying'],
                    ]);

        return true;
    }
    
    public static function updateProductLine($productLineArray = array()) {

        $sqlValue = [
            'name' => $productLineArray['name'],
            'description' => $productLineArray['description'],
            'qty' => $productLineArray['qty'],
            'price' => $productLineArray['price'],
            'promo_price' => $productLineArray['promoPrice'],
            'weight' => $productLineArray['weight'],
            'branch' => $productLineArray['branch'],
            'max_buying' => $productLineArray['maxBuying'],
        ];
        DB::table('product_line')->where('product_line_id', '=', $productLineArray['productLineID'])
                          ->update($sqlValue); 
        
        return true;
    }
    
    public static function getAllInvoice($invoiceID = '', $status= '', $count = false, $startdate = '', $enddate = '', $invoice_number = '') {
        
        
        
        $sql = DB::table('invoice AS I')
                ->leftJoin('users AS U','U.id','=','I.user_id')
                ->leftJoin('payment_type AS PT','PT.payment_type_id','=','I.payment_type_id')
                ->leftJoin('invoice_status AS IS','IS.invoice_status_id','=','I.status')
                ->select('I.*','U.name','PT.description AS pament_type','IS.description as statusInvoice');        

        if($invoiceID !== ""){
            $sql->where('I.invoice_id', '=', $invoiceID);
        }
        
        if($status !== ""){
            $sql->where('I.status', '=', $status);
        }
        
        if($startdate !== "" && $enddate !== ""){
            $startDateYMD = isset($startdate)?substr($startdate,6,4).'-'.substr($startdate,3,2).'-'.substr($startdate,0,2):"";
            $endDateYMD = isset($enddate)?substr($enddate,6,4).'-'.substr($enddate,3,2).'-'.substr($enddate,0,2):"";
        
            $sql->where('I.created_date', '>=', $startDateYMD);
            $sql->where('I.created_date', '<=', $endDateYMD);
        }
        
        if(!$count){
            return $sql->get();
        }else{
            return $sql->count();
        }
    }
    
    public static function getAllConfirmation($invoiceID = '') {

        $sql = DB::table('confirm_payment AS CP')
                ->leftJoin('bank_account AS BC','BC.bank_account_id','=','CP.admin_bank')
                ->where('invoice_id','=',$invoiceID)
                ->select('CP.*','BC.bank_name','BC.bank_number');

        return $sql->get();
    }
    
    
    
    public static function getAllInvoiceLine($invoiceLineID = '', $invoiceID= '') {

        $sql = DB::table('invoice_line AS IL')
                ->leftJoin('product AS P','P.product_id','=','IL.product_id')
                ->join('product_line AS PL','IL.product_line_id','=','PL.product_line_id')
                ->leftJoin('product_type AS PT','P.product_type_id','=','PT.product_type_id')
                ->select('IL.*','P.product_name','PL.name AS product_line_name', 'PT.delivery');        

        if($invoiceLineID !== ""){
            $sql->where('IL.invoice_line_id', '=', $invoiceLineID);
        }
        
        if($invoiceID !== ""){
            $sql->where('IL.invoice_id', '=', $invoiceID);
        }
        
        return $sql->get();
        
    }
    
    public static function getAllProductHistory($productID= '') {

        $sql = DB::table('invoice_line AS IL')
                ->leftJoin('product AS P','P.product_id','=','IL.product_id')
                ->join('product_line AS PL','IL.product_line_id','=','PL.product_line_id')
                ->join('invoice AS I','I.invoice_id','=','IL.invoice_id')
                ->select('IL.*','P.product_name','PL.name AS product_line_name', 'I.invoice_number', 'I.invoice_id');        

        if($productID !== ""){
            $sql->where('IL.product_id', '=', $productID);
        }
        
        return $sql->get();
        
    }
    
    public static function getAllBankAccount($bankAccountID = '', $active = '') {

        $sql = DB::table('bank_account AS B')
                ->leftJoin('payment_type AS PT','PT.payment_type_id','=','B.payment_method_id')
                ->select ('B.*','PT.description');
        
        if ($bankAccountID !== "") {
            $sql->where('bank_account_id', '=', $bankAccountID);
        }

        if ($active !== "") {
            $sql->where('active', '=', $active);
        }
        
        return $sql->get();
    }
    
    public static function insertBank($bankArray = array()) {

        DB::insert('insert into bank_account (bank_name, bank_number, payment_method_id, account_name, active) values (?, ?, ?, ?, 1)', 
                    [
                        $bankArray['bankName'], 
                        $bankArray['bankNumber'],
                        $bankArray['paymentType'],
                        $bankArray['accountName'],
                    ]);

        return true;
    }
    
    public static function updateBank($bankArray = array()) {
        $sqlValue = [
            'bank_name' => $bankArray['bankName'],
            'bank_number' => $bankArray['bankNumber'],
            'account_name' => $bankArray['accountName'],
            'payment_method_id' => $bankArray['paymentType'],
            
        ];
        DB::table('bank_account')->where('bank_account_id', '=', $bankArray['bankAccountID'])
                          ->update($sqlValue); 
        return true;
    }
    
    
    
    public static function cancelInvoice($invoiceID = '', $value= '') {
        DB::beginTransaction();
        try{
            $productLineArray = [];
            
            $sql = DB::table('invoice_line AS IL')
                ->leftJoin('product AS P','P.product_id','=','IL.product_id')
                ->join('product_line AS PL','IL.product_line_id','=','PL.product_line_id')
                ->leftJoin('product_type AS PT','P.product_type_id','=','PT.product_type_id')    
                ->select('IL.*','P.product_name','PL.name AS product_line_name', 'PT.delivery', 'PL.product_line_id', 'PL.qty as qty_stock');        

        
                if($invoiceID !== ""){
                    $sql->where('IL.invoice_id', '=', $invoiceID);
                }
        
            $productLineArray = $sql->get();    
                
           
            if(count($productLineArray) > 0){
                foreach($productLineArray as $productLineArrayDetail) {
                    $lastQtyItem = (int) $productLineArrayDetail->qty_stock + (int) $productLineArrayDetail->qty;
                    $productLineID = $productLineArrayDetail->product_line_id;
                    $updateValue = [
                        'qty' => $lastQtyItem
                    ];
                    $update = DB::table('product_line')->where('product_line_id', '=', $productLineID)->update($updateValue);
                }
            }

            $updateValue = [
                'status' => $value
            ];
            DB::table('invoice')->where('invoice_id', '=', $invoiceID)->update($updateValue); 
            DB::commit();
            return true;
            
        } catch (Exception $ex) {
            DB::rollBack();
        }
        
    }
    
    public static function getAllSlider($sliderID = '') {

        $sql = DB::table('slide AS S')                
                ->select('S.title','S.description','S.images','S.url', 'S.active', 'S.slideID');
                
                
        if ($sliderID !== "") {
            $sql->where('slideID', '=', $sliderID);
        }        
                
        return $sql->get();
    }
    
    public static function insertSlider($sliderArray = []){
        DB::insert('insert into slide (slideID, title, images, url, active) values (?, ?, ?, ?, 1)', 
                    [
                        $sliderArray['slideID'], 
                        $sliderArray['title'],
                        $sliderArray['images'],
                        $sliderArray['url'],
                    ]);

        return true; 
    }
    
    public static function updateSlider($sliderArray = []) {
        
        $updateValue = [
            'title' => $sliderArray['title'],
            'images' => $sliderArray['images'],
            'url' => $sliderArray['url'],
        ];

        DB::table('slide')->where('slideID', '=', $sliderArray['slideID'])->update($updateValue); 
        return true;
    }
    
    public static function getAllInvoiceCommision($invoiceID = ''){
        $sql = DB::table('invoice_line AS IL')         
                ->leftJoin('product AS P','P.product_id','=','IL.product_id')
                ->leftJoin('commision AS C', function($join)
                         {
                             $join->on('C.commision_type_id', '=', 'P.commision_type_id');
                             $join->on('P.product_type_id', '=', 'C.product_type_id');
                             $join->on('IL.grand_total','>=','C.start_price');
                             $join->on('IL.grand_total','<=','C.end_price');
                         })
                ->where('IL.invoice_id','=',$invoiceID)         
                ->select('IL.grand_total','C.percentage')         
            ;
                         
        return $sql->get();                 
    }
    
    public static function getAllCommisionHistory($commisionArray = []){
        $status = isset($commisionArray['status'])?$commisionArray['status']:"";
        $vendorID = isset($commisionArray['vendorID'])?$commisionArray['vendorID']:"";
        $startDate = isset($commisionArray['startdate'])?substr($commisionArray['startdate'],6,4).'-'.substr($commisionArray['startdate'],3,2).'-'.substr($commisionArray['startdate'],0,2):"";
        $endDate = isset($commisionArray['enddate'])?substr($commisionArray['enddate'],6,4).'-'.substr($commisionArray['enddate'],3,2).'-'.substr($commisionArray['enddate'],0,2):"";
        
        $sql = DB::table('commision_payment AS CP')         
                ->leftJoin('invoice AS I','I.invoice_id','=','CP.invoice_id')
                ->leftJoin('vendor AS V','V.vendor_id','=','CP.vendor_id')
                ->select('CP.*','V.name','I.invoice_number');
        
        if($status !== ""){
            $sql->where('CP.status','=',$status);
        }
                
        if($vendorID !== ""){
            $sql->where('CP.vendor_id','=',$vendorID);
        }
        
        if($startDate !== "" && $endDate !== ""){
            $sql->where('CP.created_on','>=',$startDate);
            $sql->where('CP.created_on','<=',$endDate);
        }
        
        return $sql->get();                 
    }

    public static function insertCustomer($array = array()) {
                
        $sql = 'insert into customer ('
                . 'email, '
                . 'name, '
                . 'password, '
                . 'phone, '
                . 'createdon, '
                . 'country_code ) '
                . 'values (?, ?, ?, ?, now(), ?)';
                
        $sqlValue = [
            $array['email'],
            $array['name'],
            $array['password'],
            $array['phone_number'],          
            $array['country_code'],          
        ];
        $result = DB::insert($sql ,$sqlValue);
        
        return $result;
    }
    
    public static function insertDealer($array = array()) {
                
        $sql = 'insert into seller ('
                . 'email, '
                . 'pic_name, '
                . 'password, '
                . 'contact, '
                . 'createdon) '
                . 'values (?, ?, ?, ?, now())';
                
        $sqlValue = [
            $array['email'],
            $array['name'],
            $array['password'],
            $array['phone_number'],          
        ];
        $result = DB::insert($sql ,$sqlValue);
        
        return $result;
    }
}
