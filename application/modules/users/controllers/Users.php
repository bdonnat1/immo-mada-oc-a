<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MX_Controller
{

    var $data;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users/Users_model');
        // $this->load->library('securite');
    }

    // private function cors() {

    //     // Allow from any origin
    //     if (isset($_SERVER['HTTP_ORIGIN'])) {
    //         // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    //         // you want to allow, and if so:
    //         header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    //         header('Access-Control-Allow-Credentials: true');
    //         header('Access-Control-Max-Age: 86400');    // cache for 1 day
    //     }

    //     // Access-Control headers are received during OPTIONS requests
    //     if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    //         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    //             // may also be using PUT, PATCH, HEAD etc
    //             header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    //         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    //             header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //         exit(0);
    //     }


    // }

    // public function test_securite(){
    //     $this->cors();
    //     echo "<pre>";
    //     var_dump($this->input->get_request_header('Content-Type',false));
    //     echo "</pre>";
    // }
    public function xxx()
    {
        $page = (215 / 25);
        $total_page = $page - (int)$page;
        $page = $total_page > 0 ? ((int)$page) + 1 : $page;
        echo $page;
    }
    public function fetchData()
    {
        $data = [];

        echo json_encode($data);
    }
    /**
     * recuperer la list de toutes les utilisateurs
     */
    public function fetchAll()
    {
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
            $critere = "( nom LIKE '%" . $filter . "%' OR ";
            $critere .= " role LIKE '%" . $filter . "%' OR ";
            $critere .= " statut LIKE '%" . $filter . "%' OR ";
            $critere .= " login LIKE '%" . $filter . "%' ) ";
        }
        $critere .= " AND role != 'dévéloppeur' AND etat_suppression = 0 ";

        $users_role = get_cookie("users_smcm_role");
        $users_id = get_cookie("users_smcm_id");

        if ($users_role != 'ADMIN' && $users_role != 'dévéloppeur') {
            $critere .= " AND id = $users_id ";
        }
        if (!empty($statut)) {
            $critere .= " AND statut = '$statut' ";
        }

        $data_parent = array();

        $data_parent["current_page"] = $current_page;
        $data_parent["per_page"] = $per_page;
        $total = $this->Users_model->count($critere);
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

        $data = $this->Users_model->get($critere, $champs, $ordre, $from, $per_page);
        //$data_parent = array("data" => $data);
        $data_parent["data"] = $data;

        echo json_encode($data_parent);
    }

    public function get($id = "")
    {
        $data = $this->Users_model->getSimple(["id" => $id]);
        $data[0]->mdp = "";
        echo json_encode($data);
    }

    public function addaction()
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

        $nbrnom = $this->Users_model->count(["nom" => $nom]);
        $nbrlogin = $this->Users_model->count(["login" => $login]);

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
            $this->Users_model->add($data);


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

    public function editaction()
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

        $unom = $this->Users_model->getSimple(["nom" => $nom]);
        $ulogin = $this->Users_model->getSimple(["login" => $login]);

        $current_users = $this->Users_model->getSimple(["id" => $id])[0];

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
            $this->Users_model->edit($data, ["id" => $id]);

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
        $error = [];
        $message = "";


        if (empty($error)) {
            $this->db->trans_start();
            $this->Users_model->edit(array("etat_suppression" => 1), ["id" => $id]);
            $this->db->trans_complete();
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $error[] = "Une erreur est survenue lors de l'operation.</br>Veuillez reesayer ulterieurement";
            } else {
                $this->db->trans_commit();
                $message = "Suppression effectué!";
            }
        }
        $output = array(
            'error' => implode(",<br/> ", $error),
            'message' => $message
        );
        echo json_encode($output);
    }

    public function getUserConfig()
    {
        $data = [];
        // echo date("H:i:s")."</br>";
        $users = $this->Users_model->getSimple(["id" => get_cookie("users_smcm_id")]);
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
        //var_dump($this->input->post());
        $login = $this->input->post("login");
        $password = $this->input->post("password");
        $remember = $this->input->post("remember");

        $error = "";
        $message = "";
        $key_user = "";
        $is_set_mdp = true;
        $duree_cookie = '31536000';

        $nbr = $this->Users_model->count(array(
            'login' => $login,
            'mdp' => sha1($password),
            'statut' => 'ACTIVE',
            'etat_suppression' => 0
        ));

        if ($nbr > 0) {
            $users = $this->Users_model->getSimple(array('login' => $login, 'mdp' => sha1($password)));


            set_cookie('users_smcm_id', $users[0]->id, $duree_cookie);
            set_cookie('users_smcm_role', $users[0]->role, $duree_cookie);
            set_cookie('users_smcm_nom', $users[0]->nom, $duree_cookie);
            set_cookie('users_smcm_statut', $users[0]->statut, $duree_cookie);
            set_cookie('users_smcm_token', sha1(TOKEN_PREFIX . $users[0]->id . TOKEN_SUFIX), $duree_cookie);

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

    public function activerTva()
    {
        $error = "";
        $message = "";

        $users_role = get_cookie("users_smcm_role");
        $users_id = get_cookie("users_smcm_id");
        $duree_cookie = '31536000';

        if ($users_role == 'ADMIN' || $users_role == 'dévéloppeur' || $users_role == 'COMPTABLE') {
            if (empty(get_cookie("users_smcm_tva")) || get_cookie("users_smcm_tva") == 'INACTIVE') {
                set_cookie("users_smcm_tva", "ACTIVE", $duree_cookie);
            } else {
                set_cookie("users_smcm_tva", "INACTIVE", $duree_cookie);
            }

            $message = "Enregistrement effectué avec succès!";
        } else {
            $error = "Vous n'êtes-pas autorisés à effectuer cette operation";
        }
        $output = array(
            'error' => $error,
            'message' => $message
        );
        echo json_encode($output);
    }

    public function logout()
    {
        delete_cookie("users_smcm_id");
        delete_cookie("users_smcm_role");
        delete_cookie("users_smcm_nom");
        delete_cookie("users_smcm_statut");

        echo json_encode(["statut" => "deconnected"]);
    }

    public function login()
    {
        $data = [];
        $this->load->view("login/index", $data);
    }
}
