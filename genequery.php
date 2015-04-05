<?php require_once("include/functions.php"); ?>
<?php require_once("include/db_connection.php"); ?>

<?php
$default=initilize_form();
$defaultTypes=$default["type"];
$defaultExprs=$default["expr"];
$defaultSamples=$default["sample"];
$selectList[]=array();
  // perform database query
  // initialize the first row of data sample
  // $dataset stored all the selection that have been made
if(!isset($_POST['count'])) {
  $count=1;
  $selectList[0]=$default;
  $selectedTypeList=$defaultTypes[$default["selectedTypeIdx"]];
  $selectedExprList=$defaultExprs[$default["selectedExprIdx"]];
  $selectedSampleList=$defaultSamples[$default["selectedSampleIdx"]];
}
else {
  $count = $_POST['count'];
    //Restore the memory of selected items and corresponding lists
  $selectedTypeList=array();
  $selectedExprList=array();
  $selectedSampleList=array();
  for($i=0;$i<$count;$i++) {
      //selectedTypeList, selectedExprList, selectedSampleList records all the selected information, used to restore the
      //full lists for all the select boxes.
    $selectedTypeList[$i]=$_POST["typeList$i"];
    $selectedExprList[$i]=$_POST["exprList$i"];
    $selectedSampleList[$i]=$_POST["sampleList$i"];  

    $typeList=$defaultTypes;
    $exprsample=updateSelect($selectedTypeList[$i],$selectedExprList[$i],$selectedSampleList[$i]);
    $exprList=$exprsample["exprList"];
    $sampleList=$exprsample["sampleList"];
    $selectedTypeIdx=findIdx($selectedTypeList[$i],$typeList);
    $selectedExprIdx=findIdx($selectedExprList[$i],$exprList);
    $selectedSampleIdx=findIdx($selectedSampleList[$i],$sampleList);
    $data=array(
      "type" => $typeList,
      "expr" => $exprList,
      "sample" => $sampleList,
      "selectedTypeIdx" => $selectedTypeIdx,
      "selectedExprIdx" => $selectedExprIdx,
      "selectedSampleIdx" => $selectedSampleIdx,  
      );
    $selectList[$i]=$data;
  }

}
  //If form submitted is add button, add one more row
if(isset($_POST["btnadd"])) {
  $selectList[$count]=$default;
  $selectedTypeList[$count]=$defaultTypes[$default["selectedTypeIdx"]];
  $selectedExprList[$count]=$defaultExprs[$default["selectedExprIdx"]];
  $selectedSampleList[$count]=$defaultSamples[$default["selectedSampleIdx"]]; 
  $count = $count + 1;
}
  //If form submitted is remove button, remove the last row
else if(isset($_POST["btnremove"])) {
    // decrement the row counter
  if($count>1) {
    unset($selectedTypeList[$count-1]);
    unset($selectedExprList[$count-1]);
    unset($selectedSampleList[$count-1]);
    unset($selectList[$count-1]);
    $count = $count - 1;
  }
}


?>

<html>
<head>
  <meta charset="utf-8">
  <title>RNA-Seq Gene Expression</title>
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
        <a class="navbar-brand" href="index.html">Jincheng Wu</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="header-1">
        <ul class="nav navbar-nav"> 
          <li><a href="research.html">Publication</a></li>
          <li><a href="bioinformatics.html">Bioinformatics</a></li>
          <li><a href="software.html">Software</a></li>
          <li class="active"><a href="genequery.php">Database</a></li>
          <li><a href="equity.php">Equity</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div>
  </nav>

  <br>

  <div class="container">
    <h4 align="center">
      RNA-Seq RPKM/FPKM gene expression database
    </h4>
    <br>
    <p class="muted" align="right">
      Presented by <a href="http://engineering.tufts.edu/chbe/people/tzanakakis/">Dr. Tzanakakis lab </a><br>
      Department of Chemical and Biological Engineering <br>
      Tufts University
    </p>
    <form name="selectForm" id="form1" method="POST" >
      <table class="table table-striped">
        <tr>
          <th align="left">Cell Type</th>
          <th align="left">GEO ID</th>
          <th align="left">Sample</th>
        </tr>
        <?php 
      // make a form based on each selected values
        for($i=0; $i<$count; $i++) {
          $data=$selectList[$i];
          echo makeForm($data,$i);
        }
        ?> 

      </table>


  <!-- you only need one set of buttons and only one counter for this script
  because every button would do the same -->
  <input type="submit" class="btn btn-success" name="btnadd" id="btnadd" value="ADD">
  <input type="submit" class="btn btn-warning" name="btnremove" id="btnremove" value="REMOVE">
  <input type="hidden" name="count" value="<?php echo $count; ?>">
  <hr size="3" noshade style="color:#000000" align="left" >

</form>

<form name="querySub" action="showTable.php" id="form2" method="POST" target="_blank">
  <input type="hidden" name="samples" value="<?php echo urlencode(base64_encode(serialize($selectedSampleList))); ?>">
  <input type="text" value="ACTB, GAPDH, TP53" class="span2" placeholder="Gene Names" name="geneNames" id="gene1" style="width: 300px; height:30px;" align="center">
  <p>Gene names, separated by comma. Wildcard (*) can be used. </p>
  <hr size="3" noshade style="color:#000000" align="left" >
  <input type="submit" class="btn btn-primary" name="submitSelect"value="SUBMIT QUERY">
</form>

<br>

<div class="footer" id="footer">

  <!-- Start: TraceMyIP.org Code //-->
  <br>
  <div align="center">
    <script type="text/javascript" src="http://s2.tracemyip.org/tracker/lgUrl.php?stlVar2=1326&amp;rgtype=4684NR-IPIB&amp;pidnVar2=54519&amp;prtVar2=13&amp;scvVar2=12">
    </script>
    <noscript>
      <a title="how to trace people" href="http://www.tracemyip.org/" target="_blank">

        <img src="http://s2.tracemyip.org/tracker/1326/4684NR-IPIB/54519/13/12/ans/" alt="how to trace people" border="0">

      </a>
    </noscript>
  </div>
  <!-- End: TraceMyIP.org Code //-->

  <div>
    <p>Last update @ 03/2015</p>
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
