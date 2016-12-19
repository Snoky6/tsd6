<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Testcontroller extends CI_Controller {

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

    public function index() {
        $this->load->model('setting_model');
        $setting = $this->setting_model->get(1);
        if ($setting->countdownEnabled == 1) {
            $this->timer($setting->countdownEndDate);
        } else {
            $this->karretje();
        }
    }

    private function haalopkarretje() {
        if (!$this->session->userdata('karretje')) {
            return array();
        } else {
            return $this->session->userdata('karretje');
        }
    }

    public function voegtoe($id) {
        $karretje = $this->haalopkarretje();
        if (isset($karretje[$id])) {
            $karretje[$id] ++;
        } else {
            $karretje[$id] = 1;
        }
        $this->session->set_userdata('karretje', $karretje);

        redirect('/winkelmandje/karretje', 'refresh');
    }

    public function voegtoemetmaat2() {
        $waar = "1 begin";
        $maatId = $this->input->post('maat');
        $artikelId = $this->input->post('artikelId');
        $this->load->model('maat_model');
        $this->load->model('artikel_model');
        $artikel = $this->artikel_model->getSolo($artikelId);
        $maat = $this->maat_model->get($maatId);

        $karretje = $this->haalopkarretje();

        $createId = '' . $artikelId . "x" . $maatId; //zo maak je een unieke id
        $winkelkarArtikel = new stdClass();
        $winkelkarArtikel->createid = $createId; // id toevoegen
        $winkelkarArtikel->artikel = $artikel;
        $winkelkarArtikel->maat = $maat;

        $winkelkarArtikel->aantal = 1;
        array_push($karretje, $winkelkarArtikel);

// test data
        $data['categorien'] = $this->loadCategorienForMenu();
        $data['karretje'] = $karretje;

        $this->session->set_userdata('karretje', $winkelkarArtikel);

        $partials = array('header' => 'templ/main_header', 'content' => 'test', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
//redirect('/winkelmandje/karretje', 'refresh');
    }

    public function voegtoemetmaat() {
        $maatId = $this->input->post('maat');
        $artikelId = $this->input->post('artikelId');
        $this->load->model('maat_model');
        $this->load->model('artikel_model');

        $artikel = $this->artikel_model->getSolo($artikelId);
        $maat = $this->maat_model->get($maatId);

        $karretje = null;
        $karretje = $this->haalopkarretje();

        $createId = '' . $artikelId . "x" . $maatId; //zo maak je een unieke id

        /* $winkelkarArtikel = new stdClass();
          $winkelkarArtikel->createid = $createId; // id toevoegen
          $winkelkarArtikel->artikel = $artikel;
          $winkelkarArtikel->maat = $maat; */

// minder waarden voor de sessie kleiner te maken
        $winkelkarArtikel = new stdClass();
        $winkelkarArtikel->createid = $createId; // id toevoegen
        $winkelkarArtikel->artikelId = $artikelId;
        $winkelkarArtikel->maatId = $maatId;

        $winkelkarArtikel->aantal = 1;

        $zaterin = "nee";
        if (isset($karretje) && $karretje != NULL) {
            foreach ($karretje as $karitem) {
                if ($karitem->createid == $createId) {
// zit al in karretje
                    $zaterin = "ja";
                    $karitem->aantal++;
                }
            }
        } else {
            
        }

        if ($zaterin == "nee") {
// zat nog niet in karretje, dus toevoegen           
            array_push($karretje, $winkelkarArtikel);
        }

        $this->session->set_userdata('karretje', $karretje);

        redirect('/winkelmandje/karretje', 'refresh');
    }

    public function karretje() {
        $data['title'] = 'Dulani';
        $data['pagina'] = 'Winkelmandje';
        $karretje = $this->haalopkarretje();
        $data['karretje'] = $karretje; // waarom dat hier staat en werkt weet niemand :)
        $this->load->model('artikel_model');
        $this->load->model('maat_model');
//$data['artikels'] = $this->artikel_model->getProductenInKarretje($karretje);

        foreach ($karretje as $karitem) {
            $artikel = $this->artikel_model->getSolo($karitem->artikelId);
            $maat = $this->maat_model->get($karitem->maatId);
            $karitem->artikel = $artikel;
            $karitem->maat = $maat;
        }

        $this->load->model('setting_model');
        $setting = $this->setting_model->get(1);
        $data['transportkost'] = $setting->transportkost;
        $data['taxvrijlimiet'] = $setting->taxvrijlimiet;

        $this->load->model('onlangsbekeken_model');
        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();

        $partials = array('header' => 'templ/main_header', 'content' => 'winkelmandje', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function betalen() {
        $data['title'] = 'Dulani';
        $data['pagina'] = 'Afrekenen';
        $partials = array('header' => 'templ/main_header', 'content' => 'afrekenen', 'footer' => 'templ/main_footer');
        $karretje = $this->haalopkarretje();
        $data['karretje'] = $karretje;
        $this->load->model('artikel_model');
        $this->load->model('artikelmaat_model');
        $this->load->model('maat_model');
//$data['artikels'] = $this->artikel_model->getProductenInKarretje($karretje);           

        foreach ($karretje as $karitem) {
            $aantalInStock = $this->artikelmaat_model->getAmountInStock($karitem->artikelId, $karitem->maatId);
            if ($karitem->aantal > $aantalInStock) {
// uit stock ga naar foutpagina
                $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                $maat = $this->maat_model->get($karitem->maatId);
                $data["artikel"] = $artikel;
                $data["maat"] = $maat;
                $data["aantalInStock"] = $aantalInStock;
                $data["title"] = "Uit stock";
                $data["pagina"] = "Uit stock";
                $partials = array('header' => 'templ/main_header', 'content' => 'uit_stock', 'footer' => 'templ/main_footer');
//$this->template->load('main_master', $partials, $data);          
// verwijder uit mandje
                $this->verwijderUitStock($karitem->createid);
            }
            $artikel = $this->artikel_model->getSolo($karitem->artikelId);
            $maat = $this->maat_model->get($karitem->maatId);
            $karitem->artikel = $artikel;
            $karitem->maat = $maat;
        }


        $this->load->model('setting_model');
        $setting = $this->setting_model->get(1);
        $data['transportkost'] = $setting->transportkost;
        $data['taxvrijlimiet'] = $setting->taxvrijlimiet;

        $this->load->model('onlangsbekeken_model');
        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();

//echo "echoke ";
//echo count($karretje);
// partials verplaats naar boven!!
        $this->template->load('main_master', $partials, $data);
    }

    public function leeg() {
        $this->session->unset_userdata('karretje');

        redirect('/winkelmandje/index', 'refresh');
    }

    public function verwijder_old($id) {
        $karretje = $this->haalopkarretje();
        if ($karretje[$id] > 1) {
            $karretje[$id] --;
        } else {
            unset($karretje[$id]);
        }
        $this->session->set_userdata('karretje', $karretje);

        redirect('/winkelmandje/index', 'refresh');
    }

    public function verwijder($id) {
        $karretje = $this->haalopkarretje();

        for ($x = 0; $x <= count($karretje) + 1; $x++) {
            if (isset($karretje[$x])) {
                if ($karretje[$x]->createid == $id) {
// zit al in karretje 
                    if ($karretje[$x]->aantal > 1) {
                        $karretje[$x]->aantal--;
                    } else {
                        unset($karretje[$x]);
                    }
                }
            }
        }

        $this->session->set_userdata('karretje', $karretje);

        redirect('/winkelmandje/karretje', 'refresh');
    }

    public function verwijderUitStock($id) {
        $karretje = $this->haalopkarretje();

        for ($x = 0; $x <= count($karretje) + 1; $x++) {
            if (isset($karretje[$x])) {
                if ($karretje[$x]->createid == $id) {
// zit al in karretje 

                    unset($karretje[$x]);
                }
            }
        }

        $this->session->set_userdata('karretje', $karretje);
    }

    public function loadCategorienForMenu() {
//get all categorien voor menu
        $this->load->model('categorie_model');
        $categorien = $this->categorie_model->getAllWithSub();

        return $categorien;
    }

    public function bestellingplaatsen() {
//$this->session->set_userdata('postdata', $_POST);
        $karretje = $this->haalopkarretje();
        if (count($karretje) < 1 || $this->input->post('email') == '') {
            $this->index();
        } else {
            // MOLLIE
            if ($this->input->post('submitmollie') != null || $this->input->post('submitmollie') != "") {
// nieuwe sessies voor bug op te lossen
                $naam = $this->input->post('naam');
                $straat = $this->input->post('straat');
                $huisnr = $this->input->post('nr');
                $geboortedatum = $this->input->post('geboortedatum');
                $postcode = $this->input->post('postcode');
                $woonplaats = $this->input->post('woonplaats');
                $telefoon = $this->input->post('telefoon');
                $email = $this->input->post('email');
                $opmerkingen = $this->input->post('opmerkingen');

// nieuw nieuwsbrief / land
                $brief = $this->input->post('nieuwsbrief');
                $land = $this->input->post('land');

// kortingcode
                $kortingscode = $this->input->post('kortingcode');

                if ($brief == "ja") {
                    $nieuwsbriefinschrijving = new stdClass();
                    $nieuwsbriefinschrijving->naam = $naam;
                    $nieuwsbriefinschrijving->email = $email;

                    $this->load->model('nieuwsbriefinschrijving_model');
                    $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                }

// alt leveradres
                if ($this->input->post('levstraat') === '') {
// geen leveradres
                    $levstraat = $straat;
                    $levhuisnr = $huisnr;
                    $levpostcode = $postcode;
                    $levwoonplaats = $woonplaats;
                    $levnaam = $naam;
                    $levcontactpersoon = $naam;
                    $levland = $land;
                } else {
                    $levstraat = $this->input->post('levstraat');
                    $levhuisnr = $this->input->post('levnr');
                    $levpostcode = $this->input->post('levpostcode');
                    $levwoonplaats = $this->input->post('levwoonplaats');
                    $levnaam = $this->input->post('levnaam');
                    $levcontactpersoon = $this->input->post('levcontactpersoon');
                    $levland = $this->input->post('levland');
                }

                $this->session->set_flashdata('naam', $naam);
                $this->session->set_flashdata('straat', $straat);
                $this->session->set_flashdata('huisnr', $huisnr);
                $this->session->set_flashdata('geboortedatum', $geboortedatum);
                $this->session->set_flashdata('postcode', $postcode);
                $this->session->set_flashdata('woonplaats', $woonplaats);
                $this->session->set_flashdata('telefoon', $telefoon);
                $this->session->set_flashdata('land', $land);
//$this->session->set_flashdata('email', $email);
                $this->session->set_userdata('email', $email);
                $this->session->set_flashdata('opmerkingen', $opmerkingen);
                $this->session->set_flashdata('levstraat', $levstraat);
                $this->session->set_flashdata('levhuisnr', $levhuisnr);
                $this->session->set_flashdata('levpostcode', $levpostcode);
                $this->session->set_flashdata('levwoonplaats', $levwoonplaats);
                $this->session->set_flashdata('levnaam', $levnaam);
                $this->session->set_flashdata('levcontactpersoon', $levcontactpersoon);
                $this->session->set_flashdata('levland', $levland);
                $this->session->set_flashdata('kortingscode', $kortingscode);

// prijs
                $karretje = $this->haalopkarretje();
                $this->load->model("artikel_model");
                $this->load->model('maat_model');
                $this->load->model('setting_model');
//$data['artikels'] = $this->artikel_model->getProductenInKarretje($karretje);

                foreach ($karretje as $karitem) {
                    $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                    $maat = $this->maat_model->get($karitem->maatId);
                    $karitem->artikel = $artikel;
                    $karitem->maat = $maat;
                }
                $totaalprijs = 0.00;
                $artikelString = "";
                foreach ($karretje as $karitem) {
                    $artikel = $this->artikel_model->getSolo($karitem->artikelId);
// korting toekennen
                    $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));
                    $totaalprijs += $artikel->prijs * $karitem->aantal;
                    $artikelString .= $karitem->artikel->naam . " (" . $karitem->aantal . ") - ";
                }
                $setting = $this->setting_model->get(1);
                if ($totaalprijs < $setting->taxvrijlimiet) {
                    $totaalprijs+= $setting->transportkost;
                } else {
                    
                }

//kortingcode valideren en pas updaten na betaling
                $this->load->model('kortingcode_model');
                $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                if (isset($code->id)) {
                    if ($code->kortingBedrag == null) {
                        $totaalPrijsVoorKorting = $totaalprijs;
                        $kortingPercentage = $code->kortingProcent;
                        $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                    } else {
                        $totaalPrijsVoorKorting = $totaalprijs;
                        $kortingBedrag = $code->kortingBedrag;
                        $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                    }
                    $totaalprijs = $totaalPrijsNaKorting;
                    if ($totaalprijs < 0) {
                        $totaalprijs = 0;
                    }
                } // nu nog na betalen                

                $artikelString = substr($artikelString, 0, -3);
//$this->session->set_userdata('artikelString', $artikelString);
//$this->session->set_userdata('totaalprijs', $totaalprijs);
                $data["totaalprijs"] = $totaalprijs;
                $data["artikelString"] = $artikelString;
// einde nieuwe sessies
                //$redirectURL = "http://localhost:88/DulaniWebshop/index.php/winkelmandje/bestellingplaatsenmollie";
                $redirectURL = "http://www.dulani.be/index.php/winkelmandje/bestellingplaatsenmollie";

                $this->betaalmetmollie($totaalprijs, $artikelString, $redirectURL);
            } else {
                // END MOLLIE
                if ($this->input->post('submitpaypal') != null || $this->input->post('submitpaypal') != "") {
// nieuwe sessies voor bug op te lossen
                    $naam = $this->input->post('naam');
                    $straat = $this->input->post('straat');
                    $huisnr = $this->input->post('nr');
                    $geboortedatum = $this->input->post('geboortedatum');
                    $postcode = $this->input->post('postcode');
                    $woonplaats = $this->input->post('woonplaats');
                    $telefoon = $this->input->post('telefoon');
                    $email = $this->input->post('email');
                    $opmerkingen = $this->input->post('opmerkingen');

// nieuw nieuwsbrief / land
                    $brief = $this->input->post('nieuwsbrief');
                    $land = $this->input->post('land');

// kortingcode
                    $kortingscode = $this->input->post('kortingcode');

                    if ($brief == "ja") {
                        $nieuwsbriefinschrijving = new stdClass();
                        $nieuwsbriefinschrijving->naam = $naam;
                        $nieuwsbriefinschrijving->email = $email;

                        $this->load->model('nieuwsbriefinschrijving_model');
                        $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                    }

// alt leveradres
                    if ($this->input->post('levstraat') === '') {
// geen leveradres
                        $levstraat = $straat;
                        $levhuisnr = $huisnr;
                        $levpostcode = $postcode;
                        $levwoonplaats = $woonplaats;
                        $levnaam = $naam;
                        $levcontactpersoon = $naam;
                        $levland = $land;
                    } else {
                        $levstraat = $this->input->post('levstraat');
                        $levhuisnr = $this->input->post('levnr');
                        $levpostcode = $this->input->post('levpostcode');
                        $levwoonplaats = $this->input->post('levwoonplaats');
                        $levnaam = $this->input->post('levnaam');
                        $levcontactpersoon = $this->input->post('levcontactpersoon');
                        $levland = $this->input->post('levland');
                    }

                    $this->session->set_flashdata('naam', $naam);
                    $this->session->set_flashdata('straat', $straat);
                    $this->session->set_flashdata('huisnr', $huisnr);
                    $this->session->set_flashdata('geboortedatum', $geboortedatum);
                    $this->session->set_flashdata('postcode', $postcode);
                    $this->session->set_flashdata('woonplaats', $woonplaats);
                    $this->session->set_flashdata('telefoon', $telefoon);
                    $this->session->set_flashdata('land', $land);
//$this->session->set_flashdata('email', $email);
                    $this->session->set_userdata('email', $email);
                    $this->session->set_flashdata('opmerkingen', $opmerkingen);
                    $this->session->set_flashdata('levstraat', $levstraat);
                    $this->session->set_flashdata('levhuisnr', $levhuisnr);
                    $this->session->set_flashdata('levpostcode', $levpostcode);
                    $this->session->set_flashdata('levwoonplaats', $levwoonplaats);
                    $this->session->set_flashdata('levnaam', $levnaam);
                    $this->session->set_flashdata('levcontactpersoon', $levcontactpersoon);
                    $this->session->set_flashdata('levland', $levland);
                    $this->session->set_flashdata('kortingscode', $kortingscode);

// prijs
                    $karretje = $this->haalopkarretje();
                    $this->load->model("artikel_model");
                    $this->load->model('maat_model');
                    $this->load->model('setting_model');
//$data['artikels'] = $this->artikel_model->getProductenInKarretje($karretje);

                    foreach ($karretje as $karitem) {
                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                        $maat = $this->maat_model->get($karitem->maatId);
                        $karitem->artikel = $artikel;
                        $karitem->maat = $maat;
                    }
                    $totaalprijs = 0.00;
                    $artikelString = "";
                    foreach ($karretje as $karitem) {
                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);
// korting toekennen
                        $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));
                        $totaalprijs += $artikel->prijs * $karitem->aantal;
                        $artikelString .= $karitem->artikel->naam . " (" . $karitem->aantal . ") - ";
                    }
                    $setting = $this->setting_model->get(1);
                    if ($totaalprijs < $setting->taxvrijlimiet) {
                        $totaalprijs+= $setting->transportkost;
                    } else {
                        
                    }

//kortingcode valideren en pas updaten na betaling
                    $this->load->model('kortingcode_model');
                    $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                    if (isset($code->id)) {
                        if ($code->kortingBedrag == null) {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingPercentage = $code->kortingProcent;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                        } else {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingBedrag = $code->kortingBedrag;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                        }
                        $totaalprijs = $totaalPrijsNaKorting;
                        if ($totaalprijs < 0) {
                            $totaalprijs = 0;
                        }
                    } // nu nog na betalen

                    /* if ($totaalprijs < 49.99 && $land == "belgie") {
                      $totaalprijs+=8;
                      } elseif ($totaalprijs < 49.99 && $land != "belgie") {
                      $totaalprijs+=8;
                      } else {
                      //gratis levering
                      } */
                    $artikelString = substr($artikelString, 0, -3);
//$this->session->set_userdata('artikelString', $artikelString);
//$this->session->set_userdata('totaalprijs', $totaalprijs);
                    $data["totaalprijs"] = $totaalprijs;
                    $data["artikelString"] = $artikelString;
// einde nieuwe sessies


                    $data['categorien'] = $this->loadCategorienForMenu();
                    $data['title'] = "Bedankt";
                    $data['pagina'] = "Bedankt";
                    $partials = array('header' => 'templ/main_header', 'content' => 'bedankt_paypal', 'footer' => 'templ/main_footer');
                } else {
                    $naam = $this->input->post('naam');
                    $straat = $this->input->post('straat');
                    $huisnr = $this->input->post('nr');
                    $geboortedatum = $this->input->post('geboortedatum');
                    $postcode = $this->input->post('postcode');
                    $woonplaats = $this->input->post('woonplaats');
                    $telefoon = $this->input->post('telefoon');
                    $email = $this->input->post('email');
                    $opmerkingen = $this->input->post('opmerkingen');

// nieuw nieuwsbrief / land
                    $brief = $this->input->post('nieuwsbrief');
                    $land = $this->input->post('land');

                    // kortingcode
                    $kortingscode = $this->input->post('kortingcode');

                    if ($brief == "ja") {
                        $nieuwsbriefinschrijving = new stdClass();
                        $nieuwsbriefinschrijving->naam = $naam;
                        $nieuwsbriefinschrijving->email = $email;

                        $this->load->model('kortingcode_model');
                        $this->load->model('nieuwsbriefinschrijving_model');
                        $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                    }

// alt leveradres
                    if ($this->input->post('levstraat') === '') {
// geen leveradres
                        $levstraat = $straat;
                        $levhuisnr = $huisnr;
                        $levpostcode = $postcode;
                        $levwoonplaats = $woonplaats;
                        $levnaam = $naam;
                        $levcontactpersoon = $naam;
                        $levland = $land;
                    } else {
                        $levstraat = $this->input->post('levstraat');
                        $levhuisnr = $this->input->post('levnr');
                        $levpostcode = $this->input->post('levpostcode');
                        $levwoonplaats = $this->input->post('levwoonplaats');
                        $levnaam = $this->input->post('levnaam');
                        $levcontactpersoon = $this->input->post('levcontactpersoon');
                        $levland = $this->input->post('levland');
                    }

                    $this->load->library('email');
                    $config = array(
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'priority' => '1'
                    );
                    $this->email->initialize($config);

                    $this->email->from('webshop@dulani.be', 'Dulani');
                    $this->email->to($email);

                    $this->email->subject('Dulani webshop');

// message, moet met code en prijs, zie die fucntie
                    $berichtKlantIntro = "";

                    $bericht = "KLANTGEGEVENS<br><br>";
                    $bericht .= "Naam: " . $naam . "<br>";
                    $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
                    $bericht .= "Land: " . $land . "<br>";
                    $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
                    $bericht .= "Postcode: " . $postcode . "<br>";
                    $bericht .= "Woonplaats: " . $woonplaats . "<br>";
                    $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
                    $bericht .= "E-mail: " . $email . "<br><br>";

                    $bericht .= "BESTELGEGEVENS<br><br>";
                    $karretje = $this->haalopkarretje();
                    $this->load->model('artikel_model');
                    $this->load->model('maat_model');

// toevoegen aan database
                    $persoon = new stdClass();
                    $persoon->naam = $naam;
                    $persoon->geboortedatum = $geboortedatum;
                    $persoon->straat = $straat;
                    $persoon->huisnummer = $huisnr;
                    $persoon->postcode = $postcode;
                    $persoon->woonplaats = $woonplaats;
                    $persoon->telefoon = $telefoon;
                    $persoon->email = $email;
                    $persoon->land = $land;

                    $this->load->model('persoon_model');
                    $persoonId = $this->persoon_model->insert($persoon);

                    $bestelling = new stdClass();
                    $bestelling->persoonId = $persoonId;
                    $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 7200); // 2uur bijtellen voor server
                    $bestelling->datum = $date;
//$bestelling->datum = date('Y-m-d H:i:s a');
                    $bestelling->opmerkingen = $opmerkingen;

// alt lever adres
                    $bestelling->leverStraat = $levstraat;
                    $bestelling->leverHuisnummer = $levhuisnr;
                    $bestelling->leverPostcode = $levpostcode;
                    $bestelling->leverGemeente = $levwoonplaats;
                    $bestelling->leverNaam = $levnaam;
                    $bestelling->leverContactpersoon = $levcontactpersoon;
                    $bestelling->leverLand = $levland;
                    $bestelling->paypal = 0;
                    $bestelling->verzonden = 0;
                    $bestelling->betaald = 0;

                    $this->load->model('kortingcode_model');
                    $codeVoorBestelling = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);

                    if ($codeVoorBestelling == null) {
                        $bestelling->kortingCodeID = null;
                    } else {
                        $bestelling->kortingCodeID = $codeVoorBestelling->id;
                    }


                    $this->load->model('bestelling_model');
                    $bestellingId = $this->bestelling_model->insert($bestelling);

//bestellingartikel toevoegen in loop        
                    $this->load->model('bestellingartikel_model');
                    $this->load->model('verkochtartikel_model');
                    $this->load->model('artikelmaat_model');
                    $this->load->model('setting_model');

                    $totaalprijs = 0.00;
                    foreach ($karretje as $karitem) {
                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);

                        // artikel toevoegen aan nieuwe tabel om juiste kortingen te onthouden voor later HIER AL DOEN ANDERS KLOPT PRIJS NIET MEER, ZIE KORTING HIERONDER
                        $artikel->id = null;
                        $verkochtArtikelId = $this->verkochtartikel_model->insert($artikel);

                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);
// korting toekennen                    
                        $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));

                        $totaalprijs += $artikel->prijs * $karitem->aantal;
                        $maat = $this->maat_model->get($karitem->maatId);
                        $karitem->artikel = $artikel;
                        $karitem->maat = $maat;
                        $bericht .= $karitem->aantal . "x " . $artikel->naam . " (maat:" . $maat->maat . ") EUR " . number_format($artikel->prijs * $karitem->aantal, 2) . "<br>";

// toevoegen aan database
                        $bestellingArtikel = new stdClass();
                        $bestellingArtikel->bestellingId = $bestellingId;
                        $bestellingArtikel->verkochtArtikelId = $verkochtArtikelId;
                        $bestellingArtikel->maatId = $maat->id;
                        $bestellingArtikel->aantal = $karitem->aantal;

                        $this->bestellingartikel_model->insert($bestellingArtikel);

// maten wegschrijven / updaten
                        $artikelMaat = new stdClass();
                        $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                        $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                        $this->artikelmaat_model->update($artikelMaat);
                    }

                    $setting = $this->setting_model->get(1);
                    if ($totaalprijs < $setting->taxvrijlimiet) {
                        $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                        $totaalprijs+= $setting->transportkost;
                    } else {
                        $bericht .= "Leverkosten: gratis<br>";
                    }
                    /* if ($totaalprijs < 49.99 && $land == "belgie") {
                      $bericht .= "Leverkosten: EUR 8<br>";
                      $totaalprijs+=8;
                      } elseif ($totaalprijs < 49.99 && $land != "belgie") {
                      $bericht .= "Leverkosten: EUR 8<br>";
                      $totaalprijs+=8;
                      } else {
                      $bericht .= "Leverkosten: gratis<br>";
                      } */


                    // kortingsbon
                    $this->load->model('kortingcode_model');
                    $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                    if (isset($code->id)) {
                        if ($code->kortingBedrag == null) {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingPercentage = $code->kortingProcent;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);

                            // korting weergeven in mail
                            $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingProcent . "%)<br/>";
                        } else {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingBedrag = $code->kortingBedrag;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;

                            // korting weergeven in mail
                            $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingBedrag . " EURO)<br/>";
                        }
                        $totaalprijs = $totaalPrijsNaKorting;

                        if ($totaalprijs < 0) {
                            $totaalprijs = 0;
                        }

                        $code->gebruikt = $code->gebruikt + 1;
                        $this->kortingcode_model->update($code);
                    } // nu nog na betalen

                    $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

                    $bericht .= "OPMERKINGEN<br><br>";
                    $bericht .= $opmerkingen . "<br><br>";

                    $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar webshop@dulani.be of een privé bericht via onze Facebook pagina!";


                    $mededeling = date('d') . date('m') . date('Y') . $bestellingId;
                    $berichtKlantIntro = "Bedankt voor uw aankoop bij Dulani! Gelieve zo snel mogelijk &euro;" . $totaalprijs . " over te schrijven. Van zodra wij uw betaling ontvangen hebben vertrekt uw pakket via B-Post. B-Post levert in 98% van de gevallen binnen de 24u in België en 24/48u in de buurlanden. Indien wij uw betaling na 5 werkdagen nog niet ontvangen hebben wordt uw bestelling automatisch geannuleerd. Om uw betaling te regelen gebruikt u volgende gegevens:<br>";
                    $berichtKlantIntro .= "<ul><li>Rekeningnummer: BE04850848035531</li><li>BIC code: SPAABE22</li><li>Naam: Dulani St-Jozefsstraat 13/2 3500 Hasselt</li>";
                    $berichtKlantIntro .= "<li>Mededeling: " . $mededeling . "</li></ul>";
                    $berichtKlantIntro .= "<br/><br/>Hieronder vindt u de gegevens van uw bestelling terug:<br><br>";

                    $bericht = $berichtKlantIntro . $bericht;

// template opvragen
                    $data['naam'] = $naam;
                    $data['bericht'] = $bericht;
                    $message = $this->load->view('templ/mailcontent', $data, TRUE);

                    $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');


                    $this->email->message($message);
                    $this->email->send();

//stuur naar admin
                    $messageAdmin = "Een klant plaatste een bestelling op de dulani webshop en betaalde via overschrijving. <br/>";
                    $messageAdmin .= "<a href='http://www.dulani.be/index.php/admin/bestelling/" . $bestellingId . "'>Klik op deze link om naar de bestelling te gaan.</a>";
                    $this->email->subject('Dulani webshop - ' . $bestellingId . " - " . $persoon->naam);
                    $this->email->message($messageAdmin);
                    $this->email->to("webshop@dulani.be");
                    $this->email->send();

// stuur klant bericht ook naar admin
                    $this->email->subject('KLANT: Dulani webshop - ' . $bestellingId . " - " . $persoon->naam);
                    $this->email->message($message);
                    $this->email->to("webshop@dulani.be");
                    $this->email->send();

                    $data['email'] = $email;
                    $data['title'] = "Bedankt";
                    $data['pagina'] = "Bedankt";
                    $data['categorien'] = $this->loadCategorienForMenu();

//try {
                    $this->session->unset_userdata('karretje'); // als deze regel uitgevoerd wordt loopt het mis.
//} catch (Exception $ex) {
// echo $ex;                
//}
//unset($_SESSION['karretje']); // Deze regel crasht niet maar doet wel niet wat hij zou moeten doen.
                }
            }
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function bestellingplaatsenORI() {
//$this->session->set_userdata('postdata', $_POST);
        $karretje = $this->haalopkarretje();
        if (count($karretje) < 1 || $this->input->post('email') == '') {
            $this->index();
        } else {
            // MOLLIE
            if ($this->input->post('submitmollie') != null || $this->input->post('submitmollie') != "") {
// nieuwe sessies voor bug op te lossen
                $naam = $this->input->post('naam');
                $straat = $this->input->post('straat');
                $huisnr = $this->input->post('nr');
                $geboortedatum = $this->input->post('geboortedatum');
                $postcode = $this->input->post('postcode');
                $woonplaats = $this->input->post('woonplaats');
                $telefoon = $this->input->post('telefoon');
                $email = $this->input->post('email');
                $opmerkingen = $this->input->post('opmerkingen');

// nieuw nieuwsbrief / land
                $brief = $this->input->post('nieuwsbrief');
                $land = $this->input->post('land');

// kortingcode
                $kortingscode = $this->input->post('kortingcode');

                if ($brief == "ja") {
                    $nieuwsbriefinschrijving = new stdClass();
                    $nieuwsbriefinschrijving->naam = $naam;
                    $nieuwsbriefinschrijving->email = $email;

                    $this->load->model('nieuwsbriefinschrijving_model');
                    $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                }

// alt leveradres
                if ($this->input->post('levstraat') === '') {
// geen leveradres
                    $levstraat = $straat;
                    $levhuisnr = $huisnr;
                    $levpostcode = $postcode;
                    $levwoonplaats = $woonplaats;
                    $levnaam = $naam;
                    $levcontactpersoon = $naam;
                    $levland = $land;
                } else {
                    $levstraat = $this->input->post('levstraat');
                    $levhuisnr = $this->input->post('levnr');
                    $levpostcode = $this->input->post('levpostcode');
                    $levwoonplaats = $this->input->post('levwoonplaats');
                    $levnaam = $this->input->post('levnaam');
                    $levcontactpersoon = $this->input->post('levcontactpersoon');
                    $levland = $this->input->post('levland');
                }

                $this->session->set_flashdata('naam', $naam);
                $this->session->set_flashdata('straat', $straat);
                $this->session->set_flashdata('huisnr', $huisnr);
                $this->session->set_flashdata('geboortedatum', $geboortedatum);
                $this->session->set_flashdata('postcode', $postcode);
                $this->session->set_flashdata('woonplaats', $woonplaats);
                $this->session->set_flashdata('telefoon', $telefoon);
                $this->session->set_flashdata('land', $land);
//$this->session->set_flashdata('email', $email);
                $this->session->set_userdata('email', $email);
                $this->session->set_flashdata('opmerkingen', $opmerkingen);
                $this->session->set_flashdata('levstraat', $levstraat);
                $this->session->set_flashdata('levhuisnr', $levhuisnr);
                $this->session->set_flashdata('levpostcode', $levpostcode);
                $this->session->set_flashdata('levwoonplaats', $levwoonplaats);
                $this->session->set_flashdata('levnaam', $levnaam);
                $this->session->set_flashdata('levcontactpersoon', $levcontactpersoon);
                $this->session->set_flashdata('levland', $levland);
                $this->session->set_flashdata('kortingscode', $kortingscode);

// prijs
                $karretje = $this->haalopkarretje();
                $this->load->model("artikel_model");
                $this->load->model('maat_model');
                $this->load->model('setting_model');
//$data['artikels'] = $this->artikel_model->getProductenInKarretje($karretje);

                foreach ($karretje as $karitem) {
                    $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                    $maat = $this->maat_model->get($karitem->maatId);
                    $karitem->artikel = $artikel;
                    $karitem->maat = $maat;
                }
                $totaalprijs = 0.00;
                $artikelString = "";
                foreach ($karretje as $karitem) {
                    $artikel = $this->artikel_model->getSolo($karitem->artikelId);
// korting toekennen
                    $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));
                    $totaalprijs += $artikel->prijs * $karitem->aantal;
                    $artikelString .= $karitem->artikel->naam . " (" . $karitem->aantal . ") - ";
                }
                $setting = $this->setting_model->get(1);
                if ($totaalprijs < $setting->taxvrijlimiet) {
                    $totaalprijs+= $setting->transportkost;
                } else {
                    
                }

//kortingcode valideren en pas updaten na betaling
                $this->load->model('kortingcode_model');
                $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                if (isset($code->id)) {
                    if ($code->kortingBedrag == null) {
                        $totaalPrijsVoorKorting = $totaalprijs;
                        $kortingPercentage = $code->kortingProcent;
                        $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                    } else {
                        $totaalPrijsVoorKorting = $totaalprijs;
                        $kortingBedrag = $code->kortingBedrag;
                        $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                    }
                    $totaalprijs = $totaalPrijsNaKorting;
                    if ($totaalprijs < 0) {
                        $totaalprijs = 0;
                    }
                } // nu nog na betalen                

                $artikelString = substr($artikelString, 0, -3);
//$this->session->set_userdata('artikelString', $artikelString);
//$this->session->set_userdata('totaalprijs', $totaalprijs);
                $data["totaalprijs"] = $totaalprijs;
                $data["artikelString"] = $artikelString;
// einde nieuwe sessies
                //$redirectURL = "http://localhost:88/DulaniWebshop/index.php/winkelmandje/bestellingplaatsenmollie";
                $redirectURL = "http://www.dulani.be/index.php/winkelmandje/bestellingplaatsenmollie";

                $this->betaalmetmollie($totaalprijs, $artikelString, $redirectURL);
            } else {
                // END MOLLIE
                if ($this->input->post('submitpaypal') != null || $this->input->post('submitpaypal') != "") {
// nieuwe sessies voor bug op te lossen
                    $naam = $this->input->post('naam');
                    $straat = $this->input->post('straat');
                    $huisnr = $this->input->post('nr');
                    $geboortedatum = $this->input->post('geboortedatum');
                    $postcode = $this->input->post('postcode');
                    $woonplaats = $this->input->post('woonplaats');
                    $telefoon = $this->input->post('telefoon');
                    $email = $this->input->post('email');
                    $opmerkingen = $this->input->post('opmerkingen');

// nieuw nieuwsbrief / land
                    $brief = $this->input->post('nieuwsbrief');
                    $land = $this->input->post('land');

// kortingcode
                    $kortingscode = $this->input->post('kortingcode');

                    if ($brief == "ja") {
                        $nieuwsbriefinschrijving = new stdClass();
                        $nieuwsbriefinschrijving->naam = $naam;
                        $nieuwsbriefinschrijving->email = $email;

                        $this->load->model('nieuwsbriefinschrijving_model');
                        $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                    }

// alt leveradres
                    if ($this->input->post('levstraat') === '') {
// geen leveradres
                        $levstraat = $straat;
                        $levhuisnr = $huisnr;
                        $levpostcode = $postcode;
                        $levwoonplaats = $woonplaats;
                        $levnaam = $naam;
                        $levcontactpersoon = $naam;
                        $levland = $land;
                    } else {
                        $levstraat = $this->input->post('levstraat');
                        $levhuisnr = $this->input->post('levnr');
                        $levpostcode = $this->input->post('levpostcode');
                        $levwoonplaats = $this->input->post('levwoonplaats');
                        $levnaam = $this->input->post('levnaam');
                        $levcontactpersoon = $this->input->post('levcontactpersoon');
                        $levland = $this->input->post('levland');
                    }

                    $this->session->set_flashdata('naam', $naam);
                    $this->session->set_flashdata('straat', $straat);
                    $this->session->set_flashdata('huisnr', $huisnr);
                    $this->session->set_flashdata('geboortedatum', $geboortedatum);
                    $this->session->set_flashdata('postcode', $postcode);
                    $this->session->set_flashdata('woonplaats', $woonplaats);
                    $this->session->set_flashdata('telefoon', $telefoon);
                    $this->session->set_flashdata('land', $land);
//$this->session->set_flashdata('email', $email);
                    $this->session->set_userdata('email', $email);
                    $this->session->set_flashdata('opmerkingen', $opmerkingen);
                    $this->session->set_flashdata('levstraat', $levstraat);
                    $this->session->set_flashdata('levhuisnr', $levhuisnr);
                    $this->session->set_flashdata('levpostcode', $levpostcode);
                    $this->session->set_flashdata('levwoonplaats', $levwoonplaats);
                    $this->session->set_flashdata('levnaam', $levnaam);
                    $this->session->set_flashdata('levcontactpersoon', $levcontactpersoon);
                    $this->session->set_flashdata('levland', $levland);
                    $this->session->set_flashdata('kortingscode', $kortingscode);

// prijs
                    $karretje = $this->haalopkarretje();
                    $this->load->model("artikel_model");
                    $this->load->model('maat_model');
                    $this->load->model('setting_model');
//$data['artikels'] = $this->artikel_model->getProductenInKarretje($karretje);

                    foreach ($karretje as $karitem) {
                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                        $maat = $this->maat_model->get($karitem->maatId);
                        $karitem->artikel = $artikel;
                        $karitem->maat = $maat;
                    }
                    $totaalprijs = 0.00;
                    $artikelString = "";
                    foreach ($karretje as $karitem) {
                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);
// korting toekennen
                        $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));
                        $totaalprijs += $artikel->prijs * $karitem->aantal;
                        $artikelString .= $karitem->artikel->naam . " (" . $karitem->aantal . ") - ";
                    }
                    $setting = $this->setting_model->get(1);
                    if ($totaalprijs < $setting->taxvrijlimiet) {
                        $totaalprijs+= $setting->transportkost;
                    } else {
                        
                    }

//kortingcode valideren en pas updaten na betaling
                    $this->load->model('kortingcode_model');
                    $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                    if (isset($code->id)) {
                        if ($code->kortingBedrag == null) {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingPercentage = $code->kortingProcent;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                        } else {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingBedrag = $code->kortingBedrag;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                        }
                        $totaalprijs = $totaalPrijsNaKorting;
                        if ($totaalprijs < 0) {
                            $totaalprijs = 0;
                        }
                    } // nu nog na betalen

                    /* if ($totaalprijs < 49.99 && $land == "belgie") {
                      $totaalprijs+=8;
                      } elseif ($totaalprijs < 49.99 && $land != "belgie") {
                      $totaalprijs+=8;
                      } else {
                      //gratis levering
                      } */
                    $artikelString = substr($artikelString, 0, -3);
//$this->session->set_userdata('artikelString', $artikelString);
//$this->session->set_userdata('totaalprijs', $totaalprijs);
                    $data["totaalprijs"] = $totaalprijs;
                    $data["artikelString"] = $artikelString;
// einde nieuwe sessies


                    $data['categorien'] = $this->loadCategorienForMenu();
                    $data['title'] = "Bedankt";
                    $data['pagina'] = "Bedankt";
                    $partials = array('header' => 'templ/main_header', 'content' => 'bedankt_paypal', 'footer' => 'templ/main_footer');
                } else {
                    $naam = $this->input->post('naam');
                    $straat = $this->input->post('straat');
                    $huisnr = $this->input->post('nr');
                    $geboortedatum = $this->input->post('geboortedatum');
                    $postcode = $this->input->post('postcode');
                    $woonplaats = $this->input->post('woonplaats');
                    $telefoon = $this->input->post('telefoon');
                    $email = $this->input->post('email');
                    $opmerkingen = $this->input->post('opmerkingen');

// nieuw nieuwsbrief / land
                    $brief = $this->input->post('nieuwsbrief');
                    $land = $this->input->post('land');

                    // kortingcode
                    $kortingscode = $this->input->post('kortingcode');

                    if ($brief == "ja") {
                        $nieuwsbriefinschrijving = new stdClass();
                        $nieuwsbriefinschrijving->naam = $naam;
                        $nieuwsbriefinschrijving->email = $email;

                        $this->load->model('kortingcode_model');
                        $this->load->model('nieuwsbriefinschrijving_model');
                        $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                    }

// alt leveradres
                    if ($this->input->post('levstraat') === '') {
// geen leveradres
                        $levstraat = $straat;
                        $levhuisnr = $huisnr;
                        $levpostcode = $postcode;
                        $levwoonplaats = $woonplaats;
                        $levnaam = $naam;
                        $levcontactpersoon = $naam;
                        $levland = $land;
                    } else {
                        $levstraat = $this->input->post('levstraat');
                        $levhuisnr = $this->input->post('levnr');
                        $levpostcode = $this->input->post('levpostcode');
                        $levwoonplaats = $this->input->post('levwoonplaats');
                        $levnaam = $this->input->post('levnaam');
                        $levcontactpersoon = $this->input->post('levcontactpersoon');
                        $levland = $this->input->post('levland');
                    }

                    $this->load->library('email');
                    $config = array(
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'priority' => '1'
                    );
                    $this->email->initialize($config);

                    $this->email->from('webshop@dulani.be', 'Dulani');
                    $this->email->to($email);

                    $this->email->subject('Dulani webshop');

// message, moet met code en prijs, zie die fucntie
                    $berichtKlantIntro = "";

                    $bericht = "KLANTGEGEVENS<br><br>";
                    $bericht .= "Naam: " . $naam . "<br>";
                    $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
                    $bericht .= "Land: " . $land . "<br>";
                    $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
                    $bericht .= "Postcode: " . $postcode . "<br>";
                    $bericht .= "Woonplaats: " . $woonplaats . "<br>";
                    $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
                    $bericht .= "E-mail: " . $email . "<br><br>";

                    $bericht .= "BESTELGEGEVENS<br><br>";
                    $karretje = $this->haalopkarretje();
                    $this->load->model('artikel_model');
                    $this->load->model('maat_model');

// toevoegen aan database
                    $persoon = new stdClass();
                    $persoon->naam = $naam;
                    $persoon->geboortedatum = $geboortedatum;
                    $persoon->straat = $straat;
                    $persoon->huisnummer = $huisnr;
                    $persoon->postcode = $postcode;
                    $persoon->woonplaats = $woonplaats;
                    $persoon->telefoon = $telefoon;
                    $persoon->email = $email;
                    $persoon->land = $land;

                    $this->load->model('persoon_model');
                    $persoonId = $this->persoon_model->insert($persoon);

                    $bestelling = new stdClass();
                    $bestelling->persoonId = $persoonId;
                    $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 7200); // 2uur bijtellen voor server
                    $bestelling->datum = $date;
//$bestelling->datum = date('Y-m-d H:i:s a');
                    $bestelling->opmerkingen = $opmerkingen;

// alt lever adres
                    $bestelling->leverStraat = $levstraat;
                    $bestelling->leverHuisnummer = $levhuisnr;
                    $bestelling->leverPostcode = $levpostcode;
                    $bestelling->leverGemeente = $levwoonplaats;
                    $bestelling->leverNaam = $levnaam;
                    $bestelling->leverContactpersoon = $levcontactpersoon;
                    $bestelling->leverLand = $levland;
                    $bestelling->paypal = 0;
                    $bestelling->verzonden = 0;
                    $bestelling->betaald = 0;

                    $this->load->model('kortingcode_model');
                    $codeVoorBestelling = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);

                    if ($codeVoorBestelling == null) {
                        $bestelling->kortingCodeID = null;
                    } else {
                        $bestelling->kortingCodeID = $codeVoorBestelling->id;
                    }


                    $this->load->model('bestelling_model');
                    $bestellingId = $this->bestelling_model->insert($bestelling);

//bestellingartikel toevoegen in loop        
                    $this->load->model('bestellingartikel_model');
                    $this->load->model('verkochtartikel_model');
                    $this->load->model('artikelmaat_model');
                    $this->load->model('setting_model');

                    $totaalprijs = 0.00;
                    foreach ($karretje as $karitem) {
                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);

                        // artikel toevoegen aan nieuwe tabel om juiste kortingen te onthouden voor later HIER AL DOEN ANDERS KLOPT PRIJS NIET MEER, ZIE KORTING HIERONDER
                        $artikel->id = null;
                        $verkochtArtikelId = $this->verkochtartikel_model->insert($artikel);

                        $artikel = $this->artikel_model->getSolo($karitem->artikelId);
// korting toekennen                    
                        $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));

                        $totaalprijs += $artikel->prijs * $karitem->aantal;
                        $maat = $this->maat_model->get($karitem->maatId);
                        $karitem->artikel = $artikel;
                        $karitem->maat = $maat;
                        $bericht .= $karitem->aantal . "x " . $artikel->naam . " (maat:" . $maat->maat . ") EUR " . number_format($artikel->prijs * $karitem->aantal, 2) . "<br>";

// toevoegen aan database
                        $bestellingArtikel = new stdClass();
                        $bestellingArtikel->bestellingId = $bestellingId;
                        $bestellingArtikel->verkochtArtikelId = $verkochtArtikelId;
                        $bestellingArtikel->maatId = $maat->id;
                        $bestellingArtikel->aantal = $karitem->aantal;

                        $this->bestellingartikel_model->insert($bestellingArtikel);

// maten wegschrijven / updaten
                        $artikelMaat = new stdClass();
                        $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                        $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                        $this->artikelmaat_model->update($artikelMaat);
                    }

                    $setting = $this->setting_model->get(1);
                    if ($totaalprijs < $setting->taxvrijlimiet) {
                        $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                        $totaalprijs+= $setting->transportkost;
                    } else {
                        $bericht .= "Leverkosten: gratis<br>";
                    }
                    /* if ($totaalprijs < 49.99 && $land == "belgie") {
                      $bericht .= "Leverkosten: EUR 8<br>";
                      $totaalprijs+=8;
                      } elseif ($totaalprijs < 49.99 && $land != "belgie") {
                      $bericht .= "Leverkosten: EUR 8<br>";
                      $totaalprijs+=8;
                      } else {
                      $bericht .= "Leverkosten: gratis<br>";
                      } */


                    // kortingsbon
                    $this->load->model('kortingcode_model');
                    $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                    if (isset($code->id)) {
                        if ($code->kortingBedrag == null) {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingPercentage = $code->kortingProcent;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);

                            // korting weergeven in mail
                            $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingProcent . "%)<br/>";
                        } else {
                            $totaalPrijsVoorKorting = $totaalprijs;
                            $kortingBedrag = $code->kortingBedrag;
                            $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;

                            // korting weergeven in mail
                            $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingBedrag . " EURO)<br/>";
                        }
                        $totaalprijs = $totaalPrijsNaKorting;

                        if ($totaalprijs < 0) {
                            $totaalprijs = 0;
                        }

                        $code->gebruikt = $code->gebruikt + 1;
                        $this->kortingcode_model->update($code);
                    } // nu nog na betalen

                    $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

                    $bericht .= "OPMERKINGEN<br><br>";
                    $bericht .= $opmerkingen . "<br><br>";

                    $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar webshop@dulani.be of een privé bericht via onze Facebook pagina!";


                    $mededeling = date('d') . date('m') . date('Y') . $bestellingId;
                    $berichtKlantIntro = "Bedankt voor uw aankoop bij Dulani! Gelieve zo snel mogelijk &euro;" . $totaalprijs . " over te schrijven. Van zodra wij uw betaling ontvangen hebben vertrekt uw pakket via B-Post. B-Post levert in 98% van de gevallen binnen de 24u in België en 24/48u in de buurlanden. Indien wij uw betaling na 5 werkdagen nog niet ontvangen hebben wordt uw bestelling automatisch geannuleerd. Om uw betaling te regelen gebruikt u volgende gegevens:<br>";
                    $berichtKlantIntro .= "<ul><li>Rekeningnummer: BE04850848035531</li><li>BIC code: SPAABE22</li><li>Naam: Dulani St-Jozefsstraat 13/2 3500 Hasselt</li>";
                    $berichtKlantIntro .= "<li>Mededeling: " . $mededeling . "</li></ul>";
                    $berichtKlantIntro .= "<br/><br/>Hieronder vindt u de gegevens van uw bestelling terug:<br><br>";

                    $bericht = $berichtKlantIntro . $bericht;

// template opvragen
                    $data['naam'] = $naam;
                    $data['bericht'] = $bericht;
                    $message = $this->load->view('templ/mailcontent', $data, TRUE);

                    $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');


                    $this->email->message($message);
                    $this->email->send();

//stuur naar admin
                    $messageAdmin = "Een klant plaatste een bestelling op de dulani webshop en betaalde via overschrijving. <br/>";
                    $messageAdmin .= "<a href='http://www.dulani.be/index.php/admin/bestelling/" . $bestellingId . "'>Klik op deze link om naar de bestelling te gaan.</a>";
                    $this->email->subject('Dulani webshop - ' . $bestellingId . " - " . $persoon->naam);
                    $this->email->message($messageAdmin);
                    $this->email->to("webshop@dulani.be");
                    $this->email->send();

// stuur klant bericht ook naar admin
                    $this->email->subject('KLANT: Dulani webshop - ' . $bestellingId . " - " . $persoon->naam);
                    $this->email->message($message);
                    $this->email->to("webshop@dulani.be");
                    $this->email->send();

                    $data['email'] = $email;
                    $data['title'] = "Bedankt";
                    $data['pagina'] = "Bedankt";
                    $data['categorien'] = $this->loadCategorienForMenu();

//try {
                    $this->session->unset_userdata('karretje'); // als deze regel uitgevoerd wordt loopt het mis.
//} catch (Exception $ex) {
// echo $ex;                
//}
//unset($_SESSION['karretje']); // Deze regel crasht niet maar doet wel niet wat hij zou moeten doen.
                }
            }
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function bestellingplaatsenmollie() {
        // kijken of er echt wel betaald is
        try {
            require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

            $mollie = new Mollie_API_Client;
            // Dit is de TEST API KEY
            $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
            // Dit is de LIVE API KEY
            //$mollie->setApiKey('live_srQdsHgq4EC28zdVby5pnkwcZGzad2');
            $mollieID = $this->session->flashdata('mollieId');
            $payment_id = $mollieID;

            if ($payment_id == null || $payment_id == '') {
                // ga naar juiste error pagina
            }
            $payment = $mollie->payments->get($payment_id);
        } catch (Mollie_API_Exception $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
            exit;
        }

        /*
         * The order ID saved in the payment can be used to load the order and update it's
         * status
         */
        // $order_id = $payment->metadata->order_id;

        if ($payment->isPaid()) {
            /*
             * At this point you'd probably want to start the process of delivering the product
             * to the customer.
             */



            $karretje = $this->haalopkarretje();
            if (count($karretje) < 1 || $this->session->userdata('email') == "") {
                $this->karretje();
            } else {
                $naam = $this->session->flashdata("naam");
                $straat = $this->session->flashdata('straat');
                $huisnr = $this->session->flashdata('huisnr');
                $geboortedatum = $this->session->flashdata('geboortedatum');
                $postcode = $this->session->flashdata('postcode');
                $woonplaats = $this->session->flashdata('woonplaats');
                $telefoon = $this->session->flashdata('telefoon');
//$email = $this->session->flashdata('email');
                $email = $this->session->userdata('email');
                $opmerkingen = $this->session->flashdata('opmerkingen');
                $kortingscode = $this->session->flashdata('kortingscode');

// nieuw nieuwsbrief / land
                try {
                    $brief = $this->session->flashdata('nieuwsbrief');
                } catch (Exception $ex) {
                    $brief = 'nee';
                }

                $land = $this->session->flashdata('land');

                if ($brief == "ja") {
                    $nieuwsbriefinschrijving = new stdClass();
                    $nieuwsbriefinschrijving->naam = $naam;
                    $nieuwsbriefinschrijving->email = $email;

                    $this->load->model('nieuwsbriefinschrijving_model');
                    $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                }

// alt leveradres
                if ($this->session->flashdata('levstraat') === '') {
// geen leveradres
                    $levstraat = $straat;
                    $levhuisnr = $huisnr;
                    $levpostcode = $postcode;
                    $levwoonplaats = $woonplaats;
                    $levnaam = $naam;
                    $levcontactpersoon = $naam;
                    $levland = $land;
                } else {
                    $levstraat = $this->session->flashdata('levstraat');
                    $levhuisnr = $this->session->flashdata('levhuisnr');
                    $levpostcode = $this->session->flashdata('levpostcode');
                    $levwoonplaats = $this->session->flashdata('levwoonplaats');
                    $levnaam = $this->session->flashdata('levnaam');
                    $levcontactpersoon = $this->session->flashdata('levcontactpersoon');
                    $levland = $this->session->flashdata('levland');
                }
//test

                $this->load->library('email');
                $config = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1'
                );
                $this->email->initialize($config);

                $this->email->from('webshop@dulani.be', 'Dulani');
                $this->email->to($email);

                $this->email->subject('Dulani webshop');

// message
                $berichtKlantIntro = "U plaatste een bestelling bij Dulani en gaf volgende gegevens mee:<br><br>";

                $bericht = "KLANTGEGEVENS<br><br>";
                $bericht .= "Naam: " . $naam . "<br>";
                $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
                $bericht .= "Land: " . $land . "<br>";
                $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
                $bericht .= "Postcode: " . $postcode . "<br>";
                $bericht .= "Woonplaats: " . $woonplaats . "<br>";
                $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
                $bericht .= "E-mail: " . $email . "<br><br>";

                $bericht .= "BESTELGEGEVENS<br><br>";
                $karretje = $this->haalopkarretje();
                $this->load->model('artikel_model');
                $this->load->model('maat_model');

// toevoegen aan database
                $persoon = new stdClass();
                $persoon->naam = $naam;
                $persoon->geboortedatum = $geboortedatum;
                $persoon->straat = $straat;
                $persoon->huisnummer = $huisnr;
                $persoon->postcode = $postcode;
                $persoon->woonplaats = $woonplaats;
                $persoon->telefoon = $telefoon;
                $persoon->email = $email;
                $persoon->land = $land;

                $this->load->model('persoon_model');
                $persoonId = $this->persoon_model->insert($persoon);

                $bestelling = new stdClass();
                $bestelling->persoonId = $persoonId;
                $bestelling->datum = date('Y-m-d H:i:s a');
                $bestelling->opmerkingen = $opmerkingen;

// alt lever adres
                $bestelling->leverStraat = $levstraat;
                $bestelling->leverHuisnummer = $levhuisnr;
                $bestelling->leverPostcode = $levpostcode;
                $bestelling->leverGemeente = $levwoonplaats;
                $bestelling->leverNaam = $levnaam;
                $bestelling->leverContactpersoon = $levcontactpersoon;
                $bestelling->leverLand = $levland;
                $bestelling->paypal = 0;
                $bestelling->mollieId = $mollieID;
                $bestelling->verzonden = 0;
                $bestelling->betaald = 1;

                $this->load->model('kortingcode_model');
                $codeVoorBestelling = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);

                if ($codeVoorBestelling == null) {
                    $bestelling->kortingCodeID = null;
                } else {
                    $bestelling->kortingCodeID = $codeVoorBestelling->id;
                }

                $this->load->model('bestelling_model');
                $bestellingId = $this->bestelling_model->insert($bestelling);

//bestellingartikel toevoegen in loop        
                $this->load->model('bestellingartikel_model');
                $this->load->model('verkochtartikel_model');
                $this->load->model('artikelmaat_model');
                $this->load->model('setting_model');

                $totaalprijs = 0.00;
                foreach ($karretje as $karitem) {
                    $artikel = $this->artikel_model->getSolo($karitem->artikelId);

// artikel toevoegen aan nieuwe tabel om juiste kortingen te onthouden voor later HIER AL DOEN ANDERS KLOPT PRIJS NIET MEER, ZIE KORTING HIERONDER
                    $artikel->id = null;
                    $verkochtArtikelId = $this->verkochtartikel_model->insert($artikel);

                    $artikel = $this->artikel_model->getSolo($karitem->artikelId);

// korting toekennen
                    $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));

                    $totaalprijs += $artikel->prijs * $karitem->aantal;
                    $maat = $this->maat_model->get($karitem->maatId);
                    $karitem->artikel = $artikel;
                    $karitem->maat = $maat;
                    $bericht .= $karitem->aantal . "x " . $artikel->naam . " (maat:" . $maat->maat . ") EUR " . number_format($artikel->prijs * $karitem->aantal, 2) . "<br>";



// toevoegen aan database
                    $bestellingArtikel = new stdClass();
                    $bestellingArtikel->bestellingId = $bestellingId;
                    $bestellingArtikel->verkochtArtikelId = $verkochtArtikelId;
                    $bestellingArtikel->maatId = $maat->id;
                    $bestellingArtikel->aantal = $karitem->aantal;

                    $this->bestellingartikel_model->insert($bestellingArtikel);

// maten wegschrijven / updaten
                    $artikelMaat = new stdClass();
                    $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                    $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                    $this->artikelmaat_model->update($artikelMaat);
                }
                $setting = $this->setting_model->get(1);
                if ($totaalprijs < $setting->taxvrijlimiet) {
                    $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                    $totaalprijs+= $setting->transportkost;
                } else {
                    $bericht .= "Leverkosten: gratis<br>";
                }
                /*
                  if ($totaalprijs < 49.99) {
                  $bericht .= "Leverkosten: EUR 8<br>";
                  $totaalprijs+=8;
                  } elseif ($totaalprijs < 49.99 && $land != "belgie") {
                  $bericht .= "Leverkosten: EUR 8<br>";
                  $totaalprijs+=8;
                  } else {
                  $bericht .= "Leverkosten: gratis<br>";
                  } */

                // kortingsbon
                $this->load->model('kortingcode_model');
                $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                if (isset($code->id)) {
                    if ($code->kortingBedrag == null) {
                        $totaalPrijsVoorKorting = $totaalprijs;
                        $kortingPercentage = $code->kortingProcent;
                        $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);

                        // korting weergeven in mail
                        $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingProcent . "%)<br/>";
                    } else {
                        $totaalPrijsVoorKorting = $totaalprijs;
                        $kortingBedrag = $code->kortingBedrag;
                        $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;

                        // korting weergeven in mail
                        $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingBedrag . " EURO)<br/>";
                    }
                    $totaalprijs = $totaalPrijsNaKorting;

                    if ($totaalprijs < 0) {
                        $totaalprijs = 0;
                    }

                    $code->gebruikt = $code->gebruikt + 1;
                    $this->kortingcode_model->update($code);
                } // nu nog na betalen

                $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

                $bericht .= "OPMERKINGEN<br><br>";
                $bericht .= $opmerkingen . "<br><br>";

                $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar webshop@dulani.be of een privé bericht via onze Facebook pagina!";

                $bericht = $berichtKlantIntro . $bericht;

// template opvragen
                $data['naam'] = $naam;
                $data['bericht'] = $bericht;
                $message = $this->load->view('templ/mailcontent', $data, TRUE);

                $messagePaypal = $message;

                $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');
                $this->email->message($message);

                $this->email->send();

                // stuur klant bericht ook naar admin
                $this->email->subject('KLANT: Dulani webshop - ' . $bestellingId . " - " . $persoon->naam);
                $this->email->message($message);
                $this->email->to("webshop@dulani.be");
                $this->email->send();

//stuur naar admin
                $messageMollieAdmin = "Een klant plaatste een bestelling op de dulani webshop en betaalde via Mollie ($mollieID). <br/>";
                $messageMollieAdmin .= "<a href='http://www.dulani.be/index.php/admin/bestelling/" . $bestellingId . "'>Klik op deze link om naar de bestelling te gaan.</a>";
                $this->email->subject('Dulani webshop - ' . $bestellingId . " - " . $persoon->naam . " (Mollie)");
                $this->email->message($messageMollieAdmin);
                $this->email->to("webshop@dulani.be");
                $this->email->send();

                $data['email'] = $email;
                $data['title'] = "Bedankt";
                $data['pagina'] = "Bedankt";
                $data['categorien'] = $this->loadCategorienForMenu();


                $this->session->unset_userdata('karretje');
                $this->session->unset_userdata('postdata');
                $this->template->load('main_master', $partials, $data);
            }
        } elseif (!$payment->isOpen()) {
            /*
             * The payment isn't paid and isn't open anymore. We can assume it was aborted.
             */
            $data['pagina'] = 'Bestelling geannuleerd';
            $data['title'] = "Annulatie";
            $partials = array('header' => 'templ/timer_header', 'content' => 'bestelling_annuleren', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        }
    }

    // wordt gebruikt om de betaling al in de database te zetten voor echt betaald is, is omdat de gebruiker kan wegklikken
    public function bestellingmetmollieindatabase($mollieID) {
        $karretje = $this->haalopkarretje();
        if (count($karretje) < 1 || $this->session->userdata('email') == "") {
            $this->karretje();
        } else {
            $naam = $this->session->flashdata("naam");
            $straat = $this->session->flashdata('straat');
            $huisnr = $this->session->flashdata('huisnr');
            $geboortedatum = $this->session->flashdata('geboortedatum');
            $postcode = $this->session->flashdata('postcode');
            $woonplaats = $this->session->flashdata('woonplaats');
            $telefoon = $this->session->flashdata('telefoon');
//$email = $this->session->flashdata('email');
            $email = $this->session->userdata('email');
            $opmerkingen = $this->session->flashdata('opmerkingen');
            $kortingscode = $this->session->flashdata('kortingscode');

// nieuw nieuwsbrief / land
            try {
                $brief = $this->session->flashdata('nieuwsbrief');
            } catch (Exception $ex) {
                $brief = 'nee';
            }

            $land = $this->session->flashdata('land');

            if ($brief == "ja") {
                $nieuwsbriefinschrijving = new stdClass();
                $nieuwsbriefinschrijving->naam = $naam;
                $nieuwsbriefinschrijving->email = $email;

                $this->load->model('nieuwsbriefinschrijving_model');
                $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
            }

// alt leveradres
            if ($this->session->flashdata('levstraat') === '') {
// geen leveradres
                $levstraat = $straat;
                $levhuisnr = $huisnr;
                $levpostcode = $postcode;
                $levwoonplaats = $woonplaats;
                $levnaam = $naam;
                $levcontactpersoon = $naam;
                $levland = $land;
            } else {
                $levstraat = $this->session->flashdata('levstraat');
                $levhuisnr = $this->session->flashdata('levhuisnr');
                $levpostcode = $this->session->flashdata('levpostcode');
                $levwoonplaats = $this->session->flashdata('levwoonplaats');
                $levnaam = $this->session->flashdata('levnaam');
                $levcontactpersoon = $this->session->flashdata('levcontactpersoon');
                $levland = $this->session->flashdata('levland');
            }
//test

            $this->load->library('email');
            $config = array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'priority' => '1'
            );
            $this->email->initialize($config);

            $this->email->from('webshop@dulani.be', 'Dulani');
            $this->email->to($email);

            $this->email->subject('Dulani webshop');

// message
            $berichtKlantIntro = "U plaatste een bestelling bij Dulani en gaf volgende gegevens mee:<br><br>";

            $bericht = "KLANTGEGEVENS<br><br>";
            $bericht .= "Naam: " . $naam . "<br>";
            $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
            $bericht .= "Land: " . $land . "<br>";
            $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
            $bericht .= "Postcode: " . $postcode . "<br>";
            $bericht .= "Woonplaats: " . $woonplaats . "<br>";
            $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
            $bericht .= "E-mail: " . $email . "<br><br>";

            $bericht .= "BESTELGEGEVENS<br><br>";
            $karretje = $this->haalopkarretje();
            $this->load->model('artikel_model');
            $this->load->model('maat_model');

// toevoegen aan database
            $persoon = new stdClass();
            $persoon->naam = $naam;
            $persoon->geboortedatum = $geboortedatum;
            $persoon->straat = $straat;
            $persoon->huisnummer = $huisnr;
            $persoon->postcode = $postcode;
            $persoon->woonplaats = $woonplaats;
            $persoon->telefoon = $telefoon;
            $persoon->email = $email;
            $persoon->land = $land;

            $this->load->model('persoon_model');
            $persoonId = $this->persoon_model->insert($persoon);

            $bestelling = new stdClass();
            $bestelling->persoonId = $persoonId;
            $bestelling->datum = date('Y-m-d H:i:s a');
            $bestelling->opmerkingen = $opmerkingen;

// alt lever adres
            $bestelling->leverStraat = $levstraat;
            $bestelling->leverHuisnummer = $levhuisnr;
            $bestelling->leverPostcode = $levpostcode;
            $bestelling->leverGemeente = $levwoonplaats;
            $bestelling->leverNaam = $levnaam;
            $bestelling->leverContactpersoon = $levcontactpersoon;
            $bestelling->leverLand = $levland;
            $bestelling->paypal = 0;
            $bestelling->mollieId = $mollieID;
            $bestelling->inAfwachting = 1;
            $bestelling->verzonden = 0;
            $bestelling->betaald = 1;

            $this->load->model('kortingcode_model');
            $codeVoorBestelling = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);

            if ($codeVoorBestelling == null) {
                $bestelling->kortingCodeID = null;
            } else {
                $bestelling->kortingCodeID = $codeVoorBestelling->id;
            }

            $this->load->model('bestelling_model');
            $bestellingId = $this->bestelling_model->insert($bestelling);

//bestellingartikel toevoegen in loop        
            $this->load->model('bestellingartikel_model');
            $this->load->model('verkochtartikel_model');
            $this->load->model('artikelmaat_model');
            $this->load->model('setting_model');

            $totaalprijs = 0.00;
            foreach ($karretje as $karitem) {
                $artikel = $this->artikel_model->getSolo($karitem->artikelId);

// artikel toevoegen aan nieuwe tabel om juiste kortingen te onthouden voor later HIER AL DOEN ANDERS KLOPT PRIJS NIET MEER, ZIE KORTING HIERONDER
                $artikel->id = null;
                $verkochtArtikelId = $this->verkochtartikel_model->insert($artikel);

                $artikel = $this->artikel_model->getSolo($karitem->artikelId);

// korting toekennen
                $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));

                $totaalprijs += $artikel->prijs * $karitem->aantal;
                $maat = $this->maat_model->get($karitem->maatId);
                $karitem->artikel = $artikel;
                $karitem->maat = $maat;
                $bericht .= $karitem->aantal . "x " . $artikel->naam . " (maat:" . $maat->maat . ") EUR " . number_format($artikel->prijs * $karitem->aantal, 2) . "<br>";



// toevoegen aan database
                $bestellingArtikel = new stdClass();
                $bestellingArtikel->bestellingId = $bestellingId;
                $bestellingArtikel->verkochtArtikelId = $verkochtArtikelId;
                $bestellingArtikel->maatId = $maat->id;
                $bestellingArtikel->aantal = $karitem->aantal;

                $this->bestellingartikel_model->insert($bestellingArtikel);

// maten wegschrijven / updaten
                $artikelMaat = new stdClass();
                $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                $this->artikelmaat_model->update($artikelMaat);
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                $totaalprijs+= $setting->transportkost;
            } else {
                $bericht .= "Leverkosten: gratis<br>";
            }
            /*
              if ($totaalprijs < 49.99) {
              $bericht .= "Leverkosten: EUR 8<br>";
              $totaalprijs+=8;
              } elseif ($totaalprijs < 49.99 && $land != "belgie") {
              $bericht .= "Leverkosten: EUR 8<br>";
              $totaalprijs+=8;
              } else {
              $bericht .= "Leverkosten: gratis<br>";
              } */

            // kortingsbon
            $this->load->model('kortingcode_model');
            $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $totaalPrijsVoorKorting = $totaalprijs;
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);

                    // korting weergeven in mail
                    $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingProcent . "%)<br/>";
                } else {
                    $totaalPrijsVoorKorting = $totaalprijs;
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;

                    // korting weergeven in mail
                    $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingBedrag . " EURO)<br/>";
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }

                $code->gebruikt = $code->gebruikt + 1;
                $this->kortingcode_model->update($code);
            } // nu nog na betalen

            $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

            $bericht .= "OPMERKINGEN<br><br>";
            $bericht .= $opmerkingen . "<br><br>";

            $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar webshop@dulani.be of een privé bericht via onze Facebook pagina!";

            $bericht = $berichtKlantIntro . $bericht;

// template opvragen
            $data['naam'] = $naam;
            $data['bericht'] = $bericht;
            $message = $this->load->view('templ/mailcontent', $data, TRUE);

            $messagePaypal = $message;

            $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');
            // hier stond email

            $data['email'] = $email;
            $data['title'] = "Bedankt";
            $data['pagina'] = "Bedankt";
            $data['categorien'] = $this->loadCategorienForMenu();


            $this->session->unset_userdata('karretje');
            $this->session->unset_userdata('postdata');
            //$this->template->load('main_master', $partials, $data);
        }
    }

    public function webhookMollie() {
        //$mollieId = $this->session->flashdata('mollieId');
        $mollieId = $this->input->post('id');

        $this->load->library('email');
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'priority' => '1'
        );
        $this->email->initialize($config);
        $this->email->from('webshop@dulani.be');
        $this->email->subject('Foutmelding database error www.dulani.be');
        $this->email->message("test webhook<br/>" . $mollieId);
        $this->email->to("jeroen_vinken@hotmail.com");
        $this->email->send();
        //$mollieId = 'tr_3Cb3SdKztY';
        $this->load->model('bestelling_model');
        $bestelling = $this->bestelling_model->getByMollieId($mollieId);

        // kijken of er echt wel betaald is
        try {
            require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

            $mollie = new Mollie_API_Client;
            // Dit is de TEST API KEY
            $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
            // Dit is de LIVE API KEY
            //$mollie->setApiKey('live_srQdsHgq4EC28zdVby5pnkwcZGzad2');


            $payment_id = $mollieId;

            if ($payment_id == null || $payment_id == '') {
                // ga naar juiste error pagina
            }
            $payment = $mollie->payments->get($payment_id);
        } catch (Mollie_API_Exception $e) {
            //echo "API call failed: " . htmlspecialchars($e->getMessage());
            //exit;
            // dit gaat niet weergegeven worden
        }

        /*
         * The order ID saved in the payment can be used to load the order and update it's
         * status
         */
        // $order_id = $payment->metadata->order_id;

        if ($payment->isPaid()) {
            $bestelling->inAfwachting = 0;
            $bestelling->betaald = 1;
            $this->bestelling_model->update($bestelling);

            echo $bestelling->id;
        } elseif (!$payment->isOpen()) {
            /*
             * The payment isn't paid and isn't open anymore. We can assume it was aborted.
             */
            $data['pagina'] = 'Bestelling geannuleerd';
            $data['title'] = "Annulatie";
            $partials = array('header' => 'templ/timer_header', 'content' => 'bestelling_annuleren', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function bestellingplaatsenpaypal() {
        /* $postdata = $this->session->userdata('postdata');
          $naam = $postdata["naam"];
          $straat = $postdata['straat'];
          $huisnr = $postdata['nr'];
          $geboortedatum = $postdata['geboortedatum'];
          $postcode = $postdata['postcode'];
          $woonplaats = $postdata['woonplaats'];
          $telefoon = $postdata['telefoon'];
          $email = $postdata['email'];
          $opmerkingen = $postdata['opmerkingen'];

          // nieuw nieuwsbrief / land
          try {
          $brief = $postdata['nieuwsbrief'];
          } catch (Exception $ex) {
          $brief = 'nee';
          }

          $land = $postdata['land'];

          if ($brief == "ja") {
          $nieuwsbriefinschrijving = new stdClass();
          $nieuwsbriefinschrijving->naam = $naam;
          $nieuwsbriefinschrijving->email = $email;

          $this->load->model('nieuwsbriefinschrijving_model');
          $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
          }

          // alt leveradres
          if ($postdata['levstraat'] === '') {
          // geen leveradres
          $levstraat = $straat;
          $levhuisnr = $huisnr;
          $levpostcode = $postcode;
          $levwoonplaats = $woonplaats;
          $levnaam = $naam;
          $levcontactpersoon = $naam;
          $levland = $land;
          } else {
          $levstraat = $postdata['levstraat'];
          $levhuisnr = $postdata['levnr'];
          $levpostcode = $postdata['levpostcode'];
          $levwoonplaats = $postdata['levwoonplaats'];
          $levnaam = $postdata['levnaam'];
          $levcontactpersoon = $postdata['levcontactpersoon'];
          $levland = $postdata['levland'];
          } */

        $karretje = $this->haalopkarretje();
        if (count($karretje) < 1 || $this->session->userdata('email') == "") {
            $this->karretje();
        } else {
            $naam = $this->session->flashdata("naam");
            $straat = $this->session->flashdata('straat');
            $huisnr = $this->session->flashdata('huisnr');
            $geboortedatum = $this->session->flashdata('geboortedatum');
            $postcode = $this->session->flashdata('postcode');
            $woonplaats = $this->session->flashdata('woonplaats');
            $telefoon = $this->session->flashdata('telefoon');
//$email = $this->session->flashdata('email');
            $email = $this->session->userdata('email');
            $opmerkingen = $this->session->flashdata('opmerkingen');
            $kortingscode = $this->session->flashdata('kortingscode');

// nieuw nieuwsbrief / land
            try {
                $brief = $this->session->flashdata('nieuwsbrief');
            } catch (Exception $ex) {
                $brief = 'nee';
            }

            $land = $this->session->flashdata('land');

            if ($brief == "ja") {
                $nieuwsbriefinschrijving = new stdClass();
                $nieuwsbriefinschrijving->naam = $naam;
                $nieuwsbriefinschrijving->email = $email;

                $this->load->model('nieuwsbriefinschrijving_model');
                $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
            }

// alt leveradres
            if ($this->session->flashdata('levstraat') === '') {
// geen leveradres
                $levstraat = $straat;
                $levhuisnr = $huisnr;
                $levpostcode = $postcode;
                $levwoonplaats = $woonplaats;
                $levnaam = $naam;
                $levcontactpersoon = $naam;
                $levland = $land;
            } else {
                $levstraat = $this->session->flashdata('levstraat');
                $levhuisnr = $this->session->flashdata('levhuisnr');
                $levpostcode = $this->session->flashdata('levpostcode');
                $levwoonplaats = $this->session->flashdata('levwoonplaats');
                $levnaam = $this->session->flashdata('levnaam');
                $levcontactpersoon = $this->session->flashdata('levcontactpersoon');
                $levland = $this->session->flashdata('levland');
            }
//test

            $this->load->library('email');
            $config = array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'priority' => '1'
            );
            $this->email->initialize($config);

            $this->email->from('webshop@dulani.be', 'Dulani');
            $this->email->to($email);

            $this->email->subject('Dulani webshop');

// message
            $berichtKlantIntro = "U plaatste een bestelling bij Dulani en gaf volgende gegevens mee:<br><br>";

            $bericht = "KLANTGEGEVENS<br><br>";
            $bericht .= "Naam: " . $naam . "<br>";
            $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
            $bericht .= "Land: " . $land . "<br>";
            $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
            $bericht .= "Postcode: " . $postcode . "<br>";
            $bericht .= "Woonplaats: " . $woonplaats . "<br>";
            $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
            $bericht .= "E-mail: " . $email . "<br><br>";

            $bericht .= "BESTELGEGEVENS<br><br>";
            $karretje = $this->haalopkarretje();
            $this->load->model('artikel_model');
            $this->load->model('maat_model');

// toevoegen aan database
            $persoon = new stdClass();
            $persoon->naam = $naam;
            $persoon->geboortedatum = $geboortedatum;
            $persoon->straat = $straat;
            $persoon->huisnummer = $huisnr;
            $persoon->postcode = $postcode;
            $persoon->woonplaats = $woonplaats;
            $persoon->telefoon = $telefoon;
            $persoon->email = $email;
            $persoon->land = $land;

            $this->load->model('persoon_model');
            $persoonId = $this->persoon_model->insert($persoon);

            $bestelling = new stdClass();
            $bestelling->persoonId = $persoonId;
            $bestelling->datum = date('Y-m-d H:i:s a');
            $bestelling->opmerkingen = $opmerkingen;

// alt lever adres
            $bestelling->leverStraat = $levstraat;
            $bestelling->leverHuisnummer = $levhuisnr;
            $bestelling->leverPostcode = $levpostcode;
            $bestelling->leverGemeente = $levwoonplaats;
            $bestelling->leverNaam = $levnaam;
            $bestelling->leverContactpersoon = $levcontactpersoon;
            $bestelling->leverLand = $levland;
            $bestelling->paypal = 1;
            $bestelling->verzonden = 0;
            $bestelling->betaald = 1;

            $this->load->model('kortingcode_model');
            $codeVoorBestelling = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);

            if ($codeVoorBestelling == null) {
                $bestelling->kortingCodeID = null;
            } else {
                $bestelling->kortingCodeID = $codeVoorBestelling->id;
            }

            $this->load->model('bestelling_model');
            $bestellingId = $this->bestelling_model->insert($bestelling);

//bestellingartikel toevoegen in loop        
            $this->load->model('bestellingartikel_model');
            $this->load->model('verkochtartikel_model');
            $this->load->model('artikelmaat_model');
            $this->load->model('setting_model');

            $totaalprijs = 0.00;
            foreach ($karretje as $karitem) {
                $artikel = $this->artikel_model->getSolo($karitem->artikelId);

// artikel toevoegen aan nieuwe tabel om juiste kortingen te onthouden voor later HIER AL DOEN ANDERS KLOPT PRIJS NIET MEER, ZIE KORTING HIERONDER
                $artikel->id = null;
                $verkochtArtikelId = $this->verkochtartikel_model->insert($artikel);

                $artikel = $this->artikel_model->getSolo($karitem->artikelId);

// korting toekennen
                $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));

                $totaalprijs += $artikel->prijs * $karitem->aantal;
                $maat = $this->maat_model->get($karitem->maatId);
                $karitem->artikel = $artikel;
                $karitem->maat = $maat;
                $bericht .= $karitem->aantal . "x " . $artikel->naam . " (maat:" . $maat->maat . ") EUR " . number_format($artikel->prijs * $karitem->aantal, 2) . "<br>";



// toevoegen aan database
                $bestellingArtikel = new stdClass();
                $bestellingArtikel->bestellingId = $bestellingId;
                $bestellingArtikel->verkochtArtikelId = $verkochtArtikelId;
                $bestellingArtikel->maatId = $maat->id;
                $bestellingArtikel->aantal = $karitem->aantal;

                $this->bestellingartikel_model->insert($bestellingArtikel);

// maten wegschrijven / updaten
                $artikelMaat = new stdClass();
                $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                $this->artikelmaat_model->update($artikelMaat);
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                $totaalprijs+= $setting->transportkost;
            } else {
                $bericht .= "Leverkosten: gratis<br>";
            }
            /*
              if ($totaalprijs < 49.99) {
              $bericht .= "Leverkosten: EUR 8<br>";
              $totaalprijs+=8;
              } elseif ($totaalprijs < 49.99 && $land != "belgie") {
              $bericht .= "Leverkosten: EUR 8<br>";
              $totaalprijs+=8;
              } else {
              $bericht .= "Leverkosten: gratis<br>";
              } */

            // kortingsbon
            $this->load->model('kortingcode_model');
            $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $totaalPrijsVoorKorting = $totaalprijs;
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);

                    // korting weergeven in mail
                    $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingProcent . "%)<br/>";
                } else {
                    $totaalPrijsVoorKorting = $totaalprijs;
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;

                    // korting weergeven in mail
                    $bericht .= "Korting: EUR" . ($totaalPrijsVoorKorting - $totaalPrijsNaKorting) . " (-" . $code->kortingBedrag . " EURO)<br/>";
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }

                $code->gebruikt = $code->gebruikt + 1;
                $this->kortingcode_model->update($code);
            } // nu nog na betalen

            $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

            $bericht .= "OPMERKINGEN<br><br>";
            $bericht .= $opmerkingen . "<br><br>";

            $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar webshop@dulani.be of een privé bericht via onze Facebook pagina!";

            $bericht = $berichtKlantIntro . $bericht;

// template opvragen
            $data['naam'] = $naam;
            $data['bericht'] = $bericht;
            $message = $this->load->view('templ/mailcontent', $data, TRUE);

            $messagePaypal = $message;

            $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');
            $this->email->message($message);

            $this->email->send();

            // stuur klant bericht ook naar admin
            $this->email->subject('KLANT: Dulani webshop - ' . $bestellingId . " - " . $persoon->naam);
            $this->email->message($message);
            $this->email->to("webshop@dulani.be");
            $this->email->send();

//stuur naar admin
            $messagePaypalAdmin = "Een klant plaatste een bestelling op de dulani webshop en betaalde via paypal. <br/>";
            $messagePaypalAdmin .= "<a href='http://www.dulani.be/index.php/admin/bestelling/" . $bestellingId . "'>Klik op deze link om naar de bestelling te gaan.</a>";
            $this->email->subject('Dulani webshop - ' . $bestellingId . " - " . $persoon->naam . " (PayPal)");
            $this->email->message($messagePaypalAdmin);
            $this->email->to("webshop@dulani.be");
            $this->email->send();

            $data['email'] = $email;
            $data['title'] = "Bedankt";
            $data['pagina'] = "Bedankt";
            $data['categorien'] = $this->loadCategorienForMenu();


            $this->session->unset_userdata('karretje');
            $this->session->unset_userdata('postdata');
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function timer($date) {
        $data['title'] = 'Dulani';
        $data['pagina'] = 'Home';
//$data['gebruiker'] = $this->authex->getUserInfo();         
        $data['enddate'] = $date;
        $this->load->model('tekst_model');
        $teksten = $this->tekst_model->getAllByPage("timer.php");
        $data['teksten'] = $teksten;
        $partials = array('header' => 'templ/timer_header', 'content' => 'timer', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function checkStock() {
        $karretje = $this->haalopkarretje();
        $this->load->model("artikel_model");
        $this->load->model("artikelmaat_model");
        $this->load->model("maat_model");

        foreach ($karretje as $karitem) {
            $aantalInStock = $this->artikelmaat_model->getAmountInStock($karitem->artikelId, $karitem->maatId);
            if ($karitem->aantal > $aantalInStock) {
// uit stock ga naar foutpagina
                $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                $data["artikel"] = $artikel;
                $data["aantalInStock"] = $aantalInStock;
                $data["title"] = "Uit stock";
                $data["pagina"] = "Uit stock";
                $partials = array('header' => 'templ/timer_header', 'content' => 'uit_stock', 'footer' => 'templ/main_footer');
                $this->template->load('main_master', $partials, $data);
            }
        }
//return "niet op";
    }

    public function betaalmetmollie($prijs = 12.34, $beschrijving = 'Bestelling bij Dulani', $redirectURL = 'http://www.dulani.be/index.php/winkelmandje/bestellingplaatsenmollie') {
        require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

        $mollie = new Mollie_API_Client;
        // Dit is de TEST API KEY
        $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
        // Dit is de LIVE API KEY
        // $mollie->setApiKey('live_srQdsHgq4EC28zdVby5pnkwcZGzad2');

        /*
         * Payment parameters:
         *   amount        Amount in EUROs. This example creates a € 10,- payment.
         *   description   Description of the payment.
         *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
         *   webhookUrl    Webhook location, used to report when the payment changes state.
         *   metadata      Custom metadata that is stored with the payment.
         */

        $payment = $mollie->payments->create(array(
            'amount' => $prijs,
            'description' => $beschrijving,
            'redirectUrl' => $redirectURL,
            'webhookUrl' => "http://www.dulani.be/index.php/testcontroller/webhookMollie",
        ));

        $payment->
        //$payment->redirectUrl .=  "/" . $payment->id;
        $this->session->set_flashdata('mollieId', $payment->id);

        // new mollie webhook test - save data al
        $this->bestellingmetmollieindatabase($payment->id);

        header("Location: " . $payment->getPaymentUrl());
    }

    public function mollieok() {
        header("Location: www.dulani.be");
    }

    public function errortest() {
        $e = 0 / 0;
    }
    
    public function bposttester(){
        $this->load->view('TEST_bpost'); 
    }

}
