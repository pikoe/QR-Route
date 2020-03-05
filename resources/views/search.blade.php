@extends('layouts.master')

@section('content')
	<div id="map" class="full-map"></div>
	<div id="fullScreen" class="button"><i class="fas fa-compress"></i><i class="fas fa-expand"></i></div>

	<div id="qrScanner" class="overlay">
		<video muted playsinline autoplay></video>
		<div class="button close"><i class="fa fa-times"></i></div>
	</div>
	<div id="status" class="overlay">
		<div id="load" class="text-center"><i class="fas fa-spinner fa-pulse"></i></div>
		<div class="button close"><i class="fa fa-times"></i></div>
		<div id="message"></div>
	</div>
@endsection

@section('scripts')
<script type="text/javascript">
	var map = L.map('map').setView([52.20142, 6.20114], 13);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(map);
	
	var markerIcon = L.icon({
		iconUrl: 'img/marker.png',
		iconSize: [30, 43],
		iconAnchor: [15, 43],
		
		shadowUrl: 'img/marker-shadow.png',
		shadowSize: [57, 38], // size of the shadow
		shadowAnchor: [15, 34],  // the same for the shadow
		
		popupAnchor: [0, -43] // point from which the popup should open relative to the iconAnchor
	});
	L.marker([52.20142, 6.20114], {
		icon: markerIcon
	}).addTo(map);
	
	var markerStepsIcon = L.icon({
		iconUrl: 'img/marker-steps.png',
		iconSize: [42, 45],
		iconAnchor: [21, 45],
		
		shadowUrl: 'img/marker-shadow.png',
		shadowSize: [57, 38], // size of the shadow
		shadowAnchor: [15, 34],  // the same for the shadow
		
		popupAnchor: [0, -43] // point from which the popup should open relative to the iconAnchor
	});
	var markerSearchIcon = L.icon({
		iconUrl: 'img/marker-search.png',
		iconSize: [42, 45],
		iconAnchor: [21, 45],
		
		shadowUrl: 'img/marker-shadow.png',
		shadowSize: [57, 38], // size of the shadow
		shadowAnchor: [15, 34],  // the same for the shadow
		
		popupAnchor: [0, -43] // point from which the popup should open relative to the iconAnchor
	});
	
	var nextCode = [52.20342, 6.25114];
	var searchMarker = L.marker(nextCode, {
		icon: markerSearchIcon
	}).addTo(map);
	
	var searchLine = L.polyline([nextCode], {
		color: '#ff6600',
		weight: 2,
		dashArray: '3 4'
	}).addTo(map);
	
	var positionHistory = [];
	var positionHistoryLine = L.polyline(positionHistory, {
		color: '#cc0000',
		weight: 2
	}).addTo(map);
	
	if (navigator.geolocation) {
		var positionAccuracy = L.circle([52.20142, 6.20114], {
			radius: 1,
			opacity: 0.8,
			color: '#dfe6fa',
			fill: true,
			fillColor: '#dfe6fa',
			fillOpacity: 0.3
		}).addTo(map);
		var positionMarker = L.marker([52.20142, 6.20114], {
			icon: markerStepsIcon
		}).addTo(map);

		function currentPosition(position) {
			positionAccuracy.setLatLng([position.coords.latitude, position.coords.longitude]).setRadius(position.coords.accuracy);
			positionMarker.setLatLng([position.coords.latitude, position.coords.longitude]);
			if(!positionHistory.length || positionHistory[positionHistory.length - 1][0] !== position.coords.latitude || positionHistory[positionHistory.length - 1][1] !== position.coords.longitude) {
				positionHistory.push([position.coords.latitude, position.coords.longitude]);
				positionHistoryLine.setLatLngs(positionHistory);
				
				searchLine.setLatLngs([nextCode, [position.coords.latitude, position.coords.longitude]]);
			}
		}
		navigator.geolocation.watchPosition(currentPosition);
	}
</script>
<script type="module">
	import QrScanner from "./js/qr-scanner.js";
	QrScanner.WORKER_PATH = './js/qr-scanner-worker.min.js';

	const video = $('#qrScanner video')[0];
	const scanner = new QrScanner(video, result => setResult(result));

	function setResult(result) {
		scanner.stop();
		$('#qrScanner').removeClass('active');
		$('#status').addClass('load');
		
		$.ajax({
			url: '{{ route('points.check') }}',
			method: 'POST',
			data: {
				code: result
			},
			context: document.body
		}).done(function(data) {
			$('#status').removeClass('load');
			if(data.error) {
				$('#status #message').html(data.error);
				$('#status').addClass('active');
			} else {
				nextCode = [data.lat, data.lng];
				searchMarker.setLatLng(nextCode);
				if(positionHistory.length) {
					searchLine.setLatLngs([nextCode, positionHistory[positionHistory.length - 1]]);
					map.panInsideBounds(L.latLngBounds(nextCode, positionHistory[positionHistory.length - 1]), {
						paddingTopLeft: [25, 55],
						paddingBottomRight: [25, 5]
					});
				} else {
					map.panInside(nextCode, {
						paddingTopLeft: [25, 55],
						paddingBottomRight: [25, 5]
					});
				}
			}
		});
	}
	
	searchMarker.on('click', function() {
	    // setResult('sdf');
		
		$('#qrScanner').addClass('active');
		scanner.start();
		map.panInside(this.getLatLng(), {
			paddingTopLeft: [25, window.innerWidth + 65],
			paddingBottomRight: [25, 5]
		});
	});
	
	$('#qrScanner .button.close').click(function() {
		scanner.stop();
		$('#qrScanner').removeClass('active');
	});
	
	$('#status .button.close').click(function() {
		$('#status').removeClass('active');
	});
	map.invalidateSize();
</script>
@endsection