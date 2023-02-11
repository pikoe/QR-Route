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
class Point extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
		'lat',
		'lng',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
	
	public function route() {
		return $this->belongsTo(Route::class);
	}
	
	public function nextPoint() {
		return $this->belongsTo(Point::class, 'next_point_id');
	}
	
	public function prevPoint() {
		return $this->hasOne(Point::class, 'next_point_id');
	}
}
