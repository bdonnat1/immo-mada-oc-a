<?php
class Boncommande_model extends CI_Model {

    var $table_name;
    var $rqtable_name;
    public function __construct(){
        $this->load->database();
        $this->table_name = 'bon_de_commande';
        $this->rqtable_name = 'rq_bon_de_commande';
    }

    /**
     * Retourner la liste des utilisateurs
     * @param type $param
     * @param type $orderBy
     * @param type $order
     * @return type
     */
    public function get($param = array(), $orderBy='nom', $order = 'asc', $from, $to) {
        $this->db->select($this->rqtable_name.'.*, CONCAT(nom_client, " ",prenom_client) as nom_prenom,DATE_FORMAT(date_bon_de_commande, "%d/%m/%Y") AS date_bon_de_commande_formatted');
        $this->db->from($this->rqtable_name);
        $this->db->where($param);
        $this->db->order_by($orderBy, $order);
        $this->db->limit($to, $from);
        $query = $this->db->get();
        return $query->result();
    }

    public function getSimple($param = array(), $orderBy='id', $order = 'asc') {
        $this->db->select('*,DATE_FORMAT(date_bon_de_commande, "%d/%m/%Y") AS date_bon_de_commande_formatted');
        $this->db->from($this->rqtable_name);
        $this->db->order_by($orderBy, $order);
        $this->db->where($param);
        $query = $this->db->get();
        return $query->result();
    }

    public function getSansMdp($param = array(), $orderBy='id', $order = 'asc') {
        $this->db->select('id,nom,login,role,statut,etat_suppression');
        $this->db->from($this->table_name);
        $this->db->order_by($orderBy, $order);
        $this->db->where($param);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Retourner le nombre de ligne
     * @param type $param
     * @return type
     */
    public function count($param = array()) {
        $this->db->select('COUNT(*) AS nbr');
        $this->db->from($this->rqtable_name);
        $this->db->where($param);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->nbr;
    }

    /**
     * Retourner la dernière id
     * @return type
     */
    public function get_last() {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->id;
    }


    /**
     * Ajout dans la base de données
     * Ajout de données
     * @param type $param
     */
    public function add($param = array()) {
        $this->db->insert($this->table_name, $param);
    }

    /**
     * Edition dans la base de données
     * @param type $param
     * @param type $critere
     */
    public function edit($param = array(), $critere = array()) {
        $this->db->update($this->table_name, $param, $critere);
    }

    /**
     * Suppression total d'une ligne
     * @param type $critere
     */
    public function delete($critere = array()) {
        $this->db->delete($this->table_name, $critere);
    }


}
