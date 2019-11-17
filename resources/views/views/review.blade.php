@extends('layouts.master')

@section('content')
<div class="container">

  <div class='title'>
    
  </div> 

  <div class='comment'>
    <h1>
      Review
    </h1>
  
    <div class='container'>
      <div class='row'>
        <div class='col-md-12 col-xs-12'>
          How accurate was the item description 
          <?php
            if(isset($accuration_rating)){
            for($i=1;$i<=$accuration_rating;$i++){?>
              <img src="{{URL::to('/')}}/assets/images/yellow-star.png" alt="star">
          <?php }
            $blank_star = 5 - $accuration_rating;

            for($i=1;$i<=$blank_star;$i++){?>
            <img src="{{URL::to('/')}}/assets/images/star.png" alt="star">
          <?php }
          }
          ?>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12 col-xs-12'>
          How satisfied were you with the seller's communication 
          <?php
            if(isset($satisfication_rating)){
            for($i=1;$i<=$satisfication_rating;$i++){?>
              <img src="{{URL::to('/')}}/assets/images/yellow-star.png" alt="star">
          <?php }
            $blank_star = 5 - $satisfication_rating;

            for($i=1;$i<=$blank_star;$i++){?>
            <img src="{{URL::to('/')}}/assets/images/star.png" alt="star">
          <?php }
          }
          ?>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12 col-xs-12'>
          How quickly did the seller ship the item 
          <?php
            if(isset($speed_rating)){
            for($i=1;$i<=$speed_rating;$i++){?>
              <img src="{{URL::to('/')}}/assets/images/yellow-star.png" alt="star">
          <?php }
            $blank_star = 5 - $speed_rating;

            for($i=1;$i<=$blank_star;$i++){?>
            <img src="{{URL::to('/')}}/assets/images/star.png" alt="star">
          <?php }
          }
          ?>
        </div>
      </div>
    </div>
    
  </div>
  
  <div class='height50'></div>
  <div class='filter'>
    <div class='row'>
      <div class='col-md-12 col-xs-12'>
        <b>Filter By</b>
      </div>
    </div>
    <div class='row'>
      <div class='col-md-3 col-xs-12'>
        <select id='country' class='form-control optimus-input'>
          <option value="all" selected="selected">Country(All)</option>
        <?php foreach ($car_filter_option['country'] as $country_detail) { ?>
          @if($selected_filter['country_code'] == $country_detail->country_code)
            <option value="{{$country_detail->country_code}}" selected>{{$country_detail->country_name}}</option>
          @else
            <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
          @endif  
        <?php } ?>
        </select>
      </div>
      <div class='col-md-3 col-xs-12'>
        <select id="make" class="form-control optimus-input" name="make">
          <option value="all" selected="selected">Make(All)</option>
          <?php foreach ($car_filter_option['make'] as $make_detail) { ?>
          @if($selected_filter['make_id'] == $make_detail->id)
            <option value="{{str_slug($make_detail->make,'-')}}" data-id="{{$make_detail->id}}" selected>{{$make_detail->make}}</option>
          @else
            <option value="{{str_slug($make_detail->make,'-')}}" data-id="{{$make_detail->id}}">{{$make_detail->make}}</option>
          @endif  
          <?php } ?>
        </select>
      </div>
      <div class='col-md-3 col-xs-12'>
        <select id="model" class="form-control optimus-input" name="model">
          <option value="all" selected="selected">Model(All)</option>
          <?php foreach ($car_filter_option['model'] as $model_detail) { ?>
          @if($selected_filter['model_id'] == $model_detail->id)
            <option value="{{$model_detail->id}}" selected>{{$model_detail->model}}</option>
          @else
            <option value="{{$model_detail->id}}">{{$model_detail->model}}</option>
          @endif  
          <?php } ?>
        </select>
      </div>
      <div class='col-md-3 col-xs-12'>
        <button class='btn btn-danger search-btn'>Search</button>
      </div>
    </div>
  </div>
  
  <div><hr></div>
  @if($ratings['total'] > 0)
  <div class='result'>
    <div class='row'>
      <div class='col-md-12'>
      Showing {{((($page - 1) * $limit) + 1)}} - {{(($page - 1) * $limit) + count($ratings['data'])}} of {{$ratings['total']}} reviews
      </div>
    </div>
    <div class='row'>
      <div class='col-md-12'>

        @if($page > 1)
          <button class='btn btn-normal' onclick="window.location='{{url()->current().'?page=1'}}'"><<</button>
        @endif
        
        @if($page - 1 > 0)
          <button class='btn btn-normal' onclick="window.location='{{url()->current().'?page='.($page-1)}}'">{{$page-1}}</button>
        @endif
        
        <button class='btn btn-danger'>{{$page}}</button>

        @if($page+1 <= $ratings['total_page'])
          <button class='btn btn-normal' onclick="window.location='{{url()->current().'?page='.($page+1)}}'">{{$page+1}}</button>
        @endif
        
        @if($ratings['total_page'] != $page)
          <button class='btn btn-normal' onclick="window.location='{{url()->current().'?page='.$ratings['total_page']}}'">>></button>
        @endif
      </div>
    </div>
    <div class="row">
      <div class='col-md-12'>
        <hr>
      </div>
    </div>

    @if(count($ratings['data'])>0)
    @foreach($ratings['data'] as $data)
    <div class="row">
      <div class='col-md-4 col-xs-4'>
        @if($data->profile_picture !== '' && $data->profile_picture !== null)
          <img src='{{URL::to('/')}}/uploads/customer/{{$data->profile_picture}}' width='150px'>
        @else
          <img src='{{URL::to('/')}}/assets/images/no_photo.gif' width='150px'>
        @endif
      </div>
      <div class='col-md-8 col-xs-8'>
        <table width='100%'>
          <tr>
            <td><h2>{{$data->make}} {{$data->model}}</h2></td>
          </tr>
          <tr>
            <td>
              How accurate was the item description 
              <?php
                for($i=1;$i<=$data->accuration;$i++){?>
                  <img src="{{URL::to('/')}}/assets/images/yellow-star.png" alt="star">
              <?php }
                $blank_star = 5 - $data->accuration;

                for($i=1;$i<=$blank_star;$i++){?>
                <img src="{{URL::to('/')}}/assets/images/star.png" alt="star">
              <?php }
              ?>
            </td>
          </tr>
          <tr>
            <td>
              How satisfied were you with the seller's communication 
              <?php
                for($i=1;$i<=$data->satisfication;$i++){?>
                  <img src="{{URL::to('/')}}/assets/images/yellow-star.png" alt="star">
              <?php }
                $blank_star = 5 - $data->satisfication;

                for($i=1;$i<=$blank_star;$i++){?>
                <img src="{{URL::to('/')}}/assets/images/star.png" alt="star">
              <?php }
              ?>
            </td>
          </tr>
          <tr>
            <td>
              How quickly did the seller ship the item 
              <?php
                for($i=1;$i<=$data->speed;$i++){?>
                  <img src="{{URL::to('/')}}/assets/images/yellow-star.png" alt="star">
              <?php }
                $blank_star = 5 - $data->speed;

                for($i=1;$i<=$blank_star;$i++){?>
                <img src="{{URL::to('/')}}/assets/images/star.png" alt="star">
              <?php }
              ?>
            </td>
          </tr>
          
          <tr>
            <td>
              <br>
              by {{$data->name}} ({{$data->country_name}}) on {{$data->createdon !== null ? date( "d M Y", strtotime($data->createdon)) : ''}}
            </td>
          </tr>
          <tr>
            <td class='height30'>
              <br>
            </td>
          </tr>
          <tr>
            <td>
              {{$data->comment}}
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="row">
      <div class='col-md-12'>
        <hr>
      </div>
    </div>
    @endforeach
    @endif
  </div>
  @else
    <div class='result'>
      <div class='row'>
        <div class='col-md-12'>
          Not find any result
        </div>
      </div>
    </div>
  @endif
</div>
</div>
@stop


@section('script')
  <script>
    var url = "{{route('front.review')}}";
    $("#country").select2();
    $("#make").select2();
    $("#model").select2();
    
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
          var select_template = '<option value="" selected="selected">Model(All)</option>';
          for (x in result){
            select_template += '<option value="'+ result[x].id +'">'+ result[x].model +'</option>';
          }
          $("#model").html(select_template);
          $("#model").prop('disabled',false);
        }
    }); 
  })
  
  $(".search-btn").on('click',function(){
      var make_id = typeof $("#make option:selected").attr('data-id') !== 'undefined' ? $("#make option:selected").attr('data-id') : 'all';
      var model_id = $("#model").val() != '' ? $("#model").val() : 'all';
      var params = $("#country").val() + '/' + make_id + '/' + model_id;
      var review_url = url;
      if(params !== 'all/all/all'){
        review_url += '/' + params;
      }
      
      window.location = review_url;
    })
  </script>
@stop