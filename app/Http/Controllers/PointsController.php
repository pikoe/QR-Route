<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class PointsController extends Controller {
	public function checkCode(Request $request) {
		sleep(1);
		
		if($request->session()->has('client_id')) {
			$client = Client::find($request->session()->get('client_id'));
			if($client->clientPoints()->count() == 0) {
				if($client->route->startPoint->code == $request->code) {
					if($client->route->startPoint->nextPoint) {
						return [
							'client_id' => $client->id,
							'lat' => $client->route->startPoint->nextPoint->lat,
							'lng' => $client->route->startPoint->nextPoint->lng,
						];
					} else {
						return [
							'client_id' => $client->id,
							'error' => 'Dit was het laatste punt van de route',
						];
					}
				}
			} else {
				$nextPoint = false;
				// todo volgende in route?
			
				foreach($client->clientPoints as $clientPiont) {
					$clientPiont->point->nextPoint;
				}
						
				
			}
					
		} else {
			$client = Client::where('code', $request->code)->first();
			if($client) {
				$request->session()->put('client_id', $client->id);
				
				if($client->route && $client->route->startPoint) {
					// todo route geven
					return [
						'client_id' => $client->id,
						'lat' => $client->route->startPoint->lat,
						'lng' => $client->route->startPoint->lng,
					];
				} else {
					return [
						'client_id' => $client->id,
						'error' => 'Er is nog geen startpunt bekend',
					];
				}
			}
		}
		
		return [
			'error' => 'Dit is niet een code die we zoeken',
		];
	}
	
	public function updateLocation(Request $request) {
		// todo, client location aanmaken
		
		return $request->only(['lat', 'lng']) + ['t' => date('Y-m-d H:i:s')];
	}
}
