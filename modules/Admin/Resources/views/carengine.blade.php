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
    $thispage = isset($_GET['page']) ? $_GET['page'] : 1;
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
      <h5>Car Engine List</h5>
      <h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

      <div class='row'>
        <div class='col-md-3 col-xs-12' align='center'>
          <select id="maxpage" class='custom-select'>
            <?php
              $nqs = $qs;
            ?>
            <option <?php if ($limit === "5") echo 'selected' ?> <?php $nqs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">5</option>
            <option <?php if ($limit === "10") echo 'selected' ?> <?php $nqs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">10</option>
            <option <?php if ($limit === "20") echo 'selected' ?> <?php $nqs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">20</option>
            <option <?php if ($limit === "all") echo 'selected' ?> <?php $nqs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">All</option>
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
              $pqs = $qs;
            ?>
            <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $pqs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($pqs) }}">New-old</option>
            <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $pqs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($pqs) }}">Old-new</option>
          </select>

        </div>
        <div class='col-md-1 col-xs-12' align='center'>
          <button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
        </div>
      </div>
      
      <div class='row'>

        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th width='60%'>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($db['data'] as $d)
            <?php $i++ ?>
            <tr>
              <td>{{ $i }}</td>
              <td> {{ $d->description }} </td>
              <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">Update</button> <button onclick="deleteData({{ $d->id }})" class="btn btn-danger">Delete</button></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

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
            <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger">Prev</button></a> | 
          <?php }?>
      <?php if($thispage < $total_page){
            $qs['page'] = $thispage+1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              $current_url = Request::fullUrl();
              $explode = explode('?', $current_url);
        ?>
            <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger">Next</button></a>
          </caption>
      <?php }?>    	
        </div>
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
              <label class="col-form-label">Description:</label>
              <input type="text" class="form-control" id="newDescription">
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
              <label class="col-form-label">Description:</label>
              <input type="text" class="form-control" id="editDescription">
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
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
      $.get("{{ URL::to('/') }}/admin/carengine/view/" + ids, function(response) {
        console.log(response);
        modal.find('#editDescription').val(response.description);
      });
    })
    function updateData(){
      var id = $('#idEdit').val();
      var data = new FormData();
      data.append('editDescription', $('#editDescription').val());
      $.ajax({
      url: "{{ URL::to('/') }}/admin/carengine/update/" + id,
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
                  $.get("{{ URL::to('/') }}/admin/carengine/delete/" + id, function(response) {
                  location.reload();
                  });
                  } else {
                  swal("Your file is safe!");
                  }
                  });
          }
          $("#maxpage").change(function() {
          var max = $('#maxpage').val();
          window.location.href = max;
          });
          $("#descasc").change(function() {
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