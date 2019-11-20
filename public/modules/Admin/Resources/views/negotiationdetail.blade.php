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
              <form name="deal_negotiation" method="post" action="{{route('admin.negotiationDeal')}}">
                <input type="hidden" name="id" value="{{$session['negotiation']->id}}">
                <input type="hidden" name="status" value="2">                    
                Negotiation Price : {{$session['negotiation']->currency }} <input size="10" type="text" name="negotiation_price" value="{{$session['negotiation']->price}}">                    
                <button type="submit" class="btn btn-success">Deal</button>
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
if ($d->customer_chat_id === $d->customer_chat_id) {
?>
					<div class="col-md-12">
						<h5>{{ $d->customer_name }}</h5>
						<p>{{ date( "d/m/Y H:i", strtotime($d->createdon)) }}</p>
            <div class="row">
              <div class="col-md-12 user-chat-div">                      
                {{ $d->chat }}
              </div>
            </div>
					</div>
<?php } else{?>
					<div class="col-md-12">
						<h5>{{ $d->sales_name }}</h5>
						<p>{{ date( "d/m/Y H:i", strtotime($d->createdon)) }}</p>
            <div class="row">
              <div class="col-md-12 sales-chat-div">                      
                {{ $d->chat }}
              </div>
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
@stop