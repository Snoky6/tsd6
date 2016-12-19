<?php

class ArtikelMaat_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');

        $query = $this->db->get('artikelmaat');
        $artikelMaten = $query->result();
        return $artikelMaten;
    }

    function getAllByArtikelId($artikelId) {
        $this->db->order_by('id', 'asc');
        $this->db->where('artikelId', $artikelId);
        $query = $this->db->get('artikelmaat');
        $artikelMaten = $query->result();

        $this->load->model('maat_model');

        foreach ($artikelMaten as $artikelMaat) {
            $artikelMaat->maat = $this->maat_model->get($artikelMaat->maatId);
        }

        return $artikelMaten;
    }

    function getByArtikelIdAndMaatId($artikelId, $maatId) {
        $this->db->order_by('id', 'asc');
        $this->db->where('artikelId', $artikelId);
        $this->db->where('maatId', $maatId);
        $query = $this->db->get('artikelmaat');
        $artikelMaat = $query->row();
        return $artikelMaat;
    }

    function getAllWithVoorraadByArtikelId($artikelId) {
        $this->db->order_by('id', 'asc');
        $this->db->where('artikelId', $artikelId);
        $this->db->where('voorraad >', 0);
        $query = $this->db->get('artikelmaat');
        $artikelMaten = $query->result();

        $this->load->model('maat_model');

        foreach ($artikelMaten as $artikelMaat) {
            $artikelMaat->maat = $this->maat_model->get($artikelMaat->maatId);
        }

        return $artikelMaten;
    }

    function getLastWithLowStock($amount) {
        $this->db->limit($amount);
        $this->db->where('voorraad !=', 0);
        $this->db->order_by('voorraad', 'desc');
        $query = $this->db->get('artikelmaat');
        $artikelMaten = $query->result();
        return $artikelMaten;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('artikelmaat');
        return $query->row();
    }

    function insert($artikelMaat) {
        $this->db->insert('artikelmaat', $artikelMaat);
        return $this->db->insert_id();
    }

    function update($artikelMaat, $bestellingId = 0) {        
        /* bij update ook toevoegen aan checkstock database */
        $this->db->where('id', $artikelMaat->id);
        $query = $this->db->get('artikelmaat');
        $artikelmaatdb = $query->row();

        if ($artikelmaatdb->voorraad != $artikelMaat->voorraad) {
            /* voorraad is veranderd */
            $this->load->model('stockchange_model');
            $this->load->model('artikel_model');
            $stockchange = new stdClass();
            $stockchange->artikelMaatId = $artikelMaat->id;
            $stockchange->artikelId = $artikelMaat->artikelId;
            
            $artikel = $this->artikel_model->getSolo($artikelMaat->artikelId);
            $stockchange->prijs = $artikel->prijs;
            $stockchange->korting = $artikel->korting;
            
            $stockchange->maatId = $artikelMaat->maatId;
            $stockchange->datum = date('Y-m-d H:i:s a');
            /* voor updates via online bestelling is er een nieuwe variable meegegeven (bestellingId = x) */
            if ($bestellingId == 0) {
                $stockchange->bestellingId = NULL;
            } else {
                $stockchange->bestellingId = $bestellingId;
            }

            if ($artikelmaatdb->voorraad > $artikelMaat->voorraad) {
                /* minder voorraad => verkocht */
                $stockchange->sold = TRUE;
            } else {
                /* meer voorraad => stock toegevoegd */
                $stockchange->sold = FALSE;
            }
            $stockchange->aantal = $artikelMaat->voorraad - $artikelmaatdb->voorraad;
            $this->stockchange_model->insert($stockchange);
        }        
        
        $this->db->where('id', $artikelMaat->id);
        $this->db->update('artikelmaat', $artikelMaat);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('artikelmaat');
    }

    function getAmountInStock($id, $maatId) {
        $this->db->where('artikelId', $id);
        $this->db->where('maatId', $maatId);
        $query = $this->db->get('artikelmaat');

        $artikelMaat = $query->row();


        return $artikelMaat->voorraad;
    }

}

?>