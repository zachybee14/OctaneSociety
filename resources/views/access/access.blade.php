<!doctype html>
<html lang="en">
	<head>
		<title>Octane Society</title>
		<link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/access.css?{{ filemtime('assets/css/access.scss') }}">
		<link rel="stylesheet" href="/assets/lib/font-awesome/css/font-awesome.css">
	</head>
	<body>
		<div class="bg-mask"></div>

		<div class="main-wrapper">
			<div class="logo-container">
				<h1>Octane Society</h1>​
				<div class="logo" :class="v_state == null ? '' : 'darken'"></div>

				<div class="buttons-container" v-invisible="v_state">
					<button @click="showOverlay">Enter</button>
					<button @click="loginWithFacebook"><i class="fa fa-facebook-official"></i> Enter with Facebook</button>
				</div>
			</div>
		</div>

		<div v-if="v_state != null" id="access-overlay" class="overlay" @click="hideOverlay">
			<div class="inner-wrap">
				<div v-if="v_state == 'login'" class="login-wrap">
					<form>
						<input lazy v-model="login.email" type="text" required class="form-control" placeholder="E-mail">
						<input lazy v-model="login.password" type="password" required class="form-control" placeholder="Password">
						<button @click.prevent="loginWithCredentials">Login</button>
					</form>

					<div class="forgot-password">
						<a href="#">Forgot password</a>
					</div>

					<div class="divider">
						<div></div>
						<div>or</div>
						<div></div>
					</div>

					<button class="join-btn ghost-btn" @click.prevent="showJoinForm">Join the society</button>
				</div>

				<div v-if="v_state == 'collect-personal-info'" class="info-wrap">
					<form>
						<input lazy v-model="personal.first_name" v-el:first-name-input type="text" required class="form-control" placeholder="first name">
						<input lazy v-model="personal.last_name" type="text" required class="form-control" placeholder="last name">
						<input lazy v-model="personal.email" type="text" required class="form-control" placeholder="e-mail">
						<input lazy v-model="personal.password" type="password" required class="form-control password" placeholder="Password">
						<button @click.prevent="proceedFromPersonalInfo">Continue &gt;</button>
					</form>
				</div>

				<div v-if="v_state == 'collect-car-info'" class="car-wrap">
					<h2>Select your car</h2>
					<div>
						<select v-model="car.make" v-disabled="car.v_makes == null" @change="loadCarModels">
							<option v-if="car.v_makes == null" disabled selected :value="null">Loading makes...</option>
							<template v-else>
								<option disabled selected :value="null">Select a make</option>
								<option v-for="make in car.v_makes">@{{ make }}</option>
							</template>
						</select>
						<span class="caret"></span>
					</div>
					<div v-if="car.make">
						<select v-model="car.model" v-disabled="car.v_models == null" @change="loadCarYears">
							<option v-if="car.v_models == null" disabled selected :value="null">Loading models...</option>
							<template v-else>
								<option disabled selected :value="null">Select a model</option>
								<option v-for="model in car.v_models" v-bind:value="model">@{{ model.name }}</option>
							</template>
						</select>
						<span class="caret"></span>
					</div>
					<div v-if="car.model">
						<select v-model="car.year" @change="loadCarStyles">
							<option disabled selected :value="null">Select a year</option>
							<option v-for="year in car.v_years">@{{ year }}</option>
						</select>
						<span class="caret"></span>
					</div>
					<div v-if="car.year">
						<select v-model="car.style" v-disabled="car.v_styles == null">
							<option v-if="car.v_styles == null" disabled selected :value="null">Loading styles...</option>
							<template v-else>
								<option disabled selected :value="null">Select a style</option>
								<option v-for="style in car.v_styles" v-bind:value="style">@{{ style.name }}</option>
							</template>
						</select>
						<span class="caret"></span>
					</div>
					<button v-if="car.style" @click.prevent="proceedFromCarInfo">Join</button>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="/assets/lib/jquery/jquery.js"></script>
		<script type="text/javascript" src="/assets/lib/bootstrap/js/bootstrap.js"></script> 
		<script type="text/javascript" src="/assets/lib/vue/vue.js"></script>
		<script type="text/javascript" src="/assets/js/common.js"></script>
		<script type="text/javascript" src="/assets/js/access.js"></script>

		<div class="loading">
			<i class="fa fa-spinner fa-pulse fa-5x"></i>
		</div>
	</body>
</html>
</html>	