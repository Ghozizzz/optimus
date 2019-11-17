@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
@section('content')

<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('admin::layouts.menu')
    </div>

    <div class="col-md-10 right-menu-div">
      <div class='wraper'>
        <div class='admin-title'>
          <h2>Proforma Invoice</h2>
        </div>
        <div>
          <hr>
        </div>
        <div class='admin-content'>
          <div width="100%">
            @include('admin::negotiation_menu')
          </div>
          
          <div class="height10"></div>
          
          <div class="row">
            <div class="title">Sending Proforma Invoice</div>
          </div>
          <div class="row">
            <div class="col-md-6 col-xs-12 background1">
              <table>
                <tr>
                  <td class='header_table'>
                    Sender's Information
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>{{$invoice->name}}</b>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href='#' onclick="openBiodata('<?php echo $session['negotiation']->customer_id ?>')">Customer Information</a>
                  </td>
                </tr>
                
              </table>
            </div>
            <div class="col-md-6 col-xs-12">
              <table>
                <tr>
                  <td>
                    <b>Item Information</b>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php 
                      $picture = json_decode($car->picture);
                    ?>
                    <img src="{{URL::to('/')}}/uploads/car/{{$picture[0]->picture}}" alt="{{$car->description}}" width='250px'>
                  </td>
                  <td valign='top'>
                    <u>{{\App\Http\Controllers\API::dateFormat($car->registration_date)}}, Car Type : </u><br>
                    {{$car->description}}<br>
                    {{$car->currency}} {{\App\Http\Controllers\API::currency_format($car->price)}}<br>
                  </td>
                </tr>
              </table>              
            </div>
          </div>
          <div class='row'>
            <div class="col-md-12 color-red">
              <b>*Required Fields</b>
            </div>
          </div>
          
          @if(session()->has('message') && session()->get('message') !== '')
            <div class='alert alert-info'>
          <?php echo session()->get('message'); ?>
            </div>
          @endif
          
          <form method="post" action="{{route('admin.saveProformaInvoice')}}" onsubmit="return formValidation()">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" /> 
          <input type="hidden" name="id" value="{{ $session['proforma_invoice']->id }}" id="proforma_invoice_id" /> 
          <div class='row'>
            <div class="col-md-12 proformainvoice-header">
              Company Information
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1 proformainvoice-content" align='right'>
              Company Information *
            </div>
            <div class="col-md-8 proformainvoice-content">
              Optimus Auto Trading Pte Ltd
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1 proformainvoice-content" align='right'>
              Signature
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-12 proformainvoice-header">
              Buyer Information
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-12 proformainvoice-content">
              You can issue proforma invoice withoutfilling buyer's information
            </div>
          </div>
          
          <?php
          $company_name = $session['proforma_invoice']->company_name != null ? $session['proforma_invoice']->company_name : '';
          $address = $session['proforma_invoice']->address != null ? $session['proforma_invoice']->address : '';
          $city = $session['proforma_invoice']->city != null ? $session['proforma_invoice']->city : '';
          $country_code = $session['proforma_invoice']->country_code != null ? $session['proforma_invoice']->country_code : '';
          $telephone = $session['proforma_invoice']->telephone != null ? $session['proforma_invoice']->telephone : '';
          $fax = $session['proforma_invoice']->fax != null ? $session['proforma_invoice']->fax : '';
          $car_d = explode(',',$car->dimension);
          $p = $l = $t = '';
          if(count($car_d) == 3){
            $p = $car_d[0] !== '' ? $car_d[0] : '';
            $l = $car_d[1] !== '' ? $car_d[1] : '';
            $t = $car_d[2] !== '' ? $car_d[2] : '';
          }
          $car_width = $session['proforma_invoice']->car_width != null ? $session['proforma_invoice']->car_width : $l;
          $car_height = $session['proforma_invoice']->car_height != null ? $session['proforma_invoice']->car_height : $t;
          $car_length = $session['proforma_invoice']->car_length != null ? $session['proforma_invoice']->car_length : $p;
          $detail = $session['proforma_invoice']->detail != null ? json_decode($session['proforma_invoice']->detail) : '';
          $sales_agreement = $session['proforma_invoice']->sales_agreement != null ? $session['proforma_invoice']->sales_agreement : '';
          $incoterm = $session['proforma_invoice']->incoterm != null ? $session['proforma_invoice']->incoterm : '';
          $status = $session['proforma_invoice']->status != null ? json_decode($session['proforma_invoice']->status) : 1;
          $inspection_value = $session['proforma_invoice']->inspection_value != null ? $session['proforma_invoice']->inspection_value : '';
          $payment_due = $session['proforma_invoice']->payment_due != null ? $session['proforma_invoice']->payment_due : '';
          $negotiation_status = $session['negotiation']->status != null ? $session['negotiation']->status : '';
          $current_url = Request::fullUrl();
          $explode = explode('?', $current_url);
          $web_url = $explode[0];
  
          if($negotiation_status > 2){
            $readonly = 'readonly="true"';
            $disabled = 'disabled="true"';
          }else{
            $readonly = '';
            $disabled = '';
          }

          ?>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Company Name
            </div>
            <div class="col-md-6">
              <input type="text" class='form-control input-style' name='company_name' size="40%" value='{{$company_name}}' {{$readonly}}>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Street Address
            </div>
            <div class="col-md-6">
              <textarea name='address' class='form-control input-style' cols='40' {{$readonly}}>{{$address}}</textarea>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              City
            </div>
            <div class="col-md-6">
              <input type="text" class='form-control input-style' name='city' size="40%" value='{{$city}}' {{$readonly}}>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Country
            </div>
            <div class="col-md-6">
              <select class='input-style' name='country' id="country"  {{$disabled}}>
                <option value=''>-- select country --</option>
                <?php
                  foreach($countries as $country){?>
                    @if($country->country_code === $country_code)                    
                      <option value='{{$country->country_code}}' selected>{{$country->country_name}}</option>
                    @else
                      <option value='{{$country->country_code}}'>{{$country->country_name}}</option>
                    @endif
                <?php }?>
              </select>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Telephone
            </div>
            <div class="col-md-6">
              <input type="text" class='form-control input-style' name='telephone' size="40%" value='{{$telephone}}' {{$readonly}}>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Fax
            </div>
            <div class="col-md-6">
              <input type="text" class='form-control input-style' name='fax' size="40%" value='{{$fax}}' {{$readonly}}>
            </div>
          </div>
          
          
          <div class='row'>
            <div class="col-md-12 proformainvoice-header">
              Item Information
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Model
            </div>
            <div class="col-md-6">
              {{ $car->manufacture_year .' '. $car->model .' '. $car->make }}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Registration Date
            </div>
            <div class="col-md-6">
              {{ \App\Http\Controllers\API::dateFormat($car->registration_date) }}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Model Code
            </div>
            <div class="col-md-6">
              {{$car->model}}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Vehicle Number
            </div>
            <div class="col-md-6">
              {{$car->plate_number}}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Exterior Colour
            </div>
            <div class="col-md-6">
              {{ $car->exterior_color }}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Mileage
            </div>
            <div class="col-md-6">
              {{ \App\Http\Controllers\API::currency_format($car->distance) }} km
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Engine capacity
            </div>
            <div class="col-md-6">
              {{\App\Http\Controllers\API::currency_format($car->engine)}} CC
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Transmision
            </div>
            <div class="col-md-6">
              {{$car->car_transmission_description}}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Fuel
            </div>
            <div class="col-md-6">
              {{$car->fuel}}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Chassis number
            </div>
            <div class="col-md-6">
              {{$car->vin}}
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Steering
            </div>
            <div class="col-md-6">
              @if($car->steering == '1')
                {{'left'}}
              @else
                {{'right'}}
              @endif
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Dimension
            </div>
            <div class="col-md-2">
              Length
              <input type="text" class='form-control input-style' name='length' size="5" value='{{$car_length}}' readonly>
              Cm
            </div>
            <div class="col-md-2">
              Width
              <input type="text" class='form-control input-style' name='width' size="5" value='{{$car_width}}' readonly>
              Cm
            </div>
            <div class="col-md-2">
              Height
              <input type="text" class='form-control input-style' name='height' size="5" value='{{$car_height}}' readonly>
              Cm
            </div>
          </div>
          
          
          <div class='row'>
            <div class="col-md-12 proformainvoice-header">
              Delivery Information
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Port of Departure *
            </div>
            <div class="col-md-3">
              <select id="departure_country" class="form-control" name="departure_country" {{$disabled}}>
                  @foreach($logistic['departure_country'] as $country_detail)
                  @if($country_detail->country_code === 'SG' )
                    <option value="{{$country_detail->country_code}}" selected>{{$country_detail->country_name}}</option>
                  @else
                  @endif
                  @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="departure_port" name="departure_port" class="form-control" {{$disabled}}>
                  <option value="" selected="selected">Select Nearest Port</option>
                  @foreach($logistic['departure_port'] as $port_detail)
                  @if($port_detail->id == '5763' )
                    <option value="{{$port_detail->id}}" selected>{{$port_detail->port_name}}</option>
                  @else
                    <option value="{{$port_detail->id}}">{{$port_detail->port_name}}</option>
                  @endif
                  @endforeach
              </select>
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Final Destination Country City *
            </div>
            <div class="col-md-3">
              <select id="destination_country" class="form-control" name="destination_country" {{$disabled}}>
                <option value="" selected="selected">Select Country</option>
                  @foreach($logistic['destination_country'] as $country_detail)
                  @if(isset($session['destination_port']->country_code) && $country_detail->country_code == $session['destination_port']->country_code )
                    <option value="{{$country_detail->country_code}}" selected>{{$country_detail->country_name}}</option>
                  @else
                    <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                  @endif
                  
                  @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="destination_port" name="destination_port" class="form-control" {{$disabled}}>
                  <option value="" selected="selected">Select Nearest Port</option>
              </select>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Preship inspection *
            </div>
            <div class="col-md-3">
              <?php
              if($session['proforma_invoice'] !== null){
                $inspection = $session['proforma_invoice']->inspection;
              }else{
                $inspection = $session['negotiation']->inspection;
              }
              
              $opt1 = $opt2 = '';
              if($inspection == 0){
                $opt1 = 'selected';
              }else{
                $opt2 = 'selected';
              }
              
              ?>
              <select id="inspection" class="form-control" name="inspection" {{$disabled}}>
                <option value="0" {{$opt1}}>None</option>
                <option value="1" {{$opt2}}>Yes</option>
              </select>              
            </div>
            <div class="col-md-3">
              <input type='text' name='inspection_value' id="inspection_value" {{$readonly}} class='form-control input-style' style="display:none" value="{{$inspection_value}}">
            </div>
          </div>
            
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Select currency
            </div>
            <div class="col-md-8">
              Please select the currency(JYP/USD) which you wish to receive the payment from Carview.<br>
              <?php 
              $usd_selected = $jyp_selected = $usd_checked = $jyp_checked = '';
              if($session['proforma_invoice']->currency === 'USD'){
                $curency = 'USD';
                $usd_checked = 'checked';
                $usd_selected = 'selected';
              }elseif($session['proforma_invoice']->currency === 'JYP'){
                $currency = 'JYP';
                $jyp_checked = 'checked';
                $jyp_selected = 'selected';
              }else{
                $curency = 'USD';
                $usd_checked = 'checked';
                $usd_selected = 'selected';
              }

              ?>
              <!--<input type='radio' name='payment_currency' value="JYP" <?php echo $jyp_checked ?>>Receive in JYP-->
              <input type='radio' name='payment_currency' value="USD" <?php echo $usd_checked ?>>Receive in USD<br>
              <span class='color-red'><small>*70 USD will be charged if you choose to receive in USD.(70 USD/transfer)</small></span>
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Incoterms *
            </div>
            <div class="col-md-4"> 
              <?php
                $fob = $cif = $cnf = '';
                if($incoterm == 'FOB'){
                  $fob = 'selected';
                }elseif($incoterm == 'CIF'){
                  $cif = 'selected';
                }elseif($incoterm == 'C&F'){
                  $cnf = 'selected';
                }
                ?>
              <select name='incoterms' class="form-control" id='incoterms' {{$disabled}}>
                <option value=''>Please Select</option>
                <option value='FOB' {{$fob}}>FOB</option>
                <option value='CIF' {{$cif}}>CIF</option>
                <option value='C&F' {{$cnf}}>C&F</option>
              </select>
              <small>Please make sure to check the incoterms with the buyer</small>
            </div>
          </div>
            
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Total Amount *
            </div>
            <div class="col-md-2">              
              <select name='currency' class="form-control" id='currency' {{$disabled}}>
                <option value='USD' <?php echo $usd_selected ?>>USD</option>
                <!--<option value='JYP' <?php echo $jyp_selected ?>>JYP</option>-->
              </select>
              <?php
              if($session['proforma_invoice']->total_amount === NULL){
                $total_amount = $session['negotiation']->price;
              }else{
                $total_amount = $session['proforma_invoice']->total_amount;
              }
              ?>
            </div>
            <div class="col-md-2">
              <input type='text' class='form-control' name='total_amount' id='total_amount' value='<?php echo $total_amount?>' {{$readonly}}>
            </div>
          </div>  
          
          <div class='row'>
            <div class="col-md-4 background1" align='right' style='vertical-align: middle'>
              Details
            </div>
            <div class="col-md-4">  
              <small>Item name<br>(Freight / pre-ship inspection Fees Etc)</small>
              <input type='text' name='item_name[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[0]->item_name)?$detail[0]->item_name:''}}'><br>
              <input type='text' name='item_name[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[1]->item_name)?$detail[1]->item_name:''}}'><br>
              <input type='text' name='item_name[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[2]->item_name)?$detail[2]->item_name:''}}'><br>
              <input type='text' name='item_name[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[3]->item_name)?$detail[3]->item_name:''}}'><br>
              <input type='text' name='item_name[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[4]->item_name)?$detail[4]->item_name:''}}'><br>
            </div>
            
            <div class="col-md-4">      
              <small>Price<br><br></small>
              <input type='text' name='item_price[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[0]->item_price)?$detail[0]->item_price:''}}'><br>
              <input type='text' name='item_price[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[1]->item_price)?$detail[1]->item_price:''}}'><br>
              <input type='text' name='item_price[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[2]->item_price)?$detail[2]->item_price:''}}'><br>
              <input type='text' name='item_price[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[3]->item_price)?$detail[3]->item_price:''}}'><br>
              <input type='text' name='item_price[]' {{$readonly}} class='form-control input-style' value='{{isset($detail[4]->item_price)?$detail[4]->item_price:''}}'><br>
            </div>
            
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Buyer's Payment Due Date *
            </div>
            <div class="col-md-8">
              <div class='row'>
                <div class='col-md-4'>
                  <select name='due_date' class="form-control" id='incoterms' width='40%'>
                    @if($payment_due !== '' && $status > 1)
                      <option value='{{$payment_due}}'>{{$payment_due}}</option>
                    @else
                      <option value=''>Please Select</option>
                      @for($i = 0; $i<=30 ; $i ++)
                        @if(date("Y-m-d", strtotime("+".$i." days")) == $payment_due)
                          <option value='{{date("Y-m-d", strtotime("+".$i." days"))}}' selected>{{date("Y-m-d", strtotime("+".$i." days"))}}</option>
                        @else
                          <option value='{{date("Y-m-d", strtotime("+".$i." days"))}}'>{{date("Y-m-d", strtotime("+".$i." days"))}}</option>
                        @endif
                      @endfor
                    @endif
                  </select>
                </div>
                <div class='col-md-4'>
                  (listed on invoice)
                </div>
              </div>
              <small>*Vehicle will be "Reserved" status and not be shown on the siteuntil 3 days after the payment due date.</small>
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Sales Agreement
            </div>
            <div class="col-md-8">
              <textarea class='input-style form-control' name="sales_agreement" {{$readonly}}>{!! $sales_agreement !!}</textarea>
            </div>
          </div>
          
          <div class='row'>
            <div class="col-md-12" align="center">
              @if($session['negotiation']->status < 5)
              <input type="submit" value="Save" class="btn btn-danger">
              @endif
            </div>
          </div>
          </form>
          
          <div class='row'>
            <div class="col-md-12" align="center">
              <a href="{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}"><button class="btn btn-danger" id='back-btn'>Back</button></a>
            @if($session['proforma_invoice']->status > 1)
            
              @if($session['proforma_invoice']->approval != 1)
                <input type="submit" value="Reject" class="btn btn-info edit-proforma" id='edit-proforma'>
              @endif
              <button class="btn btn-danger" id='pdf-btn'>PDF</button>
              @if(count($proformance_invoice_log)>1)
              <button class="btn btn-danger" id='compare-btn'>Compare</button>
              @endif
              @if(!isset($session['invoice']) && $session['proforma_invoice']->approval == 0 )
                <input type="submit" value="Approve" class="btn btn-danger create-invoice" id='create-invoice'>
              @endif
            
            @endif
            </div>
          </div>
        </div>      
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->


<div class="modal fade" id="compareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Proformance Invoice Comparison</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="compare-container container">
          <div class="row">
<!--            <div class="col-md-2">
              <table>  
              @if(is_array($proformance_invoice_log) && count($proformance_invoice_log)>1)
                @foreach($proformance_invoice_log as $log)


                @endforeach
              @endif
              </table>
            </div>-->
            
            @if(isset($proformance_invoice_log[1]) && isset($proformance_invoice_log[0]))
              <?php 
              $data_log = json_decode($proformance_invoice_log[1]->content);
              $data_log_2 = json_decode($proformance_invoice_log[0]->content);
              
              $color = '#FFFFDD';
              
              if($data_log->company_name !== $data_log_2->company_name){
                $company_row = 'style="background:'.$color.'"';
              }else{
                $company_row = '';
              }
              if($data_log->address !== $data_log_2->address){
                $address_row = 'style="background:'.$color.'"';
              }else{
                $address_row = '';
              }
              if($data_log->city !== $data_log_2->city){
                $city_row = 'style="background:'.$color.'"';
              }else{
                $city_row = '';
              }
              if($data_log->country_code !== $data_log_2->country_code){
                $country_row = 'style="background:'.$color.'"';
              }else{
                $country_row = '';
              }
              
              if($data_log->telephone !== $data_log_2->telephone){
                $telephone_row = 'style="background:'.$color.'"';
              }else{
                $telephone_row = '';
              }
              if($data_log->fax !== $data_log_2->fax){
                $fax_row = 'style="background:'.$color.'"';
              }else{
                $fax_row = '';
              }
              
              if($data_log->car_width !== $data_log_2->car_width){
                $width_row = 'style="background:'.$color.'"';
              }else{
                $width_row = '';
              }
              if($data_log->car_length !== $data_log_2->car_length){
                $length_row = 'style="background:'.$color.'"';
              }else{
                $length_row = '';
              }
              if($data_log->car_height !== $data_log_2->car_height){
                $height_row = 'style="background:'.$color.'"';
              }else{
                $height_row = '';
              }
              if($data_log->car_height !== $data_log_2->car_height){
                $dpt_country_row = 'style="background:'.$color.'"';
              }else{
                $dpt_country_row = '';
              }
              if($data_log->port_departure !== $data_log_2->port_departure){
                $dpt_country_row = 'style="background:'.$color.'"';
                $dpt_port_row = 'style="background:'.$color.'"';
              }else{
                $dpt_country_row = '';
                $dpt_port_row = '';
              }
              if($data_log->port_destination !== $data_log_2->port_destination){
                $dst_country_row = 'style="background:'.$color.'"';
                $dst_port_row = 'style="background:'.$color.'"';
              }else{
                $dst_country_row = '';
                $dst_port_row = '';
              }
              if($data_log->inspection !== $data_log_2->inspection){
                $inspection_row = 'style="background:'.$color.'"';
              }else{
                $inspection_row = '';
              }
              if($data_log->inspection_value !== $data_log_2->inspection_value){
                $inspection_value_row = 'style="background:'.$color.'"';
              }else{
                $inspection_value_row = '';
              }
              if($data_log->incoterm !== $data_log_2->incoterm){
                $incoterm_row = 'style="background:'.$color.'"';
              }else{
                $incoterm_row = '';
              }
              if($data_log->total_amount !== $data_log_2->total_amount){
                $total_amount_row = 'style="background:'.$color.'"';
              }else{
                $total_amount_row = '';
              }
              if($data_log->detail !== $data_log_2->detail){
                $detail_row = 'style="background:'.$color.'"';
              }else{
                $detail_row = '';
              }
              if($data_log->payment_due !== $data_log_2->payment_due){
                $payment_due_row = 'style="background:'.$color.'"';
              }else{
                $payment_due_row = '';
              }
              
              if($data_log->detail !== ''){
                $details = json_decode($data_log->detail);
              }else{
                $details = [];
              }
              if($data_log_2->detail !== ''){
                $details_2 = json_decode($data_log_2->detail);
              }else{
                $details_2 = [];
              }

              ?>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">Updated by : {{$proformance_invoice_log[1]->createdby}}</div>
                </div>
                <div class="row">
                  <div class="col-md-12"><label><b>Buyer Information</b></label></div>
                </div>
                <div class="row" <?php echo $company_row ?>>
                  <div class="col-md-4"><label>Company name</label></div>
                  <div class="col-md-8">
                    <input name="last_company_name" class="form-control" value="{{$data_log->company_name}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $address_row ?>>
                  <div class="col-md-4"><label>Street Address</label></div>
                  <div class="col-md-8">
                    <textarea name="last_address" class="form-control"  readonly="true">{{$data_log->address}}</textarea>
                  </div>
                </div>
                <div class="row" <?php echo $city_row ?>>
                  <div class="col-md-4"><label>City</label></div>
                  <div class="col-md-8">
                    <input name="last_city" class="form-control" value="{{$data_log->city}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $country_row ?>>
                  <div class="col-md-4"><label>Country</label></div>
                  <div class="col-md-8">
                    <input name="last_country" class="form-control" value="{{$data_log->country_code}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $telephone_row ?>>
                  <div class="col-md-4"><label>Telephone</label></div>
                  <div class="col-md-8">
                    <input name="last_telephone" class="form-control" value="{{$data_log->telephone}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $fax_row ?>>
                  <div class="col-md-4"><label>Fax</label></div>
                  <div class="col-md-8">
                    <input name="last_fax" class="form-control" value="{{$data_log->fax}}" readonly="true">
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12"><label><b>Item Information</b></label></div>
                </div>
                
                <div class="row" <?php echo $width_row ?>>
                  <div class="col-md-4"><label>Width</label></div>
                  <div class="col-md-7">
                    <input name="last_width" class="form-control" value="{{$data_log->car_width}}" readonly="true">
                  </div>
                  <div class="col-md-1"><label>cm</label></div>
                </div>
                <div class="row" <?php echo $length_row ?>>
                  <div class="col-md-4"><label>Length</label></div>
                  <div class="col-md-7">
                    <input name="last_length" class="form-control" value="{{$data_log->car_length}}" readonly="true">
                  </div>
                  <div class="col-md-1"><label>cm</label></div>
                </div>
                <div class="row" <?php echo $height_row ?>>
                  <div class="col-md-4"><label>Height</label></div>
                  <div class="col-md-7">
                    <input name="last_height" class="form-control" value="{{$data_log->car_height}}" readonly="true">
                  </div>
                  <div class="col-md-1"><label>cm</label></div>
                </div>
                
                <div class="row">
                  <div class="col-md-12"><label><b>Delivery Information</b></label></div>
                </div>
                
                <div class="row" <?php echo $dpt_country_row ?>>
                  <div class="col-md-4"><label>Departure Country</label></div>
                  <div class="col-md-8">
                    <input name="last_departure_country" class="form-control" value="{{$logistic['departure_port'][$data_log->port_departure]->country_name}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $dpt_port_row ?>>
                  <div class="col-md-4"><label>Port of Departure</label></div>
                  <div class="col-md-8">
                    <input name="last_departure_port" class="form-control" value="{{$logistic['departure_port'][$data_log->port_departure]->port_name}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $dst_country_row ?>>
                  <div class="col-md-4"><label>Destination Country</label></div>
                  <div class="col-md-8">
                    <input name="last_destination_country" class="form-control" value="{{$logistic['destination_port'][$data_log->port_destination]->country_name}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $dst_port_row ?>>
                  <div class="col-md-4"><label>Port of Destination</label></div>
                  <div class="col-md-8">
                    <input name="last_destination_port" class="form-control" value="{{$logistic['destination_port'][$data_log->port_destination]->port_name}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $inspection_row ?>>
                  <div class="col-md-4"><label>Inspection</label></div>
                  <div class="col-md-8">
                    <input name="last_inspection" class="form-control" value="{{$data_log->inspection == 0? 'No' : 'Yes'}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $inspection_value_row ?>>
                  <div class="col-md-4"><label>Inspection value</label></div>
                  <div class="col-md-8">
                    <input name="last_inspection_value" class="form-control" value="{{$data_log->inspection_value}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $incoterm_row ?>>
                  <div class="col-md-4"><label>Incoterm</label></div>
                  <div class="col-md-8">
                    <input name="last_incoterm" class="form-control" value="{{$data_log->incoterm}}" readonly="true">                    
                  </div>
                </div>
                <div class="row" <?php echo $total_amount_row ?>>
                  <div class="col-md-4"><label>Total amount</label></div>
                  <div class="col-md-8">
                    <input name="last_total_amount" class="form-control" value="{{$data_log->total_amount}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $payment_due_row ?>>
                  <div class="col-md-4"><label>Payment Due</label></div>
                  <div class="col-md-8">
                    <input name="last_payment_due" class="form-control" value="{{$data_log->payment_due}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $detail_row ?>>
                  <div class="col-md-12"><label>Detail</label></div>
                </div>
                <div class="row" <?php echo $detail_row ?>>
                  @if(count($details)>0)
                  @foreach($details as $detail)
                  <div class="col-md-6">
                    <input name="last_detail_name" class="form-control" value="{{$detail->item_name}}" readonly="true">                    
                  </div>
                  <div class="col-md-6">
                    <input name="last_detail_price" class="form-control" value="{{$detail->item_price}}" readonly="true">                    
                  </div>
                  @endforeach
                  @endif
                </div>
                
                
                
              </div>
            
            
            
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">Updated by : {{$proformance_invoice_log[0]->createdby}}</div>
                </div>
                <div class="row">
                  <div class="col-md-12"><label><b>Buyer Information</b></label></div>
                </div>
                <div class="row" <?php echo $company_row ?>>
                  <div class="col-md-4"><label>Company name</label></div>
                  <div class="col-md-8">
                    <input name="compare_company_name" class="form-control" value="{{$data_log_2->company_name}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $address_row ?>>
                  <div class="col-md-4"><label>Street Address</label></div>
                  <div class="col-md-8">
                    <textarea name="compare_address" class="form-control" readonly="true">{{$data_log_2->address}}</textarea>
                  </div>
                </div>
                <div class="row" <?php echo $city_row ?>>
                  <div class="col-md-4"><label>City</label></div>
                  <div class="col-md-8">
                    <input name="compare_city" class="form-control" value="{{$data_log_2->city}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $country_row ?>>
                  <div class="col-md-4"><label>Country</label></div>
                  <div class="col-md-8">
                    <input name="compare_country" class="form-control" value="{{$data_log_2->country_code}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $telephone_row ?>>
                  <div class="col-md-4"><label>Telephone</label></div>
                  <div class="col-md-8">
                    <input name="compare_telephone" class="form-control" value="{{$data_log_2->telephone}}" readonly="true">
                  </div>
                </div>
                <div class="row" <?php echo $fax_row ?>>
                  <div class="col-md-4"><label>Fax</label></div>
                  <div class="col-md-8">
                    <input name="compare_fax" class="form-control" value="{{$data_log_2->fax}}" readonly="true">
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12"><label><b>Item Information</b></label></div>
                </div>
                
                <div class="row" <?php echo $width_row ?>>
                  <div class="col-md-4"><label>Width</label></div>
                  <div class="col-md-7">
                    <input name="compare_width" class="form-control" value="{{$data_log_2->car_width}}" readonly="true">
                  </div>
                  <div class="col-md-1"><label>cm</label></div>
                </div>
                <div class="row" <?php echo $length_row ?>>
                  <div class="col-md-4"><label>Length</label></div>
                  <div class="col-md-7">
                    <input name="compare_length" class="form-control" value="{{$data_log_2->car_length}}" readonly="true">
                  </div>
                  <div class="col-md-1"><label>cm</label></div>
                </div>
                <div class="row" <?php echo $height_row ?>>
                  <div class="col-md-4"><label>Height</label></div>
                  <div class="col-md-7">
                    <input name="compare_height" class="form-control" value="{{$data_log_2->car_height}}" readonly="true">
                  </div>
                  <div class="col-md-1"><label>cm</label></div>
                </div>
                
                
                <div class="row">
                  <div class="col-md-12"><label><b>Delivery Information</b></label></div>
                </div>
                
                <div class="row" <?php echo $dpt_country_row ?>>
                  <div class="col-md-4"><label>Departure Country</label></div>
                  <div class="col-md-8">
                    <input name="compare_departure_country" class="form-control" value="{{$logistic['departure_port'][$data_log_2->port_departure]->country_name}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $dpt_port_row ?>>
                  <div class="col-md-4"><label>Port of Departure</label></div>
                  <div class="col-md-8">
                    <input name="compare_port_departure" class="form-control" value="{{isset($data_log_2->port_departure) && $data_log_2->port_departure!== '' ? $logistic['departure_port'][$data_log_2->port_departure]->port_name : ''}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $dst_country_row ?>>
                  <div class="col-md-4"><label>Destination Country</label></div>
                  <div class="col-md-8">
                    <input name="compare_destination_country" class="form-control" value="{{isset($data_log_2->port_destination) && $data_log_2->port_destination !== '' ? $logistic['destination_port'][$data_log_2->port_destination]->country_name : ''}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $dst_port_row ?>>
                  <div class="col-md-4"><label>Port of Destination</label></div>
                  <div class="col-md-8">
                    <input name="compare_destination_port" class="form-control" value="{{isset($data_log_2->port_destination) && $data_log_2->port_destination !== '' ? $logistic['destination_port'][$data_log_2->port_destination]->port_name : ''}}" readonly="true">
                    
                  </div>
                </div>
                
                <div class="row" <?php echo $inspection_row ?>>
                  <div class="col-md-4"><label>Inspection</label></div>
                  <div class="col-md-8">
                    <input name="compare_inspection" class="form-control" value="{{$data_log_2->inspection == 0? 'No' : 'Yes'}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $inspection_value_row ?>>
                  <div class="col-md-4"><label>Inspection value</label></div>
                  <div class="col-md-8">
                    <input name="compare_inspection_value" class="form-control" value="{{$data_log_2->inspection_value}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $incoterm_row ?>>
                  <div class="col-md-4"><label>Incoterm</label></div>
                  <div class="col-md-8">
                    <input name="compare_incoterm" class="form-control" value="{{$data_log_2->incoterm}}" readonly="true">                    
                  </div>
                </div>
                <div class="row" <?php echo $total_amount_row ?>>
                  <div class="col-md-4"><label>Total amount</label></div>
                  <div class="col-md-8">
                    <input name="compare_total_amount" class="form-control" value="{{$data_log_2->total_amount}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $payment_due_row ?>>
                  <div class="col-md-4"><label>Payment Due</label></div>
                  <div class="col-md-8">
                    <input name="compare_payment_due" class="form-control" value="{{$data_log_2->payment_due}}" readonly="true">                    
                  </div>
                </div>
                
                <div class="row" <?php echo $detail_row ?>>
                  <div class="col-md-12"><label>Detail</label></div>
                </div>
                <div class="row" <?php echo $detail_row ?>>
                  @if(count($details_2)>0)
                  @foreach($details_2 as $detail_2)
                  <div class="col-md-6">
                    <input name="compare_detail_name" class="form-control" value="{{$detail_2->item_name}}" readonly="true">                    
                  </div>
                  <div class="col-md-6">
                    <input name="compare_detail_price" class="form-control" value="{{$detail_2->item_price}}" readonly="true">                    
                  </div>
                  @endforeach
                  @endif
                </div>
                
                
                
              </div>
            @endif
          </div>          
        </div>
      </div>
      
      <div class="modal-footer">
        @if($session['proforma_invoice']->approval != 1)
        <button type="button" class="btn btn-danger edit-proforma">
          Reject
        </button>
        @endif
        @if(!isset($session['invoice']) && $session['proforma_invoice']->approval == 0 )
        <button type="button" class="btn btn-danger create-invoice">
          Approve
        </button>
        @endif
      </div>
      
    </div>
  </div>
</div>



<div class="modal fade" id="biodataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="biodata-container container">
          <div class="row">
            <div class="col-md-5">
              <div class="row">
                <div class="col-md-4">
                  <label>Name</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-name"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Email</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-email"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Phone</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-phone"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Birthday</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-birthday"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Address</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-address"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12" style="margin-top:100px"><hr></div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label>Total Negotiation: </label>
                  <label id="total_negotiation"></label>
                </div>
              </div>
              <div class="row"
                <div class="col-md-12" id="total_negotiation_div">
                  
                </div>
              </div>


            <div class="col-md-7">
              <div class="row">
                <div class="col-md-12">
                  <label><b>Last 10 History</b></label>
                </div>
              </div>
              <div class="row">
                <!--<div class="col-md-12" id="tbl-history">-->
                <div class="col-md-12">
                  
                  <div id="cardetailtabs" class="historytabs">             
                    <ul class="nav nav-pills">
                      <li><a data-toggle="pill" class="active" href="#home">Negotiation</a></li>
                      <li><a data-toggle="pill" href="#menu1">Invoice</a></li>
<!--                      <li><a data-toggle="pill" href="#menu2">cancel</a></li>-->
                    </ul>

                    <div class="tab-content">
                      <div id="home" class="tab-pane fade in active show">
                        <div class="col-md-12" id="tbl-negotiation-history">
                          
                        </div>
                      </div>
                      <div id="menu1" class="tab-pane fade">
                        <div class="col-md-12" id="tbl-invoice-history">
                          
                        </div>
                      </div>
<!--                      <div id="menu2" class="tab-pane fade">
                        <div class="col-md-12" id="tbl-cancel-history">
                          
                        </div>
                      </div>-->
                    </div>
                  </div>
                  
                  
                </div>
              </div>
            </div>  
          </div>          
        </div>
      </div>
    </div>
  </div>
</div>
  
@stop


@section('script')
<script>  
  var web_url = '{{$web_url}}';
  var base_url = "{{ URL::to('/') }}";
  var image_asset_url = "{{URL::to('/').'/customer/car/'}}";
  $("#country").select2();
  $("#departure_country").select2();
  $("#departure_port").select2();
  $("#destination_country").select2();
  $("#destination_port").select2();
  
  var status_proforma_invoice = "{{$status}}";
  var status_negotiation = "{{$session['negotiation']->status}}";
  var port_id = "{{isset($session['destination_port']->id)?$session['destination_port']->id:''}}";
  $("#pdf-btn").on('click',function(){
    window.open('{{route("report.proformaInvoice")}}');
  })
  $(".edit-proforma").on('click',function(){
    $("#compareModal").modal('hide');
    window.location="{{route('admin.editproformainvoice',['id'=>$session['proforma_invoice']->id])}}";
  })
  $(".create-invoice").on('click',function(){
    $("#compareModal").modal('hide');
    var requestData = {
      id: $("#proforma_invoice_id").val(),
      user_type : 'user',
      negotiation_id : "{{$session['negotiation']->id}}",
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('admin.approveProformaInvoice')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        window.location="{{route('admin.createInvoice')}}";
      }
    });
  })
  function formValidation(){
    if($("#incoterms").val() == ''){
      alert('incoterm required');
      return false;
    }
    
    if($("#total_amount").val() == ''){
      alert('total amount required');
      return false;
    }
    
    if($("#due_date").val() == ''){
      alert('due date required');
      return false;
    }
    
    var confirmation = confirm('Are you sure want to submit this proforma invoice?')
    
    if(confirmation === true){
      return true;
    }else{
      return false;
    }
  }
  
  $("#compare-btn").on('click',function(){
    $("#compareModal").modal('show');
  })
  
  function getDestinationPort(){
  if($("#destination_country").val()=== ''){
    return false;
  }  
    var requestData = {
      destination_country: $("#destination_country").val(),
      city: '',
    };

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('front.getDestinationPort')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        
        var html_template = '';
        for(n in result){
          if(result[n]['price'] === null){
            var price = 0;
          }else{
            var price = result[n]['price'];
          }
          if(port_id == result[n]['id']){
            html_template += '<option value="'+ result[n]['id'] +'" data-price = "'+ price +'" selected>'+result[n]['port_name']+'</option>';
          }else{
            html_template += '<option value="'+ result[n]['id'] +'" data-price = "'+ price +'" >'+result[n]['port_name']+'</option>';
          }
        }
        $("#destination_port").html(html_template);
        if(status_negotiation > 2){
          $("#destination_port").attr('disabled',true);
        }else{
          $("#destination_port").attr('disabled',false);
        }
      }
    });
  }
  

  getDestinationPort();
  
  
$("#incoterms").on('change',function(){
  if($("#incoterms").val() == 'FOB'){
    $("#inspection").val(0);
    $("#inspection").prop('readonly',true);
    $("#inspection_value").hide();
  }else{
    $("#inspection").prop('readonly',false);
  }
})  
  
function openBiodata(id){
  $.ajax({  
	    url: "{{ URL::to('/') }}/admin/customer/view/"+id,  
	    type: "GET",
	    processData: false, 
	    contentType: false,
	    success: function (response) {
	    	if (response.error) {
	    		alert(response.error);
	    	}
        var data = response.data;
        $("#cust-name").text(data.name);
        $("#cust-email").text(data.email);
        $("#cust-phone").text(data.phone);
        if(data.birthday !== ''){
          var bod = data.birthday.split('-');
          var month = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
          $("#cust-birthday").text(bod[2]+' '+ month[bod[1]] +' '+bod[0]);
        }
        $("#cust-address").text(data.address);
	    	$("#biodataModal").modal('show');        
        
        var total_history = response.total_history;
        var total_history_html = '';

        var total = 0;
        if(total_history != null){
          total_history_html += '<table>';
          total_history_html += '<tr><th>Status</th><th>Total</th></tr>';
          
           for(var x in total_history){
             total_history_html += '<tr>';
             total_history_html += '<td>'+ total_history[x].description +'</td>';
             total_history_html += '<td align="center">'+ total_history[x].total +'</td>';
             total_history_html += '</tr>';
             
             total += parseFloat(total_history[x].total);
           }
          total_history_html += '</table>'; 
        }
        $("#total_negotiation").text(total);
//        $("#total_negotiation_div").html(total_history_html);
        
        var history = response.negotiation_history;
        var history_html = '';
        if(history != null){
          history_html += '<table width="100%" style="text-align:center" width="100%" border="1"><tr><th>Car</th><th>Model</td><th>Negotiation Date</th><th>Status</th></tr>';

          for(var x in history){
            var img = history[x].picture;
            if(img !== ''){
              img = JSON.parse(img);

              if(img.length >0){
                var car_img = '<img src="'+base_url+'/uploads/car/'+ img[0]['picture'] +'" width="80px">';
              }else{
                var car_img = '';
              }              
            }else{
              var car_img = '';
            }
            var createdon = history[x].createdon;
            var negotiation_date = '';
            if(createdon !== ''){
              var date = new Date(createdon);
              
              negotiation_date = date.getDate() + ' ' + (month[date.getMonth() + 1]) + ' ' +  date.getFullYear();
            }
            history_html += '<tr>';
            history_html += '<td>'+ car_img +'</td>';
            history_html += '<td>'+ history[x].model +'</td>';         
            history_html += '<td>'+ negotiation_date +'</td>';         
            history_html += '<td>'+ history[x].status_description +'</td>';         
            history_html += '</tr>';
          }
          history_html += '</table>';
        }
      $("#tbl-negotiation-history").html(history_html);
      
      
        var history = response.invoice_history;
        var history_html = '';
        if(history != null){
          history_html += '<table width="100%" style="text-align:center" width="100%" border="1"><tr><th>Car</th><th>Model</td><th>Negotiation Date</th><th>Status</th></tr>';

          for(var x in history){
            var img = history[x].picture;
            if(img !== ''){
              img = JSON.parse(img);

              if(img.length >0){
                var car_img = '<img src="'+base_url+'/uploads/car/'+ img[0]['picture'] +'" width="80px">';
              }else{
                var car_img = '';
              }              
            }else{
              var car_img = '';
            }
            var createdon = history[x].createdon;
            var negotiation_date = '';
            if(createdon !== ''){
              var date = new Date(createdon);
              
              negotiation_date = date.getDate() + ' ' + (month[date.getMonth() + 1]) + ' ' +  date.getFullYear();
            }
            history_html += '<tr>';
            history_html += '<td>'+ car_img +'</td>';
            history_html += '<td>'+ history[x].model +'</td>';         
            history_html += '<td>'+ negotiation_date +'</td>';         
            history_html += '<td>'+ history[x].status_description +'</td>';         
            history_html += '</tr>';
          }
          history_html += '</table>';
        }
        $("#tbl-invoice-history").html(history_html);
      
	    },
	    error: function(error) {
	        console.log(error);
	    }
	});  
} 

  function getDeparturePort(){
  if($("#departure_country").val()=== ''){
    return false;
  }  
    var requestData = {
      destination_country: $("#departure_country").val(),
    };

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('front.getDestinationPort')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        
        var html_template = '';
        for(n in result){
          if(result[n]['price'] === null){
            var price = 0;
          }else{
            var price = result[n]['price'];
          }
          html_template += '<option value="'+ result[n]['id'] +'" data-price = "'+ price +'" >'+result[n]['port_name']+'</option>'
        }
        $("#departure_port").html(html_template);
        $("#departure_port").attr('disabled',false);
      }
    });
  }
    
  $("#departure_country").on("change",function(){
    getDeparturePort();
  })
  $("#destination_country").on("change",function(){
    getDestinationPort();
  })
  
  $(document).on('ready', function(){
    console.log($("#inspection").val());
    if($("#inspection").val() == 0){
      $("#inspection_value").hide();
    }else{
      $("#inspection_value").show();
    }

    $("#inspection").on('change',function(){
      if($("#inspection").val() == 0){
        $("#inspection_value").hide();
      }else{
        $("#inspection_value").show();
      }
    })
  })
</script>
@stop
