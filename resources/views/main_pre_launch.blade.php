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
    <link rel="stylesheet" type="text/css" href="/assets/css/login-pre-launch.css?{{ filemtime('assets/css/login-pre-launch.scss') }}">
    <script type="text/javascript" src="/assets/lib/jquery/jquery.js?{{ filemtime('assets/lib/jquery/jquery.js') }}"></script>
    <script type="text/javascript" src="/assets/lib/vue/vue.js?{{ filemtime('assets/lib/vue/vue.js') }}"></script>
    <script type="text/javascript" src="/assets/lib/vue/vue-router.js?{{ filemtime('assets/lib/vue/vue-router.js') }}"></script>
    <script type="text/javascript" src="/spa-templates.js"></script>
</head>

<body>
<div id="app">
    <router-view></router-view>
</div>


<script type="text/javascript" src="/assets/js/login-pre-launch.js?{{ filemtime('assets/js/login-pre-launch.js') }}"></script>
<script type="text/javascript" src="/assets/js/common.js?{{ filemtime('assets/js/common.js') }}"></script>
<script type="text/javascript" src="/assets/js/main.js?{{ filemtime('assets/js/main.js') }}"></script>
</body>
</html>