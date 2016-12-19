<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Outfits extends CI_Controller {

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
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->outfits();
    }
   

    public function outfits() {
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Outfits';
        //$data['gebruiker'] = $this->authex->getUserInfo();   

        $this->load->model('outfit_model');
        $outfits = $this->outfit_model->getAll();
        $data['outfits'] = $outfits;

        $this->load->model('onlangsbekeken_model');
        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'outfits', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function details($id) {
        $data['title'] = global_bedrijfsnaam;
        //$data['gebruiker'] = $this->authex->getUserInfo();   

        $this->load->model('outfit_model');
        $outfit = $this->outfit_model->get($id);
        $data['outfit'] = $outfit;
        $data['pagina'] = $outfit->naam . ' - Details';

        $this->load->model('onlangsbekeken_model');        

        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'outfit_details', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function loadCategorienForMenu() {
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSubSnel();

        return $categorien;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */