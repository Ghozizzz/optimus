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
          <div class="search-div top30"></div>
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
    <input type="hidden" name="is_recently_view" id="is_recently_view" value="{{ (isset($session['recently_view'])) ? '1' : '0' }}">
    
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
      
        <div class="item col-md-3 col-sm-3 col-xs-3">
          <div class="card h-100">
            <?php if ($detail->flag_payment == 0) { ?>
              <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                <div class='picture-container'>
                  <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                </div>
              </a>
            <?php } else { ?>
              <?php if (Session::get('user_id') !== null) { ?>
                <a href="javascript:;" onclick="notify({{ $detail->id }})">
                  <div class='picture-container'>
                    <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                  </div>
                </a>
              <?php } else { ?>
                <a href="javascript:;">
                  <div class='picture-container' data-toggle="modal" data-target="#popSelectLogin">
                    <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                  </div>
                </a>
              <?php } ?>
            <?php } ?>
            <div class="card-body">
              <?php if ($detail->flag_payment == 0) { ?>
                <h4 style="margin-bottom: 0px;" class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
              <?php } else { ?>
                <?php if (Session::get('user_id') !== null) { ?>
                  <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" onclick="notify({{ $detail->id }})">{{$detail->make}} - {{$detail->model}}</a></h4>
                <?php } else { ?>
                  <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" data-toggle="modal" data-target="#popSelectLogin">{{$detail->make}} - {{$detail->model}}</a></h4>
                <?php } ?>
              <?php } ?>
              <p class="card-text">{{$detail->type_description}}</p>
            </div>
            <div class="card-price">
              <?php if($detail->flag_payment == 0) { ?>
                {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
              <?php } else { ?>
                  <!-- UNDER OFFER<br> -->
                  <font size="2px">Available In :<br></font>
                  <input type="hidden" name="due_date_recently" id="due_date_recently" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
                  <font class="countdown_recently" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}"></font>
                  <br>
                  <!-- <font size="1px">
                    NOTIFY WHEN AVAILABLE
                  </font> -->
              <?php } ?>
            </div>
            <div class="card-footer" style="padding: 3px;">
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
      
            <?php 
            if($j === 12){
              break;
            }
          }          
        }//recently view if
        ?>
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
      <div id="all_car" style="display: none;">
        @if($car_list['all_car']['total'] > 0)
          @foreach($car_list['all_car']['items'] as $detail)
            <?php 

    if(isset($session['recently_view']) && count($session['recently_view'])>0){ 
          if($detail->flag_payment == 1) { ?>
              <font class="countdown_all" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
            <?php } 
    }?>
          @endforeach
        @endif
      </div>
      <div class="tab-content" id="cartabsContent">
        <div class="tab-pane fade show active" id="cartabs1" role="tabpanel"><!-- html to include -->
          <div class="row" id="hot-car-content">
            @if($car_list['hot_car']['total']>0)
            @foreach($car_list['hot_car']['items'] as $key => $detail)
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
            <div class="item col-md-3 col-sm-3 col-xs-3">
              <div class="card h-100">
                <?php 

    if(isset($session['recently_view']) && count($session['recently_view'])>0){ 
                if ($detail->flag_payment == 0) { ?>
                  <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                    <div class='picture-container'>
                      <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                    </div>
                  </a>
                <?php } else { ?>
                  <?php if (Session::get('user_id') !== null) { ?>
                    <a href="javascript:;" onclick="notify({{ $detail->id }})">
                      <div class='picture-container'>
                        <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                      </div>
                    </a>
                  <?php } else { ?>
                    <a href="javascript:;">
                      <div class='picture-container' data-toggle="modal" data-target="#popSelectLogin">
                        <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
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
                  <?php } 
                }//recently view end if?>
                  <p class="card-text">{{$detail->type_description}}</p>
                </div>
                <div class="card-price" style="padding-top: 0">
                  <?php 
    if(isset($session['recently_view']) && count($session['recently_view'])>0){ 
      if($detail->flag_payment == 0) { ?>
                    {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                  <?php } else { ?>
                    <!-- UNDER OFFER<br> -->
                    <font size="2px">Available In :</font>
                    <input type="hidden" name="due_date_hot" id="due_date_hot" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
                    <font class="countdown_hot" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
                    <br>
                    <!-- <font size="1px">
                      NOTIFY WHEN AVAILABLE
                    </font> -->
                  <?php } 
                }?>
                </div>
                <div class="card-footer">
                  <?php 

    if(isset($session['recently_view']) && count($session['recently_view'])>0){ 
                  if($detail->flag_payment == 0) { ?>
                    <a class="btn btn-primary" style="padding: 8px;" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
                  <?php } else { ?>
                    <?php if(Session::get('user_id') !== null) { ?>
                      <a href="javascript:;" onclick="notify({{ $detail->id }})" class="btn btn-info"><p style="line-height:1.5; font-size: 9px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a>
                    <?php } else { ?>
                      <a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target="#popSelectLogin"><p style="line-height:1.5; font-size: 9px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a>
                    <?php } ?>
                  <?php } 
                }//recenty view end if?>
                  <a href="#" class="wishlist" onclick="checkAuth(function () {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
                </div>
              </div>
            </div>
            @endforeach
            
            @if($car_list['best_deal']['items']>4)
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
            <div class="item col-md-3 col-sm-3 col-xs-3">
              <div class="card h-100">
                <?php 
    if(isset($session['recently_view']) && count($session['recently_view'])>0){ 
      if ($detail->flag_payment == 0) { ?>
                  <a href="{{route('front.productdetail',['id'=>$detail->id])}}">
                    <div class='picture-container'>
                      <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                    </div>
                  </a>
                <?php } else { ?>
                  <?php if (Session::get('user_id') !== null) { ?>
                    <a href="javascript:;" onclick="notify({{ $detail->id }})">
                      <div class='picture-container'>
                        <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                      </div>
                    </a>
                  <?php } else { ?>
                    <a href="javascript:;">
                      <div class='picture-container' data-toggle="modal" data-target="#popSelectLogin">
                        <img class="card-img-top" src="{{$picture_url}}" alt="{{$detail->description}}" style="border-radius:5px;">
                      </div>
                    </a>
                  <?php } ?>
                <?php } 
              }//recently view end if

    if(isset($session['recently_view']) && count($session['recently_view'])>0){ ?>
                <div class="card-body">
                  <?php if ($detail->flag_payment == 0) { ?>
                    <h4 style="margin-bottom: 0px;" class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <?php } else { ?>
                    <?php if (Session::get('user_id') !== null) { ?>
                      <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" onclick="notify({{ $detail->id }})">{{$detail->make}} - {{$detail->model}}</a></h4>
                    <?php } else { ?>
                      <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" data-toggle="modal" data-target="#popSelectLogin">{{$detail->make}} - {{$detail->model}}</a></h4>
                    <?php } ?>
                  <?php } ?>
                  <p class="card-text">{{$detail->type_description}}</p>
                </div>
                <div class="card-price">
                  <?php if($detail->flag_payment == 0) { ?>
                    {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                  <?php } else { ?>
                    <!-- UNDER OFFER<br> -->
                    <font size="2px">Available In :<br></font>
                    <input type="hidden" name="due_date_recommended" id="due_date_recommended" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
                    <font class="countdown_recommended" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
                    <br>
                    <!-- <font size="1px">
                      NOTIFY WHEN AVAILABLE
                    </font> -->
                  <?php } ?>
                </div>
                <div class="card-footer" style="padding: 3px;">
                  <?php if($detail->flag_payment == 0) { ?>
                    <a class="btn btn-primary" style="padding: 8px;" href="{{route('front.productdetail',['id'=>$detail->id])}}">View</a>
                  <?php } else { ?>
                    <?php if(Session::get('user_id') !== null) { ?>
                      <a href="javascript:;" onclick="notify({{ $detail->id }})" class="btn btn-info"><p style="line-height:1.5; font-size: 9px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a>
                    <?php } else { ?>
                      <a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target="#popSelectLogin"><p style="line-height:1.5; font-size: 9px; margin: 2px 0;">NOTIFY ME<br>WHEN AVAILABLE</p></a>
                    <?php } ?>
                  <?php } ?>
                  <a href="#" class="wishlist" onclick="checkAuth(function() {addWishlist('{{$detail->id}}')});return false;"> &nbsp;&nbsp;<i class="fas fa-heart"></i></a>
                </div>
            <?php } ?>
              </div>
            }
            </div>
            @endforeach
            
            @if($car_list['best_deal']['items']>4)
            <div class = 'col-md-12 view-more-div' align='center' width='100%'> <a href='{{route("front.gallery")."?criteria=recommended"}}'><button class='btn btn-danger'>View More</button></a> </div> 
            @endif
            
            @else
              <div class="item col-md-12 col-sm-12 col-xs-12 car-not-found-div" align='center'>              
                <b>data is not found</b>
              </div>              
            @endif
          </div>
        </div>
      <?Php 
    if(isset($session['recently_view']) && count($session['recently_view'])>0){ ?>
        <div class="tab-pane fade" id="cartabs3" role="tabpanel"><!-- html to include -->
          <div class="row" id="best-seller-content">            
            @if($car_list['best_seller']['total']>0)
            @foreach($car_list['best_seller']['items'] as $detail)
            @php
              $picture_arr = isset($detail->picture)&& $detail->picture !== '' ? json_decode($detail->picture) : '';               
            @endphp
            <div class="item col-md-3 col-sm-3 col-xs-3">
              <div class="card h-100">
                <?php 
      if ($detail->flag_payment == 0) { ?>
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
                    <h4 style="margin-bottom: 0px;" class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <?php } else { ?>
                    <?php if (Session::get('user_id') !== null) { ?>
                      <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" onclick="notify({{ $detail->id }})">{{$detail->make}} - {{$detail->model}}</a></h4>
                    <?php } else { ?>
                      <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" data-toggle="modal" data-target="#popSelectLogin">{{$detail->make}} - {{$detail->model}}</a></h4>
                    <?php } ?>
                  <?php } ?>
                  <p class="card-text">{{$detail->type_description}}</p>
                </div>
                <div class="card-price">
                  <?php if($detail->flag_payment == 0) { ?>
                    {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                  <?php } else { ?>
                    <!-- UNDER OFFER<br> -->
                    <font size="2px">Available In :<br></font>
                    <input type="hidden" name="due_date_best_seller" id="due_date_best_seller" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
                    <font class="countdown_best_seller" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
                    <br>
                    <!-- <font size="1px">
                      NOTIFY WHEN AVAILABLE
                    </font> -->
                  <?php } ?>
                </div>
                <div class="card-footer" style="padding: 3px;">
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
            
            @if($car_list['best_deal']['items']>4)
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
            <div class="item col-md-3 col-sm-3 col-xs-3">
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
                    <h4 style="margin-bottom: 0px;" class="card-title"><a href="{{route('front.productdetail',['id'=>$detail->id])}}">{{$detail->make}} - {{$detail->model}}</a></h4>
                  <?php } else { ?>
                    <?php if (Session::get('user_id') !== null) { ?>
                      <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" onclick="notify({{ $detail->id }})">{{$detail->make}} - {{$detail->model}}</a></h4>
                    <?php } else { ?>
                      <h4 style="margin-bottom: 0px;" class="card-title"><a href="javascript:;" data-toggle="modal" data-target="#popSelectLogin">{{$detail->make}} - {{$detail->model}}</a></h4>
                    <?php } ?>
                  <?php } ?>
                  <p class="card-text">{{$detail->type_description}}</p>
                </div>
                <div class="card-price">
                  <?php if($detail->flag_payment == 0) { ?>
                    {{$detail->currency}} {{\App\Http\Controllers\API::currency_format($detail->price)}}
                  <?php } else { ?>
                    <!-- UNDER OFFER<br> -->
                    <font size="2px">Available In :<br></font>
                    <input type="hidden" name="due_date_best_deal" id="due_date_best_deal" value="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}">
                    <font class="countdown_best_deal" data-countdown="{{ date('Y-m-d', strtotime($detail->due_date.' + 1 days')) }}" data-carid="{{ $detail->id }}"></font>
                    <br>
                    <!-- <font size="1px">
                      NOTIFY WHEN AVAILABLE
                    </font> -->
                  <?php } ?>
                </div>
                <div class="card-footer" style="padding: 3px;">
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
            
            @if($car_list['best_deal']['items']>4)
            <div class = 'col-md-12 view-more-div' align='center' width='100%'> <a href='{{route("front.gallery")."?criteria=clearance-sale"}}'><button class='btn btn-danger'>View More</button></a> </div> 
            @endif
            
            @else
              <div class="item col-md-12 col-sm-12 col-xs-12 car-not-found-div" align='center'>              
                <b>data is not found</b>
              </div>              
            @endif
          </div>
        </div>
      <?php }//recently view end if ?>
      </div>
    </div>



  </div>
  <!-- /.col-lg-9 -->

</div>
<!-- /.row -->

@stop


@section('script')
<script>  
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

  // y untuk countdown all car
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
      // a.html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");
      a.html(days + "d " + hours + "h " + minutes + "m ");

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
      
    // Output the result in an element with id="countdown_hot"
    $('.countdown_hot').each(function() {
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

  // start countdown recommended car
  // Set the date we're counting down to
  // var countDownDateRecommended = new Date($('#due_date_recommended').val()).getTime();

  // Update the count down every 1 second
  var x = setInterval(function() {

    // Output the result in an element with id="countdown_recommended"
    $('.countdown_recommended').each(function() {
      var a = $(this), finalDate = $(this).data('countdown'), car_id = $(this).data('carid');
      var countDownRecommended = new Date(finalDate).getTime();
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownRecommended - now;
        
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
        // document.getElementById("countdown_recommended").innerHTML = "EXPIRED";
        a.html("EXPIRED");
        // cancelNegotiation(car_id, finalDate);
      }
    });
  }, 1000);

  // end countdown

  // start countdown best seller
  // Update the count down every 1 second
  var x = setInterval(function() {

    // Output the result in an element with id="countdown_best_seller"
    $('.countdown_best_seller').each(function() {
      var a = $(this), finalDate = $(this).data('countdown'), car_id = $(this).data('carid');
      var countDownBestSeller = new Date(finalDate).getTime();
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownBestSeller - now;
        
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
        // document.getElementById("countdown_best_seller").innerHTML = "EXPIRED";
        a.html("EXPIRED");
        // cancelNegotiation(car_id, finalDate);
      }
    });
  }, 1000);

  // end countdown

  // start countdown best deal

  // Update the count down every 1 second
  var x = setInterval(function() {

    // Output the result in an element with id="countdown_best_deal"
    $('.countdown_best_deal').each(function() {
      var a = $(this), finalDate = $(this).data('countdown'), car_id = $(this).data('carid');
      var countDownBestDeal = new Date(finalDate).getTime();
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownBestDeal - now;
        
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
        // document.getElementById("countdown_best_deal").innerHTML = "EXPIRED";
        a.html("EXPIRED");
        // cancelNegotiation(car_id, finalDate);
      }
    });
  }, 1000);

  // end countdown



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
    if ($('#is_recently_view').val() == 1) {
      // start countdown recently view
      // Update the count down every 1 second
      var x = setInterval(function() {
        $('.countdown_recently').each(function() {
          var a = $(this), finalDate = $(this).data('countdown');
          var countDownRecently = new Date(finalDate).getTime();
          var now = new Date().getTime();
        
          // Find the distance between now and the count down date
          var distance = countDownRecently - now;
            
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
          }
        });
      }, 1000);

      // end countdown
    }

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
