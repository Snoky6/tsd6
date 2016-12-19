<?php

class Bestelling_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('id', 'asc');

        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();
        return $bestellingen;
    }

    function getAllWithInfo() {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief', '0');
        $this->db->where('inAfwachting', '0');

        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();

        $this->load->model('verkochtartikel_model');
        $this->load->model('bestellingartikel_model');
        $this->load->model('persoon_model');
        $this->load->model('setting_model');
        $this->load->model('kortingcode_model');

        foreach ($bestellingen as $bestelling) {
            $bestelling->persoon = $this->persoon_model->get($bestelling->persoonId);
            $bestelling->bestellingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
            $totaalprijs = 0.00;
            foreach ($bestelling->bestellingartikels as $artikel) {
                //$artikel->artikel->prijs = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $prijsmetkorting = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $totaalprijs += ($prijsmetkorting) * $artikel->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            }
            // kortingscode toekennen
            $bestelling->kortingCode = $this->kortingcode_model->get($bestelling->kortingCodeID);
            $code = $bestelling->kortingCode;
            $totaalPrijsVoorKorting = $totaalprijs;
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                } else {
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }
            }


            $bestelling->totaalprijs = $totaalprijs;
        }

        return $bestellingen;
    }
    
    function getLastBestellingNummer() {
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get('bestelling');

        $bestelling = $query->row();
        
        return $bestelling->id;
    }
    
    function getLastAmountWithInfo($amount) {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief', '0');
        $this->db->where('inAfwachting', '0');
        $this->db->limit($amount);

        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();

        $this->load->model('verkochtartikel_model');
        $this->load->model('bestellingartikel_model');
        $this->load->model('persoon_model');
        $this->load->model('setting_model');
        $this->load->model('kortingcode_model');

        foreach ($bestellingen as $bestelling) {
            $bestelling->persoon = $this->persoon_model->get($bestelling->persoonId);
            $bestelling->bestellingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
            $totaalprijs = 0.00;
            foreach ($bestelling->bestellingartikels as $artikel) {
                //$artikel->artikel->prijs = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $prijsmetkorting = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $totaalprijs += ($prijsmetkorting) * $artikel->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            }
            // kortingscode toekennen
            $bestelling->kortingCode = $this->kortingcode_model->get($bestelling->kortingCodeID);
            $code = $bestelling->kortingCode;
            $totaalPrijsVoorKorting = $totaalprijs;
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                } else {
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }
            }


            $bestelling->totaalprijs = $totaalprijs;
        }

        return $bestellingen;
    }
    
    function getAllByInputAdmin($input) {        
        $this->db->where('inAfwachting', '0');
        $this->db->where('archief =', 0);        
        $this->db->order_by('id', 'desc');        

        $where = '(leverContactpersoon LIKE "%' . $input . '%" OR id LIKE "%' . $input . '%" OR mollieId LIKE "%' . $input . '%" OR leverNaam LIKE "%' . $input . '%")';
        $this->db->where($where);

        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();

        $this->load->model('verkochtartikel_model');
        $this->load->model('bestellingartikel_model');
        $this->load->model('persoon_model');
        $this->load->model('setting_model');
        $this->load->model('kortingcode_model');

        foreach ($bestellingen as $bestelling) {
            $bestelling->persoon = $this->persoon_model->get($bestelling->persoonId);
            $bestelling->bestellingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
            $totaalprijs = 0.00;
            foreach ($bestelling->bestellingartikels as $artikel) {
                //$artikel->artikel->prijs = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $prijsmetkorting = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $totaalprijs += ($prijsmetkorting) * $artikel->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            }
            // kortingscode toekennen
            $bestelling->kortingCode = $this->kortingcode_model->get($bestelling->kortingCodeID);
            $code = $bestelling->kortingCode;
            $totaalPrijsVoorKorting = $totaalprijs;
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                } else {
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }
            }


            $bestelling->totaalprijs = $totaalprijs;
        }

        return $bestellingen;
    }

    function getAllWithInfoOokArchief() {
        $this->db->order_by('id', 'desc');
        $this->db->where('geannuleerd', 0);
        $this->db->where('betaald', 1);
        
        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();

        $this->load->model('verkochtartikel_model');
        $this->load->model('bestellingartikel_model');
        $this->load->model('persoon_model');
        $this->load->model('setting_model');
        $this->load->model('kortingcode_model');

        foreach ($bestellingen as $bestelling) {
            $bestelling->persoon = $this->persoon_model->get($bestelling->persoonId);
            $bestelling->bestellingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
            $totaalprijs = 0.00;
            foreach ($bestelling->bestellingartikels as $artikel) {
                //$artikel->artikel->prijs = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $prijsmetkorting = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $totaalprijs += ($prijsmetkorting) * $artikel->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            }
            // kortingscode toekennen
            $bestelling->kortingCode = $this->kortingcode_model->get($bestelling->kortingCodeID);
            $code = $bestelling->kortingCode;
            $totaalPrijsVoorKorting = $totaalprijs;
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                } else {
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }
            }


            $bestelling->totaalprijs = $totaalprijs;
        }

        return $bestellingen;
    }

    function getWithInfo($id) {
        $this->db->where('id', $id);

        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();

        $this->load->model('verkochtartikel_model');
        $this->load->model('bestellingartikel_model');
        $this->load->model('persoon_model');
        $this->load->model('setting_model');
        $this->load->model('kortingcode_model');

        foreach ($bestellingen as $bestelling) {
            $bestelling->persoon = $this->persoon_model->get($bestelling->persoonId);
            $bestelling->bestellingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
            $totaalprijs = 0.00;
            foreach ($bestelling->bestellingartikels as $artikel) {
                //$artikel->artikel->prijs = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $prijsmetkorting = ($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100));
                $totaalprijs += ($prijsmetkorting) * $artikel->aantal;
            }
            $setting = $this->setting_model->get(1);
            if ($totaalprijs < $setting->taxvrijlimiet) {
                $totaalprijs+= $setting->transportkost;
            }

            //kortingcode
            $bestelling->kortingCode = $this->kortingcode_model->get($bestelling->kortingCodeID);
            $code = $bestelling->kortingCode;
            $totaalPrijsVoorKorting = $totaalprijs;
            if (isset($code->id)) {
                if ($code->kortingBedrag == null) {
                    $kortingPercentage = $code->kortingProcent;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - ($totaalPrijsVoorKorting * $kortingPercentage / 100);
                } else {
                    $kortingBedrag = $code->kortingBedrag;
                    $totaalPrijsNaKorting = $totaalPrijsVoorKorting - $kortingBedrag;
                }
                $totaalprijs = $totaalPrijsNaKorting;

                if ($totaalprijs < 0) {
                    $totaalprijs = 0;
                }
            }


            $bestelling->totaalprijs = $totaalprijs;
        }



        return $bestellingen;
    }

    function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestelling');
        return $query->row();
    }

    function getByMollieId($id) {
        $this->db->where('mollieId', $id);
        $query = $this->db->get('bestelling');
        return $query->row();
    }

    function insert($bestelling) {
        $this->db->insert('bestelling', $bestelling);
        return $this->db->insert_id();
    }

    function update($bestelling) {
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('bestelling');
    }

    function archiveer($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestelling');
        $bestelling = $query->row();
        if ($bestelling->archief == 0) {
            $bestelling->archief = 1;
        } else {
            $bestelling->archief = 0;
        }
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }

    function verzend($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestelling');
        $bestelling = $query->row();
        if ($bestelling->verzonden == 0) {
            $bestelling->verzonden = 1;
        } else {
            $bestelling->verzonden = 0;
        }
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }

    function betaal($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestelling');
        $bestelling = $query->row();
        if ($bestelling->betaald == 0) {
            $bestelling->betaald = 1;
        } else {
            $bestelling->betaald = 0;
        }
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }

    function annuleer($id) {
        $bestelling = $this->get($id);
        $this->load->model('bestellingartikel_model');
        $bestelling->bestellingartikels = $this->bestellingartikel_model->getAllWithArtikelByBestellingId($bestelling->id);
        /* $this->db->where('id', $id);
          $query = $this->db->get('bestelling');
          $bestelling = $query->row(); */
        if ($bestelling->geannuleerd == 0) {
            $bestelling->geannuleerd = 1;
            // vermeerder stock!
            $this->load->model('artikelmaat_model');

            foreach ($bestelling->bestellingartikels as $bestellingartikel) {

                // maten wegschrijven / updaten
                $verkochtartikel = $bestellingartikel->artikel;
                $maat = $bestellingartikel->maat;
                $this->load->model('artikel_model');
                $this->load->model('artikelmaat_model');

                $artikel = $this->artikel_model->getByBarcode($verkochtartikel->barcode);

                $artikelMaat = new stdClass();
                $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($artikel->id, $maat->id);
                $artikelMaat->voorraad = $artikelMaat->voorraad + $bestellingartikel->aantal;
                $this->artikelmaat_model->update($artikelMaat);
            }
        } else {
            $bestelling->geannuleerd = 0;
            // verminder stock terug!
            $this->load->model('artikelmaat_model');

            foreach ($bestelling->bestellingartikels as $bestellingartikel) {

                // maten wegschrijven / updaten
                $verkochtartikel = $bestellingartikel->artikel;
                $maat = $bestellingartikel->maat;
                $this->load->model('artikel_model');
                $this->load->model('artikelmaat_model');

                $artikel = $this->artikel_model->getByBarcode($verkochtartikel->barcode);

                $artikelMaat = new stdClass();
                $artikelMaat = $this->artikelmaat_model->getByArtikelIdAndMaatId($artikel->id, $maat->id);
                $artikelMaat->voorraad = $artikelMaat->voorraad - $bestellingartikel->aantal;
                $this->artikelmaat_model->update($artikelMaat);
            }
        }
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }

    function reserveer($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestelling');
        $bestelling = $query->row();
        if ($bestelling->gereserveerd == 0) {
            $bestelling->gereserveerd = 1;
        } else {
            $bestelling->gereserveerd = 0;
        }
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }

    function remind($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bestelling');
        $bestelling = $query->row();
        if ($bestelling->reminder == 0) {
            $bestelling->reminder = 1;
        } else {
            $bestelling->reminder = 0;
        }
        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);

        // nog email versturen!
    }

    function checkforneworder($lastchecktime) {
        $this->db->order_by('id', 'asc');
        $where = "datum >= '$lastchecktime' AND gereserveerd = 0 AND archief = 0 AND geannuleerd = 0 AND inAfwachting = 0 AND (betaald = 1 OR mollieId LIKE '%REMBOURS%')";        
        $this->db->where($where);        
        
        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();
        return $bestellingen;
    }
}

?>