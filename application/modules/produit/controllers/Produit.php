<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produit extends MX_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('produit/Produit_model');
        $this->load->model('produit_variation/Produit_variation_model');
        $this->load->model('Produit_photos/Produit_photos_model');
        $this->load->model('categorie/Categorie_model');
    }

    public function getALlProduit(){
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $categorie_id = isset($_GET['categorie_id']) ? $_GET['categorie_id'] : "";
        $souscategorie_id = isset($_GET['souscategorie_id']) ? $_GET['souscategorie_id'] : "";
        $data = [];
        $critere = "etat_suppression = 0";

        // var_dump($_GET); exit;

        if(!empty($search))
            $critere .= " AND designation LIKE '%" . $search . "%' ";

        // && $categorie_id != "produit"
        if($categorie_id != "" && $categorie_id != "undefined" && $categorie_id !='null' && $souscategorie_id <=0)
            $critere .= " AND (categorie_id = " . $categorie_id . " OR categorie_parent =".$categorie_id.")";

        if($categorie_id != "" && $categorie_id != "undefined" && $categorie_id !='null' && $souscategorie_id >0)
            $critere .= " AND (categorie_parent = " . $categorie_id . " AND categorie_id =".$souscategorie_id.")";

        // echo $critere;


        $produit = $this->Produit_model->getSimple($critere);

        foreach ($produit as $key => $value_produit){
            $value_produit->produit_photos = $this->Produit_photos_model->getSimple(array("produit_id"=>$value_produit->id));
        }
        $data['categories'] = $this->Categorie_model->getSimple(array("etat_suppression"=>0,"categorie_parent"=>0),"nom_categorie", "asc");
        $data['produits'] = $produit;
        // var_dump( $data);
        echo json_encode($data);
    }

    public function GetElementProduit($id_produit) {
        $data = [];
        $data['produits'] = $this->Produit_model->getSimple(array("etat_suppression"=>0,"id"=>$id_produit))[0];
        $data['produit_photos'] = $this->Produit_photos_model->getSimple(array("produit_id"=>$id_produit,"etat_suppression"=>0));
        $data['value_variation'] = $this->Produit_variation_model->getSimple(array("produit_id"=>$id_produit,"etat_suppression"=>0));
        $data['produit_meme_categorie'] = []; // $this->Produit_model->getSimple(array("etat_suppression"=>0,"categorie_id"=>$data->categorie_id));
        $data['categories'] = $this->Categorie_model->getSimple(array("etat_suppression"=>0,"categorie_parent"=>0),"nom_categorie", "asc");
        echo json_encode($data);
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

        if($criteriacol != "") {
            $critere = " ( ".$criteriacol." LIKE '%".$filter."%' ) ";
        } else {
            $critere = " ( designation LIKE '%".$filter."%' OR";
            $critere .= "  reference_produit LIKE '%".$filter."%' ) ";

        }

        $critere.=" AND id >= 1 AND etat_suppression = 0";

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Produit_model->count($critere);
        $data_parent["total"] = $total;

        $last_page = ($total / $per_page);
        $total_page = $last_page - (int)$last_page;
        $last_page = $total_page > 0 ? ((int)$last_page) + 1 : $last_page;

        $data_parent["last_page"] = $last_page;

        $from = ($current_page - 1) * $per_page;
        $to = $current_page * $per_page;

        $data_parent["from"] = $from;
        $data_parent["to"] = $to;


        $data = $this->Produit_model->getEasyTable($critere, $champs, $ordre, $from, $per_page);
        foreach ($data as $key => $produit){
            $produit_images = $this->Produit_photos_model->getSimple(array("produit_id"=>$produit->id));

            $produit_image = count($produit_images) > 0 ? $produit_images[0] : "produit.png";
            $produit->photos = count($produit_images) > 0 ? $produit_images[0]->photos : "produit.png";
            $produit_variation = $this->Produit_variation_model->getSimpleNoRq(array("produit_id"=>$produit->id));
            // $concat ="";
            // foreach ($produit_variation as $key_v => $variation){
            //     $concat.=$variation->variation."    (".$variation->prix.")</br>";
            // }
            $produit->variation = $produit_variation;
        }

        //$data_parent = array("data" => $data);
        $data_parent["data"] = $data;

        echo json_encode($data_parent);

    }

    public function addaction(){

        $msg_envoyer = array();
        $ref_produit=$this->input->post('reference_produit');
        $designation=$this->input->post('designation');
        $statut=$this->input->post('statut');
        $variation=$this->input->post('variation');
        $prix = $this->input->post('prix');
        $photos = $this->input->post('photos');
        $file = $this->input->post('file');
        $categorie_id = $this->input->post('categorie_id');
        $description_produit = $this->input->post('description_produit');
        $longueur = $this->input->post('longueur');
        $largeur = $this->input->post('largeur');
        $superficie = $this->input->post('superficie');
        $position_map = $this->input->post('position_map');

        if (empty($statut)){
            array_push($msg_envoyer, 'Le champ statut est requis');
            $message = array('cle' => 'erreur',
                'msg' => $msg_envoyer);
        }

        else{
            $data = array(
                'reference_produit'=>$ref_produit,
                'designation'=>$designation,
                'statut'=>$statut,
                'categorie_id' =>$categorie_id,
                'description_produit'=>$description_produit,
                'longueur'=>$longueur,
                'largeur'=>$largeur,
                'superficie'=>$superficie,
                'position_map'=>$position_map,
            );
            $this->Produit_model->add($data);

            if (!empty($variation) && !empty($prix) ){
                for($i=0;$i<count($variation);$i++){
                    $query= array(
                        'variation'=>$variation[$i],
                        'prix'=>$prix[$i],
                        'photos'=>$photos[$i],
                        'produit_id'=>$this->Produit_model->get_last()
                    );
                    $this->Produit_variation_model->add($query);

                    //modification image
                    if ($photos[$i]!=""){
                        // $photos_new = deplace_image("/uploads/variation/",'/assets/img/variation/',$this->Produit_variation_model->get_last(),$photos[$i]);
                        // $this->Produit_variation_model->edit(["photos"=>$photos_new],["id"=>$this->Produit_variation_model->get_last()]);
                        $photos_new = deplace_image("/uploads/variation/",'/assets/img/variation/',$this->Produit_variation_model->get_last(),$photos[$i]);
                        $this->Produit_variation_model->edit(["photos"=>$photos_new],["id"=>$this->Produit_variation_model->get_last()]);
                    }

                }
            }

            if (!empty($file) ){
                for($i=0;$i<count($file);$i++){
                    $query= array(
                        'photos'=>$file[$i],
                        'produit_id'=>$this->Produit_model->get_last()
                        );
                    $this->Produit_photos_model->add($query);

                    if ($file[$i]!=""){
                        $file_new = deplace_image("/uploads/produit/",'/assets/img/produit/',$this->Produit_photos_model->get_last(),$file[$i]);
                        // var_dump($file_new);
                        $this->Produit_photos_model->edit(["photos"=>$file_new],["id"=>$this->Produit_photos_model->get_last()]);
                    }
                }
            }

            array_push($msg_envoyer,'INSERTION TERMINER AVEC SUCCES');
            $message = array('cle'=>'ok', 'msg'=>$msg_envoyer);
        }
        echo json_encode($message);

    }

    public function delete($id) {

        $msg_envoyer = array();
            $data = array(
                'etat_suppression' => 1
            );
            $this->Produit_model->edit($data,array("id"=>$id));

            array_push($msg_envoyer,'SUPPRESSION TERMINER AVEC SUCCESS');
            $message = array('cle'=>'ok',
                'msg'=>$msg_envoyer);
            echo json_encode($message);
    }

    public function editprodruit()
    {
        $msg_envoyer = array();
        $produit_id=$this->input->post('produit_id');
        $variation_id=$this->input->post('variation_id');
        $photos_id=$this->input->post('photos_id');
        $ref_produit=$this->input->post('reference_produit');
        $designation=$this->input->post('designation');
        $statut=$this->input->post('statut');
        $categorie_id = $this->input->post('categorie_id');
        $variation=$this->input->post('variation');
        $prix = $this->input->post('prix');
        $photos = $this->input->post('photos');
        $libelle=$this->input->post('libelle');
        $file = $this->input->post('file');
        $id_variation = $this->input->post('id_variation');
        $id_produit = $this->input->post('id_produit');
        $description_produit = $this->input->post('description_produit');
        $longueur = $this->input->post('longueur');
        $largeur = $this->input->post('largeur');
        $superficie = $this->input->post('superficie');
        $position_map = $this->input->post('position_map');


        $data = array(
            'reference_produit'=>$ref_produit,
            'designation'=>$designation,
            'categorie_id' =>$categorie_id,
            'statut'=>$statut,
            'description_produit'=>$description_produit,
            'largeur'=>$largeur,
            'longueur'=>$longueur,
            'superficie'=>$superficie,
            'position_map'=>$position_map
            );

        $this->Produit_model->edit($data,array("id"=>$produit_id));
        if (!empty($variation) && !empty($prix) ){
            for($i=0;$i<count($id_variation);$i++){

                if (($id_variation[$i]) <= 0){
                    $query= array(
                        'variation'=>$variation[$i],
                        'prix'=>$prix[$i],
                        'photos'=>$photos[$i],
                        'produit_id'=>$produit_id
                    );

                    $this->Produit_variation_model->add($query);
                    $id_last = $this->Produit_variation_model->get_last();
                    if ($photos[$i] != ""){
                        $photos_new = deplace_image("/uploads/variation/",'/assets/img/variation/',$id_last,$photos[$i]);
                        $this->Produit_variation_model->edit(["photos"=>$photos_new],["id"=>$id_last]);
                    }
                }else{
                    $variation_data = $this->Produit_variation_model->getSimple(array("id"=>$id_variation[$i]))[0];
                    $id_last = $id_variation[$i];
                    if ($variation_data->photos != $photos[$i] && $photos[$i] !="" && $variation_data->photos !=''){
                        $photos_new = deplace_image("/uploads/variation/",'/assets/img/variation/',$id_last,$photos[$i]);
                        $photos[$i] = $photos_new;
                    }
                    $query= array(
                        'variation'=>$variation[$i],
                        'prix'=>$prix[$i],
                        'photos'=>$photos[$i],
                        'produit_id'=>$produit_id
                    );
                    $this->Produit_variation_model->edit($query,array("id"=>$id_last));
                }
            }
        }

        if (!empty($file) ){
            for($i=0;$i<count($file);$i++){
                if (($id_produit[$i]) <= 0){
                    $query= array(
                        'photos'=>$file[$i],
                        'produit_id'=>$produit_id
                    );

                    $this->Produit_photos_model->add($query);
                    $id_last = $this->Produit_photos_model->get_last();
                    if ($file[$i] != ""){
                        $photos_new = deplace_image("/uploads/produit/",'/assets/img/produit/',$id_last,$file[$i]);
                        $this->Produit_photos_model->edit(["photos"=>$photos_new],["id"=>$id_last]);
                    }
                }else{
                    $variation_data = $this->Produit_photos_model->getSimple(array("id"=>$id_produit[$i]))[0];
                    $id_last = $id_produit[$i];
                    if ($variation_data->photos != $file[$i] && $file[$i] !="" && $variation_data->photos !=''){
                        $photos_new = deplace_image("/uploads/produit/",'/assets/img/produit/',$id_last,$file[$i]);
                        $file[$i] = $photos_new;
                    }
                    $query= array(
                        'photos'=>$file[$i],
                        'produit_id'=>$produit_id
                    );
                    $this->Produit_photos_model->edit($query,array("id"=>$id_last));
                }


            }
        }
        array_push($msg_envoyer,'MODIFICATION TERMINER AVEC SUCCES');
        $message = array('cle'=>'ok',
        'msg'=>$msg_envoyer);
        echo json_encode($message);

    }

    public function getid($id=""){
        $data["produit"] = $this->Produit_model->getSimple(["id" => $id,"etat_suppression"=>0]);
        $data["photos"] = $this->Produit_photos_model->getSimple(["produit_id" => $id,"etat_suppression"=>0]);
        $data["variations"] = $this->Produit_variation_model->getSimple(["produit_id" => $id,"etat_suppression"=>0]);


        echo json_encode($data);
    }



}
