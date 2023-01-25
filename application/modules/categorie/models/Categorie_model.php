<?php
class Categorie_model extends CI_Model{
    var  $table_name;
    public function __construct()
    {
      $this->load->database();
      $this->table_name= 'categorie';

    }
     /**
     * Retourner la liste des categorie
     * @param type $param
     * @param type $orderBy
     * @param type $order
     * @return type
     */

    public function getCategorie()
    {
        $this->db->select('categorie.*, (select count(id) from produit where produit.categorie_id = categorie.id and produit.etat_suppression = 0) AS nombreproduit');
        $this->db->from($this->table_name);
        $this->db->where('etat_suppression', 0);
        $query = $this->db->get();
        return $query->result();
    }
    public function get($param = array(), $orderBy='id', $order = 'asc', $from = '', $to = '') {
        $this->db->select($this->table_name.'.*, (select count(id) from produit where produit.categorie_id = categorie.id and produit.etat_suppression = 0) AS nombreproduit');
        $this->db->from($this->table_name);
        $this->db->where($param);
        $this->db->order_by($orderBy, $order);
        $this->db->limit($to, $from);
        $query = $this->db->get();
        return $query->result();
    }


    public function getSimple($param = array(), $orderBy='id', $order = 'asc') {
        $this->db->select('categorie.*, (select count(id) from produit where produit.categorie_id = categorie.id and produit.etat_suppression = 0) AS nombreproduit');
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


//    public function teste(){
//        $dbConn = mysqli_connect('localhost', 'root', 'root', 'roytuts') or die('MySQL connect failed. ' . mysqli_connect_error());
//
////select all rows from the category table
//        $result = mysqli_query($dbConn, "SELECT
//	category_id, category_name, category_link, parent_id, sort_order
//	FROM category
//	ORDER BY parent_id, sort_order, category_name");
//
////create a multidimensional array to hold a list of category and parent category
//        $category = array(
//            'categories' => array(),
//            'parent_cats' => array()
//        );
//
////build the array lists with data from the category table
//        while ($row = mysqli_fetch_assoc($result)) {
//            //creates entry into categories array with current category id ie. $categories['categories'][1]
//            $category['categories'][$row['category_id']] = $row;
//            //creates entry into parent_cats array. parent_cats array contains a list of all categories with children
//            $category['parent_cats'][$row['parent_id']][] = $row['category_id'];
//        }
//    }
}
