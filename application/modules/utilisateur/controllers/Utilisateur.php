<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Utilisateur extends MX_Controller
{

    var $data;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('utilisateur/Utilisateur_model');
        // $this->load->library('securite');
    }


    public function addaction(){
        $cle = "ok";
        $nom_utilisateur = $this->input->post('nom_utilisateur');
        $mail_utilisateur = $this->input->post('mail_utilisateur');
        $login_utilisateur = $this->input->post('login_utilisateur');
        $telephone_utilisateur = $this->input->post('telephone_utilisateur');
        $role_utilisateur = $this->input->post('role_utilisateur');
        $photos_utilisateur = $this->input->post('photos_utilisateur');
        $statut_utilisateur = $this->input->post('statut_utilisateur');

        $mdp_utilisateur = $this->input->post('mdp_client');
        $mdp_utilisateur_confirmation = $this->input->post('mdp_client_confirmation');

        $count_login = $this->Utilisateur_model->count(array("login_utilisateur"=>$login_utilisateur));
        $count_mail = $this->Utilisateur_model->count(array("mail_utilisateur"=>$mail_utilisateur));
        if ($count_login > 0){
            $cle ="erreur";
            $msg ="CE LOGIN EST DEJA ENREGISTRER";
        }
        if ($count_mail > 0 && $mail_utilisateur != ""){
            $cle ="erreur";
            $msg =" LE MAIL EST DEJA ENREGISTRER";
        }
        if ($role_utilisateur ==''){
            $cle = "erreur";
            $msg ="LE CHAMP ROLE EST REQUIS";
        }

        if ($statut_utilisateur ==''){
            $cle = "erreur";
            $msg ="LE CHAMP STATUT EST REQUIS";
        }
        if ($mdp_utilisateur != $mdp_utilisateur_confirmation){
            $cle = "erreur";
            $msg ="VERIFIER VOTRE MOTS DE PASSE";
        }

        if ($cle ==="ok"){
            //insertion dans la table utilisateur
            $data_users = array(
                "nom_utilisateur"=>$nom_utilisateur,
                "mail_utilisateur"=>$mail_utilisateur,
                "login_utilisateur"=>$login_utilisateur,
                "telephone_utilisateur"=>$telephone_utilisateur,
                "role_utilisateur"=>$role_utilisateur,
                "statut_utilisateur"=>$statut_utilisateur,
                "photos_utilisateur"=>$photos_utilisateur,
                "mdp_utilisateur"=>sha1($login_utilisateur)
            );
            $this->db->trans_start();
            $this->Utilisateur_model->add($data_users);
            if ($photos_utilisateur != ""){
                $photos_utilisateur = deplace_image("/uploads/variation/",'/assets/img/utilisateur/',$this->Utilisateur_model->get_last(),$photos_utilisateur);
                $this->Utilisateur_model->edit(["photos_utilisateur"=>$photos_utilisateur],["id"=>$this->Utilisateur_model->get_last()]);
            }
            $this->db->trans_complete();

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $cle ="erreur";
                $msg = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
                $this->db->trans_commit();
                $msg = "Enregistrement effectué avec succès!";
            }
        }

        $data = array(
            "cle"=>$cle,
            "msg"=>$msg
        );
        echo json_encode($data);
    }

    public function EditAction(){
        $cle = "ok";
        $nom_utilisateur = $this->input->post('nom_utilisateur');
        $mail_utilisateur = $this->input->post('mail_utilisateur');
        $login_utilisateur = $this->input->post('login_utilisateur');
        $telephone_utilisateur = $this->input->post('telephone_utilisateur');
        $role_utilisateur = $this->input->post('role_utilisateur');
        $photos_utilisateur = $this->input->post('photos_utilisateur');
        $statut_utilisateur = $this->input->post('statut_utilisateur');
        $id = $this->input->post('id');

        $count_login = $this->Utilisateur_model->count(array("login_utilisateur"=>$login_utilisateur));
        $count_mail = $this->Utilisateur_model->count(array("mail_utilisateur"=>$mail_utilisateur));
        $get = $this->Utilisateur_model->getSimple(array("id"=>$id))[0];
        $msg ="";
        if ($count_login > 0 && $get->login_utilisateur != $login_utilisateur){
            $cle ="erreur";
            $msg ="CE LOGIN EST DEJA ENREGISTRER";
        }
        if ($count_mail > 0 && $get->mail_utilisateur != $mail_utilisateur){
            $cle ="erreur";
            $msg =" LE MAIL EST DEJA ENREGISTRER";
        }

        if ($cle =="ok"){
            //insertion dans la table utilisateur
            if ($get->photos_utilisateur != $photos_utilisateur && $photos_utilisateur != ""){
                $photos_utilisateur = deplace_image("/uploads/variation/",'/assets/img/utilisateur/',$id,$photos_utilisateur);
            }
            $data_users = array(
                "nom_utilisateur"=>$nom_utilisateur,
                "mail_utilisateur"=>$mail_utilisateur,
                "login_utilisateur"=>$login_utilisateur,
                "telephone_utilisateur"=>$telephone_utilisateur,
                "role_utilisateur"=>$role_utilisateur,
                "statut_utilisateur"=>$statut_utilisateur,
                "photos_utilisateur"=>$photos_utilisateur,
            );
            $this->db->trans_start();
            $this->Utilisateur_model->edit($data_users,["id"=>$id]);
            $this->db->trans_complete();

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $cle ="erreur";
                $msg = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
                $this->db->trans_commit();
                $msg = "Enregistrement effectué avec succès!";
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
        $data = $this->Utilisateur_model->getSimple(["id" => $id]);
        $data[0]->mdp = "";
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
            $critere = "( nom_utilisateur LIKE '%" . $filter . "%' OR ";
            $critere .= " role_utilisateur LIKE '%" . $filter . "%' OR ";
            $critere .= " statut_utilisateur LIKE '%" . $filter . "%' OR ";
            $critere .= " login_utilisateur LIKE '%" . $filter . "%' ) ";
        }
        $critere .= " AND role_utilisateur != 'dévéloppeur' AND etat_suppression = 0 ";

        if (!empty($statut)) {
            $critere .= " AND statut_utilisateur = '$statut' ";
        }

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Utilisateur_model->count($critere);
        $data_parent["total"] = $total;

        $last_page = ($total / $per_page);
        $total_page = $last_page - (int)$last_page;
        $last_page = $total_page > 0 ? ((int)$last_page) + 1 : $last_page;

        $data_parent["last_page"] = $last_page;

        $from = ($current_page - 1) * $per_page;
        $to = $current_page * $per_page;

        $data_parent["from"] = $from;
        $data_parent["to"] = $to;

        //$data_parent["next_page_url"] = "xxx";
        //$data_parent["prev_page_url"] = "";

        $data = $this->Utilisateur_model->get($critere, $champs, $ordre, $from, $per_page);
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

        $nbrnom = $this->Utilisateur_model->count(["nom" => $nom]);
        $nbrlogin = $this->Utilisateur_model->count(["login" => $login]);

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
            $this->Utilisateur_model->add($data);


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

        $unom = $this->Utilisateur_model->getSimple(["nom" => $nom]);
        $ulogin = $this->Utilisateur_model->getSimple(["login" => $login]);

        $current_users = $this->Utilisateur_model->getSimple(["id" => $id])[0];

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
            $this->Utilisateur_model->edit($data, ["id" => $id]);

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
        $get = $this->Utilisateur_model->getSimple(array("id"=>$id))[0];


            $this->db->trans_start();
            $this->Utilisateur_model->edit(array("etat_suppression" => 1), ["id" => $id]);
            $this->db->trans_complete();
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $cle = "erreur";
                $message = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
            //    if(file_exists(getcwd().'/assets/img/utilisateur/'.$get->photos_utilisateur)){
            //        $file =getcwd().'/assets/img/utilisateur/'.$get->photos_utilisateur;
            //        unlink($file);
            //    }
                $this->db->trans_commit();
                $message = "Suppression effectué!";
            }

        $output = array(
            'cle' => $cle,
            'msg' => $message
        );
        echo json_encode($output);
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
        $duree_cookie = '7200';

        $nbr = $this->Utilisateur_model->count(array(
            'login_utilisateur' => $login,
            'mdp_utilisateur' => sha1($password),
            'statut_utilisateur' => 'Activer',
            'etat_suppression' => 0
        ));

        if ($nbr > 0) {
            $users = $this->Utilisateur_model->getSimple(array('login_utilisateur' => $login, 'mdp_utilisateur' => sha1($password)));

            set_cookie('admin_ctht_id', $users[0]->id, $duree_cookie);
            set_cookie('admin_ctht_login', $users[0]->login_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_nom', $users[0]->nom_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_mail', $users[0]->mail_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_role', $users[0]->role_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_photo', $users[0]->photos_utilisateur, $duree_cookie);
            set_cookie('users_ctht_token', sha1(TOKEN_PREFIX . $users[0]->id . TOKEN_SUFIX), $duree_cookie);

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

    public function logout()
    {
        delete_cookie("admin_ctht_id");
        delete_cookie("admin_ctht_login");
        delete_cookie("admin_ctht_nom");
        delete_cookie("admin_ctht_mail");
        delete_cookie("admin_ctht_role");
        delete_cookie("admin_ctht_photo");

        echo json_encode(["statut" => "deconnected"]);
    }

    public function getUserConfig()
    {
        $data = [];
        // echo date("H:i:s")."</br>";
        $users = $this->Utilisateur_model->getSimple(["id" => get_cookie("admin_ctht_id")]);
        if (count($users) > 0) {
            // $users = $users[0];
            $duree_cookie = '7200';

            set_cookie('admin_ctht_id', $users[0]->id, $duree_cookie);
            set_cookie('admin_ctht_login', $users[0]->login_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_nom', $users[0]->nom_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_mail', $users[0]->mail_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_role', $users[0]->role_utilisateur, $duree_cookie);
            set_cookie('admin_ctht_photo', $users[0]->photos_utilisateur, $duree_cookie);
            
        }
        echo json_encode($data);
    }

}
