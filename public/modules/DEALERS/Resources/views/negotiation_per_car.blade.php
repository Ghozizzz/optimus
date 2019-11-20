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
<h5>Negotiation List</h5>
<h6>{{ count($db['data']) }} item found(shown: 1-{{ count($db['data']) }})</h6>
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
    		<a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="prev">Prev</a> | 
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
      		<th width="250">Sender Information</th>
      		<th>Item</th>
      		<th>Status</th>
			
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		@foreach($db['data'] as $d)
		<tr>
			<td>{{ $i }}</td>
      		<td><span><img src="{{ URL::to('/') }}/img/message.png" height="40" width="40">(1/6)</span><span>Optimus Auto Trading Ptd Ltd<br> Singapore</span><br>Jan / 30 / 18 05:08 PM (JST)</td>
      		<td> 
      			<img height="50" width="50" src="{{ URL::to('/') }}/images/car/{{ json_decode($d->picture)[0]->picture }}" alt=""><br>
      				Id: {{ $d->serial }} <br>
      			 {{$d->keyword}}<br>
      			 {{$d->description}}<br>
      			 {{$d->description}}<br>
      			 {{$d->vin}}<br>
      			 {{$d->currency}}
      			 {{$d->price}}<br>
      			</td>
      		<td>
            <div>
            @if($d->id === 1)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
            @elseif($d->id === 2)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
            @elseif($d->id === 3)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
            @elseif($d->id === 4)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
            @elseif($d->id === 5)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
            @elseif($d->id === 6)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
            @elseif($d->id === 7)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7" width="30px">
            @elseif($d->id === 8)
              <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7" width="30px">
              <img src="{{URL::to('/')}}/assets/images/step8.png" alt="step8" width="30px">
            @endif
            </div>
            {{$d->status_description}}<br>
            {{$d->currency .' '. $d->negotiation_price}}
          </td>
			
		</tr>
		<?php $i++ ?>
		@endforeach
	</tbody>
</table>
</div>
</div>
</div>
@stop
@section('script')
<script>
	$( "#maxpage" ).change(function() {
		var max = $('#maxpage').val();
		window.location.href = max;
	});

	$( "#descasc" ).change(function() {
		var descasc = $('#descasc').val();
		window.location.href = descasc;
	});

</script>
@stop