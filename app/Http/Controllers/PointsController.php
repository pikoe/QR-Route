<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PointsController extends Controller {
	public function checkCode(Request $request) {
		sleep(1);
		if($request->code == 'sdf') {
			// todo, client aanmaken, voor eerste code
			// todo, client point aanmaken voor gevonden point
			return [
				'lat' => 52.22342,
				'lng' => 6.23414,
			];
		} else {
			return [
				'error' => 'Dit is niet een code die we zoeken',
			];
		}
	}
	
	public function updateLocation(Request $request) {
		// todo, client location aanmaken
		
		return $request->only(['lat', 'lng']) + ['t' => date('Y-m-d H:i:s')];
	}
}
