<?php

class Bezoekerhit_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('bezoekerhit');
        $bezoekerhits = $query->result();
        return $bezoekerhits;
    }
    
    
    function getAllCount() {
        $this->db->order_by('id', 'asc');
        // for our grahps we only need this year and last year so let's get that out of the way        
        $currentYear = date("Y");
        $lastYear = $currentYear - 1;

        $where = "YEAR(bezoekdatum) >= '" . $lastYear . "'";
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoekerhit WHERE " . $where . ";")->row_array()["COUNT(*)"];      

        $bezoekers = new stdClass();
        $bezoekers->totaal = $totalbezoekers;
        $bezoekers->monthdata = array();
        $bezoekers->lastyearmonthdata = array();

        for ($x = 1; $x <= 12; $x++) {
            $bezoekers->monthdata[$x] = $this->getBezoekersMonth($x, $currentYear);
            $bezoekers->lastyearmonthdata[$x] = $this->getBezoekersMonth($x, $lastYear);
        }

        return $bezoekers;
    }

    
    function getBezoekersMonth($month, $year) {
        $this->db->order_by('id', 'asc');

        $where = "YEAR(bezoekdatum) = '" . $year . "' AND MONTH(bezoekdatum) = '" . $month . "'";
        $this->db->query("SELECT count(*) FROM bezoekerhit WHERE " . $where . ";");
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoekerhit WHERE " . $where . ";")->row_array()["COUNT(*)"];      

        //$query = $this->db->get('bezoeker');
        //$bezoekers = $query->result();
        //return $bezoekers;
        return $totalbezoekers;
    }

    function getBezoekersVandaag() {
        $this->db->order_by('id', 'asc');
        $where = "bezoekdatum > '" . date('Y-m-d 00:00:00') . "' AND " . "bezoekdatum < '" . date('Y-m-d 23:59:59') . "'";
        /*$this->db->where($where);
        $query = $this->db->get('bezoeker');
        $bezoekers = $query->result();
        return $bezoekers;*/
        //$this->db->query("SELECT count(*) FROM bezoeker WHERE " . $where . ";");
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoekerhit WHERE " . $where . ";")->row_array()["COUNT(*)"]; 
        
        $bezoekers = new stdClass();
        $bezoekers->totaal = $totalbezoekers;
        $bezoekers->daydata = array();        

        for ($x = 0; $x <= 24; $x++) {
            $bezoekers->daydata[$x] = $this->getBezoekersHour($x, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));            
        }

        return $bezoekers;
    }
    
    function getBezoekersHour($hour, $daystart, $dayend) {
        $this->db->order_by('id', 'asc');

        $where = "bezoekdatum > '" . $daystart . "' AND " . "bezoekdatum < '" . $dayend . "' AND HOUR(bezoekdatum) = " . $hour . "";
        //$this->db->query("SELECT count(*) FROM bezoeker WHERE " . $where . ";");
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoekerhit WHERE " . $where . ";")->row_array()["COUNT(*)"];      

        //$query = $this->db->get('bezoeker');
        //$bezoekers = $query->result();
        //return $bezoekers;
        return $totalbezoekers;
    }

    function getBezoekersByYmdDate($date) {
        $this->db->order_by('id', 'asc');
       
        $date1 = str_replace('-', '/', $date);
        $endOfDay = date('Y-m-d 23:59:59', strtotime($date1));

        $where = "bezoekdatum > '" . $date . "' AND " . "bezoekdatum < '" . $endOfDay . "'";
        $this->db->where($where);
        $query = $this->db->get('bezoekerhit');
        $bezoekerhits = $query->result();
        return $bezoekerhits;
    }

    function getLastBezoeker() {
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get('bezoekerhit');
        return $query->row();
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bezoekerhit');
        return $query->row();
    }

    function insert($bezoekerhit) {
        $this->db->insert('bezoekerhit', $bezoekerhit);
        return $this->db->insert_id();
    }

    function update($bezoekerhit) {
        $this->db->where('id', $bezoekerhit->id);
        $this->db->update('bezoekerhit', $bezoekerhit);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('bezoekerhit');
    }

}

?>