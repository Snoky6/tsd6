<?php

class Artikelfoto_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('artikelfoto');
        $artikelfotos = $query->result();
        return $artikelfotos;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('artikelfoto');
        return $query->row();
    }

    function getAllByArtikelID($artikelId) {
        $this->db->where('artikelId', $artikelId);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('artikelfoto');
        $artikelfotos = $query->result();
        return $artikelfotos;
    }

    function insert($artikelfoto) {
        $this->db->insert('artikelfoto', $artikelfoto);
        return $this->db->insert_id();
    }

    function update($artikelfoto) {
        $this->db->where('id', $artikelfoto->id);
        $this->db->update('artikelfoto', $artikelfoto);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('artikelfoto');
    }

}

?>