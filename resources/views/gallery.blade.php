@extends('layouts.master')

@section('content')
<div class="row" style='min-height:500px;'>
  @include('carfilter') 

  <div class="col-md-9 gallery-content">
    <?php if(isset($_GET['keyword'])){?>
      <a href="{{URL::to('/')}}"><< Back</a>  
    <?php }?>
      
    <div class="row">
      <div class='col-md-12'>
        <?php if(isset($_GET['make']) && $_GET['make'] !== '' && count($car)>0){?>
          <div class='brand-logo'>
            <img src="{{URL::to('/')}}/uploads/car_logo/{{$car[0]->logo}}">
          </div>
        <?php }?>
      </div>
    </div>
      <div class="row">
        <div class="col-md-12 h50"></div>
      </div>
    <?php
    $url_full = url()->full();
    $url_list = explode('?',$url_full);
    $origin_url = $url_list[0];
    $qs = [];
    if(isset($url_list[1])){
      $param_array = explode('&',$url_list[1]);      
      foreach($param_array as $param_detail){
        $arr = explode('=',$param_detail);
        $qs[$arr[0]] = $arr[1];
      }
      
      
    }
    ?>
    <div class='row'>
      <div class='col-md-12 col-xs-12 view-port'>
        <?php
          $qs['view'] = 'grid';
          $url = $origin_url.'?'.http_build_query($qs);
        ?>
        
        @if(isset($_GET['view']))
          @if($_GET['view'] == 'grid' )
            <a href='{{$url}}' class="active"><span class='glyphicon glyphicon-th'></span></a>
          @else
            <a href='{{$url}}'><span class='glyphicon glyphicon-th'></span></a>
          @endif
          <?php
            $qs['view'] = 'list';
            $url = $origin_url.'?'.http_build_query($qs);
          ?>
          @if($_GET['view'] == 'list' )
            <a href='{{$url}}' class="active"><span class='glyphicon glyphicon-th-list'></span></a>
          @else
            <a href='{{$url}}'><span class='glyphicon glyphicon-th-list'></span></a>
          @endif
        
        @else
          <a href='{{$url}}'><span class='glyphicon glyphicon-th'></span></a>
          <a href='{{$url}}' class="active"><span class='glyphicon glyphicon-th-list'></span></a>
        @endif
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 h30"></div>
    </div>
    @if(isset($_GET['view']) && $_GET['view'] == 'grid' )  
    <div class="row">
      <?php
      $i = 0;
      ?>
      @if(count($car)>0)
      @foreach($car as $detail)
      <?php if($detail->flag_payment == 1) { ?>
        <font class="countdown_all" style="display: none;" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
      <?php } ?>
      <?php
      $i++;
      if($i === 5){
        $i=1;
        echo '</div><div class="row">';
      }
        $picture_arr = json_decode($detail->picture);              
      ?>
      <div class="item col-lg-3 col-md-6 mb-4" data-key="1">
        <div class="card h-100">
          <?php if ($detail->flag_payment == 0) { ?>
            <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
              <div class='picture-container'>
                <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" style="border-radius:5px;">
              </div>
            </a>
          <?php } else { ?>
            <?php if (Session::get('user_id') !== null) { ?>
              <a href="javascript:;" onclick="notify({{ $detail->id }})">
                <div class='picture-container'>
                  <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" style="border-radius:5px;">
                </div>
              </a>
            <?php } else { ?>
              <a href="javascript:;">
                <div class='picture-container' data-toggle="modal" data-target="#popSelectLogin">
                  <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" style="border-radius:5px;">
                </div>
              </a>
            <?php } ?>
          <?php } ?>
          <div class="card-body">
            <?php if ($detail->flag_payment == 0) { ?>
              <h4 style="margin-bottom: -5px;" class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
            <?php } else { ?>
              <?php if (Session::get('user_id') !== null) { ?>
                <h4 style="margin-bottom: -5px;" class="card-title"><a href="javascript:;" onclick="notify({{ $detail->id }})">{{$detail->make}} - {{$detail->model}}</a></h4>
              <?php } else { ?>
                <h4 style="margin-bottom: -5px;" class="card-title"><a href="javascript:;" data-toggle="modal" data-target="#popSelectLogin">{{$detail->make}} - {{$detail->model}}</a></h4>
              <?php } ?>
            <?php } ?>
            <p class="card-text">{{$detail->type_description}}</p>
          </div>
          <div class="card-price" style="padding-top: 0">
            <?php if($detail->flag_payment == 0) { ?>
              {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
            <?php } else { ?>
              <font size="2px">Available In :</font>
              <input type="hidden" name="due_date_grid" id="due_date_grid" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
              <font class="countdown_grid" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}"></font>
              <br>
            <?php } ?>
          </div>
          <div class="card-footer">
            <?php if($detail->flag_payment == 0) { ?>
              <a class="btn btn-primary" style="padding: 8px;" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
            <?php } else { ?>
              <?php if(Session::get('user_id') !== null) { ?>
                <a href="javascript:;" onclick="notify({{ $detail->id }})" class="btn btn-info"><p style="line-height:1.5; font-size: 9px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a>
              <?php } else { ?>
                <a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target="#popSelectLogin"><p style="line-height:1.5; font-size: 9px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a>
              <?php } ?>
            <?php } ?>
            <a href="#" class="wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
          </div>
        </div>
      </div>
      @endforeach
      @else
      <div class='col-md-12 gallery-not-found-item' align='center'>
        <b>Data Not Found</b>
      </div>
      @endif
    </div>
      
    @else
    
    @if(count($car)>0)
    <div class="row">
      <div class="col-xs-12" style="overflow-y: auto">
        <center>
          <table border='1' cellspacing='0' cellpadding='0' class='car-list'>
            <tr>
              <th>Photo</th>
              <th>Ref number</th>
              <th>(Selling Point) Make/Model</th>
              <th>Year (Registration)</th>
              <th style='display:none'>Engine Capacity (CC)</th>
              <th>Mileage (Km)</th>
              <th style='display:none'>Steerling Trans</th>
              <th>Vehicle Price</th>
              <th>Action</th>
            </tr>

            @foreach($car as $detail)
            <?php if($detail->flag_payment == 1) { ?>
              <font class="countdown_all" style="display: none;" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
            <?php } ?>
            <?php
            $picture_arr = json_decode($detail->picture);        
            $registration_date_arr = explode('-',$detail->registration_date);        
            ?>
            <tr>
              <td>
                <?php if ($detail->flag_payment == 0) { ?>
                  <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                    <div class='picture-container'>
                      <img class="img-responsive" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" width='120px'>
                    </div>
                  </a>
                <?php } else { ?>
                  <?php if (Session::get('user_id') !== null) { ?>
                    <a href="javascript:;" onclick="notify({{ $detail->id }})">
                      <div class='picture-container'>
                        <img class="img-responsive" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" width='120px'>
                      </div>
                    </a>
                  <?php } else { ?>
                    <a href="javascript:;">
                      <div class='picture-container' data-toggle="modal" data-target="#popSelectLogin">
                        <img class="img-responsive" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" width='120px'>
                      </div>
                    </a>
                  <?php } ?>
                <?php } ?>
              </td>
              <td>
                {{$detail->vin}}
              </td>
              <td>
                <span class="selling-point">{{$detail->make}} - {{$detail->model}}</span><br>
                {{$detail->type_description}}
              </td>
              <td align='center'>
                {{$registration_date_arr[0]}}
              </td>
              <td style='display:none'>
                {{$detail->engine}}
              </td>
              <td align='center'>
                {{\App\Http\Controllers\API::currency_format($detail->distance)}}
              </td>
              <td style='display:none'>
                @if($detail->steering == 1)
                {{'left'}}
                @else
                {{'right'}}
                @endif
              </td>
              <td align='center'>
                <?php if($detail->flag_payment == 0) { ?>
                  {{$detail->currency != '' ? $detail->currency : 'USD'}} <span id='unit_price'>{{\App\Http\Controllers\API::currency_format($detail->price)}}</span>
                <?php } else { ?>
                  Available In:
                  <br>
                  <input type="hidden" name="due_date_list" id="due_date_list" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
                  <font class="countdown_list" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
                <?php } ?>
              </td>
              <td align='center' style="padding: 2px;">
                <?php if ($detail->flag_payment == 0) { ?>
                  <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a><br>
                <?php } else { ?>
                  <?php if(Session::get('user_id') !== null) { ?>
                    <a href="javascript:;" onclick="notify({{ $detail->id }})" class="btn btn-info"><p style="line-height:1.5; font-size: 12px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a><br>
                  <?php } else { ?>
                    <a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target="#popSelectLogin"><p style="line-height:1.5; font-size: 12px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a><br>
                  <?php } ?>
                <?php } ?>
                <a href="#" class="btn btn-normal wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"><i class="fas fa-heart"></i></a>
              </td>
            </tr>
            @endforeach
          </table>
        </center>
      </div>
    </div>
    
    @else
      <div class='col-md-12 gallery-not-found-item' align='center'>
        <b>Data Not Found</b>
      </div>
    @endif
      
    @endif
    <div class='row'>
      <?php 
      $this_page = isset($_GET['page'])?$_GET['page']:1;
      $limit = 12;
      $total_page = ceil($total_car/$limit);
      
      ?>
      
      
      @if($total_car >0)
      <div class='col-md-12 top10' align='center'>
        <?php if($this_page>1){ 
          $page = $this_page-1;
          ?>
        <a href='{{route('front.gallery').'?page='.$page}}'><button class='btn btn-danger'><<</button></a>
        <?php }?>
        
        <?php if($this_page>1){ 
         $page = $this_page-1;
         ?>
        <a href='{{route('front.gallery').'?page='.$page}}'><button class='btn btn-danger'>{{$page}}</button></a>
        <?php }?>
        
        <button class='btn btn-danger' disabled="true">{{$this_page}}</button>
        
        <?php if($total_page>$this_page){
          $page = $this_page+1;
          ?>
        <a href='{{route('front.gallery').'?page='.$page}}'><button class='btn btn-danger'>{{$page}}</button></a>
        <?php }?>
        
        <?php if($total_page>$this_page){
          $page = $this_page+1;
          ?>
        <a href='{{route('front.gallery').'?page='.$page}}'><button class='btn btn-danger'>>></button></a>
        <?php }?>
      </div>
      @endif
      
      <div class='col-md-12 top10 bottom10'><center><button class="btn btn-primary" onclick="goTo('{{isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER'] : route('front.home')}}')">Back</button></center></div>
    </div>
  </div>



</div>
<!-- /.col-lg-9 -->

@stop


@section('script')
<script>
  function goTo(url){
    window.location = url;
  }
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

  $("#cartoolbar").buttonset();

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

  if ($("#detailtabs").length > 0) {
    $("#detailtabs").tabs();
  }

  if ($("#logintabs").length > 0) {
    $("#logintabs").tabs();
  }

  //
  //if($( "#cartabs" ).length > 0) {
  //    $.get("index.php?r=gallery/recommend", function(result){
  //        $( "#cartabs1" ).html(result);
  //        $( "#cartabs2" ).html(result);
  //        $( "#cartabs3" ).html(result);
  //        $( "#cartabs4" ).html(result);
  //    });	
  //}

  function cancelNegotiation(car_id, due_date) {
    $.ajax({
      type: "POST",
      dataType: "JSON",
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      data: {
        car_id: car_id,
        due_date: due_date,
      },
      url: "{{ route('front.cancelNegotiation') }}",
      success:function(result){
        if(result['response'] == 1) {
          location.reload();
        } else {
          alert(result['message']);
        }
      }
    });
  }

  function notify(car_id) {
    window.location.href = '{{URL::to("/")}}/notify/'+car_id;
  }

  // y untuk countdown all
  var y = setInterval(function() {
      
    $('.countdown_all').each(function() {
      var a = $(this), finalDate = $(this).data('countdown'), car_id = $(this).data('carid');
      var countDownHot = new Date(finalDate).getTime();
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownHot - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      a.html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");
      // a.html(days + "d " + hours + "h " + minutes + "m ");

      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(y);
        a.html("EXPIRED");
        cancelNegotiation(car_id, finalDate);
      }
     });      

  }, 10000);

  // start countdown hot car
  // Update the count down every 1 second
  var x = setInterval(function() {
      
    // Output the result in an element with id="countdown_list"
    $('.countdown_list').each(function() {
      var a = $(this), finalDate = $(this).data('countdown'), car_id = $(this).data('carid');
      var countDownHot = new Date(finalDate).getTime();
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownHot - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      // a.html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");
      a.html(days + "d " + hours + "h " + minutes + "m ");

      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        a.html("EXPIRED");
        // cancelNegotiation(car_id, finalDate);
      }
     });      

  }, 1000);

  // end countdown

  // start countdown hot car
  // Update the count down every 1 second
  var x = setInterval(function() {
      
    // Output the result in an element with id="countdown_grid"
    $('.countdown_grid').each(function() {
      var a = $(this), finalDate = $(this).data('countdown'), car_id = $(this).data('carid');
      var countDownHot = new Date(finalDate).getTime();
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownHot - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      // a.html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");
      a.html(days + "d " + hours + "h " + minutes + "m ");

      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        a.html("EXPIRED");
        // cancelNegotiation(car_id, finalDate);
      }
     });      

  }, 1000);

  // end countdown

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
