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
<ul class="list-unstyled components">
  <li><a href="{{ route('dealers.car') }}" class="active" class="dropdown-toggle">Car</a></li>
  <li><a href="{{ route('dealers.negotiation') }}" class="active" class="dropdown-toggle">Negotiation</a></li>
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
  <li><a href="{{ route('dealers.logout') }}" class="active">Logout</a></li>
</ul>
</div>
</div>