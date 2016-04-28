<?php

class Sample_Model extends Model {

   public function __construct(){
      parent::__construct();
   }

   /**
   * Show last 20 items in database
   */
   /*public function all() {
      return $this->_db->select('SELECT * FROM sample ORDER BY id DESC LIMIT 0, 20');
   }*/

}