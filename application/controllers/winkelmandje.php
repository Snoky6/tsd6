<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Winkelmandje extends CI_Controller {

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

    public function addtocartajax() {
        $maatId = $this->input->get('maatId');
        $artikelId = $this->input->get('artikelId');
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
        
        $data['karretje'] = $karretje;
        
        $this->load->view('winkelmandje_sidebar', $data);       
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
        $data['title'] = global_bedrijfsnaam;
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
        $data['title'] = global_bedrijfsnaam;
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
        $categorien = $this->categorie_model->getAllWithSubSnel();

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


                $this->session->set_userdata('email', $email);

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
                $data["totaalprijs"] = $totaalprijs;
                $data["artikelString"] = $artikelString;

                //$redirectURL = "http://localhost:88/DulaniWebshop/index.php";
                //$redirectURL = "http://www.dulani.be/index.php/winkelmandje/bestellingplaatsenmollie";
                $redirectURL = global_websiteURL . "/index.php/winkelmandje/bedanktMollie";

                //$this->betaalmetmollie($totaalprijs, $artikelString, $redirectURL); // Nu geen sessies meer, dus ook geen errors meer, alle functies staan hier nu in 1
                // laatste bestellinmummer +1 opvragen voor mee te geven als description ipv artikelstring
                $this->load->model("bestelling_model");
                $bestellingnummer = $this->bestelling_model->getLastBestellingNummer() + 1;

                /*                 * **** BETAALMETMOLLIE FUNCTIE ***** */
                // Karretje sessie leegmaken anders soms de nginx error van sessie vol. MAX 4000 BYTES
                $karretje = $this->haalopkarretje();
                $this->session->unset_userdata('karretje');

                require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

                $mollie = new Mollie_API_Client;
                // Dit is de TEST API KEY
                // $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
                // Dit is de LIVE API KEY
                $mollie->setApiKey(global_mollieAPIKeyLive);

                $payment = $mollie->payments->create(array(
                    'amount' => $totaalprijs,
                    'description' => "Bestellingnr.: " . $bestellingnummer,
                    'redirectUrl' => $redirectURL,
                    'webhookUrl' => global_websiteURL . "/index.php/winkelmandje/webhookMollie",
                ));

                $this->session->set_userdata('mollieId', $payment->id);

                // new mollie webhook - save data al voor betalen        
                //$this->bestellingmetmollieindatabase($payment->id, $karretje); // Nu geen sessies meer, dus ook geen errors meer, alle functies staan hier nu in 1

                /*                 * **** bestellingmetmollieindatabase FUNCTIE ***** */
                if (count($karretje) < 1 || $this->session->userdata('email') == "") {
                    $this->karretje();
                } else {
                    // nieuw nieuwsbrief / land
                    try {
                        $brief = $this->session->userdata('nieuwsbrief');
                    } catch (Exception $ex) {
                        $brief = 'nee';
                    }

                    $land = $this->session->userdata('land');

                    if ($brief == "ja") {
                        $nieuwsbriefinschrijving = new stdClass();
                        $nieuwsbriefinschrijving->naam = $naam;
                        $nieuwsbriefinschrijving->email = $email;

                        $this->load->model('nieuwsbriefinschrijving_model');
                        $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                    }

                    $this->load->library('email');
                    $config = array(
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'priority' => '1'
                    );
                    $this->email->initialize($config);

                    $this->email->from(global_webshopemail, global_bedrijfsnaam);
                    $this->email->to($email);

                    $this->email->subject(global_bedrijfsnaam . ' webshop');

                    // message
                    //$berichtKlantIntro = "U plaatste een bestelling bij Dulani en gaf volgende gegevens mee:<br><br>";
                    $berichtKlantIntro = "Bedankt voor uw aankoop bij " . global_bedrijfsnaam . "! Uw bestelling wordt nu doorgestuurd naar ons magazijn voor verdere verwerking. Vervolgens dragen wij uw pakket over aan B-Post die instaat voor de levering. B-Post levert in 98% van de gevallen binnen de 24u in België en 48/72u in de buurlanden. Bij eventuele vragen over uw bestelling stuur je best een mail naar " . global_webshopemail . ". Hierna een overzicht van de gegevens die wij van u hebben ontvangen:<br><br>";

                    $bericht = "KLANTGEGEVENS<br><br>";
                    $bericht .= "Naam: " . $naam . "<br>";
                    $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
                    $bericht .= "Land: " . $land . "<br>";
                    $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
                    $bericht .= "Postcode: " . $postcode . "<br>";
                    $bericht .= "Woonplaats: " . $woonplaats . "<br>";
                    $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
                    $bericht .= "E-mail: " . $email . "<br><br>";

                    $bericht .= "LEVERADRES<br><br>";

                    $bericht .= "Naam: " . $levnaam . "<br>";
                    $bericht .= "Contactpersoon: " . $levcontactpersoon . "<br>";
                    $bericht .= "Land: " . $levland . "<br>";
                    $bericht .= "Straat: " . $levstraat . ", " . $levhuisnr . "<br>";
                    $bericht .= "Postcode: " . $levpostcode . "<br>";
                    $bericht .= "Woonplaats: " . $levwoonplaats . "<br>";

                    $bericht .= "<br><br>";

                    $bericht .= "BESTELGEGEVENS<br><br>";
                    //$karretje = $this->haalopkarretje(); !!!!! VERVANGEN 
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
                    $bestelling->mollieId = $payment->id;
                    $bestelling->inAfwachting = 1;
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

// maten wegschrijven / updaten NIET HIER DOEN, gebeurd pas na betaling bevestiging
                        /* $artikelMaat = new stdClass();
                          $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                          $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                          $this->artikelmaat_model->update($artikelMaat); */
                    }
                    $setting = $this->setting_model->get(1);
                    if ($totaalprijs < $setting->taxvrijlimiet) {
                        $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                        $totaalprijs+= $setting->transportkost;
                    } else {
                        $bericht .= "Leverkosten: gratis<br>";
                    }

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

                    $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar " . global_webshopemail . " of een privé bericht via onze Facebook pagina!";

                    $bericht = $berichtKlantIntro . $bericht;

// template opvragen
                    $data['naam'] = $naam;
                    $data['bericht'] = $bericht;
                    $message = $this->load->view('templ/mailcontent', $data, TRUE);

                    $messageMollie = $message;
                    // $email message opslaan in database
                    $bestelling->id = $bestellingId;
                    $bestelling->emailText = $messageMollie;
                    $this->bestelling_model->update($bestelling);
                }

                // einde -> linken naar betaling
                header("Location: " . $payment->getPaymentUrl());
            } else {
                // END MOLLIE
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
            // $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
            // Dit is de LIVE API KEY
            $mollie->setApiKey(global_mollieAPIKeyLive);
            $mollieID = $this->session->userdata('mollieId');
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
                $naam = $this->session->userdata("naam");
                $straat = $this->session->userdata('straat');
                $huisnr = $this->session->userdata('huisnr');
                $geboortedatum = $this->session->userdata('geboortedatum');
                $postcode = $this->session->userdata('postcode');
                $woonplaats = $this->session->userdata('woonplaats');
                $telefoon = $this->session->userdata('telefoon');
//$email = $this->session->userdata('email');
                $email = $this->session->userdata('email');
                $opmerkingen = $this->session->userdata('opmerkingen');
                $kortingscode = $this->session->userdata('kortingscode');

// nieuw nieuwsbrief / land
                try {
                    $brief = $this->session->userdata('nieuwsbrief');
                } catch (Exception $ex) {
                    $brief = 'nee';
                }

                $land = $this->session->userdata('land');

                if ($brief == "ja") {
                    $nieuwsbriefinschrijving = new stdClass();
                    $nieuwsbriefinschrijving->naam = $naam;
                    $nieuwsbriefinschrijving->email = $email;

                    $this->load->model('nieuwsbriefinschrijving_model');
                    $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
                }

// alt leveradres
                if ($this->session->userdata('levstraat') === '') {
// geen leveradres
                    $levstraat = $straat;
                    $levhuisnr = $huisnr;
                    $levpostcode = $postcode;
                    $levwoonplaats = $woonplaats;
                    $levnaam = $naam;
                    $levcontactpersoon = $naam;
                    $levland = $land;
                } else {
                    $levstraat = $this->session->userdata('levstraat');
                    $levhuisnr = $this->session->userdata('levhuisnr');
                    $levpostcode = $this->session->userdata('levpostcode');
                    $levwoonplaats = $this->session->userdata('levwoonplaats');
                    $levnaam = $this->session->userdata('levnaam');
                    $levcontactpersoon = $this->session->userdata('levcontactpersoon');
                    $levland = $this->session->userdata('levland');
                }
//test

                $this->load->library('email');
                $config = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1'
                );
                $this->email->initialize($config);

                $this->email->from(global_webshopemail, global_bedrijfsnaam);
                $this->email->to($email);

                $this->email->subject(global_bedrijfsnaam . ' webshop');

// message
                $berichtKlantIntro = "U plaatste een bestelling bij " . global_bedrijfsnaam . " en gaf volgende gegevens mee:<br><br>";

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

                    $this->artikelmaat_model->update($artikelMaat, $bestellingId);
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

                $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar " . global_webshopemail . " of een privé bericht via onze Facebook pagina!";

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
                $this->email->subject('KLANT: ' . global_bedrijfsnaam . ' webshop - ' . $bestellingId . " - " . $persoon->naam);
                $this->email->message($message);
                $this->email->to(global_webshopemail);
                $this->email->send();

//stuur naar admin
                $messageMollieAdmin = "Een klant plaatste een bestelling op de " . global_bedrijfsnaam . " webshop en betaalde via Mollie ($mollieID). <br/>";
                $messageMollieAdmin .= "<a href='" . global_websiteURL . "/index.php/admin/bestelling/" . $bestellingId . "'>Klik op deze link om naar de bestelling te gaan.</a>";
                $this->email->subject(global_bedrijfsnaam . ' webshop - ' . $bestellingId . " - " . $persoon->naam . " (Mollie)");
                $this->email->message($messageMollieAdmin);
                $this->email->to(global_webshopemail);
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
    public function bestellingmetmollieindatabase($mollieID, $karretje) {
        // $karretje = $this->haalopkarretje(); !! VERVANGEN
        if (count($karretje) < 1 || $this->session->userdata('email') == "") {
            $this->karretje();
        } else {
            $naam = $this->session->userdata("naam");
            $straat = $this->session->userdata('straat');
            $huisnr = $this->session->userdata('huisnr');
            $geboortedatum = $this->session->userdata('geboortedatum');
            $postcode = $this->session->userdata('postcode');
            $woonplaats = $this->session->userdata('woonplaats');
            $telefoon = $this->session->userdata('telefoon');
//$email = $this->session->userdata('email');
            $email = $this->session->userdata('email');
            $opmerkingen = $this->session->userdata('opmerkingen');
            $kortingscode = $this->session->userdata('kortingscode');

// nieuw nieuwsbrief / land
            try {
                $brief = $this->session->userdata('nieuwsbrief');
            } catch (Exception $ex) {
                $brief = 'nee';
            }

            $land = $this->session->userdata('land');

            if ($brief == "ja") {
                $nieuwsbriefinschrijving = new stdClass();
                $nieuwsbriefinschrijving->naam = $naam;
                $nieuwsbriefinschrijving->email = $email;

                $this->load->model('nieuwsbriefinschrijving_model');
                $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
            }

// alt leveradres
            if ($this->session->userdata('levstraat') === '') {
// geen leveradres
                $levstraat = $straat;
                $levhuisnr = $huisnr;
                $levpostcode = $postcode;
                $levwoonplaats = $woonplaats;
                $levnaam = $naam;
                $levcontactpersoon = $naam;
                $levland = $land;
            } else {
                $levstraat = $this->session->userdata('levstraat');
                $levhuisnr = $this->session->userdata('levhuisnr');
                $levpostcode = $this->session->userdata('levpostcode');
                $levwoonplaats = $this->session->userdata('levwoonplaats');
                $levnaam = $this->session->userdata('levnaam');
                $levcontactpersoon = $this->session->userdata('levcontactpersoon');
                $levland = $this->session->userdata('levland');
            }
//test

            $this->load->library('email');
            $config = array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'priority' => '1'
            );
            $this->email->initialize($config);

            $this->email->from(global_webshopemail, global_bedrijfsnaam);
            $this->email->to($email);

            $this->email->subject(global_bedrijfsnaam . ' webshop');

// message
            //$berichtKlantIntro = "U plaatste een bestelling bij Dulani en gaf volgende gegevens mee:<br><br>";
            $berichtKlantIntro = "Bedankt voor uw aankoop bij " . global_bedrijfsnaam . "! Uw bestelling wordt nu doorgestuurd naar ons magazijn voor verdere verwerking. Vervolgens dragen wij uw pakket over aan B-Post die instaat voor de levering. B-Post levert in 98% van de gevallen binnen de 24u in België en 48/72u in de buurlanden. Bij eventuele vragen over uw bestelling stuur je best een mail naar " . global_webshopemail . ". Hierna een overzicht van de gegevens die wij van u hebben ontvangen:<br><br>";

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
            //$karretje = $this->haalopkarretje(); !!!!! VERVANGEN 
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

// maten wegschrijven / updaten NIET HIER DOEN, gebeurd pas na betaling bevestiging
                /* $artikelMaat = new stdClass();
                  $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                  $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                  $this->artikelmaat_model->update($artikelMaat); */
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                $totaalprijs+= $setting->transportkost;
            } else {
                $bericht .= "Leverkosten: gratis<br>";
            }

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

            $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar " . global_webshopemail . " of een privé bericht via onze Facebook pagina!";

            $bericht = $berichtKlantIntro . $bericht;

// template opvragen
            $data['naam'] = $naam;
            $data['bericht'] = $bericht;
            $message = $this->load->view('templ/mailcontent', $data, TRUE);

            $messageMollie = $message;
            // $email message opslaan in database
            $bestelling->id = $bestellingId;
            $bestelling->emailText = $messageMollie;
            $this->bestelling_model->update($bestelling);

            // unset sessies
            $this->session->unset_userdata('naam');
            $this->session->unset_userdata('straat');
            $this->session->unset_userdata('huisnr');
            $this->session->unset_userdata('geboortedatum');
            $this->session->unset_userdata('postcode');
            $this->session->unset_userdata('woonplaats');
            $this->session->unset_userdata('telefoon');
            $this->session->unset_userdata('land');

            $this->session->unset_userdata('opmerkingen');
            $this->session->unset_userdata('levstraat');
            $this->session->unset_userdata('levhuisnr');
            $this->session->unset_userdata('levpostcode');
            $this->session->unset_userdata('levwoonplaats');
            $this->session->unset_userdata('levnaam');
            $this->session->unset_userdata('levcontactpersoon');
            $this->session->unset_userdata('levland');
            $this->session->unset_userdata('kortingscode');

            /* $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');
              // hier stond email

              $data['email'] = $email;
              $data['title'] = "Bedankt";
              $data['pagina'] = "Bedankt";
              $data['categorien'] = $this->loadCategorienForMenu();


              $this->session->unset_userdata('karretje');
              this->session->unset_userdata('postdata');
              $this->template->load('main_master', $partials, $data); */
        }
    }

    public function webhookMollie() {
        // Id van transactie wordt met POST id meegegevn SESSIES WERKEN HIER NIET
        $mollieId = $this->input->post('id');

        $this->load->model('bestelling_model');
        $bestelling = $this->bestelling_model->getByMollieId($mollieId);

        $payment = null;
        // kijken of er echt wel betaald is
        if ($bestelling->betaald == 0) {
            try {
                require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

                $mollie = new Mollie_API_Client;
                // Dit is de TEST API KEY
                // $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
                // Dit is de LIVE API KEY
                $mollie->setApiKey(global_mollieAPIKeyLive);


                $payment_id = $mollieId;

                if ($payment_id == null || $payment_id == '') {
                    // ga naar juiste error pagina OF NIET 
                }
                $payment = $mollie->payments->get($payment_id);
            } catch (Mollie_API_Exception $e) {
                //echo "API call failed: " . htmlspecialchars($e->getMessage());
                //exit;
                // dit gaat niet weergegeven worden
            }

            if ($payment->isPaid() && !($payment->isRefunded())) {

                // Bestelling is afgerond
                $bestelling->inAfwachting = 0;
                $bestelling->betaald = 1;
                $bestelling->datum = date('Y-m-d H:i:s a');
                $this->bestelling_model->update($bestelling);

                // hier de afhandeling doen met database en mail versturen
                $this->load->model('persoon_model');
                $persoon = $this->persoon_model->get($bestelling->persoonId);
                $email = $persoon->email;
                $this->load->library('email');
                $config = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1'
                );
                $this->email->initialize($config);
                $this->email->from(global_webshopemail);
                $this->email->subject(global_bedrijfsnaam . ' webshop');
                $this->email->message($bestelling->emailText);
                $this->email->to($email);
                $this->email->send();

                // stuur klant bericht ook naar admin
                $this->email->subject('KLANT: ' . global_bedrijfsnaam . ' webshop - ' . $bestelling->id . " - " . $bestelling->leverNaam);
                $this->email->message($bestelling->emailText);
                $this->email->to(global_webshopemail);
                $this->email->send();

                //stuur naar admin
                $messageMollieAdmin = "Een klant plaatste een bestelling op de " . global_bedrijfsnaam . " webshop en betaalde via Mollie ($mollieID). <br/>";
                $messageMollieAdmin .= "<a href='" . global_websiteURL . "/index.php/admin/bestelling/" . $bestelling->id . "'>Klik op deze link om naar de bestelling te gaan.</a>";
                $this->email->subject(global_bedrijfsnaam . ' webshop - ' . $bestelling->id . " - " . $bestelling->leverNaam . " (Mollie)");
                $this->email->message($messageMollieAdmin);
                $this->email->to(global_webshopemail);
                $this->email->send();

                // stock aanpassen            
                $this->load->model('artikelmaat_model');
                // karretje bestaat hier ni e vriend
                /* foreach ($karretje as $karitem) {
                  // maten wegschrijven / updaten
                  $artikelMaat = new stdClass();
                  $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($karitem->artikelId, $karitem->maatId);
                  $artikelMaat->voorraad = $artikelMaat->voorraad - $karitem->aantal;

                  $this->artikelmaat_model->update($artikelMaat);
                  } */
                $this->load->model('bestellingartikel_model');
                $this->load->model('artikel_model');
                $bestelingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
                foreach ($bestelingartikels as $bestelingartikel) {
                    // maten wegschrijven / updaten
                    $maatId = $bestelingartikel->maatId;

                    $artikel = $this->artikel_model->getByBarcode($bestelingartikel->artikel->barcode);
                    $artikelId = $artikel->id;

                    $artikelMaat = new stdClass();
                    $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($artikelId, $maatId);
                    $artikelMaat->voorraad = $artikelMaat->voorraad - $bestelingartikel->aantal;

                    $this->artikelmaat_model->update($artikelMaat, $bestelling->id);
                }

                // kortingscode aanpassen            
                $this->load->model('kortingcode_model');
                $code = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);
                if (isset($code->id)) {
                    $code->gebruikt = $code->gebruikt + 1;
                    $this->kortingcode_model->update($code);
                }

                // data unsetten werkt hier toch ni ma allee
                $this->session->unset_userdata('karretje');
                $this->session->unset_userdata('postdata');
            } elseif (!$payment->isOpen()) {
                /*
                 * The payment isn't paid and isn't open anymore. We can assume it was aborted.
                 */
                $data['pagina'] = 'Bestelling geannuleerd';
                $data['title'] = "Annulatie";
                $partials = array('header' => 'templ/timer_header', 'content' => 'bestelling_annuleren', 'footer' => 'templ/main_footer');
                $this->template->load('main_master', $partials, $data);
            } elseif ($payment->isRefunded()) {
                // Bestelling is refunded            
                $bestelling->geannuleerd = 1;
                $this->bestelling_model->update($bestelling);

                // hier de afhandeling doen met database en mail versturen
                $this->load->model('persoon_model');
                $persoon = $this->persoon_model->get($bestelling->persoonId);
                $email = $persoon->email;
                $this->load->library('email');
                $config = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1'
                );
                $this->email->initialize($config);
                $this->email->from(global_webshopemail);
                $this->email->subject(global_bedrijfsnaam . ' webshop');
                $this->email->message("Het bedrag van bestelling " . $bestelling->id . " is terugbetaald!");
                $this->email->to("jeroen_vinken@hotmail.com");
                $this->email->send();
            }
        } else {
            // al betaald
            echo "Payment already succeeded";
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
            $naam = $this->session->userdata("naam");
            $straat = $this->session->userdata('straat');
            $huisnr = $this->session->userdata('huisnr');
            $geboortedatum = $this->session->userdata('geboortedatum');
            $postcode = $this->session->userdata('postcode');
            $woonplaats = $this->session->userdata('woonplaats');
            $telefoon = $this->session->userdata('telefoon');
//$email = $this->session->userdata('email');
            $email = $this->session->userdata('email');
            $opmerkingen = $this->session->userdata('opmerkingen');
            $kortingscode = $this->session->userdata('kortingscode');

// nieuw nieuwsbrief / land
            try {
                $brief = $this->session->userdata('nieuwsbrief');
            } catch (Exception $ex) {
                $brief = 'nee';
            }

            $land = $this->session->userdata('land');

            if ($brief == "ja") {
                $nieuwsbriefinschrijving = new stdClass();
                $nieuwsbriefinschrijving->naam = $naam;
                $nieuwsbriefinschrijving->email = $email;

                $this->load->model('nieuwsbriefinschrijving_model');
                $this->nieuwsbriefinschrijving_model->insert($nieuwsbriefinschrijving);
            }

// alt leveradres
            if ($this->session->userdata('levstraat') === '') {
// geen leveradres
                $levstraat = $straat;
                $levhuisnr = $huisnr;
                $levpostcode = $postcode;
                $levwoonplaats = $woonplaats;
                $levnaam = $naam;
                $levcontactpersoon = $naam;
                $levland = $land;
            } else {
                $levstraat = $this->session->userdata('levstraat');
                $levhuisnr = $this->session->userdata('levhuisnr');
                $levpostcode = $this->session->userdata('levpostcode');
                $levwoonplaats = $this->session->userdata('levwoonplaats');
                $levnaam = $this->session->userdata('levnaam');
                $levcontactpersoon = $this->session->userdata('levcontactpersoon');
                $levland = $this->session->userdata('levland');
            }
//test

            $this->load->library('email');
            $config = array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'priority' => '1'
            );
            $this->email->initialize($config);

            $this->email->from(global_webshopemail, global_bedrijfsnaam);
            $this->email->to($email);

            $this->email->subject(global_bedrijfsnaam . ' webshop');

// message
            $berichtKlantIntro = "U plaatste een bestelling bij " . global_bedrijfsnaam . " en gaf volgende gegevens mee:<br><br>";

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

                $this->artikelmaat_model->update($artikelMaat, $bestellingId);
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

            $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar " . global_webshopemail . " of een privé bericht via onze Facebook pagina!";

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
            $this->email->subject('KLANT: ' . global_bedrijfsnaam . ' webshop - ' . $bestellingId . " - " . $persoon->naam);
            $this->email->message($message);
            $this->email->to(global_webshopemail);
            $this->email->send();

//stuur naar admin
            $messagePaypalAdmin = "Een klant plaatste een bestelling op de " . global_bedrijfsnaam . " webshop en betaalde via paypal. <br/>";
            $messagePaypalAdmin .= "<a href='" . global_websiteURL . "/index.php/admin/bestelling/" . $bestellingId . "'>Klik op deze link om naar de bestelling te gaan.</a>";
            $this->email->subject(global_bedrijfsnaam . ' webshop - ' . $bestellingId . " - " . $persoon->naam . " (PayPal)");
            $this->email->message($messagePaypalAdmin);
            $this->email->to(global_webshopemail);
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

    public function betaalmetmollie($prijs = 12.34, $beschrijving = 'Online bestelling', $redirectURL = '') {
        // Karretje sessie leegmaken anders soms de nginx error van sessie vol. MAX 4000 BYTES
        $karretje = $this->haalopkarretje();
        $this->session->unset_userdata('karretje');

        require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

        $mollie = new Mollie_API_Client;
        // Dit is de TEST API KEY
        // $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
        // Dit is de LIVE API KEY
        $mollie->setApiKey(global_mollieAPIKeyLive);

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
            'webhookUrl' => global_websiteURL . "/index.php/winkelmandje/webhookMollie",
        ));

        //$payment->redirectUrl .=  "/" . $payment->id;
        $this->session->set_userdata('mollieId', $payment->id);

        // new mollie webhook - save data al voor betalen        
        $this->bestellingmetmollieindatabase($payment->id, $karretje);

        header("Location: " . $payment->getPaymentUrl());
    }

    public function mollieok() {
        header("Location: " . global_websiteURL);
    }

    public function errortest() {
        $e = 0 / 0;
    }

    public function bedanktMollie() {
        $mollieId = $this->session->userdata('mollieId');

        // kijken of er echt wel betaald is
        try {
            require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

            $mollie = new Mollie_API_Client;
            // Dit is de TEST API KEY
            // $mollie->setApiKey('test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
            // Dit is de LIVE API KEY
            $mollie->setApiKey(global_mollieAPIKeyLive);


            $payment_id = $mollieId;

            if ($payment_id == null || $payment_id == '') {
                // ga naar juiste error pagina OF NIET 
            }
            $payment = $mollie->payments->get($payment_id);
        } catch (Mollie_API_Exception $e) {
            //echo "API call failed: " . htmlspecialchars($e->getMessage());
            //exit;
            // dit gaat niet weergegeven worden
        }

        if ($payment->isPaid()) {
            // Bestelling is afgerond
            $data['email'] = $this->session->userdata('email');
            $data['title'] = "Bedankt mollie";
            $data['pagina'] = "Bedankt mollie";
            $data['categorien'] = $this->loadCategorienForMenu();

            $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        } elseif (!$payment->isOpen()) {
            /*
             * The payment isn't paid and isn't open anymore. We can assume it was aborted.
             */
            $data['pagina'] = 'Bestelling geannuleerd';
            $data['title'] = "Annulatie";
            $data['categorien'] = $this->loadCategorienForMenu();
            $partials = array('header' => 'templ/main_header', 'content' => 'bestelling_annuleren', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function betalenbpost() { 
        $karretje = $this->haalopkarretje();

        if (count($karretje) < 1) {
            $this->index();
        } else {
            $data['title'] = global_bedrijfsnaam;
            $data['pagina'] = 'Winkelmandje';
            $partials = array('header' => 'templ/main_header', 'content' => 'afrekenen_bpost', 'footer' => 'templ/main_footer');

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
    }

    /* This function is called when the user has filled in all the data and wanted their order to be delivered and payed at their wanted location (rembours) 
     * This function sends out the mails to admin and the client and shows a thank you page afterwards.
     */

    public function bedanktbpostrembours() {        
        $bestellingid = $this->session->userdata('bestellingid');
        $this->session->unset_userdata('bestellingid');
        if ($bestellingid == null || count($this->session->userdata('karretje')) == 0) {
            $this->index();
        } else {
            $this->session->unset_userdata('karretje');
            
            $this->load->model('bestelling_model');
            $bestelling = $this->bestelling_model->get($bestellingid);

            // Bestelling is afgerond
            $bestelling->inAfwachting = 0;
            $bestelling->betaald = 0;
            $bestelling->datum = date('Y-m-d H:i:s a');
            $this->bestelling_model->update($bestelling);

            // hier de afhandeling doen met database en mail versturen
            $this->load->model('persoon_model');
            $persoon = $this->persoon_model->get($bestelling->persoonId);
            $email = $persoon->email;
            $this->load->library('email');
            $config = array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'priority' => '1'
            );
            $this->email->initialize($config);
            $this->email->from(global_webshopemail);
            $this->email->subject(global_bedrijfsnaam . ' webshop');
            $this->email->message($bestelling->emailText);
            $this->email->to($email);
            $this->email->send();

            // stuur klant bericht ook naar admin
            $this->email->subject('KLANT: ' . global_bedrijfsnaam . ' webshop - ' . $bestelling->id . " - " . $bestelling->leverNaam);
            $this->email->message($bestelling->emailText);
            $this->email->to(global_webshopemail);
            $this->email->send();

            //stuur naar admin
            $messageMollieAdmin = "Een klant plaatste een bestelling op de " . global_bedrijfsnaam . " webshop en moet nog betalen bij levering. <br/>";
            $messageMollieAdmin .= "<a href='" . global_websiteURL . "/index.php/admin/bestelling/" . $bestelling->id . "'>Klik op deze link om naar de bestelling te gaan.</a>";
            $this->email->subject(global_bedrijfsnaam . ' webshop - ' . $bestelling->id . " - " . $bestelling->leverNaam . " (Rembours)");
            $this->email->message($messageMollieAdmin);
            $this->email->to(global_webshopemail);
            $this->email->send();

            // stock aanpassen            
            $this->load->model('artikelmaat_model');
            $this->load->model('bestellingartikel_model');
            $this->load->model('artikel_model');
            $bestelingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
            foreach ($bestelingartikels as $bestelingartikel) {
                // maten wegschrijven / updaten
                $maatId = $bestelingartikel->maatId;
                
                /* dit gaat er maar van uit dat er altijd een barcode bij een product hoort */
                $artikel = $this->artikel_model->getByBarcode($bestelingartikel->artikel->barcode);
                $artikelId = $artikel->id;

                $artikelMaat = new stdClass();
                $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($artikelId, $maatId);
                $artikelMaat->voorraad = $artikelMaat->voorraad - $bestelingartikel->aantal;

                $this->artikelmaat_model->update($artikelMaat, $bestelling->id);
            }

            // kortingscode aanpassen   
            $this->load->model('kortingcode_model');
            if ($bestelling->kortingCodeID != NULL) {
                $code = $this->kortingcode_model->get($bestelling->kortingCodeID);
                if (isset($code->id)) {
                    $code->gebruikt = $code->gebruikt + 1;
                    $this->kortingcode_model->update($code);
                }
            }

            // Bestelling is afgerond
            $data['email'] = $email;
            $data['title'] = "Bedankt BPOST";
            $data['pagina'] = "Bedankt BPOST";
            $data['categorien'] = $this->loadCategorienForMenu();

            $partials = array('header' => 'templ/main_header', 'content' => 'bedankt', 'footer' => 'templ/main_footer');
            $this->template->load('main_master', $partials, $data);
        }
    }

    public function bevestigbpostbestelling() {
        // Karretje sessie leegmaken anders soms de nginx error van sessie vol. MAX 4000 BYTES        
        $karretje = $this->haalopkarretje();
        $this->session->unset_userdata('karretje');
        $bestellingid = $this->session->userdata('bestellingid');
        $this->session->unset_userdata('bestellingid');
        if (count($karretje) < 1 || $bestellingid == null) {
            $this->index();
        } else {
            //$bestellingid = $this->input->get('bestellingid');
            $redirectURL = global_websiteURL . "/index.php/winkelmandje/bedanktMollie";
            //$totaalprijs = $this->session->userdata('totaalprijs');
            //$this->session->unset_userdata('totaalprijs');

            $this->load->model('bestelling_model');
            $bestelling = $this->bestelling_model->get($bestellingid);


            // totaalprijs berekenen voor de laatste keer
            $this->load->model("artikel_model");
            $this->load->model('setting_model');
            $totaalprijs = 0.00;

            foreach ($karretje as $karitem) {
                $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                // korting toekennen
                $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));
                $totaalprijs += $artikel->prijs * $karitem->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            } else {
                
            }

            //kortingcode valideren en pas updaten na betaling
            if ($bestelling->kortingCodeID != NULL) {
                $this->load->model('kortingcode_model');
                $code = $this->kortingcode_model->get($bestelling->kortingCodeID);
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
                        $totaalprijs = 0.50;
                    }
                }
            }


            require_once __DIR__ . '/../src/Mollie/API/Autoloader.php';

            $mollie = new Mollie_API_Client;
            $mollie->setApiKey(global_mollieAPIKeyLive);

            $payment = $mollie->payments->create(array(
                'amount' => $totaalprijs,
                'description' => "Bestellingnr.: " . $bestellingid,
                'redirectUrl' => $redirectURL,
                'webhookUrl' => global_websiteURL . "/index.php/winkelmandje/webhookMollie",
            ));

            $this->session->set_userdata('mollieId', $payment->id);

            // MollieId toevoegen aan bestelling
            //$this->load->model('bestelling_model');
            //$bestelling = $this->bestelling_model->get($bestellingid);
            $bestelling->mollieId = $payment->id;
            $this->bestelling_model->update($bestelling);

            // einde -> linken naar betaling
            header("Location: " . $payment->getPaymentUrl());
            //echo $payment->getPaymentUrl();
        }
    }

    /* Function for placing an order in the database after the client has filled in their BPOST data. 
     * The rembours variable determines whether the client wants to pay the delivery man or pay online. 
     * If rembours is chosen you must not go to a payment page but straight to the thank you page and the mail should be slightly altered.
     */

    public function bestellingplaatsenbpost($bestellingid, $rembours = false) {
        $karretje = $this->haalopkarretje();

        if (count($karretje) < 1) {
            $this->index();
        } else {
            // get existing data from server by ID
            $this->load->model('bestelling_model');
            $bestelling = $this->bestelling_model->get($bestellingid);
            $kortingscodeid = $bestelling->kortingCodeID;

            // get data from bpost
            $naam = $this->input->post('customerFirstName');
            $naam .= " " . $this->input->post('customerLastName');
            $straat = $this->input->post('customerStreet');
            $huisnr = $this->input->post('customerStreetNumber');
            $postcode = $this->input->post('customerPostalCode');
            $woonplaats = $this->input->post('customerCity');
            $telefoon = $this->input->post('customerPhoneNumber');
            $email = $this->input->post('customerEmail');
            if ($email == '' || $email == NULL) {
                $email = $this->input->post('extra');
            }
            $land = $this->input->post('customerCountry');
            $geboortedatum = $this->session->userdata('geboortedatum');

            $this->session->unset_userdata('geboortedatum');

            $levstraat = $straat;
            $levhuisnr = $huisnr;
            $levpostcode = $postcode;
            $levwoonplaats = $woonplaats;
            $levnaam = $naam;
            $levcontactpersoon = $naam;
            $levland = $land;

            $this->session->set_userdata('email', $email);

            // message opstellen en opslaan in database            
            $berichtKlantIntro = "Bedankt voor uw aankoop bij " . global_bedrijfsnaam . "! Uw bestelling wordt nu doorgestuurd naar ons magazijn voor verdere verwerking. Vervolgens dragen wij uw pakket over aan B-Post die instaat voor de levering. B-Post levert in 98% van de gevallen binnen de 24u in België en 48/72u in de buurlanden. Bij eventuele vragen over uw bestelling stuur je best een mail naar " . global_webshopemail . ". Hierna een overzicht van de gegevens die wij van u hebben ontvangen:<br><br>";

            $bericht = "KLANTGEGEVENS<br><br>";
            $bericht .= "Naam: " . $naam . "<br>";
            $bericht .= "Geboortedatum: " . $geboortedatum . "<br>";
            $bericht .= "Land: " . $land . "<br>";
            $bericht .= "Straat: " . $straat . ", " . $huisnr . "<br>";
            $bericht .= "Postcode: " . $postcode . "<br>";
            $bericht .= "Woonplaats: " . $woonplaats . "<br>";
            $bericht .= "Telefoonnummer: " . $telefoon . "<br>";
            $bericht .= "E-mail: " . $email . "<br><br>";

            $bericht .= "LEVERADRES<br><br>";

            $bericht .= "Naam: " . $levnaam . "<br>";
            $bericht .= "Contactpersoon: " . $levcontactpersoon . "<br>";
            $bericht .= "Land: " . $levland . "<br>";
            $bericht .= "Straat: " . $levstraat . ", " . $levhuisnr . "<br>";
            $bericht .= "Postcode: " . $levpostcode . "<br>";
            $bericht .= "Woonplaats: " . $levwoonplaats . "<br>";

            $bericht .= "<br><br>";

            $bericht .= "BESTELGEGEVENS<br><br>";
            //bestellingartikel toevoegen in loop        
            $this->load->model('bestellingartikel_model');
            $this->load->model('verkochtartikel_model');
            $this->load->model('artikelmaat_model');
            $this->load->model('setting_model');
            $this->load->model("artikel_model");
            $this->load->model('maat_model');


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
                //$karitem->artikel = $artikel;
                //$karitem->maat = $maat;
                $bericht .= $karitem->aantal . "x " . $artikel->naam . " (maat: " . $maat->maat . ") EUR " . number_format($artikel->prijs * $karitem->aantal, 2) . "<br>";

                // toevoegen aan database
                $bestellingArtikel = new stdClass();
                $bestellingArtikel->bestellingId = $bestellingid;
                $bestellingArtikel->verkochtArtikelId = $verkochtArtikelId;
                $bestellingArtikel->maatId = $maat->id;
                $bestellingArtikel->aantal = $karitem->aantal;

                $this->bestellingartikel_model->insert($bestellingArtikel);

                // maten wegschrijven / updaten NIET HIER DOEN, gebeurd pas na betaling bevestiging
            }

            // settings opvragen ivm leverkosten etc.
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $bericht .= "Leverkosten: EUR " . $setting->transportkost . "<br>";
                $totaalprijs+= $setting->transportkost;
            } else {
                $bericht .= "Leverkosten: gratis<br>";
            }

            if ($rembours) {
                /* rembours kost altijd 5 euro extra na alle berekeningen */
                $bericht .= "Betalen bij levering: EUR 5.00<br>";
            }

            // kortingsbon (eigenlijk pas updaten na effectief betalen)
            $this->load->model('kortingcode_model');
            $code = $this->kortingcode_model->get($kortingscodeid);
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
                    $totaalprijs = 0.50; //anders accepteerd mollie de betaling niet
                }
            }

            if ($rembours) {
                /* rembours kost altijd 5 euro extra na alle berekeningen */
                $totaalprijs += 5;
            }

            $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

            $this->session->set_userdata('totaalprijs', $totaalprijs);

            $bericht .= "OPMERKINGEN<br><br>";
            $bericht .= $bestelling->opmerkingen . "<br><br>";

            $bericht .= "Heeft u vragen over uw bestelling? Stuur dan een mail naar " . global_webshopemail . " of een privé bericht via onze Facebook pagina!";

            $bericht = $berichtKlantIntro . $bericht;

            // template opvragen
            $data['naam'] = $naam;
            $data['bericht'] = $bericht;
            $message = $this->load->view('templ/mailcontent', $data, TRUE);

            $messageMollie = $message;
            // $email message opslaan in database            
            $bestelling->emailText = $messageMollie;
            $this->bestelling_model->update($bestelling);

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

            //$bestelling = new stdClass();
            $bestelling->persoonId = $persoonId;
            $bestelling->datum = date('Y-m-d H:i:s a');

            // alt lever adres
            $bestelling->leverStraat = $levstraat;
            $bestelling->leverHuisnummer = $levhuisnr;
            $bestelling->leverPostcode = $levpostcode;
            $bestelling->leverGemeente = $levwoonplaats;
            $bestelling->leverNaam = $levnaam;
            $bestelling->leverContactpersoon = $levcontactpersoon;
            $bestelling->leverLand = $levland;
            $bestelling->paypal = 0;
            if ($rembours) {
                $bestelling->mollieId = 'REMBOURS ' . $bestelling->id;
            } else {
                $bestelling->mollieId = 'NOT_INITIATED';
            }
            $bestelling->inAfwachting = 1;
            $bestelling->verzonden = 0;
            $bestelling->betaald = 0;
            $bestelling->datum = date('Y-m-d H:i:s a');

            // update bestelling data
            $this->bestelling_model->update($bestelling);

            // data opstellen voor overzicht op pagina
            $overzicht = "<table class='overzichtBpost'>";
            $overzicht .= "<tr><td><label>Naam: </label></td><td>" . $naam . "</td></tr>";
            $overzicht .= "<tr><td><label>Email: </label></td><td>" . $email . "</td></tr>";
            $overzicht .= "<tr><td><label>Geboortedatum: </label></td><td>" . $geboortedatum . "</td></tr>";
            $overzicht .= "<tr><td><label>Leveradres: </label></td><td>" . $levstraat . " " . $levhuisnr . ", " . $levpostcode . " " . $levwoonplaats . " (" . $levland . ")</td></tr>";
            if ($rembours) {
                $overzicht .= "<tr><td><label>Betaalkeuze: </label></td><td>Aan de deur (rembourszending)</td></tr>";
            } else {
                $overzicht .= "<tr><td><label>Betaalkeuze: </label></td><td>Online betalen</td></tr>";
            }
            $overzicht .= "<tr><td><label>Opmerkingen: </label></td><td>" . $bestelling->opmerkingen . "</td></tr>";
            $overzicht .= "</table>";
            if (isset($code->id)) {
                $overzicht .= "<p style='text-align: center; margin-bottom:20px;'>Totaalprijs met kortingscode: &euro;" . number_format($totaalprijs, 2) . "</p>";
            } else {
                $overzicht .= "<p style='text-align: center; margin-bottom:20px;'>Totaalprijs: &euro;" . number_format($totaalprijs, 2) . "</p>";
            }

            $data["overzicht"] = $overzicht;

            // Nu moet de gebruiker enkel nog op betalen klikken en de betaling uitvoeren zie functie bevestigbpostbestelling
            $this->load->view('afrekenen_bpost_ajax_confirm', $data); // misschien bedankt naam, ... hier ook al zetten
        }
    }

    public function bestellingstoppenbpost($bestellingid) {
        $this->load->model("bestelling_model");
        $this->bestelling_model->delete($bestellingid);

        $this->load->view('afrekenen_bpost_annulatie');
    }

    // Here we get the order ID and assign it to the BPOST order. We fill in what we already received from the user (email, geboortedatum, kortingscode, land)
    // Then we create a sha256 hash to make sure we are not a scrub wanna be hacker, and send it to BPOST with the other needed data.

    public function createBestellingBeforeBpost() {
        $email = $this->input->get('email');

        if ($email != "") {
            $geboortedatum = $this->input->get('geboortedatum');
            $kortingscode = $this->input->get('kortingcode');
            $land = $this->input->get('land');
            $opmerking = $this->input->get('opmerkingen');
            /* Betaalmethode is een waarde online of levering, bij online wordt er online betaald, bij levering wordt er betaald aan de postbode */
            $betaalmethode = $this->input->get('betaalmethode');
            /* device is the device that the user is using to make an order */
            $device = $this->input->get('device');

            $this->load->model("bestelling_model");
            $newbestelling = new stdClass();
            $newbestelling->opmerkingen = $opmerking;
            $newbestelling->datum = date('Y-m-d H:i:s a');
            $newbestelling->device = $device;
            $newbestelling->emailText = "Before BPOST E-mail: " . $email;
            $newbestelling->inAfwachting = 1;

            $this->load->model('kortingcode_model');
            $codeVoorBestelling = $this->kortingcode_model->getByCodeAndValidateCode($kortingscode);

            if ($codeVoorBestelling == null) {
                $newbestelling->kortingCodeID = null;
            } else {
                $newbestelling->kortingCodeID = $codeVoorBestelling->id;
            }

            $karretje = $this->haalopkarretje();
            $this->load->model("artikel_model");
            $this->load->model("setting_model");

            $totaalprijs = 0.00;
            $artikelString = "";
            foreach ($karretje as $karitem) {
                $artikel = $this->artikel_model->getSolo($karitem->artikelId);
                // korting toekennen
                $artikel->prijs = ($artikel->prijs - ($artikel->prijs * $artikel->korting / 100));
                $totaalprijs += $artikel->prijs * $karitem->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            } else {
                
            }

            $code = $codeVoorBestelling;
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
                    $totaalprijs = 0.50; //anders accepteerd mollie de betaling niet
                }
            }

            $id = $this->bestelling_model->insert($newbestelling);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $weight = 1000;
            } else {
                $weight = 2500;
            }

            $hashstring = "";
            $deliveryMethodOverrides = "Parcels depot|VISIBLE";
            /* Als betaling aan postbode is gekozen moet er ALTIJD 5 euro bijkomen bij het totaalbedrag OOK bovenop de leveringskosten en na de kortingscode berekening */
            if ($betaalmethode != "online" && $betaalmethode == "levering") {
                $totaalprijs += 5;
                $deliveryMethodOverrides = "Parcels depot|INVISIBLE&deliveryMethodOverrides=Pugo|INVISIBLE";
            }

            $hashstring = 'accountId=' . global_bpostid . '&customerCountry=' . $land . '&deliveryMethodOverrides=' . $deliveryMethodOverrides . '&orderReference=' . $id . '&orderWeight=' . $weight . '&' . global_bpostww;

            $hash = hash('sha256', $hashstring);

            $data["email"] = $email;
            $data["land"] = $land;
            $data["weight"] = $weight;
            $data["hash"] = $hash;
            $data["bestellingid"] = $id;
            $deliveryMethodOverridesForJava = $deliveryMethodOverrides;
            if ($deliveryMethodOverrides != "Parcels depot|VISIBLE") {
                $deliveryMethodOverridesForJava = "['Parcels depot|INVISIBLE', 'Pugo|INVISIBLE']";
            }
            $data["deliveryMethodOverrides"] = $deliveryMethodOverridesForJava;
            $this->session->set_userdata('bestellingid', $id); // bestellingID checken via sessie for security reasons
            $this->session->set_userdata('geboortedatum', $geboortedatum);
            $data["bpostid"] = global_bpostid;

            $this->load->view('afrekenen_bpost_ajax', $data);
        } else {
            echo "Mail niet ingevuld";
        }
    }

    /* FUNCTIE OM TE TESTEN EN HANDMATIG ORDERS TE CONFIRMEN */

    function bpostconfirm() {
        $land = 'BE';
        $bestellingId = "1870";
        // BPOST HACK
        $hashstring = 'accountId=' . global_bpostid . '&action=CONFIRM&customerCountry=' . $land . '&orderReference=' . $bestellingId . '&' . global_bpostww;
        $hash = hash('sha256', $hashstring);
        $data["hash"] = $hash;
        $data["orderReference"] = $bestellingId;
        $data["customerCountry"] = $land;
        $data["bpostid"] = global_bpostid;

        $data["pagina"] = 'test';
        $this->load->model('onlangsbekeken_model');
        $onlangsbekeken = $this->onlangsbekeken_model->haaloponlangsbekeken();
        $data['onlangsbekeken'] = $onlangsbekeken;

        $data['categorien'] = $this->loadCategorienForMenu();
        $partials = array('header' => 'templ/main_header', 'content' => 'bpostok', 'footer' => 'templ/main_footer');
        $this->template->load('main_master', $partials, $data);
        //$this->load->view('bpostok', $data);     
    }

}
