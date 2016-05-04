<?php

class Kampagne extends Controller {

   public function __construct() {
      parent::__construct();
   }

   public function index() {
      $data['title'] = 'Kampagne';

      $this->_view->render('header', $data);
      $this->_view->render('footer', $data);
   }

   public function update(){
   	  //  Include PHPExcel_IOFactory
      require_once "static/PHPExcel/Classes/PHPExcel.php";
      require_once "static/PHPExcel/Classes/PHPExcel/IOFactory.php";

      $data["Kampagne"] = '12250_2_SpotXchange_Bewegtbild_Content-Split_31.12.2016';

      $inputFileName = getcwd().'/files/12250_2_SpotXchange_Bewegtbild_Content-Split_31.12.2016.xls';

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

    public function getDatum(){
      $groupby = "GROUP BY Datum";
      $data["datas"] = $this->_model->selectClauseGroupByOrderBy("ad_report","Datum",null,$groupby,null);

      return print_r(json_encode($data["datas"]));
    }

    public function getFillrate(){
      $datum = $_GET["datum"];
      $stunde = $_GET["stunde"];
      $clause1 = "Datum = '$datum' ";
      if ($stunde == "null") {
        $select = "Datum,Kampagne,SUM(Impressions) as Impressions,SUM(AdCounts) as AdCounts";
        $clause2 = "Stunde != '' ";
        $groupby = "GROUP BY Datum,Kampagne";
      }else{
        $select = "*";
        $clause2 = "Stunde = '$stunde' ";
      }

      $data["datas"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause1 AND $clause2",$groupby,null);

      return print_r(json_encode($data["datas"]));
    }

    public function getDetailGraph(){
       $datum = $_GET["datum"];
       $clause1 = "Datum = '$datum' ";

       $data["graphs"] = $this->_model->selectClauseGroupByOrderBy("ad_report","Datum, Kampagne, Stunde, Impressions, AdCounts","WHERE $clause1",null,null);
       $array = [];
       for ($i=0; $i < 24 ; $i++) {
         $array[] = array('hour'=>"$i");
       }

       $name = null;
       foreach ($data["graphs"] as $key => $value) {
         $diff = (int)(($value['AdCounts']/$value['Impressions'])*100+.5);
         if ($array[$value['Stunde']]['hour'] == $value['Stunde']) {
            $array[$value['Stunde']][$value['Kampagne']] = $diff;
         }
         $name = $value['Kampagne'];
       }
       return print_r(json_encode($array));
     }

     public function getOverallFilledImpression(){
       $datum = $_GET["datum"];
       $clause1 = "Datum = '$datum' ";
       $stunde = $_GET["stunde"];
       if ($stunde == "null") {
         $clause2 = "Stunde != '' ";
       }else{
         $clause2 = "Stunde = '$stunde' ";
       }

       $select = "Datum, Kampagne as label,SUM(Impressions) as Impressions, SUM(AdCounts) as AdCounts";
       $groupby = "GROUP By Datum,Kampagne";

       $data["doghnutOFI"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause1 AND $clause2",$groupby,null);

       $array = [];
       foreach ($data["doghnutOFI"] as $key => $value) {
         $total_impressions = $value['Impressions'];
         $diff = (int)(($value['AdCounts']/$value['Impressions'])*100+.5);
         $array[$key]['label'] = $value['label'];
         $array[$key]['formatted'] = $diff.'%';
         $array[$key]['value'] = $diff;
       }

       return print_r(json_encode($array));
     }

     public function getOverallFillrate(){
       $datum = $_GET["datum"];
       $clause1 = "Datum = '$datum' ";
       $stunde = $_GET["stunde"];
       if ($stunde == "null") {
         $clause2 = "Stunde != '' ";
       }else{
         $clause2 = "Stunde = '$stunde' ";
       }

       $select = "Datum, SUM(Impressions) as Impressions ,SUM(AdCounts) as AdCounts";

       $data["doghnutOFRate"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause1 AND $clause2",null);

       return print_r(json_encode($data["doghnutOFRate"]));
     }
}
?>
