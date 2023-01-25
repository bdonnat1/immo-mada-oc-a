<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('dateDiffDetail'))
{
    function dateDiffDetail($start,$end){
        
        $retour = "";
        
        
        $date1 = new DateTime($start);
        $date2 = new DateTime($end);
        
        $diff = $date2->diff($date1);
        
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
        $hours = $diff->h;
        $minutes = $diff->i;
        $secondes = $diff->s;
        
        if($years > 0) {
            $retour .= $years > 1 ? $years . ' ans' : $years . ' an';
        }
        if($months > 0) {
            $retour .= $retour == "" ? "" : " ";
            $retour .= $months . ' mois';
        }
        if($days > 0) {
            $retour .= $retour == "" ? "" : " ";
            $retour .= $days > 1 ? $days . ' jours' : $days . ' jour';
        } else {
            if($hours > 0) {
                $retour .= $retour == "" ? "" : " ";
                $retour .= $hours . ' h';
            } else {
                if($minutes > 0) {
                    $retour .= $retour == "" ? "" : " ";
                    $retour .= $minutes . ' mn';
                }
                if($minutes == 0) {
                    $retour .= $retour == "" ? "" : " ";
                    $retour .= $secondes . ' s';
                }
            }
        }
        /*
        var_dump($date1);
        var_dump($date2);
        var_dump($diff);
        */
        return $retour;
        
    }
    
}
/**
* Transforme date sql en date format francais
* @param date $var au format SQL
* @param int $full 1 dans le cas de datetime
* @return string 
*/
if (!function_exists('fechaF'))
{
    function fechaF($var,$full=""){
        if(empty($var) || $var=="0000-00-00" || $var=="0000-00-00 00:00:00") return "";
        $f1=substr($var,0,4);//année
        $f2=substr($var,5,2);//mois
        $f3=substr($var,8,2);//jour
        $fecha=$f3."/".$f2."/".$f1;
        if($full=="")	return $fecha;
        else return substr($var,8,2)."/".substr($var,5,2)."/".substr($var,0,4)." &agrave; ".substr($var,-8,2)."h".substr($var,-5,2);
    }
}

/**
* transforme date format francaise au format sql
* @param string $var
* @return string 
*/
if (!function_exists('fechaSQL'))
{
    function fechaSQL($var){
        if($var=="") return "";
        $f=explode("/",$var);
        $var=$f[2]."-".$f[1]."-".$f[0];
        return $var;
    }
}

/**
*
* @param date $fecha
* @return string jour de la smaine en francais
*/
if (!function_exists('JourF'))
{
    function JourF($fecha){
           $numero=date('w', strtotime($fecha));
           $lesjours=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
           return $lesjours[$numero];
    }
}
/**
*
* @param date $fecha format mysql
* @return string nb jour dans le mois
*/
if (!function_exists('nbJourMois'))
{
    function nbJourMois($fecha){
       $last = date('t', strtotime($fecha));
       return $last;
    }
}
/**
* Ajoute ou soustraire un nombre de jour
* @param date $fecha
* @param int $nbjour
* @return string Date au format sql 
*/
if (!function_exists('AjoutJour'))
{
    function AjoutJour($fecha,$nbjour){// fecha au format SQL !
        $calcul=date('Y-m-d', (strtotime($nbjour."DAY" , strtotime($fecha) ))) ;
        return $calcul;
    }
}

/**
* Retrouve le numero de la semaine
* @param date $fecha
* @return int 
*/
if (!function_exists('SemaineNum'))
{
    function SemaineNum($fecha){
        $numero=date('W', strtotime($fecha));
        return $numero;
    }
}

if (!function_exists('JourSemaineSimple'))
{
    function JourSemaineSimple($ladate){
        $numjour=date('w', (strtotime($ladate)));
        $s[0]="Dimanche";
        $s[1]="Lundi";
        $s[2]="Mardi";
        $s[3]="Mercredi";
        $s[4]="Jeudi";
        $s[5]="Vendredi";
        $s[6]="Samedi";
        return $s[$numjour];
    }
}

if (!function_exists('jourSemaine'))
{
    function jourSemaine() {
       $jourSemaine = array(
            Array ( 'num' => 1, 'jour' => 'Lundi', 'abbr' => 'Lu'),
            Array ( 'num' => 2, 'jour' => 'Mardi', 'abbr' => 'Ma'),
            Array ( 'num' => 3, 'jour' => 'Mercredi', 'abbr' => 'Me'),
            Array ( 'num' => 4, 'jour' => 'Jeudi', 'abbr' => 'Je'),
            Array ( 'num' => 5, 'jour' => 'Vendredi', 'abbr' => 'Ve'),
            Array ( 'num' => 6, 'jour' => 'Samedi', 'abbr' => 'Sa'),
            Array ( 'num' => 0, 'jour' => 'Dimanche', 'abbr' => 'Di')
        );
        return $jourSemaine;
    }
}
/**
* Trouver le lundi de la semaine 
* @param date $n
* @return string Date au format sql
*/
if (!function_exists('DebutSemaine'))
{
    function DebutSemaine($n){
        $numerojour=date('w', strtotime($n));
        if($numerojour!=1){
                $menos=(($numerojour>1) ? $numerojour-1 : 6);
                $ndate=Ajoutjour($n,-$menos);
        }
        else{
                $ndate=$n;
        }
        return $ndate;
    }
}

/**
* Ajout ou soustraire nb de mois
* @param string $fecha date format sql
* @param int $nbmois nb de mois
* @return string date au format SQL 
*/
if (!function_exists('AjoutMois'))
{
    function AjoutMois($fecha,$nbmois){// fecha au format SQL !
        $calcul=date('Y-m-d', (strtotime($nbmois."MONTH" , strtotime($fecha) ))) ;
        $ladate[0]=$calcul;
        return $ladate[0];
    }
}
/**
* différences entre 2 dates format sql
* @param string $fecha1 date SQL
* @param string $fecha2 date SQL
* @return int quantite en jour
*/
if (!function_exists('diffdate'))
{
    function diffdate($fecha1,$fecha2){
        $diff= 1+(strtotime($fecha2) - strtotime($fecha1))/86400 ;
        return $diff;
    }
}
/**
* différences entre 2 dates format sql
* @param string $fecha1 date SQL
* @param string $fecha2 date SQL
* @return int age
*/
if (!function_exists('diffdateAge'))
{
    function diffdateAge($fecha1){
        $date = new DateTime($fecha1);
        $now = new DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }
}
/**
* différences entre 2 dates format sql
* @param string $debut date SQL
* @param string $fin date SQL
* @return int quantite en mn
*/
if (!function_exists('getDuree'))
{
    function getDuree($debut,$fin){
        $diff = (strtotime($fin) - strtotime($debut))/60 ;
        return $diff;
    }
}

if (!function_exists('getDureeOuvrable'))
{
    function getDureeOuvrable($debut,$fin){
        $enleve = 0;
        $jours = diffdate($debut,$fin);
        $nbjour = floor($jours);
        if($nbjour > 1){
                $enleve = ($nbjour-1)*60*15;
                if(substr($fin,-8,2)>12) $j = $nbjour; else $j = ($nbjour-1);
                $enleve += $j*60*2;
        }
        $diff = (strtotime($fin) - strtotime($debut))/60;
        if($nbjour == 1 && $diff >240) $enleve = 60*2;
        $duree = $diff - $enleve;
        return $duree;	
    }
}

if (!function_exists('translateMnToHeure'))
{
    function translateMnToHeure($mn){
        $rhh = floor($mn/60);
        $rmn = $reste%60;
        return $rhh."H".sprintf('%02d',$rmn);	
    }
}

if (!function_exists('FrenchStringDate'))
{
    function FrenchStringDate($var){
           $today=date('Y-m-d');
           if($var=="0000-00-00 00:00:00" OR empty($var)) return "";
           $ladate=DecortiqueFecha($var);
           $ladate2=$ladate['annee']."-".$ladate['mois']."-".$ladate['jour'];
           if($today==$ladate2){
                   $jour="Aujourd'hui ";
           }else{
                   $jour=JourF($ladate2)." ";
           }
           $mois=" ".meses($ladate['mois'])." ";
           $final=$jour.$ladate['jour'].$mois.($ladate['heure']!=""?$ladate['heure']."h":"").($ladate['minute']!=""?$ladate['minute']:"").$ladate['annee'];
           return $final;
    }
}



if (!function_exists('FrenchStringDateDMY'))
{
    function FrenchStringDateDMY($var){
           $today=date('Y-m-d');
           if($var=="0000-00-00 00:00:00" OR empty($var)) return "";
           $ladate=DecortiqueFecha($var);
           $ladate2=$ladate['annee']."-".$ladate['mois']."-".$ladate['jour'];
           if($today==$ladate2){
                   $jour="Aujourd'hui ";
           }else{
                   $jour=JourF($ladate2)." ";
           }
           $mois=" ".meses($ladate['mois'])." ";
           $final=$ladate['jour'].$mois.$ladate['annee'];
           return $final;
    }
}


if (!function_exists('DecortiqueFecha'))
{
    function DecortiqueFecha($var){
           $f['annee']=substr($var,0,4);//année
           $f['mois']=substr($var,5,2);//mois
           $f['jour']=substr($var,8,2);//jour
           $f['heure']=substr($var,11,2);//heures
           $f['minute']=substr($var,14,2);//mn
       return $f;
    }
}

if (!function_exists('getHoraire'))
{
    function getHoraire($data=''){
        if($data=="") return "";
        if($data=="00:00:00") return "";
        return substr($data,0,2)."h".substr($data,3,2);
    }
}

if (!function_exists('addDuree'))
{
    function addDuree($h,$mn,$duree){
        if(duree!=""){
                $mn=$mn*1;
                $basic=($h*60)+$mn;
                $neo=$basic+$duree;
                $h=floor($neo/60);
                $mn=$neo-($h*60);
        }
        return $h.":".sprintf('%02d',$mn);
    }
}

if (!function_exists('meses'))
{
    function meses($mois){
        switch ($mois){
            case 1:$mois='Janvier';
            break;
            case 2:$mois='Février';
            break;
            case 3:$mois='Mars';
            break;
            case 4:$mois='Avril';
            break;
            case 5:$mois='Mai';
            break;
            case 6:$mois='Juin';
            break;
            case 7:$mois='Juillet';
            break;
            case 8:$mois='Août';
            break;
            case 9:$mois='Septembre';
            break;
            case 10:$mois='Octobre';
            break;
            case 11:$mois='Novembre';
            break;
            case 12:$mois='Decembre';
            break;
        }
        return $mois;
    }
}

if (!function_exists('minute_to_real_time'))
{
    function minute_to_real_time($minutes) {
        
        $duree = "";
        $mn = $minutes%60;
        if($minutes>=60) {
            $heure = ($minutes - $mn) / 60;
            $duree .= $heure."h".($mn>0?$mn."mn":"");
        } else {
            $duree .= $mn."mn";
        }
        return $duree;
        /*
        $zero    = new DateTime('@0');
        $offset  = new DateTime('@' . $minutes * 60);
        $diff    = $zero->diff($offset);
        $jour = $diff->format('%a');
        $heure = $diff->format('%h');
        $minute = $diff->format('%i');
        $duree = "";
        $duree .= $jour>0?$jour.'j':'';
        $duree .= $heure>0?$heure.'h':'';
        $duree .= $minute>0?$minute.'mn':'';
        return $duree;*/
    }
}


if (!function_exists('difference_between_hours_to_minutes'))
{
    function difference_between_hours_to_minutes($hours, $minutes) {
        $minute_hours = date('H:i', mktime(1,$minutes));
        $date = date("H:i", strtotime($hours)-  strtotime($minute_hours));
        return date("H", strtotime($date)).'h'.date("i", strtotime($date));
    }
}
