@extends('layouts.master')

@section('content')
	<div id="map" class="full-map"></div>
	<div id="fullScreen" class="button"><i class="fas fa-compress"></i><i class="fas fa-expand"></i></div>
	<div id="centerMap" class="button"><i class="far fa-compass"></i>></div>
	<div id="addRoute" class="button"><i class="fas fa-route"></i></div>
	<div id="addClient" class="button"><i class="fas fa-user-plus"></i></div>

	<div id="clients">
		@foreach($clients as $client)
		<i class="fas fa-user" data-id="{{ $client->id }}" style="color: {{ $client->color }};"></i>
		@endforeach
	</div>
	
	<div id="qrScanner" class="overlay">
		<video muted playsinline autoplay></video>
		<div class="button close"><i class="fa fa-times"></i></div>
	</div>
	<div id="status" class="overlay">
		<div id="load" class="text-center"><i class="fas fa-spinner fa-pulse"></i></div>
		<div class="button close"><i class="fa fa-times"></i></div>
		<div id="message"></div>
	</div>
	
	<form id="newRoute" class="overlay form-overlay" method="post">
		@csrf
		<div class="button close"><i class="fa fa-times"></i></div>
		<h2>Nieuwe route maken</h2>
		<div class="form-group row">
			<label for="name" class="d-none d-sm-block col-sm-2 col-form-label">Naam</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="name" name="name" required placeholder="Naam">
			</div>
		</div>
		<div class="form-group row">
			<label for="color" class="d-none d-sm-block col-sm-2 col-form-label">Kleur</label>
			<div class="col-sm-10">
				<div class="input-group">
					<input type="text" class="form-control color" id="routeColor" name="color" required placeholder="Kleur" pattern="^#[A-Fa-f0-9]{6}$" readonly>
					<label for="routeColor" class="input-group-append">
					    <span class="input-group-text" id="routeColorPreview"><i class="fas fa-palette"></i></span>
					</label>
				</div>
				<input type="hidden" id="routeSecondColor" name="second_color">
			</div>
		</div>
		
		<input type="hidden" id="new_lat" name="lat">
		<input type="hidden" id="new_lng" name="lng">

		<button class="btn btn-primary" name="add" value="1"><i class="fas fa-save"></i> Opslaan</button>
	</form>
	
	<form id="newClient" class="overlay form-overlay" method="post" action="{{ route('admin.clients') }}">
		@csrf
		<div class="button close"><i class="fa fa-times"></i></div>
		<h2>Nieuwe deelnemer maken</h2>
		<div class="form-group row">
			<label for="clientName" class="d-none d-sm-block col-sm-2 col-form-label">Naam</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="clientName" name="name" required placeholder="Naam">
			</div>
		</div>
		<div class="form-group row">
			<label for="clientColor" class="d-none d-sm-block col-sm-2 col-form-label">Kleur</label>
			<div class="col-sm-10">
				<div class="input-group">
					<input type="text" class="form-control color" id="clientColor" name="color" required placeholder="Kleur" pattern="^#[A-Fa-f0-9]{6}$" readonly>
					<label for="clientColor" class="input-group-append">
					    <span class="input-group-text" id="clientColorPreview"><i class="fas fa-palette"></i></span>
					</label>
				</div>
				<input type="hidden" id="clientSecondColor" name="second_color">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="clientRoute" class="d-none d-sm-block col-sm-2 col-form-label">Route</label>
			<div class="col-sm-10">
				<select class="form-control" id="clientRoute" name="route_id">
					<option></option>
					@foreach($routes as $route)
					<option value="{{ $route->id }}">{{ $route->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		
		<button class="btn btn-primary" name="add" value="1"><i class="fas fa-save"></i> Opslaan</button>
	</form>
	
	<form id="client" class="overlay form-overlay" method="post" action="{{ route('admin.clients') }}">
		@csrf
		<div class="button close"><i class="fa fa-times"></i></div>
		<h2>Deelnemer</h2>
		
		<input type="hidden" id="editClientId" name="id">
		<a href="#" class="qr"></a>
		
		<div class="form-group row">
			<label for="editClientName" class="d-none d-sm-block col-sm-2 col-form-label">Naam</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="editClientName" name="name" required placeholder="Naam">
			</div>
		</div>
		<div class="form-group row">
			<label for="editClientColor" class="d-none d-sm-block col-sm-2 col-form-label">Kleur</label>
			<div class="col-sm-10">
				<div class="input-group">
					<input type="text" class="form-control color" id="editClientColor" name="color" required placeholder="Kleur" pattern="^#[A-Fa-f0-9]{6}$" readonly>
					<label for="editClientColor" class="input-group-append">
					    <span class="input-group-text" id="editClientColorPreview"><i class="fas fa-palette"></i></span>
					</label>
				</div>
				<input type="hidden" id="editClientSecondColor" name="second_color">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="editClientRoute" class="d-none d-sm-block col-sm-2 col-form-label">Route</label>
			<div class="col-sm-10">
				<select class="form-control" id="editClientRoute" name="route_id">
					<option></option>
					@foreach($routes as $route)
					<option value="{{ $route->id }}">{{ $route->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="d-none d-sm-block col-sm-2 col-form-label">Status</label>
			<div class="col-sm-10" id="clientTime"></div>
		</div>

		<button class="btn btn-primary" name="edit" value="1"><i class="fas fa-save"></i> Opslaan</button>
	</form>
		
	<form id="edit" class="overlay form-overlay" method="post">
		@csrf
		<div class="button close"><i class="fa fa-times"></i></div>
		<h2>Route bewerken</h2>
		<input type="hidden" id="route_id" name="route_id">
		<input type="hidden" id="point_id" name="point_id">
		
		<input type="hidden" id="org_lat">
		<input type="hidden" id="org_lng">
		<input type="hidden" id="edit_lat" name="lat">
		<input type="hidden" id="edit_lng" name="lng">
		
		<div class="form-group row">
			<label for="qr_code" class="d-none d-sm-block col-sm-2 col-form-label">QR code</label>
			<div class="col-sm-10">
				<div class="input-group">
					<label for="qr_code" class="input-group-prepend">
					    <span class="input-group-text" id="qr_code_label"><i class="fas fa-qrcode"></i></span>
					</label>
					<input type="text" class="form-control" id="qr_code" name="code" placeholder="QR code">
					<div class="input-group-append">
						<span class="btn btn-outline-secondary" id="qr_code_clear"><i class="fa fa-times"></i></span>
					</div>
				</div>
			</div>
		</div>
		
		<button class="btn btn-primary" name="edit" value="1"><i class="fas fa-save"></i> Opslaan</button>
		
		
		<span class="btn btn-primary" id="insertPrev"><i class="far fa-hand-point-left"></i> Punt hiervoor toevoegen</span>
		<span class="btn btn-primary" id="insertNext"><i class="far fa-hand-point-right"></i> Punt hierna toevoegen</span>
		
		<button class="btn btn-primary" name="delete" value="1"><i class="far fa-trash-alt"></i> Verwijderen</button>
	</form>
	
	<form id="insert" class="overlay form-overlay" method="post">
		@csrf
		<div class="button close"><i class="fa fa-times"></i></div>
		<h2>Route bewerken</h2>
		<input type="hidden" id="insert_route_id" name="route_id">
		<input type="hidden" id="insert_point_id" name="point_id">
		<input type="hidden" id="position" name="position">
		
		<input type="hidden" id="add_lat" name="lat">
		<input type="hidden" id="add_lng" name="lng">
		
		<button class="btn btn-primary" name="insert" value="1"><i class="fas fa-save"></i> Punt toevoegen</button>
	</form>
@endsection

@section('scripts')
<script type="text/javascript">
	var centerMode = 'routes';
	$('#centerMap').click(function() {
		if(centerMode == 'routes') {
			if(positionHistory.length) {
				map.panTo(positionHistory[positionHistory.length - 1]);
			}
			centerMode = 'current';
		} else if(centerMode == 'current') {
			var latLngsAndCurrent = latLngs;
			if(positionHistory.length) {
				latLngsAndCurrent.push(positionHistory[positionHistory.length - 1]);
			}
			map.fitBounds(L.latLngBounds(latLngs));
			centerMode = 'routesAndCurrent';
		} else if(centerMode == 'routesAndCurrent') {
			map.fitBounds(L.latLngBounds(latLngs));
			centerMode = 'routes';
		}
		
	});
	$('#addRoute').click(function() {
		$('form#newRoute').addClass('active');
		newMarker.setIcon(L.svgIcon({
			color: '#ff0000',
			borderColor: '#bf0000',
			html: '<i class="fas fa-asterisk"></i>'
		}));
		newMarker.addTo(map).setLatLng(map.getCenter()).bounce(1);
		map.panBy([0, $('form#newRoute').height() / -2]);
		
		$('#new_lat').val(map.getCenter().lat);
		$('#new_lng').val(map.getCenter().lng);
	});
	$('form#newRoute .close').click(function() {
		$('form#newRoute').removeClass('active');
		newMarker.remove();
	});
	
	
	$('#addClient').click(function() {
		$('form#newClient').addClass('active');
	});
	$('form#newClient .close').click(function() {
		$('form#newClient').removeClass('active');
	});
	
	$('form#edit .close').click(function() {
		$('form#edit').removeClass('active');
		editMarker.setLatLng([$('#org_lat').val(), $('#org_lng').val()]);
		editMarker.dragging.disable();
		editMarker.stopBouncing();
		setRouteLine(editMarker.options.route);
		editMarker = null;
		editRoute = null;
	});
	$('#insertPrev').click(function() {
		$('form#edit').removeClass('active');
		editMarker.setLatLng([$('#org_lat').val(), $('#org_lng').val()]);
		editMarker.dragging.disable();
		editMarker.stopBouncing();
		
		newMarker.setIcon(L.svgIcon({
			color: editRoute.color,
			borderColor: editRoute.second_color,
			html: '<i class="fas fa-asterisk"></i>'
		}));
		newMarker.addTo(map).setLatLng(map.getCenter()).bounce(1);
		
		editRoute.markers.splice(editRoute.markers.indexOf(editMarker), 0, newMarker);
		setRouteLine(editMarker.options.route);
		
		$('#insert_route_id').val(editRoute.id);
		$('#insert_point_id').val(editMarker.options.point_id);
		$('#position').val('prev');
		$('#add_lat').val(map.getCenter().lat);
		$('#add_lng').val(map.getCenter().lng);
		
		$('form#insert').addClass('active');
	});
	$('#insertNext').click(function() {
		$('form#edit').removeClass('active');
		editMarker.setLatLng([$('#org_lat').val(), $('#org_lng').val()]);
		editMarker.dragging.disable();
		editMarker.stopBouncing();
		
		newMarker.setIcon(L.svgIcon({
			color: editRoute.color,
			borderColor: editRoute.second_color,
			html: '<i class="fas fa-asterisk"></i>'
		}));
		newMarker.addTo(map).setLatLng(map.getCenter()).bounce(1);
		
		editRoute.markers.splice(editRoute.markers.indexOf(editMarker) + 1, 0, newMarker);
		setRouteLine(editMarker.options.route);
		
		$('#insert_route_id').val(editRoute.id);
		$('#insert_point_id').val(editMarker.options.point_id);
		$('#position').val('next');
		$('#add_lat').val(map.getCenter().lat);
		$('#add_lng').val(map.getCenter().lng);
		
		$('form#insert').addClass('active');
	});
	$('form#insert .close').click(function() {
		$('form#insert').removeClass('active');
		newMarker.remove();
		
		editRoute.markers.splice(editRoute.markers.indexOf(newMarker), 1);
		setRouteLine(editRoute);
		
		editMarker = null;
		editRoute = null;
	});
	
	$('.color').colorpicker({
		useAlpha: false,
		format: 'hex'
	});
	$('#routeColor').on('colorpickerChange', function(e) {
		var borderColor = e.color.api('hsl');
		if(borderColor.api('lightness') < 50) {
			borderColor._color.color[2] += (100 - borderColor._color.color[2]) * 0.25;
		} else {
			borderColor._color.color[2] -= borderColor._color.color[2] * 0.25;
		}
		
		$('#routeSecondColor').val(borderColor.toHexString());
        $('#routeColorPreview').css({
			'background-color': e.color.toHexString(),
			'border-color': borderColor.toHexString(),
			'color': borderColor.toHexString()
		});
		newMarker.setIcon(L.svgIcon({
			color: e.color.toHexString(),
			borderColor: borderColor.toHexString(),
			html: '<i class="fas fa-asterisk"></i>'
		}));
	});
	$('#clientColor').on('colorpickerChange', function(e) {
		var borderColor = e.color.api('hsl');
		if(borderColor.api('lightness') < 50) {
			borderColor._color.color[2] += (100 - borderColor._color.color[2]) * 0.25;
		} else {
			borderColor._color.color[2] -= borderColor._color.color[2] * 0.25;
		}
		
		$('#clientSecondColor').val(borderColor.toHexString());
        $('#clientColorPreview').css({
			'background-color': e.color.toHexString(),
			'border-color': borderColor.toHexString(),
			'color': borderColor.toHexString()
		});
	});
	$('#editClientColor').on('colorpickerChange', function(e) {
		var borderColor = e.color.api('hsl');
		if(borderColor.api('lightness') < 50) {
			borderColor._color.color[2] += (100 - borderColor._color.color[2]) * 0.25;
		} else {
			borderColor._color.color[2] -= borderColor._color.color[2] * 0.25;
		}
		
		$('#editClientSecondColor').val(borderColor.toHexString());
        $('#editClientColorPreview').css({
			'background-color': e.color.toHexString(),
			'border-color': borderColor.toHexString(),
			'color': borderColor.toHexString()
		});
	});
	
	var map = L.map('map'),
		storedPosition = false;
	if(localStorage.getItem('zoom')) {
		storedPosition = true;
		map.setView([localStorage.getItem('lat'), localStorage.getItem('lng')], localStorage.getItem('zoom'));
	} else {
		map.setView({!! json_encode($mapCenter) !!}, 13);
	}
	
	map.on('moveend', function() {
		localStorage.setItem('lat', map.getCenter().lat);
		localStorage.setItem('lng', map.getCenter().lng);
		localStorage.setItem('zoom', map.getZoom());
	});

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(map);
	
	L.SVGIcon = L.DivIcon.extend({
		options: {
			className: 'base-icon',
			html: '',
			color: '#ff0000',
			// borderColor: '#bf0000',
			iconSize: [34, 50],
			shadowSize: [52, 50]
		},
		initialize: function(options) {
			options = L.Util.setOptions(this, options);
			if (!options.borderColor) { 
				options.borderColor = options.color;
			}
		},
		createIcon: function(el, old) {
			return $('<div class="svg-marker ' + this.options.className + '" style="color: ' + this.options.borderColor + '; margin-left: -17px; margin-top: -50px; width: 34px; height: 50px;"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="34px" height="50px" viewBox="-10 -10 330 490" xml:space="preserve"><g><path fill="' + this.options.color + '" stroke="' + this.options.borderColor + '" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" stroke-alignment="center" d="M159.75,0.401C71.522,0.401,0,71.923,0,160.151c0,41.685,19.502,80,69.75,129.75   c50.5,50,84.725,142.523,90,190c5.292-47.477,39.623-140,90.281-190C300.438,240.15,320,201.836,320,160.151   C320,71.923,248.254,0.401,159.75,0.401z"/></g></svg>' + this.options.html + '</div>')[0];
		},
		createShadow(el, old) {
			return $('<div class="svg-marker-shadow ' + this.options.className + '-shadow" style="margin-left: -17px; margin-top: -50px; width: 52px; height: 50px;"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="52px" height="50px" viewBox="-10 -10 510 490" xml:space="preserve"><filter id="blurShadow"><feGaussianBlur in="SourceGraphic" stdDeviation="15" /></filter><g transform="rotate(10 -50 -100) translate(300 194) skewX(-40) scale(1 0.5)"><path filter="url(#blurShadow)" fill="rgba(0,0,0,0.5)" d="M159.75,0.401C71.522,0.401,0,71.923,0,160.151c0,41.685,19.502,80,69.75,129.75   c50.5,50,84.725,142.523,90,190c5.292-47.477,39.623-140,90.281-190C300.438,240.15,320,201.836,320,160.151   C320,71.923,248.254,0.401,159.75,0.401z"/></g></svg></div>')[0];
		}
	});
	L.svgIcon = function(options) {
		return new L.SVGIcon(options);
	};
	
	var newMarker = L.marker([52.20142, 6.20114], {
		icon: L.svgIcon({
			color: '#ff0000',
			borderColor: '#bf0000',
			html: '<i class="fas fa-asterisk"></i>'
		}),
		draggable: true
	}).on('dragend', function() {
		$('#new_lat').val(this.getLatLng().lat);
		$('#new_lng').val(this.getLatLng().lng);
		
		$('#add_lat').val(this.getLatLng().lat);
		$('#add_lng').val(this.getLatLng().lng);
	}).on('drag', function() {
		if(editRoute) {
			setRouteLine(editRoute);
		}
	});
	
	var positionHistory = [];
	if (navigator.geolocation) {
		var positionAccuracy = L.circle([52.20142, 6.20114], {
			radius: 1,
			opacity: 0.8,
			color: '#dfe6fa',
			fill: true,
			fillColor: '#dfe6fa',
			fillOpacity: 0.3
		}).addTo(map);

		function currentPosition(position) {
			positionAccuracy.setLatLng([position.coords.latitude, position.coords.longitude]).setRadius(position.coords.accuracy);
			if(!positionHistory.length || positionHistory[positionHistory.length - 1][0] !== position.coords.latitude || positionHistory[positionHistory.length - 1][1] !== position.coords.longitude) {
				positionHistory.push([position.coords.latitude, position.coords.longitude]);
			}
		}
		navigator.geolocation.watchPosition(currentPosition);
	}
	
	function setRouteLine(route) {
		var latLngs = [];
		route.markers.forEach(marker => latLngs.push(marker.getLatLng()));
		route.line.setLatLngs(latLngs);
	}
	
	var routes = [],
		latLngs = [],
		editRoute,
		editMarker;
	@foreach($routes as $route)
		route = {!! json_encode($route) !!};
		route.markers = [];
		route.line = L.polyline([], {
			color: '{{ $route->color }}',
			weight: 2,
			dashArray: '3, 4',
			dashOffset: '0'
		}).addTo(map);
		@foreach($route->getAllPoints() as $point)
			latLngs.push([{{ $point->lat }}, {{ $point->lng }}]);
			marker = L.marker([{{ $point->lat }}, {{ $point->lng }}], {
				icon: L.svgIcon({
					html: '{!! $loop->first ? '<i class="fas fa-play-circle"></i>' : (empty($point->code) ? '' : '<i class="fas fa-qrcode"></i>') !!}',
					color: '{{ $route->color }}',
					borderColor: '{{ $route->second_color }}',
					draggable: true
				}),
				point_id: '{{ $point->id }}',
				route_id: '{{ $route->id }}',
				route: route,
				code: '{{ $point->code }}'
			}).addTo(map).on('click', function() {
				if(editMarker != this) {
					if(editMarker) {
						editMarker.setLatLng([$('#org_lat').val(), $('#org_lng').val()]);
						editMarker.dragging.disable();
						editMarker.stopBouncing();
						setRouteLine(editMarker.options.route);
					}
					this.bounce(2);
				}
				$('form#edit').addClass('active');
				$('form#edit h2').html('Route bewerken: ' + this.options.route.name);
				$('#point_id').val(this.options.point_id);
				$('#route_id').val(this.options.route_id);
				$('#qr_code').val(this.options.code);
				$('#org_lat').val(this.getLatLng().lat);
				$('#org_lng').val(this.getLatLng().lng);
				$('#edit_lat').val(this.getLatLng().lat);
				$('#edit_lng').val(this.getLatLng().lng);
				
				map.panInside(this.getLatLng(), {
					paddingTopLeft: [25, $('form#edit').height() + 65],
					paddingBottomRight: [25, 5]
				});
				editMarker = this;
				editRoute = this.options.route;
			}).on('dragend', function() {
				$('#edit_lat').val(this.getLatLng().lat);
				$('#edit_lng').val(this.getLatLng().lng);
			}).on('dragstart', function() {
				this.stopBouncing();
			}).on('drag', function() {
				setRouteLine(this.options.route);
			}).on('bounceend', function() {
				if(editMarker == this) {
					this.dragging.enable();
				}
			});
			marker.dragging.disable();
			route.markers.push(marker);
		@endforeach
		
		setRouteLine(route);
		routes.push(route);
	@endforeach
	
	@if($mapFit)
	if(!storedPosition) {
		map.fitBounds(L.latLngBounds(latLngs));
	}
	@endif
	
	@if(session()->has('message'))
	$('#status #message').text('{{ session()->get('message') }}');
	$('#status').addClass('active');
	@endif
	$('#status .button.close').click(function() {
		$('#status').removeClass('active');
	});
	
	
	
	
	var clients = {!! json_encode($clients->keyBy('id')) !!};
	
	for(var id in clients) { 
		if (clients.hasOwnProperty(id)) {
			clients[id].line = L.polyline([], {
				color: clients[id].color,
				weight: 2,
				dashArray: '10, 4',
				dashOffset: '0'
			}).addTo(map);
		}
	}

	$('#clients i').on('click', function() {
		var client = clients[$(this).attr('data-id')];
		
		$('form#client').addClass('active');
		// $('form#client h2').html('Deelnemer bewerken: ' + client.name);
		
		$('form#client .qr').html('').attr('href', '/start?code=' + client.code);
		new QRCode($('form#client .qr')[0], {
			text: client.code,
			width: 128,
			height: 128,
			colorDark : "#000000",
			colorLight : "#ffffff",
			correctLevel : QRCode.CorrectLevel.H
		});
		
		$('#editClientId').val(client.id);
		$('#editClientName').val(client.name);
		$('#editClientColor').val(client.color).trigger('change');
		$('#editClientRoute').val(client.route_id);
		if(client.client_points.length) {
			$('#editClientRoute').prop('readonly', true);
		} else {
			$('#editClientRoute').prop('readonly', false);
		}
		
		
		// todo, het bewandelde pad ophalen en tonen
		
		$.ajax({
			url: '{{ route('admin.clients') }}',
			method: 'POST',
			data: {
				get: true,
				id: client.id
			},
			context: document.body
		}).done(function(data) {
			clients[client.id].client_points = data.points;
			clients[client.id].client_locations = data.locations;
			
			var locations = [];
			clients[client.id].client_locations.forEach(location => locations.push([location.lat, location.lng]));
			clients[client.id].line.setLatLngs(locations);
			
			$('#clientTime').html(data.time);
			
			console.log(clients[client.id].line);
		});
	});
	$('form#client .close').click(function() {
		$('form#client').removeClass('active');
		
		
		// todo, het bewandelde pad verbergen
	});
</script>
<script type="module">
	import QrScanner from "./js/qr-scanner.js";
	QrScanner.WORKER_PATH = './js/qr-scanner-worker.min.js';

	const video = $('#qrScanner video')[0];
	const scanner = new QrScanner(video, result => setResult(result));

	function setResult(result) {
		scanner.stop();
		$('#qrScanner').removeClass('active');
		
		$('#qr_code').val(result);
		$('form#edit').addClass('active');
	}
	
	$('#qr_code_label').on('click', function() {
		$('form#edit').removeClass('active');
		$('#qrScanner').addClass('active');
		scanner.start();
		map.panInside(this.getLatLng(), {
			paddingTopLeft: [25, window.innerWidth + 65],
			paddingBottomRight: [25, 5]
		});
	});
	$('#qr_code_clear').on('click', function() {
		$('#qr_code').val('');
	});
	
	$('#qrScanner .button.close').click(function() {
		scanner.stop();
		$('#qrScanner').removeClass('active');
		$('form#edit').addClass('active');
	});
	map.invalidateSize();
</script>
@endsection