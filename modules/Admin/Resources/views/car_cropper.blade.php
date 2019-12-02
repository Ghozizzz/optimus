@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
{!! Html::style('/assets/css/select2.min.css') !!}
{!! Html::style('/assets/css/dropzone.css') !!}
{!! Html::style('/assets/css/imgareaselect-default.css') !!}
{!! Html::style('/assets/css/cropper.min.css') !!}
<style type="text/css">
  @media screen and (max-width: 1366px){
    table{
      font-size: 12px;
    }
  }
  .modal { overflow-y: auto }
</style>
@stop
@section('content')
<div class="full-container">
  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('admin::layouts.menu')
    </div>
    <?php
    $thispage = isset($_GET['page']) ? $_GET['page'] : 1;
    $qs['status'] = isset($_GET['status']) ? $_GET['status'] : 1;
    $qs['filter'] = isset($_GET['filter']) ? str_replace('-', ' ', $_GET['filter']) : '';
    $current_url = Request::fullUrl();
    $explode = explode('?', $current_url);
    $web_url = $explode[0];
    $car_status = $qs['status'];
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
      <div class="row">
        <div class="col-md-12">
          
        
          <br>
          <h5>Car List</h5>
          <h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

          <div class='row'>
            <div class='col-md-2 col-lg-2 col-xs-12' align="center">
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

            <div class="col-md-5 col-xs-12 input-group" align="center">
              <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
              <div class="input-group-append">
                <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
              </div>
            </div>

            <div class='col-md-2 col-xs-12' align='center'>
              <select id="descasc" class='custom-select'>
                <?php
                $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
                $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
                ?>
                <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">New-old</option>
                <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Old-new</option>
              </select>
            </div>
            
            <div class='col-md-2 col-xs-12' align='center'>
              <select id="car_status" class='custom-select'>
                <?php
                $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
                $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
                $qs['status'] = isset($_GET['status']) ? $_GET['status'] : '1';
                ?>
                <option <?php if ($status === "1") echo 'selected' ?> <?php $qs['status'] = '1' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Available</option>
                <option <?php if ($status === "4") echo 'selected' ?> <?php $qs['status'] = '4' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Unavailable</option>
                <option <?php if ($status === "2") echo 'selected' ?> <?php $qs['status'] = '2' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Sold</option>
                <option <?php if ($status === "") echo 'selected' ?> <?php $qs['status'] = '' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">All</option>
              </select>
            </div>
            
            <div class="col-md-1 col-xs-12" align="center">
              <button class="btn btn-danger" id='add-car'>Add</button>
            </div>
          </div>
          <div class="row" style="overflow-x: auto;overflow-y: hidden;transform:rotateX(180deg);-ms-transform:rotateX(180deg); /* IE 9 */-webkit-transform:rotateX(180deg);margin-top:10px;">
            <table class="table table-striped" style="transform:rotateX(180deg);-ms-transform:rotateX(180deg); /* IE 9 */-webkit-transform:rotateX(180deg);">
              <thead>
                <tr>
                  <th><input type="checkbox" name="check_all" id="check_all"></th>
                  <th>Car</th>                 
                  <th>Model</th>
                  <th>Vehicle Number</th>
                  <th>Registration Date</th>
                  <th>Chasis Number</th>
                  <th>FOB Price</th>
                  <th>Mileage(KM)</th>
                  <th>Dealer</th>
                  <th>Latest PI</th>
                  
                  <th colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                    $month_arr = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                ?>
                @foreach($db['data'] as $d)
                <?php
                $i++;
                $picture_arr = json_decode($d->picture);
                
                if($d->registration_date == '0000-00-00' || $d->registration_date == ''){
                  $registration_date = '';
                }else{
                  $registration_date_arr = explode('-',$d->registration_date);
                  $registration_date = $registration_date_arr[2] .' '. $month_arr[(int)$registration_date_arr[1]] .' '. $registration_date_arr[0];
                }
                ?>
                <tr>
                  <td><input type="checkbox" name="ids" class="ids" data-id="{{ $d->id }}"></td>
                  <td>@if(isset($picture_arr[0]->picture))<img src="{{URL::to('/')}}/uploads/car/{{ $picture_arr[0]->picture }}" width='60px'>@endif</td>
                  <td>{{ $d->model .'/'. $d->make }}</td>
                  <td>{{ $d->plate_number }}</td>
                  <td>{{ $registration_date }}</td>
                  <td>{{ $d->vin }}</td>                  
                  <td>{{ $d->currency.' '. number_format($d->price,'0','.',',') }}</td>
                  <td>{{ number_format($d->distance,'0','.',',') }}</td>
                  <td>{{ $d->pic_name }}</td>
                  <td>{{ $d->proforma_invoice }}</td>
                  <td>
                    <button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" data-action="edit" class="btn btn-sm btn-danger act-btn">Edit</button><br>
                    <?php if($car_status != '4'){ ?>
                    <button onclick="unavailableCar({{ $d->id }})" class="btn btn-sm btn-danger act-btn" style="margin-top:5px;">Unavailable</button><br>
                    <button onclick="deleteData({{ $d->id }})" class="btn btn-sm btn-danger act-btn" style="margin-top:5px;">Delete</button>
                    <br><br>
                    <button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" data-action="copy" class="btn btn-sm btn-success act-btn">Copy Similiar Item</button>
                    <?php }?>
                    <?php if($car_status == '4') { ?>
                      <button onclick="availableCar({{ $d->id }})" class="btn btn-sm btn-danger act-btn" style="margin-top:5px;">Available</button>
                    <?php } ?>
                  </td>
                </tr>
    
                @endforeach
              </tbody>
            </table>
          </div>
          <?php 
          if($car_status != '4'){ ?>
          <button onclick="unavailableAll()" class="btn btn-danger" style="margin-top:5px;">Unavailable</button>
          <?php } ?>
        </div>
      </div>
      
      <div class='row'>
        <div class='col-md-12' align="center">
          <caption>
            <?php
            if ($thispage > 1) {
              $qs['page'] = $thispage - 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              ?>
            <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger">Prev</button></a> 
            <?php } ?>
            <?php
            if ($thispage < $total_page) {
              $qs['page'] = $thispage + 1;
              $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
              $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
              $current_url = Request::fullUrl();
              $explode = explode('?', $current_url);
              ?>
            <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger">Next</button></a>
            </caption>
    <?php } ?>     
        </div>
      </div>
      <div class="height10"></div>
  
    </div>
    
  </div>
  
  

  {{-- Edit Data --}}
  <div class="modal fade" id="editModal" data-toggle="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Car</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <input type="hidden" id="idEdit"/>
            <input type="hidden" id="editCurrencySymbol" value="USD">  
            <div class='row'>

              <div class='col-md-6 col-xs-12'>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Vehicle Number *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="plate_number" required>                        
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Chassis No *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <input type="text" class="form-control" id="editVin" required>
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Make *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control dropdownSelect2" id="editMake" required>
                          <option value="">Select</option>
                          @foreach($db['carmake'] as $make)
                          <option value="{{ $make->id }}" data-id="{{$make->id}}">{{ $make->make }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Model *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="editModel" class="form-control dropdownSelect2" id="editModel" required>
                          <option value="">Select</option>
                          @foreach($db['carmodel'] as $c)
                          <option value="{{ $c->id }}">{{ $c->model }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>


                  </div>

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Product Type *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="editType" class="form-control dropdownSelect2" id="editType" required>
                          <option value="">Select</option>
                          @foreach($db['car_type'] as $car_type)
                            <option value="{{$car_type->id}}">{{$car_type->description}}</option>
                          @endforeach                                                  
                        </select>
                      </div>
                    </div>
                  </div>

                </div>                
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Registration Date
                      </div>
<!--                      <div class='col-md-6 col-xs-6'>
                        <input class='form-control' type='text' name='registrationDate' id="registrationDate" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" autocomplete="off">
                      </div>-->
                      <div class='col-md-2 col-xs-2'>
                        <select name="editRegistrationYear" class="form-control dropdownSelect2" id="editRegistrationYear">
                          <option value="">Select</option>
                          <?php
                          $now_year = date('Y');
                          for ($i = $now_year; $i > ($now_year - 20); $i--) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class='col-md-2 col-xs-2'>
                        <select name="editRegistrationMonth" class="form-control dropdownSelect2" id="editRegistrationMonth">
                          <option value="">Select</option>
                          <option value="01">January</option>                        
                          <option value="02">February</option>                        
                          <option value="03">March</option>                        
                          <option value="04">April</option>                        
                          <option value="05">May</option>                        
                          <option value="06">June</option>                        
                          <option value="07">July</option>                        
                          <option value="08">August</option>                        
                          <option value="09">September</option>                        
                          <option value="10">October</option>                        
                          <option value="11">November</option>                        
                          <option value="12">December</option>                        
                        </select>
                      </div>
                      <div class='col-md-2 col-xs-2'>
                        <select name="editRegistrationDay" class="form-control dropdownSelect2" id="editRegistrationDay">
                          <option value="">Select</option>                                                  
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Manufacture Year/Month
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <select name="editManufactureYear" class="form-control dropdownSelect2" id="editManufactureYear">
                          <option value="">Select</option>
                          <?php
                          $now_year = date('Y');
                          for ($i = $now_year; $i > ($now_year - 20); $i--) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <select name="editManufactureMonth" class="form-control dropdownSelect2" id="editManufactureMonth">
                          <option value="">Select</option>
                          <option value="1">January</option>                        
                          <option value="2">February</option>                        
                          <option value="3">March</option>                        
                          <option value="4">April</option>                        
                          <option value="5">May</option>                        
                          <option value="6">June</option>                        
                          <option value="7">July</option>                        
                          <option value="8">August</option>                        
                          <option value="9">September</option>                        
                          <option value="10">October</option>                        
                          <option value="11">November</option>                        
                          <option value="12">December</option>                        
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Mileage (KM) *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editDistance" onkeypress='validate(event)' required>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Engine Capacity (cc) *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editEngine" required>                        
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Motor Number
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="motor_number">                        
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Engine No
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editSerial" required>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Fuel *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editFuel" required>
                          <option value="">Select</option>
                          <option value="CNG">CNG</option>
                          <option value="Diesel">Diesel</option>
                          <option value="Electric">Electric</option>
                          <option value="LPG">LPG</option>
                          <option value="Other">Other</option>
                          <option value="Petrol">Petrol</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Steering *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editSteering">
                          <option value="1">Left</option>
                          <option value="2" selected>Right</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Transmission *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editTransmission" required>
                          <option value="">Select</option>
                          @foreach($db['car_transmission'] as $car_transmission_detail)
                          <option value="{{$car_transmission_detail->id}}">{{$car_transmission_detail->description}}</option>
                          @endforeach                      
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Drive Type
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editDrive">
                          <option value="">Select</option>
                          <option value="2WD">2WD</option>
                          <option value="4WD">4WD</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Exterior Color *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name='editExteriorColor' id='editExteriorColor' class='form-control' required>
                        @foreach($db['car_color'] as $car_color)
                            <option value="{{$car_color->description}}">{{$car_color->description}}</option>
                        @endforeach
                        </select>
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Interior Color
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name='editInteriorColor' id='editInteriorColor' class='form-control'>
                        @foreach($db['car_color'] as $car_color)
                            <option value="{{$car_color->description}}">{{$car_color->description}}</option>
                        @endforeach
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Number of Doors *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editDoor" required onkeypress='validate(event)'>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Total Seats
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editSeat" onkeypress='validate(event)'>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Total Weight (Kg) *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editWeight" required onkeypress='validate(event)'>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Description
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <textarea class="form-control" id="editDescription"></textarea>
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Selling Point
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <textarea class="form-control" id="editSellingPoint"></textarea>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Keyword
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editKeyword">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Agent
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control dropdownSelect2" id="editAgent">
                          @foreach($db['agent'] as $c)
                          <option value="{{ $c->id }}">{{ $c->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Buyer
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editBuyer">
                      </div>
                    </div>

                  </div>

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Size *
                        <br>
                        <hr>
                        <div id='volume' width='100%'></div>
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <div class='row'>
                          <div class='col-md-3 col-xs-3'>Length *</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editLength" required></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>
                        <div class='row'>
                          <div class='col-md-3 col-xs-3'>Width *</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editWidth" required></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>
                        <div class='row'>  
                          <div class='col-md-3 col-xs-3'>Height *</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editHeight" required></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>  

                      </div>
                    </div>
                  </div>

                </div>


              </div>

              <div class='col-md-6 col-xs-12'>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Trade Price (US$) *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <input type="text" class="form-control" id="editPrice" onkeypress='validate(event)' required>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Seller *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="status" class="form-control" id="editSeller" required>
                          @foreach($db['seller'] as $c)
                          <option value="{{ $c->id }}">{{ $c->pic_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row'>
                      <div class='col-md-12 col-xs-12 dark-div'>
                        Remarks (Car Conditions)
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-12 col-xs-12'>
                        <select name="status" class="form-control" id="editState">
                          <option value="2">Used</option>
                          <option value="1">New</option>
                          <option value="3">Broken</option>
                        </select>
                      </div>                      
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-12 col-xs-12'>
                        <textarea id="editRemark" name='editRemark' class='fullwidth'></textarea>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row'>
                      <div class='col-md-12 col-xs-12 dark-div'>
                        Youtube Video ID
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-12 col-xs-12'>
                        <input type="text" class="form-control" id="editYoutube" >
                      </div>                      
                    </div>

                    <div class='row'>
                      <div class='col-md-12 col-xs-12'>
                        https://youtube.com/watch?v=
                      </div>
                    </div>

                  </div>

                </div>
                <div class="height5"></div>
                <div class='row'>
                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row'>
                      <div class='col-md-12 col-xs-12 dark-div'>
                        Accessories
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-12 col-xs-12 nopadding'>
                        <table width='100%' class='accessories' id='editAccessories'>
                          <tr>
                            <?php
                            $i = 0;
                            foreach ($db['caroption'] as $accessories_detail) {
                              if ($i == 5) {
                                echo '</tr><tr>';
                                $i = 0;
                              }
                              ?>
                              <td>{{$accessories_detail->description}}</td>
  <?php
  $i++;
}
?>
                          </tr>
                        </table>
                      </div>                      
                    </div>

                  </div>  

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row'>
                      <div class='col-md-12 col-xs-12 dark-div'>
                        Vendor Memo
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-12 col-xs-12'>
                        <textarea class='fullwidth' id="editComment" name='editComment'></textarea>
                      </div>                      
                    </div>


                  </div>  

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Top Reccomendation
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="recommendation" class="form-control dropdownSelect2" id="editRecommendation">
                          <option value="0">No</option>
                          <option value="1">Yes</option>          
                        </select>
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Best Deal
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="editBestDeal" class="form-control dropdownSelect2" id="editBestDeal">
                          <option value="0">No</option>
                          <option value="1">Yes</option>          
                        </select>
                      </div>
                    </div>
                    <div class='row row-border' style='display:none'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Best Seller
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="editBestSeller" class="form-control dropdownSelect2" id="editBestSeller" >
                          <option value="0">No</option>
                          <option value="1">Yes</option>          
                        </select>
                      </div>
                    </div>
                    <div class='row row-border' style='display:none'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Hot Car
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="status" class="form-control dropdownSelect2" id="editHotCar" style='display:none'>
                          <option value="0">No</option>
                          <option value="1">Yes</option>          
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Status
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="status" class="form-control" id="editStatus">
                          <option value="1" selected>Available</option>
                          <option value="4">Unavailable</option>
                          <option value="2">Sold</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Promotion
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="promotion" class="form-control" id="editPromotion">
                          <option value="0">No</option>
                          <option value="1">Yes</option>
                        </select>
                      </div>
                    </div>

                  </div>  

                </div>

              </div>

            </div>

          </form>

        
<div class='image-container'>          
  <div class="photo-grid-container" align="center" width="80%" style="margin-top:10px;"></div>
</div>
          <div class="row">
            <div class="col-md-12 col-xs-12" id='edit-upload-container'>
              <div class="jumbotron how-to-create" >
                <h3>Images <span id="photoCounter"></span></h3>
                <br />
                {!! Form::open(['class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}
                <div class="dz-clickable">
                  <div class="dz-message">
                    <h4 style="text-align: center;color:#428bca;">Drop images in this area  <span class="glyphicon glyphicon-hand-down"></span></h4>
                  </div>
                </div>
                <div class="dropzone-previews" id="dropzonePreview"></div>
                {!! Form::close() !!}
              </div>
              Maximum allowed size of image is 4MB

              <!--Dropzone Preview Template -->
              <div id="preview-template" style="display: none;">
                <div class="dz-preview dz-file-preview">
                  <div class="dz-image"><img data-dz-thumbnail=""></div>

                  <div class="dz-details">
                    <div class="dz-size"><span data-dz-size=""></span></div>
                    <div class="dz-filename"><span data-dz-name=""></span></div>
                  </div>
                  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                  <div class="dz-error-message"><span data-dz-errormessage=""></span></div>

                  <div class="dz-success-mark">
                    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                    Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch 
                    <title>Check</title>
                    <desc>Created with Sketch.</desc>
                    <defs></defs>
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                    <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                    </g>
                    </svg>
                  </div>

                  <div class="dz-error-mark">
                    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                    Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch 
                    <title>error</title>
                    <desc>Created with Sketch.</desc>
                    <defs></defs>
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                    <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
                    <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
                    </g>
                    </g>
                    </svg>
                  </div>

                </div>
              </div>
              End Dropzone Preview Template 
              {!! Form::hidden('csrf-token', csrf_token(), ['id' => 'csrf-token']) !!}
            </div>
          </div>
        </div>  
<!-- <div>
  <div class="form-group row">
    <label class="col-md-4 control-label text-md-right">
    </label>
    <div class="col-md-12">
      <div class="dz-clickable" id="my-dropzone">
        <div class="dz-message">
          <p class="mb-xs fw-normal dz-message"> Drop here the images that you want to upload. You'll be able to crop them.</p>
        </div>
      </div>
    </div>
  </div>
</div> -->

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button onclick="saveData()" type="button" class="btn btn-primary">Save</button>
        </div>

      </div>

    </div>
  </div>

  @stop
  @section('script')
  <script src="{{URL::to('/')}}/assets/js/datetimepicker/bootstrap-datetimepicker.js"></script>
  <script src="{{URL::to('/')}}/assets/js/datetimepicker/locales/bootstrap-datetimepicker.id.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="{{ URL::to('/') }}/assets/js/admin.js"></script>
  <script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
  <script src="{{ URL::to('/') }}/assets/js/dropzone.js" type="text/javascript"></script>
  <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>  
  <script src="{{ URL::to('/') }}/assets/js/jquery-sortable-photos.js" type="text/javascript"></script>
  <!-- <script src="{{ URL::to('/') }}/assets/js/jquery-cropper.js"></script> -->
  <script src="{{ URL::to('/') }}/assets/js/cropper.min.js"></script><!-- Cropper.js is required -->
  <script>
    var web_url = '{{$web_url}}';
    var photo_counter = 0;
    var fileList = new Array;
    var image_asset_url = "{{URL::to('/').'/uploads/car/'}}";
    var uploaded_picture = [];
    var model_id = '';
//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];
 
    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
     
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);
     
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
     
    $image($dst_img, $dst_dir, $quality);
 
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}
//usage example
resize_crop_image(100, 100, "test.jpg", "test.jpg");

$(document).ready(function() {
  // window.Cropper;
  Dropzone.autoDiscover = false;
  var c = 0;

  var cropped = false;
  var realDropzone = new Dropzone("#real-dropzone", {
          url: "{{route('upload-post')}}",
          uploadMultiple: false,
          maxFilesize: 4,
          previewsContainer: '#dropzonePreview',
          previewTemplate: document.querySelector('#preview-template').innerHTML,
          addRemoveLinks: true,
          dictRemoveFile: 'Remove',
          dictFileTooBig: 'Image is bigger than 4MB',
          // The setting up of the dropzone
          init:function() {
            // console.log(cropped);
              this.on('addedfile', function(file) {
                if(cropped==false){
                  this.removeFile(file);
                  cropper(file);
                } else {
                  cropped = false;
                  this.on("success", function(file, serverFileName) {
                    if(serverFileName!=null){
                      fileList[photo_counter] = {"serverFileName" : serverFileName.filename, "fileName" : file.name, "fileId" : photo_counter };
                      console.log(photo_counter);
                    }
                  });

                  this.on("removedfile", function(file) {
                    var rmvFile = "";
                    for (x in fileList){
                      if (fileList[x].fileName == file.name){
                        rmvFile = fileList[x].serverFileName;
                        delete fileList[x];
                      }
                    }
                    // console.log('rmvFile='+ rmvFile);
                    if(rmvFile!=''){
                      $.ajax({
                        type: 'POST',
                        url: 'upload/delete',
                        data: {id: rmvFile, _token: $('#csrf-token').val()},
                        dataType: 'html',
                        success: function(data){
                          var rep = JSON.parse(data);
                          console.log(rep);
                          if (rep.error == 0){
                            photo_counter--;
                            $("#photoCounter").text("(" + photo_counter + ")");
                          }
                        }
                      });
                    }
                  });
                }
              });

        },
        error: function(file, response) {
          if ($.type(response) === "string")
                  var message = response; //dropzone sends it's own error messages in string
          else
                  var message = response.message;
          file.previewElement.classList.add("dz-error");
          _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          node = _ref[_i];
          _results.push(node.textContent = message);
          }
          return _results;
        },
        success: function(file, done) {
          photo_counter++;
          console.log('success ='+photo_counter);
          $("#photoCounter").text("(" + photo_counter + ")");
          console.log(fileList);
        }
  });

  var dataURItoBlob = function (dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {type: 'image/jpeg'});
  };

        var cropper = function(file) {
        var fileName = file.name;
        console.log(fileName);
        var loadedFilePath = getSrcImageFromBlob(file);
        // @formatter:off
        var modalTemplate =
          '<div class="modal fade" tabindex="-2" role="dialog" id="cropModal">' +
          '<div class="modal-dialog" role="document">' +
          '<div class="modal-content">' +
          '<div class="modal-header">' +
          '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>' +
          '</div>' +
          '<div class="modal-body">' +
          '<div class="cropper-container" style="height:400px;">' +
          '<img id="img-' + ++c + '" src="' + loadedFilePath + '" data-vertical-flip="false" data-horizontal-flip="false" style="max-width: 100%;">' +
          '</div>' +
          '</div>' +
          '<div class="modal-footer">' +
          '<button type="button" class="btn btn-warning rotate-left"><span class="fa fa-undo"></span></button>' +
          '<button type="button" class="btn btn-warning rotate-right"><span class="fa fa-redo"></span></button>' +
          '<button type="button" class="btn btn-warning scale-x" data-value="-1"><span class="fa fa-arrow-circle-left"></span><span class="fa fa-arrow-circle-right"></span></button>' +
          '<button type="button" class="btn btn-warning scale-y" data-value="-1"><span class="fa fa-arrow-circle-down"></span><span class="fa fa-arrow-circle-up"></span></button>' +
          '<button type="button" class="btn btn-warning reset"><span class="fa fa-sync"></span></button>' +
          '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
          '<button type="button" class="btn btn-primary crop-upload">Crop & upload</button>' +
          '</div>' +
          '</div>' +
          '</div>' +
          '</div>';
        // @formatter:on

        var $cropperModal = $(modalTemplate);

        $cropperModal.modal('show').on("shown.bs.modal", function() {
          // var $image =document.getElementById('img-' + c);
          var cropper = new Cropper(document.getElementById('img-' + c), {
              aspectRatio: 1 / 1,
              cropBoxResizable: false,
              movable: true,
              rotatable: true,
              scalable: true,
              viewMode: 0,
              minContainerWidth: 250,
              maxContainerWidth: 250
              // initialAspectRatio: 500
            })
            var $this = $(this);
            $this
            .on('click', '.crop-upload', function () {
                // get cropped image data
                var blob = cropper.getCroppedCanvas({
                  fillColor: '#000',
                  width: 1024,
                  height: 1024,
                  minWidth: 0,
                  minHeight: 0,
                  maxWidth: 1024,
                  maxHeight: 1024,
                  imageSmoothingEnabled: false,
                  imageSmoothingQuality: 'high',}).toDataURL();
                // transform it to Blob object
                var croppedFile = dataURItoBlob(blob);
                croppedFile.name = fileName;

                var files = realDropzone.getAcceptedFiles();
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (file.name === fileName) {
                        realDropzone.removeFile(file);
                    }
                }
                cropped = true;
                // realDropzone.files.push(croppedFile);  
                // realDropzone.emit('addedfile', croppedFile);
                realDropzone.createThumbnail(croppedFile);
                realDropzone.addFile(croppedFile);
                $this.modal('hide');
            })
            .on('click', '.rotate-right', function() {
              cropper.rotate(90);
            })
            .on('click', '.rotate-left', function() {
              cropper.rotate(-90);
            })
            .on('click', '.reset', function() {
              cropper.reset();
            })
            .on('click', '.scale-x', function () {
                var $this = $(this);
                cropper.scaleX($this.data('value'));
                $this.data('value', -$this.data('value'));
            })
            .on('click', '.scale-y', function () {
                var $this = $(this);
                cropper.scaleY($this.data('value'));
                $this.data('value', -$this.data('value'));
            });
        });
    }
});
  function getSrcImageFromBlob(blob) {
    var urlCreator = window.URL || window.webkitURL;
    return urlCreator.createObjectURL(blob);
  }

  function blobToFile(theBlob, fileName) {
    theBlob.lastModifiedDate = new Date();
    theBlob.name = fileName;
    return theBlob;
  }

var accessories = [];
$(".accessories td").on('click', function () {
  var value = $(this).text();
  if ($.inArray(value, accessories) == -1) {
    accessories.push(value);
  } else {
    var index = accessories.indexOf(value);
    if (index !== -1) accessories.splice(index, 1);
  }
});
$(".accessories td").on('mouseover', function () {
  var value = $(this).text();
  if ($.inArray(value, accessories) == -1) {
    $(this).css('background-color', '#DDFFDD');
  }
});
$(".accessories td").on('mouseout', function () {
  var value = $(this).text();
  if ($.inArray(value, accessories) == -1) {
    $(this).css('background-color', '#FFF');
  }
});
$("#add-car").on('click', function () {
  openModal();
})

function openModal(id) {
  if (typeof id !== 'undefined') {
    //edit
    $('#editModal').modal('show');
  } else {
    //new
    clearModal();
    $('#editModal').modal('show');
  }
}

function clearModal() {
  $('#idEdit').val('');
  $("#editVin").val('');
  $("#editMake").val('');
  $("#editModel").val('');
  $("#editType").val('');
  $("#editRegistrationYear").val('');
  $("#editRegistrationMonth").val('');
  $("#editRegistrationDay").val('');
  $("#plate_number").val('');
  $("#motor_number").val('');
  $("#editManufactureYear").val('');
  $("#editManufactureMonth").val('');
  $("#editDistance").val('');
  $("#editPrice").val('');
  $("#editRemark").val('');
  $("#editYoutube").val('');
  $("#editEngine").val('');
  $("#editSerial").val('');
  $("#editFuel").val('');
  $("#editSteering").val(2);
  $("#editTransmission").val('');
  $("#editDrive").val('');
  $("#editColour").val('');
  $("#editInteriorColor").val('');
  $("#editExteriorColor").val('');
  $("#editDoor").val('');
  $("#editSeat").val('');
  $("#editWeight").val('');
  $("#editDescription").val('');
  $("#editKeyword").val('');
  $("#editAgent").val('');
  $("#editBuyer").val('');
  $("#editLength").val('');
  $("#editWidth").val('');
  $("#editHeight").val('');
  $("#editComment").val('');
  $("#editRecommendation").val(0);
  $("#editBestDeal").val(0);
  $("#editBestSeller").val(0);
  $("#editHotCar").val(0);
  $("#editStatus").val(1);
  $("#editPromotion").val(0);
  $("#editSellingPoint").val('');
  $(".image-container").html('');
  var default_seller = 'optimus auto trading';
  $("#editSeller option").filter(function () {
    return this.text == default_seller;
  }).attr('selected', true);
  uploaded_picture = [];
}

function saveData() {
  if ($('#idEdit').val() == '') {
    newData();
  } else {
    updateData();
  }
}

function newData() {
  if ($("#editRegistrationYear").val() !== '' && $("#editRegistrationMonth").val() !== '' && $("#editRegistrationDay").val() !== '') {
    var registration_date = $("#editRegistrationYear").val() + '-' + $("#editRegistrationMonth").val() + '-' + $("#editRegistrationDay").val();
  } else {
    var registration_date = '';
  }


  if ($('#editVin').val() === '') {
    alert('Chassis Number required');
    return false;
  } else if ($('#editMake').val() === '') {
    alert('Make required');
    return false;
  } else if ($('#editModel').val() === '') {
    alert('Model required');
    return false;
  } else if ($('#editType').val() === '') {
    alert('Product Type required');
    return false;
  } else if ($("#editState").val() == '1' && registration_date === '') {
    alert('Registration date required');
    return false;
  } else if ($('#editDistance').val() === '') {
    alert('Mileage required');
    return false;
  } else if ($('#editType').val() === '') {
    alert('Product Type required');
    return false;
  } else if ($('#editEngine').val() === '') {
    alert('Engine required');
    return false;
  } else if ($('#editPrice').val() === '') {
    alert('Price required');
    return false;
  } else if ($('#editSeller').val() === '') {
    alert('Seller required');
    return false;
  } 
  // else if ($('#editSerial').val() === '') {
  //   alert('Serial required');
  //   return false;
  // } 
  else if ($('#plate_number').val() === '') {
    alert('Plate number required');
    return false;
  } else if ($('#editFuel').val() === '') {
    alert('Fuel required');
    return false;
  } else if ($('#editTransmission').val() === '') {
    alert('Transmission required');
    return false;
  } else if ($('#editExteriorColor').val() === '') {
    alert('Exterior color required');
    return false;
  } else if ($('#editWeight').val() === '') {
    alert('Weight required');
    return false;
  } else if ($('#editSteering').val() === '') {
    alert('Steering required');
    return false;
  } else if ($('#editDoor').val() === '') {
    alert('Door required');
    return false;
  } else if ($('#editWidth').val() === '' && $('#editLength').val() === '' && $('#editHeight').val() === '') {
    alert('Size required');
    return false;
  } else if ($('#editManufactureYear').val() > registration_date) {
    alert("registration date can't before than manufacture date");
    return false;
  }


  var data = new FormData();
  data.append('newMake', $('#editMake').val());
  data.append('newModel', $('#editModel').val());
  data.append('newVin', $('#editVin').val());
  data.append('newSerial', $('#editSerial').val());
  data.append('registrationDate', registration_date);
  data.append('plate_number', $('#plate_number').val());
  data.append('motor_number', $('#motor_number').val());
  data.append('newDistance', $('#editDistance').val());
  data.append('newType', $('#editType').val());
  data.append('newColour', $('#editColour').val());
  data.append('newEngine', $('#editEngine').val());
  data.append('newPrice', $('#editPrice').val());
  data.append('newStatus', $('#editStatus').val());
  data.append('newPromotion', $('#editPromotion').val());
  data.append('newState', $('#editState').val());
  data.append('newFuel', $('#editFuel').val());
  data.append('newSteering', $('#editSteering').val());
  data.append('newManufactureYear', $('#editManufactureYear').val());
  data.append('newManufactureMonth', $('#editManufactureMonth').val());
  data.append('newCurrencySymbol', $('#editCurrencySymbol').val());
  data.append('newType2', $('#editType2').val());
  data.append('newExteriorColor', $('#editExteriorColor').val());
  data.append('newDrive', $('#editDrive').val());
  data.append('newTransmission', $('#editTransmission').val());
  data.append('newDoor', $('#editDoor').val());
  data.append('newSeat', $('#editSeat').val());
  data.append('newWeight', $('#editWeight').val());
  data.append('newOptions', $('#editOptions').val());
  data.append('newDescription', $('#editDescription').val());
  data.append('newRemark', $('#editRemark').val());
  data.append('newComment', $('#editComment').val());
  data.append('newKeyword', $('#editKeyword').val());
  data.append('newSeller', $('#editSeller').val());
  data.append('newAgent', $('#editAgent').val());
  data.append('newBuyer', $('#editBuyer').val());
  data.append('newRecommendation', $('#editRecommendation').val());
  data.append('newBestDeal', $('#editBestDeal').val());
  data.append('newBestSeller', $('#editBestSeller').val());
  data.append('newHotCar', $('#editHotCar').val());
  data.append('newYoutube', $('#editYoutube').val());
  data.append('newSellingPoint', $('#editSellingPoint').val());
  data.append('newInteriorColor', $('#editInteriorColor').val());
  data.append('newDimension', $('#editLength').val() + ',' + $('#editWidth').val() + ',' + $('#editHeight').val());
  data.append('newAccessories', JSON.stringify(accessories));
  var picture = [];

  if (fileList.length == 0) {
    alert("Please upload minimal 1 picture");
    return false;
  }

  y = 0;
  for (x in fileList) {
    if(fileList[x]['serverFileName']!=null){
      picture[y] = {
        'picture': fileList[x]['serverFileName']
      }
      y++;
    }
  }
  // console.log(picture);
  // console.log(JSON.stringify(picture));
  data.append('newPicture', JSON.stringify(picture));
  $.ajax({
    url: "{{ URL::to('/') }}/admin/car/save",
    type: "POST",
    data: data,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.error) {
        alert(response.error);
        return false;
      } else {
        location.reload();
      }
    },
    error: function (error) {
      console.log(error);
    }
  });
}

function updateData() {

  if ($("#editRegistrationYear").val() !== '' && $("#editRegistrationMonth").val() !== '' && $("#editRegistrationDay").val() !== '') {
    var registration_date = $("#editRegistrationYear").val() + '-' + $("#editRegistrationMonth").val() + '-' + $("#editRegistrationDay").val();
  } else {
    var registration_date = '';
  }

  var id = $('#idEdit').val();
  if ($('#editVin').val() === '') {
    alert('Chassis Number required');
    return false;
  } else if ($('#editMake').val() === '') {
    alert('Make required');
    return false;
  } else if ($('#editModel').val() === '') {
    alert('Model required');
    return false;
  } else if ($('#editType').val() === '') {
    alert('Product Type required');
    return false;
  } else if ($("#editState").val() == '1' && registration_date === '') {
    alert('Registration date required');
    return false;
  } else if ($('#editDistance').val() === '') {
    alert('Mileage required');
    return false;
  } else if ($('#editType').val() === '') {
    alert('Product Type required');
    return false;
  } else if ($('#editEngine').val() === '') {
    alert('Engine required');
    return false;
  } else if ($('#editPrice').val() === '') {
    alert('Price required');
    return false;
  } else if ($('#editSeller').val() === '') {
    alert('Seller required');
    return false;
  }
  //  else if ($('#editSerial').val() === '') {
  //   alert('Serial required');
  //   return false;
  // }
   else if ($('#plate_number').val() === '') {
    alert('Plate Number required');
    return false;
  } else if ($('#editFuel').val() === '') {
    alert('Fuel required');
    return false;
  } else if ($('#editTransmission').val() === '') {
    alert('Transmission required');
    return false;
  } else if ($('#editExteriorColor').val() === '') {
    alert('Exterior Color required');
    return false;
  } else if ($('#editWeight').val() === '') {
    alert('Weight required');
    return false;
  } else if ($('#editSteering').val() === '') {
    alert('Steering required');
    return false;
  } else if ($('#editDoor').val() === '') {
    alert('Door required');
    return false;
  } else if ($('#editWidth').val() === '' && $('#editLength').val() === '' && $('#editHeight').val() === '') {
    alert('Size required');
    return false;
  } else if ($('#editManufactureYear').val() > registration_date) {
    alert("registration date can't before than manufacture date");
    return false;
  }

  var data = new FormData();
  data.append('editMake', $('#editMake').val());
  data.append('editModel', $('#editModel').val());
  data.append('editVin', $('#editVin').val());
  data.append('editSerial', $('#editSerial').val());
  data.append('registrationDate', registration_date);
  data.append('plate_number', $('#plate_number').val());
  data.append('motor_number', $('#motor_number').val());
  data.append('editDistance', $('#editDistance').val());
  data.append('editType', $('#editType').val());
  data.append('editColour', $('#editColour').val());
  data.append('editEngine', $('#editEngine').val());
  data.append('editPrice', $('#editPrice').val());
  data.append('editStatus', $('#editStatus').val());
  data.append('editPromotion', $('#editPromotion').val());
  data.append('editState', $('#editState').val());
  data.append('editFuel', $('#editFuel').val());
  data.append('editSteering', $('#editSteering').val());
  data.append('editManufactureYear', $('#editManufactureYear').val());
  data.append('editManufactureMonth', $('#editManufactureMonth').val());
  data.append('editCurrencySymbol', $('#editCurrencySymbol').val());
  data.append('editType2', $('#editType2').val());
  data.append('editExteriorColor', $('#editExteriorColor').val());
  data.append('editDrive', $('#editDrive').val());
  data.append('editTransmission', $('#editTransmission').val());
  data.append('editDoor', $('#editDoor').val());
  data.append('editSeat', $('#editSeat').val());
  data.append('editOptions', $('#editOptions').val());
  data.append('editDescription', $('#editDescription').val());
  data.append('editRemark', $('#editRemark').val());
  data.append('editComment', $('#editComment').val());
  data.append('editKeyword', $('#editKeyword').val());
  data.append('editSeller', $('#editSeller').val());
  data.append('editAgent', $('#editAgent').val());
  data.append('editBuyer', $('#editBuyer').val());
  data.append('editRecommendation', $('#editRecommendation').val());
  data.append('editBestDeal', $('#editBestDeal').val());
  data.append('editBestSeller', $('#editBestSeller').val());
  data.append('editHotCar', $('#editHotCar').val());
  data.append('editInteriorColor', $('#editInteriorColor').val());
  data.append('editDimension', $('#editLength').val() + ',' + $('#editWidth').val() + ',' + $('#editHeight').val());
  data.append('editWeight', $('#editWeight').val());
  data.append('editYoutube', $('#editYoutube').val());
  data.append('editSellingPoint', $('#editSellingPoint').val());
  data.append('editAccessories', JSON.stringify(accessories));

  y = 0;
  var picture = [];
  for (x in fileList) {
    if(fileList[x]['serverFileName']!=null){
      picture[y] = {
        'picture': fileList[x]['serverFileName']
      }
      y++;
    }
  }

  uploaded_picture = [];
  $(".photo-grid-item").each(function (y) {
    uploaded_picture[y] = {
      'picture': $(this).data('img')
    };
  });

  var all_image = $.merge(picture, uploaded_picture)

  if (all_image.length == 0) {
    alert('please upload minimal 1 picture');
    return false;
  }
  data.append('editPicture', JSON.stringify(all_image));
  $.ajax({
    url: "{{ URL::to('/') }}/admin/car/update/" + id,
    type: "POST",
    data: data,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.error) {
        alert(response.error);
        return false;
      } else {
        location.reload();
      }
    },
    error: function (error) {
      console.log(error);
    }
  });
}

$('#editModal').on('show.bs.modal', function (event) {
  $("#editMake").select2();
  $("#editModel").select2();
  $("#editType").select2();

  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes

  var action = button.data('action') // Extract info from data-* attributes
  if (typeof ids !== 'undefined') {
    var modal = $(this);
    loadData($(this), ids, action);
  } else {
    clearModal();
  }
})

function calculateVolume(p, l, t) {
  if (p !== '' && l !== '' && t !== '') {
    var volume = p * l * t / 1000000;
    $('#volume').text('M3 ' + volume.toFixed(3));
  }
}

$("#editLength").on('change', function () {
  calculateVolume($("#editLength").val(), $("#editWidth").val(), $("#editHeight").val());
})

$("#editWidth").on('change', function () {
  calculateVolume($("#editLength").val(), $("#editWidth").val(), $("#editHeight").val())
})

$("#editHeight").on('change', function () {
  calculateVolume($("#editLength").val(), $("#editWidth").val(), $("#editHeight").val())
})

$.strPad = function (i, l, s) {
  var o = i.toString();
  if (!s) {
    s = '0';
  }
  while (o.length < l) {
    o = s + o;
  }
  return o;
};

$("#editVin").on('blur', function () {
  if ($('#idEdit').val() !== '' || $("#editVin").val() == '') {
    return false;
  }
  var data = {
    'newVin': $("#editVin").val(),
  };
  $.ajax({
    url: "{{ route('admin.chasisValidation') }}",
    type: "POST",
    data: data,
    success: function (response) {
      if (typeof response.error !== 'undefined' && response.error != '') {
        alert(response.error);
        $("#editVin").val('');
      }
    },
    error: function (error) {
      console.log(error);
    }
  });

})

$("#editRegistrationMonth").on('change', function () {
  createRegistrationDay($("#editRegistrationMonth").val());
})

function createRegistrationDay(month) {
  var total_day = ['', '31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31'];

  var html = '';
  var i = 1;
  for (i = 1; i <= total_day[parseInt(month)]; i++) {
    html += '<option value="' + $.strPad(i, 2) + '">' + i + '</option>';
  }

  $("#editRegistrationDay").html(html);
}

function loadData(obj, ids, action) {
  var modal = obj;
  $.get("{{ URL::to('/') }}/admin/car/view/" + ids, function (response) {
    accessories = [];
    if (response.accessories !== '') {
      accessories = JSON.parse(response.accessories);
    }

    $('#editAccessories td').each(function () {
      if ($.inArray($(this).html(), accessories) != -1) {
        $(this).css('background-color', '#DDFFDD');
      }
    });
    var registration_date_arr = [];
    if (response.registration_date !== '') {
      var registration_date_arr = response.registration_date.split('-');
    }

    model_id = response.model_id;
    modal.find('#editMake').val(response.make_id).change();
    modal.find('#editModel').val(response.model_id).change();
    modal.find('#editVin').val(response.vin);
    modal.find('#editSerial').val(response.serial);

    if (registration_date_arr.length > 0) {
      modal.find('#editRegistrationYear').val(registration_date_arr[0]);
      modal.find('#editRegistrationMonth').val(registration_date_arr[1]);

      createRegistrationDay($('#editRegistrationMonth').val());

      modal.find('#editRegistrationDay').val(registration_date_arr[2]);
    } else {
      modal.find('#editRegistrationYear').val('');
      modal.find('#editRegistrationMonth').val('');
      modal.find('#editRegistrationDay').val('');
    }

    if (action == 'edit') {
      modal.find('#editStatus').val(response.status == '' ? 1 : response.status).change();
    } else {
      modal.find('#editStatus').val(1).change();
    }
    modal.find('#plate_number').val(response.plate_number);
    modal.find('#motor_nunber').val(response.motor_number);
    modal.find('#editDistance').val(response.distance);
    modal.find('#editType').val(response.type);
    modal.find('#editType').val(response.type).trigger('change');
    modal.find('#editColour').val(response.colour);
    modal.find('#editEngine').val(response.engine);
    modal.find('#editPrice').val(response.price);
    modal.find('#editPromotion').val(response.promotion).change();
    modal.find('#editState').val(response.state).change();
    modal.find('#editFuel').val(response.fuel);
    modal.find('#editSteering').val(response.steering).change();
    modal.find('#editManufactureYear').val(response.manufacture_year);
    modal.find('#editManufactureMonth').val(response.manufacture_month);
    modal.find('#editCurrencySymbol').val(response.currency_symbol);
    modal.find('#editType2').val(response.type2);
    modal.find('#editExteriorColor').val(response.exterior_color);
    modal.find('#editDrive').val(response.drive).change();
    modal.find('#editTransmission').val(response.transmission).change();
    modal.find('#editDoor').val(response.door);
    modal.find('#editSeat').val(response.seat);
    modal.find('#editOptions').val(response.options);
    modal.find('#editDescription').val(response.description);
    modal.find('#editRemark').val(response.remark);
    modal.find('#editComment').val(response.comment);
    modal.find('#editKeyword').val(response.keyword);
    modal.find('#editSeller').val(response.seller).change();
    modal.find('#editAgent').val(response.agent);
    modal.find('#editBuyer').val(response.buyer);
    modal.find('#editWeight').val(response.weight);
    modal.find('#editRecommendation').val(response.recommendation).change();
    modal.find('#editBestDeal').val(response.best_deal).change();
    modal.find('#editBestSeller').val(response.best_seller).change();
    modal.find('#editHotCar').val(response.hot_car).change();
    modal.find('#editInteriorColor').val(response.interior_color);
    modal.find('#editYoutube').val(response.youtube);
    modal.find('#editSellingPoint').val(response.selling_point);

    if (response.dimension != null) {
      var dimensions = response.dimension.split(',');
      modal.find('#editLength').val(dimensions[0]);
      modal.find('#editWidth').val(dimensions[1]);
      modal.find('#editHeight').val(dimensions[2]);
      calculateVolume(dimensions[0], dimensions[1], dimensions[2]);

    }


    $("#editExteriorColor").select2();
    $("#editInteriorColor").select2();

    if (action == 'edit') {
      $('#idEdit').val(ids);
      uploaded_picture = [];
      var image_html = '<div class="photo-grid-container" align="center" width="80%" style="margin-top:10px;">';
      var image = response.picture;
      if (image !== '') {
        var image = JSON.parse(image);
        for (x in image) {
          if (image_asset_url + image[x]['picture'] !== '') {
            uploaded_picture[x] = {
              'picture': image[x]['picture']
            };

            image_html += '<div class="photo-grid-item" data-img = "' + image[x]['picture'] + '">' +
              '<img src="' + image_asset_url + image[x]['picture'] + '" width="120px" style="border:1px solid;border-radius:10px;"><br><a href="#" onclick="deleteImage(\'' + image[x]['picture'] + '\');">delete</a></div>';


          }
        }

        image_html += '</div>';

        $(".image-container").html(image_html);

        $('.photo-grid-container').sortablePhotos({
          selector: '> .photo-grid-item',
          padding: 20
        });
      }
    } else {
      /** copy similiar item */
      $(".image-container").html('');
      $('#idEdit').val('');
      $("#editDistance").val('');
      $("#editVin").val('');
      $("#plate_number").val('');
      $("#editSerial").val('');
      $("#editYoutube").val('');
      $("#editRecommendation").val(0);
      $("#editBestDeal").val(0);
      `
            $("#editBestSeller").val(0);
            $("#editHotCar").val(0);
            $("#editStatus").val(1);
            $("#editPromotion").val(0);`
    }

  });
}

function deleteImage(picture) {
  var confirmation = confirm("Are you sure want to delete?");
  if (confirmation) {
    var temp = [];
    var i = 0;

    uploaded_picture = [];
    $(".photo-grid-item").each(function (x) {
      uploaded_picture[x] = {
        'picture': $(this).data('img')
      };
    });

    for (x in uploaded_picture) {
      if (uploaded_picture[x]['picture'] !== picture) {
        temp[i] = {
          'picture': uploaded_picture[x]['picture']
        };
        i++;
      }
    }

    uploaded_picture = temp;
    var data = {
      'deleted_picture': picture,
      'uploaded_picture': uploaded_picture,
      'id': $('#idEdit').val(),
    }

    $.ajax({
      url: "{{ URL::to('/') }}/admin/car/delete-picture",
      type: "POST",
      data: data,
      success: function (response) {
        if (response.error == 0) {
          var image_html = '<div class="photo-grid-container" align="center" width="80%" style="margin-top:10px;">';
          var image = response.uploaded_picture;
          if (image !== '') {
            for (x in image) {
              uploaded_picture[x] = {
                'picture': image[x]['picture']
              };
              image_html += '<div class="photo-grid-item" data-img = "' + image[x]['picture'] + '">' +
                '<img src="' + image_asset_url + image[x]['picture'] + '" width="120px" style="border:1px solid;border-radius:10px;"><br><a href="#" onclick="deleteImage(\'' + image[x]['picture'] + '\');">delete</a></div>';

            }

            image_html += '</div>';

            $(".image-container").html(image_html);

            $('.photo-grid-container').sortablePhotos({
              selector: '> .photo-grid-item',
              padding: 20
            });

          }
        } else {
          alert('failed to delete, please try again letter');
        }
      },
      error: function (error) {
        console.log(error);
      }
    });
  }
  return false;
}

function deleteData(id) {
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
        $.get("{{ URL::to('/') }}/admin/car/delete/" + id, function (response) {
          location.reload();
        });
      } else {
        swal("Your file is safe!");
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
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}

function unavailableCar(id) {
  swal({
      title: "Are you sure?",
      // text: "Once unavailable, you will not be able to recover this file!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Poof! Your file has been unavailable!", {
          icon: "success",
        });
        $.get("{{ URL::to('/') }}/admin/car/unavailable/" + id, function (response) {
          location.reload();
        });
      } else {
        swal("Your file is safe!");
      }
    });
}

function availableCar(id) {
  swal({
      title: "Are you sure?",
      // text: "Once unavailable, you will not be able to recover this file!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Your file has been available!", {
          icon: "success",
        });
        $.get("{{ URL::to('/') }}/admin/car/available/" + id, function (response) {
          location.reload();
        });
      } else {
        swal("Your file is safe!");
      }
    });
}

$("#editMake").on('change', function () {
  getModel();
})

function getModel() {
  var requestData = {
    make_id: $("#editMake option:selected").attr('data-id'),
  };

  $.ajax({
    headers: {
      'X-CSRF-TOKEN': '{{csrf_token()}}',
    },
    url: "{{route('front.getCarModel')}}",
    data: requestData,
    type: 'post',
    dataType: 'json',
    beforeSend: function () {},
    success: function (result) {
      var select_template = '';
      for (x in result) {
        select_template += '<option value="' + result[x].id + '">' + result[x].model + '</option>';
      }
      $("#editModel").html(select_template);

      if (model_id !== '') {
        $('#editModel').val(model_id).change();
      }

      $("#editModel").prop('disabled', false);
    }
  });
}

$("#car_status").change(function () {
  var car_status = $('#car_status').val();
  window.location.href = car_status;
});

$("#check_all").on('change', function () {
  if ($('#check_all').is(":checked")) {
    $(".ids").prop('checked', true);
  } else {
    $(".ids").prop('checked', false);
  }
})

function unavailableAll() {
  var arr = [];
  $(".ids:checked").each(function (x) {
    arr.push($(this).data('id'));
  });

  swal({
      title: "Are you sure?",
      text: "Once unavailable, you will not be able to recover this file!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Poof! Your file has been unavailable!", {
          icon: "success",
        });
        $.get("{{ URL::to('/') }}/admin/car/unavailable/" + arr.join(','), function (response) {
          location.reload();
        });
      } else {
        swal("Your file is safe!");
      }
    });
}
  </script>
  @stop
