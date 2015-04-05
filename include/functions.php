<?php
	function confirm_query($result_set) {
		if(!$result_set) {
			die("Database query failed.");
		}
	}

	function updateSelect($type,$expr,$sample) {
		$exprlist=query_experiment($type);
		$samplelist=query_sample($type,$expr);
		$result=array(
			"exprList" => $exprlist,
			"sampleList" => $samplelist
			);
		return $result;
	}



	function initilize_form() {

		$connection=$GLOBALS['connection'];

		$query = "SELECT DISTINCT(cell_type) ";
		$query .= "FROM cell_type ";
		$query .= "ORDER BY cell_type;";
		$types = mysqli_query($connection,$query);
		    //test if there was a query error
		confirm_query($types);

		$typelist = array();
		while($row=mysqli_fetch_array($types)) {
		    	//output data from each row
		 $typelist[]=$row[0];
		}


		$type="None";
		if(count($typelist)>0) {
		 $type=$typelist[0];
		}
		$exprlist=query_experiment($type);


		$expr="None";
		if(count($exprlist)>0) {
		 $expr=$exprlist[0];
		}
		$samplelist=query_sample($type,$expr);

		$result=array(
			"type" => $typelist,
			"expr" => $exprlist,
			"sample" => $samplelist,
			"selectedTypeIdx" => 0,
			"selectedExprIdx" => 0,
			"selectedSampleIdx" => 0,  
			);
		return $result;
	}

	function makeForm($data,$idx) {
		$typelist=$data['type'];
		$exprlist=$data['expr'];
		$idlist=$data['sample'];
	    $tid="tl".$idx;
	    $eid="el".$idx;
	    $sid="sl".$idx;
	    $output = "<tr>";
	    $output .= "<td><select name=\"typeList$idx\" id=\"$tid\" onchange=\"updateType(this);\">";
        $output .= list_options($typelist,$data['selectedTypeIdx']);
        $output .= "</select></td>";
		$output .= "<td><select name=\"exprList$idx\" id=\"$eid\" onchange=\"updateExpr(this);\">";
		$output .= list_options($exprlist,$data['selectedExprIdx']);
		$output .= "</select></td>";
		$output .= "<td><select name=\"sampleList$idx\" id=\"$sid\" onchange=\"this.form.submit();\">";
		$output .= list_options($idlist,$data['selectedSampleIdx']);
		$output .= "</select></td></tr>";
		return $output;
	}


	function list_options($list,$selected) {

		$n=count($list);
		$output="";
		for($i=0; $i<$n; $i++) {
			$name=$list[$i];
			if($selected!=$i) {
				$output .= "<option value=\"$name\">$name</option>";
			}
			else {
				$output .= "<option value=\"$name\" selected>$name</option>";
			}
		}
		return $output;
	}

    //input is the cell type
    //query to find out experiment sets available for this cell type
    //return the list of experiment sets in terms of GEO_ID
	function query_experiment($type) {
		$connection=$GLOBALS['connection'];

		// perform database query
		$query = "SELECT DISTINCT(GEO_num) ";
		$query .= "FROM cell_type ";
		$query .= "WHERE cell_type=\"$type\" ";
		$query .= "ORDER BY GEO_num;";
		$exprs = mysqli_query($connection,$query);


		//test if there was a query error
		confirm_query($exprs);

		$exprlist = array();
		while($row=mysqli_fetch_array($exprs)) {
		    	//output data from each row
		 $exprlist[]=$row[0];
		}
		return $exprlist;

	}

    //input is the cell type and experiment set
    //query to find out data sets available for this cell type and experiment
    //return the list of data id (GSM_ID)
	function query_sample($type, $expr) {
		$connection=$GLOBALS['connection'];

		// perform database query
		$query = "SELECT DISTINCT(ID) ";
		$query .= "FROM cell_type ";
		$query .= "WHERE GEO_num=\"$expr\" ";
		$query .= "AND cell_type=\"$type\" ";
		$query .= "ORDER BY ID;";
		$exprs = mysqli_query($connection,$query);
		//test if there was a query error
		confirm_query($exprs);

		$samplelist = array();
		while($row=mysqli_fetch_array($exprs)) {
		    	//output data from each row
		 $samplelist[]=$row[0];
		}
		return $samplelist;

	}

	function queryTable($samples, $genes, $wildGenes) {
		$connection=$GLOBALS['connection'];
		// perform database query  
		if(count($samples)==0||(count($genes)==0&&count($wildGenes)==0)) {
			$keys=array("cellType","sampleID","gene","geneLevel");
			$result=array_fill_keys($keys,"");
			return $result;
		}

		$query = "SELECT cell_type.Cell_type, cell_type.id, GeneID, SUM(gene_level) ";
		$query .= "FROM gene_data.cell_type, gene_data.gene_level ";
		$query .= "WHERE cell_type.ID = gene_level.id ";

		$sample=$samples[0];
		$query .= "AND (cell_type.ID='$sample' ";
		for($i=1;$i<count($samples);$i++) {
			$sample=$samples[$i];
			$query .= "OR cell_type.ID='$sample' ";
		}
		$query .= ") AND ";

		$geneQuery1="";
        if(count($genes)>0) {
			$geneQuery1 = "gene_level.GeneID IN ";
			$geneQuery1 .= "( ";
			for($i=0;$i<count($genes);$i++) {
				$gene=$genes[$i];
				if($i<count($genes)-1) {
					$geneQuery1 .= "'$gene', ";
				}
				else {
					$geneQuery1 .= "'$gene' ";
				}
			}
		    $geneQuery1 .=") ";
		}
		$geneQuery2="";
		if(count($wildGenes)>0) {
			$wildGene=$wildGenes[0];
			$geneQuery2 .= "( ";
			$geneQuery2 .= "gene_level.GeneID LIKE '$wildGene' ";
			for($i=1;$i<count($wildGenes);$i++) {
				$wildGene=$wildGenes[$i];
				$geneQuery2 .= "OR gene_level.GeneID LIKE '$wildGene' ";
			}
			$geneQuery2 .=") ";
		}
		if(count($genes)>0&&count($wildGenes)>0) {
			$geneQuery= "( ".$geneQuery1. " OR ". $geneQuery2." )";
		}
		else {
			$geneQuery=$geneQuery1.$geneQuery2;
		}

        $query .=$geneQuery;
        $query .= "GROUP BY cell_type.id, GeneID ";
        $query .="ORDER BY cell_type.id,geneID;";

		$rawResult = mysqli_query($connection,$query);
		confirm_query($rawResult);

		$type=array();
		$sampleID=array();
		$geneID=array();
		$geneLevel=array();
		while($row=mysqli_fetch_array($rawResult)) {
		    	//output data from each row
			$type[]=$row[0];
			$sampleID[]=$row[1];
			$geneID[]=$row[2];
			$geneLevel[]=$row[3];
		}
		$result=array(
				"cellType" => $type,
				"sampleID" => $sampleID,
				"gene" => $geneID,
				"geneLevel" => $geneLevel
			);

		return $result;
	
	}




	function findIdx($value,$array) {
		while ($curr = current($array)) {
		    if ($curr == $value) {
		        return key($array);
		    }
		    next($array);
		}
	}
?>