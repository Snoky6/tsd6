<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Onlangsbekeken extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $this->karretje();
    }

    private function haaloponlangsbekeken() {
        if (!$this->session->userdata('onlangsbekekenartikels')) {
            return array();
        } else {
            return $this->session->userdata('onlangsbekekenartikels');
        }
    }    

    public function voegtoe($artikelId) {        
        $this->load->model('artikel_model');
        $artikel = $this->artikel_model->getSolo($artikelId);
        
        $onlangsbekekenartikels = null;
        $onlangsbekekenartikels = $this->haaloponlangsbekeken();        

        // minder waarden voor de sessie kleiner te maken
        $onlangsbekekenartikel = new stdClass();        
        $onlangsbekekenartikel->artikelId = $artikelId;
        
        $zaterin = "nee";
        if (isset($onlangsbekekenartikels) && $onlangsbekekenartikels != NULL) {
            foreach ($onlangsbekekenartikels as $art) {
                if ($art->artikelId == $artikelId) {
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
    

    public function leeg() {
        $this->session->unset_userdata('onlangsbekekenartikels');
    } 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */