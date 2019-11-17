@extends('layout')
@section('content')
<!---->
<div class="container">
    <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li class="active">Account</li>
    </ol>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="registration">
        <div class="registration_left">

            <h2>new user? <span> create an account </span></h2>
            <!-- [if IE] 
                   < link rel='stylesheet' type='text/css' href='ie.css'/>  
            [endif] -->  

            <!-- [if lt IE 7]>  
                   < link rel='stylesheet' type='text/css' href='ie6.css'/>  
            <! [endif] -->  
            <script>
                (function () {

                    // Create input element for testing
                    var inputs = document.createElement('input');

                    // Create the supports object
                    var supports = {};

                    supports.autofocus = 'autofocus' in inputs;
                    supports.required = 'required' in inputs;
                    supports.placeholder = 'placeholder' in inputs;

                    // Fallback for autofocus attribute
                    if (!supports.autofocus) {

                    }

                    // Fallback for required attribute
                    if (!supports.required) {

                    }

                    // Fallback for placeholder attribute
                    if (!supports.placeholder) {

                    }

                    // Change text inside send button on submit
                    var send = document.getElementById('register-submit');
                    if (send) {
                        send.onclick = function () {
                            this.innerHTML = '...Sending';
                        }
                    }

                })();
            </script>
            <div class="registration_form">
                <!-- Form -->
                <form id="registration_form" method="POST" action="{{ action('login@signup') }}">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <div>
                        <label>
                            <input placeholder="Nama Lengkap" name="nama" type="text" tabindex="1" required autofocus>
                        </label>
                    </div>

                    <div>
                        <label>
                            <input placeholder="email address:" name="email" type="email" tabindex="3" required>
                        </label>
                    </div>

                    <div class="sky_form1">
                        <ul>
                            <li><label class="radio left"><input type="radio" name="gender" checked="" value="1"><i></i>Pria</label></li>
                            <li><label class="radio"><input type="radio" name="gender" value="0"><i></i>Wanita</label></li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>					
                    <div>
                        <label>
                            <input placeholder="password" name="password" type="password" tabindex="4" required>
                        </label>
                    </div>						
                    <div>
                        <label>
                            <input placeholder="retype password" name="password_confirmation" type="password" tabindex="4" required>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>Can't read the image? Click it to get a new one.</h5>
                            <h5><img class="captcha" src="{{ $captcha_src }}" /></h5>
                            <input placeholder="captcha" name="captcha" id="captcha" type="text"  required>
                            
                        </label>
                    </div>

                    <div>
                        <input type="submit" value="create an account" id="register-submit">
                    </div>
                    <div class="sky-form">
                        <label class="checkbox"><input type="checkbox" name="agree" ><i></i>i agree to mobilya.com &nbsp;<a class="terms" href="#"> terms of service</a> </label>
                    </div>
                </form>
                <!-- /Form -->
            </div>
        </div>
        <div class="registration_left">
            <h2>existing user</h2>
            <div class="registration_form">
                <!-- Form -->
                <form method="POST" action="{{ action('login@doLogin') }}">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <h5>Email:</h5>	
                    <input type="email" name="email">
                    <h5>Password:</h5>
                    <input type="password" name="password">

                    <h5>Can't read the image? Click it to get a new one.</h5>
                    <h5><img class="captcha" src="{{ $captcha_src }}" /></h5>
                    {!! Form::text('captcha', '' , ['id' => 'captcha','placeholder'=>'Captcha' ] ) !!}

                    <input type="submit" value="Login">
                    <a href="#">Forgot Password ?</a>
                </form>
                <!-- /Form -->
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- end registration -->
<script>
    var config = {
        routes: {
            refreshCaptcha: "{{ route('login.refreshCaptcha') }}"
        }
    };
</script>
{!! Html::script('/js/cms/login.js') !!}
@stop