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
          <h2>Invoice</h2>
        </div>
        <div>
          <hr>
        </div>
        <div class='admin-content'>
          <div width="100%">
            @include('admin::negotiation_menu')
          </div>
          
          <div class="height10"></div>
          <?php          
          if($session['invoice']->status == 1){
            $disabled = 'disabled=true';
          }else{
            $disabled = '';
          }
          ?>
          
          <div class='row'>
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-danger checkout-btn' id='btn-step1' <?php echo $disabled?> onClick="goto('step1')">Price</button>
          </div>
          <div class='col-md-1 col-xs-1' align='center'>
            <span class='glyphicon glyphicon-menu-right'></span>
          </div>
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-default checkout-btn' id='btn-step2' <?php echo $disabled?> onClick="goto('step2')">Address</button>
          </div>
          <div class='col-md-1 col-xs-1' align='center'>
            <span class='glyphicon glyphicon-menu-right'></span>
          </div>  
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-default checkout-btn' id='btn-step3' <?php echo $disabled?> onClick="goto('step3')">Confirm</button>
          </div>
          <div class='col-md-1 col-xs-1' align='center'>
            <span class='glyphicon glyphicon-menu-right'></span>
          </div>  
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-default checkout-btn' id='btn-step4' onClick="goto('step4')">Complete</button>
          </div>  
          </div>
          
          <div class="height10"></div>
          <div class='container step1 invoice-step-content'>
            <div class='row'>
              <div class='col-md-9'>
                <div class='row'>
                  <div class='col-md-12'>
                    <b>Port and Destination</b>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12'>
                    Please enter the Country, port and consignee's name
                  </div>
                </div>              
                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Port and Departure</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{{$session['invoice']->departure_port_detail->country_name.'/'.$session['invoice']->departure_port_detail->port_name }}</label>
                      </div>                      
                    </div>
                    
                  </div>
                </div>

                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Port of Discharge</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{{ isset($session['invoice']->destination_port_detail->country_name) ? $session['invoice']->destination_port_detail->country_name : ''}} /  {{ isset($session['invoice']->destination_port_detail->port_name) ? $session['invoice']->destination_port_detail->port_name : '' }}</label>
                      </div>                      
                    </div>
                    
                  </div>
                </div>

                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Consignee's Country *</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <select class="form-control" name='consignee-country' id='consignee-country' disabled="true">
                          @foreach($countries as $country)
                          <option value='{{$country->country_code}}' @if($country->country_code === $session['invoice']->consignee_country) {{'selected'}} @endif >{{$country->country_name}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>                    
                  </div>
                </div>


                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Final Destination</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <input class='form-control' readonly="true" type='tex' name='final-destination' id='final-destination' value='{{$session['invoice']->final_destination}}'>
                      </div>                      
                    </div>
                    
                  </div>
                </div>
                
              </div>
              <div class='col-md-3'>
                <div class='row'>
                  <div class='col-md-12 car-box'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <b>{{$car->description}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-12'>
                      <?php 
                          $picture = json_decode($car->picture);
                      ?>
                      <img src="{{URL::to('/')}}/uploads/car/{{$picture[0]->picture}}" alt="{{$car->description}}" width='200px'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['proforma_invoice']->incoterm}} PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.\App\Http\Controllers\API::currency_format($session['invoice']->total_amount)}}</b>
                      </div>
                    </div>                    
                  </div>
                </div>                                
              </div>
            </div>
            <div class='col-md-12' align='center'>
              <button class='btn btn-danger' onclick="goto('step2')">Next Step <span class='glyphicon glyphicon-menu-right'></button>
            </div>  
          </div>
          
          <div class='container step2 invoice-step-content' style='display:none'>
            <div class='row'>
              <div class='col-md-9'>
                <div class='row'>
                  <div class='col-md-12'>
                    <b>Address</b>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12'>
                    Please enter the following information
                  </div>
                </div>              
                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Consignee Details</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Name *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='consignee-name' id='consignee-name' value='{{isset($session['invoice']->name) && $session['invoice']->name != '' ? $session['invoice']->name : $accounts[0]->name }}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Street Address *</label>
                      </div>
                      <div class='col-md-6'>
                        <textarea class="form-control" name='address' id='address'>{{isset($session['invoice']->street_address) && $session['invoice']->street_address != '' ? $session['invoice']->street_address : $accounts[0]->address }}</textarea>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>City *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='city' id='city' value='{{isset($session['invoice']->city)?$session['invoice']->city:''}}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Province/State *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='province' id='province' value='{{isset($session['invoice']->province)?$session['invoice']->province:''}}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Zip/Postal Code</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='zip' id='zip' value='{{isset($session['invoice']->zip)?$session['invoice']->zip:''}}'>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Country</label>
                      </div>
                      <div class='col-md-6'>
                        <?php
                          $consignee_country = isset($session['invoice']->country) && $session['invoice']->country !=='' ?$session['invoice']->country:$session['invoice']->consignee_country;
                          ?>
                        <select class="form-control" name='country' id='country' width="100%">                          
                          @foreach($countries as $country)
                          <option value='{{$country->country_code}}' @if($country->country_code === $consignee_country) {{'selected'}} @endif >{{$country->country_name}}</option>
                          @endforeach
                        </select>                        
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Tel *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='tel' id='tel' value='{{isset($session['invoice']->telephone) && $session['invoice']->telephone != '' ?$session['invoice']->telephone : $accounts[0]->phone}}'>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Fax</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='fax' id='fax' value='{{isset($session['invoice']->fax)?$session['invoice']->fax:''}}'>
                      </div>
                    </div>
                  </div>
                </div>

                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Notify Party *</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <?php
                        $notify_party = isset($session['invoice']->notify_party)?$session['invoice']->notify_party:1;
                        ?>
                        <select name="notif" class='form-control' id='notif'>
                          <option value="1" @if($notify_party == 1){{'selected'}} @endif >Same as Consignee</option>
                          <option value="2" @if($notify_party == 2){{'selected'}} @endif >Other</option>
                        </select>
                        <small>When consignee is the same as Notify Party, please choose this</small> 
                      </div>                    
                    </div>
                  </div>
                </div>
                
                
                <?php
                  $other_data = $session['invoice']->other_data == null ? [] : json_decode($session['invoice']->other_data,1);
                ?>               
                
                <div class='row padding5' id='other_data' style="display:none">
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Other Details</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Name *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='other-name' id='other-name' value='{{isset($other_data['other_name'])?$other_data['other_name']:''}}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Street Address *</label>
                      </div>
                      <div class='col-md-6'>
                        <textarea class="form-control" name='other-address' id='other-address'>{{isset($other_data['other_address'])?$other_data['other_address']:''}}</textarea>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>City *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='other-city' id='other-city' value='{{isset($other_data['other_city'])?$other_data['other_city']:''}}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Province/State *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='other-province' id='other-province' value='{{isset($other_data['other_province'])?$other_data['other_province']:''}}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Zip/Postal Code</label>
                      </div>
                      <div class='col-md-6'>
                        <input type=  'text' class='form-control' name='other-zip' id='other-zip' value='{{isset($other_data['other_zip'])?$other_data['other_zip']:''}}'>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Country</label>
                      </div>
                      <div class='col-md-6'>
                        <?php
                          $consignee_country = isset($other_data['other_country']) ? $other_data['other_country'] : '';
                          ?>
                        <select class="form-control" name='other-country' id='other-country' width="100%">                          
                          @foreach($countries as $country)
                          <option value='{{$country->country_code}}' @if($country->country_code === $consignee_country) {{'selected'}} @endif >{{$country->country_name}}</option>
                          @endforeach
                        </select>                        
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Tel *</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='other-tel' id='other-tel' value='{{isset($other_data['other_tel'])?$other_data['other_tel']:''}}'>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Fax</label>
                      </div>
                      <div class='col-md-6'>
                        <input type='text' class='form-control' name='other-fax' id='other-fax' value='{{isset($other_data['other_fax'])?$other_data['other_fax']:''}}'>
                      </div>
                    </div>
                  </div>
                </div>
                

                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Remitter's Name *</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <input type='text' class='form-control' name='remitter-name' id='remitter-name' value='{{isset($session['invoice']->remitter_name)?$session['invoice']->remitter_name:''}}'>
                      </div>                    
                    </div>
                  </div>
                </div>


              </div>
              <div class='col-md-3'>
                <div class='row'>  
                  <div class='col-md-12 car-box'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <b>{{$car->description}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-12'>
                      <?php 
                          $picture = json_decode($car->picture);
                      ?>
                      <img src="{{URL::to('/')}}/uploads/car/{{$picture[0]->picture}}" alt="{{$car->description}}" width='200px'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['proforma_invoice']->incoterm}} PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.\App\Http\Controllers\API::currency_format($session['invoice']->total_amount)}}</b>
                      </div>
                    </div>                    
                  </div>
                </div>
              </div>    
            </div>
            <div class='col-md-12' align='center'>
              <button class='btn btn-default' onclick="backto('step1')"><span class='glyphicon glyphicon-menu-left'></span>Back Step</button>
              <button class='btn btn-danger' onclick="goto('step3')">Next Step <span class='glyphicon glyphicon-menu-right'></span></button>
            </div>  
          </div>
          
          <div class='container step3 invoice-step-content' style='display:none'>
            <div class='row'>
              <div class='col-md-9'>
                <div class='row'>
                  <div class='col-md-12'>
                    <b>Confirm</b>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12'>
                    Check Information details (proforma invoice)
                  </div>
                </div>              
                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Payment</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Wire Transfer <br> Amount</label>
                      </div>
                      <div class='col-md-6'>
                        {{$session['invoice']->currency.' '.\App\Http\Controllers\API::currency_format($session['invoice']->total_amount)}}
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Payment Due Date</label>
                      </div>
                      <div class='col-md-6'>
                        
                      </div>
                    </div>
                  </div>
                </div>
                
                
                
                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Consignee Details</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Consignee Name</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='consignee-name-label'>{{isset($session['invoice']->name)?$session['invoice']->name:''}}</label>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Street Address</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='address-label'>{{isset($session['invoice']->street_address)?$session['invoice']->street_address:''}}</label>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>City</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='city-label'>{{isset($session['invoice']->city)?$session['invoice']->city:''}}</label>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Province/State</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='province-label'>{{isset($session['invoice']->province)?$session['invoice']->province:''}}</label>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Zip/Postal Code</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='zip-label'>{{isset($session['invoice']->zip)?$session['invoice']->zip:''}}</label>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Country</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='country-label'>{{isset($session['invoice']->country)?$session['invoice']->country:''}}</label>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Tel</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='tel-label'>{{isset($session['invoice']->telephone)?$session['invoice']->telephone:''}}</label>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Fax</label>
                      </div>
                      <div class='col-md-6'>
                        <label id='fax-label'>{{isset($session['invoice']->fax)?$session['invoice']->fax:''}}</label>
                      </div>
                    </div>
                  </div>
                </div>


                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Remitter's Name</span>
                  </div>
                  <div class='col-md-9'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label id='remitter-name-label'>{{isset($session['invoice']->remitter_name)?$session['invoice']->remitter_name:''}}</label>
                      </div>                    
                    </div>
                  </div>
                </div>
                
                <div class='row background1' style="padding: 20px;">
                  <table width='100%' style="color: blue;">
                    <tr>
                      <td><input type="checkbox" name="tnc" id="tnc"> By Clicking "Order Item" button you agree to the following terms.</td>
                    </tr>
                    <tr>
                      <td> > Vehicle Sales Agreement</td>
                    </tr>
                    <tr>
                      <td> > Terms of Use for Optimus Services</td>
                    </tr>
                    <tr>
                      <td>Services</td>
                    </tr>
                  </table>
                </div>

              </div>
              <div class='col-md-3'>
                <div class='row'>  
                  <div class='col-md-12 car-box'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <b>{{$car->description}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-12'>
                      <?php 
                          $picture = json_decode($car->picture);
                      ?>
                      <img src="{{URL::to('/')}}/uploads/car/{{$picture[0]->picture}}" alt="{{$car->description}}" width='200px'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['proforma_invoice']->incoterm}} PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.\App\Http\Controllers\API::currency_format($session['invoice']->total_amount)}}</b>
                      </div>
                    </div>                    
                  </div>
                </div>
              </div>    
            </div>
            <div class='col-md-12' align='center'>
              <button class='btn btn-default' onclick="backto('step2')"><span class='glyphicon glyphicon-menu-left'></span>Back Step</button>
              <button class='btn btn-danger' onclick="goto('step4')">Next Step <span class='glyphicon glyphicon-menu-right'></span></button>
            </div>  
          </div>
          
          <div class='container step4 invoice-step-content' style='display:none'>
            <div class='row'>
              <div class='col-md-9'>
                <div class='row'>
                  <div class='col-md-12' align='center'>
                    <b>Thank you for your order!</b>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12' align='center'>
                    <!--<small>You will also receive a receipt via email</small>-->
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12' align='center'>
                    <hr>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12' align='center'>
                    <small><b>Transaction Information</b></small><br>
                    <small><b>Please complete Wire Transfer by the Payment Due date</b></small><br>
                    <small>*Please note we might suspend your account if you do not payfor your orderby Due Date.</small><br>                    
                  </div>
                </div>
                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Invoice Number</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        {{$session['invoice']->invoice_number}}
                      </div>
                    </div>
                  </div>
                </div>
                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Wire Transfer Amount</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        {{$session['invoice']->currency.' '. \App\Http\Controllers\API::currency_format($session['invoice']->total_amount)}}
                      </div>
                    </div>
                  </div>
                </div>
                <div class='row padding5'>
                  <div class='col-md-4'>
                    <span class="background1 inv-sub-span">Payment Due Date</span>
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        {{($session['invoice']->due_date != null) ? \App\Http\Controllers\API::dateFormat($session['invoice']->due_date) : ''}}
                      </div>
                    </div>
                  </div>
                </div>
                
                
                <div class='row height10'></div>
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Bank Name
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{{\App\Http\Controllers\API::getSetting('bank name')}}</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Account Name
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{{\App\Http\Controllers\API::getSetting('bank account name')}}</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Account Number
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{!! \App\Http\Controllers\API::getSetting('bank account number') !!}</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Swift Code
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{{\App\Http\Controllers\API::getSetting('bank swift code')}}</label>
                      </div>
                    </div>                    
                  </div>
                </div>

                <div class='row'>
                  <div class='col-md-4 background1'>
                    Bank Code
                  </div>  
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{{\App\Http\Controllers\API::getSetting('bank code')}}</label>
                      </div>
                    </div>                    
                  </div>
                </div>               
                
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Bank Address
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{!! \App\Http\Controllers\API::getSetting('bank address') !!}</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Office Address
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>{!! \App\Http\Controllers\API::getSetting('office address') !!}</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                

                
              </div>
              <div class='col-md-3'>
                <div class='row'>  
                  <div class='col-md-12 car-box'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <b>{{$car->description}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-12'>
                      <?php 
                          $picture = json_decode($car->picture);
                      ?>
                      <img src="{{URL::to('/')}}/uploads/car/{{$picture[0]->picture}}" alt="{{$car->description}}" width='200px'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['proforma_invoice']->incoterm}} PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.\App\Http\Controllers\API::currency_format($session['invoice']->total_amount)}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-12'>
                        <button class='btn btn-danger' id='cancel-inv-btn' style="width:100% ;">Cancel Invoice</button>
                      </div>
                    </div>
                  </div>                 
                </div>
              </div>    
            </div>
            <div class='col-md-12' align='center'>
              <a href="{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}" class="btn btn-danger" id='back-btn'>Back</a>
              <a href="{{route('report.invoice')}}" target="_blank" class="btn btn-info">Please check invoice order details</a>
            </div>  
          </div>
          
          <div class="height30"></div>
        </div>
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script>  
  $("#consignee-country").select2();
  $("#country").select2();
  var status_invoice = "{{isset($session['invoice']->status)?$session['invoice']->status:0}}";
  
  if(status_invoice != '' && status_invoice != 0){
    $("#tnc").prop('checked',true);
    goto('step4');
    $("#btn-step2").removeClass('btn-default').addClass('btn-danger');
    $("#btn-step3").removeClass('btn-default').addClass('btn-danger');
  }else{
    goto('step1');
  }
  
  $("#notif").on('change',function(){
    if($("#notif").val() == 2){
      $("#other_data").show();
    }else{
      $("#other_data").hide();
      $("#other-name").val('');
      $("#other-address").val('');
      $("#other-city").val('');
      $("#other-province").val('');
      $("#other-zip").val('');
      $("#other-tel").val('');
      $("#other-fax").val('');
    }    
  })
  
  $(document).on('ready',function(){
    if($("#notif").val() == 2){
      $("#other_data").show();
    }else{
      $("#other_data").hide();
      $("#other-name").val('');
      $("#other-address").val('');
      $("#other-city").val('');
      $("#other-province").val('');
      $("#other-zip").val('');
      $("#other-tel").val('');
      $("#other-fax").val('');
    }
  })
  
  function goto(step){
    
    
    if(step == 'step3'){
      if($("#consignee-name").val() === ''){
        alert('Consignee Name is required');
        return false;
      }
      if($("#address").val() === ''){
        alert('Consignee Address is required');
        return false;
      }
      if($("#city").val() === ''){
        alert('Consignee City is required');
        return false;
      }
      if($("#tel").val() === ''){
        alert('Consignee Telephone is required');
        return false;
      }
      if($('#remitter-name').val() == ''){
        alert('Remitter\'s name is required');
        return false;
      }
      
      $("#consignee-name-label").text($("#consignee-name").val());
      $("#address-label").text($("#address").val());
      $("#city-label").text($("#city").val());
      $("#zip-label").text($("#zip").val());
      $("#country-label").text($("#country").val());
      $("#province-label").text($("#province").val());
      $("#tel-label").text($("#tel").val());
      $("#fax-label").text($("#fax").val());
      $("#remitter-name-label").text($("#remitter-name").val());
    }
    if(step == 'step4'){
      if($("#tnc").is(':checked') === false){
        alert('Please click our agreement to complete the invoice');
        return false;
      }
    }
    
    $(".invoice-step-content").hide();
    $('.'+step).show();
    $("#btn-"+step).removeClass('btn-default').addClass('btn-danger');
    
    if(status_invoice >= 1){
      $("#btn-step1").prop('disabled',true);
      $("#btn-step2").prop('disabled',true);
      $("#btn-step3").prop('disabled',true);
      return false;
    }else{
      updateInvoice(step);
    }
  }
  
  function backto(step){
    $(".invoice-step-content").hide();
    $('.'+step).show();
    $("#btn-"+step).removeClass('btn-danger').addClass('btn-default');
  }
  
  $("#cancel-inv-btn").on('click',function(){
    if(status_invoice > 1){
      alert('payment have been paid, you can\'t cancel this invoice');
      return false;
    }
    res = confirm('Are you want to cancel this invoice?');
    if(res == true){
      updateInvoice('cancel');
    }else{
      return false;
    }
    
  })
  
  function updateInvoice(step){
    var requestData = {
      'consignee_country' : $("#consignee-country").val(),
      'final_destination' : $("#final-destination").val(),
      'consignee_name' : $("#consignee-name").val(),
      'address' : $("#address").val(),
      'city' : $("#city").val(),
      'province' : $("#province").val(),
      'zip' : $("#zip").val(),
      'country' : $("#country").val(),
      'tel' : $("#tel").val(),
      'fax' : $("#fax").val(),
      'notify_party' : $("#notif").val(),
      'remitter_name' : $("#remitter-name").val(),
      'id': "{{$session['invoice']->id}}",
      'other_name' : $("#other-name").val(),
      'other_address' : $("#other-address").val(),
      'other_city' : $("#other-city").val(),
      'other_province' : $("#other-province").val(),
      'other_zip' : $("#other-zip").val(),
      'other_tel' : $("#other-tel").val(),
      'other_fax' : $("#other-fax").val(),
      'other_country' : $("#other-country").val(),
      'action' : 'save',
    }
    if(step === 'step4'){
      requestData['status'] = 1;
    }
    if(step === 'cancel'){
      requestData['status'] = 0;
      requestData['action'] = 'cancel';
    }
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('admin.updateInvoice')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        if(requestData['status'] = 1){
          $("#btn-step1").prop('disabled',true);
          $("#btn-step2").prop('disabled',true);
          $("#btn-step3").prop('disabled',true);
        }
        
        if(step=='cancel'){
          location.reload();
        }
        
        if(result['link'] !== ''){
          window.location = result['link'];
        }
      }
    });
  }
</script>
@stop
