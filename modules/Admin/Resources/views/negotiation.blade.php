@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
{!! Html::style('/assets/css/select2.min.css') !!}
{!! Html::style('/assets/css/jquery.dataTables.min.css') !!}
<style type="text/css" media="screen">
	.sales{
		margin-left: 70%;
	}
</style>
@stop
@section('content')
<div class="full-container">
<div class="row">
<div class="col-md-2 left-menu-div">
<input type="hidden" id="idEdit">
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
<?php 
if($page_type == 'negotiation_list'){ ?>
  <h5>Negotiation List</h5>
<?php }else{ ?>
  <h5>Order List</h5>
<?php } ?>
<h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>
<div class='row'>
  <div class='col-md-3 col-xs-12' align="center">
   <select id="maxpage" class='custom-select'>
      <?php $nqs = $qs; ?>
    	<option <?php if($limit === "5") echo 'selected' ?> <?php $nqs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">5</option>
			<option <?php if($limit === "10") echo 'selected' ?> <?php $nqs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">10</option>
			<option <?php if($limit === "20") echo 'selected' ?> <?php $nqs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">20</option>
			<option <?php if($limit === "all") echo 'selected' ?> <?php $nqs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($nqs) }}">All</option>
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
      <?php $pqs = $qs; ?>
			<option <?php if($descasc === "desc") echo 'selected' ?> <?php $pqs['descasc'] = 'desc' ?> value="{{ $explode[0] . '?' . http_build_query($pqs) }}">New-old</option>
			<option <?php if($descasc === "asc") echo 'selected' ?> <?php $pqs['descasc'] = 'asc' ?> value="{{ $explode[0] . '?' . http_build_query($pqs) }}">Old-new</option>
		</select>
  </div>
</div>
<div class="row">
	<table class="table table-striped">
    <thead>
		<tr>
			<th>No</th>
      <th width="25%">Sender Information</th>
      <th width="25%">Item</th>
      <th width="30%">Status</th>
			<th width="20%">Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($db['data'] as $d)
		@if(in_array($d->id, $db['unread_negotiation']))
    <tr class='unread-negotiation'>
		@else 
    <tr>
    @endif
      <td>{{ ($i + 1) }}</td>
      		<td><span onclick="openBiodata('{{$d->customer_id}}')" class="clickable">{{$d->customer_email}}<br>{{$d->customer_name}}<br>{{$d->customer_address}}</span><br>
            {{ date('d M Y H:i A', strtotime($d->createdon)) . ' SG Time'}}</td>
      		<td> 
      			<img height="100" width="100" src="{{ URL::to('/') }}/uploads/car/{{ json_decode($d->picture)[0]->picture }}" style="border-radius:5px;"><br>
      			Engine no: {{ $d->serial }} <br>
            {{$d->vin}}<br>
             
             <?php if(isset($_GET['debug'])) {echo $d->id;}?>
      			</td>
      		<td><div>
                  @if($d->status == 1)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 2)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 3)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 4)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 5)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 6)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 7)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @elseif($d->status == 8)
                    <img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7" width="50px">
                    <img src="{{URL::to('/')}}/assets/images/step8.png" alt="step8" width="50px" style='background:#DBB;border-radius: 20px;'>
                  @endif
                </div>
                {{$d->status_description}}<br>
                {{$d->currency .' '. \App\Http\Controllers\API::currency_format($d->price)}}<br>
                @if(isset($d->logistic_name) && $d->logistic_name!= null)
                  Logistic by : {{$d->logistic_name}}
                @endif
                <br>
                <?php if($d->due_date != null){?>
                Due Date : {{date('d M Y', strtotime($d->due_date)) . ' SG Time'}}
                <?php }?>
          </td>
			<td>
        
				<a href="{{ URL::to('/') }}/admin/negotiation/view/{{ $d->id }}" class="btn btn-danger mini-act-btn">Message</a>
        <button data-ids="{{ $d->id }}" data-toggle="modal" data-target="#editModal" class="btn btn-danger mini-act-btn pull-right">Sales</button>
        <button data-ids="{{ $d->id }}" data-toggle="modal" data-target="#recommendationModal" class="btn btn-success margin-top10 act-btn">Recommend other car</button>
        <?php if($d->due_date != null && $d->status < 5){?>
            <button data-ids="{{ $d->invoice_id }}" data-due='{{$d->due_date}}' data-carid='{{ $d->car_id }}' data-toggle="modal" class="btn btn-info margin-top10 extend-button act-btn">Extend due date</button>
        <?php }?>        
        <?php if($d->status < 5) { ?>
          <button data-ids="{{ $d->id }}" data-carid='{{ $d->car_id }}' data-toggle="modal" class="btn btn-info margin-top10 cancel-btn act-btn">Cancel Negotiation</button>
        <?php } ?>
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
            $nqs = $qs;
            if ($thispage > 1) {
              $nqs['page'] = $thispage - 1;
              ?>
            <a href="{{ $web_url . '?' . http_build_query($nqs) }}" id="prev"><button class="btn btn-danger">Prev</button></a> 
            <?php } 
            if ($thispage < $total_page) {
              $nqs['page'] = $thispage + 1;
              ?>
            <a href="{{ $web_url . '?' . http_build_query($nqs) }}" id="next"><button class="btn btn-danger">Next</button></a>
            </caption>
        <?php } ?>     
        </div>
      </div>
      <div class="height10"></div>
      

</div>
</div>
{{-- Modal New --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <label class="col-form-label">Name Sales:</label>
            <select class="form-control editUserId">
				@foreach($db['sales'] as $s)
					<option value="{{ $s->id }}">{{ $s->name }}</option>
				@endforeach
			</select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button onclick="updateData()" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<!--recommendation modal-->
<div class="modal fade" id="recommendationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="recommendation-container">
          <div class="filter-div">
            <div class="row">
              <div class="col-md-3">
                <label>Make</label><br>
                <input type='hidden' id='negotiation_id'>
                <select id="make" class='form-control' style='width:100%'>
                  <option value="">Any</option>
                  @foreach($make_array as $make)
                    <option value="<?php echo $make->id?>" data-id="{{$make->id}}"><?php echo $make->make?></option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label>Model</label><br>
                <select id="model" class="form-control" name="model" disabled="disabled" style='width:100%'>
                  <option value="" selected="selected">Any</option>
                </select>
              </div>
              <div class="col-md-3">
                <label>From Year</label><br>
                <select id="start_year">
                  <option value="">Any</option>
                  @for($i=date('Y');$i>=(date('Y') - 30);$i--)
                    <option value="<?php echo $i?>"><?php echo $i?></option>
                  @endfor
                </select>
              </div>
              <div class="col-md-3">
                <label>To Year</label><br>
                <select id="end_year">
                  <option value="">Any</option>
                  @for($i=date('Y');$i>=(date('Y') - 30);$i--)
                    <option value="<?php echo $i?>"><?php echo $i?></option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="row">
              <div class='col-md-12' align='center'><button id='car-search-btn' class='btn btn-danger top10'>Search</button></div>
            </div>
          </div>
        </div>
        <div class='recommend-content'>
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="biodataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="biodata-container container">
          <div class="row">
            <div class="col-md-5">
              <div class="row">
                <div class="col-md-4">
                  <label>Name</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-name"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Email</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-email"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Phone</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-phone"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Birthday</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-birthday"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>Address</label>
                </div>
                <div class="col-md-8">
                  <label id="cust-address"></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12" style="margin-top:100px"><hr></div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label>Total Negotiation: </label>
                  <label id="total_negotiation"></label>
                </div>
              </div>
              <div class="row"
                <div class="col-md-12" id="total_negotiation_div">
                  
                </div>
              </div>


            <div class="col-md-7">
              <div class="row">
                <div class="col-md-12">
                  <label><b>Last 10 History</b></label>
                </div>
              </div>
              <div class="row">
                <!--<div class="col-md-12" id="tbl-history">-->
                <div class="col-md-12">
                  
                  <div id="cardetailtabs" class="historytabs">             
                    <ul class="nav nav-pills">
                      <li><a data-toggle="pill" class="active" href="#home">Negotiation</a></li>
                      <li><a data-toggle="pill" href="#menu1">Invoice</a></li>
<!--                      <li><a data-toggle="pill" href="#menu2">cancel</a></li>-->
                    </ul>

                    <div class="tab-content">
                      <div id="home" class="tab-pane fade in active show">
                        <div class="col-md-12" id="tbl-negotiation-history">
                          
                        </div>
                      </div>
                      <div id="menu1" class="tab-pane fade">
                        <div class="col-md-12" id="tbl-invoice-history">
                          
                        </div>
                      </div>
<!--                      <div id="menu2" class="tab-pane fade">
                        <div class="col-md-12" id="tbl-cancel-history">
                          
                        </div>
                      </div>-->
                    </div>
                  </div>
                  
                  
                </div>
              </div>
            </div>  
          </div>          
        </div>
      </div>
    </div>
  </div>
</div>


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
            <input type="hidden" id="car_id">
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
<script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/assets/js/admin.js"></script>
<script src="{{ URL::to('/') }}/assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/assets/js/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="{{URL::to('/')}}/assets/js/datetimepicker/locales/bootstrap-datetimepicker.id.js"></script>  
	<script>
    var web_url = '{{$web_url}}';
    var base_url = "{{ URL::to('/') }}";
    var image_asset_url = "{{URL::to('/').'/uploads/car/'}}";

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
  
function openBiodata(id){
  $.ajax({  
	    url: "{{ URL::to('/') }}/admin/customer/view/"+id,  
	    type: "GET",
	    processData: false, 
	    contentType: false,
	    success: function (response) {
	    	if (response.error) {
	    		alert(response.error);
	    	}
        var data = response.data;
        $("#cust-name").text(data.name);
        $("#cust-email").text(data.email);
        $("#cust-phone").text(data.phone);
        if(data.birthday !== ''){
          var bod = data.birthday.split('-');
          var month = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
          $("#cust-birthday").text(bod[2]+' '+ month[bod[1]] +' '+bod[0]);
        }
        $("#cust-address").text(data.address);
	    	$("#biodataModal").modal('show');        
        
        var total_history = response.total_history;
        var total_history_html = '';

        var total = 0;
        if(total_history != null){
          total_history_html += '<table>';
          total_history_html += '<tr><th>Status</th><th>Total</th></tr>';
          
           for(var x in total_history){
             total_history_html += '<tr>';
             total_history_html += '<td>'+ total_history[x].description +'</td>';
             total_history_html += '<td align="center">'+ total_history[x].total +'</td>';
             total_history_html += '</tr>';
             
             total += parseFloat(total_history[x].total);
           }
          total_history_html += '</table>'; 
        }
        $("#total_negotiation").text(total);
//        $("#total_negotiation_div").html(total_history_html);
        
        var history = response.negotiation_history;
        var history_html = '';
        if(history != null){
          history_html += '<table width="100%" style="text-align:center" width="100%" border="1"><tr><th>Car</th><th>Model</td><th>Negotiation Date</th><th>Status</th></tr>';

          for(var x in history){
            var img = history[x].picture;
            if(img !== ''){
              img = JSON.parse(img);

              if(img.length >0){
                var car_img = '<img src="'+base_url+'/uploads/car/'+ img[0]['picture'] +'" width="80px">';
              }else{
                var car_img = '';
              }              
            }else{
              var car_img = '';
            }
            var createdon = history[x].createdon;
            var negotiation_date = '';
            if(createdon !== ''){
              var date = new Date(createdon);
              
              negotiation_date = date.getDate() + ' ' + (month[date.getMonth() + 1]) + ' ' +  date.getFullYear();
            }
            history_html += '<tr>';
            history_html += '<td>'+ car_img +'</td>';
            history_html += '<td>'+ history[x].model +'</td>';         
            history_html += '<td>'+ negotiation_date +'</td>';         
            history_html += '<td>'+ history[x].status_description +'</td>';         
            history_html += '</tr>';
          }
          history_html += '</table>';
        }
      $("#tbl-negotiation-history").html(history_html);
      
      
        var history = response.invoice_history;
        var history_html = '';
        if(history != null){
          history_html += '<table width="100%" style="text-align:center" width="100%" border="1"><tr><th>Car</th><th>Model</td><th>Negotiation Date</th><th>Status</th></tr>';

          for(var x in history){
            var img = history[x].picture;
            if(img !== ''){
              img = JSON.parse(img);

              if(img.length >0){
                var car_img = '<img src="'+base_url+'/uploads/car/'+ img[0]['picture'] +'" width="80px">';
              }else{
                var car_img = '';
              }              
            }else{
              var car_img = '';
            }
            var createdon = history[x].createdon;
            var negotiation_date = '';
            if(createdon !== ''){
              var date = new Date(createdon);
              
              negotiation_date = date.getDate() + ' ' + (month[date.getMonth() + 1]) + ' ' +  date.getFullYear();
            }
            history_html += '<tr>';
            history_html += '<td>'+ car_img +'</td>';
            history_html += '<td>'+ history[x].model +'</td>';         
            history_html += '<td>'+ negotiation_date +'</td>';         
            history_html += '<td>'+ history[x].status_description +'</td>';         
            history_html += '</tr>';
          }
          history_html += '</table>';
        }
        $("#tbl-invoice-history").html(history_html);
      
	    },
	    error: function(error) {
	        console.log(error);
	    }
	});  
}    
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
function createNewNegotiation(car_id, negotiation_id){
  var response = confirm('Are you sure want to create new car negotiation?');
  if(response){
    var data = {
      'car_id' : car_id,
      'negotiation_id' : negotiation_id,
    }
    $.ajax({  
        url: "{{ URL::to('/') }}/admin/recommendation",  
        type: "POST",  
        data:  data,
        success: function (response) {
          response = JSON.parse(response);
          console.log(response);
          console.log(response.error);
          if(response.error == 0){
            location.reload();
          }else{
            alert('failed to create new recommendation');
            return false;
          }          
        },
        error: function(error) {
            console.log(error);
        }
    });
  }
}

function searchCarList(){
  var data = {
    'make' : $("#make").val(),
    'model' : $("#model").val(),
    'start_year' : $("#start_year").val(),
    'end_year' : $("#end_year").val(),
  }
  $.ajax({  
	    url: "{{ URL::to('/') }}/admin/car-list",  
	    type: "POST",  
	    data:  data,
	    success: function (response) {
        var data = response.data;
        
        var html_template = '<table id="myTable"><thead>'+
            '<tr>'+
                '<th> </th>'+
                '<th>Car</th>'+
                '<th>Item</th>'+
                '<th>Price</th>'+
            '</tr></thead><tbody>';
        
        for( x in data){
          if(data[x].picture == ''){
          var image = '';
          }else{
          var gallery = JSON.parse(data[x].picture);
          var image = gallery[0];
          }
          
          html_template += '<tr>'+
                '<td><button class="btn btn-default" onclick="createNewNegotiation(\''+data[x].id+'\',\''+ $("#negotiation_id").val() +'\')">Select</button></td>';
                
          if(typeof image.picture !== 'undefined' && image.picture !== ''){
            html_template += '<td><img src="'+image_asset_url+image.picture+'" width="50px"></td>';
          }else{
            html_template += '<td></td>';
          }
                
          html_template += '<td>'+data[x].manufacture_year+' '+data[x].make+'<br>'+ data[x].description +'<br>Vin: '+ data[x].vin +'</td>'+
                '<td>'+ (data[x].currency != '' ? data[x].currency : 'USD')  +' '+ data[x].price +'</td>'+
            '</tr>';
        }
        
        html_template +="</tbody></table>"
        
	    	$(".recommend-content").html(html_template);

        $('#myTable').DataTable();

	    },
	    error: function(error) {
	        console.log(error);
	    }
	});
}

$(".extend-button").on('click',function(){
  var id = $(this).data('ids'); // Extract info from data-* attributes
  var due = $(this).data('due'); // Extract info from data-* attributes
  var car_id = $(this).data('carid');
  
  $("#extend-date").modal('show');  
  $("#invoiceid").val(id);
  $("#car_id").val(car_id);
  $("#due_date").val(due);
})

$("#extended-btn").on('click',function(){
  var id = $("#invoiceid").val();
  var car_id = $('#car_id').val();
	var data = new FormData();
	data.append('id', id);
  data.append('car_id', car_id);
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
$("#car-search-btn").on('click',function(){
  searchCarList();
})

$('#editModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes
  $('#idEdit').val(ids);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
	 $.get( "{{ URL::to('/') }}/admin/negotiation/viewsales/"+ids, function( response ) {
		modal.find('.editUserId').val(response.user_id);
	});
})



$(".cancel-btn").on('click',function(){
  var id = $(this).data('ids'); // Extract info from data-* attributes
  var car_id = $(this).data('carid');
  var response = confirm('Are you sure want to cancel car negotiation?');
  if(response){
    var data = new FormData();
    data.append('editUserId', $('.editUserId').val());
    data.append('status', 0);
    data.append('car_id', car_id);
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
  
$('#recommendationModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes
  $("#negotiation_id").val(ids);
  $("#start_year").select2();
  $("#end_year").select2();
  $("#make").select2();
  $("#model").select2();
  
  $("#make").on('change',function(){    
    var requestData = {
      make_id : $("#make option:selected").attr('data-id'),      
    };
    
    $.ajax({
        headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
        url: "{{route('front.getCarModel')}}",
        data: requestData,
        type: 'post',
        dataType: 'json',
        beforeSend:function(){
        },
        success: function (result) {
          var select_template = '<option value="" selected="selected">Any</option>';
          for (x in result){
            select_template += '<option value="'+ result[x].id +'">'+ result[x].model +'</option>';
          }
          $("#model").html(select_template);
          $("#model").prop('disabled',false);
        }
    }); 
  })
  
  searchCarList();
})

function updateData(){
	var id = $('#idEdit').val();
	var data = new FormData();
	data.append('editUserId', $('.editUserId').val());
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

 	$.get( "{{ URL::to('/') }}/admin/carengine/delete/"+id, function( response ) {
		location.reload();
	});

	  } else {
	    swal("Your file is safe!");
	  }
	});
}


	
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