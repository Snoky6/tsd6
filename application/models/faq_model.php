<?php

class Faq_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('faq');
        $faqs = $query->result();
        return $faqs;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('faq');
        return $query->row();
    }    

    function insert($faq) {
        $this->db->insert('faq', $faq);
        return $this->db->insert_id();
    }

    function update($faq) {
        $this->db->where('id', $faq->id);
        $this->db->update('faq', $faq);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('faq');
    }

}

?>