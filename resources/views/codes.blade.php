@extends('layouts.master')

@section('content')
<div id="codes">
	<div id="code" style="padding: 10px;">
	
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	
	var qrcode = new QRCode(document.getElementById("code"), {
		text: "NovaZembla",
		width: 128,
		height: 128,
		colorDark : "#000000",
		colorLight : "#ffffff",
		correctLevel : QRCode.CorrectLevel.H
	});
	
</script>
@endsection

