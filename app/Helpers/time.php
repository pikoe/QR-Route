<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function format_seconds($seconds, $precision = 0) {
    $seconds = round($seconds, $precision);
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    if($precision) {
        return sprintf('%02d:%02d:%0' . ((int)$precision + 3) . '.' . (int)$precision . 'f', $h, $m, $seconds % 60);
    } else {
        return sprintf('%02d:%02d:%02d', $h, $m, $seconds % 60);
    }
}
