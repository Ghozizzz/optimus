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
<h5>Port Charge</h5>
<h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

<div class='row'>
  <div class='col-md-3 col-xs-12'>
    <select id="maxpage" class='custom-select'>
    	<?php
			$qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
    		$qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';     		
		 ?>
			<option <?php if($getMax === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">5</option>
			<option <?php if($getMax === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">10</option>
			<option <?php if($getMax === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">20</option>
			<option <?php if($getMax === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">All</option>
		</select>
  </div>
  <div class="col-md-5 col-xs-12 input-group" align='center'>
    <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
    <div class="input-group-append">
      <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
    </div>
  </div>
  
  <div class='col-md-3 col-xs-12' align='center'>
    <select id="descasc" class='custom-select'>
      <?php
          $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
          $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';          
      ?>
      <option <?php if($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">New-old</option>
      <option <?php if($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Old-new</option>
    </select>
  </div>
  
  <div class='col-md-1 col-xs-12' align='center'>
		<button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
  </div>
</div>
<div class="row">
	<div class="col-md-12 col-xs-12">	
	<table class="table table-striped">
    <thead>
		<tr>
			<th>No</th>
			<th>Port Discharge</th>
      		<th>Port Destination</th>
      		<th>Price (USD)</th>
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
<div class='row'>
  <div class='col-md-12' align="center">
    <caption>
    	<?php if($thispage>1){
    		$qs['page'] = $thispage-1;
    		$qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
    		$qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
     		?>
      <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger bottom10">Prev</button></a>
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


{{-- Modal New --}}
<div class="modal fade" id="newModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              @if($d->id == 5763)
              <option value="{{ $d->id }}" selected>{{ $d->port_name }} - {{ $d->country_name }}</option>
              @else
                <option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
              @endif
            	@endforeach
            </select>
          </div>
             <div class="form-group">
            <label class="col-form-label">Port Destination:</label>
            <select id="newPortDestination" class="form-control">
            	@foreach($db['port_destination'] as $d)
            	<option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
            	@endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Price (USD):</label>
            <input type="number" class="form-control" id="newPrice">
          </div>
          <div class="form-group">
            <label class="col-form-label">Volume Price (USD):</label>
            <input type="number" class="form-control" id="newVolumePrice">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button onclick="saveData()" type="button" class="btn btn-primary" id='save-btn'>Save</button>
      </div>
    </div>
  </div>
</div>

{{-- Edit Data --}}

<div class="modal fade" id="editModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <select id="editPortDestination" class="form-control">
            	@foreach($db['port_destination'] as $d)
            	<option value="{{ $d->id }}">{{ $d->port_name }} - {{ $d->country_name }}</option>
            	@endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Price:</label>
            <input type="number" class="form-control" id="editPrice" onkeypress='validate(event)'>
          </div>
          <div class="form-group">
            <label class="col-form-label">Volume Price:</label>
            <input type="number" class="form-control" id="editVolumePrice" onkeypress='validate(event)'>
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
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="{{URL::to('/')}}/assets/js/admin.js"></script>
	<script>
    var web_url = '{{$web_url}}';
    $("#newPortDischarge").select2();
    $("#editPortDischarge").select2();
    $("#newPortDestination").select2();
    $("#editPortDestination").select2();
function saveData(){
	$("#save-btn").prop('disabled',true);
  
  var data = {
    'newPortDischarge' : $('#newPortDischarge').val(),
    'newPortDestination' : $('#newPortDestination').val(),
    'newPrice' : $('#newPrice').val(),
    'newVolumePrice' : $('#newVolumePrice').val(),
  }
	$.ajax({  
	    url: "{{ URL::to('/') }}/logistic/portcharge/save",  
	    type: "POST",  
	    data:  data,
	    success: function (response) {
        
        if (typeof response.error != 'undefined' && response.error) {
	    		alert(response.error);
          return false;
	    	}
	    	location.reload();
	    },
	    error: function(error) {
          $("#save-btn").prop('disabled',false);
	        console.log(error);
	    }
	});
}
$('#newModal').on('show.bs.modal', function (event) {
  $("#newPrice").val('');
  $("#newVolumePrice").val('');
});
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
			modal.find('#editPortDestination').val(response.destination_id);
			modal.find('#editPortDestination').select2().trigger('change');
			modal.find('#editPrice').val(response.price);
			modal.find('#editVolumePrice').val(response.volume_price);
	});
})
function updateData(){
	var id = $('#idEdit').val();
	
  var data = {
    'editPortDischarge' : $('#editPortDischarge').val(),
    'editPortDestination' : $('#editPortDestination').val(),
    'editPrice' : $('#editPrice').val(),
    'editVolumePrice' : $('#editVolumePrice').val(),
    'id' : id,
  }
  
	$.ajax({  
	    url: "{{route('logistic.portcharge.update')}}",  
	    type: "POST",  
	    data:  data,
	    success: function (response) {
	    	if (typeof response.error !== 'undefined' && response.error == 0) {
	    		alert(response.error);
          return false;
	    	}
      location.reload();
	    },
	    error: function(error) {
	        console.log(error);
	    }
	});

}

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
