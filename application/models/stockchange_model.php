<?php

class Stockchange_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('stockchange');
        $stockchanges = $query->result();
        return $stockchanges;
    }

    function getAllWithData() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('stockchange');
        $stockchanges = $query->result();

        $this->load->model('artikel_model');
        foreach ($stockchanges as $stockchange) {
            $stockchange->artikel = $this->artikel_model->getSingleArtikelMetMaatAndStock($stockchange);
        }

        return $stockchanges;
    }

    function getByBarcodeOrTimespanWithData($barcode, $startdate, $enddate) {
        $this->db->select('s.*');
        $this->db->from('stockchange s');
                
        if ($barcode != NULL && $barcode != '') {
            $this->db->join('artikel a', 'a.id = s.artikelId');
            $this->db->where('a.barcode = "' . $barcode . '"');
        } 
        if ($startdate != NULL && $startdate != '' && $enddate != NULL && $enddate != '' ) {            
            $this->db->where('s.datum >= "' . $startdate . '" AND ' . 's.datum <= "' . $enddate . '"');
        }
        
        $query = $this->db->get();
        $stockchanges = $query->result();
        
        $this->load->model('artikel_model');
        foreach ($stockchanges as $stockchange) {
            $stockchange->artikel = $this->artikel_model->getSingleArtikelMetMaatAndStock($stockchange);
        }

        return $stockchanges;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('stockchange');
        return $query->row();
    }

    function insert($stockchange) {
        $this->db->insert('stockchange', $stockchange);
        return $this->db->insert_id();
    }

    function update($stockchange) {
        $this->db->where('id', $stockchange->id);
        $this->db->update('stockchange', $stockchange);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('stockchange');
    }

}

?>