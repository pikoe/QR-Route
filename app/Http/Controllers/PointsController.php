<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PointsController extends Controller {
	public function checkCode(Request $request) {
		sleep(1);
		if($request->code == 'sdf') {
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
}
