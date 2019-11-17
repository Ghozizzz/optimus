@extends('layout')
@section('content')

<!---->
<div class="login_sec">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">Login</li>
        </ol>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($pesan!='')
            <div class="alert alert-danger">
                
                <ul>
                    <li>{{ $pesan }}</li>
                </ul>
            </div>
            @endif
            @if (session('pesan')!='')
            <div class="alert alert-danger">
                
                <ul>
                    <li>{{ session('pesan') }}</li>
                    
                </ul>
            </div>
            @endif
        <h2>Login</h2>
        <div class="col-md-6 log">			 
            <form method="POST" action="{{ action('login@doLogin') }}">
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                @if($link!='')
                <input type="hidden" name="link" value="{{$link}}" />
                @endif
                <h5>Email:</h5>	
                 <input type="text" name="email">
                <h5>Password:</h5>
                <input type="password" name="password">

                

                <input type="submit" value="Login">
                <a href="#">Forgot Password ?</a>
            </form>				 
        </div>
        <div class="col-md-6 login-right">
            <h3>Buat Account Baru</h3>
            <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
            <a class="acount-btn" href="{{ action('myView@register')}}">Create an Account</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<script>
    var config = {
        routes: {
            refreshCaptcha: "{{ route('login.refreshCaptcha') }}"
        }
    };
</script>
{!! Html::script('/js/cms/login.js') !!}
<!---->
@stop
