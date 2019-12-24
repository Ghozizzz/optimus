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
  $qs['max'] = $getMax;
  
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
<h5>Setting List</h5>
<h6>{{ $total_item }} item found(shown: 1-{{ count($db['data']) }})</h6>

<div class='row'>
  <div class='col-md-3 col-xs-12' align="center">
   <select id="maxpage" class='custom-select'>
     <?php $nqs = $qs; ?>
    	<option <?php if($getMax === "5") echo 'selected' ?> <?php $nqs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">5</option>
			<option <?php if($getMax === "10") echo 'selected' ?> <?php $nqs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">10</option>
			<option <?php if($getMax === "20") echo 'selected' ?> <?php $nqs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">20</option>
			<option <?php if($getMax === "all") echo 'selected' ?> <?php $nqs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">All</option>
		</select>
  </div>
  
  <div class="col-md-5 col-xs-12 input-group" align="center">
    <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
    <div class="input-group-append">
      <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
    </div>
  </div>
  
  <div class='col-md-4 col-xs-12' align='right'>
    <select id="descasc" class='custom-select'>
      <?php $nqs = $qs; ?>
			<option <?php if($descasc === "desc") echo 'selected' ?> <?php $nqs['descasc'] = 'desc' ?> value="{{ $explode[0] . '?' . http_build_query($nqs) }}">New-old</option>
			<option <?php if($descasc === "asc") echo 'selected' ?> <?php $nqs['descasc'] = 'asc' ?> value="{{ $explode[0] . '?' . http_build_query($nqs) }}">Old-new</option>
		</select>
  </div>
</div>

<div class="row">
  <div class="col-xs-12" style="overflow: auto">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Field</th>
          <th>Value</th>
          <th colspan="1">Action</th>
        </tr>
      </thead>
      <tbody>

        @foreach($db['data'] as $d)
        <tr>
          <td>{{ ($i + 1) }}</td>
          <td>{{ $d->field }}</td>
          <td>{{ str_replace('<br>', '\n', $d->value) }}</td>
          <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">Update</button></td>
        </tr>
        <?php $i++ ?>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<div class='row'>
  <div class='col-md-12 bottom10' align="center">
    <caption>
      <?php if($thispage>1){
        $qs['page'] = $thispage-1;
        $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
        $current_url = Request::fullUrl();
        $explode = explode('?', $current_url);
        ?>
      <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger">Prev</button></a>
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
            <label class="col-form-label">Field:</label>
            <input type="text" class="form-control" id="editField" readonly>
           
          </div>
           <div class="form-group">
            <label class="col-form-label">Type:</label>
            <select class="form-control" id="editType">
              <option value="text">text</option>
              <option value="textarea">textarea</option>
            </select>
          </div>
         <div class="form-group"> 
            <label class="col-form-label">Value:</label>
            <input type="text" class="form-control" id="editValue">
            <textarea id="editValueArea" class="form-control"  rows="10"></textarea>
          </div>
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
  <script>
    var web_url = '{{$web_url}}';
    $("#search-btn").on('click',function(){
      window.location = web_url + '?filter=' + $("#filter").val().replace(/\s/g,"-");;
    })

    $('#filter').keypress(function(e) {
        if(e.which == 13) {
            $("#search-btn").trigger('click');
        }
    });
  
$("#editType").on('change',function(){
  if($("#editType").val() == 'text'){
    var text = $("#editValueArea").val();
    $("#editValueArea").hide();
    $("#editValue").show();
    $("#editValue").val(text);    
  }else{
    var text = $("#editValue").val();
    $("#editValue").hide();
    $("#editValueArea").show();
    $("#editValueArea").val(text);
  }
})

$('#editModal').on('show.bs.modal', function (event) {
  clearSetting();
  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes
  $('#idEdit').val(ids);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
   $.get( "{{ URL::to('/') }}/admin/setting/view/"+ids, function( response ) {
    
    modal.find('#editField').val(response.field);
    modal.find('#editType').val(response.type);
    modal.find('#editValue').val(response.value);
    if (response.type === "textarea") {
      $('#editValue').hide();
      $('#editValueArea').show();
      modal.find('#editValueArea').val(response.value);
    }else{
      $('#editValueArea').hide();
      $('#editValue').show();
      modal.find('#editValue').val(response.value);
    }
  });
})

function clearSetting(){
  $('#editValueArea').hide();
  $('#editValue').hide();
  $('#editValueArea').val('');
  $('#editValue').val('');
}

function updateData(){
  var id = $('#idEdit').val();
  var data = new FormData();
  data.append('editField', $('#editField').val());
  data.append('editType', $('#editType').val());  
  if ($('#editValueArea').val() !== "") {
    data.append('editValue', $('#editValueArea').val());
  }else{
    data.append('editValue', $('#editValue').val());
  }
  $.ajax({  
      url: "{{ URL::to('/') }}/admin/setting/update/"+id,  
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