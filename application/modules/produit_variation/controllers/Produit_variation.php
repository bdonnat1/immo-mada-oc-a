<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Produit_variation extends MX_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('produit_variation/Produit_variation_model');
        $this->load->model('panierclient/Panierclient_model');
    }

    public function uploadfile(){
        $data_got=json_decode(file_get_contents('php://input'),1);
        if (!is_dir(getcwd().'/uploads/variation')) {
            mkdir(getcwd().'/uploads/variation', 0777, TRUE);
        }
        $config['upload_path']          = getcwd().'/uploads/variation'; //.$folder_name;
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

    public function get_varition($id=""){
        $data = $this->Produit_variation_model->getSimple(["produit_id" => $id,"etat_suppression"=>0]);
        echo json_encode($data);
    }

    public function getid($id=""){
        $data = $this->Produit_variation_model->getSimple(["produit_id" => $id,"etat_suppression"=>0]);
        echo json_encode($data);
    }

    public function getVariation ($id){
        $data = $this->Produit_variation_model->getSimple(array("id"=>$id));
        echo json_encode($data);
    }

    public function deleteVariation($id){
        $count_variation = $this->Produit_variation_model->count(array("id"=>$id));
        if ($count_variation > 0){
            $this->Produit_variation_model->delete(array("id"=>$id));
        }
    }


    public function addproduitsession(){
        $id_variation = $this->input->post('id_variation');
        $produit_id = $this->input->post('id_produit');
        $id_client = $this->input->post('id_client') ?? 1;
        $variation = $this->Produit_variation_model->getSimple(array("id"=>$id_variation,"produit_id"=>$produit_id))[0];
//        $data_panier = array(
//            "id_produit_variation"=>$id_variation,
//            "qte"=>1,
//            "montant"=> $variation->prix * 1,
//            "id_client"=>$id_client,
//        );
//
//        $this->Panierclient_model->add($data_panier);
//
//        $pannier_cookies = $this->Panierclient_model->getSimple(array("id_client"=>$id_client));
//        $this->input->set_cookie("pannier_dna_commerce",json_encode($pannier_cookies),(time() + (86400 * 30)));

        $pannier_dna_commerce = get_cookie("pannier_dna_commerce");
        if (!is_null($pannier_dna_commerce)){
            $val_tem = json_decode($pannier_dna_commerce);
            $pannier_cookies = unserialize($val_tem);
            $id_count = count($pannier_cookies) + 1;

            array_push($pannier_cookies,array(
                                                        "id_count"=>$id_count,
                                                        "id_variation"=>$variation->id,
                                                        "produit_id"=>$produit_id,
                                                        "reference_produit"=>$variation->reference_produit,
                                                        "variation"=>$variation->variation,
                                                        "prix"=>$variation->prix,
                                                        "qte"=>1,

            ));
            $valeur_session = serialize($pannier_cookies);
        }else{
            $data[]= array(
                "id_count"=>1,
                "id_variation"=>$variation->id,
                "produit_id"=>$produit_id,
                "reference_produit"=>$variation->reference_produit,
                "variation"=>$variation->variation,
                "prix"=>$variation->prix,
                "qte"=>1,
            );
            $valeur_session = serialize($data);
        }
        $this->input->set_cookie("pannier_dna_commerce",json_encode($valeur_session),(time() + (86400 * 30)));

        echo json_encode("ok");
    }

    public function getPannierCookies(){

        $pannier_dna_commerce = get_cookie("pannier_dna_commerce");
        $val_temp = json_decode($pannier_dna_commerce);
        $pannier_cookies = unserialize($val_temp);
        echo json_encode($pannier_cookies);
    }

    public function deletecookies($id){
        $pannier_dna_commerce = get_cookie("pannier_dna_commerce");
        $val_temp = json_decode($pannier_dna_commerce);
        $pannier_cookies = unserialize($val_temp);

        foreach ($pannier_cookies as $item => $value) {
           if ($pannier_cookies[$item]['id_count']== $id){
               unset($pannier_cookies[$item]);
               break;
            }
        }

        delete_cookie("pannier_dna_commerce");

        $valeur_session = serialize($pannier_cookies);
        $this->input->set_cookie("pannier_dna_commerce",json_encode($valeur_session),(time() + (86400 * 30)));
        echo json_encode("ok");
    }
}
