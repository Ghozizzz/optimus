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
<input type="hidden" id="idEdit">
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
<h5>Negotiation List</h5>
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
      <th width="25%">Sender Information</th>
      <th width="25%">Item</th>
      <th width="30%">Status</th>
			<th width="20%">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		@foreach($db['data'] as $d)
		<tr>
			<td>{{ $i }}</td>
      		<td><span>{{$d->customer_email}}<br>{{$d->customer_name}}<br>{{$d->customer_address}}</span><br>
            {{ date('M / d / Y H:i A', strtotime($d->createdon)) }}</td>
      		<td> 
      			<img height="50" width="50" src="{{ URL::to('/') }}/images/car/{{ json_decode($d->picture)[0]->picture }}" alt=""><br>
      			Id: {{ $d->serial }} <br>
      			 {{$d->keyword}}<br>
      			 {{$d->description}}<br>
      			 {{$d->vin}}<br>
      			</td>
      		<td><div>
                  @if($d->status === 1)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                  @elseif($d->status === 2)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                  @elseif($d->status === 3)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                  @elseif($d->status === 4)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                  @elseif($d->status === 5)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
                  @elseif($d->status === 6)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
                  @elseif($d->status === 7)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7" width="30px">
                  @elseif($d->status === 8)
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
                {{$d->currency .' '. \App\Http\Controllers\API::currency_format($d->price)}}<br>
                Logistic by : {{$d->logistic_name}}
          </td>
			<td>
				<a href="{{ URL::to('/') }}/admin/negotiation/view/{{ $d->id }}" class="btn btn-danger">Message</a>
				<button data-ids="{{ $d->id }}" data-toggle="modal" data-target="#editModal" class="btn btn-danger">Sales</button>
			</td>
		</tr>
		<?php $i++ ?>
		@endforeach
	</tbody>
</table>
</div>
</div>
</div>
{{-- Modal New --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label class="col-form-label">Name Sales:</label>
            <select class="form-control editUserId">
				@foreach($db['sales'] as $s)
					<option value="{{ $s->id }}">{{ $s->name }}</option>
				@endforeach
			</select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button onclick="updateData()" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
@stop
@section('script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
function saveData(){
	var data = new FormData();
	data.append('newDescription', $('#newDescription').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/admin/carengine/save",  
	    type: "POST",  
	    data:  data,
	    processData: false, 
	    contentType: false,
	    success: function (response) {
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
$('#editModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes
  $('#idEdit').val(ids);
  console.log(ids);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
	 $.get( "{{ URL::to('/') }}/admin/negotiation/viewsales/"+ids, function( response ) {
		modal.find('.editUserId').val(response.user_id);
	});
})
function updateData(){
	var id = $('#idEdit').val();
	var data = new FormData();
	console.log(id);
	data.append('editUserId', $('.editUserId').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/admin/negotiation/update/"+id,  
	    type: "POST",  
	    data:  data,
	    processData: false,  
	    contentType: false, 
	    success: function (response) {
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

function editReadURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#editImage').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#editInputImage").change(function() {
  editReadURL(this);
});

function deleteData(id){
	swal({
	  title: "Are you sure?",
	  text: "Once deleted, you will not be able to recover this file!",
	  icon: "warning",
	  buttons: true,
	  dangerMode: true,
	})
	.then((willDelete) => {
	  if (willDelete) {
	    swal("Poof! Your file has been deleted!", {
	      icon: "success",
	    });

 	$.get( "{{ URL::to('/') }}/admin/carengine/delete/"+id, function( response ) {
		location.reload();
	});

	  } else {
	    swal("Your file is safe!");
	  }
	});
}
	$( "#maxpage" ).change(function() {
		var max = $('#maxpage').val();
		window.location.href = max;
	});

	$( "#descasc" ).change(function() {
		var descasc = $('#descasc').val();
		window.location.href = descasc;
	});

	
	function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#newImage').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#newInputImage").change(function() {
  readURL(this);
});


	</script>
@stop