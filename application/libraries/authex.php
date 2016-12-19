<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authex {

    // +----------------------------------------------------------
    // | TV Shop
    // +----------------------------------------------------------
    // | Thomas More Kempen - 2 TI - 201x-201x
    // +----------------------------------------------------------
    // | Authex library
    // |
    // +----------------------------------------------------------
    // | Nelson Wells
    // | http://nelsonwells.net/2010/05/creating-a-simple-extensible-codeigniter-authentication-library/
    // | aangepast door K. Vangeel
    // +----------------------------------------------------------

    public function __construct()
    {
        $CI = & get_instance();
        
        $CI->load->model('account_model');
    }

    function loggedIn() 
    {
        // gebruiker is aangemeld als sessievariabele user_id bestaat
        $CI = & get_instance();
        if ($CI->session->userdata('user_id')) {
            return true;
        } else {
            return false;
        }
    }
    
    function getUserInfo() 
    {
        // geef user-object als gebruiker aangemeld is
        $CI = & get_instance();
        if (! $this->loggedIn()) {
            return null;
        } else {
            $id = $CI->session->userdata('user_id');
            return $CI->account_model->get($id);
        }
    }

    function login($email, $wachtwoord) 
    {
        // gebruiker aanmelden met opgegeven email en wachtwoord
        $CI = & get_instance();
        $user = $CI->account_model->getAccount($email, $wachtwoord);
        if ($user == null) {
            return false;
        } else {
            $CI->session->set_userdata('user_id', $user->id);
            return true;
        }
    }

    function logout() 
    {
        // uitloggen, dus sessievariabele wegdoen
        $CI = & get_instance();
        $CI->session->unset_userdata('user_id');
    }

    function register($account) 
    {
        // nieuwe gebruiker registreren als email nog niet bestaat
        $CI = & get_instance();
        if ($CI->account_model->emailVrij($account->emailadres)) {
            $id = $CI->account_model->insert($account);
            return $id;
        } else {
            return 0;
        }
    }
    
    function activate($id) 
    {
        // nieuwe gebruiker activeren
        $CI = & get_instance();
        $CI->account_model->activeer($id);
    }

}