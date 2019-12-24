@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
@section('content')

<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('customer.left_menu')
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
            @include('customer.negotiation_menu')
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
                    <a href='{{route('customer.account')}}'>Customer Information</a>
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
          
          <form method="post" action="{{route('customer.saveProformaInvoice')}}" onsubmit="return formValidation()">
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
          
          if($status > 0){
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
              <select class='input-style' name='country' {{$disabled}}>
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
              {{$car->manufacture_year .' '. $car->model .' '. $car->make }}
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
              <select id="departure_country" class="form-control" name="departure_country" disabled>
                  @foreach($logistic['departure_country'] as $country_detail)
                  @if($country_detail->country_code === 'SG' )
                    <option value="{{$country_detail->country_code}}" selected>{{$country_detail->country_name}}</option>
                  @else
                  @endif
                  @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="departure_port" name="departure_port" class="form-control" disabled>
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
             <select id="destination_country" class="form-control" name="destination_country" disabled>
                <option value="">Select Country</option>
                  @foreach($logistic['destination_country'] as $country_detail)
                  @if(isset($session['destination_port']) && $country_detail->country_code == $session['destination_port']->country_code )
                    <option value="{{$country_detail->country_code}}" selected>{{$country_detail->country_name}}</option>
                  @else
                    <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                  @endif
                  
                  @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="destination_port" name="destination_port" class="form-control" disabled>
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
              <select id="inspection" class="form-control" name="inspection" disabled>
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
              <!--Please select the currency(JYP/USD) which you wish to receive the payment from Carview.<br>-->
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
              <!--<span class='color-red'><small>*70 USD will be charged if you choose to receive in USD.(70 USD/transfer)</small></span>-->
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
              <input type='text' name='item_name[]' readonly class='form-control input-style' value='{{isset($detail[0]->item_name)?$detail[0]->item_name:''}}'><br>
              <input type='text' name='item_name[]' readonly class='form-control input-style' value='{{isset($detail[1]->item_name)?$detail[1]->item_name:''}}'><br>
              <input type='text' name='item_name[]' readonly class='form-control input-style' value='{{isset($detail[2]->item_name)?$detail[2]->item_name:''}}'><br>
              <input type='text' name='item_name[]' readonly class='form-control input-style' value='{{isset($detail[3]->item_name)?$detail[3]->item_name:''}}'><br>
              <input type='text' name='item_name[]' readonly class='form-control input-style' value='{{isset($detail[4]->item_name)?$detail[4]->item_name:''}}'><br>
            </div>
            
            <div class="col-md-4">      
              <small>Price<br><br></small>
              <input type='text' name='item_price[]' readonly class='form-control input-style' value='{{isset($detail[0]->item_price)?$detail[0]->item_price:''}}'><br>
              <input type='text' name='item_price[]' readonly class='form-control input-style' value='{{isset($detail[1]->item_price)?$detail[1]->item_price:''}}'><br>
              <input type='text' name='item_price[]' readonly class='form-control input-style' value='{{isset($detail[2]->item_price)?$detail[2]->item_price:''}}'><br>
              <input type='text' name='item_price[]' readonly class='form-control input-style' value='{{isset($detail[3]->item_price)?$detail[3]->item_price:''}}'><br>
              <input type='text' name='item_price[]' readonly class='form-control input-style' value='{{isset($detail[4]->item_price)?$detail[4]->item_price:''}}'><br>
            </div>
            
          </div>
          
          <div class='row'>
            <div class="col-md-4 background1" align='right'>
              Buyer's Payment Due Date
            </div>
            <div class="col-md-8">
              <div class='row'>
                <div class='col-md-4'>
                  <select name='due_date' class="form-control" id='due_date' width='40%' disabled>
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
              @if($session['proforma_invoice']->status <= 1)
              <!-- <input type="submit" value="confirm" class="btn btn-danger"> -->
              @endif
            </div>
          </div>
          </form>
          
          @if($session['proforma_invoice']->status <= 1)
          <div class="row">
            <div class="col-md-12" align="center">
              <a href="{{route('customer.negotiation',['id'=>$session['negotiation']->id])}}"><button class="btn btn-danger" id='back-btn'>Back</button></a>
            </div>
          </div>
          @endif

          <div class='row'>
            @if($session['proforma_invoice']->status > 1)
            <div class="col-md-12" align="center">
              <a href="{{route('customer.negotiation',['id'=>$session['negotiation']->id])}}"><button class="btn btn-danger" id='back-btn'>Back</button></a>
              @if($session['proforma_invoice']->customer_approval != 1)
              <input type="submit" value="reject" class="btn btn-info" id='edit-proforma'>
              @endif
              <button class="btn btn-danger" id='pdf-btn'>PDF</button>
              @if(!isset($session['invoice']) && $session['proforma_invoice']->customer_approval == 0)
              <input type="submit" value="Approve" class="btn btn-danger" id='create-invoice'>
              @endif
            </div>
            @endif
          </div>
        </div>      
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script>  
  $("#country").select2();
  $("#departure_country").select2();
  $("#departure_port").select2();
  $("#destination_country").select2();
  $("#destination_port").select2();
  
  var status_proforma_invoice = "{{$status}}";
  var port_id = "{{isset($session['destination_port']) ? $session['destination_port']->id : ''}}";
  $("#pdf-btn").on('click',function(){
    window.open('{{route("report.proformaInvoice")}}');
  })
  $("#edit-proforma").on('click',function(){
    swal({
      title: "Are you sure?",
      text: "Once you reject proforma in, you can't edit your proforma invoice and submit it again",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Poof! Your Proforma invoice has been rejected, we will contact you soon.", {
        icon: "success",
        });
        window.location="{{route('customer.editproformainvoice',['id'=>$session['proforma_invoice']->id])}}";
      } else {
        swal("Your file is safe!");
      }
    });
    
    
  })
  $("#create-invoice").on('click',function(){
    var requestData = {
      id: $("#proforma_invoice_id").val(),
      user_type : 'customer',
      due_date : $('#due_date').val(),
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
        window.location="{{route('customer.createInvoice')}}";
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

    
    var confirmation = confirm('Are you sure want to submit this proforma invoice?')
    
    if(confirmation === true){
      return true;
    }else{
      return false;
    }
  }
  
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
        if(status_proforma_invoice>1){
          $("#destination_port").attr('disabled',true);
        }else{
//          $("#destination_port").attr('disabled',false);
        }
      }
    });
  }
  

  getDestinationPort();
  
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
//        $("#departure_port").attr('disabled',false);
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
