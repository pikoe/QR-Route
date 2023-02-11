@extends('layouts.master')

@section('title', 'Login')

@section('content')
	<div id="map" class="full-map"></div>
	<div id="fullScreen" class="button"><i class="fas fa-compress"></i><i class="fas fa-expand"></i></div>
	
	<form id="loginForm" method="POST" action="{{ route('login') }}">
		@csrf
		<div class="clearfix">
			@if($errors->any())
				<ul class="messages">
					@foreach ($errors->all() as $error)
						<li class="error">{{ $error }}</li>
					@endforeach
				</ul>
			@endif
			<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
				<div class="col-md-10 offset-md-1">
					<input type="email" class="form-control" id="email" name="email" required autofocus placeholder="E-mail">
				</div>
			</div>

			<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
				<div class="col-md-10 offset-md-1">
					<input type="password" class="form-control" id="password" name="password" required placeholder="Wachtwoord">
				</div>
			</div>
			<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
				<div class="col-md-10 offset-md-1">
					<button class="float-right btn btn-primary"><i class="fas fa-lock-open"></i> Login</button>
				</div>
			</div>
		</div>
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