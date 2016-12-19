<?php

class Outfitfoto_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('outfitfoto');
        $outfitfotos = $query->result();
        return $outfitfotos;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('outfitfoto');
        return $query->row();
    }

    function getAllByOutfitID($outfitId) {
        $this->db->where('outfitId', $outfitId);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('outfitfoto');
        $outfitfotos = $query->result();
        return $outfitfotos;
    }    

    function insert($outfitfoto) {
        $this->db->insert('outfitfoto', $outfitfoto);
        return $this->db->insert_id();
    }

    function update($outfitfoto) {
        $this->db->where('id', $outfitfoto->id);
        $this->db->update('outfitfoto', $outfitfoto);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('outfitfoto');
    }

}

?>