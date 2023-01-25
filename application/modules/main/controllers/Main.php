<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Main extends MX_Controller {

    var $data;
    public function __construct()
    {
        parent::__construct();
        // $this->load->library("DNASocket");
        // if ((get_cookie('users_transit_52_id')) ==''){

        //     redirect(base_url().'login/index');
        // }
        // $this->output->set_header('Authorization : APIKEY your-api-key-here');
    }

    public function index(){
        $data = array();
        $this->load->view('index',$data);
    }

    public function init(){
        echo json_encode(["etat" => "OK"]);
    }

}