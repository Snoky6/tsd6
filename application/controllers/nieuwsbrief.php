<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nieuwsbrief extends CI_Controller {

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
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Nieuwsbrief';
        
        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'nieuwsbrief_inschrijven', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function inschrijven() {
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Nieuwsbrief';

        $naam = $this->input->post('naam');
        $email = $this->input->post('email');
        $nieuwsbriefinschrijving = new stdClass();
        $nieuwsbriefinschrijving->naam = $naam;
        $nieuwsbriefinschrijving->email = $email;

        $data['categorien'] = $this->loadCategorienForMenu();
        
        $this->load->model('nieuwsbriefinschrijving_model');
        $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);

        $data['ingeschreven'] = "Bedankt, je bent nu insgeschreven voor de nieuwsbrief van " . global_bedrijfsnaam . ".";

        $partials = array('header' => 'templ/main_header', 'content' => 'nieuwsbrief_brief', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function loadCategorienForMenu(){
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSubSnel();
        
        return $categorien;
    }
}
