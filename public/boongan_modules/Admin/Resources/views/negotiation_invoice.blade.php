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
          
          <div class='row'>
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-danger checkout-btn' id='btn-step1' onClick="goto('step1')">Price</button>
          </div>
          <div class='col-md-1 col-xs-1' align='center'>
            <span class='glyphicon glyphicon-menu-right'></span>
          </div>
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-default checkout-btn' id='btn-step2' onClick="goto('step2')">Address</button>
          </div>
          <div class='col-md-1 col-xs-1' align='center'>
            <span class='glyphicon glyphicon-menu-right'></span>
          </div>  
          <div class='col-md-2 col-xs-2' align='center'>
            <button class='btn-default checkout-btn' id='btn-step3' onClick="goto('step3')">Confirm</button>
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
                        <label>{{$session['invoice']->destination_port_detail->country_name.'/'.$session['invoice']->destination_port_detail->port_name }}</label>
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
                        <input class='form-control' type='tex' name='final-destination' id='final-destination' value='{{$session['invoice']->final_destination}}'>
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
                        <b>C&F PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <b>Total</b>
                      </div>

                      <div class='col-md-6'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
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
                        <input type='text' class='form-control' name='consignee-name' id='consignee-name' value='{{isset($session['invoice']->name)?$session['invoice']->name:''}}'>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label>Street Address *</label>
                      </div>
                      <div class='col-md-6'>
                        <textarea class="form-control" name='address' id='address'>{{isset($session['invoice']->street_address)?$session['invoice']->street_address:''}}</textarea>
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
                        <input type='text' class='form-control' name='tel' id='tel' value='{{isset($session['invoice']->telephone)?$session['invoice']->telephone:''}}'>
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

                <div class='row padding5'>
                  <div class='col-md-3'>
                    <span class="background1 inv-sub-span">Remitter's Name</span>
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
                        <b>C&F PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <b>Total</b>
                      </div>

                      <div class='col-md-6'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
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
                        {{$session['invoice']->currency.' '.$session['invoice']->total_amount}}
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
                
                <div class='row padding5 background1'>
                  <table width='100%'>
                    <tr>
                      <td><input type="checkbox" name="tnc" id="tnc"> By Clicking "Order Item" button you agree to the following terms.</td>
                    </tr>
                    <tr>
                      <td> > vehicle sales agreement</td>
                    </tr>
                    <tr>
                      <td> > Terms of Use for tradecarview</td>
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
                        <b>C&F PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <b>Total</b>
                      </div>

                      <div class='col-md-6'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
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
                    <small>You will also receive a receipt via email</small>
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
                        {{$session['invoice']->currency.' '.$session['invoice']->total_amount}}
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
                        <label>SUMITOMO MITSUI BANKING CORPORATION</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Branch Name
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>GINZA</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Branch Code
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label></label>
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
                        <label>SMBCJPJT</label>
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
                        <label>8-10 GINZA 5-CHOME, CHUO-KU, TOKYO,<br>Japan 104-1161</label>
                      </div>
                    </div>                    
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-4 background1'>
                    Name of Account Holder
                  </div>
                  <div class='col-md-8'>
                    <div class='row'>
                      <div class='col-md-12'>
                        <label>CARVIEW CORPORATION</label>
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
                        <label>3063701</label>
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
                        <b>C&F PRICE</b>
                      </div>

                      <div class='col-md-6 car-cf-price'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <b>Total</b>
                      </div>

                      <div class='col-md-6'>
                        <b>{{$session['invoice']->currency.' '.$session['invoice']->total_amount}}</b>
                      </div>
                    </div>
                  </div>
                </div>
              </div>    
            </div>
            <div class='col-md-12' align='center'>
              <a href="{{route('report.invoice')}}" target="_blank">Please check invoice order details</a>
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
  var status_invoice = "{{isset($session['invoice']->status)?$session['invoice']->status:null}}";
  
  if(status_invoice !== ''){
    $("#tnc").prop('checked',true);
    goto('step4');
    $("#btn-step2").removeClass('btn-default').addClass('btn-danger');
    $("#btn-step3").removeClass('btn-default').addClass('btn-danger');
  }
  
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
    
    updateInvoice(step);
    
    $(".invoice-step-content").hide();
    $('.'+step).show();
    $("#btn-"+step).removeClass('btn-default').addClass('btn-danger');
  }
  
  function backto(step){
    $(".invoice-step-content").hide();
    $('.'+step).show();
    $("#btn-"+step).removeClass('btn-danger').addClass('btn-default');
  }
  
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
      'id': "{{$session['invoice']->id}}"
    }
    if(step === 'step4'){
      requestData['status'] = 0;
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

      }
    });
  }
</script>
@stop
