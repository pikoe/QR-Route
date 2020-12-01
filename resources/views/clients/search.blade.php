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
	
	var foundCodes = [];
	var foundLine = L.polyline([], {
		color: '#ee66cc',
		weight: 2,
		dashArray: '3 4'
	}).addTo(map);
	var searchCode = false;
	var searchMarker = L.marker([52.20342, 6.25114], {
		icon: markerSearchIcon
	});
	
	var searchLine = L.polyline([], {
		color: '#ff6600',
		weight: 2,
		dashArray: '3 4'
	}).addTo(map);
	
	
	var positionHistory = [];
	var positionHistoryLine = L.polyline(positionHistory, {
		color: '#cc0000',
		weight: 2
	}).addTo(map);
	
	@if($client && $client->route)
		@php
			$searchPoint = $client->route->startPoint;
		@endphp
		
		@foreach($client->clientPoints as $clientPiont)
			foundCodes.push([{{ $clientPiont->point->lat }}, {{ $clientPiont->point->lng }}]);
			L.marker([{{ $clientPiont->point->lat }}, {{ $clientPiont->point->lng }}], {
				icon: markerIcon
			}).addTo(map);
			
			@php
				$searchPoint = $clientPiont->point->nextPoint;
			@endphp
		@endforeach
		foundLine.setLatLngs(foundCodes);
		
		@if($searchPoint)
			searchCode = [{{ $searchPoint->lat }}, {{ $searchPoint->lng }}];
			// weergeven als er wat weer te geven valt
			searchMarker.setLatLng(searchCode).addTo(map);
			searchLine.setLatLngs([searchCode]);
		@endif
		
		@foreach($client->clientLocations as $clientLocation)
			positionHistory.push([{{ $clientLocation->lat }}, {{ $clientLocation->lng }}]);
		@endforeach
	@endif
	
	var positionUpdateAt = 0,
		positionPostAt = 0,
	//	positionUpdateInterval = 2 * 60 * 1000,// 2 min, sturen als je verplaatst bent
	//	positionPostInterval = 5 * 60 * 1000,// 5 min, sturen ook als je niet verplaatst bent
		positionUpdateInterval = 2 * 1000,// 2 sec, sturen als je verplaatst bent
		positionPostInterval = 5 * 1000,// 5 sec, sturen ook als je niet verplaatst bent
		positionPostTimeout = null;
	
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
				if(searchCode) {
					searchLine.setLatLngs([searchCode, [position.coords.latitude, position.coords.longitude]]);
				}
				positionUpdateAt = new Date().getTime();
			}
			
			if(positionUpdateAt < positionPostAt + positionUpdateInterval) {
				postPosition();
			}
		}
		navigator.geolocation.watchPosition(currentPosition);
	}
	
	function postPosition() {
		clearTimeout(positionPostTimeout);
		positionPostAt = new Date().getTime();
		$.ajax({
			url: '{{ route('points.update') }}',
			method: 'POST',
			data: positionHistory.length ? {
				lat: positionHistory[positionHistory.length - 1][0],
				lng: positionHistory[positionHistory.length - 1][1]
			} : {},
			context: document.body
		}).done(function(data) {
			console.log(data);
		});
		positionPostTimeout = setTimeout(postPosition, positionPostInterval);
	}
	postPosition();
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
				
				/*
				L.marker([52.20142, 6.20114], {
					icon: markerIcon
				}).addTo(map);
				*/
				if(data.hasOwnProperty('search')) {
					searchCode = [data.search.lat, data.search.lng];
				} else {
					searchCode = false;
				}
				
				if(searchCode) {
					// weergeven als er wat weer te geven valt
					searchMarker.setLatLng(searchCode).addTo(map);
					if(positionHistory.length) {
						searchLine.setLatLngs([searchCode, positionHistory[positionHistory.length - 1]]);
						map.panInsideBounds(L.latLngBounds(searchCode, positionHistory[positionHistory.length - 1]), {
							paddingTopLeft: [25, 55],
							paddingBottomRight: [25, 5]
						});
						map.fitBounds(L.latLngBounds(searchCode, positionHistory[positionHistory.length - 1]));
					} else {
						map.panInside(searchCode, {
							paddingTopLeft: [25, 55],
							paddingBottomRight: [25, 5]
						});
					}
				}
				
				if(data.hasOwnProperty('found')) {
					foundCode = [data.found.lat, data.found.lng];
					
					foundCodes.push(foundCode);
					foundLine.setLatLngs(foundCodes);
					
					L.marker(foundCode, {
						icon: markerIcon
					}).addTo(map);
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
	
	$('#login').on('shortclick', function() {
		$('#qrScanner').addClass('active');
		scanner.start();
	});
	map.invalidateSize();
</script>
@endsection