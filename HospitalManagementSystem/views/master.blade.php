<!DOCTYPE html>
<html>
<head>	
	<title>@yield('title')</title>

	{{ HTML::style('assets/css/materialize.css',array('media' => 'screen,projection')) }}
	{{ HTML::style('assets/css/overrides.css') }}
	{{ HTML::style('assets/css/ui-common.css') }}
	{{ HTML::style('assets/css/sweet-alert.css') }}

	@yield('styles')
	<script>
		var baseURL="{{ URL::to('/') }}";
	</script>
	{{ HTML::script('assets/js/jquery.js') }}
	{{ HTML::script('assets/js/materialize.js') }}
	{{ HTML::script('assets/js/sweet-alert.min.js') }}
	{{ HTML::script('assets/js/jquery.timeago.js') }}
	{{ HTML::script('assets/js/functionalities.js') }}
	@yield('scriptIncludes')	
</head>
<body>
@yield('header')
@yield('body')
@yield('miscellaneous')
@yield('footer')
</body>
@yield('script')
<script>
$(document).ready(function(){
    $(".button-collapse").sideNav();
	$(".dropdown-button").dropdown({
		hover:false
	});        
});
</script>
</html>