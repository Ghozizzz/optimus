@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
@stop
@section('content')
<div class="full-container">
<div class="row">
<div class="col-md-2 left-menu-div">
	@include('logistic::layouts.menu')
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
<h5>Car Make List</h5>
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
		<button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
  </div>
		
		
	<table class="table table-striped">
    <thead>
		<tr>
			<th>No</th>
			<th>Port Discharge</th>
      		<th>Port Destination</th>
      		<th>Price</th>
			<th colspan="2">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		@foreach($db['data'] as $d)
		<tr>
			<td>{{ $i }}</td>
      		<td>{{ $d->discharge_port_name }} - {{ $d->discharge_country }}</td>
      		<td>{{ $d->port_destination_port_name }} - {{ $d->port_destination_country_name }}</td>
      		<td>{{ $d->price }}</td>
			<td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">Update</button> <button onclick="deleteData({{ $d->id }})" class="btn btn-danger">Delete</button></td>
		</tr>
		<?php $i++ ?>
		@endforeach
	</tbody>
</table>
</div>

</div>

</div>


{{-- Modal New --}}
<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <label class="col-form-label">Port Discharge:</label>
            <select id="newPortDischarge" class="form-control">
            	@foreach($db['port_discharge'] as $d)
            	<option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
            	@endforeach
            </select>
          </div>
             <div class="form-group">
            <label class="col-form-label">Port Destination:</label>
            <select id="newPortDestinaton" class="form-control">
            	@foreach($db['port_destination'] as $d)
            	<option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
            	@endforeach
            </select>
          </div>
           <div class="form-group">
            <label class="col-form-label">Price:</label>
            <input type="number" class="form-control" id="newPrice">
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button onclick="saveData()" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

{{-- Edit Data --}}

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<input type="hidden" id="idEdit"/>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form>
          <div class="form-group">
            <label class="col-form-label">Port Discharge:</label>
            <select id="editPortDischarge" class="form-control">
            	@foreach($db['port_discharge'] as $d)
            	<option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
            	@endforeach
            </select>
          </div>
             <div class="form-group">
            <label class="col-form-label">Port Destination:</label>
            <select id="editPortDestinaton" class="form-control">
            	@foreach($db['port_destination'] as $d)
            	<option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
            	@endforeach
            </select>
          </div>
           <div class="form-group">
            <label class="col-form-label">Price:</label>
            <input type="number" class="form-control" id="editPrice">
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button onclick="updateData()" type="button" class="btn btn-primary">Update</button>
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
	data.append('newPortDischarge', $('#newPortDischarge').val());
	data.append('newPortDestinaton', $('#newPortDestinaton').val());
	data.append('newPrice', $('#newPrice').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/logistic/portcharge/save",  
	    type: "POST",  
	    data:  data,
	    processData: false,  
	    contentType: false, 
	    success: function (response) {
	    	if (response.error) {
	    		alert(response.error);
	    	}
	    	//location.reload();
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
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
	 $.get( "{{ URL::to('/') }}/logistic/portcharge/view/"+ids, function( response ) {
    console.log(response);
			modal.find('#editPortDischarge').val(response.discharge_id);
			modal.find('#editPortDestinaton').val(response.destination_id);
			modal.find('#editPrice').val(response.price);
	});
})
function updateData(){
	var id = $('#idEdit').val();
	var data = new FormData();
	data.append('editPortDischarge', $('#editPortDischarge').val());
	data.append('editPortDestinaton', $('#editPortDestinaton').val());
	data.append('editPrice', $('#editPrice').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/logistic/portcharge/update/"+id,  
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


 	$.get( "{{ URL::to('/') }}/logistic/portcharge/delete/"+id, function( response ) {
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

  function prev(){
    alert(1);
  }
  function next(){
    alert(1);
  }

	</script>

@stop
