<?php

class Setting_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('setting');
        $settings = $query->result();
        return $settings;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('setting');
        return $query->row();
    }    

    function insert($setting) {
        $this->db->insert('setting', $setting);
        return $this->db->insert_id();
    }

    function update($setting) {
        $this->db->where('id', $setting->id);
        $this->db->update('setting', $setting);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('setting');
    }

}

?>