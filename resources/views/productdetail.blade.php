@extends('layouts.master')

@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
@stop

@section('content')
<div class="row" style='min-height: 500px'>
  @include('carfilter')

  
  <div class="col-md-9">

      <!--h1>Chevrolet</h1-->
     <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-12" align='center'>
          
          <div class="container">
            <div class='row'>
              <div class='brand-logo'>
                <?php 
                $file = URL::to('/').'/uploads/car_logo/'.$car->logo;
                if (file_exists($file)) {?>                  
                <img src="{{$file}}">
                <?php }?>
              </div>
            </div>
            <div class="row">
              @php
                $picture_arr = json_decode($car->picture);              
              @endphp
              <div class="col-md-12 col-xs-12" align='center' style="margin-bottom:-20%">
                @if($picture_arr[0]->picture !== '')
                <img id='main-picture' class='main-picture img-responsive' src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$car->description}}" style="border-radius:5px; width:100%;height:70%;object-fit: cover;" height="70%">
                @endif
              </div>

            </div>
            
            <div class="row">
              <div class="col-sm-12 col-xs-12" align='center'>
              @if(count($picture_arr)>1)
              <?php
                $total_picture = count($picture_arr);
              ?>
              @foreach($picture_arr as $image_detail)
              @if($image_detail->picture !== '')              
                <img class='car-image-collection img-responsive' width='80px' src="{{URL::to('/')}}/uploads/car/{{$image_detail->picture}}" alt="{{$car->description}}" style="border-radius:5px;">
              
              @endif
              @endforeach
              @endif
              </div>
            </div>
            
          </div>
        </div>		
      </div>
      <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-5 col-md-6">
          <div class="mini-box">
            <h5>Calculate Your Total Price</h5>
              <input name="_csrf-frontend" value="tDKqVWfZuktO-0wi1jz_NazMc3IOcvf0zwFIxOIQ-RPCXZMtHq72IgO0BkGYfstwlYBFJXsGhJf5dAeqr3vMfQ==" type="hidden">
              <div class="form-group">
                <!--label for="countryCalc">Select Country</label -->
                <select id="departure_country" class="form-control" name="departure_country">
                  <option value="" selected="selected">Select Country</option>
                  @foreach($destination_country as $country_detail)
                  <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                  @endforeach
                  </select>

              </div> 
              <div class="form-group">
                <!-- label for="portCalc">Select Nearest Port</label -->
                <select id="destination_port" name="destination_port" class="form-control" disabled="disabled">
                  <option value="" selected="selected">Select Nearest Port</option>

                </select>  
              </div> 
              <div class="checkbox">
                <label><input value="1" name="insurance" id='insurance' type="checkbox"> Insurance ?</label>
              </div>
              <div class="checkbox">
                <label><input value="1" name="inspection" id='inspection' type="checkbox"> Pre-ship Inspection ?</label>
              </div>

              <button class="btn btn-primary top10" id='calculate'>Calculate</button>
            </form>
          </div>
        </div>		
        <div class="col-lg-5 col-md-6">
          <div class="mini-box">
            <h5>{{$car->make}} - {{$car->model}} {{$car->manufacture_year}}</h5>
            <span class='selling-point'>{{$car->selling_point}}</span>
            <div class='car-description'>{{$car->description}}</div>
            <div class='price-div'>{{$car->currency}} <span id='unit_price'>{{\App\Http\Controllers\API::currency_format($car->price)}}</span></div>
            <form onsubmit="return negotiationCheck()" action="{{route('customer.negotiate')}}" method="post">
              <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
              <div class='totalprice-div' style='display:none'><span class="selling-type">C&F</span>: <span id='currency-symbol' class="red-color">{{$car->currency}}</span> 
                <span class='car_price red-color'>{{$car->price}}</span>
              </div>
              <input type="hidden" class="original_car_price" id="original_car_price" name="original_car_price" value="{{$car->price}}">
              <input type="hidden" name="car_id" class="car_id" name="car_id" value="{{$car->id}}">
              <input type="hidden" class="car_price" name="car_price" value="{{$car->price}}">
              <input type="hidden" class="insurance" name="insurance" value="0">
              <input type="hidden" class="inspection" name="inspection" value="0">
              <input type="hidden" class="destination_port" name="destination_port" value="">
              <input type="hidden" class="logistic_id" name="logistic_id" value="">
              <input type="hidden" class="currency" name="currency" value="{{$car->currency}}">
              <input type="hidden" class="shipping_price" name="shipping_price" value="">             
              <input type="hidden" class="ocean_freight" name="ocean_freight" value="">        
              <input type="hidden" class="vprice" name="vprice" value="">     
              <input name="chat_1" id='chat_1' type="checkbox" value='I want negotiate the best price'> I want negotiate the best price<br>
              <input name="chat_2" id='chat_2' type="checkbox" value='I want to know the shipping schedule'> I want to know the shipping schedule<br>
              <input name="chat_3" id='chat_3' type="checkbox" value='I want to know about the condition of the car'> I want to know about the condition of the car
              <textarea id='chat' name='chat' class='form-control' readonly='true'></textarea>
            <button class="btn btn-primary top10">Negotiate</button>
            </form>
          </div>
        </div>		
      </div>
      
      @if($car->youtube !== '')
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
          <div class="mini-box">
            <iframe width="100%" height="415" src="https://www.youtube.com/embed/<?php echo $car->youtube; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
      @endif
      
      <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
          <div id="cardetailtabs" class="cardetailtabs">             
            <ul class="nav nav-tabs">
              <li><a href="#detailtabs-1">Specific Information</a></li>
              <li><a href="#detailtabs-2">Option</a></li>
              <li><a href="#detailtabs-3">Seller</a></li>
            </ul>
            <div class="tab-content">
              <div id="detailtabs-1" class="tab-pane fade in active show" style="overflow:auto">
                <div class='row'>
                  <div class="col-md-6">
                    <table class="table detail-table" boder='1'>
                      <tbody>
                        <tr>
                          <th class="car-info-table-ttl"> VIN <span class="col-lightblack size-xsmall"> (Vehicle Identification Number) </span>/Serial No.</th>
                          <td class="car-info-table-body vin-table-body">{{$car->vin}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Make</th>
                          <td class="car-info-table-body">{{$car->make}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Model Code</th>
                          <td class="car-info-table-body">{{$car->model}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Product Type</th>
                          <td class="car-info-table-body">{{$car->type}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Registration Date</th>
                          <td class="car-info-table-body">{{date('d M Y', strtotime($car->registration_date))}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Manufacture Year</th>
                          <td class="car-info-table-body">{{$car->manufacture_month !== '' && isset($month_array[$car->manufacture_month])?$month_array[$car->manufacture_month]. ' ' .$car->manufacture_year :''. $car->manufacture_year}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Mileage (KM)</th>
                          <td class="car-info-table-body">{{\App\Http\Controllers\API::currency_format($car->distance)}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Transmission</th>
                          <td class="car-info-table-body">{{$car->car_transmission_description}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Plate Number</th>
                          <td class="car-info-table-body">{{$car->plate_number}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Motor Number</th>
                          <td class="car-info-table-body">{{$car->motor_number}}</td>
                        </tr>                      
                        <tr>
                          <th class="car-info-table-ttl">Engine Capacity (Displacement)</th>
                          <td class="car-info-table-body">{{$car->engine}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Fuel</th>
                          <td class="car-info-table-body">{{$car->fuel}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Steering</th>
                          <td class="car-info-table-body">
                            @if($car->steering == 1)
                            {{'left'}}
                            @else
                            {{'right'}}
                            @endif
                          </td>
                        </tr>                                            
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                    <table class="table detail-table" boder='1'>
                      <tbody>
                        <tr>
                          <th class="car-info-table-ttl">Drive Type</th>
                          <td class="car-info-table-body">{{$car->drive}}</td>
                        </tr> 
                        <tr>
                          <th class="car-info-table-ttl">Exterior Color</th>
                          <td class="car-info-table-body">{{$car->exterior_color}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Interior Color</th>
                          <td class="car-info-table-body">{{$car->interior_color}}</td>
                        </tr>

                        <tr>
                          <th class="car-info-table-ttl">Door</th>
                          <td class="car-info-table-body">{{$car->door}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Number of Seats</th>
                          <td class="car-info-table-body">{{$car->seat}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Total Weight (Kg)</th>
                          <td class="car-info-table-body">{{\App\Http\Controllers\API::currency_format($car->weight)}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Dimension</th>
                          <td class="car-info-table-body"></td>
                        </tr>
                        <?php 
                        $car_d = explode(',',$car->dimension);
                        if(count($car_d) == 3){
                          $p = $car_d[0] !== '' ? $car_d[0] : 0;
                          $l = $car_d[1] !== '' ? $car_d[1] : 0;
                          $t = $car_d[2] !== '' ? $car_d[2] : 0;

                          $car_volume = $p * $l * $t / 1000000;
                        }else{
                          $car_volume = 0;
                        }
                        ?>
                        <tr>
                          <th class="car-info-table-ttl"><span class="left30">Length (cm)</span></th>
                          <td class="car-info-table-body">{{isset($car_d[0])?\App\Http\Controllers\API::currency_format($car_d[0]):''}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl"><span class="left30">Width (cm)</span></th>
                          <td class="car-info-table-body">{{isset($car_d[1])?\App\Http\Controllers\API::currency_format($car_d[1]):''}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl"><span class="left30">Height (cm)</span></th>
                          <td class="car-info-table-body">{{isset($car_d[2])?\App\Http\Controllers\API::currency_format($car_d[2]):''}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Engine No</th>
                          <td class="car-info-table-body">{{$car->serial}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Remarks</th>
                          <td class="car-info-table-body">{{$car->remark}}</td>
                        </tr>
                        <tr>
                          <th class="car-info-table-ttl">Condition</th>
                          <td class="car-info-table-body">
                            @if($car->state == '1')
                              {{'New'}}
                            @elseif($car->state == '2')
                              {{'Used'}}
                            @else
                              {{'Broken'}}
                            @endif
                          </td>
                        </tr>


                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <table class="table detail-table" boder='1'>
                      <tbody>
                        <tr>
                          <th class="car-info-table-ttl"> Comment </th>
                        </tr>
                        <tr>
                          <td>
                            {{$car->comment}}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row top10">

                  <div class='col-md-12' align='center'>                  
                    <form onsubmit="return negotiationCheck()" action="{{route('customer.negotiate')}}" method="post">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <input type="hidden" class="car_id" name="car_id" name="car_id" value="{{$car->id}}">
                    <input type="hidden" class="insurance" name="insurance" value="0">
                    <input type="hidden" class="inspection" name="inspection" value="0">
                    <input type="hidden" class="car_price" name="car_price" value="{{$car->price}}">
                    <input type="hidden" class="currency" name="currency" value="{{$car->currency}}"> 
                    <input type="hidden" class="destination_port" name="destination_port" value="">
                    <input type="hidden" class="logistic_id" name="logistic_id" value="">
                    <input type="hidden" class="shipping_price" name="shipping_price" value="">
                    <input type="hidden" class="ocean_freight" name="ocean_freight" value="">
                    <input type="hidden" class="vprice" name="vprice" value="">
                    <button class="btn btn-primary">Negotiate</button>
                    </form>
                  </div>
              </div>
    </div>
    <div id="detailtabs-2" class="tab-pane fade">
      <table width='100%' class='accessories' id='editAccessories'>
                  <tr>
                  <?php 
                    $i = 0;
                    $car_accessories = json_decode($car->accessories);
                    foreach($accessories as $accessories_detail){ 
                      if($i == 5){
                        echo '</tr><tr>';
                        $i = 0;
                      }

                      if(is_array($car_accessories) && in_array($accessories_detail->description,$car_accessories)){?>
                      <td style='background-color: #900;color:#fff;'>{{$accessories_detail->description}}</td>  
                      <?php
                        }else{
                      ?>
                      <td>{{$accessories_detail->description}}</td>
                      <?php }?>
                    <?php 
                    $i++;
                    }?>
                  </tr>
                </table>
    </div>
    <div id="detailtabs-3" class="tab-pane fade">
      <table class="table">
                <tbody>
                  <tr>
                    <th>Country</th><td>Singapore</td>
                  </tr>
                  <tr>
                    <th>Address</th><td>Singapore</td>
                  </tr>
                  <tr>
                    <th>Contact Person</th><td>Optimus</td>
                  </tr>
                  <tr>
                    <th>Operating Hours</th><td>Office Hours</td>
                  </tr>
                  <tr>
                    <th>Language</th><td>Indonesia</td>
                  </tr>
                </tbody>
              </table>	
    </div>
  </div>
            
 
          </div>
          @if(isset($_SERVER['HTTP_REFERER']))
            <center><button class="btn btn-primary bottom10" onclick="goTo('{{$_SERVER['HTTP_REFERER']}}')">Back</button></center>
          @endif
        </div> 
      </div> 
  </div>

</div>
<div id='total_visitor'><a href='#' onclick='$("#total_visitor").hide()'>x</a> &nbsp;&nbsp;&nbsp;<span class='fa fa-user'> </span> {{rand(3,20)}}</div>
<!-- /.row -->

@stop


@section('script')
<script>
  var car_volume = '{{$car_volume}}';
  $("#departure_country").select2();
  $("#destination_port").select2();
  var client_ip = "{{Request::ip()}}";
  var city = '';
  var insurance_fee = "{{$insurance_fee}}";
  var inspection_fee = "{{$inspection_fee}}";
  var ocean_freight = "{{$ocean_freight}}";
  
  $("#insurance").on('change',function(){
    if($("#insurance").is(":checked")){
      $(".selling-type").html('CIF');
    }else{
      $(".selling-type").html('C&F');  
    }      
  })
  
  
  var url = 'http://api.ipstack.com/'+client_ip+'?access_key=71a28e473f6d111453340f7a7c6f213d&format=1';
    
    $.ajax({
      url: url,
      type: 'get',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        if(typeof result.country_code !== 'undefined'){
          $("#departure_country").val(result.country_code).trigger('change');
          city = result.city;
          getPort();
        }
      }
    });
    
    
  $(".nav-tabs a").click(function(){
    $(this).tab('show');
  });
    
  $("#chat_1").on('change',function(){
    checkChat();
  })
  $("#chat_2").on('change',function(){
    checkChat();
  })
  $("#chat_3").on('change',function(){
    checkChat();
  })
  $(document).on('ready',function(){
    checkChat();
  })
  
  function checkChat(){
    var chat = [];
    if($("#chat_1").is(":checked")){
      chat.push($("#chat_1").val());
    }
    
    if($("#chat_2").is(":checked")){
      chat.push($("#chat_2").val());
    }
    
    if($("#chat_3").is(":checked")){
      chat.push($("#chat_3").val());
    }

    $("#chat").val(chat.join('\n'));
    
  }
  function goTo(url){
    window.location = url;
  }
  $(".car-image-collection").on('click',function(){
    var picture_url = $(this).attr('src');
    $("#main-picture").attr('src',picture_url);
    return false;
  });
  
  function negotiationCheck(){
    if($("#departure_country").val() === '') {
      alert('please choose Departure country');
      return false;
    }
    
    if($("#destination_port").val() === ''){
      alert('please choose Destination port');
      return false;
    }
    $("#calculate").click();
    return checkAuth();
  }
  
  $("#calculate").on('click',function(){
    if($("#departure_country").val() === '') {
      alert('please choose Departure country');
      return false;
    }
    
    if($("#destination_port").val() === ''){
      alert('please choose Destination port');
      return false;
    }
    
    var price = parseFloat($("#original_car_price").val());
    var insurance_value = 0;
    var inspection_value = 0;
    
    if($("#destination_port option:selected").data('vprice') != null && parseFloat($("#destination_port option:selected").data('vprice')) != 0 && car_volume != 0){
      var shipping_price = Math.round(parseFloat($("#destination_port option:selected").data('vprice')) * car_volume);
      // -- new
      $('.vprice').val($("#destination_port option:selected").data('vprice'));
      //
    }else if($("#destination_port option:selected").data('price')){
      var shipping_price = Math.round(parseFloat($("#destination_port option:selected").data('price')));
      // -- new
      $('.vprice').val(0);
      // --
    }else{
      var shipping_price = 0;
      // -- new
      $('.vprice').val(0);
      //
      alert('Car Price are not include shipping price to your country, please contact us to get the shipping price info');
    }
    
    price += shipping_price;
    if($('#insurance').is(':checked')){
      price += parseFloat(insurance_fee);   
      insurance_value = 1;
    }
    if($('#inspection').is(':checked')){
      price += parseFloat(inspection_fee);
      inspection_value = 1;
    }

    // -- new --
    console.log($("#destination_port option:selected").data('vprice'));
    console.log($("#destination_port option:selected").data('price'));
    if (parseFloat($("#destination_port option:selected").data('vprice')) != 0) {
      price += parseFloat(ocean_freight);
      console.log('masuk sini');
    }
    // --    
    
    if($("#insurance").is(":checked")){
      $(".selling-type").html('CIF');
    }else{
      $(".selling-type").html('C&F');  
    }
    
    $('.insurance').val(insurance_value);
    $('.inspection').val(inspection_value);
    $('.destination_port').val($("#destination_port").val());
    $('.logistic_id').val($("#destination_port option:selected").data('logisticid'));
    $('.shipping_price').val(shipping_price);
    $('.ocean_freight').val(ocean_freight);
    $(".car_price").text(price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    $(".car_price").val(price);
    
    if($("#unit_price").text() !== $(".car_price").text()){
      $(".totalprice-div").show();
      $(".price-div").css('text-decoration','line-through');
    }else{
      $(".totalprice-div").hide();
      $(".price-div").css('text-decoration','');
    }
    
  })
  
  $("#car_make").on('change', function () {

    var requestData = {
      make_id: $("#car_make").val(),
    };

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('front.getCarModel')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        var select_template = '<option value="" selected="selected">-- Model --</option>';
        for (x in result) {
          select_template += '<option value="' + result[x].id + '">' + result[x].model + '</option>';
        }
        $("#car_model").html(select_template);
        $("#car_model").prop('disabled', false);
      }
    });
  })
  
  $("#departure_country").on("change",function(){
    getPort();
  })
  
  function getPort(){
  if($("#departure_country").val()=== ''){
    return false;
  }  
    var requestData = {
      destination_country: $("#departure_country").val(),
      city : city,
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
          if(result[n]['volume_price'] === null){
            var volume_price = 0;
          }else{
            var volume_price = result[n]['volume_price'];
          }
          
          if(result[n]['price'] === null){
            var price = 0;
          }else{
            var price = result[n]['price'];
          }
          html_template += '<option value="'+ result[n]['id'] +'" data-price = "'+ price +'" data-vprice = "'+ volume_price +'" data-logisticid = "'+ result[n]['logistic_id'] +'" >'+result[n]['port_name']+'</option>'
        }
        $("#destination_port").html(html_template);
        $("#destination_port").attr('disabled',false);
      }
    });
  }
  

  getPort();
  checkChat();
  
  function popError(error) {
    $("#popError .alert").html(error);
    $('#popError').modal('show');
    return false;
  }

  function selectLogin(select) {
    urlLogin = '';
    urlSignup = '';
    if (select == 'buyer') {
      urlLogin = '/optimus/index.php?r=buyer%2Flogin';
      urlSignup = '/optimus/index.php?r=buyer%2Fsignup';
    } else if (select == 'seller') {
      urlLogin = '/optimus/index.php?r=seller%2Flogin';
      urlSignup = '/optimus/index.php?r=seller%2Fsignup';
    }
    $("#loginForm").prop('action', urlLogin);
    $("#signupForm").prop('action', urlSignup);

    $('#popSelectLogin').modal('hide');
    $('#popSelectLogin').on('hidden.bs.modal', function () {
      $('#popLogin').modal('show');
    });
  }

  $("#signupForm").submit(function (event) {
    event.preventDefault();

    var $form = $(this),
            post = $form.serialize(),
            url = $form.attr("action");

    var posting = $.post(url, post);

    $form.find(".alert-danger").hide();
    posting.done(function (data) {
      if (data == '')
        location.reload();
      $form.find(".alert-danger").html(data);
      $form.find(".alert-danger").show();
    });
  });

  $("#loginForm").submit(function (event) {
    event.preventDefault();

    var $form = $(this),
            post = $form.serialize(),
            url = $form.attr("action");

    var posting = $.post(url, post);

    $form.find(".alert-danger").hide();
    posting.done(function (data) {
      if (data == '')
        location.reload();
      $form.find(".alert-danger").html(data);
      $form.find(".alert-danger").show();
    });
  });


  if ($("#carsidesearch").length > 0) {
    /*
     $( "#carsidesearch" ).accordion({
     heightStyle: "content",
     collapsible: true, 
     active: false
     });
     */
    //    $.get("index.php?r=gallery/makemodel", function(result){
    //        /*
    //		$( "#carsidesearch .make ul.menu" ).html(result);
    //		$( "#carsidesearch .make ul.menu" ).menu();	
    //		*/
    //		$("#carsidesearch div.make > ul").html(result);
    //    });
    //    $.get("index.php?r=gallery/type", function(result){
    //		$("#carsidesearch div.type > ul").html(result);
    //    });
    //    $.get("index.php?r=gallery/category", function(result){
    //		$("#carsidesearch div.category > ul").html(result);
    //    });
    //    $.get("index.php?r=gallery/price", function(result){
    //		$("#carsidesearch div.price > ul").html(result);
    //    });
  }

  if ($("#home-recent").length > 0) {
    //    $.get("index.php?r=gallery/recent", function(result){
    //        $( "#home-recent" ).html(result);
    //    });	
  }

  if ($("#gallery-result").length > 0) {
    //    $.post("tools/carlist.php" + window.location.search + "&size=24", {check: 'sent'}, function(result){
    //        $( "#gallery-result" ).html(result);
    //    });
  }

  


  $(function () {
    $("#slider-range").slider({
      range: true,
      min: 0,
      max: 90000,
      //values: [ 1000, 300000 ],
      slide: function (event, ui) {
        $("#cars_price1").val(ui.values[ 0 ]);
        $("#cars_price2").val(ui.values[ 1 ]);
        $("#price_amount").html("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
      }
    });
    $("#amount").val("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));
  });


</script>

@stop
