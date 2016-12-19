<?php

class Artikel_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $this->db->order_by('naam', 'asc');
        // alleen die in voorraad
        //$this->db->where('name !=', $name);
        $this->db->where('archief =', 0);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);

            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                
            } else {
                // geen maten meer beschikbaar, dus niet tonen
                unset($artikels[$counter]);
            }
            $counter++;
        }

        return $artikels;
    }

    function checkIfBarcodeExists($barcode) {
        $this->db->where('barcode', $barcode);
        //$this->db->where('archief =', 0);
        $query = $this->db->get('artikel');

        $artikel = $query->row();


        if (isset($artikel->id)) {
            return "false";
        } else {
            return "true";
        }
    }

    function getAllByInput($input) {
        $this->db->order_by('id', 'desc');
        //$this->db->where('archief =', 0);

        $where = '(naam LIKE "%' . $input . '%" OR barcode LIKE "' . $input . '")';
        $this->db->where($where);
        $this->db->where('archief =', 0);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);

            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                
            } else {
                // geen maten meer beschikbaar, dus niet tonen
                unset($artikels[$counter]);
            }
            $counter++;
        }

        return $artikels;
    }

    function getAllByInputAndSort($input, $sort) {
        switch ($sort) {
            case "prijs":
                $this->db->order_by('prijs', 'asc');
                break;
            case "populair":
                $this->db->order_by('bekeken', 'desc');
                break;
            case "nieuw":
                $this->db->order_by('id', 'desc');
                break;
            case "naam":
                $this->db->order_by('naam', 'asc');
                break;
            case "korting":
                $this->db->order_by('korting', 'desc');
                $this->db->order_by('naam', 'asc');
                break;
            default:
                $this->db->order_by('id', 'desc');
                break;
        }
        //$this->db->where('archief =', 0);

        $where = '(naam LIKE "%' . $input . '%" OR barcode LIKE "' . $input . '")';
        $this->db->where($where);
        $this->db->where('archief =', 0);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');
        $this->load->model('artikelfoto_model');

        $counter = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);
            $artikel->fotos = $this->artikelfoto_model->getAllByArtikelID($artikel->id);
            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                
            } else {
                // geen maten meer beschikbaar, dus niet tonen
                unset($artikels[$counter]);
            }
            $counter++;
        }

        return $artikels;
    }

    function getAllAmount($amount) {
        $this->db->order_by('naam', 'asc');
        $this->db->where('archief =', 0);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        $artikelsmetvoorraad = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);
            $artikel->fotos = $this->artikelfoto_model->getAllByArtikelID($artikel->id);
            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                
            } else {
                // geen maten meer beschikbaar, dus niet tonen
                unset($artikels[$counter]);
            }
            $counter++;
        }

        // nu zijn er alleen artikels met voorraad
        if ($amount > count($artikels)) {
            $amount = count($artikels);
        }
        $sliced_artikels = array_slice($artikels, 0, $amount);

        return $sliced_artikels;
    }

    function getAllAmountAndSort($amount, $sort, $lastamount) {
        switch ($sort) {
            case "prijs":
                $this->db->order_by('prijs', 'asc');
                break;
            case "populair":
                $this->db->order_by('bekeken', 'desc');
                break;
            case "nieuw":
                $this->db->order_by('id', 'desc');
                break;
            default:
                $this->db->order_by('id', 'desc');
                ;
        }
        $this->db->where('archief =', 0);

        //$query = $this->db->get('artikel');
        // UPDATED CODE --> Only get last products and add them to bottom of div instead of loading everything
        // This is why you pay attention in SQL class
        $this->db->select('a.*');
        $this->db->distinct();
        $this->db->from('artikel a');
        $this->db->join('artikelmaat am', 'a.id = am.artikelId');
        $this->db->where('am.voorraad > 0 AND a.archief <> 1');
        $this->db->limit(($amount - $lastamount), $lastamount);
        $query = $this->db->get();

        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelfoto_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        $artikelsmetvoorraad = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);
            $artikel->fotos = $this->artikelfoto_model->getAllByArtikelID($artikel->id);
            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                
            } else {
                // geen maten meer beschikbaar, dus niet tonen
                //unset($artikels[$counter]);
            }
            $counter++;
        }

        // nu zijn er alleen artikels met voorraad
        /* if ($amount > count($artikels)) {
          $amount = count($artikels);
          }
          $sliced_artikels = array_slice($artikels, 0, $amount);

          return $sliced_artikels; */
        return $artikels;
    }

    function getAllByCategorieId($id) {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief =', 0);
        $this->db->where('categorieId', $id);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);
            
            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                
            } else {
                // geen maten meer beschikbaar, dus niet tonen
                unset($artikels[$counter]);
            }
            $counter++;
        }

        return $artikels;
    }

    // Voor te kijken of een categorie niet leeg is, dan niet laten zien natuurlijk.
    function getAmountAvailableByCategorieId($id) {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief =', 0);
        $this->db->where('categorieId', $id);

        $query = $this->db->get('artikel');
        $artikels = $query->result();
        $this->load->model('artikelMaat_model');

        $amount = 0;
        foreach ($artikels as $artikel) {
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);

            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                $amount += count($artikel->artikelMaten);
            } else {
                // geen maten meer beschikbaar, dus niet tonen
            }
        }

        return $amount;
    }

    function getAllAdmin() {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief =', 0);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $counter++;
        }

        return $artikels;
    }

    function getAllByInputAdmin($input) {
        $this->db->order_by('id', 'desc');
        $this->db->where('archief =', 0);

        $where = '(naam LIKE "%' . $input . '%" OR barcode LIKE "' . $input . '")';
        $this->db->where($where);

        $query = $this->db->get('artikel');
        $artikels = $query->result();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $counter = 0;
        foreach ($artikels as $artikel) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $counter++;
        }

        return $artikels;
    }

    function get($id) {
        $this->db->where('id', $id);
        $this->db->where('archief =', 0);
        $query = $this->db->get('artikel');

        $artikel = $query->row();
        if ($artikel != null) {
            $artikel->bekeken = $artikel->bekeken + 1;
            $this->artikel_model->update($artikel);

            $this->load->model('categorie_model');
            $this->load->model('artikelMaat_model');
            $this->load->model('artikelfoto_model');

            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            if ($artikel->categorie->hoofdcategorieId != null) {
                // is subcategorie
                $artikel->categorie->hoofdcategorie = $this->categorie_model->get($artikel->categorie->hoofdcategorieId);
            } else {
                $artikel->categorie->hoofdcategorie = null;
            }

            $artikel->fotos = $this->artikelfoto_model->getAllByArtikelID($artikel->id);

            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id); // dees kan hoofdletter gevoelig zijn woops?
        }
        return $artikel;
    }

    function getByBarcode($barcode) {
        $this->db->where('barcode', $barcode);
        $query = $this->db->get('artikel');

        $artikel = $query->row();
        $this->load->model('artikelmaat_model');

        $artikel->artikelMaten = $this->artikelmaat_model->getAllWithVoorraadByArtikelId($artikel->id);

        return $artikel;
    }

    function getForUpdate($id) {
        $this->db->where('id', $id);
        $this->db->where('archief =', 0);
        $query = $this->db->get('artikel');

        $artikel = $query->row();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');
        $this->load->model('artikelfoto_model');

        $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
        if ($artikel->categorie->hoofdcategorieId != null) {
            // is subcategorie
            $artikel->categorie->hoofdcategorie = $this->categorie_model->get($artikel->categorie->hoofdcategorieId);
        } else {
            $artikel->categorie->hoofdcategorie = null;
        }

        $artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
        //$artikel->artikelMaten = $this->artikelMaat_model->getAllWithVoorraadByArtikelId($artikel->id);
        // EXTRA FOTOS OPVRAGEN 8/02
        $artikel->extraFotos = $this->artikelfoto_model->getAllByArtikelID($artikel->id);

        return $artikel;
    }

    function getAdmin($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('artikel');

        $artikel = $query->row();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');
        $this->load->model('artikelfoto_model');

        $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
        if ($artikel->categorie->hoofdcategorieId != null) {
            // is subcategorie
            $artikel->categorie->hoofdcategorie = $this->categorie_model->get($artikel->categorie->hoofdcategorieId);
        } else {
            $artikel->categorie->hoofdcategorie = null;
        }

        //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
        $artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);

        // EXTRA FOTOS OPVRAGEN 8/02
        $artikel->extraFotos = $this->artikelfoto_model->getAllByArtikelID($artikel->id);

        return $artikel;
    }

    // really anders werken sessies niet meer (te veel data in de gewone get)
    function getSolo($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('artikel');

        $artikel = $query->row();

        return $artikel;
    }

    function getLastArtikel() {
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get('artikel');

        $artikel = $query->row();

        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        if (!($artikel == null)) {
            $artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            $artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($artikel->id);
        }
        return $artikel;
    }

    function getLastWithLowStock($amount = 4) {
        $this->load->model('categorie_model');
        $this->load->model('artikelMaat_model');

        $artikelsMatenWithLowStock = $this->artikelMaat_model->getLastWithLowStock($amount);
        $artikels = array();

        $counter = 0;
        foreach ($artikelsMatenWithLowStock as $a) {
            $artikel;
            $artikel = $this->get($a->artikelId);
            //$artikel->categorie = $this->categorie_model->get($artikel->categorieId);
            //$artikel->artikelMaten = $this->artikelMaat_model->getAllByArtikelId($a->artikelId);
            // maat beschikbaar
            if (count($artikel->artikelMaten) != 0) {
                array_push($artikels, $artikel);
            } else {
                // geen maten meer beschikbaar, dus niet tonen
            }
            $counter++;
        }
        return $artikels;
    }

    function getMostViewed($amount) {
        $this->db->order_by('bekeken', 'desc');
        $this->db->where('archief', 0);
        $this->db->limit($amount);
        $query = $this->db->get('artikel');

        $artikels = $query->result();
        return $artikels;
    }

    function insert($artikel) {
        $this->db->insert('artikel', $artikel);
        return $this->db->insert_id();
    }

    function update($artikel) {
        $this->db->where('id', $artikel->id);
        $this->db->update('artikel', $artikel);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('artikel');
    }

    function getProductenInKarretje($karretje) {
        $artikels = array();
        foreach ($karretje as $id => $aantal) {
            $artikels[$id] = $this->get($id);
        }
        return $artikels;
    }

    function setKortingForAll($korting) {
        $query = $this->db->get('artikel');
        $artikels = $query->result();

        foreach ($artikels as $artikel) {
            $artikel->korting = $korting;
            $this->db->where('id', $artikel->id);
            $this->db->update('artikel', $artikel);
        }
    }

    /* get data needed to show stockchange => artikelId, maatId, artikelmaatid */ 
    function getSingleArtikelMetMaatAndStock($stockchange) {
        $artikelId = $stockchange->artikelId;
        $maatId = $stockchange->maatId;
        $artikelMaatId = $stockchange->artikelMaatId;
        
        $this->load->model('artikelmaat_model');
        $this->load->model('maat_model');
        
        $artikel = $this->getSolo($artikelId);        
        $artikel->maat = $this->maat_model->get($maatId);   
        $artikel->artikelmaat = $this->artikelmaat_model->get($artikelMaatId);

        return $artikel;
    }

}

?>