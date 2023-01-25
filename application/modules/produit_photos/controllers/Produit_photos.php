<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Produit_photos extends MX_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produit_photos/Produit_photos_model');
    }

    public function uploadfile(){
        $data_got=json_decode(file_get_contents('php://input'),1);
        if (!is_dir(getcwd().'/uploads/produit')) {
            mkdir(getcwd().'/uploads/produit', 0777, TRUE);
        }

        $config['upload_path']          = getcwd().'/uploads/produit'; //.$folder_name;
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
        $data = $this->Produit_photos_model->getSimple(["produit_id" => $id,"etat_suppression"=>0]);
        echo json_encode($data);
    }
    public function getid($id=""){
        $data = $this->Produit_photos_model->getSimple(["produit_id" => $id,"etat_suppression"=>0]);
        var_dump($data);
        echo json_encode($data);
    }


    public function deleteProduit($id){
        $count_produit = $this->Produit_photos_model->count(array("id"=>$id));
        if ($count_produit > 0){
            $this->Produit_photos_model->delete(array("id"=>$id));
        }
    }
}
