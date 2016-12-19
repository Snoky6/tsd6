<?php

class Outfit_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('outfit');
        $outfits = $query->result();
        return $outfits;
    }

    function getAllAdmin() {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief =', 0);

        $query = $this->db->get('outfit');
        $outfits = $query->result();

        $this->load->model('outfitartikel_model');

        $counter = 0;
        foreach ($outfits as $outfit) {            
            $outfit->artikels = $this->outfitartikel_model->getAllByOutfitId($outfit->id);
            $counter++;
        }

        return $outfits;
    }
    
    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('outfit');
        $outfit = $query->row();
        $this->load->model('outfitartikel_model');
        $outfit->artikels = $this->outfitartikel_model->getAllByOutfitId($outfit->id);
        $this->load->model('outfitfoto_model'); 
        $outfit->fotos = $this->outfitfoto_model->getAllByOutfitID($outfit->id);
        return $outfit;
    }
    
    function getAdmin($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('outfit');

        $outfit = $query->row();
        
        $this->load->model('outfitfoto_model');        
       
        // EXTRA FOTOS OPVRAGEN 8/02
        $outfit->extraFotos = $this->outfitfoto_model->getAllByOutfitID($outfit->id);

        return $outfit;
    }
    
    function getLastId() {
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get('outfit');
        $outfit = $query->row();
        
        if ($outfit == null) {
            return 0;
        }
        
        return $outfit->id;
    }

    function insert($outfit) {
        $this->db->insert('outfit', $outfit);
        return $this->db->insert_id();
    }

    function update($outfit) {
        $this->db->where('id', $outfit->id);
        $this->db->update('outfit', $outfit);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('outfit');
    }

}

?>