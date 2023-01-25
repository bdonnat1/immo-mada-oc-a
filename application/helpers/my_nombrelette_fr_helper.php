<?php
function nombreLettreFR($number,$separateur=",") {
    $convert = explode($separateur, $number);
    $num[17] = array('zero', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit',
                     'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize');
                      
    $num[100] = array(20 => 'vingt', 30 => 'trente', 40 => 'quarante', 50 => 'cinquante',
                      60 => 'soixante', 70 => 'soixante-dix', 80 => 'quatre-vingt', 90 => 'quatre-vingt-dix');
                                      
    if (isset($convert[1]) && $convert[1] != '') {
      return nombreLettreFR($convert[0]).' et '.nombreLettreFR($convert[1]);
    }
    if ($number < 0) return 'moins '.nombreLettreFR(-$number);
    if ($number < 17) {
      return $num[17][$number];
    }
    elseif ($number < 20) {
      return 'dix-'.nombreLettreFR($number-10);
    }
    elseif ($number < 100) {
      if ($number%10 == 0) {
        return $num[100][$number];
      }
      elseif (substr($number, -1) == 1) {
        if( ((int)($number/10)*10)<70 ){
          return nombreLettreFR((int)($number/10)*10).'-et-un';
        }
        elseif ($number == 71) {
          return 'soixante-et-onze';
        }
        elseif ($number == 81) {
          return 'quatre-vingt-un';
        }
        elseif ($number == 91) {
          return 'quatre-vingt-onze';
        }
      }
      elseif ($number < 70) {
        return nombreLettreFR($number-$number%10).'-'.nombreLettreFR($number%10);
      }
      elseif ($number < 80) {
        return nombreLettreFR(60).'-'.nombreLettreFR($number%20);
      }
      else {
        return nombreLettreFR(80).'-'.nombreLettreFR($number%20);
      }
    }
    elseif ($number == 100) {
      return 'cent';
    }
    elseif ($number < 200) {
      return nombreLettreFR(100).' '.nombreLettreFR($number%100);
    }
    elseif ($number < 1000) {
      return nombreLettreFR((int)($number/100)).' '.nombreLettreFR(100).($number%100 > 0 ? ' '.nombreLettreFR($number%100): '');
    }
    elseif ($number == 1000){
      return 'mille';
    }
    elseif ($number < 2000) {
      return nombreLettreFR(1000).' '.nombreLettreFR($number%1000).' ';
    }
    elseif ($number < 1000000) {
      return nombreLettreFR((int)($number/1000)).' '.nombreLettreFR(1000).($number%1000 > 0 ? ' '.nombreLettreFR($number%1000): '');
    }
    elseif ($number == 1000000) {
      return 'millions';
    }
    elseif ($number < 2000000) {
      return nombreLettreFR(1000000).' '.nombreLettreFR($number%1000000);
    }
    elseif ($number < 1000000000) {
      return nombreLettreFR((int)($number/1000000)).' '.nombreLettreFR(1000000).($number%1000000 > 0 ? ' '.nombreLettreFR($number%1000000): '');
    }
  }