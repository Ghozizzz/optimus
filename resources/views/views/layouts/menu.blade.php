<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand" href="{{ URL::to('/') }}"><img src="{{ URL::to('/') }}/assets/css/logo.png" alt="Optimus"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav menu-bar">
        <!--li class="nav-item active">
          <a class="nav-link" href="#"><img src="assets/images/menu-home.png" width="56" height="56"> <span class="sr-only">(current)</span>
          </a>
        </li-->
        <?php 
        $home_active = '';
        $about_active = '';
        $contact_active = '';
        $review_active = '';
        $gallery_active = '';
        $term_active = '';
        if(isset($active_menu)){
          if($active_menu === 'home') {
            $home_active = 'active';
            $contact_active = '';
            $review_active = '';
            $gallery_active = '';
            $term_active = '';
          }elseif($active_menu === 'contact') {
            $home_active = '';
            $about_active = '';
            $contact_active = 'active';
            $review_active = '';
            $gallery_active = '';
            $term_active = '';
          }elseif($active_menu === 'about') {
            $home_active = '';
            $about_active = 'active';
            $contact_active = '';
            $review_active = '';
            $gallery_active = '';
            $term_active = '';
          }elseif($active_menu === 'gallery') {
            $home_active = '';
            $about_active = '';
            $contact_active = '';
            $review_active = '';
            $gallery_active = 'active';
            $term_active = '';
          }elseif($active_menu === 'review') {
            $home_active = '';
            $about_active = '';
            $contact_active = '';
            $review_active = 'active';
            $gallery_active = '';
            $term_active = '';
          }elseif($active_menu === 'term') {
            $home_active = '';
            $about_active = '';
            $contact_active = '';
            $review_active = '';
            $gallery_active = '';
            $term_active = 'active';
          }
          
        }?>
        <li class="nav-item menu-bar-item">
          <a class="nav-icon home {{$home_active}}" href="{{route('front.home')}}"><span>Home</span></a>
        </li>
        <li class="nav-item menu-bar-item">
          <a class="nav-icon about {{$about_active}}" href="{{route('front.about')}}"><span>About Us</span></a>
        </li>
        <li class="nav-item menu-bar-item">
          <a class="nav-icon gallery {{$gallery_active}}" href="{{route('front.gallery')}}"><span>Gallery</span></a>
        </li>
        <li class="nav-item menu-bar-item">
          <a class="nav-icon review {{$review_active}}" href="{{route('front.review')}}"><span>Review</span></a>
        </li>
        <li class="nav-item menu-bar-item">
          <a class="nav-icon toc {{$term_active}}" href="{{route('front.term')}}"><span>Term & Condition</span></a>
        </li>
        <li class="nav-item menu-bar-item">
          <a class="nav-icon contact {{$contact_active}}" href="{{route('front.contact')}}"><span>Contact Us</span></a>
        </li>
        <li class="nav-item menu-bar-item account-bar">
          <?php
          $user_id = Session::get('user_id');
          $login_type = Session::get('login_type');
          if(isset($user_id) && $user_id !== '' && $login_type == 'customer'){?>
            <a class="nav-icon admin {{$about_active}}" href="{{route('customer.customerDashboard')}}"><span>Dashboard</span></a>
          <?php         
          }elseif(isset($user_id) && $user_id !== '' && isset($login_type)){?>
            
          <?php }else{?>
            <button type="button" class="btn btn-primary login-btn" data-toggle="modal" data-target="#popSelectLogin">login / sign up</button>
          <?php } ?>
          <!-- a class="nav-icon member " href="/optimus/index.php?r=site%2Fmember"><span>Account</span></a -->
        </li>
      </ul>
    </div>
  </div>
</nav>
