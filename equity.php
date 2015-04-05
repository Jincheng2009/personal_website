<?php 
include 'simple_html_dom.php';
?>

<?php
$html=file_get_html("http://finance.yahoo.com/q?s=jrjc");
$priceText = $html->find('#yfs_l84_jrjc',0)->plaintext;
$price = floatval($priceText);
$expense = 6.35;
$share=780;
$profit = $share * ($price - $expense) - 8.0;
$profitAT = $profit * 0.7;
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Bilin's Investment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Homepage">
  <meta name="author" content="Jincheng Wu">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="include/updateSelect.js"></script>
  <style type="text/css">
    body {
      padding-top: 70px;
      padding-bottom: 60px;
    }


  </style>
</head> 
<body>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">


      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">

        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">Bilin Zheng</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="header-1">
        <ul class="nav navbar-nav"> 
          <li><a href="research.html">Publication</a></li>
          <li><a href="bioinformatics.html">Bioinformatics</a></li>
          <li><a href="software.html">Software</a></li>
          <li><a href="genequery.php">Database</a></li>
          <li class="active"><a href="equity.php">Equity</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div>
  </nav>

<div class='container'>
<table class="table table-hover">
        <tr>
          <td align="left">Number of Shares</td>
          <td align="left">Current Profit ($) </td>
          <td align="left">Current Profit after Tax ($)</td>
        </tr>
        <tr>
        <td><?php echo $share?></td>
		<td><?php echo $profit?></td>
		<td><?php echo $profitAT?></td>
		</tr>
</table>
</div>

<div class='container'>
<div class="footer" id="footer">


  <div>
    <p>Last update @ 03/2015</p>
  </div>
</div>
</div>

</div><!-- /footer -->

        <!-- Le javascript
      ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </body>
    </html>