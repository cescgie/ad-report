<?php

class Welcome extends Controller {

   public function __construct() {
      parent::__construct();
   }

   public function index() {
      $data['title'] = 'Home';

      $this->_view->render('header', $data);
      $this->_view->render('welcome', $data);
      $this->_view->render('footer');
   }

}