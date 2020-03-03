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
          <h2>Tracking Invoice</h2>
        </div>
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
              <div class="title">Shipping</div>
            </div>            
          </div>
          <div class="row">
            <div class="col-md-12" align="center">
              <hr>
            </div>            
          </div>
          <form method='post' action="{{route('admin.submitResi')}}" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <div class='row'>
            <div class="col-md-4 col-xs-4 background1 proformainvoice-content" align='right'>
              Vessel Name
            </div>
            <div class="col-md-6 col-xs-6 proformainvoice-content">
              <input type="text" class="form-control" name="vessel_name" id="vessel_name" placeholder="Vessel Name" value='{{$session['negotiation']->vessel_name}}'>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 col-xs-4 background1 proformainvoice-content" align='right'>
              Shipment Date
            </div>
            <div class="col-md-6 col-xs-6 proformainvoice-content">
              <input class='form-control' type='text' name='shipment_date' id="shipment_date" placeholder="shipment date" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" autocomplete="off" value='{{$session['negotiation']->shipment_date}}'>
            </div>
          </div>
          <div class='row'>
            <div class="col-md-4 col-xs-4 background1 proformainvoice-content" align='right'>
              Shipment File
            </div>
            <div class="col-md-6 col-xs-6 proformainvoice-content">
              <input type="file" class="form-control" name="shipment_file" id="shipment_file" placeholder="shipment file" value='{{$session['negotiation']->shipment_file}}'>
              <?php
              if($session['negotiation']->shipment_file != ''){?>
                <a href='<?php echo URL::to('/').'/uploads/shipment/'.$session['negotiation']->shipment_file?>' target='_blank'>download file</a>
              <?php }?>
            </div>
          </div>
          <div class='row'>
            <div class='col-md-1 col-xs-1' align='center'><a href="{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}" class="btn btn-danger" id='back-btn'>Back</a></div>
            <div class="col-md-11 col-xs-11" align='center'>
              <button class="btn btn-danger" id='submit-resi'>Submit</button>
            </div>
          </div>
          </form>
                    
<!--          <div class="row">
            <div class="col-md-12" align="center">
              <div class="title">Track Your Order</div>
            </div>            
          </div>
          <div class="row">
            <div class="col-md-12" align="center">
              <hr>
            </div>            
          </div>-->
          
<!--          <div class='row'>
            <div class="col-md-4 col-xs-4 background1 proformainvoice-content" align='right'>
              Order Tracking
            </div>
            <div class="col-md-6 col-xs-6 proformainvoice-content">
              @if(isset($session['invoice']))
              <input type="text" class="form-control" name="invoice_number" id="invoice_number" placeholder="type here!" value='{{$session['invoice']->invoice_number}}' readonly="true">
              @else
               <input type="text" class="form-control" name="invoice_number" id="invoice_number" placeholder="type here!">
              @endif
            </div>
            <div class="col-md-2 col-xs-2 proformainvoice-content">
              <button class="btn btn-danger track-btn">Track</button>
            </div>
          </div>-->
          <div class="row">
            <div class="col-md-12 col-xs-12 result-tracking" align="center"></div>
          </div>
          <div class="row">
            <div class="col-md-12" align="center">
              <hr>
            </div>            
          </div>
          
          <div class="row">
            <div class="col-md-12" align="center">
              <img src="{{URL::to('/')}}/assets/images/shipment_progress.png" alt="Shipment Progress">
            </div>            
          </div>
          
          
          @if(session()->has('message') && session()->get('message') !== '')
            <div class='alert alert-info'>
          <?php echo session()->get('message'); ?>
            </div>
          @endif
         
        </div>      
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script src="{{URL::to('/')}}/assets/js/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="{{URL::to('/')}}/assets/js/datetimepicker/locales/bootstrap-datetimepicker.id.js"></script>
<script>  
  
  $('#shipment_date').datetimepicker({
      language:  'en',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0
  });
  $(".track-btn").on('click',function(){
    getInvoiceStatus($("#invoice_number").val());
  });
  
  $("#submit-resi").on('click',function(){
//    submitResi();
  });
  
  function submitResi(){
    var requestData = {
      'resi_number' : $("#resi_number").val(),
      'logistic' : $("#logistic").val(),
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: "{{route('admin.submitResi')}}",
      data: requestData,
      type: 'post',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (result) {
        window.location = "{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}";
      }
    });
  }
  
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
        $(".result-tracking").html('Invoice Status : ' + invoice_status + '<br>' + 'Negotiation Status: ' + result.negotiation_status_description + '<br>' + 'Resi number : ' + result.resi_number);
      }
    });
  }
  
</script>
@stop
