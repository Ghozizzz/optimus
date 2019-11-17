<div class="col-md-3 car-filter">

      <div class="carsidesearch-group">
        <i class="fas fa-search fa-lg"></i><span class="title">SEARCH BY<span>
          </span></span></div>
      <div class="accordion" id="carsidesearch">
        <div class="carsidesearch-group">
          <div class="carsidesearch-btn collapsed paddingleft60" data-toggle="collapse" data-target="#carsidesearch1">
            Make
          </div>
          <div id="carsidesearch1" class="collapse" data-parent="#carsidesearch">
            <div class="make paddingleft60">
              <ul class="menu nav flex-column"><!-- html to include -->
                @if(count($car_filter_option['make'])>0)
                @foreach($car_filter_option['make'] as $make_detail)
                <li class="nav-item dropdown">
                  <a class="nav-link" href="{{route('front.gallery').'?make='.str_slug($make_detail->make,'-')}}">{{$make_detail->make}}</a>
                </li>                 		
                @endforeach
                @endif
              </ul>			
            </div>
          </div>
        </div>
        <div class="carsidesearch-group">
          <div class="carsidesearch-btn collapsed paddingleft60" data-toggle="collapse" data-target="#carsidesearch2">
            Type
          </div>
          <div id="carsidesearch2" class="collapse" data-parent="#carsidesearch">
            <div class="type paddingleft60">
              <ul class="menu nav nav-pills flex-column"><!-- html to include -->
                @if(count($car_filter_option['body_type'])>0)
                @foreach($car_filter_option['body_type'] as $body_type_detail)
                <li class="nav-item dropdown">
                  <a class="nav-link" href="{{route('front.gallery').'?body_type='.str_slug($body_type_detail->description,'-')}}">{{$body_type_detail->description}}</a>
                </li>                 		
                @endforeach
                @endif
              </ul>			
            </div>
          </div>
        </div>
<!--        <div class="carsidesearch-group">
          <div class="carsidesearch-btn collapsed" data-toggle="collapse" data-target="#carsidesearch3">
            Category
          </div>
          <div id="carsidesearch3" class="collapse" data-parent="#carsidesearch">
            <div class="category">
              <ul class="menu nav nav-pills flex-column"> html to include 
                <li><div><span class="item"></span><a class="nav-link" href="http://funedge.co.id/optimus/index.php?r=gallery%2Findex&amp;cars%5Btransmission%5D=mt">Manual (1)</a></div>
                </li>
                <li><div><span class="item"></span><a class="nav-link" href="http://funedge.co.id/optimus/index.php?r=gallery%2Findex&amp;cars%5Bfuel%5D=diesel">Diesel (0)</a></div>
                </li>
                <li><div><span class="item"></span><a class="nav-link" href="http://funedge.co.id/optimus/index.php?r=gallery%2Findex&amp;cars%5Bdrive%5D=4wd">4wd (1)</a></div>
                </li>
              </ul>			
            </div>
          </div>
        </div>-->
        <div class="carsidesearch-group">
          <div class="carsidesearch-btn collapsed paddingleft60" data-toggle="collapse" data-target="#carsidesearch4">
            FOB Price
          </div>
          <div id="carsidesearch4" class="collapse" data-parent="#carsidesearch">
            <div class="price paddingleft60">
              <ul class="menu nav nav-pills flex-column"><!-- html to include -->
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('front.gallery').'?price_range=0-500'}}">Under 500</a>		  </li>
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('front.gallery').'?price_range=500-1000'}}">500 - 1,000</a>		  </li>
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('front.gallery').'?price_range=1000-2000'}}">1,000 - 2,000</a>		  </li>
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('front.gallery').'?price_range=2000-3000'}}">2,000 - 3,000</a>		  </li>
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('front.gallery').'?price_range=3000-4000'}}">3,000 - 4,000</a>		  </li>
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('front.gallery').'?price_range=4000'}}">Above 4,000</a>		  </li>
              </ul>			
            </div>
          </div>
        </div>
        
        <div class="carsidesearch-group collapsed">
          <div class="carsidesearch-div">
            <a href="{{route('front.gallery').'?criteria=new-arrival'}}">New Arrival</a>
          </div>          
        </div>
        <div class="carsidesearch-group collapsed">
          <div class="carsidesearch-div">
            <a href="{{route('front.gallery').'?criteria=recommended'}}">Top Recommend</a>
          </div>          
        </div>
        <div class="carsidesearch-group collapsed">
          <div class="carsidesearch-div">
            <a href="{{route('front.gallery').'?criteria=best-seller'}}">Top Sales</a>
          </div>          
        </div>
        <div class="carsidesearch-group collapsed">
          <div class="carsidesearch-div">
            <a href="{{route('front.gallery').'?criteria=clearance-sale'}}">Clearance Sale</a>
          </div>          
        </div>
      </div>

      <!--	
        <ul class="nav nav-pills flex-column">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="#">Dropdown</a>
          <div class="dropdown-menu">
              <a class="dropdown-item" href="#">Link 1</a>
              <a class="dropdown-item" href="#">Link 2</a>
              <a class="dropdown-item" href="#">Link 3</a>
          </div>
          </li>
        </ul>
      -->																															



      <!--
            <h2 class="my-4">Search by</h2>
            <div class="list-group">
              <a href="#" class="list-group-item">Category 1</a>
              <a href="#" class="list-group-item">Category 2</a>
              <a href="#" class="list-group-item">Category 3</a>
            </div>
      -->
    </div>