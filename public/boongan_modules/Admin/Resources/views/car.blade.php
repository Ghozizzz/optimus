@extends('layouts.master')
@section('css_additional')
{!! Html::style('/assets/css/table_css.css') !!}
{!! Html::style('/assets/css/admin.css') !!}
{!! Html::style('/assets/css/select2.min.css') !!}
{!! Html::style('/assets/css/dropzone.css') !!}
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
<h5>Car List</h5>
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
              <th>Seller</th>
              <th>Agent</th>
              <th>Registration Year</th>
              <th>Model ID</th>
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($db['data'] as $d)
            <tr>
              <td>{{ $i }}</td>
              <td>{{ $d->pic_name }}</td>
              <td>{{ $d->name }}</td>
              <td>{{ $d->serial .' / '. $d->registration_year }}</td>
              <td>{{ $d->model }}</td>
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
          <h5 class="modal-title" id="exampleModalLabel">Add New Products</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <input type="hidden" class="form-control" id="newType2">
            <input type="hidden" class="form-control" id="editCurrencySymbol" value='USD'>
            <div class='row'>
              
              <div class='col-md-6 col-xs-12'>
                <div class='row'>
                  
                <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Chassis No *
                    </div>
                    <div class='col-md-6 col-xs-6'>
                      <input type="text" class="form-control" id="newVin" required>
                    </div>
                  </div>
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Make *
                    </div>
                    <div class='col-md-6 col-xs-6 '>
                      <select name="status" class="form-control dropdownSelect2" id="newMake" required>
                        <option value="">Select</option>
                        @foreach($db['carmake'] as $c)
                        <option value="{{ $c->id }}">{{ $c->make }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Model *
                    </div>
                    <div class='col-md-6 col-xs-6 '>
                      <select name="status" class="form-control dropdownSelect2" id="newModel" required>
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
                      <select name="newType" class="form-control dropdownSelect2" id="newType" required>
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
                        <select name="newRegistrationYear" class="form-control dropdownSelect2" id="newRegistrationYear" required>
                        <option value="">Select</option>
                        <?php
                        $now_year = date('Y');
                        for($i=$now_year;$i>($now_year-20);$i--){
                          echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                        ?>
                        </select>
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <select name="newRegistrationMonth" class="form-control dropdownSelect2" id="newRegistrationMonth" required>
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
                        <select name="newManufactureYear" class="form-control dropdownSelect2" id="newManufactureYear">
                        <option value="">Select</option>
                        <?php
                        $now_year = date('Y');
                        for($i=$now_year;$i>($now_year-20);$i--){
                          echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                        ?>
                        </select>
                      </div>
                      <div class='col-md-3 col-xs-3'>
                        <select name="newManufactureMonth" class="form-control dropdownSelect2" id="newManufactureMonth">
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
                        <input type="text" class="form-control" id="newDistance" required>
                      </div>
                    </div>

                    <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Engine Capacity (cc) *
                    </div>
                    <div class='col-md-6 col-xs-6 '>
                      <select name="status" class="form-control" id="newEngine" required>
                        <option value="">Select</option>
                        @foreach($db['carengine'] as $c)
                        <option value="{{ $c->description }}">{{ $c->description }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Engine No
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newSerial" required>
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Fuel
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="newFuel">
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
                        <select name="status" class="form-control" id="newSteering">
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
                        <select name="status" class="form-control" id="newTransmission">
                          <option value="">Select</option>
                          <option value="Automatic">Automatic</option>
                          <option value="CVT">CVT</option>
                          <option value="DCT">DCT</option>
                          <option value="Manual">Manual</option>
                          <option value="Semi Automatic">Semi Automatic</option>
                          <option value="Sport AT">Sport AT</option>
                          <option value="Unspecified">Unspecified</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Drive Type
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control" id="newDrive">
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
                        <input type="text" class="form-control" id="newColour">
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Interior Color
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newInteriorColor">
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Exterior Color
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newExteriorColor">
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Number of Doors
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newDoor">
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Total Seats
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newSeat">
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Total Weight (Kg)
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newWeight">
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Description
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <textarea class="form-control" id="newDescription"></textarea>
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Keyword
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <input type="text" class="form-control" id="newKeyword">
                      </div>
                    </div>
                  
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Agent
                      </div>
                      <div class='col-md-6 col-xs-6 '>
                        <select name="status" class="form-control dropdownSelect2" id="newAgent">
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
                        <input type="text" class="form-control" id="newBuyer">
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
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="newLength" ></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>
                        <div class='row'>
                          <div class='col-md-3 col-xs-3'>Width</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="newWidth" ></div>
                          <div class='col-md-1 col-xs-1'>cm</div>
                        </div>
                        <div class='row'>  
                          <div class='col-md-3 col-xs-3'>Height</div>
                          <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="newHeight" ></div>
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
                        <input type="text" class="form-control" id="newPrice" required>
                      </div>
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-6 col-xs-6 dark-div'>
                        Seller *
                      </div>
                      <div class='col-md-6 col-xs-6'>
                        <select name="newSeller" class="form-control dropdownSelect2" id="newSeller" required>
                          <option value="">-- select seller --</option>
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
                        <select name="status" class="form-control" id="newState">
                          <option value="1">New</option>
                          <option value="2">Used</option>
                          <option value="3">Broken</option>
                        </select>
                      </div>                      
                    </div>
                    
                    <div class='row row-border'>
                      <div class='col-md-12 col-xs-12'>
                        <textarea id="newRemark" name='newRemark' class='fullwidth'></textarea>
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
                        <input type="text" class="form-control" id="newYoutube" >
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
                        <table width='100%' class='accessories' id='newAccessories'>
                          <tr>
                          <?php 
                          $i = 0;
                          foreach($db['caroption'] as $accessories_detail){ 
                            if($i == 5){
                              echo '</tr><tr>';
                              $i = 0;
                            }
                            ?>
                            <td>{{$accessories_detail->description}}</td>
                          <?php 
                          $i++;
                          }?>
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
                        <textarea class='fullwidth' id="newComment" name='newComment'></textarea>
                      </div>                      
                    </div>
                    
                    
                  </div>  
                  
                </div>
                <div class="height5"></div>
              <div class='row'>

                <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Reccomendation
                    </div>
                    <div class='col-md-6 col-xs-6'>
                      <select name="recommendation" class="form-control dropdownSelect2" id="newRecommendation">
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
                      <select name="status" class="form-control dropdownSelect2" id="newBestDeal">
                        <option value="0">No</option>
                        <option value="1">Yes</option>          
                      </select>
                    </div>
                  </div>
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Best Seller
                    </div>
                    <div class='col-md-6 col-xs-6'>
                      <select name="status" class="form-control dropdownSelect2" id="newBestSeller">
                        <option value="0">No</option>
                        <option value="1">Yes</option>          
                      </select>
                    </div>
                  </div>
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Hot Car
                    </div>
                    <div class='col-md-6 col-xs-6'>
                      <select name="status" class="form-control dropdownSelect2" id="newHotCar">
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
                      <select name="status" class="form-control" id="newStatus">
                        <option value="0">Inactive</option>
                        <option value="1" checked>Active</option>
                        <option value="2">Deal</option>
                        <option value="3">Sold</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class='row row-border'>
                    <div class='col-md-6 col-xs-6 dark-div'>
                      Promotion
                    </div>
                    <div class='col-md-6 col-xs-6'>
                      <select name="status" class="form-control" id="newPromotion">
                        <option value="1">Normal</option>
                        <option value="2">Promo</option>
                      </select>
                    </div>
                  </div>
                  
                </div>  
    
              </div>
                
              </div>
              
            </div>
          </form>
          
          
          <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="jumbotron how-to-create" >

                    <h3>Images <span id="photoCounter"></span></h3>
                    <br />

                    {!! Form::open(['url' => route('upload-post'), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}

                    <div class="dz-message">
                    </div>

                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>

                    <div class="dropzone-previews" id="dropzonePreview"></div>
                    <h4 style="text-align: center;color:#428bca;">Drop images in this area  <span class="glyphicon glyphicon-hand-down"></span></h4>

                    {!! Form::close() !!}

              </div>
              Maximum allowed size of image is 4MB
            </div>
          </div>
          <!-- Dropzone Preview Template -->
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
                    <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
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
                      <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
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
          <!-- End Dropzone Preview Template -->
          {!! Form::hidden('csrf-token', csrf_token(), ['id' => 'csrf-token']) !!}
      
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Car</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          
        <div class='row'>
              
          <div class='col-md-6 col-xs-12'>
            <div class='row'>

            <div class='col-md-12 col-xs-12 nopadding group-div' width='100%'>  
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
                    @foreach($db['carmake'] as $c)
                    <option value="{{ $c->id }}">{{ $c->make }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class='row row-border'>
                <div class='col-md-6 col-xs-6 dark-div'>
                  Model *
                </div>
                <div class='col-md-6 col-xs-6 '>
                  <select name="status" class="form-control dropdownSelect2" id="editModel" required>
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
                    <select name="editRegistrationYear" class="form-control dropdownSelect2" id="editRegistrationYear" required>
                    <option value="">Select</option>
                    <?php
                    $now_year = date('Y');
                    for($i=$now_year;$i>($now_year-20);$i--){
                      echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                    ?>
                    </select>
                  </div>
                  <div class='col-md-3 col-xs-3'>
                    <select name="editRegistrationMonth" class="form-control dropdownSelect2" id="editRegistrationMonth" required>
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
                    <select name="editManufactureYear" class="form-control dropdownSelect2" id="editManufactureYear">
                    <option value="">Select</option>
                    <?php
                    $now_year = date('Y');
                    for($i=$now_year;$i>($now_year-20);$i--){
                      echo '<option value="'.$i.'">'.$i.'</option>';
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
                    <input type="text" class="form-control" id="editDistance" required>
                  </div>
                </div>

                <div class='row row-border'>
                <div class='col-md-6 col-xs-6 dark-div'>
                  Engine Capacity (cc) *
                </div>
                <div class='col-md-6 col-xs-6 '>
                  <select name="status" class="form-control" id="editEngine" required>
                    <option value="">Select</option>
                    @foreach($db['carengine'] as $c)
                    <option value="{{ $c->description }}">{{ $c->description }}</option>
                    @endforeach
                  </select>
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
                    Fuel
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <select name="status" class="form-control" id="editFuel">
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
                    <select name="status" class="form-control" id="editSteering">
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
                    <select name="status" class="form-control" id="editTransmission">
                      <option value="">Select</option>
                      <option value="Automatic">Automatic</option>
                      <option value="CVT">CVT</option>
                      <option value="DCT">DCT</option>
                      <option value="Manual">Manual</option>
                      <option value="Semi Automatic">Semi Automatic</option>
                      <option value="Sport AT">Sport AT</option>
                      <option value="Unspecified">Unspecified</option>
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
                    Color
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <input type="text" class="form-control" id="editColour">
                  </div>
                </div>

                <div class='row row-border'>
                  <div class='col-md-6 col-xs-6 dark-div'>
                    Interior Color
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <input type="text" class="form-control" id="editInteriorColor">
                  </div>
                </div>

                <div class='row row-border'>
                  <div class='col-md-6 col-xs-6 dark-div'>
                    Exterior Color
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <input type="text" class="form-control" id="editExteriorColor">
                  </div>
                </div>

                <div class='row row-border'>
                  <div class='col-md-6 col-xs-6 dark-div'>
                    Number of Doors
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <input type="text" class="form-control" id="editDoor">
                  </div>
                </div>

                <div class='row row-border'>
                  <div class='col-md-6 col-xs-6 dark-div'>
                    Total Seats
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <input type="text" class="form-control" id="editSeat">
                  </div>
                </div>

                <div class='row row-border'>
                  <div class='col-md-6 col-xs-6 dark-div'>
                    Total Weight (Kg)
                  </div>
                  <div class='col-md-6 col-xs-6 '>
                    <input type="text" class="form-control" id="editWeight">
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
                    Size
                  </div>
                  <div class='col-md-6 col-xs-6'>
                    <div class='row'>
                      <div class='col-md-3 col-xs-3'>Length</div>
                      <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editLength" ></div>
                      <div class='col-md-1 col-xs-1'>cm</div>
                    </div>
                    <div class='row'>
                      <div class='col-md-3 col-xs-3'>Width</div>
                      <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editWidth" ></div>
                      <div class='col-md-1 col-xs-1'>cm</div>
                    </div>
                    <div class='row'>  
                      <div class='col-md-3 col-xs-3'>Height</div>
                      <div class='col-md-8 col-xs-8'><input type="text" class="form-control" id="editHeight" ></div>
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
                    <input type="text" class="form-control" id="editPrice" required>
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
                      <option value="1">New</option>
                      <option value="2">Used</option>
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
                      foreach($db['caroption'] as $accessories_detail){ 
                        if($i == 5){
                          echo '</tr><tr>';
                          $i = 0;
                        }
                        ?>
                        <td>{{$accessories_detail->description}}</td>
                      <?php 
                      $i++;
                      }?>
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
                  Reccomendation
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
                  <select name="status" class="form-control dropdownSelect2" id="editBestDeal">
                    <option value="0">No</option>
                    <option value="1">Yes</option>          
                  </select>
                </div>
              </div>
              <div class='row row-border'>
                <div class='col-md-6 col-xs-6 dark-div'>
                  Best Seller
                </div>
                <div class='col-md-6 col-xs-6'>
                  <select name="status" class="form-control dropdownSelect2" id="editBestSeller">
                    <option value="0">No</option>
                    <option value="1">Yes</option>          
                  </select>
                </div>
              </div>
              <div class='row row-border'>
                <div class='col-md-6 col-xs-6 dark-div'>
                  Hot Car
                </div>
                <div class='col-md-6 col-xs-6'>
                  <select name="status" class="form-control dropdownSelect2" id="editHotCar">
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
                    <option value="0">Inactive</option>
                    <option value="1" checked>Active</option>
                    <option value="2">Deal</option>
                    <option value="3">Sold</option>
                  </select>
                </div>
              </div>

              <div class='row row-border'>
                <div class='col-md-6 col-xs-6 dark-div'>
                  Promotion
                </div>
                <div class='col-md-6 col-xs-6'>
                  <select name="status" class="form-control" id="editPromotion">
                    <option value="1">Normal</option>
                    <option value="2">Promo</option>
                  </select>
                </div>
              </div>

            </div>  

          </div>

          </div>

        </div>
        
        </form>
        
        

      <div class="row">
  <div class="col-md-12 col-xs-12">
      <div class="jumbotron how-to-create" >

          <h3>Images <span id="photoCounter"></span></h3>
          <br />

          {!! Form::open(['url' => route('upload-post'), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}

          <div class="dz-message">

          </div>

          <div class="fallback">
              <input name="file" type="file" multiple />
          </div>

          <div class="dropzone-previews" id="dropzonePreview"></div>

          <h4 style="text-align: center;color:#428bca;">Drop images in this area  <span class="glyphicon glyphicon-hand-down"></span></h4>

          {!! Form::close() !!}

    </div>
    Maximum allowed size of image is 4MB
  </div>
</div>
      <!-- Dropzone Preview Template -->
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
                    <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
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
                    <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
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
      <!-- End Dropzone Preview Template -->
      {!! Form::hidden('csrf-token', csrf_token(), ['id' => 'csrf-token']) !!}


        
      </div>  
        

    
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button onclick="updateData()" type="button" class="btn btn-primary">Save</button>
      </div>

    </div>
    
  </div>
</div>

@stop
@section('script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
<script src="{{ URL::to('/') }}/assets/js/dropzone.js" type="text/javascript"></script>

<script>
 
var photo_counter = 0;
var fileList = new Array;
Dropzone.options.realDropzone = {

    uploadMultiple: false,
    parallelUploads: 100,
    maxFilesize: 4,
    previewsContainer: '#dropzonePreview',
    previewTemplate: document.querySelector('#preview-template').innerHTML,
    addRemoveLinks: true,
    dictRemoveFile: 'Remove',
    dictFileTooBig: 'Image is bigger than 4MB',

    // The setting up of the dropzone
    init:function() {

        $(this.element).addClass("dropzone");

        this.on("success", function(file, serverFileName) {
            fileList[photo_counter] = {"serverFileName" : serverFileName.filename, "fileName" : file.name,"fileId" : photo_counter };
            photo_counter++;

        });
                    
        this.on("removedfile", function(file) {

        var rmvFile = "";
        for(x in fileList){
          if(fileList[x].fileName == file.name)
          {
              rmvFile = fileList[x].serverFileName;
              delete fileList[x];
          }
        }
                        
            $.ajax({
                type: 'POST',
                url: 'upload/delete',
                data: {id: rmvFile, _token: $('#csrf-token').val()},
                dataType: 'html',
                success: function(data){
                    var rep = JSON.parse(data);
                    if(rep.code == 200)
                    {
                        photo_counter--;
                        $("#photoCounter").text( "(" + photo_counter + ")");
                    }

                }
            });

        } );
    },
    error: function(file, response) {
        if($.type(response) === "string")
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
    success: function(file,done) {
        $("#photoCounter").text( "(" + photo_counter + ")");
    }
}



  
  var accessories = [];
  
  $(".accessories td").on('click',function(){
    var value = $(this).text();
    if($.inArray( value, accessories ) == -1){
      accessories.push(value);
    }else{
      var index = accessories.indexOf(value);
      if (index !== -1) accessories.splice(index, 1);
    }
  });

  $(".accessories td").on('mouseover',function(){
    var value = $(this).text();
    if($.inArray( value, accessories ) == -1){
      $(this).css('background-color','#DDFFDD');
    }
  });

  $(".accessories td").on('mouseout',function(){
    var value = $(this).text();
    if($.inArray( value, accessories ) == -1){
      $(this).css('background-color','#FFF');
    }
  });
    
  function saveData(){
   
    if($('#newVin').val() === ''){
      alert('Chassis Number required');
      return false;
    }else if($('#newMake').val() === ''){
      alert('Make required');
      return false;
    }else if($('#newModel').val() === ''){
      alert('Model required');
      return false;
    }else if($('#newType').val() === ''){
      alert('Product Type required');
      return false;
    }else if($('#newRegistrationYear').val() === '' || $('#newRegistrationMonth').val() === ''){
      alert('Registration date required');
      return false;
    }else if($('#newDistance').val() === ''){
      alert('Mileage required');
      return false;
    }else if($('#newType').val() === ''){
      alert('Product Type required');
      return false;
    }else if($('#newEngine').val() === ''){
      alert('Engine required');
      return false;
    }else if($('#newPrice').val() === ''){
      alert('Price required');
      return false;
    }else if($('#newSeller').val() === ''){
      alert('Seller required');
      return false;
    }

    var data = new FormData();
    data.append('newMake', $('#newMake').val());
    data.append('newModel', $('#newModel').val());
    data.append('newVin', $('#newVin').val());
    data.append('newSerial', $('#newSerial').val());
    data.append('newRegistrationYear', $('#newRegistrationYear').val());
    data.append('newRegistrationMonth', $('#newRegistrationMonth').val());
    data.append('newDistance', $('#newDistance').val());
    data.append('newType', $('#newType').val());
    data.append('newColour', $('#newColour').val());
    data.append('newEngine', $('#newEngine').val());
    data.append('newPrice', $('#newPrice').val());
    data.append('newStatus', $('#newStatus').val());
    data.append('newPromotion', $('#newPromotion').val());
    data.append('newState', $('#newState').val());
    data.append('newFuel', $('#newFuel').val());
    data.append('newSteering', $('#newSteering').val());
    data.append('newManufactureYear', $('#newManufactureYear').val());
    data.append('newManufactureMonth', $('#newManufactureMonth').val());
    data.append('newCurrencySymbol', $('#newCurrencySymbol').val());
    data.append('newType2', $('#newType2').val());
    data.append('newExteriorColor', $('#newExteriorColor').val());
    data.append('newDrive', $('#newDrive').val());
    data.append('newTransmission', $('#newTransmission').val());
    data.append('newDoor', $('#newDoor').val());
    data.append('newSeat', $('#newSeat').val());
    data.append('newWeight', $('#newWeight').val());
    data.append('newOptions', $('#newOptions').val());
    data.append('newDescription', $('#newDescription').val());
    data.append('newRemark', $('#newRemark').val());
    data.append('newComment', $('#newComment').val());
    data.append('newKeyword', $('#newKeyword').val());
    data.append('newSeller', $('#newSeller').val());
    data.append('newAgent', $('#newAgent').val());
    data.append('newBuyer', $('#newBuyer').val());
    data.append('newRecommendation', $('#newRecommendation').val());
    data.append('newBestDeal', $('#newBestDeal').val());
    data.append('newBestSeller', $('#newBestSeller').val());
    data.append('newHotCar', $('#newHotCar').val());
    data.append('newYoutube', $('#newYoutube').val());
    data.append('newInteriorColor', $('#newInteriorColor').val());
    data.append('newDimension', $('#newLength').val()+','+$('#newWidth').val()+','+$('#newHeight').val());
    data.append('newAccessories', JSON.stringify(accessories));
    
    var picture = [];
    for(x in fileList){
      picture[x] = {'filename':fileList[x]['serverFileName']}
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
//        location.reload();
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
  $.get( "{{ URL::to('/') }}/admin/car/view/"+ids, function( response ) {
    console.log(response);
    accessories = [];
    if(response.accessories !== ''){
      accessories = JSON.parse(response.accessories);
    }    

    $('#editAccessories td').each(function() {
    if($.inArray($(this).html(),accessories) != -1){   
      $(this).css('background-color','#DDFFDD');
      }
    });

    modal.find('#editMake').val(response.make_id).change();
    modal.find('#editModel').val(response.model_id).change();
    modal.find('#editVin').val(response.vin);
    modal.find('#editSerial').val(response.serial);
    modal.find('#editRegistrationYear').val(response.registration_year);
    modal.find('#editRegistrationMonth').val(response.registration_month);
    modal.find('#editDistance').val(response.distance);
    modal.find('#editType').val(response.type);
    modal.find('#editColour').val(response.colour);
    modal.find('#editEngine').val(response.engine);
    modal.find('#editPrice').val(response.price);
    modal.find('#editStatus').val(response.status).change();
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
    var dimensions = response.dimension.split(',');
    modal.find('#editLength').val(dimensions[0]);
    modal.find('#editWidth').val(dimensions[1]);
    modal.find('#editHeight').val(dimensions[2]);
    /*modal.find('#editImage').attr('src', "{{ URL::to('/') }}" + response.picture);*/
    var obj = JSON.parse(response.picture);
    try{
        $('#hEditImage').val(obj[0].picture);
        $('#hEditImage2').val(obj[1].picture);
        $('#hEditImage3').val(obj[2].picture);
        $('#hEditImage4').val(obj[3].picture);
        $('#hEditImage5').val(obj[4].picture);
        $('#hEditImage6').val(obj[5].picture);
        if(obj[0].picture !== ''){
          modal.find('#editImage').attr('src', "{{ URL::to('/') }}"+'/uploads/car/'+ obj[0].picture);
        }
        if(obj[1].picture !== ''){
          modal.find('#editImage2').attr('src', "{{ URL::to('/') }}"+'/uploads/car/'+ obj[1].picture);
        }
        if(obj[2].picture !== ''){
          modal.find('#editImage3').attr('src', "{{ URL::to('/') }}"+'/uploads/car/'+ obj[2].picture);
        }
        if(obj[3].picture !== ''){
          modal.find('#editImage4').attr('src', "{{ URL::to('/') }}"+'/uploads/car/'+ obj[3].picture);
        }
        if(obj[4].picture !== ''){
          modal.find('#editImage5').attr('src', "{{ URL::to('/') }}"+'/uploads/car/'+ obj[4].picture);
        }
        if(obj[5].picture !== ''){
          modal.find('#editImage6').attr('src', "{{ URL::to('/') }}"+'/uploads/car/'+ obj[5].picture);
        }
    }catch(e){
        modal.find('#editImage').attr('src', "http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg");
        modal.find('#editImage2').attr('src', "http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg");
        modal.find('#editImage3').attr('src', "http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg");
        modal.find('#editImage4').attr('src', "http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg");
        modal.find('#editImage5').attr('src', "http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg");
        modal.find('#editImage6').attr('src', "http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg");
    }
  });
})
function updateData(){
  var id = $('#idEdit').val();
  
  if($('#editVin').val() === ''){
    alert('Chassis Number required');
    return false;
  }else if($('#editMake').val() === ''){
    alert('Make required');
    return false;
  }else if($('#editModel').val() === ''){
    alert('Model required');
    return false;
  }else if($('#editType').val() === ''){
    alert('Product Type required');
    return false;
  }else if($('#editRegistrationYear').val() === '' || $('#editRegistrationMonth').val() === ''){
    alert('Registration date required');
    return false;
  }else if($('#editDistance').val() === ''){
    alert('Mileage required');
    return false;
  }else if($('#editType').val() === ''){
    alert('Product Type required');
    return false;
  }else if($('#editEngine').val() === ''){
    alert('Engine required');
    return false;
  }else if($('#editPrice').val() === ''){
    alert('Price required');
    return false;
  }else if($('#editSeller').val() === ''){
    alert('Seller required');
    return false;
  }
    
  var data = new FormData();
  data.append('editMake', $('#editMake').val());
  data.append('editModel', $('#editModel').val());
  data.append('editVin', $('#editVin').val());
  data.append('editSerial', $('#editSerial').val());
  data.append('editRegistrationYear', $('#editRegistrationYear').val());
  data.append('editRegistrationMonth', $('#editRegistrationMonth').val());
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
  data.append('editDimension', $('#editLength').val()+','+$('#editWidth').val()+','+$('#editHeight').val());
  data.append('editWeight', $('#editWeight').val());
  data.append('editYoutube', $('#editYoutube').val());
  data.append('editAccessories', JSON.stringify(accessories));
  var picture = [];
  for(x in fileList){
    picture[x] = {'picture':fileList[x]['serverFileName']}
  }
  data.append('editPicture', JSON.stringify(picture));
  
  $.ajax({  
    url: "{{ URL::to('/') }}/admin/car/update/"+id,  
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
function editReadURL2(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#editImage2').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function editReadURL3(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#editImage3').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function editReadURL4(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#editImage4').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function editReadURL5(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#editImage5').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function editReadURL6(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#editImage6').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
$("#editPicture").change(function() {
  editReadURL(this);
});
$("#editPicture2").change(function() {
  editReadURL2(this);
});
$("#editPicture3").change(function() {
  editReadURL3(this);
});
$("#editPicture4").change(function() {
  editReadURL4(this);
});
$("#editPicture5").change(function() {
  editReadURL5(this);
});
$("#editPicture6").change(function() {
  editReadURL6(this);
});
var countImage = 0;
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#newImage').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function readURL2(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#newImage2').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function readURL3(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#newImage3').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function readURL4(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#newImage4').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function readURL5(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#newImage5').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function readURL6(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#newImage6').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$("#newPicture").change(function() {
  readURL(this);
});
$("#newPicture2").change(function() {
  readURL2(this);
});
$("#newPicture3").change(function() {
  readURL3(this);
});
$("#newPicture4").change(function() {
  readURL4(this);
});
$("#newPicture5").change(function() {
  readURL5(this);
});
$("#newPicture6").change(function() {
  readURL6(this);
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


      $.get( "{{ URL::to('/') }}/admin/car/delete/"+id, function( response ) {
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

function prev(){
  alert(1);
}
function next(){
  alert(1);
}

</script>
@stop
