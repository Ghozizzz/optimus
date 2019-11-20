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
   #confirmModal{
    /*padding-left: 25%;*/
    /*padding-right: 25%;*/
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
  $total_item = $db['total'];
  if(isset($_GET['max'])){
    $limit = $_GET['max'];
    if($limit === 'all') $limit = 9999999;
  }else{
    $limit = 5;
  }
  $total_page = ceil($total_item/$limit);
?>
<div class="col-md-10 right-menu-div">
  <br>
<h5>Invoice</h5>
<h6>{{ $db['total'] }} item found(shown: 1-{{ count($db['data']) }})</h6>
<div class='row'>
  <div class='col-md-12'>
    <caption>
      <?php if($thispage>1){
        $qs['page'] = $thispage-1;
        $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
        $current_url = Request::fullUrl();
        $explode = explode('?', $current_url);
        ?>
        <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="prev">Prev</a> 
    <?php }?>
<?php if($thispage < $total_page){
      $qs['page'] = $thispage+1;
        $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
        $current_url = Request::fullUrl();
        $explode = explode('?', $current_url);
  ?>
      <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="next">Next</a>
    </caption>
<?php }?>     
  </div>
</div>
      <div class='row'>
        <div class='col-md-3'>
          <select id="maxpage" class='custom-select'>
      <?php
      $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
        $current_url = Request::fullUrl();
        $explode = explode('?', $current_url);
     ?>
      <option <?php if($getMax === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">5</option>
      <option <?php if($getMax === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">10</option>
      <option <?php if($getMax === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">20</option>
      <option <?php if($getMax === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">All</option>
    </select>
  </div>
  <div class='col-md-9' align='right'>
    <label>Sort by:</label>
    <select id="descasc" class='custom-select'>
      <?php
          $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
          $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
          $current_url = Request::fullUrl();
          $explode = explode('?', $current_url);
      ?>
      <option <?php if($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">New-old</option>
      <option <?php if($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">Old-new</option>
    </select>
        </div>  
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Invoice Number</th>
              <th>Status</th>
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($invoice as $d => $v)
            <tr>
              <td>{{ $i }}</td>
              <td>{{ $v['data']->invoice_number }}</td>
              <td>{{ $v['data']->description }}</td>
              <td>
                  <button data-toggle="modal" data-target="#confirmModal" data-ids="{{ $v['data']->id }}" class="btn btn-danger">View</button>
                  <!--<button data-toggle="modal" data-target="#editModal" data-ids="{{ $v['data']->id }}" class="btn btn-danger">View</button>-->
              </td>
            </tr>
            <?php $i++ ?>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>

  </div>
</div>
  
{{-- Confirm Payment --}}
<div class="modal fade modelView" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <input type="hidden" id="idEdit"/>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form>
          <center>
            <h5 class="modal-title" align="center" id="exampleModalLabel">Customer Data</h5>
          </center>
          <br>
         <div class="form-group viewInvoice">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <b>Invoice</b>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-form-label invoice">-</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <b>Total Price</b>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-form-label total_price">-</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6"> 
              <div class="form-group">
                <b>Status</b>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-form-label viewStatus">-</label>  
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-md-6"> 
              <div class="form-group">
                <b>Total Payment</b>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-form-label totalPayment">-</label>  
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-md-12" id="payment-div" align="center">
            </div>
          </div>
        </div>
        </form>  
      </div>
      <div class="row">
        <div class="col-md-12">          
        </div>        
      </div>
    </div>
    
  </div>
</div>

@stop
@section('script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
<script>
  function convertDate(inputFormat) {
  function pad(s) { return (s < 10) ? '0' + s : s; }
  var d = new Date(inputFormat);
  return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
}

$('#confirmModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes
  $('#idEdit').val(ids);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  $.get( "{{ URL::to('/') }}/admin/invoice/view/"+ids, function( response ) {
    var invoice = response.invoice;
    var payment = response.payment;
    
    modal.find('.invoice').text(invoice.invoice_number);
    modal.find('.total_price').text(invoice.currency + ' ' + parseFloat(invoice.total_amount).toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    modal.find('.viewStatus').text(invoice.description);
    var html = '<table width="90%">'+
                '<tr>'+
                '<th>Bank Account</th>'+
                '<th>Account Name</th>'+
                '<th>Account Number</th>'+
                '<th>Total Transfer</th>'+
                '<th>Picture</th>'+
                '<th>Action</th>'+
                '</tr>';
    var total_payment;
    var total_payment = 0;
    for (var n in payment){
      html += '<tr>' +
                '<td>'+ payment[n].bank +'</td>' +
                '<td>'+ payment[n].account_name +'</td>' +
                '<td>'+ payment[n].bank_account +'</td>' +
                '<td>'+ payment[n].currency + ' ' + parseFloat(payment[n].total_payment).toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'</td>';
        if(payment[n].attachment !== ''){
          html += '<td><a href="//' + $(location).attr('host') + '/uploads/payment/' + payment[n].attachment +'"><img src="//' + $(location).attr('host') + '/uploads/payment/' + payment[n].attachment +'" width="100px"></a></td>';
        }else{
          html += '<td></td>';
        }
        
        if(payment[n].payment_status == 0){
          html += '<td>'+
                  '<button  onclick="confirmPayment('+ payment[n].payment_id +')" type="button" class="btn btn-danger">Confirm</button>'+
                  '<button  onclick="rejectPayment('+ payment[n].payment_id +')" type="button" class="btn btn-danger">Reject</button>'+
                  '</td>';
        }else if(payment[n].payment_status == 1){
          html += '<td> <b>confirmed</b> </td>';
        }else if(payment[n].payment_status == 2){
          html += '<td> <b>rejected</b> </td>';
        }
      
        html +='</tr>';
        var currency = payment[n].currency;
        
        if(payment[n].payment_status == 1){
          total_payment += payment[n].total_payment;
        }
        
    }
    
    modal.find('.totalPayment').text(currency + ' ' + parseFloat(total_payment).toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    
    $("#payment-div").html(html)
  });
})

function confirmPayment(payment_id){
  updateData(payment_id,1);
}

function rejectPayment(payment_id){
  updateData(payment_id,2)
}

function updateData(payment_id, status){
  
  if(status == 1){
    var msg = 'Are you sure want to confirm this payment?';
  }else if(status == 2){
    var msg = 'Are you sure want to reject this payment?';
  }
 var response = confirm(msg);
 
 if(response){

    var data = new FormData();
    data.append('editStatus', status);
    data.append('payment_id', payment_id);
    $.ajax({  
        url: "{{ URL::to('/') }}/admin/invoice/update",  
        type: "POST",  
        data:  data,
        processData: false,  
        contentType: false, 
        success: function (response) {
          console.log(response);
          if (response.error) {
            alert(response.error);
          }
          location.reload();
        },
        error: function(error) {
            console.log(error);
        }
    });
  }  
}


$( "#maxpage" ).change(function() {
  var max = $('#maxpage').val();
  window.location.href = max;
});

$( "#descasc" ).change(function() {
  var descasc = $('#descasc').val();
  window.location.href = descasc;
});

function prev(){
  alert(1);
}
function next(){
  alert(1);
}

</script>
@stop
