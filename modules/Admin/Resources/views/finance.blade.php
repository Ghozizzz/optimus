@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
@stop
@section('content')
<div class="full-container">
<div class="row">
<div class="col-md-2 left-menu-div">

	@include('admin::layouts.menu')
</div>

<?php
  $thispage = isset($_GET['page'])?$_GET['page'] : 1;
  $total_item = count($db['data']);
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
<h5>Finance List</h5>
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
		<button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
  </div>
		
		
	<table class="table table-striped">
    <thead>
		<tr>
			<th>No</th>
			<th>Finance Name</th>
			<th>Last Active</th>
			<th>Status</th>
			<th>Last Transaction</th>
			<th colspan="2">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		@foreach($db['data'] as $d)
		<tr>
			<td>{{ $i }}</td>
			<td>{{ $d->name }}</td>
			<td>{{ $d->last_active }}</td>
			<td><?php $d->status === 1 ? print "Active" : print "Inactive" ?></td>
			<td>{{ $d->updatedon }}</td>
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
            <label class="col-form-label">Email:</label>
            <input type="email" class="form-control" id="newEmail">
          </div>
          <div class="form-group">
            <label class="col-form-label">Password:</label>
            <input type="password" class="form-control" id="newPassword">
          </div>
          <div class="form-group">
            <label class="col-form-label">Status:</label>
            <select name="status" class="form-control" id="newStatus">
            	<option value="1">Active</option>
            	<option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="newName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Address:</label>
            <input type="text" class="form-control" id="newAddress">
          </div>          
          <div class="form-group">
            <label class="col-form-label">Phone:</label>
            <input type="number" class="form-control" id="newPhone">
          </div>
          <div class="form-group">
            <label class="col-form-label">Birthdady:</label>
            <input type="date" class="form-control" id="newBirthday">
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
            <label class="col-form-label">Email:</label>
            <input type="email" class="form-control" id="editEmail">
          </div>
          <div class="form-group">
            <label class="col-form-label">Password:</label>
            <input type="password" class="form-control" id="editPassword">
          </div>
          <div class="form-group">
            <label class="col-form-label">Status:</label>
            <select name="status" class="form-control" id="editStatus">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="editName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Address:</label>
            <input type="text" class="form-control" id="editAddress">
          </div>          
          <div class="form-group">
            <label class="col-form-label">Phone:</label>
            <input type="number" class="form-control" id="editPhone">
          </div>
          <div class="form-group">
            <label class="col-form-label">Birthday:</label>
            <input type="date" class="form-control" id="editBirthday">
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
	data.append('newEmail', $('#newEmail').val());
	data.append('newPassword', $('#newPassword').val());
	data.append('newStatus', $('#newStatus').val());
	data.append('newName', $('#newName').val());
	data.append('newAddress', $('#newAddress').val());
	data.append('newPhone', $('#newPhone').val());
	data.append('newBirthday', $('#newBirthday').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/admin/finance/save",  
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
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
	 $.get( "{{ URL::to('/') }}/admin/finance/view/"+ids, function( response ) {
    console.log(response);
		modal.find('#editEmail').val(response.email);
		modal.find('#editStatus').val(response.status);
		modal.find('#editName').val(response.name);
		modal.find('#editAddress').val(response.address);
		modal.find('#editPhone').val(response.phone);
		modal.find('#editBirthday').val(response.birthday);
	});
})

function updateData(){
	var id = $('#idEdit').val();
	var data = new FormData();
	data.append('editEmail', $('#editEmail').val());
	data.append('editPassword', $('#editPassword').val());
	data.append('editStatus', $('#editStatus').val());
	data.append('editName', $('#editName').val());
	data.append('editAddress', $('#editAddress').val());
	data.append('editPhone', $('#editPhone').val());
	data.append('editBirthday', $('#editBirthday').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/admin/finance/update/"+id,  
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


 	$.get( "{{ URL::to('/') }}/admin/finance/delete/"+id, function( response ) {
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

	</script>
@stop
