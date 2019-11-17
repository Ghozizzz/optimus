<footer class="bg-dark">
  <div class="container footer-container">
    <div class="row">
      <div class="col-lg-4">
        <div class="contact-list-group">
          CONTACT US
          <a href="#" class="contact-list-group-item"><i class="fa fa-phone"></i>{{isset($config['phone'])?$config['phone']:''}}</a>
          <a href="#" class="contact-list-group-item"><i class="fa fa-envelope"></i>{{isset($config['email'])?$config['email']:''}}</a>
          <a target="_blank" href="{{isset($config['facebook'])? 'https://facebook.com/'.$config['facebook']:'#'}}" class="contact-list-group-item"><i class="fab fa-facebook-square"></i>{{isset($config['facebook'])?$config['facebook']:''}}</a>
          <a href="#" class="contact-list-group-item"><i class="fa fa-map-marker-alt"></i>{{isset($config['address'])?$config['address']:''}}</a>
        </div>           
      </div>
      <div class="col-lg-4">   
      </div>
      <div class="col-lg-4">
        <div class="link-list-group">
          <a href="{{route('front.term')}}" class="link-list-group-item">TERM AND CONDITION</a>
          <a href="#" class="link-list-group-item">GREAT VALUE</a>
          <a href="#" class="link-list-group-item">MONEY BACK GUARANTEE</a>
          <a href="#" class="link-list-group-item">SHOP WITH CONFIDENT</a>
          <a href="#" class="link-list-group-item">FAQ</a>
        </div>       
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="popError" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div></div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <div class="alert alert-danger">

            </div>			
          </div>
        </div>
        <!-- div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div -->
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="popInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div></div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <div class="alert">

            </div>			
          </div>
        </div>
        <!-- div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div -->
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="popSelectLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">        
        <div class="modal-body">
          <div class="pull-right">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="text-center">
            <div class="logo-pop"><img src="{{ URL::to('/') }}/assets/css/logo.png" alt="Optimus"></div>
            <div class="login-div">
              SIGN UP or LOGIN
            </div>
            <div class="login-btn-div">
              <button class="btn btn-danger" id="customer-btn">BUYER</button>
              <button class="btn btn-danger" id="seller-btn">SELLER</button>
            </div>
            <div class="row">&nbsp;</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="customerForgetPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="text-center">
            <div class="logo-pop"><img src="{{ URL::to('/') }}/assets/css/logo.png" alt="Optimus"></div>
            <div class="login-div">
              <label>Email</label>
              <input type="text" name="customer-forget-email" id="customer-forget-email">
            </div>
            <div class="login-btn-div">
              <button class="btn btn-danger" id="customer-forget-btn">Send</button>
            </div>
            <div class="row">&nbsp;</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="sellerForgetPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="text-center">
            <div class="logo-pop"><img src="{{ URL::to('/') }}/assets/css/logo.png" alt="Optimus"></div>
            <div class="login-div">
              <label>Email</label>
              <input type="text" name="seller-forget-email" id="seller-forget-email">
            </div>
            <div class="login-btn-div">
              <button class="btn btn-danger" id="seller-forget-btn">Send</button>
            </div>
            <div class="row">&nbsp;</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div class="modal fade" id="customer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="logo-pop"><img src="{{ URL::to('/') }}/assets/css/logo.png" alt="Optimus"></div>
          <!-- h5 class="modal-title" id="exampleModalLabel">Modal title</h5 -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <ul class="nav justify-content-center" id="cartabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link" id="cartabs1-tab" data-toggle="pill" href="#signup" role="tab">Sign Up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" id="cartabs2-tab" data-toggle="pill" href="#login" role="tab">Login</a>
              </li>              
            </ul>

            <div class="row">&nbsp;</div>

            <div class="tab-content" id="logintabsContent">
              <div class="tab-pane fade" id="signup" role="tabpanel" align='center'><!-- html to include -->
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="email" name='email' id='email'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="name" name='name' id='name'>
                  </div>
                </div>
                <div class="row" align='center'>  
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='password' class='form-control optimus-input' placeholder="password" name='password' id='password'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='password' class='form-control optimus-input' placeholder="password confirmation" name='password_confirmation' id='password_confirmation'>
                  </div>
                </div>
                <?php 
                $countries = \App\Http\Controllers\API::getAllCountry();
                ?>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <select id='signup_country' class='form-control optimus-input'>
                      <option value="" selected="selected">-- select country  --</option>
                    <?php foreach ($countries as $country_detail) { ?>
                        <option value="{{$country_detail->country_code}}">{{$country_detail->country_name}}</option>
                    <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="phone number (optional)" name='phone_number' id='phone_number'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    By sign up, you have agree with term and condition Optimus
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="signup-customer-loader">
                    <img src="{{ URL::to('/') }}/assets/images/small-reload.gif">
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto;padding-top: 20px;">
                    <button id='signupbtn' class='btn btn-danger'>Submit</button>
                  </div>                  
                </div>
              </div>
              <div class="tab-pane fade show active" id="login" role="tabpanel" align='center'><!-- html to include -->
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="email" name='email' id='login_email'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto">
                    <input type='password' class='form-control optimus-input' placeholder="password" name='password' id='login_password'>
                  </div>
                </div>
                <div class="forget-password"><a href="#" onclick="showCustomerForgetModal();return false;">Forget Password?</a></div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto;padding-top: 20px;">
                    <button id='loginbtn' class='btn btn-danger'>Login</button>
                  </div>                  
                </div>
              </div>

            </div>

          </div>
        </div>
        <!-- div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div -->
      </div>
    </div>
  </div>
  
  
  <!-- Modal -->
  <div class="modal fade" id="seller_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="logo-pop"><img src="{{ URL::to('/') }}/assets/css/logo.png" alt="Optimus"></div>
          <!-- h5 class="modal-title" id="exampleModalLabel">Modal title</h5 -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <ul class="nav justify-content-center" id="cartabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link" id="cartabs1-tab" data-toggle="pill" href="#signupseller" role="tab">Sign Up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" id="cartabs2-tab" data-toggle="pill" href="#loginseller" role="tab">Login</a>
              </li>              
            </ul>

            <div class="row">&nbsp;</div>

            <div class="tab-content" id="logintabsContent">
              <div class="tab-pane fade" id="signupseller" role="tabpanel" align='center'><!-- html to include -->
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="email" name='email' id='email_seller'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="name" name='name' id='name_seller'>
                  </div>
                </div>
                <div class="row" align='center'>  
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='password' class='form-control optimus-input' placeholder="password" name='password' id='password_seller'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='password' class='form-control optimus-input' placeholder="password confirmation" name='password_confirmation' id='password_confirmation_seller'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="phone number (optional)" name='phone_number' id='phone_number_seller'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-8 col-xs-10" style="margin:auto">
                    By sign up, you have agree with term and condition Optimus
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="signup-seller-loader">
                    <img src="{{ URL::to('/') }}/assets/images/small-reload.gif">
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto;padding-top: 20px;">
                    <button id='signupsellerbtn' class='btn btn-danger'>Submit</button>
                  </div>                  
                </div>
              </div>
              <div class="tab-pane fade show active" id="loginseller" role="tabpanel" align='center'><!-- html to include -->
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto">
                    <input type='text' class='form-control optimus-input' placeholder="email" name='email' id='login_email_seller'>
                  </div>
                </div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto">
                    <input type='password' class='form-control optimus-input' placeholder="password" name='password' id='login_password_seller'>
                  </div>
                </div>
                <div class="forget-password"><a href="#" onclick="showSellerForgetModal();return false;">Forget Password?</a></div>
                <div class="row" align='center'>
                  <div class="item col-md-8 col-sm-10 col-xs-10" style="margin:auto;padding-top: 20px;">
                    <button id='loginsellerbtn' class='btn btn-danger'>Login</button>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>


  <div class='copyright'>copyright &copy; {{date('Y')}}</div> 
  <!-- /.container -->
</footer>
<script>
  $("#customer-btn").on('click',function(){
    $("#popSelectLogin").modal('hide');
    $("#customer_modal").modal('show');
  })

  $("#seller-btn").on('click',function(){
    $("#popSelectLogin").modal('hide');
    $("#seller_modal").modal('show');
  })
  
  function showSellerForgetModal(){
    $("#seller_modal").modal('hide');
    $("#sellerForgetPassword").modal('show');
  }
  
  function showCustomerForgetModal(){
    $("#customer_modal").modal('hide');
    $("#customerForgetPassword").modal('show');
  }
  
  $("#customer-forget-btn").on('click',function(){
    if($("#customer-forget-email").val() !== ''){
      resetPassword($("#customer-forget-email").val(),'customer');
    }else{
      alert('Please input your email');
    }
  })
  
  $("#seller-forget-btn").on('click',function(){
    if($("#seler-forget-email").val() !== ''){
      resetPassword($("#seler-forget-email").val(),'seller');
    }else{
      alert('Please input your email');
    }
  })
  
</script>