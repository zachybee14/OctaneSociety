<!doctype html>
<html lang="en">
<head>
	<title>Octane Society</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
	<link rel="stylesheet" href="/assets/lib/font-awesome/css/font-awesome.css">
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrQSHUNIHEz2FbfCaAXb718DHfWJn5PtA"></script>
	<link rel="stylesheet" type="text/css" href="/assets/css/reset.css?{{ filemtime('assets/css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/main.css?{{ filemtime('assets/css/main.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/login.css?{{ filemtime('assets/css/login.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/home.css?{{ filemtime('assets/css/home.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/events.css?{{ filemtime('assets/css/events.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/events-view.css?{{ filemtime('assets/css/events-view.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/events-edit.css?{{ filemtime('assets/css/events-edit.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/articles.css?{{ filemtime('assets/css/articles.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/articles-view.css?{{ filemtime('assets/css/articles-view.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/articles-edit.css?{{ filemtime('assets/css/articles-edit.scss') }}">
	<link rel="stylesheet" type="text/css" href="/assets/css/vehicles.css?{{ filemtime('assets/css/vehicles.scss') }}">
	<script type="text/javascript" src="/assets/lib/jquery/jquery.js?{{ filemtime('assets/lib/jquery/jquery.js') }}"></script>
	<script type="text/javascript" src="/assets/lib/vue/vue.js?{{ filemtime('assets/lib/vue/vue.js') }}"></script>
	<script type="text/javascript" src="/assets/lib/vue/vue-router.js?{{ filemtime('assets/lib/vue/vue-router.js') }}"></script>
	<script type="text/javascript" src="/spa-templates.js"></script>
</head>

	<body>
		<div id="app">
			<div v-if="this.$route.path != '/'" class="site-header">

				<div class="home">
					<img>
				</div>

				<nav class="links">
					<router-link :to="'/home'" href="#" active-class>Home</router-link>
					<router-link :to="'/articles'" href="#" active-class>Articles</router-link>
					<router-link :to="'/events'" href="#" active-class>Events</router-link>
					<router-link :to="'/vehicles'" href="#" active-class>Vehicles</router-link>
					<router-link :to="'/blogs'" href="#" active-class>Blogs</router-link>
				</nav>

				<div class="search">
					<i class="fa fa-search fa-lg"></i>
				</div>

				<div @click="$router.push('profile')" class="profile">
					<div class="picture"></div>
					<div>Zach Beland</div>
				</div>

				<!--<div class="input-group search-box">
					<span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
					<input type="text" class="form-control" id="search-email" placeholder=" cars, friends, articles..." tabindex="1">
				</div>-->
			</div>

			<router-view></router-view>
		</div>


		<script type="text/javascript" src="/assets/js/login.js?{{ filemtime('assets/js/login.js') }}"></script>
		<script type="text/javascript" src="/assets/js/common.js?{{ filemtime('assets/js/common.js') }}"></script>
		<script type="text/javascript" src="/assets/js/vehicle-loader.js?{{ filemtime('assets/js/vehicle-loader.js') }}"></script>
		<script type="text/javascript" src="/assets/js/home.js?{{ filemtime('assets/js/home.js') }}"></script>
		<script type="text/javascript" src="/assets/js/events.js?{{ filemtime('assets/js/events.js') }}"></script>
		<script type="text/javascript" src="/assets/js/events-edit.js?{{ filemtime('assets/js/events-edit.js') }}"></script>
		<script type="text/javascript" src="/assets/js/events-view.js?{{ filemtime('assets/js/events-view.js') }}"></script>
		<script type="text/javascript" src="/assets/js/articles.js?{{ filemtime('assets/js/articles.js') }}"></script>
		<script type="text/javascript" src="/assets/js/articles-view.js?{{ filemtime('assets/js/articles-view.js') }}"></script>
		<script type="text/javascript" src="/assets/js/articles-edit.js?{{ filemtime('assets/js/articles-edit.js') }}"></script>
		<script type="text/javascript" src="/assets/js/vehicles.js?{{ filemtime('assets/js/vehicles.js') }}"></script>
		<script type="text/javascript" src="/assets/js/vehicles-view.js?{{ filemtime('assets/js/vehicles-view.js') }}"></script>
		<script type="text/javascript" src="/assets/js/blogs.js?{{ filemtime('assets/js/blogs.js') }}"></script>
		<script type="text/javascript" src="/assets/js/blogs-view.js?{{ filemtime('assets/js/blogs-view.js') }}"></script>
		<script type="text/javascript" src="/assets/js/shop.js?{{ filemtime('assets/js/shop.js') }}"></script>
		<script type="text/javascript" src="/assets/js/profile.js?{{ filemtime('assets/js/profile.js') }}"></script>
		<script type="text/javascript" src="/assets/js/main.js?{{ filemtime('assets/js/main.js') }}"></script>
	</body>
</html>