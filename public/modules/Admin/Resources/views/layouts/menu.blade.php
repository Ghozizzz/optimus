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
  <div class="col-md-12">
       
        
        
        
      @if(Session::get('login_type') === 'sales')
      <ul>
        <li><a href="{{ route('admin.customer') }}" class="active">Customer</a></li>
      </ul>
      <ul>
        <li><a href="{{ route('admin.negotiation') }}" class="active">Negotiation</a></li>
      </ul>
      @elseif(Session::get('login_type') === 'finance')
      <ul>
        <li><a href="{{ route('admin.invoice') }}" class="active">Payment</a></li>
      </ul>
      @elseif(Session::get('login_type') === 'purchaser')
      @else
      <nav id="sidebar">
      <ul class="list-unstyled components">
          <li>
              <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Master</a>
              <ul class="collapse list-unstyled child-menu" id="homeSubmenu">
                  <li>
                      <a href="{{ route('admin.carmodel') }}" class="active">Car Model</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.carmake') }}" class="active">Car Make</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.carengine') }}" class="active">Car Engine</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.banner') }}" class="active">Banner</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.portDestination') }}" class="active">Port Destination</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.portDischarge') }}" class="active">Port Discharge</a>
                  </li>
              </ul>
          </li>
          <li>
              <a href="#dealerSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dealer</a>
              <ul class="collapse list-unstyled child-menu" id="dealerSubmenu">
                  <li>
                      <a href="{{ route('admin.dealer') }}" class="active">Dealers</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.car') }}" class="active">Car</a>
                  </li>
              </ul>
          </li>
          
          <li>
              <a href="#crmSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">CRM</a>
              <ul class="collapse list-unstyled child-menu" id="crmSubmenu">
                  <li>                      
                      <a href="{{ route('admin.negotiation') }}" class="active">Negotiation</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.customer') }}" class="active">Customer</a>
                  </li>
                  <li>
                      <a href="{{ route('admin.invoice') }}" class="active">Payment</a>
                  </li>
                  
              </ul>
          </li>

          <li><a href="{{ route('admin.user') }}" class="active">User</a></li>
          <li><a href="{{ route('admin.logistic') }}" class="active">Logistic</a></li>
          <li><a href="{{ route('admin.sales') }}" class="active">Sales</a></li>
          <li><a href="{{ route('admin.setting') }}" class="active">Setting</a></li>
      </ul>
      </nav>
      
      @endif
      
  </div>
</div>    
<div class='row'>
  <div class='col-md-12'>
    <hr>
  </div>
</div>
<div class='row'>
  <div class='col-md-12'>
    <ul>
      <li><a href="{{ route('admin.logout') }}" class="active">Logout</a></li>
    </ul>
  </div>
</div>
<div class='row'>
  <div class='col-md-12'>
    <hr>
  </div>
</div>