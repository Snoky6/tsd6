<?php

class Maat_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);

        $query = $this->db->get('maat');
        $maten = $query->result();
        return $maten;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('maat');
        return $query->row();
    }

    function insert($maat) {
        $this->db->insert('maat', $maat);
        return $this->db->insert_id();
    }

    function update($maat) {
        $this->db->where('id', $maat->id);
        $this->db->update('maat', $maat);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('maat');
    }

}

?>