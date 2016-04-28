<?php

class Kampagne_Model extends Model {

   public function __construct(){
      parent::__construct();
   }

   /**
   * Show last 20 items in database
   */
   public function all($table) {
     	return $this->_db->select("SELECT * FROM $table ORDER BY Datum");
   }

   public function insert($table,$data){
   		return $this->_db->insert($table,$data);
   }

   public function check($table,$kampagne,$datum,$stunde){
 		return $this->_db->select("SELECT count(*) as count FROM $table WHERE Datum = '$datum' AND Kampagne = '$kampagne' AND Stunde ='$stunde'");
   }
   public function getData($table,$datum){
   		return $this->_db->select("SELECT id,data FROM $table WHERE datum='$datum'");
   }

   public function updateData($table,$data,$datum){
   		return $this->_db->update($table,$data,"datum='$datum'");
   }

   public function selectOneClause($table,$clause){
   	    return $this->_db->select("SELECT * FROM $table WHERE $clause ORDER BY Datum");
   }

}