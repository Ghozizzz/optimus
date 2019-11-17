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
<h5>Dealers List</h5>
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
			<th>Dealer Name</th>
			<th>Last Active</th>
			<th>Status</th>
			<th>Last Update</th>
			<th colspan="2">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		@foreach($db['data'] as $d)
		<tr>
			<td>{{ $i }}</td>
			<td>{{ $d->pic_name }}</td>
			<td>{{ $d->last_active }}</td>
			<td><?php $d->status == 1 ? print "Verified" : print "Inactive" ?></td>
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
        		<center><img width="100" height="100" id="newImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/></center>
        		<input type="file" id="newInputImage" />
        	</div>
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
            	<option value="1">Verified</option>
            	<option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Country:</label>
            <input type="text" class="form-control" id="newCountry">
          </div>
          <div class="form-group">
            <label class="col-form-label">Address:</label>
            <input type="text" class="form-control" id="newAddress">
          </div>          
          <div class="form-group">
            <label class="col-form-label">Telephone:</label>
            <input type="number" class="form-control" id="newTelephone">
          </div>
          <div class="form-group">
            <label class="col-form-label">Fax:</label>
            <input type="number" class="form-control" id="newFax">
          </div>
          <div class="form-group">
            <label class="col-form-label">Contact:</label>
            <input type="text" class="form-control" id="newContact">
          </div>
          <div class="form-group">
            <label class="col-form-label">Company Name:</label>
            <input type="text" class="form-control" id="newCompanyName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Pic Name:</label>
            <input type="text" class="form-control" id="newPicName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Bank Name:</label>
            <input type="text" class="form-control" id="newBankName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Account Number:</label>
            <input type="text" class="form-control" id="newAccountNumber">
          </div>
          <div class="form-group">
            <label class="col-form-label">Account Name:</label>
            <input type="text" class="form-control" id="newAccountName">
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
            <center><img width="100" height="100" id="editImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/></center>
            <input type="file" id="editInputImage" />
          </div>
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
              <option value="1">Verified</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Country:</label>
            <input type="text" class="form-control" id="editCountry">
          </div>
          <div class="form-group">
            <label class="col-form-label">Address:</label>
            <input type="text" class="form-control" id="editAddress">
          </div>          
          <div class="form-group">
            <label class="col-form-label">Telephone:</label>
            <input type="number" class="form-control" id="editTelephone">
          </div>
          <div class="form-group">
            <label class="col-form-label">Fax:</label>
            <input type="number" class="form-control" id="editFax">
          </div>
          <div class="form-group">
            <label class="col-form-label">Contact:</label>
            <input type="text" class="form-control" id="editContact">
          </div>
          <div class="form-group">
            <label class="col-form-label">Company Name:</label>
            <input type="text" class="form-control" id="editCompanyName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Pic Name:</label>
            <input type="text" class="form-control" id="editPicName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Bank Name:</label>
            <input type="text" class="form-control" id="editBankName">
          </div>
          <div class="form-group">
            <label class="col-form-label">Account Number:</label>
            <input type="text" class="form-control" id="editAccountNumber">
          </div>
          <div class="form-group">
            <label class="col-form-label">Account Name:</label>
            <input type="text" class="form-control" id="editAccountName">
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
	var file_data = $('#newInputImage').prop('files')[0];
	var data = new FormData();
	data.append('newImage', file_data);
	data.append('newEmail', $('#newEmail').val());
	data.append('newPassword', $('#newPassword').val());
	data.append('newStatus', $('#newStatus').val());
	data.append('newCountry', $('#newCountry').val());
	data.append('newAddress', $('#newAddress').val());
	data.append('newTelephone', $('#newTelephone').val());
	data.append('newFax', $('#newFax').val());
	data.append('newContact', $('#newContact').val());
	data.append('newCompanyName', $('#newCompanyName').val());
	data.append('newPicName', $('#newPicName').val());
	data.append('newBankName', $('#newBankName').val());
	data.append('newAccountNumber', $('#newAccountNumber').val());
	data.append('newAccountName', $('#newAccountName').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/admin/dealer/save",  
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
          alert(error['responseJSON']['error']);
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
	 $.get( "{{ URL::to('/') }}/admin/dealer/view/"+ids, function( response ) {
		modal.find('#editEmail').val(response.email);
		modal.find('#editStatus').val(response.status);
		modal.find('#editCountry').val(response.country);
		modal.find('#editAddress').val(response.address);
		modal.find('#editTelephone').val(response.telephone);
		modal.find('#editFax').val(response.fax);
		modal.find('#editContact').val(response.contact);
		modal.find('#editCompanyName').val(response.company_name);
		modal.find('#editPicName').val(response.pic_name);
		modal.find('#editBankName').val(response.bank_name);
		modal.find('#editAccountNumber').val(response.account_number);
		modal.find('#editAccountName').val(response.account_name);
		modal.find('#editImage').attr('src', "{{ URL::to('/') }}/uploads/dealer/" + response.picture);
	});
})

function updateData(){
	var id = $('#idEdit').val();
	var file_data = $('#editInputImage').prop('files')[0];
	var data = new FormData();
	data.append('editImage', file_data);
	data.append('editEmail', $('#editEmail').val());
	data.append('editPassword', $('#editPassword').val());
	data.append('editStatus', $('#editStatus').val());
	data.append('editCountry', $('#editCountry').val());
	data.append('editAddress', $('#editAddress').val());
	data.append('editTelephone', $('#newTelephone').val());
	data.append('editFax', $('#editFax').val());
	data.append('editContact', $('#editContact').val());
	data.append('editCompanyName', $('#editCompanyName').val());
	data.append('editPicName', $('#editPicName').val());
	data.append('editBankName', $('#editBankName').val());
	data.append('editAccountNumber', $('#editAccountNumber').val());
	data.append('editAccountName', $('#editAccountName').val());
	$.ajax({  
	    url: "{{ URL::to('/') }}/admin/dealer/update/"+id,  
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


 	$.get( "{{ URL::to('/') }}/admin/dealer/delete/"+id, function( response ) {
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