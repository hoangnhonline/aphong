<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ URL::asset('public/admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ Auth::user()->display_name }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->    
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
      @if(Auth::user()->id != 5 && Auth::user()->role > 1)
      <li class="treeview {{ in_array(\Request::route()->getName(), ['product.index', 'product.create', 'product.edit', 'cate-type.index', 'cate-type.edit', 'cate-type.create', 'cate.index', 'cate.edit', 'cate.create', 'cate-parent.index', 'cate-parent.edit', 'cate-parent.create', 'grand.index', 'grand.edit', 'grand.create']) ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-opencart"></i> 
          <span>Sản phẩm</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li {{ in_array(\Request::route()->getName(), ['product.index', 'product.edit']) ? "class=active" : "" }}><a href="{{ route('product.index') }}"><i class="fa fa-circle-o"></i> Danh sách</a></li>
          <li {{ \Request::route()->getName() == "product.create" ? "class=active" : "" }}><a href="{{ route('product.create') }}"><i class="fa fa-circle-o"></i> Thêm sản phẩm</a></li>        
               
          <li {{ in_array(\Request::route()->getName(), ['cate-parent.index', 'cate-parent.edit', 'cate-parent.create']) ? "class=active" : "" }}><a href="{{ route('cate-parent.index') }}"><i class="fa fa-circle-o"></i> Loại sản phẩm</a></li>
          <li {{ in_array(\Request::route()->getName(), ['cate.index', 'cate.edit', 'cate.create']) ? "class=active" : "" }}><a href="{{ route('cate.index') }}"><i class="fa fa-circle-o"></i> Danh mục cha</a></li>
          <li {{ in_array(\Request::route()->getName(), ['grand.index', 'grand.edit', 'grand.create']) ? "class=active" : "" }}><a href="{{ route('grand.index') }}"><i class="fa fa-circle-o"></i> Danh mục con</a></li>
          
        </ul>
      </li>  
      @endif  
      <li {{ in_array(\Request::route()->getName(), ['orders.index']) ? "class=active" : "" }}>
        <a href="{{ route('orders.index') }}">
          <i class="fa fa-pencil-square-o"></i> 
          <span>Đơn hàng</span>          
        </a>       
      </li>  
      <li {{ in_array(\Request::route()->getName(), ['customer.index']) ? "class=active" : "" }}>
        <a href="{{ route('customer.index') }}">
          <i class="fa fa-pencil-square-o"></i> 
          <span>Thành viên</span>          
        </a>       
      </li>             
      @if(Auth::user()->id != 5 && Auth::user()->role > 1)
      <li class="treeview {{ in_array(\Request::route()->getName(), ['pages.index', 'pages.create']) ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-twitch"></i> 
          <span>Trang</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li {{ in_array(\Request::route()->getName(), ['pages.index', 'pages.edit']) ? "class=active" : "" }}><a href="{{ route('pages.index') }}"><i class="fa fa-circle-o"></i> Trang</a></li>
          <li {{ in_array(\Request::route()->getName(), ['pages.create']) ? "class=active" : "" }}><a href="{{ route('pages.create') }}"><i class="fa fa-circle-o"></i> Thêm trang</a></li>          
        </ul>
      </li>
      
      <li class="treeview {{ in_array(\Request::route()->getName(), ['articles.index', 'articles.create', 'articles.edit','articles-cate.create', 'articles-cate.index', 'articles-cate.edit']) ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-pencil-square-o"></i> 
          <span>Bài viết</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li {{ in_array(\Request::route()->getName(), ['articles.edit', 'articles.index']) ? "class=active" : "" }}><a href="{{ route('articles.index') }}"><i class="fa fa-circle-o"></i> Bài viết</a></li>
          <li {{ in_array(\Request::route()->getName(), ['articles.create']) ? "class=active" : "" }} ><a href="{{ route('articles.create', ['cate_id' => 1]) }}"><i class="fa fa-circle-o"></i> Thêm bài viết</a></li>
          @if(Auth::user()->role == 3)
        <li {{ in_array(\Request::route()->getName(), ['articles-cate.create', 'articles-cate.index', 'articles-cate.edit']) ? "class=active" : "" }} ><a href="{{ route('articles-cate.index') }}"><i class="fa fa-circle-o"></i> Danh mục bài viết</a></li>      
        @endif    
        </ul>
     
      </li> 
        <li {{ in_array(\Request::route()->getName(), ['tag.edit', 'tag.index']) ? "class=active" : "" }}>
          <a href="{{ route('tag.index') }}">
            <i class="fa fa-pencil-square-o"></i> 
            <span>Tags</span>          
          </a>       
        </li>
      @if(Auth::user()->role > 1)
      <li {{ in_array(\Request::route()->getName(), ['newsletter.edit', 'newsletter.index']) ? "class=active" : "" }}>
        <a href="{{ route('newsletter.index') }}">
          <i class="fa fa-pencil-square-o"></i> 
          <span>Newsletter</span>         
        </a>       
      </li>

      <li {{ in_array(\Request::route()->getName(), ['contact.edit', 'contact.index']) ? "class=active" : "" }}>
        <a href="{{ route('contact.index') }}">
          <i class="fa fa-pencil-square-o"></i> 
          <span>Liên hệ / Khiếu nại</span>          
        </a>       
      </li>
      @endif    
      @if(Auth::user()->role == 3)
      <li {{ in_array(\Request::route()->getName(), ['banner.list', 'banner.edit', 'banner.create']) ? "class=active" : "" }}>
        <a href="{{ route('banner.list') }}">
          <i class="fa fa-file-image-o"></i> 
          <span>Banner</span>          
        </a>       
      </li>       
      @endif
      @if(Auth::user()->role == 3)
      <li class="treeview {{ in_array(\Request::route()->getName(), ['branch.edit', 'branch.index', 'branch.create', 'menu.index', 'account.index', 'info-seo.index', 'settings.index', 'settings.noti', 'menu.index', 'video.index', 'video.edit', 'video.create']) || (in_array(\Request::route()->getName(), ['custom-link.edit', 'custom-link.index', 'custom-link.create']) && isset($block_id) && $block_id == 2 ) || (in_array(\Request::route()->getName(), ['custom-link.edit', 'custom-link.index', 'custom-link.create']) && isset($block_id) && $block_id == 1 ) ? 'active' : '' }}">
        <a href="#">
          <i class="fa  fa-gears"></i>
          <span>Cài đặt</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @if(Auth::user()->role == 3)        
          <li {{ \Request::route()->getName() == "settings.index" ? "class=active" : "" }}><a href="{{ route('settings.index') }}"><i class="fa fa-circle-o"></i> Thông tin K KAFFEE</a></li>
          <li {{ (in_array(\Request::route()->getName(), ['branch.edit', 'branch.index', 'branch.create']))? "class=active" : "" }}>
            <a href="{{ route('branch.index') }}">
              <i class="fa fa-circle-o"></i>
              <span>Chi nhánh</span>         
            </a>       
          </li>
          <li {{ (in_array(\Request::route()->getName(), ['doitac.edit', 'doitac.index', 'doitac.create']))? "class=active" : "" }}>
            <a href="{{ route('doitac.index') }}">
              <i class="fa fa-circle-o"></i>
              <span>Đối tác</span>         
            </a>       
          </li>
          <li><a target="_blank" href="{{ route('rss') }}"><i class="fa fa-circle-o"></i> RSS</a></li>
          <li {{ (in_array(\Request::route()->getName(), ['custom-link.edit', 'custom-link.index', 'custom-link.create']) && isset($block_id) && $block_id == 1 )? "class=active" : "" }}>
            <a href="{{ route('custom-link.index', ['block_id' => 1 ]) }}">
              <i class="fa fa-circle-o"></i>
              <span>Link Footer</span>         
            </a>       
          </li>         
          <li {{ \Request::route()->getName() == "menu.index" ? "class=active" : "" }}><a href="{{ route('menu.index') }}"><i class="fa fa-circle-o"></i> Menu</a></li>          
          @endif
          <li {{ \Request::route()->getName() == "account.index" ? "class=active" : "" }}><a href="{{ route('account.index') }}"><i class="fa fa-circle-o"></i> Users</a></li>              
        </ul>
      </li>
      @endif
      @endif      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<style type="text/css">
  .skin-blue .sidebar-menu>li>.treeview-menu{
    padding-left: 15px !important;
  }
</style>