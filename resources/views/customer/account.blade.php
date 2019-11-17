@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
{!! Html::style('/assets/css/bootstrap-datetimepicker.min.css') !!}
@section('content')
<style>
  .admin-content .row{
    padding:5px;
  }
</style>  
<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('customer.left_menu')
    </div>

    <div class="col-md-10 right-menu-div">
      
      
      <div class='wraper'>
        <div class='admin-title'>
          <h2>My Profile</h2>
        </div>
        <div>
          <hr>
        </div>
        
          @if(session()->has('message') && session()->get('message') !== '')
            <div class='alert alert-info'>
          <?php echo session()->get('message'); ?>
            </div>
          @endif
       
        
          <table width='100%'>
            <tr>
              <td width='20%' valign='top'>
                @if($account->photo !== '' && $account->photo !== null)  
                  <img src='{{URL::to('/')}}/uploads/customer/{{$account->photo}}' width='150px'>
                @else 
                  <img src='{{URL::to('/')}}/assets/images/no_photo.gif' width='150px'>
                  <br>
                  * please upload your photo 
                @endif
              </td>
              <td>
              <div class='admin-content'>  
                <form method="post" action="{{route('customer.saveAccount')}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{csrf_token()}}'>
                <input type='hidden' name='id' value='{{$account->id}}'>
              <div class='row'>
                <div class='col-md-3'>
                  Name
                </div>
                <div class='col-md-6'>
                  <input class='form-control' type='text' name='name' value='<?php echo $account->name?>'>
                </div>            
              </div>
              <div class='row'>
                <div class='col-md-3'>
                  Email
                </div>
                <div class='col-md-6'>
                  <input class='form-control' type='text' name='email' readonly="true" value='<?php echo $account->email?>'>
                </div>            
              </div>
              <div class='row'>
                <div class='col-md-3'>
                  Phone Number
                </div>
                <div class='col-md-6'>
                  <input class='form-control' type='text' name='phone' value='<?php echo $account->phone?>'>
                </div>            
              </div>
              <div class='row'>
                <div class='col-md-3'>
                  Gender
                </div>
                <div class='col-md-6'>
                  <input type='radio' name='gender' value='M' <?php if($account->gender == 'M') echo 'checked'; ?>>Male
                  <input type='radio' name='gender' value='F' <?php if($account->gender == 'F') echo 'checked'; ?>>Female
                </div>            
              </div>          
              <div class='row'>
                <div class='col-md-3'>
                  Date of Birth
                </div>
                <div class='col-md-6'>
                  <input class='form-control' type='text' name='birthday' value='<?php echo $account->birthday?>' id="birthday" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" autocomplete="off">
                </div>            
              </div>
              <div class='row'>
                <div class='col-md-3'>
                  Address
                </div>
                <div class='col-md-6'>
                  <textarea class='form-control' name='address' id="address" autocomplete="off"><?php echo $account->address?></textarea>
                </div>            
              </div>  
              <div class='row'>
                <div class='col-md-3'>
                  Password
                </div>
                <div class='col-md-6'>
                  <input class='form-control' name='password' id="password" autocomplete="off" readonly="true" type='password'>
                </div> 
                <div class='col-md-1' valign='middle'>
                  <input id='cb-password' type='checkbox' checked="true" style='margin-top: 10%'>
                </div>
              </div>
              <div class='row'>
                <div class='col-md-3'>
                  Country
                </div>
                <div class='col-md-6'>
                  <select id='country' name='country' class='form-control optimus-input'>
                    <option value="" selected="selected">-- select country  --</option>
                  <?php foreach ($countries as $country_detail) { ?>
                    @if($country_detail->country_code == $account->country_code)
                      <option value="{{$country_detail->country_code}}" selected="true">{{$country_detail->country_name}}</option>
                    @else
                      <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                    @endif
                  <?php } ?>
                  </select>
                </div>            
              </div>

              <div class='row'>
                <div class='col-md-3'>
                  Photo profile
                </div>
                <div class='col-md-6'>
                  <input class='form-control' type='file' name='photo'>
                </div>            
              </div>


                <br><br>
              <div class='row'>
                <div class='col-md-8' align='center'>
                  <input type="submit" class="btn btn-danger" value='save'>
                </div>
              </div>
            </form>
            </div>    
              </td>
            </tr>
          </table>
        
      </div>
      
      
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script src="{{URL::to('/')}}/assets/js/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="{{URL::to('/')}}/assets/js/datetimepicker/locales/bootstrap-datetimepicker.id.js"></script>
<script>
  checkPasswordActive();
  
  function checkPasswordActive(){
    if($("#cb-password").is(':checked')){
      $("#password").prop('readonly',true);
    }else{
      $("#password").prop('readonly',false);
    }
  }
  $("#cb-password").on('change',function(){
    checkPasswordActive();
  })
  $('#birthday').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
@stop
