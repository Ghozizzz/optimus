@extends('layouts.master')

@section('content')
<div class="container">

  <div class='title'>
    <h1>Contact</h1>
  </div> 
  <div class="row">
    <div class='col-md-12'>
      <p>If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.</p>
      {!! $contact_info !!}
    </div>
  </div>

  <div class='h50'></div>
  <div class="row">
    <div class='col-md-6'>
      <label>Name</label>
      <input type="text" class="form-control" name='name' id='contact_name'>
    </div>
  </div>
  <div class="row">
    <div class='col-md-6'>
      <label>Email</label>
      <input type="text" class="form-control" name='email' id='contact_email'>
    </div>
  </div>
  <div class="row">
    <div class='col-md-6'>
      <label>Subject</label>
      <input type="text" class="form-control" name='subject' id='contact_subject'>
    </div>
  </div>
  <div class="row">
    <div class='col-md-6'>
      <label>Message</label>
      <textarea class="form-control" name='message' id='contact_message' rows="5"></textarea>
    </div>
  </div>
  <div class="row">
    <div class='h50'>

    </div>
  </div>
  <div class="row">
    <div class='col-md-2'>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name='captcha' id='captcha_val'>        
      </div>
    </div>
    <div class='col-md-10'>Can't read the image? Click it to get a new one.
        <img class="captcha" src="{{ $captcha_src }}" /></div>
  </div>
  <div class="row">
    <div class='h50'>

    </div>
  </div>

  <div class="row">
    <div class='col-md-6'>
      <input type='submit' name='send_mail' id='send_mail' class='btn btn-danger' value='submit'>
    </div>
  </div>

  <div class="row">
    <div class='h50'>

    </div>
  </div>
</div>
@stop


@section('script')
<script>

  $("#send_mail").on('click', function () {

    if ($('#contact_name').val() === '') {
      alert('please fill your name');
      return false;
    }
    if ($('#contact_email').val() === '') {
      alert('please fill your email');
      return false;
    }
    if ($('#contact_subject').val() === '') {
      alert('please fill your subject');
      return false;
    }
    if ($('#contact_message').val() === '') {
      alert('please fill message');
      return false;
    }

    var sent_mail_url = "{{route('customer.sendContact')}}";
    var data = {
      'name': $('#contact_name').val(),
      'email': $('#contact_email').val(),
      'subject': $('#contact_subject').val(),
      'message': $('#contact_message').val(),
      'captcha': $('#captcha_val').val(),
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
      },
      url: sent_mail_url,
      type: 'post',
      dataType: 'json',
      data: data,
      beforeSend: function () {
      },
      success: function (e) {
        refreshCaptcha();

        if (e.error == 0) {
          $('#contact_name').val('');
          $('#contact_email').val('');
          $('#contact_subject').val('');
          $('#contact_message').val('');
        }
        alert(e.message);
      },
      error: function (e){
        alert('time out');
      }
    });
  })

  var config = {
    routes: {
      refreshCaptcha: "{{ route('front.refreshCaptcha') }}"
    }
  };

  var refreshCaptcha = function () {

    $.ajax({
      url: config.routes.refreshCaptcha,
      type: 'get',
      dataType: 'json',
      beforeSend: function () {
      },
      success: function (e) {
        $(".captcha").attr('src', e.source);
        $(".captcha").on('load', function () {
          $("#loaderCaptcha").hide();
        });
      }
    });

  };


  $(".captcha").on('click', function (e) {
    refreshCaptcha();
  });
</script>
@stop