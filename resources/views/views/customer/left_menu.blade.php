<div class='row'>
<div class='col-md-12 left-menu'>
  <h3>WELCOME</h3>
</div>
</div>
<div class='row'>
<div class='col-md-12'>
 {{$session['name']}}
</div>
</div>
<div class='row'>
<div class='col-md-12'>
  <hr>
</div>
</div>
<div class='row'>

<!--Master-->
<ul class='left-menu-bar'>
  <li>
    <a href="{{ route('customer.negotiationlist') }}" class="active">Negotiation <span class='notif'>{{isset($total_unread_message)?$total_unread_message:0}}</span></a>
  </li>
  <li>
    <a href="{{ route('customer.orderlist') }}" class="active">Order List <span class='notif'>{{isset($total_unread_order)?$total_unread_order:0}}</span></a>
  </li>
  <li><a href="{{ route('customer.account') }}" class="active">Account</a></li>
  <li><a href="{{ route('customer.wishlist') }}" class="active">Wishlist</a></li>
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
  <li><a href="{{ route('customer.logout') }}" class="active">Logout</a></li>
</ul>
</div>
</div>
