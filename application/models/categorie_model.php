<?php

class Categorie_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('naam', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);
        // enkel diegene pakken die geen hoofdcat hebben!!
        $query = $this->db->where('hoofdcategorieId IS NULL', null, false);
        $query = $this->db->get('categorie');
        $categorien = $query->result();
        return $categorien;
    }

    // voor menu
    function getAllWithSub() {
        $this->db->order_by('naam', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);
        $this->load->model('artikel_model');


        // enkel diegene pakken die geen hoofdcat hebben!!
        $query = $this->db->where('hoofdcategorieId IS NULL', null, false);
        $query = $this->db->get('categorie');
        $categorien = $query->result();

        $counter = 0;
        foreach ($categorien as $categorie) {
            $categorie->subcategorien = $this->getAllSubCategorien($categorie->id); //??
            // lege cats er uit halen LET OP: Een hoofdcat kan leeg zijn, kijken of de subs ook leeg zijn dan e slimme


            $artikelsAanwezig = $this->artikel_model->getAmountAvailableByCategorieId($categorie->id);
            if ($artikelsAanwezig < 1 && count($categorie->subcategorien) < 1) {
                unset($categorien[$counter]);
            }
            $counter++;
        }

        return $categorien;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('categorie');
        return $query->row();
    }

    function getHoofdCategorie($hoofdcategorieId) {
        $this->db->where('id', $hoofdcategorieId);
        $query = $this->db->get('categorie');
        return $query->row();
    }

    function getAllSubCategorien($id) {
        $this->db->order_by('naam', 'asc');
        $this->db->where('hoofdcategorieId', $id);
        $query = $this->db->get('categorie');
        $subcats = $query->result();

        $this->load->model('artikel_model');
        $counter = 0;
        foreach ($subcats as $subcat) {
            $artikelsAanwezig = $this->artikel_model->getAmountAvailableByCategorieId($subcat->id);
            if ($artikelsAanwezig < 1) {
                unset($subcats[$counter]);
            }
            $counter++;
        }

        return $subcats;
    }

    function getAllSubCategorienAdmin($id) {
        $this->db->order_by('naam', 'asc');
        $this->db->where('hoofdcategorieId', $id);
        $query = $this->db->get('categorie');
        $subcats = $query->result();

        $this->load->model('artikel_model');

        return $subcats;
    }

    function getByName($naam) {
        $this->db->where('naam', $naam);
        $query = $this->db->get('categorie');
        return $query->row();
    }

    function insert($categorie) {
        $this->db->insert('categorie', $categorie);
        return $this->db->insert_id();
    }

    function update($categorie) {
        $this->db->where('id', $categorie->id);
        $this->db->update('categorie', $categorie);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('categorie');
    }

    /*
     * Check which categories have stock, if they don't have articles in stock don't show them 
     * Again kids, this is why you should pay attention in SQL class.
     */

    function getAllWithSubSnel() {
        $query = $this->db->query("SELECT DISTINCT c.* FROM categorie c 
inner join artikel a on a.categorieId = c.id 
INNER join artikelmaat am ON a.id = am.artikelId 
WHERE a.archief = 0 AND (am.voorraad > 0 
       OR c.id IN (
           SELECT c2.hoofdcategorieId FROM categorie c2 
           INNER JOIN artikel a2 on a2.categorieId = c2.id 
           INNER join artikelmaat am2 ON a2.id = am2.artikelId 
           WHERE c2.hoofdcategorieId = c.id AND a2.archief = 0)) 
       AND c.hoofdcategorieId IS NULL ORDER BY `c`.`naam` ASC
");

        $categorien = $query->result();
       
        foreach ($categorien as $categorie) {
            $categorie->subcategorien = $this->getAllSubCategorienSnel($categorie->id);
        }

        return $categorien;
    }

    function getAllSubCategorienSnel($id) {
        $query = $this->db->query("SELECT DISTINCT c.* FROM categorie c 
inner join artikel a on a.categorieId = c.id 
INNER join artikelmaat am ON a.id = am.artikelId 
WHERE a.archief = 0 AND am.voorraad > 0 AND c.hoofdcategorieId = $id ORDER BY `c`.`naam` ASC");
        $subcats = $query->result();
        return $subcats;
    }

}

?>