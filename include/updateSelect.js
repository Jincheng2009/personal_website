
  function updateType(item) {
    var xmlhttp;
    var selectedType=$("#"+item.id +" option:selected").text();
    var selectName=$("#"+item.id).attr('name');
    //the next select is Experiment selector
    //the next next select is Sample selector
    var exprBox=$("#"+item.id).closest("td").next("td").find("select").attr('id');
    var sampleBox=$("#"+exprBox).closest("td").next("td").find("select").attr('id');
    if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  } 
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      var js_array = xmlhttp.responseText.split("||");
      document.getElementById(exprBox).innerHTML=js_array[0];
      document.getElementById(sampleBox).innerHTML=js_array[1];

      item.form.submit();
    }  
  }
  xmlhttp.open("POST","include/updateSelect.php?select=change&type="+selectedType,true);
  xmlhttp.send();
}

function updateExpr(item) {
  var xmlhttp;
  var selectedExpr=$("#"+item.id +" option:selected").text();
  //the next select is the sample selector, needs to be updated
  var sampleBox=$("#"+item.id).closest("td").next("td").find("select").attr('id');
  //the previous select is the cell type selector, needs to provide information for SQL query
  var typeBox=$("#"+item.id).closest("td").prev("td").find("select").attr('id');
  var selectedType=$("#"+ typeBox +" option:selected").text();

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById(sampleBox).innerHTML=xmlhttp.responseText;
      item.form.submit();
    }
  }
  xmlhttp.open("POST","include/updateSelect.php?select=change&type="+selectedType+"&expr="+selectedExpr,true);
  xmlhttp.send();
}
