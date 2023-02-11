<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of ClientPoint
 *
 * @author Dennis
 */
class ClientPoint extends Model {

	public function client() {
		return $this->belongsTo(Client::class);
	}
	
	public function point() {
		return $this->belongsTo(Point::class);
	}
	
}
