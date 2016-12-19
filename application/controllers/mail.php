<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mail extends CI_Controller {

    public function index() {
        $this->load->library('email');
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'priority' => '1'
        );
        $this->email->initialize($config);

        $this->email->from(global_webshopemail, global_bedrijfsnaam);
        $this->email->to(global_webshopemail);

        $bericht = "<b>KLANTGEGEVENS</b><br><br>";
        $bericht .= "Naam: " . "Jeroen Vinken" . "<br>";
        $bericht .= "Geboortedatum: " . "16 juli 1993" . "<br>";
        $bericht .= "Straat: " . "Hertog janplein" . ", " . "14D" . "<br>";
        $bericht .= "Postcode: " . "3920" . "<br>";
        $bericht .= "Woonplaats: " . "Lommel" . "<br>";
        $bericht .= "Telefoonnummer: " . "0473137332" . "<br>";
        $bericht .= "E-mail: " . "jeroen_vinken@hotmail.com" . "<br><br>";

        $bericht .= "<b>BESTELGEGEVENS</b><br><br>";

        $totaalprijs = 25.99;

        $bericht .= "2x Jacket (maat: Large): &euro;25.99<br>";

        if ($totaalprijs <= 50) {
            $bericht .= "Leverkosten: EUR 8<br>";
            $totaalprijs+=8;
        } else {
            $bericht .= "Leverkosten: gratis<br>";
        }
        $bericht .= "Totaalprijs: EUR " . number_format($totaalprijs, 2) . "<br><br>";

        $bericht .= "<b>OPMERKINGEN</b><br><br>";
        $bericht .= "Dit is een opmerking." . "<br><br>";

        $mededeling = "32";
        $berichtKlantIntro = "Bedankt voor uw aankoop bij " . global_bedrijfsnaam . "! Gelieve zo snel mogelijk &euro;" . $totaalprijs . " over te schrijven. U gebruikt daarvoor de volgende gegevens:<br>";
        $berichtKlantIntro .= "<ul><li>Rekeningnummer: BE04850848035531</li><li>BIC code: SPAABE22</li><li>Naam: " . global_bedrijfsnaam . " St-Jozefsstraat 13/2 3500 Hasselt</li>";
        $berichtKlantIntro .= "<li>Mededeling: " . $mededeling . "</li></ul>";
        $berichtKlantIntro .= "<br/><br/>Hieronder vindt u de gegevens van uw bestelling terug:<br><br>";

        $bericht = $berichtKlantIntro . $bericht;

        // template opvragen
        $data['naam'] = "Jeroen Vinken";
        $data['bericht'] = $bericht;


        $message = $this->load->view('templ/mailcontent', $data, TRUE);

        $this->email->subject(global_bedrijfsnaam . ' webshop');
        $this->email->message($message);
        $this->email->send();
    }

}
