<?php

//prodline_fetch.php

include('phpfunc/database_connection.php');
include('phpfunc/function.php');

//Display any type of errors;
error_reporting(E_ALL); 
ini_set('display_errors', 1); 

$query='';
$output = array();
//Preparing Query
$query .= "
	SELECT *
	FROM i_prodline 	
";

if(isset($_POST["search"]["value"]) && $_POST["search"]["value"]!="")//Just in case we have a search
{
	$query .= 'WHERE prodline_cod LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR prodline_desc LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR pt_pricecode LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST["columns"][1]["search"]["value"]) && $_POST["columns"][1]["search"]["value"] != "")//Just in case we have adavanced search
{
	$query .= 'WHERE prodline_cod LIKE "'.$_POST["columns"][1]["search"]["value"].'%" ';
}

if(isset($_POST["columns"][2]["search"]["value"]) && $_POST["columns"][2]["search"]["value"] != "")//Just in case we have adavanced search
{
	$query .= 'WHERE prodline_desc LIKE "%'.$_POST["columns"][2]["search"]["value"].'%" ';
}

if(isset($_POST["columns"][3]["search"]["value"]) && $_POST["columns"][3]["search"]["value"] != "")//Just in case we have adavanced search
{
	$query .= 'WHERE pt_pricecode LIKE "'.$_POST["columns"][3]["search"]["value"].'%" ';
}

if(isset($_POST['order']))//Sort the search 
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY prodline_cod ASC ';
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
	$sub_array = array();	//Set the array to fill with datafields
	$sub_array["DT_RowId"] = $row['prodline_id'];
	$sub_array[] = "";
	$sub_array[] = $row['prodline_cod'];
	$sub_array[] = $row['prodline_desc'];
	$sub_array[] = $row['pt_pricecode'];
	$sub_array[] = '<button type="button" name="view" id="'.$row["prodline_id"].'" class="btn btn-info btn-xs view">View</button>';	
	$sub_array[] = '<button type="button" name="update" id="'.$row["prodline_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["prodline_id"].'" class="btn btn-danger btn-xs delete" data-status="">Delete</button>';	
	$data[] = $sub_array;
}

$output = array(
	"draw"    			=> 	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, 'SELECT * FROM i_prodline'),	
	"data"    			=> 	$data
);
echo json_encode($output);
?>