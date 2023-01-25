<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sendmail extends MX_Controller
{
    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sendmail/Sendmail_model');
        $this->load->model('client/Monsociete_model');
        $this->load->library('email');
    }


    public function addaction()
    {
        $cle = "ok";
        $nom = $this->input->post('nom');
        $adresse_mail = $this->input->post('adresse_mail');
        $telephone = $this->input->post('telephone');
        $msg_envoyer = $this->input->post('msg_envoyer');
        $lien = $this->input->post('lien');
        $bool = true;
        $from = $this->Monsociete_model->getSimple(array("id"=>1))[0]->adresse_mail;;


        $concat = "\n\n\n\n      Lien: <a href='".$lien."'>".$lien." </a>";

        $msg_mail = $msg_envoyer." ".$concat;
        if ($msg_envoyer == '') {
            $cle = 'erreur';
            $msg = 'LE CHAMP MESSAGE EST REQUIS';
            $bool = false;
        }

        if ($bool) {

            $this->email->set_mailtype("html");
            $this->email->from($from);
            $this->email->to($adresse_mail);
            $this->email->subject("DEMANDE INFORMATION CLIENT");
            $this->email->message($msg_mail);
            $this->email->send();
            $this->email->clear();

            //insertion dans la table email
            $data_mail = array(
                "nom"=>$nom,
                "adresse_mail"=>$adresse_mail,
                "telephone"=>$telephone,
                "msg_envoyer" => $msg_envoyer,
                "lien"=>$lien
            );
            $this->Sendmail_model->add($data_mail);

            $msg="EMAIL EST BIEN ENVOYER";
        }
        $data = array(
            "cle"=>$cle,
            "msg"=>$msg
        );
        echo json_encode($data);
    }
}
