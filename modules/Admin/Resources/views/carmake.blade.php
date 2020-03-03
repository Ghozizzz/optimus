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
    $qs['filter'] = isset($_GET['filter']) ? str_replace('-', ' ', $_GET['filter']) : '';
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
      <h5>Car Make List</h5>
      <h6>{{ $total_page }} item found(shown: {{($i+1)}} - {{ $total_page }})</h6>
      
      <div class='row'>
        <div class='col-md-3'>
          <select id="maxpage" class='custom-select'>
            <?php
            $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
            ?>
            <option <?php if ($limit === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">5</option>
            <option <?php if ($limit === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">10</option>
            <option <?php if ($limit === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">20</option>
            <option <?php if ($limit === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">All</option>
          </select>
        </div>

        <div class="col-md-5 col-xs-12 input-group">
          <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
          <div class="input-group-append">
            <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
          </div>
        </div>

        <div class='col-md-3 col-xs-12' align='right'>
          <select id="descasc" class='custom-select'>
            <?php
            $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
            ?>
            <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">New-old</option>
            <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Old-new</option>
          </select>
        </div>

        <div class='col-md-1 col-xs-1'>
          <button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
        </div>

      </div>
      <div class='row'>		
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Make ID</th>
              <th>Make</th>
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
          
            @foreach($db['data'] as $d)
            <?php $i++ ?>
            <tr>
              <td>{{ $i }}</td>
              <th> {{ $d->make }} </th>
              <td><img width="50" height="50" src="{{ URL::to('/').'/uploads/car_logo' }}/{{ $d->logo }}" alt=""></td>
              <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">Update</button> <button onclick="deleteData({{ $d->id }})" class="btn btn-danger">Delete</button></td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>

      <div class='row'>
        <div class='col-md-12' align='center'>
          <caption>
            <?php
            if ($thispage > 1) {
              $qs['page'] = $thispage - 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              ?>
            <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class='btn btn-danger bottom10'>Prev</button></a>
            <?php } ?>
            <?php
            if ($thispage < $total_page) {
              $qs['page'] = $thispage + 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              $current_url = Request::fullUrl();
              $explode = explode('?', $current_url);
              ?>
              <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="next"><button class='btn btn-danger bottom10'>Next</button></a>
            </caption>
<?php } ?>    	
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
              <center><img width="100" height="100" id="newImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/></center>
              <input type="file" id="newInputImage" />
            </div>
            <div class="form-group">
              <label class="col-form-label">Make:</label>
              <input type="text" class="form-control" id="newMake">
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
              <label class="col-form-label">Make:</label>
              <input type="text" class="form-control" id="editMake">
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
    var file_data = $('#newInputImage').prop('files')[0];
    var data = new FormData();
    data.append('newLogo', file_data);
    data.append('newMake', $('#newMake').val());
    data.append('newCorporate', $('#newCorporate').val());
    $.ajax({
    url: "{{ URL::to('/') }}/admin/carmake/save",
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
            $.get("{{ URL::to('/') }}/admin/carmake/view/" + ids, function(response) {
            console.log(response);
            modal.find('#editMake').val(response.make);
            modal.find('#editCorporate').val(response.corporate);
            modal.find('#editImage').attr('src', "{{ URL::to('/') }}" + "/uploads/car_logo/" +response.logo);
            });
    })
            function updateData(){
            var file_data = $('#editInputImage').prop('files')[0];
            var id = $('#idEdit').val();
            var data = new FormData();
            data.append('editLogo', file_data);
            data.append('editMake', $('#editMake').val());
            data.append('editCorporate', $('#editCorporate').val());
            $.ajax({
            url: "{{ URL::to('/') }}/admin/carmake/update/" + id,
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
      console.log(e.target.result);
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
            $.get("{{ URL::to('/') }}/admin/carmake/delete/" + id, function(response) {
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
