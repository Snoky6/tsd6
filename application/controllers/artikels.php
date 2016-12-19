<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Artikels extends CI_Controller {

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
        $data['pagina'] = 'Home';
        //$data['gebruiker'] = $this->authex->getUserInfo();   

        $this->load->model('artikel_model');
        $artikels = $this->artikel_model->getAll();
        $data['artikels'] = $artikels;

        $this->load->model('onlangsbekeken_model');
        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'home', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function categorie($id) {
        $this->load->model('setting_model');
        $setting = $this->setting_model->get(1);
        if ($setting->countdownEnabled == 1) {
            $this->timer($setting->countdownEndDate);
        } else {
            $data['title'] = global_bedrijfsnaam;
            $data['pagina'] = 'Artikels';
            //$data['gebruiker'] = $this->authex->getUserInfo();   

            $this->load->model('artikel_model');
            $artikels = $this->artikel_model->getAllByCategorieId($id);
            $data['artikels'] = $artikels;

            $this->load->model('onlangsbekeken_model');
            $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
            $data['onlangsbekeken'] = $onlangsbekeken;

            $data['categorien'] = $this->loadCategorienForMenu();

            $partials = array('header' => 'templ/main_header', 'content' => 'artikels', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        }
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

        $this->load->model('artikel_model');
        $artikel = $this->artikel_model->get($id);
        $data['artikel'] = $artikel;
        $this->load->model('onlangsbekeken_model');
        if ($artikel != null) {
            $data['pagina'] = $artikel->naam;
            $this->load->model('onlangsbekeken_model');
            $this->onlangsbekeken_model->voegtoe($id);
        } else {
            $data['pagina'] = "Artikel niet gevonden";
        }

        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'artikel_details', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }
    
    public function detailspopup() {
        $id = $this->input->get('id');
        $data['title'] = global_bedrijfsnaam;
        //$data['gebruiker'] = $this->authex->getUserInfo();   

        $this->load->model('artikel_model');
        $artikel = $this->artikel_model->get($id);
        $data['artikel'] = $artikel;
        $this->load->model('onlangsbekeken_model');
        if ($artikel != null) {
            $data['pagina'] = $artikel->naam;
            $this->load->model('onlangsbekeken_model');
            $this->onlangsbekeken_model->voegtoe($id);
        } else {
            $data['pagina'] = "Artikel niet gevonden";
        }          
        
        $this->load->view('outfit_artikel_popup_ajax', $data);
    }

    public function loadCategorienForMenu() {
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSubSnel();

        return $categorien;
    }

    public function timer($date) {
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Home';
//$data['gebruiker'] = $this->authex->getUserInfo();         
        $data['enddate'] = $date;
        $this->load->model('tekst_model');
        $teksten = $this->tekst_model->getAllByPage("timer.php");
        $data['teksten'] = $teksten;
        $partials = array('header' => 'templ/timer_header', 'content' => 'timer', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }
    
    function getMatenForCartPopup(){
        $id = $this->input->get('id');
        $data['title'] = global_bedrijfsnaam;
        //$data['gebruiker'] = $this->authex->getUserInfo();   

        $this->load->model('artikel_model');
        $artikel = $this->artikel_model->get($id);
        $data['artikel'] = $artikel;
        
        if ($artikel != null) {
            $data['pagina'] = $artikel->naam;            
        } else {
            $data['pagina'] = "Artikel niet gevonden";
        }          
        
        $this->load->view('artikel_maten_winkelmandje_ajax', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */