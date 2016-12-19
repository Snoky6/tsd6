<?php

class Persoon_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);

        $query = $this->db->get('persoon');
        $personen = $query->result();
        return $personen;
    }
    
    function getAllUnique() {        
        $query = $this->db->query("SELECT * FROM persoon where geboortedatum != '0000-00-00' AND YEAR(geboortedatum) <= (YEAR(NOW()) - 12)");

        //$query = $this->db->get('persoon');
        $personen = $query->result();
        return $personen;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('persoon');
        return $query->row();
    }

    function insert($persoon) {
        $this->db->insert('persoon', $persoon);
        return $this->db->insert_id();
    }

    function update($persoon) {
        $this->db->where('id', $persoon->id);
        $this->db->update('persoon', $persoon);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('persoon');
    }

}

?>