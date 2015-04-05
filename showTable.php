<?php require_once("include/functions.php"); ?>
<?php require_once("include/db_connection.php"); ?>

<?php
if(isset($_REQUEST["geneNames"])) {
	$geneString=$_REQUEST["geneNames"];
}
else {
	die("No genes selected");
}

if(isset($_REQUEST["samples"])) {
	$samples=unserialize(base64_decode($_REQUEST["samples"]));
}

$hasWildCard=False;
$genes=explode(',',$geneString);
$geneArr=array();
$wildGeneArr=array();
foreach($genes as &$gene) {
	$gene=trim($gene);
	if(strlen($gene)>0) {
		if(strpos($gene,'*')) {
			$gene=str_replace("*","%",$gene);
			$wildGeneArr[]=$gene;
		}
		else {
			$geneArr[]=$gene;
		}
	}
}

$sampleArr=array();
if(count($samples)==1) {
	$sampleArr[0]=$samples;
}
else {
	foreach($samples as &$sample) {
		$sampleArr[]=$sample;
	}
}
unset($gene);
$dataList=queryTable($sampleArr,$geneArr,$wildGeneArr);

$typeAll=$dataList['cellType'];
$sampleAll=$dataList['sampleID'];
$geneAll=$dataList['gene'];
$geneLevelAll=$dataList['geneLevel'];
$ntot=count($geneLevelAll);

if(count($samples)>1) {
$uniqueSamples=array_unique($samples);
$nsample=count($uniqueSamples);
}
else {
  $nsample=1;
}


if(!empty($geneAll)) {
	$ngene=$ntot/$nsample;
}
else {
	$ngene=0;
}

$header1="<tr><th></th>";
$header2="<tr><td align=\"left\">Gene Name</td>";

for($i=0;$i<$nsample;$i++) {
	$header1 .= "<th align=\"left\">".$sampleAll[$i*$ngene]."</th>";
	$header2 .= "<td align=\"left\">".$typeAll[$i*$ngene]."</td>";
}
$header1 .= "</tr>";
$header2 .= "</tr>";

$rows="";
for($i=0;$i<$ngene;$i++) {
	$gene=$geneAll[$i];
	$rows .= "<tr>";
	$rows .= "<td>$gene</td>";
	for($j=0;$j<$nsample;$j++) {
		$num=number_format($geneLevelAll[$j*$ngene+$i],3);
		$rows .= "<td>".$num."</td>";
	}
$rows .= "</tr>";
}

?>


<html>
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Homepage">
  <meta name="author" content="Jincheng Wu">
  <title>
  RPKM/FPKM RNA-Seq results
</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="include/updateSelect.js"></script>
  <script type="text/javascript" src="include/jquery-1.3.2.js" ></script>
  <script type="text/javascript" src="include/table2CSV.js" ></script>
      <!-- Le styles -->
    <link href="include/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 60px;
      }
    </style>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	</head>

<body>


<div class="container">
  <div class="well">
    <form action="getCSV.php" method ="get"> 
    <input type="hidden" name="csv_text" id="csv_text">
    <input type="submit" class="btn btn-success" value="Export CSV" onclick="getCSVData()">
    </form>

    <script>
    function getCSVData(){
     var csv_value=$('#genes').table2CSV({delivery:'value'});
     $("#csv_text").val(csv_value);
    }
    </script>
	<table id="genes" class="table table-bordered">
		<?php
			echo $header1;
			echo $header2;
			echo $rows;
		?>
	</table>
</div>
</div>

</body>
</html>