<?php

//item_fetch.php

include('phpfunc/database_connection.php');
include('phpfunc/function.php');

//Display any type of errors;
error_reporting(E_ALL); 
ini_set('display_errors', 1); 

$query='';
$output = array();
//Preparing Query
$query .= "
	SELECT item.*, i_prodline.prodline_id, i_prodline.prodline_cod, i_prodline.prodline_desc
	FROM item 
	INNER JOIN i_prodline ON item.item_prodline = i_prodline.prodline_cod  
";

if(isset($_POST["search"]["value"]) && $_POST["search"]["value"]!="")//Just in case we have a search
{
	$query .= 'WHERE i_prodline.prodline_cod LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR i_prodline.prodline_desc LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR item.item_code LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR item.item_desc LIKE "%'.$_POST["search"]["value"].'%" ';	
}

if(isset($_POST["columns"][1]["search"]["value"]) && $_POST["columns"][1]["search"]["value"] != "")//Just in case we have adavanced search
{
	$query .= 'WHERE item.item_code LIKE "'.$_POST["columns"][1]["search"]["value"].'%" ';
}

if(isset($_POST["columns"][2]["search"]["value"]) && $_POST["columns"][2]["search"]["value"] != "")//Just in case we have adavanced search
{
	$query .= 'WHERE item.item_desc LIKE "%'.$_POST["columns"][2]["search"]["value"].'%" ';
}

if(isset($_POST["columns"][3]["search"]["value"]) && $_POST["columns"][3]["search"]["value"] != "")//Just in case we have adavanced search
{
	$query .= 'WHERE item.item_prodline LIKE "'.$_POST["columns"][3]["search"]["value"].'%" ';
}

if(isset($_POST['order']))//Sort the search 
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY item.item_code ASC ';
}

if(isset($_POST['length'])){ //Set the lenght for page
	if ($_POST['length'] != -1)
	{
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}
}

$statement = $connect->prepare($query);//Prepare query
$statement->execute();//Execute query
$result = $statement->fetchAll();//Get results
$data = array();//Initialize the array
$filtered_rows = $statement->rowCount();//set variable with total of records
$dtrec = null;
foreach($result as $row)
{
	if (!$row['item_datareceipt']){
		$dtrec = "";		
	}else{
		$dtrec = date("m/d/Y",strtotime($row['item_datareceipt']));		
	}
	$status = '';
	if($row['item_inactiveitem'] == 'N')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}	
	$sub_array = array();	//Set the array to fill with datafields
	$sub_array["DT_RowId"] = $row['item_id'];
	$sub_array[] = "";
	$sub_array[] = $row['item_code'];
	$sub_array[] = $row['item_desc'];
	$sub_array[] = $row['prodline_cod'];
	$sub_array[] = "$ ".$row['item_standardcost'];
	$sub_array[] = "$ ".$row['item_standardprice'];
	$sub_array[] = $dtrec;
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="view" id="'.$row["item_id"].'" class="btn btn-info btn-xs view">View</button>';	
	$sub_array[] = '<button type="button" name="update" id="'.$row["item_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["item_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["item_inactiveitem"].'">Delete</button>';	
	$data[] = $sub_array;
}

$output = array(
	"draw"    			=> 	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, 'SELECT * FROM item'),	
	"data"    			=> 	$data
);
echo json_encode($output);
?>