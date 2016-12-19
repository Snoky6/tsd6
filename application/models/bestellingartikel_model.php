<?php

class Bestellingartikel_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);

        $query = $this->db->get('bestellingartikel');
        $bestellingartikels = $query->result();
        return $bestellingartikels;
    }
    
    function getAllWithArtikelByBestellingId($bestellingId) {
        $this->db->order_by('id', 'asc');
        $this->db->where('bestellingId', $bestellingId);
        $query = $this->db->get('bestellingartikel');
        $bestellingartikels = $query->result();
        
        $this->load->model('verkochtartikel_model');
        $this->load->model('maat_model');
        
        foreach ($bestellingartikels as $bestellingartikel) {
            $bestellingartikel->artikel = $this->verkochtartikel_model->get($bestellingartikel->verkochtArtikelId);            
            $bestellingartikel->maat = $this->maat_model->get($bestellingartikel->maatId);            
        } 
        
        return $bestellingartikels;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestellingartikel');
        return $query->row();
    }

    function insert($bestellingartikel) {
        $this->db->insert('bestellingartikel', $bestellingartikel);
        return $this->db->insert_id();
    }

    function update($bestellingartikel) {
        $this->db->where('id', $bestellingartikel->id);
        $this->db->update('bestellingartikel', $bestellingartikel);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('bestellingartikel');
    }

}

?>