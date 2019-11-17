@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
<style type="text/css" media="screen">
.sales{
	margin-left: 70%;
}
</style>
@stop
@section('content')

<div class="full-container">
	<div class="row">
		<div class="col-md-2 left-menu-div">
			@include('admin::layouts.menu')
		</div>
		<div class="col-md-10 right-menu-div">
      <div class="wraper">
        <div class='admin-title'>
          <h2>Negotiation</h2>
        </div>
        <div>
          <hr>
        </div>
        <div width="100%">
            @include('admin::negotiation_menu')
          </div>
        

			<div class='negotiation-term'>
            <h2>Negotiate with the Buyer by exchanging message</h2>
            <p>The following things should be discussed</p>
            <p>
              <ol>
              <li> Total Price : FOB Price (not including freight cost) or C&F Price (Cost & Freight),Insurance cost, preshop inspection fee etc<br>
                <a href="#">Click here for calculating sales charge</a></li>
              <li> Payment term & payment date</li>
              <li> Condition of the item: If you have unsure points about the deal, please make sure before you confirm the order.</li>
              </ol>
            </p>
            <hr>
            <h2>Next Action:</h2>
            <p>
              After above points and other conditions are agreed, please send proforma invoice by clicking on [Issue proforma invoice] and submit the form.              
            </p>
            <p>
<!--              <a href="{{route('admin.changeNegotiationStatus',['id'=>$session['negotiation']->id,'status'=>3])}}">
                <button type="button" class="btn btn-default">Lock</button>
              </a> &nbsp; -->
              <?php
                $disabled = '';
                $readonly = '';
                $total_price = 0;
                if(!empty($session['proforma_invoice'])){
                  $disabled = 'disabled="true"';
                  $readonly = 'readonly="true"';
                }
              ?>
              <form name="deal_negotiation" method="post" action="{{route('admin.negotiationDeal')}}">
                <input type="hidden" name="id" value="{{$session['negotiation']->id}}">
                <input type="hidden" name="status" value="2">
                <b>Detail</b>
                <table>
                  <tr>
                    <td>Car Price </td>
                    <td><?php echo $session['negotiation']->currency ; ?> <input size="10" type="text" id="car_price" name="car_price" value="{{$session['negotiation']->car_price}}" <?php echo $readonly ?> onkeypress='validate(event)'></td>
                  </tr>
                  <?php $total_price += $session['negotiation']->car_price; ?>
                  
                  <?php $other_charge = 0;?>
                  
                  <?php if($session['negotiation']->inspection == 1){ ?>
                  <tr>
                    <td>Inspection Price </td>
                    <td><?php echo $session['negotiation']->currency ?> <input size="10" type="text" id="inspection_price" name="inspection_price" value="{{\App\Http\Controllers\API::currency_format(\App\Http\Controllers\API::getSetting('inspection'))}}" <?php echo $readonly ?> onkeypress='validate(event)'></td>
                  </tr>
                  <?php 
                  $total_price += \App\Http\Controllers\API::getSetting('inspection');
                  $other_charge += \App\Http\Controllers\API::getSetting('inspection');
                  } ?>
                  
                  <?php if($session['negotiation']->insurance == 1){ ?>
                  <tr>
                    <td>Insurance Price </td>
                    <td><?php echo $session['negotiation']->currency ?> <input size="10" type="text" id="insurance_price" name="insurance_price" value="{{\App\Http\Controllers\API::currency_format(\App\Http\Controllers\API::getSetting('insurance'))}}" <?php echo $readonly ?> onkeypress='validate(event)'></td>
                  </tr>
                  
                  <?php 
                  $total_price += \App\Http\Controllers\API::getSetting('insurance');
                  $other_charge += \App\Http\Controllers\API::getSetting('insurance');
                  } ?>
                  
                  <!-- <tr>
                    <td>Shipping Price </td>
                    <td>{{$session['negotiation']->currency }} <input size="10" type="text" id="shipping_price" name="shipping_price" value="{{$session['negotiation']->shipping_price}}" <?php echo ($session['negotiation']->shipping_price > 0) ? $readonly  : ''; ?> onkeypress='validate(event)'></td>
                  </tr>
                  <?php 
                  $total_price += $session['negotiation']->shipping_price; 
                  $other_charge += $session['negotiation']->shipping_price;
                  ?> -->
                  
                  <tr>
                    <td>Ocean Freight </td>
                    <?php if($session['negotiation']->volume_price != 0 || $session['negotiation']->volume_price != null){ ?>
                      <td>{{$session['negotiation']->currency }} <input size="10" type="text" id="ocean_freight" name="ocean_freight" value="{{$session['negotiation']->ocean_freight_fee + $session['negotiation']->shipping_price}}" <?php echo ($session['negotiation']->ocean_freight_fee > 0) ? $readonly  : ''; ?> onkeypress='validate(event)'></td>
                    <?php } else { ?>
                      <td>{{$session['negotiation']->currency }} <input size="10" type="text" id="ocean_freight" name="ocean_freight" value="{{$session['negotiation']->shipping_price}}" <?php echo ($session['negotiation']->ocean_freight_fee > 0) ? $readonly  : ''; ?> onkeypress='validate(event)'></td>
                    <?php } ?>
                  </tr>
                  <?php 
                  if($session['negotiation']->volume_price != 0 || $session['negotiation']->volume_price != null){
                    $total_price += $session['negotiation']->ocean_freight_fee + $session['negotiation']->shipping_price; 
                    $other_charge += $session['negotiation']->ocean_freight_fee + $session['negotiation']->shipping_price;
                  } else {
                    $total_price += $session['negotiation']->ocean_freight_fee; 
                    $other_charge += $session['negotiation']->ocean_freight_fee;
                  }
                  ?>  
                  
                  <tr>
                    <td>
                      <br><br><br>
                    </td>
                  </tr>
                  
                  <tr>
                    <td>Negotiation Price</td>
                    <td>{{$session['negotiation']->currency }} <input size="10" type="text" id="negotiation_price" name="negotiation_price" value="{{$session['negotiation']->price}}" <?php echo $readonly ?> onkeypress='validate(event)'></td>
                    <td><button type="submit" class="btn btn-success" <?php echo $disabled ?>>Deal</button></td>
                  </tr>
                  
                </table>              
                
              </form>
            </p>
            @if($negotiation->status == '2' && empty($session['proforma_invoice']))
            <p><a href="{{route('admin.save-proformainvoice',array('negotiation_id'=>$session['negotiation']->id))}}"><button type="button" class="btn btn-danger">Issue Proforma Invoice</button></a></p>            
            @else
            <p><button type="button" class="btn btn-danger" disabled='true' title="To enable this button please click deal first">Issue Proforma Invoice</button></p>
            @endif
          </div>
          
          <div class="height10"></div>
          
			<div class="row">
				<div class="col-md-12">
	@if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <div class='chatbox'>
<?php
foreach($session['negotiation_line'] as $d)
if ($d->customer_chat_id !== null) {
?>
        <div class='row' style="margin-top:5px;border-radius: 10px;">
          @if(in_array($d->negotiation_line_id,$unread_message))
            <div class="col-md-11 user-chat-div unread-negotiation">
          @else  
            <div class="col-md-11 user-chat-div">
          @endif
						<b>{{ $d->customer_name }}</b>
						<p class='chat-date'>{{ date( "d M Y H:i:s", strtotime($d->createdon)) .' SG Time'}}</p>
            
              <div class="row">
                <div class="col-md-12">
                  {!! nl2br($d->chat) !!}
                </div>
              </div>
            <?php if($d->file !== ''){?>
              <div class="row" align="center">
                <div class="col-md-12">
                  attachment: <a href="{{URL::to('/')}}/uploads/negotiation/{{$d->file}}">{{$d->file}}</a>
                </div>
              </div>
            <?php }?>  
              
					</div>
          <div class="col-md-1"></div>
        </div>
<?php } else{?>
        <div class='row' style="margin-top:5px;border-radius: 10px;">      
          <div class="col-md-1"></div>
					<div class="col-md-11 sales-chat-div" style='float: right' align='right'>
						<b>{{ $d->sales_name }}</b>
						<p class='chat-date'>{{ date( "d M Y H:i:s", strtotime($d->createdon)) .' SG Time'}}</p>
            <div class="row">
              <div class="col-md-12">                      
                {!! nl2br($d->chat) !!}
              </div>
            </div>	
            <?php if($d->file !== ''){?>
              <div class="row" align="center">
                <div class="col-md-12">
                  attachment: <a href="{{URL::to('/')}}/uploads/negotiation/{{$d->file}}">{{$d->file}}</a>
                </div>
              </div>
            <?php }?>
					</div>
        </div>
					<?php
			if($d->file !== ''){
							?>
					<pre>
						Attachment: <a target="_blank" href="{{ URL::to('/') }}/uploads/negotiation/{{ $d->file }}" title="">{{ $d->file }}</a>
					</pre>
			<?php } ?>
<?php } ?>
            </div>
            <form action="{{ URL::to('/') }}/admin/negotiation/reply/{{$session['negotiation']->id}}/{{$session['negotiation']->customer_id}}" method="POST" enctype="multipart/form-data">
					<div class="col-md-12">
						<h5>Comment</h5>
						<input type="hidden" name="userId" value="{{$session['negotiation']->customer_id}}">
						<textarea name="messageChat" id="messageChat" placeholder="Type your message here!" class="form-control" rows="5"></textarea>
					</div>
					<br>
					<div class="col-md-12">
						<h6>Attach File(optinal)</h6>
						<input name="attachFile" id="attachFile" type="file" class="form-control-file">
						<p>*WORD, EXCEL, BITMAP, JPEG, PNG, TIFF, ZIP, PDF format only (5MB max)</p>
					</div>
					<center><button type="submit" class="btn btn-danger">Reply</button></center>
          </form>
				</div>
			</div>
			<br>
      
      </div>
		</div>
	
	</div>

</div>
@stop
@section('script')
<script>
  var other_charge = "{{$other_charge}}";
  
  $("#negotiation_price").on('change',function(){
    var car_price = $("#negotiation_price").val() - other_charge;
    $("#car_price").val(car_price);    
  });
  
  function validate(evt) {
    var theEvent = evt || window.event;

    // Handle paste
    if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }
</script>
@stop