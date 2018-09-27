<ul id="explore-dropdown" class="dropdown-content">
  <li><a href="{{ URK }}">one</a></li>
  <li><a href="#!">two</a></li>
  <li class="divider"></li>
  <li><a href="#!">three</a></li>
</ul>
<nav>
<div class="nav-wrapper">
  <a href="#" class="brand-logo">NITR Web Gallery</a>
  <ul id="nav-mobile" class="right side-nav">
  	@if(isset($_SESSION['username']))		
		<li><a href="all">Browse</a></li>
		<li><a href="dashboard">Dashboard</a></li>
		<li><a href="logout">Logout</a></li>
	@elseif(isset($_SESSION['admin']))
		<li><a href="browse">Browse</a></li>
		<li><a href="admin">Admin Panel</a></li>
		<li><a href="logout">Logout</a></li>
	@else		
		<li><a href="browse">Browse</a></li>
		<li><a href="login">Login</a></li>
	@endif
   </ul>
   <a class="button-collapse" href="#" data-activates="nav-mobile"><i class="mdi-navigation-menu"></i></a>
</div>
</nav>
