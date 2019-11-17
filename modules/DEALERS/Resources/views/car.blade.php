@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
{!! Html::style('/assets/css/select2.min.css') !!}
@stop
@section('content')
<div class="full-container">
  <div class="row">
    <div class="col-md-2 left-menu-div">

      @include('dealers::layouts.menu')
    </div>
<?php
  $thispage = isset($_GET['page'])?$_GET['page'] : 1;
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
  <h5>Car Model List</h5>
  <h6>{{ $total_item }} item found(shown: {{($i+1)}} - {{ $total_item }})</h6>

  <div class='row'>
    <div class='col-md-3'>
      <select id="maxpage" class='custom-select'>
  <?php
  $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
  $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
  ?>
  <option <?php if($getMax === "5") echo 'selected' ?> <?php $qs['max'] = 5; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">5</option>
  <option <?php if($getMax === "10") echo 'selected' ?> <?php $qs['max'] = 10; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">10</option>
  <option <?php if($getMax === "20") echo 'selected' ?> <?php $qs['max'] = 20; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">20</option>
  <option <?php if($getMax === "all") echo 'selected' ?> <?php $qs['max'] = 'all'; ?> value="{{ $web_url . '?' . http_build_query($qs) }}">All</option>
      </select>
    </div>
    
    
    
    <div class="col-md-5 col-xs-12 input-group" align='center'>
      <input type='text' name='filter' id='filter' class='form-control' value='<?php echo $qs['filter'] ?>' placeholder="filter">
      <div class="input-group-append">
        <button class='btn btn-danger' id='search-btn'><span class='glyphicon glyphicon-search'></span></button>
      </div>
    </div>

    <div class='col-md-4 col-xs-12' align='center'>
      <select id="descasc" class='custom-select'>
        <?php
        $qs['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
        ?>
        <option <?php if ($descasc === "desc") echo 'selected' ?> <?php $qs['descasc'] = 'desc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">New-old</option>
        <option <?php if ($descasc === "asc") echo 'selected' ?> <?php $qs['descasc'] = 'asc' ?> value="{{ $web_url . '?' . http_build_query($qs) }}">Old-new</option>
      </select>    
    </div>
  </div>

  <div class='row'>
    <div class='col-md-12 col-xs-12'>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Car</th>
            <th>VIN / Serial No</th>
            <th>Registration Year</th>
            <th>Model</th>
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
          ?>
          <tr>
            <td>{{ $i }}</td>
            <td>@if(isset($picture_arr[0]->picture))<img src='{{URL::to('/')}}/uploads/car/{{ $picture_arr[0]->picture }}' width='80px'>@endif</td>
            <td>{{ $d->vin }}</td>
            <td>{{ $d->serial .' / '. $d->registration_date }}</td>
            <td>{{ $d->model }}</td>
            <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">View Detail</button></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  
  <div class='row'>
    <div class='col-md-12' align="center">
      <caption>
        <?php if($thispage>1){
          $qs['page'] = $thispage-1;
          $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
          $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
          $current_url = Request::fullUrl();
          $explode = explode('?', $current_url);
          ?>
        <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="prev"><button class="btn btn-danger bottom10">Prev</button></a> 
      <?php }?>
  <?php if($thispage < $total_page){
        $qs['page'] = $thispage+1;
          $qs['max'] = isset($_GET['max']) ? $_GET['max'] : 5;
          $qs['descasc'] = isset($_GET['descasc']) ? $_GET['descasc'] : 'asc';
          $current_url = Request::fullUrl();
          $explode = explode('?', $current_url);
    ?>
        <a href="{{ $web_url . '?' . http_build_query($qs) }}" id="next"><button class="btn btn-danger bottom10">Next</button></a>
      </caption>
  <?php }?>     
    </div>
  </div>
  
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
                        Chassis No *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <input type="text" class="form-control" id="editVin" required readonly="true">
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Make *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control dropdownSelect2" id="editMake" required disabled="true">
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
                        <select name="editModel" class="form-control dropdownSelect2" id="editModel" required disabled="true">
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
                        <select name="editType" class="form-control dropdownSelect2" id="editType" required disabled="true">
                          <option value="">Select</option>
                          <option value="Bus">Bus</option>                        
                          <option value="Bus 20 Seats">Bus 20 Seats</option>                        
                          <option value="Convertible">Convertible</option>                        
                          <option value="Coupe">Coupe</option>                        
                          <option value="Hatchback">Hatchback</option>                        
                          <option value="Mini Bus">Mini Bus</option>                        
                          <option value="Mini Van">Mini Van</option>                        
                          <option value="Mini Vehicle">Mini Vehicle</option>                        
                          <option value="Pick Up">Pick Up</option>                        
                          <option value="Sedan">Sedan</option>                        
                          <option value="SUV">SUV</option>                        
                          <option value="Truck">Truck</option>                        
                          <option value="Unspecified">Unspecified</option>                        
                          <option value="Van">Van</option>                        
                          <option value="Wagon">Wagon</option>                        
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
                        Registration Year/Month *
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <input class='form-control' type='text' name='registrationDate' id="registrationDate" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" autocomplete="off">
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <select name="editRegistrationMonth" class="form-control dropdownSelect2" id="editRegistrationMonth" required disabled="true">
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
                        Manufacture Year/Month
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <select name="editManufactureYear" class="form-control dropdownSelect2" id="editManufactureYear" disabled="true">
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
                        <select name="editManufactureMonth" class="form-control dropdownSelect2" id="editManufactureMonth" disabled="true">
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
                        <input type="text" class="form-control" id="editDistance" required readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Engine Capacity (cc) *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editEngine" required disabled="true">
                          <option value="">Select</option>
                          @foreach($db['carengine'] as $c)
                          <option value="{{ $c->description }}">{{ $c->description }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Plate Number *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="plate_number" required>                        
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
                        Engine No *
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editSerial" required readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Fuel
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editFuel" disabled="true">
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
                        Steering
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editSteering" disabled="true">
                          <option value="1">Left</option>
                          <option value="2" selected>Right</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Transmission
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="editTransmission" disabled="true">
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
                        <select name="status" class="form-control" id="editDrive" disabled="true">
                          <option value="">Select</option>
                          <option value="2WD">2WD</option>
                          <option value="4WD">4WD</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Color
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editColour" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Interior Color
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editInteriorColor" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Exterior Color
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editExteriorColor" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Number of Doors
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editDoor" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Total Seats
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editSeat" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Total Weight (Kg)
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editWeight" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Description
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <textarea class="form-control" id="editDescription" readonly="true"></textarea>
                      </div>
                    </div>
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Selling Point
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <textarea class="form-control" id="editSellingPoint" readonly="true"></textarea>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Keyword
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="editKeyword" readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Agent
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control dropdownSelect2" id="editAgent" disabled="true">
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
                        <input type="text" class="form-control" id="editBuyer" readonly="true">
                      </div>
                    </div>

                  </div>

                </div>
                <div class="height5"></div>
                <div class='row'>

                  <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Size
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <div class='row'>
                          <div class='col-md-3 col-xs-3'>Length</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editLength" readonly="true"></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>
                        <div class='row'>
                          <div class='col-md-3 col-xs-3'>Width</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editWidth" readonly="true"></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>
                        <div class='row'>  
                          <div class='col-md-3 col-xs-3'>Height</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editHeight" readonly="true"></div>
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
                        <input type="text" class="form-control" id="editPrice" required readonly="true">
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Seller *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="status" class="form-control" id="editSeller" required disabled="true">
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
                        <select name="status" class="form-control" id="editState" disabled="true">
                          <option value="1">New</option>
                          <option value="2">Used</option>
                          <option value="3">Broken</option>
                        </select>
                      </div>                      
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-12 col-xs-12'>
                        <textarea id="editRemark" name='editRemark' class='form-control' readonly="true"></textarea>
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
                        <input type="text" class="form-control" id="editYoutube" readonly="true">
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
                        <textarea class='form-control' id="editComment" name='editComment' readonly="true"></textarea>
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
                        <select name="recommendation" class="form-control dropdownSelect2" id="editRecommendation" disabled="true">
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
                        <select name="editBestDeal" class="form-control dropdownSelect2" id="editBestDeal" disabled="true">
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
                        <select name="editBestSeller" class="form-control dropdownSelect2" id="editBestSeller" disabled="true">
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
                        <select name="status" class="form-control" id="editStatus" disabled="true">
                          <option value="1" selected>Active</option>
                          <option value="2">Deal</option>
                          <option value="3">Sold</option>
                          <option value="4">Inactive</option>
                        </select>
                      </div>
                    </div>

                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Promotion
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="status" class="form-control" id="editPromotion" disabled="true">
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


          <div id='picture-uploaded' align='center' width='80%'>

          </div>
        </div>  



        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>          
        </div>

      </div>

    </div>
  </div>
  
@stop
@section('script')
<script src="{{URL::to('/')}}/assets/js/admin.js"></script>
  <script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
  <script src="{{ URL::to('/') }}/assets/js/dropzone.js" type="text/javascript"></script>

  <script>
    var web_url = '{{$web_url}}';
    var photo_counter = 0;
    var fileList = new Array;
    var image_asset_url = "{{URL::to('/').'/uploads/car/'}}";
    var uploaded_picture = [];
    var model_id = '';
   
  var accessories = [];
  $(".accessories td").on('click', function(){
  var value = $(this).text();
  if ($.inArray(value, accessories) == - 1){
  accessories.push(value);
  } else{
  var index = accessories.indexOf(value);
  if (index !== - 1) accessories.splice(index, 1);
  }
  });
  $(".accessories td").on('mouseover', function(){
  var value = $(this).text();
  if ($.inArray(value, accessories) == - 1){
  $(this).css('background-color', '#DDFFDD');
  }
  });
  $(".accessories td").on('mouseout', function(){
    var value = $(this).text();
    if ($.inArray(value, accessories) == - 1){
      $(this).css('background-color', '#FFF');
    }
  });
  $("#add-car").on('click', function(){
    openModal();
  })

  function openModal(id){
    if (typeof id !== 'undefined'){
      //edit
      $('#editModal').modal('show');
    } else{
      //new
      clearModal();
      $('#editModal').modal('show');
    }
  }

  function clearModal(){
    $("#editVin").val('');
    $("#editMake").val('');
    $("#editModel").val('');
    $("#editType").val('');
    $("#registrationDate").val('');
    $("#editManufactureYear").val('');
    $("#editManufactureMonth").val('');
    $("#editDistance").val('');
    $("#editPrice").val('');
    $("#editSeller").val('');
    $("#editRemark").val('');
    $("#editYoutube").val('');
    $("#editEngine").val('');
    $("#editSerial").val('');
    $("#editFuel").val('');
    $("#editSteering").val('');
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
    $("#editRecommendation").val('0');
    $("#editBestDeal").val('0');
    $("#editBestSeller").val('0');
    $("#editHotCar").val('0');
    $("#editStatus").val('1');
    $("#editPromotion").val('0');
    $("#editSellingPoint").val('');
    $("#plate_number").val('');
    $("#motor_number").val('');
  }

  function saveData(){
    if ($('#idEdit').val() == ''){
      newData();
    } else{
      updateData();
    }
  }

  function newData(){

  if ($('#editVin').val() === ''){
    alert('Chassis Number required');
    return false;
  } else if ($('#editMake').val() === ''){
    alert('Make required');
    return false;
  } else if ($('#editModel').val() === ''){
    alert('Model required');
    return false;
  } else if ($('#editType').val() === ''){
    alert('Product Type required');
    return false;
  } else if ($("#editState").val() == '1' && $('#registrationDate').val() === ''){
    alert('Registration date required');
    return false;
  } else if ($('#editDistance').val() === ''){
    alert('Mileage required');
    return false;
  } else if ($('#editType').val() === ''){
    alert('Product Type required');
    return false;
  } else if ($('#editEngine').val() === ''){
    alert('Engine required');
    return false;
  } else if ($('#editPrice').val() === ''){
    alert('Price required');
    return false;
  } else if ($('#editSeller').val() === ''){
    alert('Seller required');
    return false;
  } else if ($('#editSerial').val() === ''){
    alert('Serial required');
    return false;
  }


  var data = new FormData();
  data.append('newMake', $('#editMake').val());
  data.append('newModel', $('#editModel').val());
  data.append('newVin', $('#editVin').val());
  data.append('newSerial', $('#editSerial').val());
  data.append('registrationDate', $('#registrationDate').val());
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
  for (x in fileList){
    picture[x] = {'picture':fileList[x]['serverFileName']}
  }
  data.append('newPicture', JSON.stringify(picture));
  $.ajax({
  url: "{{ URL::to('/') }}/admin/car/save",
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

  function updateData(){
    var id = $('#idEdit').val();
    if ($('#editVin').val() === ''){
    alert('Chassis Number required');
    return false;
    } else if ($('#editMake').val() === ''){
    alert('Make required');
    return false;
    } else if ($('#editModel').val() === ''){
    alert('Model required');
    return false;
    } else if ($('#editType').val() === ''){
    alert('Product Type required');
    return false;
    } else if ($("#editState").val() == '1' && $('#registrationDate').val() === ''){
    alert('Registration date required');
    return false;
    } else if ($('#editDistance').val() === ''){
    alert('Mileage required');
    return false;
    } else if ($('#editType').val() === ''){
    alert('Product Type required');
    return false;
    } else if ($('#editEngine').val() === ''){
      alert('Engine required');
      return false;
    } else if ($('#editPrice').val() === ''){
      alert('Price required');
      return false;
    } else if ($('#editSeller').val() === ''){
      alert('Seller required');
      return false;
    } else if ($('#editSerial').val() === ''){
      alert('Serial required');
      return false;
    }

  var data = new FormData();
  data.append('editMake', $('#editMake').val());
  data.append('editModel', $('#editModel').val());
  data.append('editVin', $('#editVin').val());
  data.append('editSerial', $('#editSerial').val());
  data.append('registrationDate', $('#registrationDate').val());
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
  var picture = [];
  for (x in fileList){
    picture[x] = {'picture':fileList[x]['serverFileName']}
  }
  var all_image = $.merge(picture, uploaded_picture)

          data.append('editPicture', JSON.stringify(all_image));
  $.ajax({
  url: "{{ URL::to('/') }}/admin/car/update/" + id,
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
    var action = button.data('action') // Extract info from data-* attributes
    if (typeof ids !== 'undefined'){
      var modal = $(this);
      loadData($(this), ids, action);
    }
  })

  function loadData(obj, ids, action){
          if(action == 'edit'){
            $('#idEdit').val(ids);
          }else{
            $('#idEdit').val('');
          }  
          
          var modal = obj;
          $.get("{{ URL::to('/') }}/admin/car/view/" + ids, function(response) {
          accessories = [];
          if (response.accessories !== ''){
            accessories = JSON.parse(response.accessories);
          }

          $('#editAccessories td').each(function() {
            if ($.inArray($(this).html(), accessories) != - 1){
              $(this).css('background-color', '#DDFFDD');
            }
          });
          model_id = response.model_id;
          modal.find('#editMake').val(response.make_id).change();
          modal.find('#editModel').val(response.model_id).change();
          modal.find('#editVin').val(response.vin);
          modal.find('#editSerial').val(response.serial);
          modal.find('#registrationDate').val(response.registration_date);
          modal.find('#plate_number').val(response.plate_number);
          modal.find('#motor_number').val(response.motor_number);
          modal.find('#editDistance').val(response.distance);
          modal.find('#editType').val(response.type);
          modal.find('#editColour').val(response.colour);
          modal.find('#editEngine').val(response.engine);
          modal.find('#editPrice').val(response.price);
          modal.find('#editStatus').val(response.status == ''? 1 : response.status).change();
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
          modal.find('#editRecommendation').val(response.recommendation).change();
          modal.find('#editBestDeal').val(response.best_deal).change();
          modal.find('#editBestSeller').val(response.best_seller).change();
          modal.find('#editHotCar').val(response.hot_car).change();
          modal.find('#editInteriorColor').val(response.interior_color);
          modal.find('#editYoutube').val(response.youtube);
          modal.find('#editSellingPoint').val(response.selling_point);

          if(response.dimension != '' && response.dimension != null){
            var dimensions = response.dimension.split(',');
            modal.find('#editLength').val(dimensions[0]);
            modal.find('#editWidth').val(dimensions[1]);
            modal.find('#editHeight').val(dimensions[2]);
          }
          
          if(action == 'edit'){
            uploaded_picture = [];
            var image_html = '';
            var image = response.picture;
            if (image !== ''){
              image_html += '<ul>';
              var image = JSON.parse(image);
              for (x in image){
                if (image_asset_url + image[x]['picture'] !== ''){
                  image_html += '<li style="display:inline-block;text-align:center;padding:10px;">'
                    + '<img src="' + image_asset_url + image[x]['picture'] + '" width="120px" style="border:1px solid;"><br><a href="#" onclick="deleteImage(\'' + image[x]['picture'] + '\');">delete</a></li>';
                  uploaded_picture[x] = {'picture':image[x]['picture']};
                }
              }

              image_html += '</ul>';
              $("#picture-uploaded").html(image_html);
            }
          }else{
            $("#picture-uploaded").html('');
          }

        });
      }

  function deleteImage(picture){
    var confirmation = confirm("Are you sure want to delete?");
    if (confirmation) {
    var temp = [];
    var i = 0;
    for (x in uploaded_picture){
    if (uploaded_picture[x]['picture'] !== picture){
    temp[i] = {'picture':uploaded_picture[x]['picture']};
    i++;
    }
    }

    uploaded_picture = temp;
    var data = {
    'deleted_picture' : picture,
            'uploaded_picture' : uploaded_picture,
            'id' : $('#idEdit').val(),
    }
  
  $.ajax({
  url: "{{ URL::to('/') }}/admin/car/delete-picture",
          type: "POST",
          data:  data,
          success: function (response) {
          if (response.error == 0){
          var image_html = '';
          var image = response.uploaded_picture;
          if (image !== ''){
          image_html += '<ul>';
          for (x in image){
          image_html += '<li style="display:inline-block;text-align:center"><img src="' + image_asset_url + image[x]['picture'] + '" width="80px"><br><a href="#" onclick="deleteImage(\'' + image[x]['picture'] + '\');">delete</a></li>';
          uploaded_picture[x] = {'picture':image[x]['picture']};
          }

          image_html += '</ul>';
          $("#picture-uploaded").html(image_html);
          }
          } else{
          alert('failed to delete, please try again letter');
          }
          },
          error: function(error) {
          console.log(error);
          }
  });
  }
  return false;
  }

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
        $.get("{{ URL::to('/') }}/admin/car/delete/" + id, function(response) {
          location.reload();
        });
      } else {
        swal("Your file is safe!");
      }
    });
  }
    
  $("#editMake").on('change',function(){    
     getModel();
  })
  
  function getModel(){
    var requestData = {
      make_id : $("#editMake option:selected").attr('data-id'),      
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
          var select_template = '';
          for (x in result){
            select_template += '<option value="'+ result[x].id +'">'+ result[x].model +'</option>';
          }
          $("#editModel").html(select_template);
          
          if(model_id !== ''){
            $('#editModel').val(model_id).change();
          }
        }
    });
  }
  </script>
  @stop
