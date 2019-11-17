@extends('layouts.master')

@section('content')
<div class="home-header">

  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <?php
    $i = 1;
    ?>
    @foreach($banner as $banner_detail)
    <?php 
    if($i == 1){
      $active = 'active';
    }else{
      $active = '';
    }?>
    <div class="carousel-item <?php echo $active?>">
      <a href='{{$banner_detail->url}}'>
        <div class='picture-container'>
          <img class="d-block w-100" src="{{ URL::to('/') }}/uploads/banner/{{$banner_detail->picture}}" alt="{{$banner_detail->name}}" width='100%' height='100%'>
        </div>
        </a>
    </div>
    <?php $i++; ?>
    @endforeach
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
  
</div> 

<div class="container body-container">

  <div class="home-selector">
    <form action="{{route('front.gallery')}}" method="get" id="queryForm">
      <input name="r" value="gallery/index" type="hidden">
      <div class="row">
        <div class="col-md-9 col-sm-12 col-xs-12">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="search-div">Search Keyword</div>
              <input class="form-control optimus-input" name="keyword" type="text">
            </div>
            
          </div>
          <div class="row">

            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="search-div">Select Make</div>
              <!-- input class="form-control" name="cars[make]" type="text" value="" -->
              <select id="make" class="form-control optimus-input" name="make">
                <option value="" selected="selected">Select Make</option>
                <?php foreach ($car_filter_option['make'] as $make_detail) { ?>
                  <option value="{{str_slug($make_detail->make,'-')}}" data-id="{{$make_detail->id}}">{{$make_detail->make}}</option>
                <?php } ?>
              </select>		
            </div>
            
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="search-div">Select Model</div>
              <!-- input class="form-control" name="cars[model]" type="text" value="" -->
              <select id="model" class="form-control optimus-input" name="model" disabled="disabled">
                <option value="" selected="selected">-- Model --</option>
              </select>		
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="search-div">Select Body Type</div>
              <!-- input class="form-control" name="cars[type]" type="text" value="" -->
              <select class="form-control optimus-input" name="body_type" id="body_type">
                <option value="" selected="selected">-- Body Type --</option>
                <?php foreach ($car_filter_option['body_type'] as $body_type_detail) { ?>
                  <option data-id="{{$body_type_detail->id}}" value="{{str_slug($body_type_detail->description,'-')}}">{{$body_type_detail->description}}</option>
                <?php } ?>
              </select>		

            </div>
          </div>

        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
          <div class="row">
            <div class="col-12">
              <div class="search-div">Price range (FOB)</div>
              <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"><div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 0%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span></div>
              <div id="price_amount">&nbsp;</div>
              <input class="form-control optimus-input" id="cars_price1" name="price1" value="" type="hidden">
              <input class="form-control optimus-input" id="cars_price2" name="price2" value="" type="hidden">
            </div>

            <div class="col-12">
              &nbsp;
            </div>
            <div class="col-lg-12 checkbox-filter-div">
              <div class="checkbox">
                <input name="new_car_cb" id='checkbox-1' type="checkbox" class='input regular-checkbox'><label for="checkbox-1"></label>  New Car
              </div>
            </div> 
            <div class="col-lg-12 checkbox-filter-div">
              <div class="checkbox">
                <input name="used_car_cb" id='checkbox-2' type="checkbox" class='input regular-checkbox'><label for="checkbox-2"></label>  Used Car
              </div>
            </div> 
            <div class="col-lg-12 checkbox-filter-div">
              <div class="checkbox">
                <input name="promotion_cb" id='checkbox-3' type="checkbox" class='input regular-checkbox'><label for="checkbox-3"></label>  Promotion
              </div>
            </div> 

          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-12">
          <div class="search-div">Select Engine Capacity</div>
          <input class="form-control optimus-input" name="engine_capacity" type="text">
        </div>

        <div class="col-md-3 col-sm-3 col-xs-12">
          <div class="search-div">Start Year</div>
          <!--input class="form-control" name="cars[year2]" type="text" value=""-->

          <select class="form-control optimus-input" name="car_year" id="car_year">
            <option value="" selected="selected">-- Year --</option>
            <?php
            $this_year = date("Y");
            for ($i = $this_year; $i > ($this_year - 10); $i--) {
              ?>
              <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
          </select>		

        </div>
        
        <div class="col-md-3 col-sm-3 col-xs-12">
          <div class="search-div">End Year</div>
          <!--input class="form-control" name="cars[year2]" type="text" value=""-->

          <select class="form-control optimus-input" name="car_end_year" id="car_end_year">
            <option value="" selected="selected">-- Year --</option>
            <?php
            $this_year = date("Y");
            for ($i = $this_year; $i > ($this_year - 10); $i--) {
              ?>
              <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
          </select>		

        </div>
        
        <div class="col-md-3 col-sm-3 col-xs-12 text-center">
          <div class="search-div"></div>
          <button type="submit" class="btn btn-primary search-btn">SEARCH</button>
        </div>
        
      </div>

    </form>

  </div>

  <div class="row">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <div class="row">
    @include('carfilter')
    
    <div class="col-md-9" align="center">
      <?php
      if(isset($session['recently_view']) && count($session['recently_view'])>0){?>
        <span class="recently-div">Recently View</span>
      <?php }?>
      <div class='recently-view-container'>
      <div class="row">
      <?php

      if(isset($session['recently_view']) && count($session['recently_view'])>0){ 
      $arr = array_reverse($session['recently_view']);
      $i =0;
      $j = 0;

      foreach($arr as $detail){ 

        $i = $i+1;
        $j = $j+1;
        if($i === 7){
          echo '</div><div class="row">';
        }
        $picture_arr = json_decode($detail->picture);
        $picture_url = '';
        if(isset($picture_arr[0])){
          $picture_url = URL::to('/').'/uploads/car/'.$picture_arr[0]->picture;
        }
      ?>
      
        <div class="item col-md-2 col-sm-2 col-xs-2">
          <div class="card h-100">
            <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
              <div class='picture-container'>
                <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
              </div>
            </a>
            <div class="card-body">
              <h4 class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
              <p class="card-text">{{$detail->type_description}}</p>
            </div>
            <div class="card-price">
              {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
            </div>
            <div class="card-footer">
              <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
              <a href="#" class="wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
            </div>
          </div>
        </div>
      
            <?php 
            if($j === 12){
              break;
            }
          }          
        }?>
        </div>
      </div>
      
      <ul class="nav justify-content-center" id="cartabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="cartabs1-tab" data-toggle="pill" href="#cartabs1" role="tab">New Arrival</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="cartabs2-tab" data-toggle="pill" href="#cartabs2" role="tab">Top Recommend</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="cartabs3-tab" data-toggle="pill" href="#cartabs3" role="tab">Top Sales</a>
        </li>        
        <li class="nav-item">
          <a class="nav-link" id="cartabs4-tab" data-toggle="pill" href="#cartabs4" role="tab">Clearance Sale</a>
        </li>
      </ul>
      <div class="row">&nbsp;</div>

      <div class="tab-content" id="cartabsContent">
        <div class="tab-pane fade show active" id="cartabs1" role="tabpanel"><!-- html to include -->
          <div class="row" id="hot-car-content">
            @if($car_list['hot_car']['total']>0)
            @foreach($car_list['hot_car']['items'] as $detail)
            @php
              if($detail->picture !== ''){
                $picture_arr = json_decode($detail->picture);
                $picture_url = '';
                if(isset($picture_arr[0])){
                  $picture_url = URL::to('/').'/uploads/car/'.$picture_arr[0]->picture;
                }
              }else{
                continue;
              }
            @endphp
            <div class="item col-md-2 col-sm-2 col-xs-2">
              <div class="card h-100">
                <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                  <div class='picture-container'>
                    <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                  </div>
                </a>
                <div class="card-body">
                  <h4 class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <p class="card-text">{{$detail->type_description}}</p>
                </div>
                <div class="card-price">
                  {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                </div>
                <div class="card-footer">
                  <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
                  <a href="#" class="wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
                </div>
              </div>
            </div>
            @endforeach
            
            @if($car_list['best_deal']['items']>6)
            <div class = 'col-md-12 view-more-div' align='center' width='100%'> <a href='{{route("front.gallery")."?criteria=new-arrival"}}'><button class='btn btn-danger'>View More</button></a> </div> 
            @endif
            
            @else
              <div class="item col-md-12 col-sm-12 col-xs-12 car-not-found-div" align='center'>              
                <b>data is not found</b>
              </div>              
            @endif
          </div>
        </div>
        
        <div class="tab-pane fade" id="cartabs2" role="tabpanel"><!-- html to include -->
          <div class="row" id="top-recommended-content">
            @if($car_list['recommended']['total']>0)
            @foreach($car_list['recommended']['items'] as $detail)
            @php
              if($detail->picture !== ''){
                $picture_arr = json_decode($detail->picture);
                if(isset($picture_arr[0]->picture)){
                  $picture_url = URL::to('/').'/uploads/car/'.$picture_arr[0]->picture;
                }
              }else{
                continue;
              }
            @endphp
            <div class="item col-md-2 col-sm-2 col-xs-2">
              <div class="card h-100">
                <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                  <div class='picture-container'>
                    <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}" style="border-radius:5px;">
                  </div>
                </a>
                <div class="card-body">
                  <h4 class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <p class="card-text">{{$detail->description}}</p>
                </div>
                <div class="card-price">
                  {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                </div>
                <div class="card-footer">
                  <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
                  <a href="#" class="wishlist" onclick="checkAuth(function() {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
                </div>
              </div>
            </div>
            @endforeach
            
            @if($car_list['best_deal']['items']>6)
            <div class = 'col-md-12 view-more-div' align='center' width='100%'> <a href='{{route("front.gallery")."?criteria=recommended"}}'><button class='btn btn-danger'>View More</button></a> </div> 
            @endif
            
            @else
              <div class="item col-md-12 col-sm-12 col-xs-12 car-not-found-div" align='center'>              
                <b>data is not found</b>
              </div>              
            @endif
          </div>
        </div>
        <div class="tab-pane fade" id="cartabs3" role="tabpanel"><!-- html to include -->
          <div class="row" id="best-seller-content">            
            @if($car_list['best_seller']['total']>0)
            @foreach($car_list['best_seller']['items'] as $detail)
            @php
              $picture_arr = isset($detail->picture)&& $detail->picture !== '' ? json_decode($detail->picture) : '';               
            @endphp
            <div class="item col-md-2 col-sm-2 col-xs-2">
              <div class="card h-100">
                <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                  <div class='picture-container'>
                    <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}">
                  </div>
                </a>
                <div class="card-body">
                  <h4 class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <p class="card-text">{{$detail->description}}</p>
                </div>
                <div class="card-price">
                  {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                </div>
                <div class="card-footer">
                  <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
                  <a href="#" class="wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
                </div>
              </div>
            </div>
            @endforeach
            
            @if($car_list['best_deal']['items']>6)
            <div class = 'col-md-12 view-more-div' align='center' width='100%'> <a href='{{route("front.gallery")."?criteria=best-seller"}}'><button class='btn btn-danger'>View More</button></a> </div> 
            @endif
            
            @else
              <div class="item col-md-12 col-sm-12 col-xs-12 car-not-found-div" align='center'>              
                <b>data is not found</b>
              </div>              
            @endif
          </div>
        </div>
        <div class="tab-pane fade" id="cartabs4" role="tabpanel"><!-- html to include -->
          <div class="row" id="best-deal-content">
            @if($car_list['best_deal']['total']>0)
            @foreach($car_list['best_deal']['items'] as $detail)
            @php
              $picture_arr = json_decode($detail->picture);              
            @endphp
            <div class="item col-md-2 col-sm-2 col-xs-2">
              <div class="card h-100">
                <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                  <div class='picture-container'>  
                    <img class="card-img-top" src="{{URL::to('/')}}/uploads/car/{{$picture_arr[0]->picture}}" alt="{{$detail->description}}">
                  </div>
                </a>
                <div class="card-body">
                  <h4 class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <p class="card-text">{{$detail->description}}</p>
                </div>
                <div class="card-price">
                  {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                </div>
                <div class="card-footer">
                  <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
                  <a href="#" class="wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
                </div>
              </div>
            </div>
            @endforeach
            
            @if($car_list['best_deal']['items']>6)
            <div class = 'col-md-12 view-more-div' align='center' width='100%'> <a href='{{route("front.gallery")."?criteria=clearance-sale"}}'><button class='btn btn-danger'>View More</button></a> </div> 
            @endif
            
            @else
              <div class="item col-md-12 col-sm-12 col-xs-12 car-not-found-div" align='center'>              
                <b>data is not found</b>
              </div>              
            @endif
          </div>
        </div>
      </div>
    </div>



  </div>
  <!-- /.col-lg-9 -->

</div>
<!-- /.row -->

@stop


@section('script')
<script>  
  $("#make").select2();
  $("#model").select2();
  $("#body_type").select2();
  $("#engine_capacity").select2();
  $("#car_year").select2();
  $('.carousel').carousel();        
  
  function getCar(criteria){
    var requestData = {
      criteria : criteria,
    };
    
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': token,
        },
        url: "{{route('front.getCar')}}",
        data: requestData,
        type: 'post',
        dataType: 'json',
        beforeSend:function(){
        },
        success: function (result) {
        }
    });
  }
  
  $("#make").on('change',function(){    
    var requestData = {
      make_id : $("#make option:selected").attr('data-id'),
      make : $("#make").val(),
    };
    
    $.ajax({
        headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
        url: "{{route('front.getCarModel')}}",
        data: requestData,
        type: 'post',
        dataType: 'json',
        beforeSend:function(){
        },
        success: function (result) {
          var select_template = '<option value="" selected="selected">-- Model --</option>';
          for (x in result){
            select_template += '<option value="'+ result[x].id +'">'+ result[x].model +'</option>';
          }
          $("#model").html(select_template);
          $("#model").prop('disabled',false);
        }
    }); 
  })

  function popError(error) {
    $("#popError .alert").html(error);
    $('#popError').modal('show');
    return false;
  }

  $(function () {
    $("#slider-range").slider({
      range: true,
      min: 0,
      max: "{{$car_max_price}}",
      step: 500,
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
