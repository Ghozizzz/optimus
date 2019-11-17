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
<h5>Invoice</h5>
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

        <div class='col-md-2 col-xs-12' align='center'>
          <select id="status" class='custom-select'>
            <option <?php if($qs['status'] == "0") echo 'selected' ?> 
            <?php 
              $qs_temp = $qs; 
              $qs_temp['status'] = '0'; 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">Outstanding</option>
            <option <?php if($qs['status'] == "1") echo 'selected' ?> 
            <?php
              $qs_temp = $qs;
              $qs_temp['status'] = '1' 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">Invoice</option>
            <option <?php if($qs['status'] == "2") echo 'selected' ?> 
            <?php
              $qs_temp = $qs;
              $qs_temp['status'] = '2'; 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">Partial Payment</option>
            <option <?php if($qs['status'] == "3") echo 'selected' ?> 
            <?php 
              $qs_temp = $qs;
              $qs_temp['status'] = '3'; 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">Complete</option>
            <option <?php if($qs['status'] == "") echo 'selected' ?> 
            <?php 
              $qs_temp = $qs;
              $qs_temp['status'] = ''; 
            ?> 
            value="{{ $web_url . '?' . http_build_query($qs_temp) }}">All</option>
          </select>
        </div>
        
      </div>
      <div class="row">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Invoice Number</th>
              <th>Status</th>
              <th>Due Date</th>
              <th>Total</th>
              <th>Balance Leave</th>
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
            @if(count($invoice)>0)
            @foreach($invoice as $d => $v)
            <?php
              $balance = $v['data']->total_amount - $v['data']->total_payment;
            ?>
              @if(in_array($v['data']->invoice_number, $active_payment['data']))
                <tr class='unread-negotiation'>
              @else 
                <tr>
              @endif  
              <td>{{ ($i+1) }}</td>
              <td>{{ $v['data']->invoice_number }}</td>
              <td>{{ isset($v['data']->description) ? $v['data']->description : 'No payment' }}</td>
              <td>@if($v['data']->due_date != ''){{ $v['data']->due_date }}@endif</td>
              <td>USD {{ number_format($v['data']->total_amount,'0','.',',') }}</td>
              <td>USD {{ number_format($balance,'0','.',',') }}</td>
              <td>
                  <button data-toggle="modal" data-target="#confirmModal" data-ids="{{ $v['data']->id }}" class="btn btn-danger">View</button>
                  <!--<button data-toggle="modal" data-target="#editModal" data-ids="{{ $v['data']->id }}" class="btn btn-danger">View</button>-->
              </td>
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
  
{{-- Confirm Payment --}}
<div class="modal fade modelView" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <input type="hidden" id="idEdit"/>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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
                <b>Due date</b>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-form-label duedate"></label>
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
            <div class="col-md-6"> 
              <div class="form-group">
                <b>Balance Leave</b>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-form-label balanceLeave">-</label>  
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
<script src="{{URL::to('/')}}/assets/js/admin.js"></script>
<script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
<script>
  var web_url = '{{$web_url}}';
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
      modal.find('.duedate').text(invoice.due_date);
      var html = '<table width="95%">'+
                  '<tr>'+
                  '<th width="20%">Submit Date (SG Time)</th>'+
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
                  '<td>'+ payment[n].createdon +'</td>' +
                  '<td>'+ payment[n].bank +'</td>' +
                  '<td>'+ payment[n].account_name +'</td>' +
                  '<td>'+ payment[n].bank_account +'</td>' +
                  '<td>'+ payment[n].currency + ' ' + parseFloat(payment[n].total_payment).toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'</td>';
          if(payment[n].attachment !== ''){
            html += '<td align="center"><a href="//' + $(location).attr('host') + '/uploads/payment/' + payment[n].attachment +'" target="_blank"><img src="//' + $(location).attr('host') + '/assets/images/view.png" width="60px"></a></td>';
          }else{
            html += '<td align="center">-</td>';
          }

          if(payment[n].payment_status == 0){
            html += '<td>'+
                    '<button  onclick="confirmPayment('+ payment[n].payment_id +')" type="button" class="btn btn-danger">Confirm</button>'+
                    '&nbsp; <button  onclick="rejectPayment('+ payment[n].payment_id +')" type="button" class="btn btn-danger">Reject</button>'+
                    '</td>';
          }else if(payment[n].payment_status == 1){
            html += '<td> <b>confirmed</b> </td>';
          }else if(payment[n].payment_status == 2){
            html += '<td> <b>rejected</b> </td>';
          }

          html +='</tr>';
          var currency = payment[n].currency;

          if(payment[n].payment_status == 1){
            total_payment += parseFloat(payment[n].total_payment);
          }

      }
      
      var balance = parseFloat(invoice.total_amount) - parseFloat(total_payment);

      modal.find('.totalPayment').text(invoice.currency + ' ' + parseFloat(total_payment).toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
      modal.find('.balanceLeave').text(invoice.currency + ' ' + parseFloat(balance).toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));

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


  $( "#status" ).change(function() {
    var status = $('#status').val();
    window.location.href = status;
  });

</script>
@stop
