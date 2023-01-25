<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('calcRowNumber'))
{
    /**
     * @DjasnivX
     * Cette fonction permet le calcul du nombre de ligne qu'un text a besoin
     * pour qu'il soit bien afficher dans un PDF generer par FPDF
     * 
     * Les paramettres nécéssaire sont
     *      $fpdf: L'instance de FPDF ($this->fpdf)
     *      $w: La largeur du cellule
     *      $text: Le text à afficher dont on veut calculer la largeur
     *  
     */
    function calcRowNumber($fpdf,$w,$text = ""){
            $taille_text = doubleval($fpdf->GetStringWidth($text)+$fpdf->cMargin*2);
            $nbr_ligne = intval($taille_text/$w);
            $nbr_ligne = (($taille_text/$w)-$nbr_ligne)>0?$nbr_ligne+1:$nbr_ligne;

            return $nbr_ligne;
    }

}