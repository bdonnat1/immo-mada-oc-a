<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends MX_Controller
{

    var $data;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('client/Client_model');
        // $this->load->library('securite');
    }


    public function addaction(){

        $cle = "ok";
        $msg="";

        $nom_client = $this->input->post('nom_client');
        $prenom_client = $this->input->post('prenom_client');
        $mail_client = $this->input->post('mail_client');
        $telephone_client = $this->input->post('telephone_client');
        $adresse_client = $this->input->post('adresse_client');
        $type_client = $this->input->post('type_client');
        $nif_client = $this->input->post('nif_client');
        $stat_client = $this->input->post('stat_client');
        $raison_sociale_client = $this->input->post('raison_sociale_client');
        $login = $this->input->post('login_client');


        $login_utilisateur = $this->input->post('login_utilisateur');
        $mdp_utilisateur = $this->input->post('mdp_client');
        $mdp_utilisateur_confirmation = $this->input->post('mdp_client_confirmation');

        $count_raison_sociale_client = $this->Client_model->count(array("raison_sociale_client"=>$raison_sociale_client));
        $count_login = $this->Client_model->count(array("login_client"=>$login));

        if ($count_raison_sociale_client > 0 && $type_client =='Société'){
            $cle ="erreur";
            $msg ="CE RAISON SOCIAL EST DEJA ENREGISTRER";
        }
        if ($count_login > 0){
            $cle ="erreur";
            $msg =" CE LOGIN EST DEJA ENREGISTRER";
        }
        if ($nom_client=="" && $type_client == "Personnel"){
            $cle ="erreur";
            $msg =" LE CHAMP NOM EST REQUIS";
        }
        if ($prenom_client=="" && $type_client == "Personnel"){
            $cle ="erreur";
            $msg =" LE CHAMP PRENOM EST REQUIS";
        }
        if ($nif_client ==''&& $type_client=='Société'){
            $cle = "erreur";
            $msg ="LE CHAMP NIF EST REQUIS";
        }
        if ($stat_client ==''&& $type_client=='Société'){
            $cle = "erreur";
            $msg ="LE CHAMP STAT EST REQUIS";
        }
        if ($telephone_client ==''){
            $cle = "erreur";
            $msg ="LE CHAMP TELEPHONE EST REQUIS";
        }

        if ($mdp_utilisateur_confirmation =='' || $mdp_utilisateur ==''){
            $cle = "erreur";
            $msg ="LE CHAMP MOTS DE PASSE EST REQUIS";
        }
        if ($mdp_utilisateur !== $mdp_utilisateur_confirmation){
            $cle = "erreur";
            $msg ="VERIFIER VOTRE MOTS DE PASSE";
        }

        if ($cle ==="ok"){
            //insertion dans la table utilisateur
            $data_users = array(
                "nom_client"=>trim(strtoupper($nom_client)),
                "prenom_client"=>trim(ucwords($prenom_client)),
                "mail_client"=>trim($mail_client),
                "telephone_client"=>trim($telephone_client),
                "adresse_client"=>trim($adresse_client),
                "type_client"=>trim($type_client),
                "nif_client"=>trim($nif_client),
                "stat_client"=>trim($stat_client),
                "raison_sociale_client"=>trim($raison_sociale_client),
                "login_client"=>$login,
                "mdp_client"=>sha1($mdp_utilisateur)
            );
            $this->db->trans_start();
            $this->Client_model->add($data_users);
            $this->db->trans_complete();

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $cle ="erreur";
                $msg = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
                $this->db->trans_commit();
                $msg = "Enregistrement effectué avec succès!";
                $duree_cookie = '31536000';

                set_cookie('client_ctht_id', $this->Client_model->get_last(), $duree_cookie);
                set_cookie('client_ctht_login', $login, $duree_cookie);
            }
        }

        $data = array(
            "cle"=>$cle,
            "msg"=>$msg
        );
        echo json_encode($data);
    }


    public function get($id = "")
    {
        $data = $this->Client_model->getSimple(["id" => $id]);
        echo json_encode($data);
    }

    public function fetchALl(){
        // Recuperer le champs à trier et le mode de tri
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

        $statut = isset($_GET['statut']) ? $_GET['statut'] : "";


        if ($criteriacol != "") {
            $critere = " ( " . $criteriacol . " LIKE '%" . $filter . "%' ) ";
        } else {
            $critere = "( nom_client LIKE '%" . $filter . "%' OR ";
            $critere .= " nif_client LIKE '%" . $filter . "%' OR ";
            $critere .= " statut_client LIKE '%" . $filter . "%' OR ";
            $critere .= " type_client LIKE '%" . $filter . "%' ) ";
        }

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Client_model->count($critere);
        $data_parent["total"] = $total;

        $last_page = ($total / $per_page);
        $total_page = $last_page - (int)$last_page;
        $last_page = $total_page > 0 ? ((int)$last_page) + 1 : $last_page;

        $data_parent["last_page"] = $last_page;

        $from = ($current_page - 1) * $per_page;
        $to = $current_page * $per_page;

        $data_parent["from"] = $from;
        $data_parent["to"] = $to;

        $data = $this->Client_model->get($critere, $champs, $ordre, $from, $per_page);
        //$data_parent = array("data" => $data);
        $data_parent["data"] = $data;

        echo json_encode($data_parent);
    }

    public function addactions()
    {
        $error = [];
        $message = "";

        $nom = $this->input->post("nom");
        $login = $this->input->post("login");
        $mdp = $this->input->post("mdp");
        $validation_mdp = $this->input->post("validation_mdp");
        $role = $this->input->post("role");
        $statut = $this->input->post("statut");


        if ($nom == "") {
            $error[] = "Champs Nom complet réquis";
        }
        if ($login == "") {
            $error[] = "Champs Login réquis";
        }
        if (trim($mdp) == "") {
            $error[] = "Champs Mot de passe réquis";
        }
        if (trim($validation_mdp) == "") {
            $error[] = "Champs Validation mot de passe réquis";
        }
        if ($role == "") {
            $error[] = "Champs Role réquis";
        }
        if ($statut == "") {
            $error[] = "Champs Statut du compte réquis";
        }
        if (trim($mdp) != trim($validation_mdp)) {
            $error[] = "Le mot de passe doit être égale à la validation";
        }

        $nbrnom = $this->Client_model->count(["nom" => $nom]);
        $nbrlogin = $this->Client_model->count(["login" => $login]);

        if ($nbrnom > 0) {
            $error[] = "Le Nom complet choisit existe déjà!";
        }
        if ($nbrlogin > 0) {
            $error[] = "Le Login choisit existe déjà!";
        }
        if (empty($error)) {
            $data = [
                "nom" => $nom,
                "login" => $login,
                "mdp" => sha1(trim($mdp)),
                "role" => $role,
                "statut" => $statut,
                "etat_suppression" => 0
            ];
            $this->db->trans_start();
            // Operation d'insertion
            $this->Client_model->add($data);


            $this->db->trans_complete();
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $error[] = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
                $this->db->trans_commit();
                $message = "Enregistrement effectué avec succès!";
            }
        }

        $output = array(
            'error' => implode(",<br/> ", $error),
            'message' => $message
        );
        echo json_encode($output);
    }

    public function editaction_()
    {
        $error = [];
        $message = "";

        $id = $this->input->post("id");
        $nom = $this->input->post("nom");
        $login = $this->input->post("login");
        $mdp = $this->input->post("mdp");
        $validation_mdp = $this->input->post("validation_mdp");
        $role = $this->input->post("role");
        $statut = $this->input->post("statut");


        if ($nom == "") {
            $error[] = "Champs Nom complet réquis";
        }
        if ($login == "") {
            $error[] = "Champs Login réquis";
        }
        if ($role == "") {
            $error[] = "Champs Role réquis";
        }
        if ($statut == "") {
            $error[] = "Champs Statut du compte réquis";
        }
        if (trim($mdp) != trim($validation_mdp)) {
            $error[] = "Le mot de passe doit être égale à la validation";
        }

        $unom = $this->Client_model->getSimple(["nom" => $nom]);
        $ulogin = $this->Client_model->getSimple(["login" => $login]);

        $current_users = $this->Client_model->getSimple(["id" => $id])[0];

        if (count($unom) > 0) {
            if ($unom[0]->id != $current_users->id) {
                $error[] = "Le Nom complet choisit existe déjà!";
            }
        }
        if (count($ulogin) > 0) {
            if ($ulogin[0]->id != $current_users->id) {
                $error[] = "Le Login choisit existe déjà!";
            }
        }


        if (empty($error)) {
            $data = [
                "nom" => $nom,
                "login" => $login,
                "role" => $role,
                "statut" => $statut
            ];

            if (get_cookie("users_smcm_role") != "SUPER ADMIN" && get_cookie("users_smcm_role") != "dévéloppeur") {
                $data = [
                    "nom" => $nom,
                    "login" => $login
                ];
            }

            if (trim($mdp) != "") {
                $data["mdp"] = sha1(trim($mdp));
            }

            $this->db->trans_start();
            // Operation d'insertion
            // var_dump($data);
            $this->Client_model->edit($data, ["id" => $id]);

            $this->db->trans_complete();
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $error[] = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
                $this->db->trans_commit();
                $message = "Enregistrement effectué avec succès!";
            }
        }

        $output = array(
            'error' => implode(",<br/> ", $error),
            'message' => $message
        );
        echo json_encode($output);
    }

    /**
     * Supprimer une famille d'article
     *
     * La suppression ne peut s'effectuer que si la famille n'est associé à aucun article
     *
     * @param type $id
     */
    public function delete($id)
    {
        $cle= "ok";
        $message = "";
        $get = $this->Client_model->getSimple(array("id"=>$id))[0];


        $this->db->trans_start();
        $this->Client_model->edit(array("etat_suppression" => 1), ["id" => $id]);
        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            $cle = "erreur";
            $message = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
        } else {
//                if(file_exists(getcwd().'/assets/img/utilisateur/'.$get->adresse_client)){
//                    $file =getcwd().'/assets/img/utilisateur/'.$get->adresse_client;
//                    unlink($file);
//                }
            $this->db->trans_commit();
            $message = "Suppression effectué!";
        }

        $output = array(
            'cle' => $cle,
            'msg' => $message
        );
        echo json_encode($output);
    }

    public function getUserConfig()
    {
        $data = [];
        // echo date("H:i:s")."</br>";
        $users = $this->Client_model->getSimple(["id" => get_cookie("users_smcm_id")]);
        if (count($users) > 0) {
            $users = $users[0];
            $duree_cookie = '31536000';

            set_cookie("users_smcm_nom", $users->nom, $duree_cookie);
            set_cookie("users_smcm_statut", $users->statut, $duree_cookie);
            set_cookie("users_smcm_role", $users->role, $duree_cookie);
            if (empty(get_cookie("users_smcm_tva"))) {
                set_cookie("users_smcm_tva", "INACTIVE", $duree_cookie);
            }
        }
        echo json_encode($data);
    }



    public function loginaction()
    {
        $data = array();
        $login = $this->input->post("login_utilisateur");
        $password = $this->input->post("mdp_utilisateur");
        $remember = $this->input->post("remember");

        $error = "";
        $message = "";
        $key_user = "";
        $is_set_mdp = true;
        $duree_cookie = '31536000';

        $nbr = $this->Client_model->count(array(
            'login_client' => $login,
            'mdp_client' => sha1($password),
//            'statut' => 'ACTIVE',
//            'etat_suppression' => 0
        ));

        if ($nbr > 0) {
            $users = $this->Client_model->getSimple(array('login_client' => $login, 'mdp_client' => sha1($password)));

            set_cookie('client_ctht_id', $users[0]->id, $duree_cookie);
            set_cookie('client_ctht_login', $users[0]->login_client, $duree_cookie);

            $message = 'Authentification établie!';
        } else {
            $error = "Login ou mot de passe incorrect!";
        }

        $output = array(
            'error' => $error,
            'message' => $message
        );
        echo json_encode($output);
    }

}
