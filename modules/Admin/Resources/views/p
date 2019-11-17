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
    $limit = isset($_GET['max']) ? $_GET['max'] : 5;
    if ($limit == 'all') {
      $total_page = 1;
      $i = 0;
    } else {
      $total_page = ceil($total_item / $limit);
      $i = ($thispage - 1) * $limit;
    }
    ?>
    <div class="col-md-10 right-menu-div">
      <br>
      <h5>Banner List</h5>
      <h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

      <div class='row'>
        <div class='col-md-3 col-xs-12' align="center">
          <select id="maxpage" class='custom-select'>
            <?php
            $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
            $current_url = Request::fullUrl();
            $explode = explode('?', $current_url);
            ?>
            <option <?php if ($limit === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">5</option>
            <option <?php if ($limit === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">10</option>
            <option <?php if ($limit === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">20</option>
            <option <?php if ($limit === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">All</option>
          </select>
        </div>

        <div class="col-md-5 col-xs-12 input-group" align="center">
          <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
          <div class="input-group-append">
            <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
          </div>
        </div>

        <div class='col-md-3 col-xs-12' align="center">
          <select id="descasc" class='custom-select'>
            <?php
            $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
            $current_url = Request::fullUrl();
            $explode = explode('?', $current_url);
            ?>
            <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">New-old</option>
            <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Old-new</option>
          </select>

        </div>
        <div class='col-md-1 col-xs-12' align='center'>
          <button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
        </div>
      </div>		
      <div class="row">		
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>Picture</th>
              <th>Status</th>
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($db['data'] as $d)
            <tr>
              <td>{{ $i }}</td>
              <td>{{ $d->name }}</td>
              <td><img src='{{ URL::to('/') }}/uploads/banner/{{ $d->picture }}' width='100px'></td>
              <td><?php $d->isactive == 1 ? print "Active" : print "Inactive" ?></td>
              <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">Update</button> <button onclick="deleteData({{ $d->id }})" class="btn btn-danger">Delete</button></td>
            </tr>
            <?php $i++ ?>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class='row'>
        <div class='col-md-12 bottom10'>
          <caption>
            <?php
            if ($thispage > 1) {
              $qs['page'] = $thispage - 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              $current_url = Request::fullUrl();
              $explode = explode('?', $current_url);
              ?>
              <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger">Prev</button></a>
            <?php } ?>
            <?php
            if ($thispage < $total_page) {
              $qs['page'] = $thispage + 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              $current_url = Request::fullUrl();
              $explode = explode('?', $current_url);
              ?>
              <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger">Next</button></a>
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
              <center>Recommended : 1200px x 430px</center>
              <br>
              <center><img width="100" height="100" id="newImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/></center>
              <input type="file" id="newInputImage" />
            </div>
            <div class="form-group">
              <label class="col-form-label">Name:</label>
              <input type="text" class="form-control" id="newName">
            </div>
            <div class="form-group">
              <label class="col-form-label">Url:</label>
              <input type="text" class="form-control" id="newUrl">
            </div>
            <div class="form-group">
              <label class="col-form-label">Status:</label>
              <select name="status" class="form-control" id="newStatus">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
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
              <center>Recommended : 1200px x 430px</center>
              <br>
              <center><img width="100" height="100" id="editImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/></center>
              <input type="file" id="editInputImage" />
            </div>
            <div class="form-group">
              <label class="col-form-label">Name:</label>
              <input type="text" class="form-control" id="editName">
            </div>
            <div class="form-group">
              <label class="col-form-label">Url:</label>
              <input type="text" class="form-control" id="editUrl">
            </div>
            <div class="form-group">
              <label class="col-form-label">Status:</label>
              <select name="status" class="form-control" id="editStatus">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
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
  <script src="{{URL::to('/')}}/assets/js/admin.js"></script>
  <script>
    var web_url = '{{$web_url}}';
    function saveData(){
    var file_data = $('#newInputImage').prop('files')[0];
    var data = new FormData();
    data.append('newImage', file_data);
    data.append('newName', $('#newName').val());
    data.append('newUrl', $('#newUrl').val());
    data.append('newStatus', $('#newStatus').val());
    $.ajax({
    url: "{{ URL::to('/') }}/admin/banner/save",
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
            $.get("{{ URL::to('/') }}/admin/banner/view/" + ids, function(response) {
            modal.find('#editName').val(response.name);
            modal.find('#editUrl').val(response.url);
            modal.find('#editStatus').val(response.isactive);
            modal.find('#editImage').attr('src', "{{ URL::to('/') }}/uploads/banner/" + response.picture);
            modal.find('#editImage').attr('width', "200px");
            });
    })

            function updateData(){
            var id = $('#idEdit').val();
            var file_data = $('#editInputImage').prop('files')[0];
            var data = new FormData();
            data.append('editImage', file_data);
            data.append('editName', $('#editName').val());
            data.append('editUrl', $('#editUrl').val());
            data.append('editStatus', $('#editStatus').val());
            $.ajax({
            url: "{{ URL::to('/') }}/admin/banner/update/" + id,
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
            $.get("{{ URL::to('/') }}/admin/banner/delete/" + id, function(response) {
            location.reload();
            });
            } else {
            swal("Your file is safe!");
            }
            });
    }
  </script>
  @stop