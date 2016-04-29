<!doctype html>
<html>
<head>
   <meta charset="utf-8">
   <title><?= $data['title'] . ' - ' . SITETITLE ?></title>
   <link rel="stylesheet" href="<?= URL::STYLES('bootstrap.min') ?>">
   <link rel="stylesheet" href="<?= URL::STYLES('style') ?>">
   <link rel="stylesheet" href="<?= URL::STYLES('jquery.circliful') ?>">
   <link rel="stylesheet" href="<?= URL::STYLES('moris') ?>">
   <style>body{margin:0;font-family:'Work Sans',sans-serif;font-weight:400;color:#2a3440}td{vertical-align:top}.chartboxsmall{padding:5px;margin:2px;-webkit-border-radius:2px;-moz-border-radius:2px;border-radius:2px;box-shadow:0 4px 6px 0 rgba(0,0,0,.2) , 0 6px 20px 0 rgba(0,0,0,.19);text-align:center}.chartboxbig{padding:5px;margin:2px;-webkit-border-radius:2px;-moz-border-radius:2px;border-radius:2px;box-shadow:0 4px 6px 0 rgba(0,0,0,.2) , 0 6px 20px 0 rgba(0,0,0,.19);text-align:center}h2{font-weight:600!important;color:#2a3440}#head{width:100%;padding:5px 5px 5px 20px}#wrapper{width:100%;padding:5px 5px 40px 20px;margin-bottom:30px}.linecharts{width:625px}</style>
   <script type="text/javascript" src="<?= URL::SCRIPTS('jquery.min') ?>"></script>
   <script type="text/javascript" src="<?= URL::SCRIPTS('jquery.circliful.min') ?>"></script>
</head>
<body>

   <div class="container">
