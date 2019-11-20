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
<h5>Car Model List</h5>
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
         {{--  <button data-toggle="modal" data-target="#newModal" class="btn btn-danger">Add</button> --}}
        </div>  
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Seller</th>
             
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
              <td>{{ $d->registration_year }}</td>
              <td>{{ $d->model }}</td>
              <td><button data-toggle="modal" data-target="#editModal" data-ids="{{ $d->id }}" class="btn btn-danger">View Detail</button></td>
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
          <h5 class="modal-title" id="exampleModalLabel">Add new</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">

              <div class="row">
                <div class="col-md-6">
                  <img width="100" height="100" id="newImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
                  <input type="file" id="newPicture" />
                </div>
                <div class="col-md-6">
                 {{-- Image 2 --}}
                 <img width="100" height="100" id="newImage2" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
                 <input type="file" id="newPicture2" />
               </div>
             </div>

             <div class="row">
              <div class="col-md-6">
                {{-- Image 3 --}}
                <img width="100" height="100" id="newImage3" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
                <input type="file" id="newPicture3" />     
              </div>
              <div class="col-md-6">
                {{-- Image 4 --}}
                <img width="100" height="100" id="newImage4" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
                <input type="file" id="newPicture4" />    
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
               {{-- Image 5 --}}
               <img width="100" height="100" id="newImage5" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
               <input type="file" id="newPicture5" />
             </div>
             <div class="col-md-6">
              <img width="100" height="100" id="newImage6" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
              <input type="file" id="newPicture6" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="col-form-label">Make:</label>
              <select name="status" class="form-control dropdownSelect2" id="newMake">
                @foreach($db['carmake'] as $c)
                <option value="{{ $c->id }}">{{ $c->make }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Model:</label>
              <select name="status" class="form-control dropdownSelect2" id="newModel">
                @foreach($db['carmodel'] as $c)
                <option value="{{ $c->id }}">{{ $c->model }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Chassis Number:</label>
              <input type="text" class="form-control" id="newVin">
            </div>
            <div class="form-group">
              <label class="col-form-label">Serial:</label>
              <input type="text" class="form-control" id="newSerial">
            </div>
            <div class="form-group">
              <label class="col-form-label">Year:</label>
              <input type="number" class="form-control" id="newRegistrationYear">
            </div>
            <div class="form-group">
              <label class="col-form-label">Distance:</label>
              <input type="text" class="form-control" id="newDistance">
            </div>
            <div class="form-group">
              <label class="col-form-label">Type:</label>
              <input type="text" class="form-control" id="newType">
            </div>
            <div class="form-group">
              <label class="col-form-label">Colour:</label>
              <input type="text" class="form-control" id="newColour">
            </div>
            <div class="form-group">
              <label class="col-form-label">Engine:</label>
              <select name="status" class="form-control" id="newEngine">
                @foreach($db['carengine'] as $c)
                <option value="{{ $c->description }}">{{ $c->description }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Price:</label>
              <input type="text" class="form-control" id="newPrice">
            </div>
            <div class="form-group">
              <label class="col-form-label">Currency Symbol:</label>
              <input type="text" class="form-control" id="newCurrencySymbol">
            </div>
            <div class="form-group">
              <label class="col-form-label">Status:</label>
              <select name="status" class="form-control" id="newStatus">
                <option value="0">Active</option>
                <option value="1">Inactive</option>
                <option value="2">Inactive</option>
                <option value="3">Sold</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Promotion:</label>
              <select name="status" class="form-control" id="newPromotion">
                <option value="1">Normal</option>
                <option value="2">Promo</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">State:</label>
              <select name="status" class="form-control" id="newState">
                <option value="1">New</option>
                <option value="2">Used</option>
                <option value="3">Broken</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Fuel:</label>
              <select name="status" class="form-control" id="newFuel">
                <option value="1">Gasoline</option>
                <option value="2">Diesel</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Steering:</label>
              <select name="status" class="form-control" id="newSteering">
                <option value="1">Left</option>
                <option value="2">Right</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Menufacture Year:</label>
              <input type="number" class="form-control" max="4" id="newMenuFactureYear">
            </div>
            <div class="form-group">
              <label class="col-form-label">Type 2:</label>
              <input type="text" class="form-control" id="newType2">
            </div>
            <div class="form-group">
              <label class="col-form-label">Exterior Color:</label>
              <input type="text" class="form-control" id="newExteriorColor">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="col-form-label">Drive:</label>
              <select name="status" class="form-control" id="newDrive">
                <option value="1">Rear</option>
                <option value="2">Front</option>
                <option value="3">4WD</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Transmission:</label>
              <select name="status" class="form-control" id="newTransmission">
                <option value="1">MT</option>
                <option value="2">AT</option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label">Door:</label>
              <input type="text" class="form-control" id="newDoor">
            </div>
            <div class="form-group">
              <label class="col-form-label">Seat:</label>
              <input type="text" class="form-control" id="newSeat">
            </div>
            <div class="form-group">
              <label class="col-form-label">Options:</label>
              <input type="text" class="form-control" id="newOptions">
            </div>
            <div class="form-group">
              <label class="col-form-label">Description:</label>
              <input type="text" class="form-control" id="newDescription">
            </div>
            <div class="form-group">
              <label class="col-form-label">Remark:</label>
              <input type="text" class="form-control" id="newRemark">
            </div>
            <div class="form-group">
              <label class="col-form-label">Comment:</label>
              <input type="text" class="form-control" id="newComment">
            </div>
            <div class="form-group">
              <label class="col-form-label">Keyword:</label>
              <input type="text" class="form-control" id="newKeyword">
            </div>
            <div class="form-group">
              <label class="col-form-label">Seller:</label>
              <select name="status" class="form-control dropdownSelect2" id="newSeller">
                @foreach($db['seller'] as $c)
                <option value="{{ $c->id }}">{{ $c->pic_name }}</option>
                @endforeach
              </select>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Agent:</label>
            <select name="status" class="form-control dropdownSelect2" id="newAgent">
                @foreach($db['agent'] as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">Buyer:</label>
            <input type="text" class="form-control" id="newBuyer">
          </div>
          <div class="form-group">
            <label class="col-form-label">Recommendation:</label>
            <input type="text" class="form-control" id="newRecommendation">
          </div>
          <div class="form-group">
            <label class="col-form-label">Best Deal:</label>
            <input type="text" class="form-control" id="newBestDeal">
          </div>
          <div class="form-group">
            <label class="col-form-label">Best Seller:</label>
            <input type="text" class="form-control" id="newBestSeller">
          </div>
          <div class="form-group">
            <label class="col-form-label">Hot Car:</label>
            <input type="text" class="form-control" id="newHotCar">
          </div>
          <div class="form-group">
            <label class="col-form-label">Interior Color:</label>
            <input type="text" class="form-control" id="newInteriorColor">
          </div>
          <div class="form-group">
            <label class="col-form-label">Dimension:</label>
            <input type="text" class="form-control" id="newDimension">
          </div>
        </div>
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
        <h5 class="modal-title" id="exampleModalLabel">View Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
         <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <img width="100" height="100" id="editImage" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
              <input type="hidden" id="hEditImage">          
            </div>
            <div class="col-md-6">
             {{-- Image 2 --}}
             <img width="100" height="100" id="editImage2" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
             <input type="hidden" id="hEditImage2">
           </div>
         </div>
         <div class="row">
          <div class="col-md-6">
            {{-- Image 3 --}}
            <img width="100" height="100" id="editImage3" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
            <input type="hidden" id="hEditImage3">
          </div>
          <div class="col-md-6">
            {{-- Image 4 --}}
            <img width="100" height="100" id="editImage4" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
            <input type="hidden" id="hEditImage4"> 
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
           {{-- Image 5 --}}
           <img width="100" height="100" id="editImage5" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
           <input type="hidden" id="hEditImage5">    
         </div>
         <div class="col-md-6">
          <img width="100" height="100" id="editImage6" src="http://cdn7.bigcommerce.com/s-viqdwewl26/stencil/8f903ed0-76e7-0135-12e4-525400970412/icons/icon-no-image.svg"/>
          <input type="hidden" id="hEditImage6">
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-form-label">Make:</label>
          <select disabled name="status" class="form-control" id="editMake">
            @foreach($db['carmake'] as $c)
            <option value="{{ $c->id }}">{{ $c->make }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Model:</label>
          <select disabled name="status" class="form-control" id="editModel">
            @foreach($db['carmodel'] as $c)
            <option value="{{ $c->id }}">{{ $c->model }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Chassis Number:</label>
          <input disabled type="text" class="form-control" id="editVin">
        </div>
        <div class="form-group">
          <label class="col-form-label">Serial:</label>
          <input disabled type="text" class="form-control" id="editSerial">
        </div>
        <div class="form-group">
          <label class="col-form-label">Year:</label>
          <input disabled type="number" max="4" class="form-control" id="editRegistrationYear">
        </div>
        <div class="form-group">
          <label class="col-form-label">Distance:</label>
          <input disabled type="text" class="form-control" id="editDistance">
        </div>
        <div class="form-group">
          <label class="col-form-label">Type:</label>
          <input disabled type="text" class="form-control" id="editType">
        </div>
        <div class="form-group">
          <label class="col-form-label">Colour:</label>
          <input disabled type="text" class="form-control" id="editColour">
        </div>
        <div class="form-group">
          <label class="col-form-label">Engine:</label>
          <select disabled name="status" class="form-control" id="editEngine">
            @foreach($db['carengine'] as $c)
            <option value="{{ $c->description }}">{{ $c->description }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Price:</label>
          <input disabled type="text" class="form-control" id="editPrice">
        </div>
        <div class="form-group">
          <label class="col-form-label">Currency Symbol:</label>
          <input disabled type="text" class="form-control" id="editCurrencySymbol">
        </div>
        <div class="form-group">
          <label class="col-form-label">Status:</label>
          <select disabled name="status" class="form-control" id="editStatus">
            <option value="0">Active</option>
            <option value="1">Inactive</option>
            <option value="2">Inactive</option>
            <option value="3">Sold</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Promotion:</label>
          <select disabled name="status" class="form-control" id="editPromotion">
            <option value="1">Normal</option>
            <option value="2">Promo</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">State:</label>
          <select disabled name="status" class="form-control" id="editState">
            <option value="1">edit</option>
            <option value="2">Used</option>
            <option value="3">Broken</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Fuel:</label>
          <select disabled name="status" class="form-control" id="editFuel">
            <option value="1">Gasoline</option>
            <option value="2">Diesel</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Steering:</label>
          <select disabled name="status" class="form-control" id="editSteering">
            <option value="1">Left</option>
            <option value="2">Right</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Menufacture Year:</label>
          <input disabled type="number" max="4" class="form-control" id="editMenuFactureYear">
        </div>
        <div class="form-group">
          <label class="col-form-label">Type 2:</label>
          <input disabled type="text" class="form-control" id="editType2">
        </div>
        <div class="form-group">
          <label class="col-form-label">Exterior Color:</label>
          <input disabled type="text" class="form-control" id="editExteriorColor">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-form-label">Drive:</label>
          <select disabled name="status" class="form-control" id="editDrive">
            <option value="1">Rear</option>
            <option value="2">Front</option>
            <option value="3">4WD</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Transmission:</label>
          <select disabled name="status" class="form-control" id="editTransmission">
            <option value="1">MT</option>
            <option value="2">AT</option>
          </select>
        </div>
        <div class="form-group">
          <label class="col-form-label">Door:</label>
          <input disabled type="text" class="form-control" id="editDoor">
        </div>
        <div class="form-group">
          <label class="col-form-label">Seat:</label>
          <input disabled type="text" class="form-control" id="editSeat">
        </div>
        <div class="form-group">
          <label class="col-form-label">Options:</label>
          <input disabled type="text" class="form-control" id="editOptions">
        </div>
        <div class="form-group">
          <label class="col-form-label">Description:</label>
          <input disabled type="text" class="form-control" id="editDescription">
        </div>
        <div class="form-group">
          <label class="col-form-label">Remark:</label>
          <input disabled type="text" class="form-control" id="editRemark">
        </div>
        <div class="form-group">
          <label class="col-form-label">Comment:</label>
          <input disabled type="text" class="form-control" id="editComment">
        </div>
        <div class="form-group">
          <label class="col-form-label">Keyword:</label>
          <input disabled type="text" class="form-control" id="editKeyword">
        </div>
        <div class="form-group">
          <label class="col-form-label">Seller:</label>
          <select disabled name="status" class="form-control" id="editSeller">
            @foreach($db['seller'] as $c)
            <option value="{{ $c->id }}">{{ $c->pic_name }}</option>
            @endforeach
          </select>
        </select>
      </div>
       <div class="form-group">
            <label class="col-form-label">Agent:</label>
            <select disabled name="status" class="form-control dropdownSelect2" id="editAgent">
                @foreach($db['agent'] as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
            </select>
          </div>
      <div class="form-group">
        <label class="col-form-label">Buyer:</label>
        <input disabled type="text" class="form-control" id="editBuyer">
      </div>
      <div class="form-group">
        <label class="col-form-label">Recommendation:</label>
        <input disabled type="text" class="form-control" id="editRecommendation">
      </div>
      <div class="form-group">
        <label class="col-form-label">Best Deal:</label>
        <input disabled type="text" class="form-control" id="editBestDeal">
      </div>
      <div class="form-group">
        <label class="col-form-label">Best Seller:</label>
        <input disabled type="text" class="form-control" id="editBestSeller">
      </div>
      <div class="form-group">
        <label class="col-form-label">Hot Car:</label>
        <input disabled type="text" class="form-control" id="editHotCar">
      </div>
      <div class="form-group">
        <label class="col-form-label">Interior Color:</label>
        <input disabled type="text" class="form-control" id="editInteriorColor">
      </div>
      <div class="form-group">
        <label class="col-form-label">Dimension:</label>
        <input disabled type="text" class="form-control" id="editDimension">
      </div>
    </div>
  </div>
</form>
</div>
<div class="modal-footer">
 {{--  <button onclick="updateData()" type="button" class="btn btn-primary">View</button> --}}

</div>
</div>
</div>
</div>
@stop
@section('script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
<script>
 
   /*$(".dropdownSelect2").select2({
           dropdownParent: $("#newModal")
    });*/


  function saveData(){
    var file_data = $('#newPicture').prop('files')[0];
    var file_data2 = $('#newPicture2').prop('files')[0];
    var file_data3 = $('#newPicture3').prop('files')[0];
    var file_data4 = $('#newPicture4').prop('files')[0];
    var file_data5 = $('#newPicture5').prop('files')[0];
    var file_data6 = $('#newPicture6').prop('files')[0];

    var data = new FormData();
    data.append('newMake', $('#newMake').val());
    data.append('newModel', $('#newModel').val());
    data.append('newVin', $('#newVin').val());
    data.append('newSerial', $('#newSerial').val());
    data.append('newRegistrationYear', $('#newRegistrationYear').val());
    data.append('newDistance', $('#newDistance').val());
    data.append('newType', $('#newType').val());
    data.append('newColour', $('#newColour').val());
    data.append('newEngine', $('#newEngine').val());
    data.append('newPrice', $('#newPrice').val());
    data.append('newCurrencySymbol', $('#newCurrencySymbol').val());
    data.append('newStatus', $('#newStatus').val());
    data.append('newPromotion', $('#newPromotion').val());
    data.append('newState', $('#newState').val());
    data.append('newFuel', $('#newFuel').val());
    data.append('newSteering', $('#newSteering').val());
    data.append('newMenuFactureYear', $('#newMenuFactureYear').val());
    data.append('newCurrencySymbol', $('#newCurrencySymbol').val());
    data.append('newType2', $('#newType2').val());
    data.append('newExteriorColor', $('#newExteriorColor').val());
    data.append('newDrive', $('#newDrive').val());
    data.append('newTransmission', $('#newTransmission').val());
    data.append('newDoor', $('#newDoor').val());
    data.append('newSeat', $('#newSeat').val());
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
    data.append('newInteriorColor', $('#newInteriorColor').val());
    data.append('newDimension', $('#newDimension').val());
    data.append('newPicture', file_data);
    data.append('newPicture2', file_data2);
    data.append('newPicture3', file_data3);
    data.append('newPicture4', file_data4);
    data.append('newPicture5', file_data5);
    data.append('newPicture6', file_data6);
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

  $('#editModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var ids = button.data('ids') // Extract info from data-* attributes
  $('#idEdit').val(ids);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  $.get( "{{ URL::to('/') }}/dealers/car/view/"+ids, function( response ) {
    console.log(response);
    modal.find('#editMake').val(response.make_id).change();
    modal.find('#editModel').val(response.model_id).change();
    modal.find('#editVin').val(response.vin);
    modal.find('#editSerial').val(response.serial);
    modal.find('#editRegistrationYear').val(response.registration_year);
    modal.find('#editDistance').val(response.distance);
    modal.find('#editType').val(response.type);
    modal.find('#editColour').val(response.colour);
    modal.find('#editEngine').val(response.engine);
    modal.find('#editPrice').val(response.price);
    modal.find('#editCurrencySymbol').val(response.currency_symbol);
    modal.find('#editStatus').val(response.status).change();
    modal.find('#editPromotion').val(response.promotion).change();
    modal.find('#editState').val(response.state).change();
    modal.find('#editFuel').val(response.fuel);
    modal.find('#editSteering').val(response.steering).change();
    modal.find('#editMenuFactureYear').val(response.manufacture_year);
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
    modal.find('#editRecommendation').val(response.recommendation);
    modal.find('#editBestDeal').val(response.best_deal);
    modal.find('#editBestSeller').val(response.best_seller);
    modal.find('#editHotCar').val(response.hot_car);
    modal.find('#editInteriorColor').val(response.interior_color);
    modal.find('#editDimension').val(response.dimension);
    /*modal.find('#editImage').attr('src', "{{ URL::to('/') }}" + response.picture);*/
   var obj = JSON.parse(response.picture);
    try{
        $('#hEditImage').val(obj[0].picture);
        $('#hEditImage2').val(obj[1].picture);
        $('#hEditImage3').val(obj[2].picture);
        $('#hEditImage4').val(obj[3].picture);
        $('#hEditImage5').val(obj[4].picture);
        $('#hEditImage6').val(obj[5].picture);
        modal.find('#editImage').attr('src', "{{ URL::to('/') }}"+'/images/car/'+ obj[0].picture);
        modal.find('#editImage2').attr('src', "{{ URL::to('/') }}"+'/images/car/'+ obj[1].picture);
        modal.find('#editImage3').attr('src', "{{ URL::to('/') }}"+'/images/car/'+ obj[2].picture);
        modal.find('#editImage4').attr('src', "{{ URL::to('/') }}"+'/images/car/'+ obj[3].picture);
        modal.find('#editImage5').attr('src', "{{ URL::to('/') }}"+'/images/car/'+ obj[4].picture);
        modal.find('#editImage6').attr('src', "{{ URL::to('/') }}"+'/images/car/'+ obj[5].picture);
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
  var file_data = $('#editPicture').prop('files')[0];
  var file_data2 = $('#editPicture2').prop('files')[0];
  var file_data3 = $('#editPicture3').prop('files')[0];
  var file_data4 = $('#editPicture4').prop('files')[0];
  var file_data5 = $('#editPicture5').prop('files')[0];
  var file_data6 = $('#editPicture6').prop('files')[0];

  if(file_data == null) {
    var file_data = $('#hEditImage').val();
  }
  if(file_data2 == null) {
    var file_data2 = $('#hEditImage2').val();
  }
  if(file_data3 == null) {
    var file_data3 = $('#hEditImage3').val();
  }
  if(file_data4 == null) {
    var file_data4 = $('#hEditImage4').val();
  }
  if(file_data5 == null) {
    var file_data5 = $('#hEditImage5').val();
  }
  if(file_data6 == null) {
    var file_data6 = $('#hEditImage6').val();
  }

  var data = new FormData();
  data.append('editMake', $('#editMake').val());
  data.append('editModel', $('#editModel').val());
  data.append('editVin', $('#editVin').val());
  data.append('editSerial', $('#editSerial').val());
  data.append('editRegistrationYear', $('#editRegistrationYear').val());
  data.append('editDistance', $('#editDistance').val());
  data.append('editType', $('#editType').val());
  data.append('editColour', $('#editColour').val());
  data.append('editEngine', $('#editEngine').val());
  data.append('editPrice', $('#editPrice').val());
  data.append('editCurrencySymbol', $('#editCurrencySymbol').val());
  data.append('editStatus', $('#editStatus').val());
  data.append('editPromotion', $('#editPromotion').val());
  data.append('editState', $('#editState').val());
  data.append('editFuel', $('#editFuel').val());
  data.append('editSteering', $('#editSteering').val());
  data.append('editMenuFactureYear', $('#editMenuFactureYear').val());
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
  data.append('editDimension', $('#editDimension').val());
  data.append('editPicture', file_data);
  data.append('editPicture2', file_data2);
  data.append('editPicture3', file_data3);
  data.append('editPicture4', file_data4);
  data.append('editPicture5', file_data5);
  data.append('editPicture6', file_data6);
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
      console.log(file_data);
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
