@extends('layouts.web')

@section('title', 'Wachtwoord vergeten')

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
	<form method="POST" action="{{ url('/password/email') }}">
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
			<input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
		</div>

		<div class="clearfix">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<button class="button" title="Verzenden" type="submit">Stuur wachtwoord reset link</button>
			
			<a href="{{ url('/login') }}">Terug naar login</a>
		</div>
	</form>
</div>
@endsection
