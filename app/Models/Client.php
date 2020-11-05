<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Client
 *
 * @author Dennis
 */
class Client extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'color',
		'second_color',
    ];
	
	
	public function route() {
		return $this->belongsTo(Route::class);
	}
	
	public function clientLocations() {
		return $this->hasMany(ClientLocation::class);
	}
	
	public function clientPoints() {
		return $this->hasMany(ClientPoint::class);
	}
}
