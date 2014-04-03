<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*  File:  Logout Controller
 *  Description: Handles logout requests
 *
 *  Written by: Patrick Burns
 *  Copyright: COPYRIGHT (C) 2010 BURNSFORCE.COM. ALL RIGHTS RESERVED
 */

class Logout extends CI_Controller {

    public function index() {
        
        $this->session->sess_destroy();
        
        //send the user back to the login page
        redirect('/', 'refresh');
    }
}
?>
