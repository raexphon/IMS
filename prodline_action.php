<?php

//product_action.php
include('phpfunc/database_connection.php');
include('phpfunc/function.php');

//Display any type of errors;
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
set_error_handler('my_error_handler'); //Call this custom error function to handle errors and show or not the message error

$query='';
$type='';
$output = "";//array();

if(isset($_POST['btn_action'])) //Check what operation I gotta do
{	
	//SHOW THE PLINE DETAILS IN MODE VIEW
	if ($_POST['btn_action']=='pline_view'){ //View Pline details
		$query="
			SELECT *
			FROM
				i_prodline			
			WHERE
				prodline_id = ".$_POST['prodline_id']."  
			ORDER BY prodline_id ASC			
		";	
			
		try {
			$statement = $connect->prepare($query);//Prepare query
			$statement->execute();//Execute query
			$result = $statement->fetchAll();//Get results
			$arrpline=array();
			$arrpcode=array();
			
			foreach($result as $row){				
				$arrpline=cvprodline($connect, $row['item_prodline'], "y");
				$arrpcode=cvprodline($connect, $row['item_pricecode'], "y");
				if (!is_null($row["item_datareceipt"])){
					$daterecp=cvdate($row["item_datareceipt"]);
				}else{
					$daterecp="<i>no data receipt</i>";
				}
				$output = '			
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:35%;">
						<span style="font-weight:bold;">Product Code:</span>
						<span>'.$row['prodline_cod'].'</span>
					</div>
					<div class="form-group" style="float:right; width:auto;">
						<span style="font-weight:bold;">Item Description:</span>
						<span>'.$row['prodline_desc'].'</span>						
					</div>
				</div>				
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:35%;">
						<span style="font-weight:bold;">Price Level Code:</span>
						<span>'.$row['pt_pricecode'].'</span>
					</div>
				</div>
				';
				echo $output;
			}
		}
		catch(PDOException $e) { //Just in case of errors, it will show the error code		
			echo '<div class="alert alert-danger">ERROR IN PRODUCTLINE VIEW! '.$e->getMessage().'</div>';				
		}		
	}
			
	//SHOW THE PRODUCT LINE DETAILS IN UPDATE MODE
	if ($_POST['btn_action']=='pline_update'){ //View Pline details
		//$output = array();
		$query='';
		$filtered_rows_det="";		
		
		$query="
			SELECT
				*
			FROM
				i_prodline			
			WHERE
				prodline_id = ".$_POST['prodline_id']."  
			ORDER BY prodline_id ASC			
		";
		
		try {
			$statement = $connect->prepare($query);//Prepare query
			$statement->execute();//Execute query
			$result = $statement->fetchAll();//Get results
			if ($statement->rowCount() == 0){
				echo '<div class="alert alert-danger">SOMETHING IS WRONG WITH THIS ITEM! </div>';					
			}
			foreach($result as $row){				
				$data['prodline_id'] = $row['prodline_id'];
				$data['prodline_cod'] = $row['prodline_cod'];
				$data['prodline_desc'] = $row['prodline_desc'];
				$data['pt_pricecode'] = $row['pt_pricecode'];							
			}
			
			$output = array(
				"rowcount"	=> $filtered_rows_det,	
				"data" 		=> $data
			);
			echo json_encode($output);
		}
		catch(PDOException $e) { //Just in case of errors, it will show the error code		
			echo '<div class="alert alert-danger">ERROR IN PRODUCT LINE UPDATE! '.$e->getMessage().'</div>';			
		}
	}
	
	if ($_POST['btn_action']=='add_pline' || $_POST['btn_action'] == 'updsv_pline'){ //Add/UPDATE Product Line.
		//Prepare the query to insert data
		$arr1=array();//Desclare the array 
		parse_str($_POST['valdata'], $arr1); //Putting all content serialized into the array
		
		if ($_POST['btn_action']=='add_pline') { //NEW PLINE 
			$query="
			INSERT INTO `i_prodline`
				(`prodline_cod`, `prodline_desc`, `pt_pricecode`) 
			VALUES 
				(:prodline_cod, :prodline_desc, :pt_pricecode) 
			";
		}elseif ($_POST['btn_action']=='updsv_pline'){ //UPDATING PLINE
			$query = "
			UPDATE `i_prodline` 
			SET 
				prodline_cod=:prodline_cod,
				prodline_desc=:prodline_desc,
				pt_pricecode=:pt_pricecode 
			WHERE 
				prodline_id=".$_POST['prodline_id'];
		}			
		try {
			//Prepare the query next I'll replace the vars with the data;
			$statement = $connect->prepare($query);			
			//Replacing text with the value of any single field			
			$statement->execute(
				array(				
					':prodline_cod'		=>	$arr1['pline_code'],
					':prodline_desc'	=>	$arr1['pline_desc'],
					':pt_pricecode'		=>	$arr1['type']					
				)
			);				
		}
		catch(PDOException $e)//Just in case of errors, it will show the error code
		{
			echo '<div class="alert alert-danger">ERROR UPDATING PRODUCT LINE! '.$e->getMessage().'</div>';
			exit();
		}
		echo '<div class="alert alert-success">Product Line Update is complete!</div>';			
	}	
}else{	
	echo '<div class="alert alert-warning">ERROR! No function selected -> '.$_POST['btn_action'].'</div>';
}
restore_error_handler();
?>