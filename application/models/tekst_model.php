<?php

class Tekst_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');

        $query = $this->db->get('tekst');
        $maten = $query->result();
        return $maten;
    }
    
    function getAllByPage($page) {
        $this->db->order_by('id', 'asc');
        $this->db->where('pagina =', $page);

        $query = $this->db->get('tekst');
        $maten = $query->result();
        return $maten;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tekst');
        return $query->row();
    }

    function insert($tekst) {
        $this->db->insert('tekst', $tekst);
        return $this->db->insert_id();
    }

    function update($tekst) {
        $this->db->where('id', $tekst->id);
        $this->db->update('tekst', $tekst);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('tekst');
    }

}

?>