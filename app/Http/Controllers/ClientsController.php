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
	public function admin(Request $request) {
		if($request->isMethod('post') && $request->add) {
			$client = new Client;
			$client->fill($request->all());
			$client->code = Str::random(15);
			$client->save();
			
			return redirect()->back();
		}
		
		return view('clients.admin', [
			'clients' => [],
		]);
	}
}
