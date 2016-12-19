<?php

class Nieuwsbriefinschrijving_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');

        $query = $this->db->get('nieuwsbriefinschrijving');
        $maten = $query->result();
        return $maten;
    }    

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('nieuwsbriefinschrijving');
        return $query->row();
    }

    function insert($nieuwsbriefinschrijving) {
        $this->db->insert('nieuwsbriefinschrijving', $nieuwsbriefinschrijving);
        return $this->db->insert_id();
    }

    function update($nieuwsbriefinschrijving) {
        $this->db->where('id', $nieuwsbriefinschrijving->id);
        $this->db->update('nieuwsbriefinschrijving', $nieuwsbriefinschrijving);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('nieuwsbriefinschrijving');
    }

}

?>