@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
{!! Html::style('/assets/css/select2.min.css') !!}
<style type="text/css" media="screen">
  .viewInvoice{
    padding-left: 5%;
  }
  #editModal{
    padding-left: 25%;
    padding-right: 25%;
    padding-top: 10%;
  }

</style>
@stop
@section('content')
<div class="full-container">
  <div class="row">
    <div class="col-md-2 left-menu-div">

      @include('admin::layouts.menu')
    </div>
<?php
  $thispage = isset($_GET['page'])?$_GET['page'] : 1;
  $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
  $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
  $qs['filter'] = isset($_GET['filter']) ? str_replace('-',' ',$_GET['filter']) : '';
  $qs['max'] = $getMax;
  
  $current_url = Request::fullUrl();
  $explode = explode('?', $current_url);
  $web_url = $explode[0];
  
	$total_item = $db['total'];
	$limit = isset($_GET['max'])?$_GET['max']:5;
  if($limit == 'all'){
    $total_page = 1;
    $i = 0;
  }else{
    $total_page = ceil($total_item / $limit);
    $i = ($thispage - 1) * $limit;
  }
?>
<div class="col-md-10 right-menu-div">
  <br>
<h5>Review</h5>
<h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

      <div class='row'>
        <div class='col-md-3 col-xs-12' align="center">
          <select id="maxpage" class='custom-select'>
      <?php
        $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
        $qs['status'] = isset($_GET['status']) ? $_GET['status'] : '';
      ?>
      <option <?php if($getMax === "5") echo 'selected' ?> 
        <?php 
        $qs_tmp = $qs; 
        $qs_tmp['max'] = 5; 
        ?> 
        value="{{ $web_url . '?' . http_build_query($qs_tmp) }}">5</option>
      <option <?php if($getMax === "10") echo 'selected' ?> 
        <?php 
        $qs_tmp = $qs;
        $qs_tmp['max'] = 10; ?> 
        value="{{ $web_url . '?' . http_build_query($qs_tmp) }}">10</option>
      <option <?php if($getMax === "20") echo 'selected' ?> 
        <?php 
        $qs_tmp = $qs;
        $qs_tmp['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($qs_tmp) }}">20</option>
      <option <?php if($getMax === "all") echo 'selected' ?> 
        <?php $qs_tmp['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($qs_tmp) }}">All</option>
    </select>
  </div>
        
  <div class="col-md-5 col-xs-12 input-group" align='center'>
    <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
    <div class="input-group-append">
      <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
    </div>
  </div>
  
        <div class='col-md-2 col-xs-12' align='center'>
          <select id="descasc" class='custom-select'>
            <option <?php if($qs['descasc'] === "desc") echo 'selected' ?> 
            <?php 
              $qs_temp = $qs;
              $qs_temp['descasc'] = 'desc' 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">New-old</option>
            <option <?php if($qs['descasc'] === "asc") echo 'selected' ?> 
              <?php 
              $qs_temp = $qs;
              $qs_temp['descasc'] = 'asc' 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">Old-new</option>
          </select>
        </div>

        
      </div>
      <div class="row">
        <table class="table table-striped">
          <thead>
            <tr>
              <th width='5%'>No</th>
              <th width='10%'>Invoice Number</th>
              <th width='50%'>Comment</th>
              <th>Status</th>
              <th colspan="3">Action</th>
            </tr>
          </thead>
          <tbody>
            @if(count($db['data'])>0)
            @foreach($db['data'] as $d => $v)
              @if(in_array($v->invoice_id, $moderated_review))
                <tr class='unread-negotiation'>
              @else 
                <tr>
              @endif
              <td>{{$i+1}}</td>
              <td>{{$v->invoice_number}}</td>
              <td>{!! addslashes($v->comment) !!}</td>
             
              @if($v->isdisplayed == '0')
              <td>
                {{'not moderated'}}
              </td>
              @elseif($v->isdisplayed == '1')
                <td>
                  {{'displayed'}}
                </td>
              @else  
                <td>
                  {{'not displayed'}}
                </td>
              @endif
              
              <td><button 
                  class='btn btn-danger' 
                  data-invoice_id='{{$v->invoice_id}}' 
                  data-comment='{!! addslashes($v->comment) !!}'
                  data-speed='{{$v->speed}}'
                  data-accuration='{{$v->accuration}}'
                  data-satisfication='{{$v->satisfication}}'
                  onclick='reviewDetail($(this))'>View</button>
              </td>
              
              @if($v->isdisplayed == '0')
              <td>
                <button class='btn btn-danger bottom10 mini-act-btn' onclick='changeReviewStatus(1,{{$v->invoice_id}})' data-invoice_id ='{{$v->invoice_id}}'>Accept</button> 
                <button class='btn btn-success bottom10 mini-act-btn' onclick='changeReviewStatus(2,{{$v->invoice_id}})' data-invoice_id ='{{$v->invoice_id}}'>reject</button>
              </td>
              @elseif($v->isdisplayed == '1')
                <td><button class='btn btn-success bottom10 mini-act-btn' onclick='changeReviewStatus(2,{{$v->invoice_id}})' data-invoice_id ='{{$v->invoice_id}}'>reject</button></td>
              @else  
                <td><button class='btn btn-danger bottom10 mini-act-btn' onclick='changeReviewStatus(1,{{$v->invoice_id}})' data-invoice_id ='{{$v->invoice_id}}'>Accept</button></td>
              @endif
              
            </tr>
            <?php $i++ ?>
            @endforeach
            @endif
          </tbody>
        </table>
      </div>

      <div class='row'>
        <div class='col-md-12 bottom10' align="center">
          <caption>
            <?php if($thispage>1){
              $qs['page'] = $thispage-1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              ?>
              <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class='btn btn-danger'>Prev</button></a> 
          <?php }?>
      <?php if($thispage < $total_page){
            $qs['page'] = $thispage+1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';              
        ?>
            <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="next"><button class='btn btn-danger'>Next</button></a>
          </caption>
      <?php }?>     
        </div>
      </div>

    </div>

  </div>
</div>
  
<div class="modal fade" id="rate_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <div class="row">
          <div class="height50"></div>
        </div>
        <div class="row">
          <div class="col-md-4">How accurate was the item description</div>
          <div id="accuration_rating">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">How satisfied were you with the seller's communication</div>
          <div id="satisfication_rating">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">How quickly did the seller ship the item</div>
          <div id="speed_rating">
          </div>
        </div>
        <div class="text-center">
          <div class=''>
              <textarea id="rating-comment" class='form-control' readonly="true"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary close-btn">close</button>
      </div>
    </div>
  </div>
</div>

@stop
@section('script')
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="{{URL::to('/')}}/assets/js/admin.js"></script>
<script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
<script>
  var web_url = '{{$web_url}}';
  function convertDate(inputFormat) {
    function pad(s) { return (s < 10) ? '0' + s : s; }
    var d = new Date(inputFormat);
    return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
  }

  function changeReviewStatus(status, invoice_id){
    
    if(status == 1){
      var msg = 'Are you sure want to display this review?';
    }else if(status == 2){
      var msg = 'Are you sure want to not display this review?';
    }
    var response = confirm(msg);

    if(response){     
   
      var data = new FormData();
      data.append('status', status);
      data.append('invoice_id', invoice_id);
      $.ajax({  
          url: "{{ URL::to('/') }}/admin/review/update",  
          type: "POST",  
          data:  data,
          processData: false,  
          contentType: false, 
          success: function (response) {
            location.reload();
          },
          error: function(error) {
              console.log(error);
          }
      });
      
    }
    
  }
  
  function reviewDetail(val){    
    $("#rate_modal").modal('show');
    speed_rating = val.data('speed');
    $("#speed_rating").html('');
    for($i=1;$i<=speed_rating;$i++){
      $("#speed_rating").append("<span class='green'><i class='fa fa-star'></i></span>");
    }
    for($i=(speed_rating+1);$i<=5;$i++){
      $("#speed_rating").append("<span><i class='fa fa-star'></i></span>");
    }
    
    satisfication_rating = val.data('satisfication');
    $("#satisfication_rating").html('');
    for($i=1;$i<=satisfication_rating;$i++){
      $("#satisfication_rating").append("<span class='green'><i class='fa fa-star'></i></span>");
    }
    for($i=(satisfication_rating+1);$i<=5;$i++){
      $("#satisfication_rating").append("<span><i class='fa fa-star'></i></span>");
    }
    
    accuration_rating = val.data('accuration');
    $("#accuration_rating").html('');
    for($i=1;$i<=accuration_rating;$i++){
      $("#accuration_rating").append("<span class='green'><i class='fa fa-star'></i></span>");
    }
    for($i=(accuration_rating+1);$i<=5;$i++){
      $("#accuration_rating").append("<span><i class='fa fa-star'></i></span>");
    }
    
    $("#rating-comment").val(val.data('comment'));
    ratingReadonly();
  }
  
  function ratingReadonly() {
      $('#speed-rating-bar').barrating({
          theme: 'bootstrap-stars',
          showSelectedRating: false,
          readonly:true,
      });
      $('#satisfication-rating-bar').barrating({
          theme: 'bootstrap-stars',
          showSelectedRating: false,
          readonly:true,
      });
      $('#accuration-rating-bar').barrating({
          theme: 'bootstrap-stars',
          showSelectedRating: false,
          readonly:true,
      });
  }
  
  $(".close-btn").on('click',function(){
    $("#rate_modal").modal('hide');
  })
  
</script>
@stop
