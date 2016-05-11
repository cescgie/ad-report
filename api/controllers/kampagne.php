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

   public function getDatum(){
      $groupby = "GROUP BY Datum";
      $clause = "WHERE Impressions != ''";
      $data["datas"] = $this->_model->selectClauseGroupByOrderBy("ad_report","Datum",$clause,$groupby,null);

      return print_r(json_encode($data["datas"]));
   }

   public function getFillrate(){
      if ($_GET["datum1"] && $_GET["datum2"]) {
        //getFillrate with range dates
        $datum1 = $_GET["datum1"];
        $datum2 = $_GET["datum2"];
        $clause = "Datum between date('$datum1') and date('$datum2')";
      }else{
        $datum = $_GET["datum"];
        $stunde = $_GET["stunde"];
        $clause1 = "Datum = '$datum' ";
        if ($stunde == "null") {
          $clause2 = "Stunde > -1";
        }else{
          $clause2 = "Stunde = '$stunde' ";
        }
        $clause = $clause1.' AND '.$clause2;
      }

      $select = "Kampagne,SUM(Impressions) as Impressions,SUM(AdCounts) as AdCounts";
      $groupby = "GROUP BY Kampagne";

      $data["datas"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause",$groupby,null);

      return print_r(json_encode($data["datas"]));
    }

    public function getOverallFilledImpression(){
       if ($_GET["datum1"] && $_GET["datum2"]) {
         //getOverallFilledImpression with range dates
         $datum1 = $_GET["datum1"];
         $datum2 = $_GET["datum2"];
         $clause = "Datum between date('$datum1') and date('$datum2')";
       }else{
         $datum = $_GET["datum"];
         $clause1 = "Datum = '$datum' ";
         $stunde = $_GET["stunde"];
         if ($stunde == "null") {
           $clause2 = "Stunde > -1";
         }else{
           $clause2 = "Stunde = '$stunde' ";
         }
         $clause = $clause1.' AND '.$clause2;
       }

       $select = "Kampagne as label,SUM(Impressions) as Impressions, SUM(AdCounts) as AdCounts";
       $groupby = "GROUP By Kampagne";

       $data["doghnutOFI"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause",$groupby,null);

       $array = [];
       foreach ($data["doghnutOFI"] as $key => $value) {
         $total_impressions = $value['Impressions'];
         $diff = (float)(($value['AdCounts']/$value['Impressions'])*100);
         $array[$key]['label'] = $value['label'];
         $array[$key]['formatted'] = round($diff).'%';
         $array[$key]['value'] = round($diff);
       }

       return print_r(json_encode($array));
     }

     public function getOverallFillrate(){
       if ($_GET["datum1"] && $_GET["datum2"]) {
         //getOverallFillrate with range dates
         $datum1 = $_GET["datum1"];
         $datum2 = $_GET["datum2"];
         $clause = "Datum between date('$datum1') and date('$datum2')";
       }else{
         $datum = $_GET["datum"];
         $clause1 = "Datum = '$datum' ";
         $stunde = $_GET["stunde"];
         if ($stunde == "null") {
           $clause2 = "Stunde > -1";
         }else{
           $clause2 = "Stunde = '$stunde' ";
         }
         $clause = $clause1.' AND '.$clause2;
       }

       $select = "Datum, SUM(Impressions) as Impressions ,SUM(AdCounts) as AdCounts";

       $data["doghnutOFRate"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause",null);

       return print_r(json_encode($data["doghnutOFRate"]));
     }

     public function getDetailGraph(){
       if ($_GET["datum1"] && $_GET["datum2"]) {
         //getDetailGraph with range dates
         $datum1 = $_GET["datum1"];
         $datum2 = $_GET["datum2"];
         $clause = "Datum between date('$datum1') and date('$datum2')";
         $groupby = "GROUP By Kampagne,Datum";
         $select = "Datum,Kampagne,SUM(Impressions) as Impressions, SUM(AdCounts) as AdCounts";

         $data["graphs"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause",$groupby,null);

         $array_tmp = [];
         foreach ($data["graphs"] as $key => $value) {
           $diff = (float)(($value['AdCounts']/$value['Impressions'])*100);
           $array_tmp[$value['Datum']]['x_achse_label'] = $value['Datum'];
           $array_tmp[$value['Datum']][$value['Kampagne']] = round($diff);
         }
         $array = [];
         $i = 0;
         foreach ($array_tmp as $key => $value) {
           $array[$i] = $value;
           $i++;
         }
       }else{
         $datum = $_GET["datum"];
         $clause1 = "Datum = '$datum' ";
         $select = "Datum, Kampagne, Stunde, Impressions, AdCounts";

         $data["graphs"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause1",null,null);

         $array = [];
         for ($i=0; $i < 24 ; $i++) {
           $array[] = array('x_achse'=>"$i");
         }

         foreach ($data["graphs"] as $key => $value) {
           $diff = (float)(($value['AdCounts']/$value['Impressions'])*100);
           if ($array[$value['Stunde']]['x_achse'] == $value['Stunde']) {
              $array[$value['Stunde']]['x_achse_label'] = (strlen($value['Stunde'])==1) ? '0'.$value['Stunde'].':00' : $value['Stunde'].':00';
              $array[$value['Stunde']][$value['Kampagne']] = round($diff);
           }
         }
       }
       return print_r(json_encode($array));
     }

     public function getDetailAverage(){
       if ($_GET["datum1"] && $_GET["datum2"]) {
         //getDetailAverage with range dates
         $datum1 = $_GET["datum1"];
         $datum2 = $_GET["datum2"];
         $clause = "Datum between date('$datum1') and date('$datum2')";
         $select = "Datum,SUM(Impressions) as Impressions, SUM(AdCounts) as AdCounts";
         $groupby = "GROUP By Datum";

         $data["detailsAverage"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause",$groupby,null);

         $array = [];
         foreach ($data["detailsAverage"] as $key => $value) {
           $array[$key]['x_achse_label'] = $value['Datum'];
           $array[$key]['AdCounts'] = $value['AdCounts'];
           $array[$key]['Impressions'] = $value['Impressions'];
           $array[$key]['Average'] = round(($value['AdCounts']/$value['Impressions'])*100);
         }
       }else{
         $datum = $_GET["datum"];
         $clause = "Datum = '$datum' ";
         $select = "Datum, Kampagne, Stunde, Impressions, AdCounts";

         $data["detailsAverage"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,"WHERE $clause",null,null);

         $array = [];
         for ($i=0; $i < 24 ; $i++) {
           $array[] = array('x_achse'=>"$i");
         }

         foreach ($data["detailsAverage"] as $key => $value) {
           if ($array[$value['Stunde']]['x_achse'] == $value['Stunde']) {
             $array_2[$key]['AdCounts'] = $value['AdCounts'];
             $array_2[$key]['Impressions'] = $value['Impressions'];
             $array[$value['Stunde']]['x_achse_label'] = (strlen($value['Stunde'])==1) ? '0'.$value['Stunde'].':00' : $value['Stunde'].':00';
             $array[$value['Stunde']]['AdCounts'] = $array_2[$key]['AdCounts'] + $array[$value['Stunde']]['AdCounts'];
             $array[$value['Stunde']]['Impressions'] = $array_2[$key]['Impressions'] + $array[$value['Stunde']]['Impressions'];
             $array[$value['Stunde']]['Average'] = round(($array[$value['Stunde']]['AdCounts']/$array[$value['Stunde']]['Impressions']*100));
           }
         }
       }
       return print_r(json_encode($array));
     }

     public function notif(){
       $current_date = date('Y-m-d');
       $gestern = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
       $vorgestern = date('Y-m-d', strtotime('-2 day', strtotime($current_date)));

       $clause1 = "WHERE Datum ='$gestern'";
       $select = "Kampagne,SUM(Impressions) as Impressions, SUM(AdCounts) as AdCounts";
       $groupby = "GROUP By Kampagne";

       $data["gestern"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,$clause1,$groupby,null);

       $clause2 = "WHERE Datum ='$vorgestern'";
       $data["vorgestern"] = $this->_model->selectClauseGroupByOrderBy("ad_report",$select,$clause2,$groupby,null);

       $array = [];
       for ($i=0; $i < count($data["gestern"]); $i++) {
         if ($data["gestern"][$i]['Kampagne']==$data["vorgestern"][$i]['Kampagne']) {
           $array[$i]["Kampagne"] = $data["gestern"][$i]['Kampagne'];
           //Impressions ad
           $array[$i]["Impressions_gestern"] = $data["gestern"][$i]['Impressions'];
           $array[$i]["Impressions_vorgestern"] = $data["vorgestern"][$i]['Impressions'];
           $array[$i]["Impressions_diff"] = $data["gestern"][$i]['Impressions'] - $data["vorgestern"][$i]['Impressions'];
           //AdCounts ad
           $array[$i]["AdCounts_gestern"] = $data["gestern"][$i]['AdCounts'];
           $array[$i]["AdCounts_vorgestern"] = $data["vorgestern"][$i]['AdCounts'];
           $array[$i]["AdCounts_diff"] = $data["gestern"][$i]['AdCounts'] - $data["vorgestern"][$i]['AdCounts'];
           //diff
           $array[$i]["prozent_gestern"] = round(($data["gestern"][$i]['AdCounts']/$data["gestern"][$i]['Impressions'])*100,2);
           $array[$i]["prozent_vorgestern"] = round(($data["vorgestern"][$i]['AdCounts']/$data["vorgestern"][$i]['Impressions'])*100,2);
           //state
           if($array[$i]["prozent_gestern"]==$array[$i]["prozent_vorgestern"]){
             $array[$i]["state"] = "stagnant";
           }elseif ($array[$i]["prozent_gestern"]>$array[$i]["prozent_vorgestern"]) {
             $array[$i]["state"] = "up";
           }else{
             $array[$i]["state"] = "down";
           }
         }
       }

        $to = "y.firmanda@netpoint-media.de";
        $subject = "Ad-Report";


        $message = '<html><head>
        <title>Ad-Report</title>
        <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        </style>
        </head>
        <body>
        <p>Vergleich zwischen '.$vorgestern.' und '.$gestern.'</p>
        <table border="1" style="width:100%">
        <tr>
        <td>Kampagne</td>
        <td>'.$vorgestern.'</td>
        <td>'.$gestern.'</td>
        <td>status</td>
        </tr>';
        foreach($array as $val){
             $message .=
             '<tr>
             <td>'.$val['Kampagne'].'</td>
             <td>'.$val['prozent_vorgestern'].'%</td>
             <td>'.$val['prozent_gestern'].'%</td>
             <td>'.$val['state'].'</td>
             </tr>';
        }
        $message .= '</table>';

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: <y.firmanda@netpoint-media.de>' . "\r\n";
        // $headers .= 'Cc: y.firmanda@netpoint-media.de' . "\r\n";

        mail($to,$subject,$message,$headers);
    }

}
?>
