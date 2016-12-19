<?php

class Outfitartikel_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);

        $query = $this->db->get('outfitartikel');
        $outfitartikels = $query->result();
        return $outfitartikels;
    }
    
    function getAllByOutfitId($outfitId) {
        $this->db->order_by('id', 'asc');        
        $this->db->where('outfitId',$outfitId);
        $query = $this->db->get('outfitartikel');
        $outfitartikels = $query->result();
        
        $this->load->model('artikel_model');
        
        foreach ($outfitartikels as $outfitartikel) {
            $outfitartikel->artikel = $this->artikel_model->get($outfitartikel->artikelId); 
        }
        
        return $outfitartikels;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('outfitartikel');
        return $query->row();
    }

    function insert($outfitartikel) {
        $this->db->insert('outfitartikel', $outfitartikel);
        return $this->db->insert_id();
    }

    function update($outfitartikel) {
        $this->db->where('id', $outfitartikel->id);
        $this->db->update('outfitartikel', $outfitartikel);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('outfitartikel');
    }

}

?>