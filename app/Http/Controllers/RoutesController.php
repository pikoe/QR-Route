<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Route;
use App\Models\Client;

class RoutesController extends Controller {
	public function admin(Request $request) {
		
		if($request->isMethod('post') && $request->add) {
			$startPoint = new Point;
			$startPoint->lat = $request->lat;
			$startPoint->lng = $request->lng;
			$startPoint->save();
			
			$route = new Route;
			$route->name = $request->name;
			$route->color = $request->color;
			$route->second_color = $request->second_color;
            $route->start_point_id = $startPoint->id;
			$route->save();
			
			$startPoint->route_id = $route->id;
			$startPoint->save();
			
			return redirect()->back()->with([
				'mapCenter' => [
					$request->lat,
					$request->lng
				]
			]);
		} else if($request->isMethod('post') && $request->edit) {
			if($request->code !== null && Point::where('code', '=', $request->code)->where('id', '<>', $request->point_id)->exists()) {
				return redirect()->back()->with([
					'mapCenter' => [
						$request->lat,
						$request->lng
					],
					'message' => 'QR code is al in gebruik',
				]);
			}
			$point = Point::find($request->point_id);
			$point->lat = $request->lat;
			$point->lng = $request->lng;
			$point->code = $request->code;
			$point->save();
			
			return redirect()->back()->with([
				'mapCenter' => [
					$request->lat,
					$request->lng
				]
			]);
		} else if($request->isMethod('post') && $request->insert) {
			if($request->position == 'prev') {
				$nextPoint = Point::find($request->point_id);
				$prevPoint = $nextPoint->prevPoint;
			} else {
				$prevPoint = Point::find($request->point_id);
				$nextPoint = $prevPoint->nextPoint;
			}
			if($prevPoint) {
				$prevPoint->next_point_id = null;
				$prevPoint->save();
			}
			$point = new Point;
			$point->lat = $request->lat;
			$point->lng = $request->lng;
			if($nextPoint) {
				$point->next_point_id = $nextPoint->id;
			}
			$point->route_id = $request->route_id;
			$point->save();
			if($prevPoint) {
				$prevPoint->next_point_id = $point->id;
				$prevPoint->save();
			} else {
				$route = Route::find($request->route_id);
				$route->start_point_id = $point->id;
				$route->save();
			}
			
			return redirect()->back()->with([
				'mapCenter' => [
					$request->lat,
					$request->lng
				]
			]);
		} else if($request->isMethod('post') && $request->delete) {
			$point = Point::find($request->point_id);
			$prevPoint = $point->prevPoint;
			$next_point_id = $point->next_point_id;
			$point->delete();
			if($prevPoint) {
				if($next_point_id) {
					$prevPoint->next_point_id = $next_point_id;
				} else {
					$prevPoint->next_point_id = null;
				}
				$prevPoint->save();
			} else {
				$route = Route::find($request->route_id);
				if($next_point_id) {
					$route->start_point_id = $next_point_id;
					$route->save();
				} else {
					$route->delete();
				}
			}
			
			return redirect()->back();
		}
		
		return view('routes.admin', [
			'routes' => Route::all(),
			'clients' => Client::all(),
			'mapFit' => !$request->session()->has('mapCenter'),
			'mapCenter' => $request->session()->get('mapCenter', [51.75294164, 5.89340866]),
		]);
	}
}
