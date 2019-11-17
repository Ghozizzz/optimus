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
    $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
    $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
    $qs['filter'] = isset($_GET['filter']) ? str_replace('-',' ',$_GET['filter']) : '';
    $qs['max'] = isset($_GET['max'])?$_GET['max'] : '5';

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
      @if($type == 'port_destination')
        <h5>Port Destinaion</h5>
      @else
        <h5>Port Disharge</h5>
      @endif
      <h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

      <div class='row'>
        <div class='col-md-3 col-xs-12' align="center">
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
        <div class="col-md-1 col-xs-12" align="center">
          <button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button>
        </div>	
      </div>
      <div class="row">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Port Name</th>
              <th>Port ID</th>
              <th>Country Code</th>
              <th>Port Code</th>			
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
<?php $i = 1; ?>
            @foreach($db['data'] as $d)
            <tr>
              <td>{{ $d->port_name }}</td>
              <td>{{ $d->id_code }}</td>
              <td>{{ $d->country_code }}</td>
              <td>{{ $d->port_code }}</td>
              <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">Update</button> 
                <button onclick="deleteData({{ $d->id }})" class="btn btn-danger">Delete</button></td>
            </tr>
<?php $i++ ?>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class='row'>
        <div class='col-md-12 bottom10' align="center">
          <caption>
            <?php
            if ($thispage > 1) {
              $qs['page'] = $thispage - 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              ?>
            <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger bottom10">Prev</button></a>  
            <?php } ?>
            <?php
            if ($thispage < $total_page) {
              $qs['page'] = $thispage + 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              ?>
            <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger bottom10">Next</button></a>
            </caption>
<?php } ?>     
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
            <input type="hidden" class="form-control" id="new_port_type" value='{{$type}}'>            
            <div class="form-group">
              <label class="col-form-label">Port ID:</label>
              <input type="text" class="form-control" id="id_code">
            </div>
            <div class="form-group">
              <label class="col-form-label">Country Code:</label>
              <select name="country_code" class="form-control" id="country_code">
                @foreach($country['data'] as $country_detail)
                <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Port Code:</label>
              <input type="text" class="form-control" id="port_code">
            </div>
            <div class="form-group">
              <label class="col-form-label">Port Name:</label>
              <input type="text" class="form-control" id="port_name">
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
            <input type="hidden" class="form-control" id="edit_port_type" value='{{$type}}'>
            <input type="hidden" class="form-control" id="edit_id">
            <div class="form-group">
              <label class="col-form-label">Port ID:</label>
              <input type="text" class="form-control" id="edit_id_code" disabled="true">
            </div>
            <div class="form-group">
              <label class="col-form-label">Country Code:</label>
              <select name="country_code" class="form-control" id="edit_country_code">
                <option value="" disabled>-- select country --</option>
                @foreach($country['data'] as $country_detail)
                <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Port Code:</label>
              <input type="text" class="form-control" id="edit_port_code">
            </div>
            <div class="form-group">
              <label class="col-form-label">Port Name:</label>
              <input type="text" class="form-control" id="edit_port_name">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button onclick="updateData()" type="button" class="btn btn-primary" id='update-btn'>Update</button>
        </div>
      </div>
    </div>
  </div>

  @stop
  @section('script')
  <script src="{{URL::to('/')}}/assets/js/admin.js"></script>
  <script>
    var web_url = '{{$web_url}}';
    $("#country_code").select2();
    function clearData(){
      $('#id_code').val('');
      $('#port_code').val('');
      $('#country_code').val('');
      $('#port_name').val('');
    }
    function saveData(){
      if($('#id_code').val() == ''){
        alert('Port Id required');
        return false;
      }
      $("#save-btn").prop('disabled',true);
      var data = new FormData();
      data.append('id_code', $('#id_code').val());
      data.append('country_code', $('#country_code').val());
      data.append('country_name', $("#edit_country_code option:selected").text());
      data.append('port_code', $('#port_code').val());
      data.append('port_name', $('#port_name').val());
      if($("#new_port_type").val() == 'port_destination'){
        var url = "{{ URL::to('/') }}/admin/port-destination/save";
      }else{
        var url = "{{ URL::to('/') }}/admin/port-discharge/save";
      }

      $.ajax({            
          url: url,
          type: "POST",
          data:  data,
          processData: false,
          contentType: false,
          success: function (response) {
          if (response.error) {
            alert(response.error);
            return false;
          }
          location.reload();
          $("#save-btn").prop('disabled',false);
        },
        error: function(error) {
          console.log(error);
        }
      });
      }

      $('#newModal').on('show.bs.modal', function (event) {
        clearData();
      })
      $('#editModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
              var ids = button.data('ids') // Extract info from data-* attributes
              $('#edit_id').val(ids);
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this);
      if($("#edit_port_type").val() == 'port_destination'){
        var url = "{{ URL::to('/') }}/admin/port-destination/view/";
      }else{
        var url = "{{ URL::to('/') }}/admin/port-discharge/view/";
      }
        $.get(url + ids, function(response) {
          modal.find('#edit_port_name').val(response.port_name);
          modal.find('#edit_id_code').val(response.id_code);
          modal.find('#edit_country_code').val(response.country_code);
          modal.find('#edit_port_code').val(response.port_code);
          modal.find('#edit_port_name').val(response.port_name);
          $("#edit_country_code").select2();
        });
      })

              function updateData(){
              if($("#edit_port_type").val() == 'port_destination'){
                var url = "{{ URL::to('/') }}/admin/port-destination/update/";
              }else{
                var url = "{{ URL::to('/') }}/admin/port-discharge/update/";
              }  
              var id = $('#edit_id').val();
              var data = new FormData();
              data.append('port_name', $('#edit_port_name').val());
              data.append('id_code', $('#edit_id_code').val());
              data.append('country_code', $('#edit_country_code').val());
              data.append('country_name', $("#edit_country_code option:selected").text());
              data.append('port_code', $('#edit_port_code').val());
              data.append('port_name', $('#edit_port_name').val());
              $.ajax({
              url: url + id,
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

      function deleteData(id){
        if($("#edit_port_type").val() == 'port_destination'){
          var url = "{{ URL::to('/') }}/admin/port-destination/delete/";
        }else{
          var url = "{{ URL::to('/') }}/admin/port-discharge/delete/";
        }

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

            $.get(url + id, function(response) {
              location.reload();
            });
          } else {
            swal("Your file is safe!");
          }
        });
      }
  </script>
  @stop
