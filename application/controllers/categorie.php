<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categorie extends CI_Controller {

    public function getsubcategorie() {
        // GETS
        $categorieNaam = $this->input->get('categorieNaam');

        $this->load->model('categorie_model');
        $categorie = $this->categorie_model->getByName($categorieNaam);
        if ($categorie != NULL) {
            $subcategorien = $this->categorie_model->getAllSubCategorien($categorie->id);
            $data['subcategorien'] = $subcategorien;
        } else {
            $data['subcategorien'] = null;
        }


        $this->load->view('subcategorien', $data);
    }
    
    public function getsubcategorieadmin() {
        // GETS
        $categorieNaam = $this->input->get('categorieNaam');

        $this->load->model('categorie_model');
        $categorie = $this->categorie_model->getByName($categorieNaam);
        if ($categorie != NULL) {
            $subcategorien = $this->categorie_model->getAllSubCategorienAdmin($categorie->id);
            $data['subcategorien'] = $subcategorien;
        } else {
            $data['subcategorien'] = null;
        }


        $this->load->view('subcategorien', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
