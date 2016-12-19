<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Onlangsbekeken_model extends CI_model {

    function __construct() {
        parent::__construct();
    }    
    
    function haaloponlangsbekeken() {
        if (!$this->session->userdata('onlangsbekekenartikels')) {
            return array();
        } else {
            return $this->session->userdata('onlangsbekekenartikels');
        }
    }    

    function voegtoe($artikelId) {        
        $this->load->model('artikel_model');
        $artikel = $this->artikel_model->getSolo($artikelId);
        
        $onlangsbekekenartikels = null;
        $onlangsbekekenartikels = $this->haaloponlangsbekeken();        

        // minder waarden voor de sessie kleiner te maken
        $onlangsbekekenartikel = new stdClass();        
        $onlangsbekekenartikel->id = $artikelId;
        $onlangsbekekenartikel->imagePath = $artikel->imagePath;
        
        $zaterin = "nee";
        if (isset($onlangsbekekenartikels) && $onlangsbekekenartikels != NULL) {
            foreach ($onlangsbekekenartikels as $art) {
                if ($art->id == $artikelId) {
                    // zit al in karretje
                    $zaterin = "ja";                    
                }
            }
        } else {
            
        }

        if ($zaterin == "nee") {
            // zat nog niet in, dus toevoegen           
            array_push($onlangsbekekenartikels, $onlangsbekekenartikel);
        }
        
        if (count($onlangsbekekenartikels) > 4) {
            // maar 4 laatste bijhouden
            $onlangsbekekenartikels = array_splice($onlangsbekekenartikels,1,4);
        }

        $this->session->set_userdata('onlangsbekekenartikels', $onlangsbekekenartikels);
    }
    

    function leeg() {
        $this->session->unset_userdata('onlangsbekekenartikels');
    } 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */