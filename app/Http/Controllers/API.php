<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Models\Front_model;
use Request;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Support\Facades\Mail;

class API {

  public static function currency_format($nominal) {
    if(!is_numeric($nominal)){
      return $nominal;
    }
    return number_format($nominal, 0, '.', ',');
  }

  public static function dateTimeFormat($value){
    return date( "d M Y H:i:s", strtotime($value));
  }
  
  public static function dateFormat($value){
    return date( "d M Y", strtotime($value));   
  }
  
  public static function getDefaultConfig() {
    $defaultConfig = API::getSetting(array('phone', 'facebook', 'address', 'email'));
    $config = array();

    foreach ($defaultConfig as $defaultConfig_detail) {
      $config[$defaultConfig_detail->field] = $defaultConfig_detail->value;
    }

    return $config;
  }

  public static function getSetting($setting_name) {

    $setting = Front_model::getSetting($setting_name);
    if (is_array($setting)) {
      if (count($setting) == 1) {
        return $setting[0]->value;
      }
      if (count($setting) == 0){
        return '';
      }
      else {
        return $setting;
      }
    } else {
      return '';
    }
  }

  public static function sendEmail($obj) {
    $template = API::attrGet('template', $obj);
    $input = $obj;
    $sent = Mail::send($template, $obj, function($messageEmail) use ($input) {

              $from_email = API::attrGet('from_email', $input);
              $subject = API::attrGet('subject', $input);
              $swiftMessage = $messageEmail->getSwiftMessage();

              $headers = $swiftMessage->getHeaders();
              $headers->addTextHeader('x-mailgun-native-send', 'true');

              $type = $messageEmail->getHeaders()->get('Content-Type');
              $type->setParameter('charset', 'iso-8859-1');

              $messageEmail->from($from_email);
              $messageEmail->to($input['email'])->subject($subject);
            });

    if (!$sent) {
      return back()->withErrors('Failed send Email');
    }
  }

  public static function attrGet($key, $obj, $options = null) {

    $defaultvalue = isset($options['defaultvalue']) ? $options['defaultvalue'] : (isset($options['default_value']) ? isset($options['default_value']) : null);

    if (is_array($key)) {
      foreach ($key as $subkey) {
        if (isset($obj[$subkey])) {
          return attr_get($subkey, $obj, $options);
        }
      }
      return $defaultvalue;
    }

    $value = null;
    $value_exists = false;
    $datatype = isset($options['datatype']) ? $options['datatype'] : null;
    $return = isset($options['return']) && $options['return'] > 0 ? true : false;
    $db_unique = isset($options['db_unique']) ? $options['db_unique'] : false;
    $db_unique_except = isset($options['db_unique_except']) ? $options['db_unique_except'] : '';

    $invalid = [];
    if (!isset($obj[$key])) {
      if (isset($options['required']) && $options['required']) {
        $invalid[] = "Parameter $key required";
      } else
        $value = $defaultvalue;
    }
    else {
      $value = $obj[$key];
      $value_exists = true;
    }

    if ($value_exists) {

      if ($datatype) {

        switch ($datatype) {

          case 'int':
          case 'double':
          case 'number':
            if (!is_numeric($value))
              exc("Invalid $key datatype, number required.");
            $value = floatval($value);
            break;

          case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL))
              exc('Invalid email format');
            break;

          case 'date':
            $d = DateTime::createFromFormat('Y-m-d', $value);
            $valid = $d && $d->format('Y-m-d') === $value;
            if (!$valid)
              exc("Invalid date format for parameter $key");

            $date_format = isset($options['format']) ? $options['format'] : 'Y-m-d';
            $value = date($date_format, strtotime($value));
            break;

          case 'enum':
            $enums = isset($options['enums']) && is_array($options['enums']) ? $options['enums'] : array();
            if (!in_array($value, $enums))
              exc("Invalid $key parameter");
            break;

          case 'string':

            $not_empty = isset($options['not_empty']) ? boolval($options['not_empty']) : false;
            if ($not_empty && strlen($value) == 0)
              exc("Invalid $key parameter, $key must be filled.");

            $min_length = isset($options['min_length']) ? intval($options['min_length']) : -1;
            if ($min_length != -1 && strlen($value) < $min_length)
              exc("Invalid $key parameter, required at least $min_length character.");

            if (isset($options['length']) && is_numeric($options['length'])) {
              $length = $options['length'];
              if (strlen($value) != $options['length'])
                exc("Invalid $key parameter, length must be $length.");
            }

            if (isset($options['max_length']) && is_numeric($options['max_length'])) {
              $max_length = $options['max_length'];
              if (strlen($value) > $max_length)
                exc("Invalid $key parameter, max length is $max_length.");
            }

            $regex = isset($options['regex']) ? $options['regex'] : null;
            if ($regex && !preg_match($regex, $value))
              exc("Invalid $key parameter, type mismatch.");

            $format = isset($options['format']) ? $options['format'] : '';
            switch ($format) {
              case 'uppercase': $value = strtoupper($value);
                break;
              case 'lowercase': $value = strtolower($value);
                break;
              case 'capitalize': $value = ucwords($value);
                break;
            }

            break;
        }
      }
    }

    if ($db_unique) {

      $db_params = explode(':', $db_unique);
      $schema = isset($db_params[0]) && !empty($db_params[0]) ? $db_params[0] : '';
      $table = isset($db_params[1]) && !empty($db_params[1]) ? $db_params[1] : '';
      $column = isset($db_params[2]) && !empty($db_params[2]) ? $db_params[2] : '';

      if ($db_unique_except) {
        $exists = db_select_col("select count(*) from {$schema}.{$table} where {$column} = ? and {$column} != ?", [$value, $db_unique_except]);
      } else {
        $exists = DB::table("{$schema}.{$table}")->where($column, '=', $value)->count();
      }
      if ($exists)
        exc("Parameter $key with value $value already exists");
    }

    if (count($invalid) > 0) {
      if ($return)
        return false;
      else
        exc(implode("\n", $invalid));
    }

    return $value;
  }
  
  public static function convertMonth($value){
    $arr_month = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    return isset($arr_month[$value])?$arr_month[$value]:$value;
  }

  public static function checkDueInvoice(){
    $result = DB::table('invoice AS I')
          ->leftJoin('invoice_payment as IP', 'IP.invoice_number' , '=', 'I.invoice_number')
          ->leftJoin('negotiation as N', 'N.id','=','I.negotiation_id')  
          ->WhereNull('IP.total_payment')
          ->where('I.due_date','>', date('Y-m-d'))  
          ->where('I.status','!=','4')  
          ->select('N.car_id','I.id','N.id as negotiation_id')  
          ->get();  
   
    if(count($result)>0){
      foreach($result as $result_detail){
        $sqlValue = [
          'status' => '1'
        ];
        DB::table('car')->where('id', '=', $result_detail->car_id)
                            ->update($sqlValue); 

        DB::table('invoice')->where('id', '=', $result_detail->id)
                            ->update(array('status' => '4'));
        
        DB::table('negotiation')->where('id', '=', $result_detail->negotiation_id)
                            ->update(array('status' => '0'));

      }
    }
    
    return $result;  
    
  }
  public static function getAllCountry(){
    $countries = Front_model::getAllCountry();
    
    return $countries;
  }

  public static function newSendEmail($param = array())
  {
    $from_email = env('MAIL_USERNAME');
    $from_name = 'Optimus Car Trade';
    if (isset($param['from_email'])) {
      $from_email = $param['from_email'];
    }
    if (isset($param['from_name'])) {
      $from_name = $param['from_name'];
    }

    $data['car'] = DB::table('car')->where('id', $param['car_id'])->first();
    $data['customer'] = DB::table('customer')->where('id', $param['customer_id'])->first();
    $data['link'] = route('front.productdetail', ['id' => $param['car_id']]);

    $sent = Mail::send('emails.notification', $data, function($message) use ($data){
      $message->from('optimuscartrade@gmail.com', 'Optimus Car Trade')->subject('Car Notification');
      $message->to($data['customer']->email, $data['customer']->name);
    });

    if ($sent) {
      $response = 1;
      return json_encode($response);
    } else {
      $response = 0;
      return json_encode($response);
    }
  }
}
