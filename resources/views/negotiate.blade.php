@extends('layouts.master')

@section('content')
<div class="row">
  @include('carfilter') 

  <div class="col-md-9 gallery-content">

    <div class="row">
      <div class='col-md-12'>
        <?php if(isset($_GET['make']) && count($car)>0){?>
          <div class='brand-logo'>
            <img src="{{URL::to('/')}}/uploads/car_logo/{{$car[0]->logo}}">
          </div>
        <?php }?>
      </div>
    </div>

    
    
    <div class="row">
      <?php
      $i = 0;
      ?>
      @if(count($car)>0)
      @foreach($car as $detail)
      <?php
      $i++;
      if($i === 7){
        $i=0;
        echo '</div><div class="row">';
      }
      ?>
      <div class="item col-lg-2 col-md-6 mb-4" data-key="1">
        <div class="card h-100">
          <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
          <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$detail->picture}}" alt="{{$detail->description}}">
          </a>
          <div class="card-body">
            <h4 class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
            <div>type : {{$detail->type_description}}</div>
            <p class="card-text">{{$detail->description}}</p>
            <p class="price">{{$detail->currency}} {{$detail->price}}</p>
          </div>
          <div class="card-footer">
            <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
            <a href="#" class="wishlist" onclick="checkAuth('{{route('front.wishlist',['car_id'=>$detail->id])}}');return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
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
    <div class='row'>
      <?php 
      $this_page = isset($_GET['page'])?$_GET['page']:1;
      $limit = 12;
      $total_page = ceil($total_car/$limit);
      
      ?>
      
      @if($total_car >0)
      <div class='col-md-12' align='center'>
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
    </div>
  </div>



</div>
<!-- /.col-lg-9 -->

@stop


@section('script')
<script>
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
  });
  $("#departure_city").chained("#departure_country");
  $("#discharge_city").chained("#discharge_country");
  $("#car_model").chained("#car_make");

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
  }


  if ($("#gallery-result").length > 0) {

  }

  if ($("#detailtabs").length > 0) {
    $("#detailtabs").tabs();
  }

  if ($("#logintabs").length > 0) {
    $("#logintabs").tabs();
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
