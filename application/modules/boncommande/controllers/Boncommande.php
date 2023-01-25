<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boncommande extends MX_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('produit/Produit_model');
        $this->load->model('produit_variation/Produit_variation_model');
        $this->load->model('Produit_photos/Produit_photos_model');
        $this->load->model('boncommande/Boncommande_model');
        $this->load->model('boncommande/Boncommandedetail_model');
        $this->load->model('utilisateur/Utilisateur_model');
        $this->load->library('email');
        $this->load->library('fpdf_master');
    }

    public function fetchAll(){

        $sort = explode('|', $_GET['sort']);
        $champs = $sort[0];
        $ordre = $sort[1];
        // Recuperer la page à afficher
        $current_page = $_GET['page'];
        // Recuperer l'item à afficher par page
        $per_page = $_GET['per_page'];
        // Recuperer le filtre si ça existe
        $filter = isset($_GET['filter']) ? $_GET['filter'] : "";
        // Recuperer le filtre colonne si ça existe
        $criteriacol = isset($_GET['criteriacol']) ? $_GET['criteriacol'] : "";
        $criteriacolEtat = isset($_GET['criteriacolEtat']) ? $_GET['criteriacolEtat'] : "";
        $filterEtat = isset($_GET['filterEtat']) ? $_GET['filterEtat'] : "";

        $critere ="";
        $critere .= " (nom_client LIKE '%".$filter."%' OR";
        $critere .= "  prenom_client LIKE '%".$filter."%' OR";
        $critere .= "  adresse_client LIKE '%".$filter."%' OR";
        $critere .= "  login_client LIKE '%".$filter."%' OR";
        $critere .= "  nif_client LIKE '%".$filter."%' OR";
        $critere .= "  stat_client LIKE '%".$filter."%' OR";
        $critere .= "  numero_facture LIKE '%".$filter."%' OR";
        $critere .= "  raison_sociale_client LIKE '%".$filter."%' OR";
        $critere .= "  telephone_client LIKE '%".$filter."%' ) ";

        if ($filterEtat != ""){
            $critere .= " AND ( statut_facture ='".$filterEtat."' )";
        }

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Boncommande_model->count($critere);
        $data_parent["total"] = $total;

        $last_page = ($total / $per_page);
        $total_page = $last_page - (int)$last_page;
        $last_page = $total_page > 0 ? ((int)$last_page) + 1 : $total;

        $data_parent["last_page"] = $last_page;

        $from = ($current_page-1)*$per_page;
        $to = $current_page*$per_page;

        $data_parent["from"] = $from;
        $data_parent["to"] = $to;


        $data = $this->Boncommande_model->get($critere, $champs, $ordre, $from, $per_page);


        //$data_parent = array("data" => $data);
        $data_parent["data"] = $data;

        echo json_encode($data_parent);

    }


    public function changestatut($id){
        // $data =array(
        //     "statut_facture"=>"VALIDER",
        // );
        // $this->Boncommande_model->edit($data,array("id"=>$id));

        $donne = array(
            "cle"=>"ok",
            "msg"=>"Impression en cours ..."
        );

        echo json_encode($donne);
    }

    public function format($value){
        $format =  $format = sprintf("%05d",$value);
        return $format;

    }

    public function addaction(){

        $cle = "ok";
        $msg = "";

        $id_client = $this->input->post('id_client');
        $id_variation = $this->input->post('id_variation');
        $qte = $this->input->post('qte');

        $date = date('Y-m-d');

        //insertion dans la table bon_de_commande
        $numero = $this->Boncommande_model->count() > 0 ? $this->Boncommande_model->count() + 1 : 1;

        $qte_total = 0;
        $montant_total = 0;
        for ($i=0; $i < count($id_variation); $i++){
            $variation = $this->Produit_variation_model->getSimple(array("id"=>$id_variation[$i]))[0];
            $qte_total += $qte[$i];
            $montant_total += ($qte[$i]) * $variation->prix;
        }

        $data_commande = array(
            "numero"=>$numero,
            "numero_facture"=>$this->format($numero),
            "qte_total"=>$qte_total,
            "montant_total"=>$montant_total,
            "statut_facture"=>"NOUVEAU",
            "id_client"=>$id_client,
            "etat_suppression"=>0,
            "date_bon_de_commande"=>$date,
        );
        $this->Boncommande_model->add($data_commande);

        //insertions dans la table detail;
        for ($i=0; $i < count($id_variation); $i++){
            $variation = $this->Produit_variation_model->getSimple(array("id"=>$id_variation[$i]))[0];
            $data_detail = array(
                "id_bon_de_commande"=>$this->Boncommande_model->get_last(),
                "id_produit_variation"=>$id_variation[$i],
                "qte"=>$qte[$i],
                "montant"=> $qte[$i] * $variation->prix
            ); //
            $this->Boncommandedetail_model->add($data_detail);
        }


        $email = " Vous avez reçu une commande </br> <a href='".base_url()."'> cliquez içi pour voir la commande</a>";

        $user = $this->Utilisateur_model->getSimple();
        foreach($user as $key => $value){
            if ($value->mail_utilisateur != ""){

                $this->email->set_mailtype("html");
//                $this->email->from("mbolatianaratsitobaina1712@gmail.com");
                $this->email->to($value->mail_utilisateur);
                $this->email->subject("Reception du commande");
                $this->email->message($email);
                $this->email->send();
                $this->email->clear();
            }
        }


        $data = array(
            "cle"=>$cle,
            "msg"=>$msg,
            "id_commande"=>$this->Boncommande_model->get_last()
        );
        echo json_encode($data);

    }


    public function apercu($id){
        $bon_commande = $this->Boncommande_model->getSimple(array("id"=>$id))[0];
        $detail = $this->Boncommandedetail_model->getSimple(array("id_bon_de_commande"=>$id));

        $this->fpdf->SetFont('Arial', '', 12);
        $this->fpdf->AddPage('P');
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->SetMargins(10, 10, 10, 10);


        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->Cell(95, 8, mb_strtoupper(utf8_decode($bon_commande->nom_client." ".$bon_commande->prenom_client)), 0, 0, 'L', 0);

        $this->fpdf->SetFont('Arial', '', 10);

        $this->fpdf->Ln();
        $this->fpdf->Cell(65, 5, utf8_decode("Adresse: ".$bon_commande->adresse_client), '', 0, 'L', 0);

        $this->fpdf->Ln();
        $this->fpdf->Cell(70, 4, utf8_decode("Telephone: ".$bon_commande->telephone_client), '', 0, 'L', 0);


        $this->fpdf->Ln();
        $this->fpdf->Cell(65, 5, utf8_decode("E-mail: ".$bon_commande->mail_client), '', 0, 'L', 0);

        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', '', 10);
        $this->fpdf->setY(15);
        $this->fpdf->setX(130);
        $this->fpdf->Cell(70, 8, utf8_decode("Toamasina  le, ".date("d/m/Y")) , 0, 0, 'R', 0);


        $this->fpdf->SetFillColor(210, 210, 210);


        $this->fpdf->SetFont('Arial', 'B', 14);
        $this->fpdf->setY(41);
        $this->fpdf->setX(110);
        $this->fpdf->Cell(90, 10, utf8_decode("BON DE COMMANDE  N° ".$bon_commande->numero_facture), 1, 0, 'C', 1);



        $this->fpdf->Ln(15);

        $this->fpdf->SetFont('Arial', 'B', 10);

        $this->fpdf->Cell(90, 8, utf8_decode(mb_strtoupper("Désignation")), "BLT", 0, 'L', 1);
        $this->fpdf->Cell(30, 8, utf8_decode(mb_strtoupper("Variation")), "BLT", 0, 'C', 1);
        $this->fpdf->Cell(15, 8, utf8_decode(mb_strtoupper("Qte")), "BLT", 0, 'C', 1);
        $this->fpdf->Cell(25, 8, utf8_decode(mb_strtoupper("PU ")), 'BLT', 0, 'C', 1);
        $this->fpdf->Cell(30, 8, utf8_decode(mb_strtoupper("Montant HT")), 'BLRT', 0, 'C', 1);

        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', '', 10);

        foreach ($detail as $key => $value){
            $this->fpdf->Cell(90, 6, utf8_decode($value->designation), 'L', 0, 'L', 0);
            $this->fpdf->Cell(30, 6, utf8_decode($value->variation), 'L', 0, 'C', 0);
            $this->fpdf->Cell(15, 6,  utf8_decode(mb_strtoupper(number_format( $value->qte,0,",", " "))), 'L', 0, 'C', 0);
            $this->fpdf->Cell(25, 6, utf8_decode(mb_strtoupper(number_format( $value->prix_produit, 0, ",", " "))), 'L', 0, 'R', 0);
            $this->fpdf->Cell(30, 6, utf8_decode(mb_strtoupper(number_format( $value->montant, 0, ",", " "))), 'LR', 0, 'R', 0);

            $this->fpdf->Ln();
        }

        $table_height = 200 - count($detail) * 6;
        $this->fpdf->Cell(90, $table_height, "", 'L', 0, 'L', 0);
        $this->fpdf->Cell(30, $table_height, "", 'L', 0, 'L', 0);
        $this->fpdf->Cell(15, $table_height, "", 'L', 0, 'L', 0);
        $this->fpdf->Cell(25, $table_height,"", 'L', 0, 'C', 0);
        $this->fpdf->Cell(30, $table_height, "", 'LR', 0, 'R', 0);

        // $this->fpdf->Ln();

        // $this->fpdf->Cell(160, 1, "", 'T', 0, 'C', 0);
        // $this->fpdf->Cell(30, 2, "", 'LRT', 0, 'R', 0);

        $montant_total = $bon_commande->montant_total;

        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->Cell(160, 8, utf8_decode("MONTANT TOTAL  "), 'LTRB', 0, 'R', 0);
        $this->fpdf->Cell(30, 8, utf8_decode(mb_strtoupper(number_format($montant_total, 0, ",", " "))), 'TRB', 0, 'R', 0);

        $this->fpdf->SetFont('Arial', 'BI', 10);
        $this->fpdf->Ln(12);
        $this->fpdf->MultiCell(190, 5, utf8_decode(mb_strtoupper("Arrêté la présente facture à la somme de " . ucwords(nombreLettreFR($montant_total)) . " Ariary")), '', 'C', 0);

        $this->fpdf->Output();

//        $this->fpdf->Output('Bon_commande/'.$data[0]->numero.'.pdf','F');

//        if(file_exists(getcwd().'/Bon_commande/devis_'.$data[0]->numero.'.pdf')) {
//            unlink(getcwd().'/Bon_commande/devis_'.$data[0]->numero.'.pdf');
//        };

//        echo "BON DE COMMANDE GENERER AVEC SUCCES";
    }

}
