<ul id="browse-dropdown" class="dropdown-content">
  <li><a href="{{ URL::to('all') }}">All</a></li>
  <li><a href="{{ URL::to('tags') }}">Tags</a></li>
  <!--<li><a href="{{ URL::to('users') }}">Users</a></li>-->
</ul>
<nav>
<div class="nav-wrapper">
  <a href="{{ URL::to('/') }}" class="brand-logo">NITR Web Gallery</a>
  <ul class="right hide-on-med-and-down">
  	@if(isset($_SESSION['username']))
		<li><a href="{{ URL::to('dashboard') }}">Dashboard</a></li>
		<li><a href="{{ URL::to('logout') }}">Logout</a></li>
	@elseif(isset($_SESSION['admin']))
		@if(isset($admin_editable)&&$admin_editable)
			<li><a href="{{ URL::to('edit/'.Request::path()) }}">Edit Page</a></li>	
		@endif
		<li><a href="{{ URL::to('admin') }}">Admin Panel</a></li>
		<li><a href="{{ URL::to('logout') }}">Logout</a></li>
	@else		
		<li><a href="{{ URL::to('login') }}">Login</a></li>
	@endif
  	<li><a class="dropdown-button" href="#" data-activates="browse-dropdown">Browse<i class="mdi-navigation-arrow-drop-down right"></i></a></li>	
  </ul>
  <ul id="nav-mobile" class="side-nav">
  	@if(isset($_SESSION['username']))
		<li><a href="{{ URL::to('dashboard') }}">Dashboard</a></li>
		<li><a href="{{ URL::to('logout') }}">Logout</a></li>
	@elseif(isset($_SESSION['admin']))
		@if(isset($admin_editable)&&$admin_editable)
			<li><a href="{{ URL::to('edit/'.Request::path()) }}">Edit Page</a></li>	
		@endif
		<li><a href="{{ URL::to('admin') }}">Admin Panel</a></li>
		<li><a href="{{ URL::to('logout') }}">Logout</a></li>
	@else		
		<li><a href="{{ URL::to('login') }}">Login</a></li>
	@endif
  	<li><a class="dropdown-button" href="#" data-activates="browse-dropdown">Browse<i class="mdi-navigation-arrow-drop-down right"></i></a></li>	
  </ul>
  <a class="button-collapse" href="#" data-activates="nav-mobile"><i class="mdi-navigation-menu"></i></a>
</div>
</nav>
