@extends('layouts.master')

@section('content')
<div id="codes">
	<div>bemanning</div>
	<div>Witte Swaen</div>
	<div>ijsbeer</div>
	<div>Nova</div>
	<div>Zembla</div>
	<div>Nova Zembla</div>
	<div>Schip</div>
	<div>IJsco</div>
	<div>scheepsbeschuit</div>
	<div>stuurman</div>
	<div>Willem Barentsz</div>
	<div>ontdekkingsreis</div>
	<div>Poolcirkel</div>
	<div>IJskap</div>
	<div>Toendra</div>
	<div>Perestrojka</div>
	<div>Glasnost</div>
	<div>Scout</div>
	<div>Iglo</div>
	<div>Mook</div>
	<div>HIT</div>
	<div>Route</div>
	<div>Kompas</div>
	<div>Uitkijk</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$('#codes > div').each(function() {
		new QRCode(this, {
			text: this.innerHTML + Math.floor(Math.random() * 100),
			width: 128,
			height: 128,
			colorDark : "#000000",
			colorLight : "#ffffff",
			correctLevel : QRCode.CorrectLevel.H
		});
	});
	
	
</script>
@endsection

