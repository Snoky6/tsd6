<?php

class Kortingcode_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('kortingcode');
        $kortingcodes = $query->result();
        return $kortingcodes;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('kortingcode');
        return $query->row();
    }
    
    function getByCode($code) {
        $this->db->where('code', $code);
        $query = $this->db->get('kortingcode');
        return $query->row();
    }

    function insert($kortingcode) {
        $this->db->insert('kortingcode', $kortingcode);
        return $this->db->insert_id();
    }

    function update($kortingcode) {
        $this->db->where('id', $kortingcode->id);
        $this->db->update('kortingcode', $kortingcode);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('kortingcode');
    }

    function checkIfKortingcodeExists($code) {
        $this->db->where('code', $code);
        //$this->db->where('archief =', 0);
        $query = $this->db->get('kortingcode');

        $kortingcode = $query->row();

        if (isset($kortingcode->id)) {
            // checken of nog geldig            
            if ($kortingcode->multiUse == 1) {
                // checken of procent of vast bedrag
                if ($kortingcode->kortingProcent == null) {
                    return $kortingcode->kortingBedrag . " EURO"; // prijs korting
                } else {                    
                    return $kortingcode->kortingProcent . " %";
                }
            } else {
                if ($kortingcode->gebruikt < 1) {
                    if ($kortingcode->kortingProcent == null) {
                        return $kortingcode->kortingBedrag . " EURO"; // prijs korting
                    } else {
                        return $kortingcode->kortingProcent . " %";
                    }
                } else {
                    return "Deze code is niet meer geldig!";
                }
            } 
        } else {
            return "false";
        }
    }
    
    function getByCodeAndValidateCode($code) {
        $this->db->where('code', $code);
        //$this->db->where('archief =', 0);
        $query = $this->db->get('kortingcode');

        $kortingcode = $query->row();

        if (isset($kortingcode->id)) {
            // checken of nog geldig            
            if ($kortingcode->multiUse == 1) {
                // checken of procent of vast bedrag
                return $kortingcode;
            } else {
                if ($kortingcode->gebruikt <= 1) {
                    return $kortingcode;
                } else {
                    return null;//"Deze code is niet meer geldig!";
                }
            } 
        } else {
            return null;
        }
    }

}

?>