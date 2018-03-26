<header class="main-header">
  <!-- Logo -->
  <a href="{{ Auth::user()->id != 5 ? route('customer.index') : route('orders.index')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>PARSE LINK</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>PARSE LINK</b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
   
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">     
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">            
            <i class="fa fa-gears"></i><span class="hidden-xs">Hello, {{ Auth::user()->display_name }}</span>
          </a>
          <ul class="dropdown-menu">            
            <li class="user-footer">
            <div class="pull-left">
                <a href="{{ route('account.change-pass') }}" class="btn btn-success btn-flat">Change password</a>
              </div>             
              <div class="pull-right">

                <a href="{{ route('backend.logout') }}" class="btn btn-danger btn-flat">Logout</a>
              </div>
            </li>
          </ul>
        </li>          
      </ul>
    </div>
  </nav>
</header>