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
			
			return [
				'points' => $client->clientPoints,
				'locations' => $client->clientLocations,
			];
		}
	}
}
