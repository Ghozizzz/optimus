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
	$qs['filter'] = isset($_GET['filter'])? str_replace('-',' ',$_GET['filter']) : '';
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
<h5>Negotiation List</h5>
<h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

<div class='row'>
  <div class='col-md-3'>
   <select id="maxpage" class='custom-select'>
    	<?php
			$qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
      $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
     	
		 ?>
			<option <?php if($getMax === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">5</option>
			<option <?php if($getMax === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">10</option>
			<option <?php if($getMax === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">20</option>
			<option <?php if($getMax === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">All</option>
		</select>
  </div>

    <div class="col-md-5 col-xs-12 input-group" align="center">
      <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
      <div class="input-group-append">
        <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
      </div>
    </div>

    <div class='col-md-4 col-xs-12' align='center'>
      <select id="descasc" class='custom-select'>
        <?php
        $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
        ?>
        <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">New-old</option>
        <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Old-new</option>
      </select>
    </div>
</div>
<div class="row">
  <div class="col-md-12 col-xs-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
              <th width="250">Sender Information</th>
              <th>Item</th>
              <th>Status</th>
              <th>Action</th>

        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach($db['data'] as $d)
        <tr>
          <td>{{ $i }}</td>
              <td><span><img src="{{ URL::to('/') }}/img/message.png" height="40" width="40"></span><span>Optimus Auto Trading Ptd Ltd<br> Singapore</span><br>
                {{ date('M / d / Y H:i A', strtotime($d->createdon)) }}</td>
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
                Total : {{$d->total}}<br>
                Highest price : {{$d->currency .' '. $d->highest_price}}
              </td>
              <td>
                <a href="{{route('dealers.negotiationCar',['car_id'=>$d->car_id])}}"><button type="button" class="btn btn-danger">View</button></a>
              </td>
        </tr>
        <?php $i++ ?>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<div class='row'>
  <div class='col-md-12 col-xs-12' align="center">
    <caption>
    	<?php if($thispage>1){
    		$qs['page'] = $thispage-1;
    		$qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
    		$qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
     		?>
      <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger bottom10">Prev</button></a> | 
		<?php }?>
<?php if($thispage < $total_page){
			$qs['page'] = $thispage+1;
    		$qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
    		$qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
     		
	?>
      <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger bottom10">Next</button></a>
    </caption>
<?php }?>    	
  </div>
</div>

</div>
</div>
@stop
@section('script')
<script src="{{URL::to('/')}}/assets/js/admin.js"></script>
<script>
  var web_url = '{{$web_url}}';
</script>
@stop