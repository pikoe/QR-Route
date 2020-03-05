<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>QR Route</title>

	<style>
		#qr-scanner {
			width: 100%;
			position: relative;
			overflow: hidden;
			border: 2px solid #000;
			box-sizing: border-box;
		}
		#qr-scanner::after {
			content: "";
			display: block;
			padding-bottom: 100%;
		}
		#qr-scanner video {
			position: absolute;
		}
		
		#map {
			height: 400px;  /* The height is 400 pixels */
			width: 100%;  /* The width is the width of the web page */
		}
	</style>
</head>
<body>
	<div id="qr-scanner">
		<video muted playsinline autoplay id="qr-video"></video>
	</div>
	<div id="cam-qr-result">None</div>
	
	<div id="map"></div>
	

	<script type="module">
		import QrScanner from "./js/qr-scanner.js";
		QrScanner.WORKER_PATH = './js/qr-scanner-worker.min.js';

		const video = document.getElementById('qr-video');
		const camQrResult = document.getElementById('cam-qr-result');

		function setResult(label, result) {
			label.textContent = result;

			scanner.stop();
		}

		const scanner = new QrScanner(video, result => setResult(camQrResult, result));
		scanner.start();
	</script>
    <script type="text/javascript">
	// Initialize and add the map
	function initMap() {
		// The location of Uluru
		var position = {
			lat: 52.199795,
			lng: 6.214973
		};
		// The map, centered at Uluru
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 12,
			center: position,
			streetViewControl: false,
			disableDefaultUI: true,
			mapTypeId: 'hybrid',
			tilt: 0,
			styles: [
				{
					featureType: 'poi',
					elementType: 'labels',
					stylers: [
						{ visibility: 'off' }
					]
			    }
			]
		});
		// The marker, positioned at Uluru
		var marker = new google.maps.Marker({
			position: position,
			map: map
		});
	}
    </script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-N0QabvmkBev7w-YovJw2-C96NsNh5VQ&callback=initMap"></script>
</body>
</html>
