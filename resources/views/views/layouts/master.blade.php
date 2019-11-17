<!DOCTYPE html>
<html lang="en-US"><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf-frontend">
    <meta name="token" content="{{csrf_token()}}">
    <title>Optimus Car Trade</title>
    {!! Html::style('/assets/css/bootstrap.css') !!}
    {!! Html::style('/assets/css/jquery-ui.css') !!}
    {!! Html::style('/assets/css/select2.min.css') !!}
    {!! Html::style('/assets/css/site.css') !!}
    {!! Html::style('/assets/css/fontawesome-all.css') !!}    
    @yield('css_additional')
    <script src="{{URL::to('/')}}/assets/js/jquery_002.js"></script>
    
  <script>
    var email_login = "{{isset($session['email'])?$session['email']:''}}";
    var privilege = "{{isset($session['privilege'])?$session['privilege']:''}}"; 
    var login_type = "{{isset($session['login_type'])?$session['login_type']:''}}"; 
    var login_url = "{{route('front.checklogin')}}";
    var loginseller_url = "{{route('front.checkloginseller')}}";
    var wishlist_url = "{{route('front.wishlist')}}";
    var signup_url = "{{route('front.signup')}}";
    var signupseller_url = "{{route('front.signupseller')}}";
    var reset_password_url = "{{route('front.resetPassword')}}";
    var token = '{{csrf_token()}}';
  </script>
  
  </head>

  <body class='bg-light'>

    <div class="wrap" style='background:#fff'>
      @include('layouts.menu')
      <!-- Navigation  -fixed-top-->

      <div class="body-container">
        <div class='height5'></div>
        @yield('content')
      </div>

      <!-- Footer -->
      <div class="wrap" style='background:#fff'>
        @include('layouts.footer')
      </div>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
      <script src="{{URL::to('/')}}/assets/js/bootstrap.js"></script>
      <script src="{{URL::to('/')}}/assets/js/jquery-ui.js"></script>
      <script src="{{URL::to('/')}}/assets/js/jquery.barrating.js"></script>
      <script src="{{URL::to('/')}}/assets/js/main.js"></script>      
      <script src="{{URL::to('/')}}/assets/js/select2.min.js"></script>      
      @yield('script')
  </body>
</html>