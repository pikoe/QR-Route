@extends('layouts.master')

@section('title', 'Login')

@section('content')
	<div id="map" class="full-map"></div>
	<div id="fullScreen" class="button"><i class="fas fa-compress"></i><i class="fas fa-expand"></i></div>
	
	<form id="loginForm" method="POST" action="{{ route('login') }}">
		@csrf
		
		<h1>Login</h1>
		
		@if($errors->any())
			<ul class="messages">
				@foreach ($errors->all() as $error)
					<li class="error">{{ $error }}</li>
				@endforeach
			</ul>
		@endif
		<div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
			<label for="email" class="d-none d-sm-block col-sm-2 col-form-label">E-mail</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="email" name="email" required autofocus placeholder="E-mail">
			</div>
		</div>
		
		<div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
			<label for="password" class="d-none d-sm-block col-sm-2 col-form-label">Wachtwoord</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="password" name="password" required placeholder="Wachtwoord">
			</div>
		</div>
		
		<button class="btn btn-primary"><i class="fas fa-lock-open"></i> Login</button>
	</form>
@endsection

@section('scripts')
<script type="text/javascript">
	var map = L.map('map').setView([52.20142, 6.20114], 13);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(map);
</script>
@endsection