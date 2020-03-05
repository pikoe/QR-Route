<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Model
 *
 * @author Dennis
 */
class Route extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'color',
		'second_color'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
	
	public function points() {
		return $this->hasMany(Point::class);
	}
	
	public function startPoint() {
		return $this->belongsTo(Point::class, 'start_point_id');
	}
	
	public function getAllPoints() {
		$points = [];
		
		$last = $this->startPoint;
		while($last) {
			$points[] = $last;
			$last = $last->nextPoint;
		}
		
		return $points;
	}
}
