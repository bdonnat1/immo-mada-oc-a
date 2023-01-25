<?php
class Panierclient_model extends CI_Model{
    var  $table_name;
    var  $table_rq;
    public function __construct()
    {
        $this->load->database();
        $this->table_name= 'panier_client';
        $this->table_rq= 'rq_panier_client';


    }
    /**
     * Retourner la liste des produit_photos
     * @param type $param
     * @param type $orderBy
     * @param type $order
     * @return type
     */

    public function get($param = array(), $orderBy='id', $order = 'asc', $from = '', $to = '') {
        $this->db->select($this->table_rq.'.*');
        $this->db->from($this->table_rq);
        $this->db->where($param);
        $this->db->order_by($orderBy, $order);
        $this->db->limit($to, $from);
        $query = $this->db->get();
        return $query->result();
    }
    public function getproduit()
    {
        $this->db->select('*');
        $this->db->from($this->table_rq);
        $this->db->where('etat_suppression', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function getSimple($param = array(), $orderBy='id', $order = 'asc') {
        $this->db->select('*');
        $this->db->from($this->table_rq);
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
        $this->db->from($this->table_name);
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

    public function get_last_model() {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
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
