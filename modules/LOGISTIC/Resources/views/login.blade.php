<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <!------ Include the above in your HEAD tag ---------->
  <div class="container" style="margin-top:30px">
    <div class="col-md-4 col-md-offset-4">
      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><strong>Sign in </strong></h3>
        </div>
        <div class="panel-body">
         <form method="POST" action="{{ URL::to('/') }}/logistic/signin" role="form">
           
            @if (Session::has('faileds'))
            <div class="alert alert-danger">
                  <a class="close" data-dismiss="alert" href="#">×</a>{{ Session::get("faileds") }}
              </div>
            @endif
        
          <div style="margin-bottom: 12px" class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="login-username" type="email" class="form-control" name="email" value="" placeholder="Email">
          </div>
          <div style="margin-bottom: 12px" class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="login-password" type="password" class="form-control" name="password" placeholder="Password">
          </div>
          <div class="input-group">
            <div class="checkbox" style="margin-top: 0px;">
              <label>
                <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
              </label>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Sign in</button>
          <hr style="margin-top:10px;margin-bottom:10px;" >
          <div class="form-group">
          </div> 
        </form>
      </div>
    </div>
  </div>
</div>
   <script src="{{URL::to('/')}}/assets/js/jquery_002.js"></script>
    <script src="{{URL::to('/')}}/assets/js/bootstrap.js"></script>
    <script src="{{URL::to('/')}}/assets/js/jquery-ui.js"></script>
    <script src="{{URL::to('/')}}/assets/js/jquery.js"></script>
</body>
</html>
