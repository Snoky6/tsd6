<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Info';
        //$data['gebruiker'] = $this->authex->getUserInfo(); 
        
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSubSnel();
        $data['categorien'] = $categorien;
        
        $this->load->model('tekst_model');
        $teksten = $this->tekst_model->getAllByPage("info.php");
        $data['teksten'] = $teksten;

        $partials = array('header' => 'templ/main_header', 'content' => 'info', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }
    
    public function overons(){
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'Over ons';
        //$data['gebruiker'] = $this->authex->getUserInfo(); 
        
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSubSnel();
        $data['categorien'] = $categorien;
        
        $this->load->model('tekst_model');
        $teksten = $this->tekst_model->getAllByPage("overons.php");
        $data['teksten'] = $teksten;

        $partials = array('header' => 'templ/main_header', 'content' => 'overons', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }
    
    public function faq(){
        $data['title'] = global_bedrijfsnaam;
        $data['pagina'] = 'FAQ';
        //$data['gebruiker'] = $this->authex->getUserInfo(); 
        
        $this->load->model('faq_model');
        $faqs = $this->faq_model->getAll();
        $data['faqs'] = $faqs;
        
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSubSnel();
        $data['categorien'] = $categorien;        

        $partials = array('header' => 'templ/main_header', 'content' => 'faq', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }
}