<?php
require "Storage.php";
require 'PHPMailer/PHPMailerAutoload.php';
date_default_timezone_set('Europe/Berlin');

class Send{
  protected $_db;

  //Konstruktor
  public function __construct()
  {
    $this->_db = new Storage();
    $this->_mail = new PHPMailer();
  }

  public function send_email(){

    echo 'Sending email...';
    $current_date = date('Y-m-d');
    $gestern = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
    $vorgestern = date('Y-m-d', strtotime('-2 day', strtotime($current_date)));

    $clause1 = "WHERE Datum ='$gestern'";
    $select = "Kampagne,SUM(Impressions) as Impressions, SUM(AdCounts) as AdCounts";
    $groupby = "GROUP By Kampagne";
    $data["gestern"] = $this->_db->select("SELECT $select FROM ad_report $clause1 $groupby");

    $clause2 = "WHERE Datum ='$vorgestern'";
    $data["vorgestern"] = $this->_db->select("SELECT $select FROM ad_report $clause2 $groupby");

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
     <td>Status</td>
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


     $this->_mail->isSMTP();                                      // Set mailer to use SMTP
     $this->_mail->Host = 'dehamex02.configcenter.info';  // Specify main and backup SMTP servers
     $this->_mail->SMTPAuth = true;                               // Enable SMTP authentication
     $this->_mail->Username = 'y.firmanda@netpoint-media.de';                 // SMTP username
     $this->_mail->Password = 'Netpoint2015';                           // SMTP password
     $this->_mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
     $this->_mail->Port = 587;                                    // TCP port to connect to

     $this->_mail->SetFrom('y.firmanda@netpoint-media.de', 'Y.Firmanda');
     $this->_mail->AddAddress('j.cordero@netpoint-media.de', 'J.Cordero');
     $this->_mail->addCC('y.firmanda@netpoint-media.de');
     $this->_mail->Subject = 'Ad-Report';
     $this->_mail->isHTML(true);
     $this->_mail->MsgHTML( $message );

     if( ! $this->_mail->Send() ) {
      echo "Mailer Error: " . $this->_mail->ErrorInfo;
     }else {
      echo "Mail sent";
     }

  }
}

$obj = new Send;

$obj->send_email();

?>
