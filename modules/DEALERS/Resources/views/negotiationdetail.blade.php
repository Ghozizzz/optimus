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
			@include('dealers::layouts.menu')
		</div>
		<div class="col-md-10 right-menu-div">
<div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-control">
						<label class="label-control">
							<h4>Negotiation with the Buyer by exchanging message</h4>
							<p>The following things should be discussed</p>
							<li>
								Total price: FOB Price (Not including freight cost) or C&F Price (Cost & Freight), Insurance cost, pre-ship inspection fee etc<br>
								<a href="#">&nbsp;&nbsp;Click here for Calculating Sales Charge</a>
							</li>
							<li>Payment Terms & Payment date</li>
							<li>Condition of the item: if you have unsure points about the deal, please make sure before you confirm the order.</li>
						</label>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-control">
						<label class="label-control">
							<h3 style="color: red;">Next Action:</h3>
							<h5>After above points and other conditions are agreed, please send Proforma Invoice by cliking on (Issue Proforma Invoice) the submit the form.</h5>
						</label>
						<button type="submit" class="btn btn-danger">Issue Proforma Invoice</button>
					</div>
				</div>
			</div>
			<br>
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
<?php
foreach($db as $d)
if ($d->user_chat_id === $d->customer_id) {
?>
					<div class="col-md-12">
						<h5>{{ $d->customer_name }}</h5>
						<p>{{ date( "d/m/Y H:i", strtotime($d->chatdate)) }}</p>
						<div class="alert alert-secondary" role="alert">
							<b style="color: black;">{{ $d->chat }}</b>
						</div>
					</div>
<?php } else{?>
					<div class="col-md-12">
						<h5>{{ $d->sales_name }}</h5>
						<p>{{ date( "d/m/Y H:i", strtotime($d->chatdate)) }}</p>
						<div class="alert alert-danger" role="alert">
							<b style="color: black;">{{ $d->chat }}</b>
							
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
			</div>
			<br>
		</div>
	</div>
	</div>

</div>
@stop
@section('script')
@stop