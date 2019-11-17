@extends('layouts.master')

@section('content')
<div class="container main-container">

  <div class='title'>
    <h1>Forget Password</h1>
  </div> 

  @if(session()->has('message') && session()->get('message') !== '')
    <div class='alert alert-info'>
  <?php echo session()->get('message'); ?>
    </div>
  @endif

  <div class="container forget-password">
    <form method="post" action="{{route('front.updatePassword')}}">
      <input type='hidden' name='email' value='{{$decode_email}}'>
      <input type='hidden' name='type' value='{{$type}}'>
      <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <div class="row">
      <div class="col-md-3">
        Password
      </div>
      <div class="col-md-8">
        <input type="password" name="password">
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        Retype Password
      </div>
      <div class="col-md-8">
        <input type="password" name="retype_password">
      </div>
    </div>
    <div class="row">
      <div class="col-md-11">
        <input type='submit' class='btn-danger' value='submit'>
      </div>
    </div>  
    </form>
  </div>

</div>
@stop


@section('script')

@stop