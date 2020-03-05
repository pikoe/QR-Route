@extends('layouts.web')

@section('title', 'Wachtwoord herstellen')

@section('content')
<ul class="breadcrumbs clearfix">
	<li class="home"><a href="/">Home</a></li>
	<li><a href="login">Login</a></li>
</ul>
@if(session('status'))
	<div class="page-block">
		{{ session('status') }}
	</div>
@endif
<div class="page-block">
	<form method="POST" action="{{ url('/password/reset') }}">
		<h1>Wachtwoord vergeten?</h1>
		@if($errors->any())
			<ul class="messages">
				@foreach ($errors->all() as $error)
					<li class="error">{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		<div class="clearfix{{ $errors->has('email') ? ' has-error' : '' }}">
			<label for="email">E-mail</label>
			<input id="email" type="email" name="email" value="{{ $email or old('email') }}" required autofocus>
		</div>

		<div class="clearfix{{ $errors->has('password') ? ' has-error' : '' }}">
			<label for="password">Wachtwoord</label>
			<input id="password" type="password" name="password" required>
		</div>
		<div class="clearfix{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
			<label for="password_password">Bevestig wachtwoord</label>
			<input id="password_password" type="password" name="password_confirmation" required>
		</div>

		<div class="clearfix">
			<input type="hidden" name="token" value="{{ $token }}">
			
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<button class="button" title="Verzenden" type="submit">Wachtwoord resetten</button>
			
			<a href="{{ url('/login') }}">Terug naar login</a>
		</div>
	</form>
</div>
@endsection
