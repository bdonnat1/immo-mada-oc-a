<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorie extends MX_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('categorie/Categorie_model');

    }

    public function get()
    {
        $data=$this->Categorie_model->getCategorie();

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
            $critere = " ( nom_categorie LIKE '%".$filter."%' OR";
            $critere .= "  description LIKE '%".$filter."%' ) ";


        }

        $critere.=" AND id >= 1 AND etat_suppression = 0";

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Categorie_model->count($critere);
        $data_parent["total"] = $total;

        $last_page = ($total / $per_page);
        $total_page = $last_page - (int)$last_page;
        $last_page = $total_page > 0 ? ((int)$last_page) + 1 : $total;

        $data_parent["last_page"] = $last_page;

        $from = ($current_page-1)*$per_page;
        $to = $current_page*$per_page;

        $data_parent["from"] = $from;
        $data_parent["to"] = $to;


        $data = $this->Categorie_model->get($critere, $champs, $ordre, $from, $per_page);
        $data_parent["data"] = $data;

        echo json_encode($data_parent);

    }

    public function addaction(){

        $msg_envoyer = array();
        $nom_categorie=$this->input->post('nom_categorie');
        $description=$this->input->post('description');
        $photos = $this->input->post('file');



        $count_categorie = $this->Categorie_model->count(array("nom_categorie"=>$nom_categorie));

        if ($count_categorie > 0){
            array_push($msg_envoyer, 'Ce catégorie est déjà enregistrer');
            $message = array('cle' => 'erreur',
                'msg' => $msg_envoyer);
        }
        elseif (empty($nom_categorie)){
            array_push($msg_envoyer, 'Le champ   categorie est requis');
            $message = array('cle' => 'erreur',
                'msg' => $msg_envoyer);
        }
        // elseif (empty($description)){
        //     array_push($msg_envoyer, 'Le champ description est requis');
        //     $message = array('cle' => 'erreur',
        //         'msg' => $msg_envoyer);
        // }

        else{
            $data = array(
                'nom_categorie'=>$nom_categorie,
                'description'=>$description,
                'photos'=>$photos
            );
            $this->Categorie_model->add($data);
            //deplace la photos
            if ($photos !=""){
                $photos = deplace_image("/uploads/categorie/",'/assets/img/categorie/',$this->Categorie_model->get_last(),$photos);
                $this->Categorie_model->edit(["photos"=>$photos],["id"=>$this->Categorie_model->get_last()]);
            }

            array_push($msg_envoyer,'INSERTION TERMINER AVEC SUCCES');
            $message = array('cle'=>'ok',
                'msg'=>$msg_envoyer);
            }
            echo json_encode($message);
    }

    public function delete($id) {

        $msg_envoyer = array();
            $data = array(
                'etat_suppression' => 1
            );
            $this->Categorie_model->edit($data,array("id"=>$id));

            array_push($msg_envoyer,'SUPPRESSION TERMINER AVEC SUCCESS');
            $message = array('cle'=>'ok',
                'msg'=>$msg_envoyer);
            echo json_encode($message);
    }

    public function editcategorie()
    {
        $msg_envoyer = array();
        $categorie_id=$this->input->post('categorie_id');
        $nom_categorie=$this->input->post('nom_categorie');
        $description=$this->input->post('description');
        $photos = $this->input->post('file');
        $id = $this->input->post('id');

        $count_categorie = $this->Categorie_model->count(array("nom_categorie"=>$nom_categorie));
        $categorie = $this->Categorie_model->getSimple(array("id"=>$id))[0];

        if ($count_categorie > 0 && $categorie->nom_categorie != $nom_categorie){
            array_push($msg_envoyer, 'Ce catégorie est déjà enregistrer');
            $message = array('cle' => 'erreur',
                'msg' => $msg_envoyer);
        }else{
            if ($categorie->photos != $photos && $photos !=""){
                $photos = deplace_image("/uploads/categorie/",'/assets/img/categorie/',$id,$photos);
            }
            $data = array(
                'nom_categorie'=>$nom_categorie,
                'description'=>$description,
                'photos'=>$photos,
            );

            $this->Categorie_model->edit($data,array("id"=>$categorie_id));

            array_push($msg_envoyer,'MODIFICATION TERMINER AVEC SUCCES');
            $message = array('cle'=>'ok',
                'msg'=>$msg_envoyer);
        }

        echo json_encode($message);

    }

    public function getid($id=""){
        $data = $this->Categorie_model->getSimple(["id" => $id,"etat_suppression"=>0]);
        echo json_encode($data);
    }

    public function uploadfile(){
        $data_got=json_decode(file_get_contents('php://input'),1);
        if (!is_dir(getcwd().'/uploads/categorie')) {
            mkdir(getcwd().'/uploads/categorie', 0777, TRUE);
        }
        $config['upload_path']          = getcwd().'/uploads/categorie'; //.$folder_name;
        $config['allowed_types']        = 'jpg|png|gif|pdf|doc|docx|xls|xlsx';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('file-input'))
        {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            echo json_encode($data);
        }
    }

    public function get_photos($id=""){
        $data = $this->Categorie_model->getSimple(["id" => $id,"etat_suppression"=>0]);
        echo json_encode($data);
    }

}
