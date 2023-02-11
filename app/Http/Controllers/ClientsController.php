<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Client;
use Carbon\Carbon;

/**
 * Description of ClientsController
 *
 * @author Dennis
 */

class ClientsController extends Controller {
	
	public function search(Request $request) {
	
		if($request->session()->has('client_id')) {
			$client = Client::find($request->session()->get('client_id'));
		} else {
			$client = false;	
		}
		return view('clients.search', [
			'client' => $client,
		]);
	}
			
	public function admin(Request $request) {
		if($request->isMethod('post') && $request->add) {
			$client = new Client;
			$client->fill($request->all());
			$client->route_id = $request->route_id;
			$client->code = Str::random(15);
			$client->save();
			
			return redirect()->back();
		}
		
		if($request->isMethod('post') && $request->edit) {
			$client = Client::find($request->id);
			if($client) {
				$client->fill($request->all());
				if($client->clientPoints()->count() == 0) {
					$client->route_id = $request->route_id;
				}
				$client->save();
			}
			return redirect()->back();
		}
		
		if($request->isMethod('post') && $request->get) {
			$client = Client::find($request->id);
			$time = 'Nog geen route';
			
			if($client->route && $client->route->startPoint) {
				$searchPoint = $client->route->startPoint;
				foreach($client->clientPoints as $clientPiont) {
					if($clientPiont->point->nextPoint) {
						$searchPoint = $clientPiont->point->nextPoint;
					} else {
						$searchPoint = null;
					}
				}
				if($searchPoint) {
					if($client->clientPoints()->exists()) {
						$busyInSeconds = Carbon::now()->timestamp - Carbon::parse($client->clientPoints()->min('created_at'))->timestamp;
						$time = 'Bezig: ' . format_seconds($busyInSeconds);
					} else {
						$time = 'Eerste punt nog niet gevonden';
					}
				} else {
					$finishedInSeconds = Carbon::parse($client->clientPoints()->max('created_at'))->timestamp - Carbon::parse($client->clientPoints()->min('created_at'))->timestamp;
					$time = 'Finish: ' . format_seconds($finishedInSeconds);
				}
			}	
			return [
				'points' => $client->clientPoints,
				'locations' => $client->clientLocations,
				'time' => $time,
			];
		}
	}
}
