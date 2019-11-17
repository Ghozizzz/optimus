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
              &nbsp;&nbsp;              
              <button class='btn btn-danger' id='ratebtn'>Rate</button>
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
        
        <div class="modal-body">
          <div class="text-center">
            <div class='title'>Rate this Order!</div>
          </div>
          <div class="text-center">
            <div id="rater">
              <select id="rating-bar" name="rating" autocomplete="off" disabled="disabled">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5" selected="true">5</option>
                </select>
            </div>
          </div>
          <div class="text-center">
            <div class=''>
              <?php if(isset($review[0])){?>
                <textarea id="rating-comment" class='form-control' readonly="true">{{$review[0]->comment}}</textarea>
              <?php }else{ ?>
                <textarea id="rating-comment" class='form-control'></textarea>
              <?php }?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>

@stop


@section('script')

<script>  
  var invoice_id = "{{$session['invoice']->id}}"  
  var negotiation_id = "{{$session['negotiation']->id}}"  
  var rating = "{{isset($review[0]->rating)?$review[0]->rating:''}}"
  var rating_comment = "{{isset($review[0]->comment)?$review[0]->comment:''}}"
  
    function ratingEnable() {
        $('#rating-bar').barrating({
            theme: 'bootstrap-stars',
            showSelectedRating: false,
        });
    }
    
    function ratingReadonly() {
        $('#rating-bar').barrating({
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
  
  $(".rating-btn").on('click',function(){
    if($("#rating-comment") !== ''){
      ratingSubmit($("#rating-comment").val(),$("#rating-bar").val());
    }else{
      alert('please input rating and review');
    }
  })
  
  function ratingSubmit(rating_comment, rating_star){
    var requestData = {
      'rating_star' : rating_star,
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
