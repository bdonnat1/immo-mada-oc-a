<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categorie extends MX_Controller
{

    var $final_category_ids = [];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categorie/Categorie_model');

    }

    public function getParentCategorie(){
        $data = $this->Categorie_model->getCategorie(array("categorie_parent"=>0));
        echo json_encode($data);
    }

    public function get()
    {
        $data = $this->Categorie_model->getCategorie();
        echo json_encode($data);
    }

    public function fetchAll()
    {

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

        if ($criteriacol != "") {
            $critere = " ( " . $criteriacol . " LIKE '%" . $filter . "%' ) ";
        } else {
            $critere = " ( nom_categorie LIKE '%" . $filter . "%' OR";
            $critere .= "  description LIKE '%" . $filter . "%' ) ";


        }

        $critere .= " AND id >= 1 AND etat_suppression = 0 ";

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Categorie_model->count($critere);
        $data_parent["total"] = $total;

        $last_page = ($total / $per_page);
        $total_page = $last_page - (int)$last_page;
        $last_page = $total_page > 0 ? ((int)$last_page) + 1 : $total;

        $data_parent["last_page"] = $last_page;

        $from = ($current_page - 1) * $per_page;
        $to = $current_page * $per_page;

        $data_parent["from"] = $from;
        $data_parent["to"] = $to;


        $data = $this->Categorie_model->get($critere, $champs, $ordre, $from, $per_page);
        $data_parent["data"] = $data;

        echo json_encode($data_parent);

    }

    public function addaction()
    {

        $msg = '';
        $cle ='ok';
        $bool = true;
        $nom_categorie = $this->input->post('nom_categorie');
        $description = $this->input->post('description');
        $photos = $this->input->post('file');

        //child_categorie
        $nom_categorie_child = $this->input->post('nom_categorie_child');

        $count_categorie = $this->Categorie_model->count(array("nom_categorie" => $nom_categorie, "nom_categorie != " => ''));

        if ($nom_categorie_child[0] == '') {
            $bool = false;
            $cle = "erreur";
            $msg = 'LE CHAMP CATEGORIE EST REQUIS';
        }

        var_dump($bool);
        var_dump($cle);
//        if ($count_categorie > 0) {
//            array_push($msg_envoyer, 'Ce catégorie est déjà enregistrer');
//            $message = array('cle' => 'erreur',
//                'msg' => $msg_envoyer);
//        } else

//        if (empty($nom_categorie)) {
//            array_push($msg_envoyer, 'Le champ   categorie est requis');
//            $message = array('cle' => 'erreur',
//                'msg' => $msg_envoyer);
//        }
        // elseif (empty($description)){
        //     array_push($msg_envoyer, 'Le champ description est requis');
        //     $message = array('cle' => 'erreur',
        //         'msg' => $msg_envoyer);
        // }

//            if ($count_categorie > 0 && $nom_categorie != ''){
//                $is_child =0;
//                $id_categorie_parent_child = $this->Categorie_model->getSimple(array("nom_categorie"=>$nom_categorie))[0]->id;
//            }elseif($count_categorie <= 0 && $nom_categorie != ''){
//                $is_child = 0;
//                $data = array(
//                    'nom_categorie' => $nom_categorie,
//                    'description' => $description,
//                    'photos' => $photos,
//                    'categorie_parent' => 0,
//                    'is_child'=>0,
//                );
//                $this->Categorie_model->add($data);
//                $id_categorie_parent_child = $this->Categorie_model->get_last();
//
//            }else{
//                $is_child = 1;
//                $id_categorie_parent_child =0;
//            }

        if ($bool) {
            if ($count_categorie > 0) {
                $id_categorie_parent_child = $this->Categorie_model->getSimple(array("nom_categorie" => $nom_categorie))[0]->id;
                $is_child = 1;
            } else {
                $is_child = 0;
                $id_categorie_parent_child = 0;
            }

            //insertion élément enfant
            if (count($nom_categorie_child) > 0) {
                for ($i = 0; $i < count($nom_categorie_child); $i++) {
                    $data = array(
                        'nom_categorie' => $nom_categorie_child[$i],
                        'is_child' => $is_child,
                        'categorie_parent' => $id_categorie_parent_child,
                    );
                    $this->Categorie_model->add($data);
                }
            }

            //deplace la photos
            if ($photos != "") {
                $photos = deplace_image("/uploads/categorie/", '/assets/img/categorie/', $this->Categorie_model->get_last(), $photos);
                $this->Categorie_model->edit(["photos" => $photos], ["id" => $this->Categorie_model->get_last()]);
            }

            $msg = 'INSERTION TERMINER AVEC SUCCES';
            $cle = 'ok';

        }

        $message = array('cle' => $cle,
            'msg' => $msg);
        echo json_encode($message);
    }

    public function delete($id)
    {
        $msg_envoyer = array();

//        $this->Categorie_model->edit(array("etat_suppression" => 1), array("categorie_parent" => 1));

        $data = array(
            'etat_suppression' => 1
        );
        $this->Categorie_model->edit($data, array("id" => $id));

        array_push($msg_envoyer, 'SUPPRESSION TERMINER AVEC SUCCESS');
        $message = array('cle' => 'ok',
            'msg' => $msg_envoyer);
        echo json_encode($message);
    }

    public function editcategorie()
    {
        $msg_envoyer = array();
        $nom_categorie = $this->input->post('nom_categorie');
        $description = $this->input->post('description');
        $photos = $this->input->post('file');
        $id = $this->input->post('id');

        $nom_categorie_child = $this->input->post('nom_categorie_child');
        $id_child = $this->input->post('id_child');


        $is_child = 0;
        $id_categorie_parent = 0;

        if ($nom_categorie != '') {
            //on peut modifier la photos
            $categorie = $this->Categorie_model->getSimple(array("nom_categorie" => $nom_categorie))[0];
            if ($categorie->photos != $photos && $photos != "") {
                $photos = deplace_image("/uploads/categorie/", '/assets/img/categorie/', $id, $photos);
            }

            $data = array(
                'nom_categorie' => $nom_categorie,
                'description' => $description,
                'photos' => $photos,
            );
            $this->Categorie_model->edit($data, array("id" => $categorie->id));
            $id_categorie_parent = $categorie->id;
            $is_child = 1;

        }

        if (count($nom_categorie_child) > 0) {
            for ($i = 0; $i < count($nom_categorie_child); $i++) {
                $data = array(
                    'nom_categorie' => $nom_categorie_child[$i],
                    'categorie_parent' => $id_categorie_parent,
                    'is_child' => $is_child,
                );

                $this->Categorie_model->edit($data, array("id" => $id_child[$i]));
            }
        }

        array_push($msg_envoyer, 'MODIFICATION TERMINER AVEC SUCCES');
        $message = array('cle' => 'ok',
            'msg' => $msg_envoyer);

        echo json_encode($message);

    }


    public function getid($id = "")
    {

        $data = $this->Categorie_model->getSimple(["id" => $id, "etat_suppression" => 0]);

        if ($data[0]->categorie_parent > 0) {
            $categorie_parent = $this->Categorie_model->getSimple(array("id" => $data[0]->categorie_parent));
            $categorie_parent[0]->child_categorie = $data;
        } else {
            $categorie_parent = $data;
            $categorie_parent[0]->child_categorie = [];
        }
        //        if ($data[0]->is_child > 0) {
//            foreach ($data as $key => $value) {
//                $value->child_categorie = $this->Categorie_model->getSimple(array("id" => $value->categorie_parent));
//            }
//        } else {
//            $data[0]->child_categorie = [];
//        }


        echo json_encode($categorie_parent);

    }


    public function uploadfile()
    {
        $data_got = json_decode(file_get_contents('php://input'), 1);
        if (!is_dir(getcwd() . '/uploads/categorie')) {
            mkdir(getcwd() . '/uploads/categorie', 0777, TRUE);
        }
        $config['upload_path'] = getcwd() . '/uploads/categorie'; //.$folder_name;
        $config['allowed_types'] = 'jpg|png|gif|pdf|doc|docx|xls|xlsx';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file-input')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            echo json_encode($data);
        }
    }

    public function get_photos($id = "")
    {
        $data = $this->Categorie_model->getSimple(["id" => $id, "etat_suppression" => 0]);
        echo json_encode($data);
    }


    public function getsouscategorie($id){
        $categorie = $this->Categorie_model->getSimple(array("id"=>$id));
        $sous_categorie = [];
        if ($id > 0){
            $sous_categorie = $this->Categorie_model->getSimple(array("categorie_parent"=>$id));
        }

        $data = array(
            "categorie"=>$categorie,
            "sous_categorie"=>$sous_categorie
        );
        echo json_encode($data);
    }

}
