<?php

class Kampagne extends Controller {

   public function __construct() {
      parent::__construct();
   }

   public function index() {
      $data['title'] = 'Kampagne';

      $datums = $this->_model->selectClauseGroupByOrderBy("kampagne","Datum",null,"GROUP BY Datum","ORDER BY Datum ASC");
      $stundes = $this->_model->selectClauseGroupByOrderBy("kampagne","Stunde",null,"GROUP BY Stunde","ORDER BY Stunde ASC");

      $data['Datum'] = $datums;
      $data['Stunde'] = $stundes;

      if(Session::get('Datum')){
        $datum = Session::get('Datum');
        $clause1 = "Datum = '$datum' ";
        $select = "*";
      }else{
        $clause1 = "Datum != '2016-04-20' ";
        Session::set('Datum','2016-04-20');
      }

      if(Session::get('Stunde')){
        $stunde = Session::get('Stunde');
        $stunde = str_replace("'","",$stunde);
        $clause2 = "Stunde = '$stunde' ";
        $select = "*";
      }else{
        $clause2 = "Stunde != '' ";
        $groupby = "GROUP BY Datum,Kampagne";
        $select = "Datum,Kampagne,SUM(Impressions) as Impressions,SUM(AdCounts) as AdCounts";
      }

      $data["datas"] = $this->_model->selectClauseGroupByOrderBy("kampagne",$select,"WHERE $clause1 AND $clause2",$groupby,null);

      $this->_view->render('header', $data);

      $this->_view->render('kampagne/index', $data);
      $this->_view->render('footer', $data);
   }

   public function getGraph(){

     if(Session::get('Datum')){
       $datum = Session::get('Datum');
       $clause1 = "Datum = '$datum' ";
       $select = "*";
     }else{
       $clause1 = "Datum != '2016-04-20' ";
       Session::set('Datum','2016-04-20');
     }

     $data["graphs"] = $this->_model->selectClauseGroupByOrderBy("kampagne","Datum, Kampagne, Stunde, Impressions, AdCounts","WHERE $clause1",null,null);
     $array = [];
     for ($i=0; $i < 24 ; $i++) {
       $array[] = array('y'=>"$i");
     }

     $name = null;
     foreach ($data["graphs"] as $key => $value) {
       if (strpos($value['Kampagne'], '12250') !== false) {
         $value['Kampagne'] = 'a';
       }elseif (strpos($value['Kampagne'], '12169') !== false) {
         $value['Kampagne'] = 'b';
       }else {
         $value['Kampagne'] = 'c';
       }
       $diff = (int)(($value['AdCounts']/$value['Impressions'])*100+.5);
       if ($array[$value['Stunde']]['y'] == $value['Stunde']) {
         $array[$value['Stunde']][$value['Kampagne']] = $diff;
       }
       $name = $value['Kampagne'];
     }
     return print_r(json_encode($array));
   }

   public function date($date){
   	  $data['title'] = 'Kampagne';
   	  $where = "Datum = '$date'";
      $select = $this->_model->selectOneClause("kampagne",$where);

      echo '<pre>';
      foreach ($select as $key => $value) {
      	print_r($value);
      }
      echo '</pre>';
   }

   public function update(){
   	  //  Include PHPExcel_IOFactory
      require_once "static/PHPExcel/Classes/PHPExcel.php";
      require_once "static/PHPExcel/Classes/PHPExcel/IOFactory.php";

      $data["Kampagne"] = '12228_2_NERO_In-Read_2016_31.12.2016';


      $inputFileName = getcwd().'/files/12228_2_NERO_In-Read_2016_31.12.2016.xls';

      //  Read your Excel workbook
      try {
          $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($inputFileName);
      } catch(Exception $e) {
          die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
      }

      //  Get worksheet dimensions
      $sheet = $objPHPExcel->getSheet(3);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();

      $cols = $sheet->rangeToArray('A1:' . $highestColumn . '1',
                                          NULL,
                                          TRUE,
                                          FALSE);
      $row_headers = NULL;
      foreach ($cols as $col) {
      	$row_headers[] = $col;
  	  }
      $rowDa = array();
      //  Loop through each row of the worksheet in turn
      $i=0;
      $datex = null;
      for ($row = 2; $row < $highestRow; $row++){
          //  Read a row of data into an array
          $rowData = $sheet->rangeToArray('A' . $row . ':D' . $row,
                                          NULL,
                                          TRUE,
                                          FALSE);

          $j = 0;
          foreach ($rowData[0] as $key => $value) {
          	$rowDa[$row_headers[0][$j]] = $value;
          	if ($row_headers[0][$j]=="Datum") {
          		$rowDa[$row_headers[0][$j]] = gmdate("Y-m-d",($value - 25569) * 86400);
          	}
            if ($row_headers[0][$j]=="Stunde") {
            	if (strlen($value)==1) {
            		$rowDa[$row_headers[0][$j]] = '0'.$value;
            	}else{
            		$rowDa[$row_headers[0][$j]] = $value;
            	}
          	}
          	$j++;
          }
          $data['Datum']=$rowDa['Datum'];
          $data['Stunde']=$rowDa['Stunde'];
          $data['Impressions']=$rowDa['Impressions'];
          $data['AdCounts']=$rowDa['AdCounts'];

          $check_exists = $this->_model->check("kampagne",$data['Kampagne'],$rowDa['Datum'],$rowDa['Stunde']);
          if($check_exists[0]['count']==0){
          	 $insert = $this->_model->insert("kampagne",$data);
          }
          print_r($check_exists);

          echo '<pre>';
          print_r($data);
          echo '</pre>';
       }
   }

   public function set_Datum(){
     $datum = $_GET['datum'];
     if(Session::get('Datum')){
       Session::clear('Datum');
     }
     Session::set('Datum',$datum);
     URL::STAYCURRENTPAGE();
  }

  public function remove_date(){
     if(Session::get('Datum')){
       Session::clear('Datum');
     }
     URL::STAYCURRENTPAGE();
  }

  public function set_Stunde(){
    $stunde = $_GET['stunde'];
    if(Session::get('Stunde')){
      Session::clear('Stunde');
    }
    Session::set('Stunde',$stunde);
    URL::STAYCURRENTPAGE();
  }

  public function remove_stunde(){
    if(Session::get('Stunde')){
      Session::clear('Stunde');
    }
    URL::STAYCURRENTPAGE();
  }


}
?>
