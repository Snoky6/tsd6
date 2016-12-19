<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

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
    public function index($bewerkt = false) {
        if ($this->isAdmin()) {
            $data['title'] = global_bedrijfsnaam . " - Admin";
            $data['pagina'] = 'Admin - Algemeen';

            $this->load->model('tekst_model');
            $teksten = $this->tekst_model->getAll();
            $data['teksten'] = $teksten;

            $this->load->model('setting_model');
            $setting = $this->setting_model->get(1);
            $data['setting'] = $setting;
            if ($bewerkt == true) {
                $data['bewerkt'] = 'Tekst is aangepast!';
            }

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_algemeen', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function algemeen() {
        $this->index();
    }

    private function isAdmin() {
        if (!$this->session->userdata('admin')) {
            return false;
        } else {
            return $this->session->userdata('admin');
        }
    }

    private function noAccess() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Login';
        $data['error'] = 'Incorrecte login';
        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'admin_login', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function loadCategorienForMenu() {
        //get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSub();

        return $categorien;
    }

    public function login() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Algemeen';
        $username = $this->input->post('naam');
        $password = $this->input->post('wachtwoord');

        if ($password == global_adminloginww && $username == global_adminlogin) {
            $this->session->set_userdata('admin', true);
            $this->algemeen();
        } else {
            // Geen toegang
            $this->noAccess();
        }
    }

    public function artikels($bewerkt = FALSE) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Artikels';

        if ($bewerkt == true) {
            $data['bewerkt'] = 'Artikel is aangepast!';
        }

        if ($this->isAdmin()) {
            $this->session->set_userdata('admin', true);

            /* $this->load->model('artikel_model');
              $artikels = $this->artikel_model->getAllAdmin();
              $data['artikels'] = $artikels; */

            $this->load->model('categorie_model');
            $categorien = $this->categorie_model->getAll();
            $data['categorien'] = $categorien;

            $this->load->model('maat_model');
            $maten = $this->maat_model->getAll();
            $data['maten'] = $maten;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_artikels', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            // Geen toegang
            $this->noAccess();
        }
    }

    public function searchArtikelsByInput($page = 'standard') {
        $input = $this->input->get('input');

        $this->load->model('artikel_model');
        $artikels = $this->artikel_model->getAllByInputAdmin($input);
        $data['artikels'] = $artikels;

        if ($page == 'standard') {
            $this->load->view('admin_artikels_ajax', $data);
        } else if ($page == 'outfits') {
            $this->load->view('admin_artikels_outfit_ajax', $data);
        }
    }

    public function searchBestellingenByInput() {
        $input = $this->input->get('input');

        $this->load->model('bestelling_model');
        $bestellingen = $this->bestelling_model->getAllByInputAdmin($input);
        $data['bestellingen'] = $bestellingen;

        $this->load->view('admin_bestellingen_ajax', $data);
    }

    public function outfits($bewerkt = FALSE) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Outfits';

        if ($bewerkt == true) {
            $data['bewerkt'] = 'Outfit is aangepast!';
        }

        if ($this->isAdmin()) {
            $this->session->set_userdata('admin', true);

            $this->load->model('outfit_model');
            $outfits = $this->outfit_model->getAllAdmin();
            $data['outfits'] = $outfits;

            /* $this->load->model('maat_model');
              $maten = $this->maat_model->getAll();
              $data['maten'] = $maten; */

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_outfits', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            // Geen toegang
            $this->noAccess();
        }
    }

    public function bewerkoutfit($id, $bewerkt = FALSE) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Outfits';

        if ($bewerkt == true) {
            $data['bewerkt'] = 'Outfit is aangepast!';
        }

        if ($this->isAdmin()) {
            $this->session->set_userdata('admin', true);

            $this->load->model('outfit_model');
            $outfit = $this->outfit_model->getAdmin($id);
            $data['outfit'] = $outfit;

            if (!(isset($outfit)) || $outfit == null) {
                $this->load->model('outfit_model');
                $lastOutfitId = $this->outfit_model->getLastId();
                $data['outfitId'] = $lastOutfitId + 1;
            } else {
                $data['outfitId'] = $outfit->id;
            }


            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_nieuwe_outfit', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            // Geen toegang
            $this->noAccess();
        }
    }

    public function voegArtikelToeAanOutfit() {
        $artikelId = $this->input->get('artikelId');
        $outfitId = $this->input->get('outfitId');

        $this->load->model('outfitartikel_model');
        $outfitartikel = new stdClass();
        $outfitartikel->artikelId = $artikelId;
        $outfitartikel->outfitId = $outfitId;

        $this->outfitartikel_model->insert($outfitartikel);

        $outfitArtikels = $this->outfitartikel_model->getAllByOutfitId($outfitId);

        $data['artikels'] = $outfitArtikels;

        $this->load->view('admin_gekozen_outfitartikels_ajax', $data);
    }

    public function verwijderArtikelVanOutfit() {
        $outfitArtikelid = $this->input->get('outfitArtikelId');

        $this->load->model('outfitartikel_model');
        $this->outfitartikel_model->delete($outfitArtikelid);

        return true;
    }

    public function bewerkteoutfitopslaan() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Outfit bewerken';
//$data['gebruiker'] = $this->authex->getUserInfo();   
        // gegevesn uit form post opvragen
        if ($this->isAdmin()) {
            $bewerkteoutfitid = $this->input->post('bewerkteoutfitid');
            $naam = $this->input->post('naam');
            $omschrijving = $this->input->post('omschrijving');
            $archief = $this->input->post('archief');

            $this->load->model('outfit_model');
            /* $lastOutfit = $this->outfit_model->getLastId();
              $lastId = $lastArtikel->id; */
            $fotonaam = ($bewerkteoutfitid) . " - " . $naam;
            // trim ""
            $fotonaam = str_replace('"', '', $fotonaam);
            $fotonaam = str_replace("'", '', $fotonaam);
            $fotonaam = str_replace(".", '', $fotonaam);
            $fotonaam = str_replace("&", '', $fotonaam);

            //$bewerkteOutfit = $this->outfit_model->get($bewerkteoutfitid);
            // insert in var outfit voor insert db
            $outfit = new stdClass();
            $outfit->id = $bewerkteoutfitid;
            $outfit->naam = $naam;
            $outfit->omschrijving = $omschrijving;
            if ($archief == true || $archief == "true") {
                $outfit->archief = 1;
            } else {
                $outfit->archief = 0;
            }

            $this->load->model('outfitfoto_model');

            ///////////////////////////////////////////////
            // fileupload
            if (isset($_FILES['userfile']) && is_uploaded_file($_FILES['userfile']['tmp_name'][0])) {

                $config['upload_path'] = 'application/images/outfits';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '10000000000000';
                $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

                $name_array = array();
                if (isset($_FILES['userfile'])) {
                    $count = count($_FILES['userfile']['size']);
                    $files = $_FILES;
                    // RESIZE IMG LIB HIER LADEN
                    $this->load->library('image_lib');

                    // enkel hoofdoto wordt hier geupload, OVERWRITE TRUE
                    for ($t = 0; $t <= $count - 1; $t++) {
                        $_FILES['userfile']['name'] = $files['userfile']['name'][$t];
                        $_FILES['userfile']['type'] = $files['userfile']['type'][$t];
                        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$t];
                        $_FILES['userfile']['error'] = $files['userfile']['error'][$t];
                        $_FILES['userfile']['size'] = $files['userfile']['size'][$t];
                        $config['upload_path'] = 'application/images/outfits';
                        $config['overwrite'] = TRUE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                        //RESIZE HIER AL
                        $filepath = $upload_data['full_path'];

                        // EXIF al opvragen voor resize anders is die leeg
                        //$exif = @exif_read_data($filepath);
                        //echo $filepath . " is readable: " . is_readable($filepath);
                        $exif = @exif_read_data($filepath, 'EXIF', 0);

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $filepath;
                        $config2['maintain_ratio'] = TRUE;
                        $config2['width'] = 1024;
                        $config2['height'] = 768;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        $this->image_lib->resize();
                        // end resize
                        // ROTATE
                        if (empty($exif['Orientation'])) {
                            // GEEN EXIF DATA, NIET ROTATEN
                        } else {
                            // WEL EXIFDATA, ROTATEN
                            //$CI = & get_instance(); // =$this

                            $config3['image_library'] = 'gd2';
                            $config3['source_image'] = $filepath;

                            $oris = array();

                            switch ($exif['Orientation']) {
                                case 1: // no need to perform any changes
                                    break;

                                case 2: // horizontal flip
                                    $oris[] = 'hor';
                                    break;

                                case 3: // 180 rotate left
                                    $oris[] = '180';
                                    break;

                                case 4: // vertical flip
                                    $oris[] = 'ver';
                                    break;

                                case 5: // vertical flip + 90 rotate right
                                    $oris[] = 'ver';
                                    $oris[] = '270';
                                    break;

                                case 6: // 90 rotate right
                                    $oris[] = '270';
                                    break;

                                case 7: // horizontal flip + 90 rotate right
                                    $oris[] = 'hor';
                                    $oris[] = '270';
                                    break;

                                case 8: // 90 rotate left
                                    $oris[] = '90';
                                    break;

                                default: break;
                            }

                            foreach ($oris as $ori) {
                                $config3['rotation_angle'] = $ori;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config3);
                                $this->image_lib->rotate();
                            }
                        }
                        // END ROTATE

                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {
                                if ($t == 0) {
                                    $outfit->imagePath = "images/outfits/" . str_replace(' ', '_', $fotonaam) . '' . $file_ext;
                                    $mainImageAangepast = true;
                                }
                            }
                        }
                        //}
                    }
                }
            }

            //////////////////////////////////////////////////
            // fileupload EXTRA

            if (isset($_FILES['userfileextra']) && is_uploaded_file($_FILES['userfileextra']['tmp_name'][0])) {
                $config['upload_path'] = 'application/images/outfits';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '10000000000000';
                $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

                if (isset($_FILES['userfileextra'])) {
                    $count = count($_FILES['userfileextra']['size']);
                    $files = $_FILES;
                    // RESIZE HIER
                    $this->load->library('image_lib');

                    //voor extra fotos
                    for ($s = 0; $s <= $count - 1; $s++) {
                        $_FILES['userfile']['name'] = $files['userfileextra']['name'][$s];
                        $_FILES['userfile']['type'] = $files['userfileextra']['type'][$s];
                        $_FILES['userfile']['tmp_name'] = $files['userfileextra']['tmp_name'][$s];
                        $_FILES['userfile']['error'] = $files['userfileextra']['error'][$s];
                        $_FILES['userfile']['size'] = $files['userfileextra']['size'][$s];
                        $config['upload_path'] = 'application/images/outfits';
                        $config['overwrite'] = FALSE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                        //RESIZE HIER AL
                        $filepath = $upload_data['full_path'];

                        // EXIF al opvragen voor resize anders is die leeg
                        $exif = @exif_read_data($filepath);

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $filepath;
                        $config2['maintain_ratio'] = TRUE;
                        $config2['width'] = 1024;
                        $config2['height'] = 768;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        $this->image_lib->resize();
                        // end resize
                        // ROTATE
                        if (empty($exif['Orientation'])) {
                            // GEEN EXIF DATA, NIET ROTATEN
                        } else {
                            // WEL EXIFDATA, ROTATEN
                            //$CI = & get_instance(); // =$this

                            $config3['image_library'] = 'gd2';
                            $config3['source_image'] = $filepath;

                            $oris = array();

                            switch ($exif['Orientation']) {
                                case 1: // no need to perform any changes
                                    break;

                                case 2: // horizontal flip
                                    $oris[] = 'hor';
                                    break;

                                case 3: // 180 rotate left
                                    $oris[] = '180';
                                    break;

                                case 4: // vertical flip
                                    $oris[] = 'ver';
                                    break;

                                case 5: // vertical flip + 90 rotate right
                                    $oris[] = 'ver';
                                    $oris[] = '270';
                                    break;

                                case 6: // 90 rotate right
                                    $oris[] = '270';
                                    break;

                                case 7: // horizontal flip + 90 rotate right
                                    $oris[] = 'hor';
                                    $oris[] = '270';
                                    break;

                                case 8: // 90 rotate left
                                    $oris[] = '90';
                                    break;

                                default: break;
                            }

                            foreach ($oris as $ori) {
                                $config3['rotation_angle'] = $ori;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config3);
                                $this->image_lib->rotate();
                            }
                        }
                        // END ROTATE 


                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {

                                // 8/02/16 change voor extra fotos apart! 
                                // aangepast voor fout 18/01 --> vroger lastid + 1 bij artikelID
                                $outfitFoto = new stdClass();
                                $outfitFoto->outfitId = $outfit->id;
                                $outfitFoto->imagePath = '/images/outfits/' . $upload_data['file_name'];
                                $this->outfitfoto_model->insert($outfitFoto);
                            }
                        }
                    }
                }
            }

            $this->outfit_model->update($outfit);

            $data['bewerkt'] = 'Outfit is aangepast!';
            $this->nieuweoutfitpage(true);
        } else {
            $this->noAccess();
        }
    }

    public function nieuweoutfitpage($toegevoegd = false) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Nieuwe outfit';
        if ($toegevoegd == true) {
            $data['toegevoegd'] = 'Outfit toegevoegd aan database!';
        }
        if ($this->isAdmin()) {
            $this->load->model('outfit_model');
            $lastOutfitId = $this->outfit_model->getLastId();
            $data['outfitId'] = $lastOutfitId + 1;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_nieuwe_outfit', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function nieuweoutfit() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Nieuwe outfit';
//$data['gebruiker'] = $this->authex->getUserInfo();   
        // gegevesn uit form post opvragen
        if ($this->isAdmin()) {
            $naam = $this->input->post('naam');
            $omschrijving = $this->input->post('omschrijving');
            $archief = $this->input->post('archief');

            $this->load->model('outfit_model');
            $lastId = $this->outfit_model->getLastId();

            $fotonaam = ($lastId + 1) . " - " . $naam;
            // trim ""
            $fotonaam = str_replace('"', '', $fotonaam);
            $fotonaam = str_replace("'", '', $fotonaam);
            $fotonaam = str_replace(".", '', $fotonaam);
            $fotonaam = str_replace("&", '', $fotonaam);

            //$bewerkteOutfit = $this->outfit_model->get($bewerkteoutfitid);
            // insert in var outfit voor insert db
            $outfit = new stdClass();
            $outfit->id = $lastId + 1;
            $outfit->naam = $naam;
            $outfit->omschrijving = $omschrijving;
            if ($archief == true || $archief == "true") {
                $outfit->archief = 1;
            } else {
                $outfit->archief = 0;
            }

            $this->load->model('outfitfoto_model');

            ///////////////////////////////////////////////
            // fileupload
            if (isset($_FILES['userfile']) && is_uploaded_file($_FILES['userfile']['tmp_name'][0])) {

                $config['upload_path'] = 'application/images/outfits';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '10000000000000';
                $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

                $name_array = array();
                if (isset($_FILES['userfile'])) {
                    $count = count($_FILES['userfile']['size']);
                    $files = $_FILES;
                    // RESIZE IMG LIB HIER LADEN
                    $this->load->library('image_lib');

                    // enkel hoofdoto wordt hier geupload, OVERWRITE TRUE
                    for ($t = 0; $t <= $count - 1; $t++) {
                        $_FILES['userfile']['name'] = $files['userfile']['name'][$t];
                        $_FILES['userfile']['type'] = $files['userfile']['type'][$t];
                        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$t];
                        $_FILES['userfile']['error'] = $files['userfile']['error'][$t];
                        $_FILES['userfile']['size'] = $files['userfile']['size'][$t];
                        $config['upload_path'] = 'application/images/outfits';
                        $config['overwrite'] = TRUE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                        //RESIZE HIER AL
                        $filepath = $upload_data['full_path'];

                        // EXIF al opvragen voor resize anders is die leeg
                        //$exif = @exif_read_data($filepath);
                        //echo $filepath . " is readable: " . is_readable($filepath);
                        $exif = @exif_read_data($filepath, 'EXIF', 0);

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $filepath;
                        $config2['maintain_ratio'] = TRUE;
                        $config2['width'] = 1024;
                        $config2['height'] = 768;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        $this->image_lib->resize();
                        // end resize
                        // ROTATE
                        if (empty($exif['Orientation'])) {
                            // GEEN EXIF DATA, NIET ROTATEN
                        } else {
                            // WEL EXIFDATA, ROTATEN
                            //$CI = & get_instance(); // =$this

                            $config3['image_library'] = 'gd2';
                            $config3['source_image'] = $filepath;

                            $oris = array();

                            switch ($exif['Orientation']) {
                                case 1: // no need to perform any changes
                                    break;

                                case 2: // horizontal flip
                                    $oris[] = 'hor';
                                    break;

                                case 3: // 180 rotate left
                                    $oris[] = '180';
                                    break;

                                case 4: // vertical flip
                                    $oris[] = 'ver';
                                    break;

                                case 5: // vertical flip + 90 rotate right
                                    $oris[] = 'ver';
                                    $oris[] = '270';
                                    break;

                                case 6: // 90 rotate right
                                    $oris[] = '270';
                                    break;

                                case 7: // horizontal flip + 90 rotate right
                                    $oris[] = 'hor';
                                    $oris[] = '270';
                                    break;

                                case 8: // 90 rotate left
                                    $oris[] = '90';
                                    break;

                                default: break;
                            }

                            foreach ($oris as $ori) {
                                $config3['rotation_angle'] = $ori;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config3);
                                $this->image_lib->rotate();
                            }
                        }
                        // END ROTATE

                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {
                                if ($t == 0) {
                                    $outfit->imagePath = "images/outfits/" . str_replace(' ', '_', $fotonaam) . '' . $file_ext;
                                    $mainImageAangepast = true;
                                }
                            }
                        }
                        //}
                    }
                }
            }

            //////////////////////////////////////////////////
            // fileupload EXTRA

            if (isset($_FILES['userfileextra']) && is_uploaded_file($_FILES['userfileextra']['tmp_name'][0])) {
                $config['upload_path'] = 'application/images/outfits';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '10000000000000';
                $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

                if (isset($_FILES['userfileextra'])) {
                    $count = count($_FILES['userfileextra']['size']);
                    $files = $_FILES;
                    // RESIZE HIER
                    $this->load->library('image_lib');

                    //voor extra fotos
                    for ($s = 0; $s <= $count - 1; $s++) {
                        $_FILES['userfile']['name'] = $files['userfileextra']['name'][$s];
                        $_FILES['userfile']['type'] = $files['userfileextra']['type'][$s];
                        $_FILES['userfile']['tmp_name'] = $files['userfileextra']['tmp_name'][$s];
                        $_FILES['userfile']['error'] = $files['userfileextra']['error'][$s];
                        $_FILES['userfile']['size'] = $files['userfileextra']['size'][$s];
                        $config['upload_path'] = 'application/images/outfits';
                        $config['overwrite'] = FALSE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                        //RESIZE HIER AL
                        $filepath = $upload_data['full_path'];

                        // EXIF al opvragen voor resize anders is die leeg
                        $exif = @exif_read_data($filepath);

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $filepath;
                        $config2['maintain_ratio'] = TRUE;
                        $config2['width'] = 1024;
                        $config2['height'] = 768;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        $this->image_lib->resize();
                        // end resize
                        // ROTATE
                        if (empty($exif['Orientation'])) {
                            // GEEN EXIF DATA, NIET ROTATEN
                        } else {
                            // WEL EXIFDATA, ROTATEN
                            //$CI = & get_instance(); // =$this

                            $config3['image_library'] = 'gd2';
                            $config3['source_image'] = $filepath;

                            $oris = array();

                            switch ($exif['Orientation']) {
                                case 1: // no need to perform any changes
                                    break;

                                case 2: // horizontal flip
                                    $oris[] = 'hor';
                                    break;

                                case 3: // 180 rotate left
                                    $oris[] = '180';
                                    break;

                                case 4: // vertical flip
                                    $oris[] = 'ver';
                                    break;

                                case 5: // vertical flip + 90 rotate right
                                    $oris[] = 'ver';
                                    $oris[] = '270';
                                    break;

                                case 6: // 90 rotate right
                                    $oris[] = '270';
                                    break;

                                case 7: // horizontal flip + 90 rotate right
                                    $oris[] = 'hor';
                                    $oris[] = '270';
                                    break;

                                case 8: // 90 rotate left
                                    $oris[] = '90';
                                    break;

                                default: break;
                            }

                            foreach ($oris as $ori) {
                                $config3['rotation_angle'] = $ori;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config3);
                                $this->image_lib->rotate();
                            }
                        }
                        // END ROTATE 


                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {

                                // 8/02/16 change voor extra fotos apart! 
                                // aangepast voor fout 18/01 --> vroger lastid + 1 bij artikelID
                                $outfitFoto = new stdClass();
                                $outfitFoto->outfitId = $outfit->id;
                                $outfitFoto->imagePath = '/images/outfits/' . $upload_data['file_name'];
                                $this->outfitfoto_model->insert($outfitFoto);
                            }
                        }
                    }
                }
            }

            $this->outfit_model->insert($outfit);

            $data['bewerkt'] = 'Outfit is aangemaakt!';
            $this->outfits(true);
        } else {
            $this->noAccess();
        }
    }

    public function loadOutfitArtikels() {
        $outfitId = $this->input->get('outfitId');
        $this->load->model('outfitartikel_model');
        $outfitArtikels = $this->outfitartikel_model->getAllByOutfitId($outfitId);

        $data['artikels'] = $outfitArtikels;

        $this->load->view('admin_gekozen_outfitartikels_ajax', $data);
    }

    public function nieuwartikelpage($toegevoegd = FALSE, $bewerkt = FALSE) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Nieuw artikel';
        if ($toegevoegd == true) {
            $data['toegevoegd'] = 'Artikel toegevoegd aan database!';
        }
        if ($this->isAdmin()) {
            $this->load->model('categorie_model');
            $categorien = $this->categorie_model->getAll();
            $data['categorien'] = $categorien;

            $this->load->model('maat_model');
            $maten = $this->maat_model->getAll();
            $data['maten'] = $maten;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_nieuw_artikel', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function bewerkartikel($id, $toegevoegd = FALSE, $bewerkt = FALSE) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Bewerk artikel';
        if ($bewerkt == true) {
            $data['bewerkt'] = 'Artikel is aangepast!';
        }
        if ($this->isAdmin()) {
            $this->load->model('artikel_model');
            $artikel = $this->artikel_model->getAdmin($id);
            //$artikel->hoofdcategorie = ;
            $data['artikel'] = $artikel;

            $this->load->model('categorie_model');
            $categorien = $this->categorie_model->getAll();
            $data['categorien'] = $categorien;

            $this->load->model('maat_model');
            $maten = $this->maat_model->getAll();
            $data['maten'] = $maten;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_nieuw_artikel', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function nieuwartikel() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Nieuw Artikel';
//$data['gebruiker'] = $this->authex->getUserInfo();   
        // gegevesn uit form post opvragen
        if ($this->isAdmin()) {
            $naam = $this->input->post('naam');
            $categorieNaam = $this->input->post('categorie');
            $subcategorieNaam = $this->input->post('subcategorie');
            $korting = $this->input->post('korting');
            $prijs = $this->input->post('prijs');
            $omschrijving = $this->input->post('omschrijving');
            $barcode = $this->input->post('barcode');
            $archief = $this->input->post('archief');

            // check for nulls en nieuwe categorien
            $this->load->model('categorie_model');
            $categorie = $this->categorie_model->getByName($categorieNaam);
            $subcategorie = $this->categorie_model->getByName($subcategorieNaam);

            // geen bestaande categorie
            $subid;
            if ($categorie == NULL) {
                // nieuwe categorie aanmaken
                unset($categorie);
                $categorie = new stdClass();
                $categorie->naam = $categorieNaam;
                $categorie->hoofdcategorieId = NULL;
                $id = $this->categorie_model->insert($categorie);
                if ($subcategorieNaam == NULL || $subcategorieNaam == '') {
                    // leeg gelaten
                    $categorieId = $id;
                } else if ($subcategorie == NULL) {
                    // subcategorie bestaat nog niet
                    unset($subcategorie);
                    $subcategorie = new stdClass();
                    $subcategorie->naam = $subcategorieNaam;
                    $subcategorie->hoofdcategorieId = $id;
                    $subid = $this->categorie_model->insert($subcategorie);
                    $categorieId = $subid;
                }
            } else {
                $categorieId = $categorie->id;
                if ($subcategorieNaam == NULL || $subcategorieNaam == '') {
                    // leeg gelaten
                    $categorieId = $categorie->id;
                } else if ($subcategorie == NULL) {
                    // subcategorie bestaat nog niet
                    unset($subcategorie);
                    $subcategorie = new stdClass();
                    $subcategorie->naam = $subcategorieNaam;
                    $subcategorie->hoofdcategorieId = $categorie->id;
                    $subid = $this->categorie_model->insert($subcategorie);
                    $categorieId = $subid;
                } else {
                    $subid = $subcategorie->id;
                }
            }

            if ($korting == NULL) {
                $korting = 0;
            }


            $this->load->model('artikel_model');
            $lastArtikel = $this->artikel_model->getLastArtikel();
            if ($lastArtikel == null) {
                $lastId = 0;
            } else {
                $lastId = $lastArtikel->id;
            }
            $fotonaam = ($lastId + 1) . " - " . $naam;
            // trim ""
            $fotonaam = str_replace('"', '', $fotonaam);
            $fotonaam = str_replace("'", '', $fotonaam);
            $fotonaam = str_replace(".", '', $fotonaam);
            $fotonaam = str_replace("&", '', $fotonaam);

            // Laad alle maten en maak variables met id als naam en aantal als value
            $this->load->model('maat_model');
            $maten = $this->maat_model->getAll();

            $this->load->model('artikelmaat_model');

            $artikelmaten;
            foreach ($maten as $maat) {
                // als maat niet ingevuld = 0
                $artikelmaat = new stdClass();
                $artikelmaat->artikelId = $lastId + 1;
                if ($this->input->post(strtolower($maat->maat)) == NULL || $this->input->post(strtolower($maat->maat)) == '') {
                    //${"$maat->id"} = 0;
                    $artikelmaat->maatId = $maat->id;
                    $artikelmaat->voorraad = 0;
                } else {
                    //${"$maat->id"} = $this->input->post(strtolower($maat->maat));
                    $artikelmaat->maatId = $maat->id;
                    $artikelmaat->voorraad = $this->input->post(strtolower($maat->maat));
                }
                $this->artikelmaat_model->insert($artikelmaat);
                // deze array is waarschijnlijk niet nodig, gewoon direct aan db toevoegen hier
                //array_push($artikelmaten,$artikelmaat);             
            }

            // insert in var artikel voor insert db
            $artikel = new stdClass();
            $artikel->id = $lastId + 1;
            $artikel->naam = $naam;
            if ($subcategorie == null || $subcategorie == '' || !(isset($subid))) {
                $artikel->categorieId = $categorieId;
            } else {
                $artikel->categorieId = $subid;
            }

            $artikel->korting = $korting;
            $artikel->prijs = $prijs;
            $artikel->omschrijving = $omschrijving;
            if ($barcode == '' || $barcode == null) {
                $barcode = "x" . ($lastId + 1) . "-" . date("Y");
            }
            $artikel->barcode = $barcode;
            if ($archief == true || $archief == "true") {
                $artikel->archief = 1;
            } else {
                $artikel->archief = 0;
            }

///////////////////////////////////////////////
            // fileupload
            $config['upload_path'] = 'application/images/artikels';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '10000000000000';
            $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

            $this->load->model('artikelfoto_model');
            $name_array = array();
            if (isset($_FILES['userfile']) && is_uploaded_file($_FILES['userfile']['tmp_name'][0])) {
                if (isset($_FILES['userfile'])) {
                    $count = count($_FILES['userfile']['size']);
                    $files = $_FILES;
                    // RESIZE IMG LIB HIER LADEN
                    $this->load->library('image_lib');

                    // enkel hoofdoto wordt hier geupload, OVERWRITE TRUE
                    for ($t = 0; $t <= $count - 1; $t++) {
                        $_FILES['userfile']['name'] = $files['userfile']['name'][$t];
                        $_FILES['userfile']['type'] = $files['userfile']['type'][$t];
                        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$t];
                        $_FILES['userfile']['error'] = $files['userfile']['error'][$t];
                        $_FILES['userfile']['size'] = $files['userfile']['size'][$t];
                        $config['upload_path'] = 'application/images/artikels';
                        $config['overwrite'] = TRUE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                        //RESIZE HIER AL
                        $filepath = $upload_data['full_path'];

                        // EXIF al opvragen voor resize anders is die leeg
                        $exif = @exif_read_data($filepath);

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $filepath;
                        $config2['maintain_ratio'] = TRUE;
                        $config2['width'] = 1024;
                        $config2['height'] = 768;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        $this->image_lib->resize();
                        // end resize
                        // ROTATE   
                        if (empty($exif['Orientation'])) {
                            // GEEN EXIF DATA, NIET ROTATEN
                        } else {
                            // WEL EXIFDATA, ROTATEN
                            //$CI = & get_instance(); // =$this                   

                            $config3['image_library'] = 'gd2';
                            $config3['source_image'] = $filepath;

                            $oris = array();

                            switch ($exif['Orientation']) {
                                case 1: // no need to perform any changes
                                    break;

                                case 2: // horizontal flip
                                    $oris[] = 'hor';
                                    break;

                                case 3: // 180 rotate left
                                    $oris[] = '180';
                                    break;

                                case 4: // vertical flip
                                    $oris[] = 'ver';
                                    break;

                                case 5: // vertical flip + 90 rotate right
                                    $oris[] = 'ver';
                                    $oris[] = '270';
                                    break;

                                case 6: // 90 rotate right
                                    $oris[] = '270';
                                    break;

                                case 7: // horizontal flip + 90 rotate right
                                    $oris[] = 'hor';
                                    $oris[] = '270';
                                    break;

                                case 8: // 90 rotate left
                                    $oris[] = '90';
                                    break;

                                default: break;
                            }

                            foreach ($oris as $ori) {
                                $config3['rotation_angle'] = $ori;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config3);
                                $this->image_lib->rotate();
                            }
                        }
                        // END ROTATE


                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {
                                if ($t == 0) {
                                    $artikel->imagePath = "images/artikels/" . str_replace(' ', '_', $fotonaam) . '' . $file_ext;
                                    $mainImageAangepast = true;
                                }
                            }
                        }
                        //}
                    }
                }
            }

            //////////////////////////////////////////////////
            // fileupload EXTRA

            if (isset($_FILES['userfileextra']) && is_uploaded_file($_FILES['userfileextra']['tmp_name'][0])) {
                if (isset($_FILES['userfileextra'])) {
                    $count = count($_FILES['userfileextra']['size']);
                    $files = $_FILES;
                    // RESIZE HIER
                    $this->load->library('image_lib');

                    //voor extra fotos
                    for ($s = 0; $s <= $count - 1; $s++) {
                        $_FILES['userfile']['name'] = $files['userfileextra']['name'][$s];
                        $_FILES['userfile']['type'] = $files['userfileextra']['type'][$s];
                        $_FILES['userfile']['tmp_name'] = $files['userfileextra']['tmp_name'][$s];
                        $_FILES['userfile']['error'] = $files['userfileextra']['error'][$s];
                        $_FILES['userfile']['size'] = $files['userfileextra']['size'][$s];
                        $config['upload_path'] = 'application/images/artikels';
                        $config['overwrite'] = FALSE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                        //RESIZE HIER AL
                        $filepath = $upload_data['full_path'];

                        // EXIF al opvragen voor resize anders is die leeg
                        $exif = @exif_read_data($filepath);

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $filepath;
                        $config2['maintain_ratio'] = TRUE;
                        $config2['width'] = 1024;
                        $config2['height'] = 768;
                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        $this->image_lib->resize();
                        // end resize
                        // ROTATE
                        if (empty($exif['Orientation'])) {
                            // GEEN EXIF DATA, NIET ROTATEN
                        } else {
                            // WEL EXIFDATA, ROTATEN
                            //$CI = & get_instance(); // =$this

                            $config3['image_library'] = 'gd2';
                            $config3['source_image'] = $filepath;

                            $oris = array();

                            switch ($exif['Orientation']) {
                                case 1: // no need to perform any changes
                                    break;

                                case 2: // horizontal flip
                                    $oris[] = 'hor';
                                    break;

                                case 3: // 180 rotate left
                                    $oris[] = '180';
                                    break;

                                case 4: // vertical flip
                                    $oris[] = 'ver';
                                    break;

                                case 5: // vertical flip + 90 rotate right
                                    $oris[] = 'ver';
                                    $oris[] = '270';
                                    break;

                                case 6: // 90 rotate right
                                    $oris[] = '270';
                                    break;

                                case 7: // horizontal flip + 90 rotate right
                                    $oris[] = 'hor';
                                    $oris[] = '270';
                                    break;

                                case 8: // 90 rotate left
                                    $oris[] = '90';
                                    break;

                                default: break;
                            }

                            foreach ($oris as $ori) {
                                $config3['rotation_angle'] = $ori;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config3);
                                $this->image_lib->rotate();
                            }
                        }
                        // END ROTATE 


                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {

                                // 8/02/16 change voor extra fotos apart! 
                                // aangepast voor fout 18/01 --> vroger lastid + 1 bij artikelID
                                $artikelFoto = new stdClass();
                                $artikelFoto->artikelId = $artikel->id;
                                $artikelFoto->imagePath = '/images/artikels/' . $upload_data['file_name'];
                                $this->artikelfoto_model->insert($artikelFoto);
                            }
                        }
                    }
                }
            }
            $this->artikel_model->insert($artikel);

            $data['toegevoegd'] = 'Artikel toegevoegd aan database!';
            $this->nieuwartikelpage(true);
        } else {
            $this->noAccess();
        }
    }

    public function bewerktartikelopslaan() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Nieuw Artikel';
//$data['gebruiker'] = $this->authex->getUserInfo();   
        // gegevesn uit form post opvragen
        if ($this->isAdmin()) {
            $bewerktartikelid = $this->input->post('bewerktartikelid');
            $naam = $this->input->post('naam');
            $categorieNaam = $this->input->post('categorie');
            $subcategorieNaam = $this->input->post('subcategorie');
            $korting = $this->input->post('korting');
            $prijs = $this->input->post('prijs');
            $omschrijving = $this->input->post('omschrijving');
            $barcode = $this->input->post('barcode');
            $archief = $this->input->post('archief');

            // check for nulls en nieuwe categorien
            $this->load->model('categorie_model');
            $categorie = $this->categorie_model->getByName($categorieNaam);
            $subcategorie = $this->categorie_model->getByName($subcategorieNaam);

            // geen bestaande categorie
            $subid;
            if ($categorie == NULL) {
                // nieuwe categorie aanmaken
                unset($categorie);
                $categorie;
                $categorie->naam = $categorieNaam;
                $categorie->hoofdcategorieId = NULL;
                $id = $this->categorie_model->insert($categorie);
                if ($subcategorieNaam == NULL || $subcategorieNaam == '') {
                    // leeg gelaten
                    $categorieId = $id;
                } else if ($subcategorie == NULL) {
                    // subcategorie bestaat nog niet
                    unset($subcategorie);
                    $subcategorie;
                    $subcategorie->naam = $subcategorieNaam;
                    $subcategorie->hoofdcategorieId = $id;
                    $subcategorie->id = $this->categorie_model->insert($subcategorie);
                    $subid = $subcategorie->id;
                    $categorieId = $subid;
                }
            } else {
                $categorieId = $categorie->id;
                if ($subcategorieNaam == NULL || $subcategorieNaam == '') {
                    // leeg gelaten
                    $categorieId = $categorie->id;
                } else if ($subcategorie == NULL) {
                    // subcategorie bestaat nog niet
                    unset($subcategorie);
                    $subcategorie;
                    $subcategorie->naam = $subcategorieNaam;
                    $subcategorie->hoofdcategorieId = $categorie->id;
                    $subcategorie->id = $this->categorie_model->insert($subcategorie);
                    $subid = $subcategorie->id;
                    $categorieId = $subid;
                } else {
                    $subid = $subcategorie->id;
                }
            }

            if ($korting == NULL) {
                $korting = 0;
            }


            $this->load->model('artikel_model');
            $fotonaam = ($bewerktartikelid) . " - " . $naam;
            // trim ""
            $fotonaam = str_replace('"', '', $fotonaam);
            $fotonaam = str_replace("'", '', $fotonaam);
            $fotonaam = str_replace(".", '', $fotonaam);
            $fotonaam = str_replace("&", '', $fotonaam);

            // Laad alle maten en maak variables met id als naam en aantal als value
            $this->load->model('maat_model');
            $maten = $this->maat_model->getAll();

            $this->load->model('artikelmaat_model');

            $bewerkteArtikel = $this->artikel_model->getForUpdate($bewerktartikelid);

            $artikelmaten;
            foreach ($maten as $maat) {
                $maataltoegekent = false;
                foreach ($bewerkteArtikel->artikelMaten as $bewerktemaat) {
                    // als maat niet ingevuld = 0
                    $artikelmaat = new stdClass();
                    $artikelmaat->artikelId = $bewerktartikelid;
                    $artikelmaat->id = $bewerktemaat->id;
                    if ($maat->id == $bewerktemaat->maatId) {
                        // alles hierin                    
                        $artikelmaat->maatId = $bewerktemaat->maatId;

                        if ($this->input->post(strtolower($bewerktemaat->maat->maat)) == NULL || $this->input->post(strtolower($bewerktemaat->maat->maat)) == '') {
                            $artikelmaat->voorraad = 0;
                        } else {
                            $artikelmaat->voorraad = $this->input->post(strtolower($bewerktemaat->maat->maat));
                        }
                        $maataltoegekent = TRUE;
                        $this->artikelmaat_model->update($artikelmaat);
                        // deze array is waarschijnlijk niet nodig, gewoon direct aan db toevoegen hier
                        //array_push($artikelmaten,$artikelmaat);                      
                    }
                }
                if ($maataltoegekent == false) {
                    $artikelmaat = new stdClass();
                    $artikelmaat->artikelId = $bewerktartikelid;
                    $artikelmaat->maatId = $maat->id;
                    $artikelmaat->voorraad = 0;
                    $this->artikelmaat_model->insert($artikelmaat);
                }
            }

            // insert in var artikel voor insert db
            $artikel = new stdClass();
            $artikel->id = $bewerktartikelid;
            $artikel->naam = $naam;
            if ($subcategorieNaam == null || $subcategorieNaam == '' || !(isset($subid))) {
                $artikel->categorieId = $categorieId;
            } else {
                //$artikel->categorieId = $subcategorie->id;
                $artikel->categorieId = $subcategorie->id;
            }
            $artikel->korting = $korting;
            $artikel->prijs = $prijs;
            $artikel->omschrijving = $omschrijving;
            if ($barcode == '' || $barcode == null) {
                $barcode = "x" . $bewerktartikelid . "-" . date("Y");
            }
            $artikel->barcode = $barcode;
            if ($archief == true || $archief == "true") {
                $artikel->archief = 1;
            } else {
                $artikel->archief = 0;
            }

///////////////////////////////////////////////
            // fileupload
            if (isset($_FILES['userfile']) && is_uploaded_file($_FILES['userfile']['tmp_name'][0])) {

                $config['upload_path'] = 'application/images/artikels';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '10000000000000';
                $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

                $this->load->model('artikelfoto_model');
                $name_array = array();
                if (isset($_FILES['userfile'])) {
                    $count = count($_FILES['userfile']['size']);
                    $files = $_FILES;                    

                    // enkel hoofdoto wordt hier geupload, OVERWRITE TRUE
                    for ($t = 0; $t <= $count - 1; $t++) {
                        $_FILES['userfile']['name'] = $files['userfile']['name'][$t];
                        $_FILES['userfile']['type'] = $files['userfile']['type'][$t];
                        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$t];
                        $_FILES['userfile']['error'] = $files['userfile']['error'][$t];
                        $_FILES['userfile']['size'] = $files['userfile']['size'][$t];
                        $config['upload_path'] = 'application/images/artikels/large';
                        $config['overwrite'] = TRUE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        //zoek extensie
                        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.                        
                        $filepath = $upload_data['full_path'];
                        
                        // rotate here and and the smaller image will also be rotated                        
                        $this->rotateImage($filepath);

                        // Set watermark on rotated image
                        $this->setwatermark('application/images/artikels/large/' . $upload_data['file_name']);

                        // start resizing
                        $destination = 'application/images/artikels/resized';
                        $this->resizeImage($filepath, $destination);

                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {
                                if ($t == 0) {
                                    $artikel->imagePath = "images/artikels/resized/" . str_replace(' ', '_', $fotonaam) . '' . $file_ext;
                                    $artikel->largeImagePath = "images/artikels/large/" . str_replace(' ', '_', $fotonaam) . '' . $file_ext;
                                    $mainImageAangepast = true;
                                }
                            }
                        }
                        //}
                    }
                }
            }

            //////////////////////////////////////////////////
            // fileupload EXTRA

            if (isset($_FILES['userfileextra']) && is_uploaded_file($_FILES['userfileextra']['tmp_name'][0])) {
                $config['upload_path'] = 'application/images/artikels';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '10000000000000';
                $config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!

                if (isset($_FILES['userfileextra'])) {
                    $count = count($_FILES['userfileextra']['size']);
                    $files = $_FILES;
                    // RESIZE HIER
                    $this->load->library('image_lib');

                    //voor extra fotos
                    for ($s = 0; $s <= $count - 1; $s++) {
                        $_FILES['userfile']['name'] = $files['userfileextra']['name'][$s];
                        $_FILES['userfile']['type'] = $files['userfileextra']['type'][$s];
                        $_FILES['userfile']['tmp_name'] = $files['userfileextra']['tmp_name'][$s];
                        $_FILES['userfile']['error'] = $files['userfileextra']['error'][$s];
                        $_FILES['userfile']['size'] = $files['userfileextra']['size'][$s];
                        $config['upload_path'] = 'application/images/artikels/large';
                        $config['overwrite'] = FALSE;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        $this->upload->do_upload();

                        // get all upload data such as full_path
                        $upload_data = $this->upload->data();
                        $filepath = $upload_data['full_path'];

                        // rotate here and and the smaller image will also be rotated                        
                        $this->rotateImage($filepath);

                        // Set watermark on rotated image
                        $this->setwatermark('application/images/artikels/large/' . $upload_data['file_name']);

                        // start resizing
                        $destination = 'application/images/artikels/resized';
                        $this->resizeImage($filepath, $destination);

                        if (isset($upload_data) && $upload_data != null) {
                            $file_ext = $upload_data['file_ext'];
                            if ($file_ext != '' && $file_ext != null) {
                                // 8/02/16 change voor extra fotos apart! 
                                // aangepast voor fout 18/01 --> vroger lastid + 1 bij artikelID
                                $artikelFoto = new stdClass();
                                $artikelFoto->artikelId = $bewerktartikelid;
                                $artikelFoto->imagePath = '/images/artikels/resized/' . $upload_data['file_name'];
                                $artikelFoto->largeImagePath = '/images/artikels/large/' . $upload_data['file_name'];
                                $this->artikelfoto_model->insert($artikelFoto);
                            }
                        }
                    }
                }
            }

            $this->artikel_model->update($artikel);

            $data['bewerkt'] = 'Artikel is aangepast!';
            $this->artikels(true);
        } else {
            $this->noAccess();
        }
    }

    public function tekstenaanpassen() {
        $this->load->model('tekst_model');
        $teksten = $this->tekst_model->getAll();
        if ($this->isAdmin()) {
            foreach ($teksten as $tekst) {
                $tekst->tekst = $this->input->post(str_replace(' ', '', $tekst->naam));
                $tekst->tekstgrootte = $this->input->post(str_replace(' ', '', $tekst->naam . "tekstgrootte"));
                $this->tekst_model->update($tekst);
            }

            $data['bewerkt'] = 'Tekst is aangepast!';
            $this->index(true);
        } else {
            $this->noAccess();
        }
    }

    public function downloadnieuwsbriefinschrijvingen() {
        // Load the DB utility class
        if ($this->isAdmin()) {
            $this->load->dbutil();

            $query = $this->db->query("SELECT DISTINCT naam, email FROM nieuwsbriefinschrijving");

            $email = $this->dbutil->csv_from_result($query);

            //str_replace('"', '', $email);
            //Load the download helper and send the file to your desktop
            $this->load->helper('download');
            force_download('email.csv', $email);
        } else {
            $this->noAccess();
        }
    }

    public function testupload() {
        $config['upload_path'] = 'application/images/artikels';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '10000000000000';
        //$config['file_name'] = $fotonaam; // dit vervangt automatisch spaties door _ !!!!
        //$config['max_width'] = '1024';
        //$config['max_height'] = '768';
        //$this->load->library('upload');
        $artikelFoto = new stdClass();
        $nummer = 0;

        $name_array = array();
        $count = count($_FILES['userfile']['size']);
        foreach ($_FILES as $key => $value) {
            for ($s = 0; $s <= $count - 1; $s++) {
                $_FILES['userfile']['name'] = $value['name'][$s];
                $_FILES['userfile']['type'] = $value['type'][$s];
                $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
                $_FILES['userfile']['error'] = $value['error'][$s];
                $_FILES['userfile']['size'] = $value['size'][$s];
                $config['upload_path'] = 'application/images/artikels';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '100';
                $config['max_width'] = '1024';
                $config['max_height'] = '768';
                $this->load->library('upload', $config);
                $this->upload->do_upload();
                $data = $this->upload->data();
                $name_array[] = $data['file_name'];
            }
        }
        $names = implode(',', $name_array);
        /* 	$this->load->database();
          $db_data = array('id'=> NULL,
          'name'=> $names);
          $this->db->insert('testtable',$db_data);
         */ print_r($names);
    }

    public function checkbarcode() {
        $barcode = $this->input->get('barcode');

        $this->load->model('artikel_model');
        $barcodeExists = $this->artikel_model->checkIfBarcodeExists($barcode);
        //$data['barcodeExists'] = $barcodeExists;
        //$this->load->view('home_artikels', $data);
        echo "" . $barcodeExists;
        //return $barcodeExists;
    }

    public function checkkortingcode() {
        $kortingcode = $this->input->get('kortingcode');

        $this->load->model('kortingcode_model');
        $kortingcode = $this->kortingcode_model->checkIfKortingcodeExists($kortingcode);
        //$data['barcodeExists'] = $barcodeExists;
        //$this->load->view('home_artikels', $data);
        echo "" . $kortingcode;
        //return $barcodeExists;
    }

    public function faq() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - FAQ';
        if ($this->isAdmin()) {
            $this->load->model('faq_model');
            $faqs = $this->faq_model->getAll();
            $data['faqs'] = $faqs;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_faq', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function faq_toevoegen() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - FAQ';
        if ($this->isAdmin()) {
            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_nieuw_faq', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function deletefaqbyid() {
        $id = $this->input->get('id');

        $this->load->model("faq_model");
        $this->faq_model->delete($id);
    }

    public function faqtoevoegen() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - FAQ';
        if ($this->isAdmin()) {
            $vraag = $this->input->post('vraag');
            $antwoord = $this->input->post('antwoord');

            $this->load->model('faq_model');

            $faq = new stdClass();
            $faq->vraag = $vraag;
            $faq->antwoord = $antwoord;

            $this->faq_model->insert($faq);
            $this->faq_toevoegen();
        } else {
            $this->noAccess();
        }
    }

    public function resize() {
        if ($this->isAdmin()) {
            $this->load->view('resize');
        } else {
            $this->noAccess();
        }
    }

    public function bestellingen() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Bestellingen';
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $bestellingen = $this->bestelling_model->getLastAmountWithInfo(50);
            $data['bestellingen'] = $bestellingen;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_bestellingen', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function bestelling($id) {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = 'Admin - Bestellingen';
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $bestellingen = $this->bestelling_model->getWithInfo($id);
            $data['bestellingen'] = $bestellingen;

            $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_bestellingen', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } else {
            $this->noAccess();
        }
    }

    public function archiveerbestelling() {
        $id = $this->input->get('bestellingid');
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $this->bestelling_model->archiveer($id);
        } else {
            $this->noAccess();
        }
    }

    public function verzendbestelling() {
        $id = $this->input->get('bestellingid');
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $this->bestelling_model->verzend($id);
        } else {
            $this->noAccess();
        }
    }

    public function betaalbestelling() {
        $id = $this->input->get('bestellingid');
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $this->bestelling_model->betaal($id);
        } else {
            $this->noAccess();
        }
    }

    public function annuleerbestelling() {
        $id = $this->input->get('bestellingid');
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $this->bestelling_model->annuleer($id);
        } else {
            $this->noAccess();
        }
    }

    public function reserveerbestelling() {
        $id = $this->input->get('bestellingid');
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $this->bestelling_model->reserveer($id);
        } else {
            $this->noAccess();
        }
    }

    public function remindbestelling() {
        $id = $this->input->get('bestellingid');
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $this->bestelling_model->remind($id);
        } else {
            $this->noAccess();
        }
    }

    public function grafiekbezoekersperuur() {
        if ($this->isAdmin()) {
            $this->load->model('bezoeker_model');
            $this->load->model('bezoekerhit_model');
            $bezoekers = $this->bezoeker_model->getBezoekersVandaag();
            //$bezoekersall = $this->bezoeker_model->getAll(); // Yeah this gets out of hand when you got 100k+ visitors
            $bezoekersallCount = $this->bezoeker_model->getAllCount();
            $bezoekerhits = $this->bezoekerhit_model->getBezoekersVandaag();
            //$bezoekerhitsall = $this->bezoekerhit_model->getAll(); // Yeah this gets out of hand when you got 100k+ visitors
            $bezoekerhitsallCount = $this->bezoekerhit_model->getAllCount(); // Yeah this gets out of hand when you got 100k+ visitors
            $lastbezoeker = $this->bezoeker_model->getLastBezoeker();

            // bezoekers gisteren opvragen
            $date = date('Y-m-d 00:00:00');
            $date1 = str_replace('-', '/', $date);
            $yesterday = date('Y-m-d 00:00:00', strtotime($date1 . "-1 days"));
            $bezoekersGisteren = $this->bezoeker_model->getBezoekersByYmdDate($yesterday);

            // totaal bezoekers
            $lastbezoekerhit = $this->bezoekerhit_model->getLastBezoeker();

            $data['bezoekers'] = $bezoekers;
            $data['bezoekerhitsvandaag'] = $bezoekerhits;
            $data['bezoekersallCount'] = $bezoekersallCount;
            $data['bezoekerhitsallCount'] = $bezoekerhitsallCount;
            $data['lastbezoeker'] = $lastbezoeker;
            $data['bezoekersGisteren'] = $bezoekersGisteren;

            $data['lastbezoekerhit'] = $lastbezoekerhit;
            $this->load->view('admin_grafiek', $data);
        } else {
            $this->noAccess();
        }
    }

    public function grafiekbestellingenpermaand() {
        if ($this->isAdmin()) {
            $this->load->model('bestelling_model');
            $bestellingen = $this->bestelling_model->getAllWithInfoOokArchief();
            $data['bestellingen'] = $bestellingen;

            $this->load->view('admin_grafiek_bestellingen', $data);
        } else {
            $this->noAccess();
        }
    }

    public function topproducten() {
        if ($this->isAdmin()) {
            $this->load->model('artikel_model');
            $artikels = $this->artikel_model->getMostViewed(20);
            $data['artikels'] = $artikels;

            $this->load->view('admin_top_producten', $data);
        } else {
            $this->noAccess();
        }
    }

    public function grafiekleeftijdenbestellers() {
        if ($this->isAdmin()) {
            $this->load->model('persoon_model');
            $personen = $this->persoon_model->getAllUnique();
            $data['personen'] = $personen;

            $this->load->view('admin_grafiek_leeftijd', $data);
        } else {
            $this->noAccess();
        }
    }

    public function savetimer() {
        $date = $this->input->get('date');

        $this->load->model("setting_model");
        $setting = $this->setting_model->get(1);

        $setting->countdownEndDate = $date;

        if ($setting->countdownEnabled == 1) {
            $setting->countdownEnabled = 0;
        } else {
            $setting->countdownEnabled = 1;
        }

        $this->setting_model->update($setting);
    }

    public function savetransport() {
        $transportkost = $this->input->get('transportkost');
        $taxvrijlimiet = $this->input->get('taxvrijlimiet');

        $this->load->model("setting_model");
        $setting = $this->setting_model->get(1);

        $setting->taxvrijlimiet = $taxvrijlimiet;
        $setting->transportkost = $transportkost;

        $this->setting_model->update($setting);
    }

    public function savealgemenekorting() {
        $algemenekorting = $this->input->get('algemenekorting');

        $this->load->model("setting_model");
        $setting = $this->setting_model->get(1);

        $setting->algemenekorting = $algemenekorting;

        $this->setting_model->update($setting);
        // set alle kortingen op dit!!!
        $this->load->model("artikel_model");
        $this->artikel_model->setKortingForAll($algemenekorting);
    }

    public function kortingscodes() {
        if ($this->isAdmin()) {
            $this->load->model("kortingcode_model");
            $codes = $this->kortingcode_model->getAll();

            $data['codes'] = $codes;
            $this->load->view('admin_kortingscodes', $data);
        } else {
            $this->noAccess();
        }
    }

    public function savekortingscode() {
        $code = $this->input->get('code');
        $korting = $this->input->get('korting');
        $procent = $this->input->get('procent');
        $multiuse = $this->input->get('multiuse');

        $this->load->model("kortingcode_model");
        $kortingcode = new stdClass();

        if ($procent == 'true') {
            $kortingcode->kortingProcent = $korting;
        } else {
            $kortingcode->kortingBedrag = $korting;
        }

        $kortingcode->gebruikt = 0;
        $kortingcode->code = $code;
        if ($multiuse == 'true') {
            $kortingcode->multiUse = 1;
        } else {
            $kortingcode->multiUse = 0;
        }


        $this->kortingcode_model->insert($kortingcode);
    }

    public function deletekortingscodebyid() {
        $id = $this->input->get('id');

        $this->load->model("kortingcode_model");
        $this->kortingcode_model->delete($id);
    }

    public function betalingMetMollieTerugbetalen() {
        if ($this->isAdmin()) {
            $bestellingId = $this->input->get('bestellingId');
            $amount = $this->input->get('terugbetaalbedrag');
            try {
                require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

                $mollie = new Mollie_API_Client;
                // Dit is de TEST API KEY
                // $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
                // Dit is de LIVE API KEY
                $mollie->setApiKey(global_mollieAPIKeyLive);

                $this->load->model('bestelling_model');
                $bestelling = $this->bestelling_model->get($bestellingId);

                $bestelling->terugBetaaldBedrag += $amount;
                $this->bestelling_model->update($bestelling);


                $payment_id = $bestelling->mollieId;

                /*
                 * Load the payment
                 */
                $payment = $mollie->payments->get($payment_id);

                /*
                 * Optional amount, if no amount is provided the total
                 * payment amount will be refunded
                 */

                /*
                 * Refund the payment
                 */
                if ($amount == 0 || $amount == null || $amount = '') {
                    $refund = $mollie->payments->refund($payment);
                } else {
                    $refund = $mollie->payments->refund($payment, $amount);
                }


                echo "Payment $payment_id is now refunded.", PHP_EOL;
            } catch (Mollie_API_Exception $e) {
                echo "API call failed: " . htmlspecialchars($e->getMessage());
            }
        } else {
            $this->noAccess();
        }
    }

    public function deleteextrafoto() {
        if ($this->isAdmin()) {
            // extra foto id
            $id = $this->input->get('id');
            $this->load->model('artikelfoto_model');
            $extraFoto = $this->artikelfoto_model->get($id);

            unlink(APPPATH . $extraFoto->imagePath);
            $this->artikelfoto_model->delete($id);
            return true;
        } else {
            $this->noAccess();
        }
    }

    public function deleteextraoutfitfoto() {
        if ($this->isAdmin()) {
            // extra foto id
            $id = $this->input->get('id');
            $this->load->model('outfitfoto_model');
            $extraFoto = $this->outfitfoto_model->get($id);

            unlink(APPPATH . $extraFoto->imagePath);
            $this->outfitfoto_model->delete($id);
            return true;
        } else {
            $this->noAccess();
        }
    }

    public function testdelete() {
        unlink(APPPATH . "images/artikels/ok.JPG");
    }

    public function confirmOrderForBpostAjax() {
        $land = $this->input->get('land');
        $bestellingId = $this->input->get('bestellingId');

        // BPOST HACK
        $hashstring = 'accountId=' . global_bpostid . '&action=CONFIRM&customerCountry=' . $land . '&orderReference=' . $bestellingId . '&' . global_bpostww;
        $hash = hash('sha256', $hashstring);
        $data["hash"] = $hash;
        $data["orderReference"] = $bestellingId;
        $data["customerCountry"] = $land;
        $data["bpostid"] = global_bpostid;

        $this->load->view('confirm_bpost_ajax', $data);
    }

    /* This function is made to show a popup anywhere in te adminpage if there is a new order */

    public function checkneworders() {
        $lastchecktime = $this->input->get('lastchecktime');
        $this->load->model('bestelling_model');
        $bestellingen = $this->bestelling_model->checkforneworder($lastchecktime);
        /* set last checktime session to be 99% accurate */
        $this->session->set_userdata('lastchecktimeorders', date('Y-m-d H:i:s a'));

        /* We make a little string with order data to send to the user, so he/she can easily navigate to the order */
        $string = "";
        $header = "<div id='popupHead'>";
        $body = "";
        if (count($bestellingen) > 0) {
            if (count($bestellingen) == 1) {
                //$header .= count($bestellingen) . " nieuwe bestelling!<br/><br/>";
                $header .= "<b>Nieuwe bestelling!</b><br/><br/>";
            } else {
                //$header .= count($bestellingen) . " nieuwe bestellingen!<br/><br/>";
                $header .= "<b>Nieuwe bestelling!</b><br/><br/>";
            }
        }
        $header .= "</div>";
        foreach ($bestellingen as $bestelling) {
            $body .= anchor('admin/bestelling/' . $bestelling->id, "Bestellingnummer: " . $bestelling->id) . "<br/>";
        }
        //$body .= "</div>";
        if ($header == "<div id='popupHead'></div>") {
            $string = $this->session->userdata('lastchecktimeorders');
        } else {
            $string = $this->session->userdata('lastchecktimeorders') . '[split]' . $body . '[split]' . $header;
        }

        echo $string;
    }

    public function stockchanges() {
        $data['title'] = global_bedrijfsnaam . " - Admin";
        $data['pagina'] = "Admin - Artikels";

        $partials = array('header' => 'templ/main_header_admin', 'content' => 'admin_stockchanges', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function stockchangesajax() {
        $barcode = $this->input->get('barcode');
        $startdate = $this->input->get('startdate');
        $enddate = $this->input->get('enddate');
        $this->load->model('stockchange_model');
        $stockchanges = $this->stockchange_model->getByBarcodeOrTimespanWithData($barcode, $startdate, $enddate);

        $data['stockchanges'] = $stockchanges;

        $this->load->view('admin_stockchanges_ajax', $data);
    }

    function setwatermark($src) {
        // IMG LIB HIER LADEN
        $this->load->library('image_lib');
        $paths = $this->session->userdata('paths');
        $paths .= $src;
        $this->session->set_userdata('paths', $paths);
        //$this->load->library('image_lib');
        $configx['image_library'] = 'gd2';
        //$configx['source_image'] = 'application/images/artikels/test.jpg';
        $configx['source_image'] = $src;
        //$configx['wm_font_path'] = 'application/fonts/League_Gothic-webfont.ttf';
        $configx['wm_text'] = 'Chelsey';
        $configx['wm_type'] = 'text';
        $configx['wm_font_size'] = '50';
        $configx['wm_vrt_alignment'] = 'bottom';
        $configx['wm_hor_alignment'] = 'right';
        $configx['wm_padding'] = -50;
        /* $configx['wm_hor_offset'] = '20';
          $configx['wm_vrt_offset'] = '20'; */
        $this->image_lib->clear();
        $this->image_lib->initialize($configx);
        if (!$this->image_lib->watermark()) {
            echo $this->image_lib->display_errors();
        }
    }

    function rotateImage($src) {
         // IMG LIB HIER LADEN
        $this->load->library('image_lib');
        // Extract EXIF data
        $exif = @exif_read_data($src);
        // ROTATE
        if (empty($exif['Orientation'])) {
            // GEEN EXIF DATA, NIET ROTATEN
        } else {
            // WEL EXIFDATA, ROTATEN
            $config3['image_library'] = 'gd2';
            $config3['source_image'] = $src;

            $oris = array();

            switch ($exif['Orientation']) {
                case 1: // no need to perform any changes
                    break;

                case 2: // horizontal flip
                    $oris[] = 'hor';
                    break;

                case 3: // 180 rotate left
                    $oris[] = '180';
                    break;

                case 4: // vertical flip
                    $oris[] = 'ver';
                    break;

                case 5: // vertical flip + 90 rotate right
                    $oris[] = 'ver';
                    $oris[] = '270';
                    break;

                case 6: // 90 rotate right
                    $oris[] = '270';
                    break;

                case 7: // horizontal flip + 90 rotate right
                    $oris[] = 'hor';
                    $oris[] = '270';
                    break;

                case 8: // 90 rotate left
                    $oris[] = '90';
                    break;

                default: break;
            }

            foreach ($oris as $ori) {
                $config3['rotation_angle'] = $ori;
                $this->image_lib->clear();
                $this->image_lib->initialize($config3);
                $this->image_lib->rotate();
            }
        }
        // END ROTATE 
    }

    function resizeImage($src, $dest) {
        // IMG LIB HIER LADEN
        $this->load->library('image_lib');
        $config2['image_library'] = 'gd2';
        $config2['source_image'] = $src;
        $config2['maintain_ratio'] = TRUE;
        $config2['width'] = 1024;
        $config2['height'] = 768;
        $config2['new_image'] = $dest;
        $this->image_lib->clear();
        $this->image_lib->initialize($config2);
        $this->image_lib->resize();
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
