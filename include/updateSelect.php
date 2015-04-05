<?php require_once("functions.php"); ?>
<?php require_once("db_connection.php"); ?>

<?php
if(isset($_REQUEST["select"])) {
    $type=$_REQUEST["type"];
    if(!isset($_REQUEST["expr"])) {
		$exprlist=query_experiment($type);
		$expr="";
		if(count($exprlist)>0) {
		   $expr=$exprlist[0];
		}
		$idlist=query_sample($type,$expr);
		$l1=list_options($exprlist,0);
		$l2=list_options($idlist,0);
		$result = array(
			"exprlist" => $l1,
			"idlist" => $l2
			);
		$GLOBAL['selectList']=array();
		$result = implode("||", $result);
		echo $result;
    }
    elseif(isset($_REQUEST["expr"])) {
    	$expr=$_REQUEST["expr"];
    	$idlist=query_sample($type,$expr);
    	echo list_options($idlist,0);
    }
}
?>