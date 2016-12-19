<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
        // Your own constructor code            
    }

    /* Home leads to the REAL homepage with the 2 choices, FASHION and BEAUTY */

    public function home() {
        $this->addVisitor();
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Home';
        $partials = array('header' => 'templ/main_header_mainhome', 'content' => 'mainhome', 'footer' => 'templ/main_footer_mainhome');
        $this->template->load('main_master', $partials, $data);
    }

    public function addVisitor() {
        // check voor nieuwe bezoeker
        $this->load->model('bezoeker_model');
        $this->load->model('bezoekerhit_model');
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $bezoeker = new stdClass();
        $bezoeker->ip = $ip;
        /* $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 7200); // 2uur bijtellen voor server */
        $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        $bezoeker->bezoekdatum = $date; //date('Y-m-d H:i:s a');
        if ($this->session->userdata('bezocht') == null || $this->session->userdata('bezocht') == 0) {
            $id = $this->bezoeker_model->insert($bezoeker);
            $this->session->set_userdata('bezocht', $id);
        }

        // bezoeker ook toevoegen aan totaal aantal bezoekers = hits
        $id = $this->bezoekerhit_model->insert($bezoeker);
    }

    public function index($autosearchfor = "") {
        $this->load->model('setting_model');
        $setting = $this->setting_model->get(1);
        $data['setting'] = $setting;
        if ($setting->countdownEnabled == 1) {
            $this->addVisitor();
            $this->timer($setting->countdownEndDate);
        } else {
            $data['title'] = global_bedrijfsnaam;
            $data['pagina'] = 'Home';
//$data['gebruiker'] = $this->authex->getUserInfo();   

            /*
             * Geen artikels meer laden, gebeurd async via ajax!
             */
            /* $this->load->model('artikel_model');
              //$artikels = $this->artikel_model->getAll();
              $artikels = $this->artikel_model->getAllAmount(9);
              $data['artikels'] = $artikels; */

            //$artikelsLow = $this->artikel_model->getLastWithLowStock(4);
            $this->load->model('onlangsbekeken_model');
            $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
            $data['onlangsbekeken'] = $onlangsbekeken;

            $this->load->model('tekst_model');
            $teksten = $this->tekst_model->getAllByPage("home.php");
            $data['teksten'] = $teksten;

            $this->addVisitor();

            //get all categorien voor menu
            $this->load->model('categorie_model');
            $categorien = $this->categorie_model->getAllWithSubSnel();
            $data['categorien'] = $categorien;
            if (strpos($autosearchfor, 'index') !== false) {
                $autosearchfor = "";
            }
            $data['autosearchfor'] = $autosearchfor;

            $partials = array('header' => 'templ/main_header', 'content' => 'home', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function lazyload() {
        $amount = $this->input->get('amount');
        $sort = $this->input->get('sort');
        $lastamount = $this->input->get('lastamount');

        $this->load->model('artikel_model');
        $artikels = $this->artikel_model->getAllAmountAndSort($amount, $sort, $lastamount);
        $data['artikels'] = $artikels;
        
        $this->load->model('setting_model');
        $setting = $this->setting_model->get(1);
        $data['setting'] = $setting;

        $this->load->view('home_artikels', $data);
    }

    public function searchArtikelsByInput() {
        $input = $this->input->get('input');

        $this->load->model('artikel_model');
        $artikels = $this->artikel_model->getAllByInput($input);
        $data['artikels'] = $artikels;

        $this->load->view('home_artikels', $data);
    }

    public function searchArtikelsByInputAndSort() {
        $input = $this->input->get('input');
        $sort = $this->input->get('sort');

        $this->load->model('artikel_model');
        $artikels = $this->artikel_model->getAllByInputAndSort($input, $sort);
        $data['artikels'] = $artikels;

        $this->load->view('home_artikels', $data);
    }

    public function test() {
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Home';
//$data['gebruiker'] = $this->authex->getUserInfo();         
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSub();
        $data['categorien'] = $categorien;

        $partials = array('header' => 'templ/main_header', 'content' => 'tester', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function phpinfo() {
        echo phpinfo();
    }

    public function refresh() {
        $email = 'jeroen_vinken@hotmail.com';
        $this->load->library('email');
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'priority' => '1'
        );
        $session_data = $this->session->all_userdata();
        $results = print_r($session_data, true);
        $this->email->initialize($config);
        $this->email->from(global_webshopemail);
        $this->email->subject('Iemand refreshte zijn data op ' . global_websiteURL);
        $this->email->message($results);
        $this->email->to($email);
        $this->email->send();

        $this->session->sess_destroy();
        $this->index();
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

    public function timetest() {
        $date = date_create(now(), timezone_open('Pacific/Nauru'));
    }

    function design() {
        $data['pagina'] = 'test';
        $data['title'] = 'test';
        $partials = array('header' => 'templ/main_header', 'content' => 'design', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
