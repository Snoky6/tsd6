<?php

class Verkochtartikel_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('verkochtartikel');
        $verkochtartikels = $query->result();
        return $verkochtartikels;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('verkochtartikel');
        return $query->row();
    }

    function insert($verkochtartikel) {
        $this->db->insert('verkochtartikel', $verkochtartikel);
        return $this->db->insert_id();
    }

    function update($verkochtartikel) {
        $this->db->where('id', $verkochtartikel->id);
        $this->db->update('verkochtartikel', $verkochtartikel);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('verkochtartikel');
    }

}

?>