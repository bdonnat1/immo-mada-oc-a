<?php

if (!function_exists('convertGrToKg'))
{
    function convertGrToKg($poids = 0, $decimal = 0) {
        
        return strlen($poids) > 3 ?  number_format($poids / 1000, $decimal, '.', ' ') . " Kg" : $poids . " gr";
    
    }
}
