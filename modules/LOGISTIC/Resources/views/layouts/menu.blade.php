<div class='row'>
<div class='col-md-12 left-menu'>
  <h3>WELCOME</h3>
</div>
</div>
<div class='row'>
<div class='col-md-12'>
  {{Session::get('email')}}
</div>
</div>
<div class='row'>
<div class='col-md-12'>
  <hr>
</div>
</div>
<div class='row'>

<ul>
  <li><a href="{{ route('logistic.portcharge') }}" class="active">Port Charge</a></li>
</ul> 
</div>    
<div class='row'>
<div class='col-md-12'>
  <hr>
</div>
</div>
<div class='row'>
<div class='col-md-12'>
 <ul>
  <li><a href="{{ route('logistic.logout') }}" class="active">Logout</a></li>
</ul>
</div>
</div>