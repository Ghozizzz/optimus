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

        <div>
          <hr>
        </div>
        <div class='admin-content'>
          <div width="100%">
            @include('admin::negotiation_menu')
          </div>
          
          <div class="height10"></div>
          
          <div class="row">
            <div class="col-md-12" align="center">
              <div class="title">Item Receive Confirmation</div>
            </div>            
          </div>
          <div class="row">
            <div class="col-md-12" align="center">
              <hr>
            </div>            
          </div>
          
          <div class='row'>
            <div class='col-md-12' align='center'>
              <a href="{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}">
                <button class="btn btn-danger" id='back-btn'>Back</button>
              </a>
              &nbsp;&nbsp;              
              <?php
              if(isset($review->id)){?>
              <button class='btn btn-danger' id='ratebtn'>Rate</button>
              <?php }else{
              echo '<b>Customer not yet review</b>';  
              }?>
            </div>                        
          </div>
          
        </div>      
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

<div class="modal fade" id="rate_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <input type="hidden" id="review_id" value="{{isset($review->id)?$review->id:''}}">
        <div class="modal-body">
          <div class="text-center">
            <div class='title'>Rate this Order!</div>
          </div>
          <div class="row">
            <div class="height50"></div>
          </div>
          <div class="row">
            <div class="col-md-4">How accurate was the item description</div>
            <div id="rater col-md-8">
              <select id="accuration-rating-bar" name="rating" autocomplete="off" disabled="disabled">
                  <option value=""></option>
                  <option value="1" <?php if(isset($review->accuration) && $review->accuration == 1)echo 'selected=true' ?>>1</option>
                  <option value="2" <?php if(isset($review->accuration) && $review->accuration == 2)echo 'selected=true' ?>>2</option>
                  <option value="3" <?php if(isset($review->accuration) && $review->accuration == 3)echo 'selected=true' ?>>3</option>
                  <option value="4" <?php if(isset($review->accuration) && $review->accuration == 4)echo 'selected=true' ?>>4</option>
                  <option value="5" <?php if(isset($review->accuration) && $review->accuration == 5)echo 'selected=true' ?>>5</option>
                </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">How satisfied were you with the seller's communication</div>
            <div id="rater col-md-8">
              <select id="satisfication-rating-bar" name="rating" autocomplete="off" disabled="disabled">
                  <option value=""></option>
                  <option value="1" <?php if(isset($review->satisfication) && $review->satisfication == 1)echo 'selected=true' ?>>1</option>
                  <option value="2" <?php if(isset($review->satisfication) && $review->satisfication == 2)echo 'selected=true' ?>>2</option>
                  <option value="3" <?php if(isset($review->satisfication) && $review->satisfication == 3)echo 'selected=true' ?>>3</option>
                  <option value="4" <?php if(isset($review->satisfication) && $review->satisfication == 4)echo 'selected=true' ?>>4</option>
                  <option value="5" <?php if(isset($review->satisfication) && $review->satisfication == 5)echo 'selected=true' ?>>5</option>
                </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">How quickly did the seller ship the item</div>
            <div id="rater col-md-8">
              <select id="speed-rating-bar" name="rating" autocomplete="off" disabled="disabled">
                  <option value=""></option>
                  <option value="1" <?php if(isset($review->speed) && $review->speed == 1)echo 'selected=true' ?>>1</option>
                  <option value="2" <?php if(isset($review->speed) && $review->speed == 2)echo 'selected=true' ?>>2</option>
                  <option value="3" <?php if(isset($review->speed) && $review->speed == 3)echo 'selected=true' ?>>3</option>
                  <option value="4" <?php if(isset($review->speed) && $review->speed == 4)echo 'selected=true' ?>>4</option>
                  <option value="5" <?php if(isset($review->speed) && $review->speed == 5)echo 'selected=true' ?>>5</option>
                </select>
            </div>
          </div>
          <div class="text-center">
            <div class=''>
              <?php if(isset($review->comment)){?>
                <textarea id="rating-comment" class='form-control' readonly="true">{{$review->comment}}</textarea>
              <?php }else{ ?>
                <textarea id="rating-comment" class='form-control'></textarea>
              <?php }?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary delete-rating">Delete</button>
        </div>
      </div>
    </div>
  </div>

@stop


@section('script')

<script>  
  var invoice_id = "{{$session['invoice']->id}}"  
  var negotiation_id = "{{$session['negotiation']->id}}"  
  var rating = "{{isset($review->rating)?$review->rating:''}}"
  var rating_comment = "{{isset($review->comment)?$review->comment:''}}"
  
    function ratingEnable() {
        $('#speed-rating-bar').barrating({
            theme: 'bootstrap-stars',
            showSelectedRating: false,
        });
        $('#satisfication-rating-bar').barrating({
            theme: 'bootstrap-stars',
            showSelectedRating: false,
        });
        $('#accuration-rating-bar').barrating({
            theme: 'bootstrap-stars',
            showSelectedRating: false,
        });
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

    function ratingDisable() {
        $('select').barrating('destroy');
    }

    $('.rating-enable').click(function(event) {
        event.preventDefault();

        ratingEnable();

        $(this).addClass('deactivated');
        $('.rating-disable').removeClass('deactivated');
    });

    $('.rating-disable').click(function(event) {
        event.preventDefault();

        ratingDisable();

        $(this).addClass('deactivated');
        $('.rating-enable').removeClass('deactivated');
    });
    
    if(rating !== ''){
      ratingReadonly();
    }else{
      ratingEnable();
    }
   
    
 
  $('#confirmbtn').on('click',function(){
    var conf = confirm('Are you sure confirm this order have been received');
    
    if(conf){
      receive(negotiation_id);
    }else{
      return false;
    }
    
  })
  
  $(".delete-rating").on('click',function(){
    var requestData = {
      'id' : $("#review_id").val(),
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('admin.deleteRating')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
        $(this).prop('disabled',true);
      },
      success: function (result) {
        if(result.error == 0){
          location.reload();
        }else{
          console.log('error');
        }
      }
    });
  });
  
  $(".rating-btn").on('click',function(){
    if($("#speed-rating-bar").val() !== '' && $("#accuration-rating-bar").val() && $("#satisfication-rating-bar").val()){
      ratingSubmit($("#speed-rating-bar").val(), $("#accuration-rating-bar").val(), $("#satisfication-rating-bar").val(), $("#rating-comment").val());
    }else{
      alert('please input rating and review');
    }
  })
  
  function ratingSubmit(speed,accuration,satisfication,rating_comment){
    var requestData = {
      'speed_rating_star' : speed,
      'accuration_rating_star' : accuration,
      'satisfication_rating_star' : satisfication,
      'rating_comment' : rating_comment,
      'invoiceid' : "{{$session['invoice']->id}}",
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('customer.saveRating')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
        $(".rating-btn").prop('disabled',true);
      },
      success: function (result) {
        if(result.error == 0){
          $(".rating-btn").hide();
          alert(result.message);
        }else{
          console.log('error');
        }
        $('#confirmbtn').hide();
      }
    });
  }
  
  function receive(negotiation_id){
    var requestData = {
      'negotiation_id' : negotiation_id,
      'status' : 8,
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('customer.negotiationstatus')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        alert('Thanks for your confirmation');
        $('#confirmbtn').hide();
      }
    });
  }
  
  $('#ratebtn').on('click',function(){
    $("#rate_modal").modal('show');
  })
  
  function getInvoiceStatus(invoice_number){
    var requestData = {
      'invoice_number' : invoice_number,
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('customer.getTracking')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        var invoice_status = result.status;
        $(".result-tracking").text('Invoice Status : ' + invoice_status);
      }
    });
  }
  
</script>
@stop
