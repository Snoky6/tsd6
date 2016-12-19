<?php

class Bezoeker_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('bezoeker');
        $bezoekers = $query->result();
        return $bezoekers;
    }

    function getAllCount() {
        $this->db->order_by('id', 'asc');
        // for our grahps we only need this year and last year so let's get that out of the way        
        $currentYear = date("Y");
        $lastYear = $currentYear - 1;

        $where = "YEAR(bezoekdatum) >= '" . $lastYear . "'";
        //$where = "id > 10";
        //$this->db->query("SELECT count(*) FROM bezoeker WHERE " . $where . ";");
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoeker WHERE " . $where . ";")->row_array()["COUNT(*)"];      
        
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
        //$this->db->query("SELECT count(*) FROM bezoeker WHERE " . $where . ";");
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoeker WHERE " . $where . ";")->row_array()["COUNT(*)"];      

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
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoeker WHERE " . $where . ";")->row_array()["COUNT(*)"]; 
        
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
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoeker WHERE " . $where . ";")->row_array()["COUNT(*)"];      

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
        /*$this->db->where($where);
        $query = $this->db->get('bezoeker');
        $bezoekers = $query->result();
        return $bezoekers;*/
        $totalbezoekers = $this->db->query("SELECT COUNT(*) FROM bezoeker WHERE " . $where . ";")->row_array()["COUNT(*)"]; 
        
        $bezoekers = new stdClass();
        $bezoekers->totaal = $totalbezoekers;
        $bezoekers->daydata = array();        

        for ($x = 0; $x <= 24; $x++) {
            $bezoekers->daydata[$x] = $this->getBezoekersHour($x, $date, $endOfDay);            
        }

        return $bezoekers;
    }

    function getLastBezoeker() {
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get('bezoeker');
        return $query->row();
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bezoeker');
        return $query->row();
    }

    function insert($bezoeker) {
        $this->db->insert('bezoeker', $bezoeker);
        return $this->db->insert_id();
    }

    function update($bezoeker) {
        $this->db->where('id', $bezoeker->id);
        $this->db->update('bezoeker', $bezoeker);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('bezoeker');
    }

}

?>