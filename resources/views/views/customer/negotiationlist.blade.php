@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
@section('content')

<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('customer.left_menu')
    </div>

    <div class="col-md-10 right-menu-div">
      <?php
      $thispage = isset($_GET['page'])?$_GET['page'] : 1;
      $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
      $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
      $qs['filter'] = isset($_GET['filter']) ? str_replace('-',' ',$_GET['filter']) : '';
      $qs['max'] = $getMax;

      $current_url = Request::fullUrl();
      $explode = explode('?', $current_url);
      $web_url = $explode[0];

      $total_item = $negotiations['total'];
      $limit = isset($_GET['max'])?$_GET['max']:5;
      if($limit == 'all'){
        $total_page = 1;
        $i = 0;
      }else{
        $total_page = ceil($total_item / $limit);
        $i = ($thispage - 1) * $limit;
      }
      ?>

      <?php 
      if($page_type == 'negotiation_list'){ ?>
        <h5>Negotiation List</h5>
      <?php }else{ ?>
        <h5>Order List</h5>
      <?php } ?>
      <h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>


      
      <div class='row'>
        <div class='col-md-3'>
          <select id="maxpage" class='custom-select'>
            <?php
            $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'desc';
            ?>
            <option <?php if ($limit === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">5</option>
            <option <?php if ($limit === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">10</option>
            <option <?php if ($limit === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">20</option>
            <option <?php if ($limit === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">All</option>
          </select>
        </div>
        <div class="col-md-5 col-xs-12 input-group">
          <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
          <div class="input-group-append">
            <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
          </div>
        </div>

        <div class='col-md-4 col-xs-12' align='right'>
          <select id="descasc" class='custom-select'>
            <?php
            $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
            ?>
            <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">New-old</option>
            <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $explode[0] . '?' . http_build_query($qs) }}">Old-new</option>
          </select>
        </div>
      </div>
      <div class="row">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th width="25%">Sender Information</th>
              <th width="30%">Item</th>
              <th width="40%">Status</th>
              <th>Negotiation</th>
            </tr>
          </thead>
          <tbody>
            @foreach($negotiations['negotiation'] as $d)
            @if(in_array($d->id,$negotiations['unread_negotiation']))
              <tr class='unread-negotiation'>
            @else  
              <tr>
            @endif
              <td>{{ $i+1 }}</td>
              <td><span><img src="{{ URL::to('/') }}/img/message.png" height="40" width="40"></span>
                <span>{{$d->customer_email}}<br>{{$d->customer_name}}<br>{{$d->customer_address}}</span><br>
                {{ date('d M Y H:i A', strtotime($d->createdon)) . ' SG Time' }}</td>
              <td> 
                <img height="100" width="100" src="{{ URL::to('/') }}/uploads/car/{{ json_decode($d->picture)[0]->picture }}" alt=""><br>
                Engine no: {{ $d->serial }} <br>
                {{$d->vin}}<br>
                
              </td>
              <td>
                <div>
                  @if($d->status == 1)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                  @elseif($d->status == 2)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                  @elseif($d->status == 3)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                  @elseif($d->status == 4)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                  @elseif($d->status == 5)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
                  @elseif($d->status == 6)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
                  @elseif($d->status == 7)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="30px">
                    <img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7" width="30px">
                  @elseif($d->status == 8)
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
                {{$d->currency .' '. \App\Http\Controllers\API::currency_format($d->price)}}
                @if(isset($d->logistic_name) && $d->logistic_name!= null)
                  Logistic by : {{$d->logistic_name}}
                @endif
                <br>
                <?php if($d->due_date != null){?>
                Due Date : {{date('d M Y', strtotime($d->due_date))}}
                <?php }?>
                
              </td>
              <td align='left'>
                <a href="{{ route('customer.negotiation',['id'=>$d->id])}}" class="btn btn-danger">Message</a>                
              </td>
            </tr>
<?php $i++ ?>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class='row'>
        <div class='col-md-12' align="center">
          <caption>
            <?php
            if ($thispage > 1) {
              $qs['page'] = $thispage - 1;
              $qs['max'] = $getMax;
              $qs['descasc'] = $descasc;
              ?>
            <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger">Prev</button></a> | 
            <?php } ?>
            <?php
            if ($thispage < $total_page) {
              $qs['page'] = $thispage + 1;
              $qs['max'] = $getMax;
              $qs['descasc'] = $descasc;
              ?>
              <a href="{{ $explode[0] . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger">Next</button></a>
            </caption>
<?php } ?>    	
        </div>
      </div>
      <div class="height10"></div>
    </div>
  </div>

</div>
<!-- /.row -->

<!-- Modal -->
<div class="modal fade" id="extend-date" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>        
      </div>
      <div class="modal-body">
        <div class='row'>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Extend Date
          </div>
        </div>
        <div class='row'>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type='hidden' id='invoiceid'>
            <input class='form-control' type='text' name='due_date' id="due_date" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" autocomplete="off">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id='extended-btn'>Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->


@stop


@section('script')
<script src="{{URL::to('/')}}/assets/js/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="{{URL::to('/')}}/assets/js/datetimepicker/locales/bootstrap-datetimepicker.id.js"></script>
<script>
  var web_url = '{{$web_url}}';
  $('#due_date').datetimepicker({
    language:  'en',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
});

$(".extend-button").on('click',function(){
  var id = $(this).data('ids'); // Extract info from data-* attributes
  var due = $(this).data('due'); // Extract info from data-* attributes
  
  $("#extend-date").modal('show');  
  $("#invoiceid").val(id);
  $("#due_date").val(due);
})

$("#extended-btn").on('click',function(){
  var id = $("#invoiceid").val();
	var data = new FormData();
	data.append('id', id);
	data.append('due_date', $("#due_date").val());
	$.ajax({  
	    url: "{{ route('admin.extendDate') }}",  
	    type: "POST",  
	    data:  data,
	    processData: false,  
	    contentType: false, 
	    success: function (response) {
        console.log(response);
	    	if (response.error) {
	    		alert(response.error);
	    	}
      location.reload();
	    },
	    error: function(error) {
	        console.log(error);
	    }
	});
})

$( "#maxpage" ).change(function() {
		var max = $('#maxpage').val();
		window.location.href = max;
	});

	$( "#descasc" ).change(function() {
		var descasc = $('#descasc').val();
		window.location.href = descasc;
	});
  
  $("#search-btn").on('click', function(){
    window.location = web_url + '?filter=' + $("#filter").val().replace(/\s/g, "-"); ;
  })

  $('#filter').keypress(function(e) {
    if (e.which == 13) {
      $("#search-btn").trigger('click');
    }
  });
  
$(".cancel-btn").on('click',function(){
  var id = $(this).data('ids'); // Extract info from data-* attributes
  
  var response = confirm('Are you sure want to cancel car negotiation?');
  if(response){
    var data = new FormData();
    data.append('editUserId', $('.editUserId').val());
    data.append('status', 0);
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
  }else{
    return false;
  }
    
})  
</script>
@stop
