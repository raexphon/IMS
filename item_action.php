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
	if ($_POST['btn_action']=='item_detail'){ //Check Item details and show in the table as details
	$query = "		
		SELECT i_pricelvl.* 
		FROM i_pricelvl 
		WHERE i_pricelvl.pt_item_id='".$_POST["product_code"]."'
		";				
		$statement = $connect->prepare($query); //Prepare the query
		$statement->execute(); //Execute query
		$result = $statement->fetchAll();	//Get results
		//OLD TABLE CLASS<table class="table table-bordered table-striped dataTable no-footer" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px">
		foreach($result as $chk){
			$type=$chk["pricelvl_method"];
		}
		//WRITING HTML CODE TO CREATE DETAIL TABLE
		$output='
		<div class="table-responsive">
			<table id="details-control" class="table table-bordered table-striped no-footer" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px">
				<thead>
					<tr>
						<th>Price Level</th>						
						<th>Pricing Method</th>
						<th>From Quantity</th>
						<th>To Quantity</th>';
		//CHECKING METHOD PRICE.... JUST IN CASE COST MARKUP ADDING COLUMN
		if ($type=="COST MARKUP"){ 
			$output.='
						<th>Markup Amount</th>';
		}
		$output.='				
						<th>Unit Price</th>
					</tr>
				</thead>
				<tbody>';				
		foreach($result as $row)//Fill the table cells
		{
			$name=($row["pricelvl_code"]=="")?"STANDARD":$row["pricelvl_code"];
			$output .= '<tr>							
							<td>'.$name.'</td>							
							<td>'.$row["pricelvl_method"].'</td>
							<td>'.$row["pricelvl_qty_from"].'</td>
							<td>'.$row["pricelvl_qty_to"].'</td>';
			//CHECKING METHOD PRICE.... JUST IN CASE COST MARKUP ADDING COLUMN
			if ($row["pricelvl_method"]=="COST MARKUP"){
				$output .='
							<td>'.$row["pricelvl_markup"].'</td>';
			}
			$output .='				
							<td>'.$row["pricelvl_unitprice"].'</td>
						</tr>';						
		}	
		$output .= '
				</tbody>
			</table>
		</div>';		
		
		echo ($output);//Return the table formatted 		
	}
	
	if ($_POST['btn_action']=='add_item' || $_POST['btn_action'] == 'updsv_item'){ //Add/UPDATE Price details and joining them with the new item.
		//Prepare the query to insert data
		$arr1=array();//Desclare the array 
		parse_str($_POST['valdata'], $arr1); //Putting all content serialized into the array
		
		if ($_POST['btn_action']=='add_item') { //NEW ITEM 
			$query="
			INSERT INTO `item`(`item_code`, `item_desc`, `item_prodline`, `item_uom`, `qty`, `item_pricecode`, 
			`item_standardcost`, `item_standardprice`, `item_retailprice`, `item_datareceipt`, `item_tax`, `item_taxvalue`, `item_enterby`, `item_inactiveitem`) 
			VALUES 
			(:item_code,:item_desc,:item_prodline,:item_uom,:qty,:item_pricecode,:item_standardcost,:item_standardprice,:item_retailprice,:item_datareceipt,
			:item_tax,:item_taxvalue,:item_enterby,:item_inactiveitem)
			";
		}elseif ($_POST['btn_action']=='updsv_item'){ //UPDATING ITEM
			$query = "
			UPDATE `item`
			SET
				item_code = :item_code,
				item_desc = :item_desc,
				item_prodline = :item_prodline,
				item_uom = :item_uom,
				qty = :qty,
				item_pricecode = :item_pricecode,
				item_standardcost = :item_standardcost,
				item_standardprice = :item_standardprice,
				item_retailprice = :item_retailprice,
				item_datareceipt = :item_datareceipt,
				item_tax = :item_tax,
				item_taxvalue = :item_taxvalue,
				item_enterby = :item_enterby,
				item_inactiveitem = :item_inactiveitem
			WHERE
				item_id = ".$_POST['item_id'];
		}
		try {
			//Prepare the query next I'll replace the vars with the data;
			$statement = $connect->prepare($query);
			$datareceipt=($arr1['item_lrec']<>'')? date('Y-m-d', strtotime($arr1['item_lrec'])) : '';
			//Replacing text with the value of any single field			
			$statement->execute(
				array(				
					':item_code'		=>	$arr1['item_code'],
					':item_desc'		=>	$arr1['item_desc'],
					':item_prodline'	=>	$_POST['prodline'], //cvprodline($connect,$_POST['prodline']),
					':item_uom'			=>	($_POST['uom']=="EACH")? "EACH" : $_POST['uom'],
					':qty'				=>	$_POST['qty'],
					':item_pricecode'	=>	$_POST['pricecode'], //cvpricecode($connect,$_POST['pricecode']),
					':item_standardcost'=>	$arr1['item_cost'],
					':item_standardprice'=>	$arr1['item_standardprice'],
					':item_retailprice'	=>	$arr1['item_retailprice'],
					':item_datareceipt'	=>	$datareceipt, 
					':item_tax'			=>	$_POST['tax'],				
					':item_taxvalue'	=>	$_POST['taxvalue'],												
					':item_enterby'		=>	getuserid($connect, $_SESSION["uname"]),
					':item_inactiveitem'		=>	$_POST['item_inactiveitem']
				)
			);	
		}
		catch(PDOException $e)//Just in case of errors, it will show the error code
		{
			echo '<div class="alert alert-danger">ERROR UPDATING ITEM! '.$e->getMessage().'</div>';
			exit();
		}
						
		//Read the array with Details lvl item		
		if (!is_null($_POST['arr1'])){
			$lvldet=$_POST['arr1'];
		}else{
			$lvldet=array();
		}	
		
		//EXECUTE THIS STEP INCASE WE ARE IN INSERT MODE 
		if ($_POST['btn_action']=='add_item') { 
			//Find the Last ID in the Item Table
			$last_id = $connect->lastInsertId(); 		
		
			//DECLARING 2 VARS COST MARKUP AND UNIT PRICE
			$pmarkup="";
			$punitprice="";
			//Loop to count how many records we have inside the array
			for ($i=0; $i<count($lvldet); $i++){
				try {								
					//Prepare query to insert the item price lvl.
					$query="
					INSERT INTO `i_pricelvl`(`pricelvl_code`, `pricelvl_method`, `pricelvl_qty_from`, `pricelvl_qty_to`, `pricelvl_markup`, `pricelvl_unitprice`, `pt_item_id`, `pt_pricecode_id`) 
					VALUES
					(:pricelvl_code,:pricelvl_method,:pricelvl_qty_from,:pricelvl_qty_to,:pricelvl_markup,
					:pricelvl_unitprice,:pt_item_id,:pt_pricecode_id)
					";	
					//Prepare the query next I'll replace the vars with the data;
					$statement = $connect->prepare($query);
					//CHECKING WHAT TYPE OF METHOD IS WORKING ON
					if ($lvldet[$i][1]=="COST MARKUP"){
						$pmarkup=$lvldet[$i][4];
						$punitprice=$lvldet[$i][5];
					}else{
						$pmarkup=0;
						$punitprice=$lvldet[$i][5];
					}
					//Replacing text with the value of any single field					
					$statement->execute(
						array(				
							':pricelvl_code'		=>	$lvldet[$i][0],						
							':pricelvl_method'		=>	$lvldet[$i][1],
							':pricelvl_qty_from'	=>	$lvldet[$i][2],
							':pricelvl_qty_to'		=>	$lvldet[$i][3],
							':pricelvl_markup'		=>	$pmarkup,
							':pricelvl_unitprice'	=>	$punitprice,
							//':pt_item_id'			=>	$last_id,
							':pt_item_id'			=>	$arr1['item_code'],
							':pt_pricecode_id'		=>	''
						)
					);
				}			
				catch(PDOException $e)//Just in case of errors, it will show the error code
				{
					echo '<div class="alert alert-danger">ERROR ADDING NEW PRICE LEVELS! '.$e->getMessage().'</div>';				
				}			
			}
			echo '<div class="alert alert-success">Item Added Successfully!</div>';
		}elseif ($_POST['btn_action']=='updsv_item'){ //UPDATING ITEM					
			//COMPARE HOW MANY RECORD WE HAVE into the price level details and into the table and in case it make delete				
			if (cprecords($connect, $_POST['item_code'], $_POST['plvl_count'] == 1)){ //1 means 1 or more records are deleted				
				dltrecplvl($connect, $_POST['item_code'], $lvldet);				
			}
			
			//I'M UPDATING THE RECORD INTO THE TABLE OR I'M ADDING A NEW ONE.
			for ($i=0; $i<count($lvldet); $i++){
				//CHECKING WHAT TYPE OF METHOD IS WORKING ON								
				if ($lvldet[$i][1]=="COST MARKUP"){
					$pmarkup	= $lvldet[$i][4];
					$punitprice	= $lvldet[$i][5];
					$pricelvl_id= getidfromstr($lvldet[$i][6]);
					$newfb=getnameibox($lvldet[$i][6]);
					if ($newfb){$newfb="new";}										
				}else{
					$pmarkup	= 0;
					$punitprice	= $lvldet[$i][5];
					$pricelvl_id= getidfromstr($lvldet[$i][6]);
					$newfb=getnameibox($lvldet[$i][6]);						
				}
				
				//CHECKING record type
				if ($newfb){
					$newfb="new";//New Record
				}else{
					$newfb="old";//Record updated
				}
				//echo "</br>ID:".$pricelvl_id. " - CHK:".$newfb;	
				
				if ($newfb=="old"){					
					try {
						//It found a record so going to update into the table
						$query = "
							UPDATE i_pricelvl 
							SET
								pricelvl_code 		= :pricelvl_code,
								pricelvl_method 	= :pricelvl_method,
								pricelvl_qty_from 	= :pricelvl_qty_from,
								pricelvl_qty_to 	= :pricelvl_qty_to,
								pricelvl_markup 	= :pricelvl_markup,
								pricelvl_unitprice 	= :pricelvl_unitprice
							WHERE
								pricelvl_id			= ".$pricelvl_id;
						
						//Prepare the query next I'll replace the vars with the data;
						$statement = $connect->prepare($query);						
						//Replacing text with the value of any single field						
						$statement->execute(
							array(				
								':pricelvl_code'		=>	$lvldet[$i][0],						
								':pricelvl_method'		=>	$lvldet[$i][1],
								':pricelvl_qty_from'	=>	$lvldet[$i][2],
								':pricelvl_qty_to'		=>	$lvldet[$i][3],
								':pricelvl_markup'		=>	$pmarkup,
								':pricelvl_unitprice'	=>	$punitprice							
							)
						);
					}
					catch(PDOException $e)//Just in case of errors, it will show the error code
					{
						echo '<div class="alert alert-danger">ERROR UPDATING PRICE LEVELS! '.$e->getMessage().'</div>';				
					}
				}elseif ($newfb=="new"){//That's means this is a new record					
					//Prepare query to insert the item price lvl.
					$query="
					INSERT INTO `i_pricelvl`(`pricelvl_code`, `pricelvl_method`, `pricelvl_qty_from`, `pricelvl_qty_to`, `pricelvl_markup`, `pricelvl_unitprice`, `pt_item_id`, `pt_pricecode_id`) 
					VALUES
					(:pricelvl_code,:pricelvl_method,:pricelvl_qty_from,:pricelvl_qty_to,:pricelvl_markup,
					:pricelvl_unitprice,:pt_item_id,:pt_pricecode_id)
					";
					try {						
						$statement=$connect->prepare($query);
						$statement->execute(
							array(
								':pricelvl_code'		=>	$lvldet[$i][0],						
								':pricelvl_method'		=>	$lvldet[$i][1],
								':pricelvl_qty_from'	=>	$lvldet[$i][2],
								':pricelvl_qty_to'		=>	$lvldet[$i][3],
								':pricelvl_markup'		=>	$pmarkup,
								':pricelvl_unitprice'	=>	$punitprice,
								':pt_item_id'			=>	$_POST['item_code'],
								':pt_pricecode_id'		=>	''
							)
						);	
					}					
					catch(PDOException $e)//Just in case of errors, it will show the error code
					{						
						echo '<div class="alert alert-danger">ERROR ADDING PRICE LEVELS IN UPDATE MODE! '.$e->getMessage().'</div>';				
					}
				}				
			}
			echo '<div class="alert alert-success">Item Update is complete!</div>';
		}
	}
	
	//ADD NEW PRODUCT LINE INTO THE TABLE
	if ($_POST['btn_action']=='new_pline'){ 
		//Prepare the query to insert data
		$arr1=array();//Desclare the array 
		parse_str($_POST['valdata'], $arr1); //Putting all content serialized into the array
		
		$query="
		INSERT INTO `i_prodline`(`prodline_cod`, `prodline_desc`, `pt_pricecode`) 
		VALUES 
		(:prodline_cod,:prodline_desc,:pt_pricecode)		
		";
		try {
			//Prepare the query next I'll replace the vars with the data;
			$statement = $connect->prepare($query);
			//Replacing text with the value of any single field
			$statement->execute(
				array(				
					':prodline_cod'		=>	$arr1['pline_code'],
					':prodline_desc'	=>	$arr1['pline_desc'],
					':pt_pricecode'		=>	$arr1['type'] //cvpricecode($connect,$arr1['type'])			
				)
			);
			//CALL fill_prodline FUNCTION TO REFILL THE SELECTBOX WITH NEW ENTRY
			fill_prodline($connect);
		}
		catch(PDOException $e)//Just in case of errors, it will show the error code
		{
			echo '<div class="alert alert-danger">ERROR! '.$e->getMessage().'</div>';
			exit();
		}		
		echo '<div class="alert alert-success">Product Line Added Successfully!</div>';
	}
	
	//ADD NEW PRICE CODE INTO THE TABLE
	if ($_POST['btn_action']=='new_pcode'){ //Add New Price Code into the table.
		//Prepare the query to insert data
		$arr1=array();//Desclare the array 
		parse_str($_POST['valdata'], $arr1); //Putting all content serialized into the array
		
		$query="
		INSERT INTO `i_pricecode`(`pricecode_code`, `pricecode_desc`) 
		VALUES 
		(:pricecode_code,:pricecode_desc)		
		";
		try {
			//Prepare the query next I'll replace the vars with the data;
			$statement = $connect->prepare($query);
			//Replacing text with the value of any single field
			$statement->execute(
				array(				
					':pricecode_code'	=>	$arr1['pcode_code'],
					':pricecode_desc'	=>	$arr1['pcode_desc']
				)
			);						
			//CALL fill_prodline FUNCTION TO REFILL THE SELECTBOX WITH NEW ENTRY
			//fill_prodline($connect);
		}
		catch(PDOException $e)//Just in case of errors, it will show the error code
		{
			echo '<div class="alert alert-danger">ERROR! '.$e->getMessage().'</div>';
			exit();
		}
		//Find the Last ID in the Item Table
		$last_id = $arr1['pcode_code'];
		
		//Read the array with Details lvl item
		$lvldet=$_POST['arr_pcode'];
		//DECLARING 2 VARS COST MARKUP AND UNIT PRICE
		$pmarkup="";
		$punitprice="";
		//Loop to count how many records we have inside the array
		for ($i=0; $i<count($lvldet); $i++){
			try {
				//Prepare query to insert the item price lvl.
				$query="
				INSERT INTO `i_pricelvl`(`pricelvl_code`, `pricelvl_method`, `pricelvl_qty_from`, `pricelvl_qty_to`, `pricelvl_markup`, `pricelvl_unitprice`, `pt_item_id`, `pt_pricecode_id`) 
				VALUES
				(:pricelvl_code,:pricelvl_method,:pricelvl_qty_from,:pricelvl_qty_to,:pricelvl_markup,
				:pricelvl_unitprice,:pt_item_id,:pt_pricecode_id)
				";	
				//Prepare the query next I'll replace the vars with the data;
				$statement = $connect->prepare($query);
				//CHECKING WHAT TYPE OF METHOD IS WORKING ON
				if ($lvldet[$i][1]=="COST MARKUP"){
					$pmarkup=$lvldet[$i][4];
					$punitprice=$lvldet[$i][5];
				}else{
					$pmarkup=0;
					$punitprice=$lvldet[$i][4];
				}
				//Replacing text with the value of any single field
				$statement->execute(
					array(				
						':pricelvl_code'		=>	$lvldet[$i][0],						
						':pricelvl_method'		=>	$lvldet[$i][1],
						':pricelvl_qty_from'	=>	$lvldet[$i][2],
						':pricelvl_qty_to'		=>	$lvldet[$i][3],
						':pricelvl_markup'		=>	$pmarkup,
						':pricelvl_unitprice'	=>	$punitprice,
						':pt_item_id'			=>	'0',
						':pt_pricecode_id'		=>	$last_id
					)
				);				
			}			
			catch(PDOException $e)//Just in case of errors, it will show the error code
			{
				echo '<div class="alert alert-danger">ERROR IN NEW PRICE CODE! '.$e->getMessage().'</div>';				
			}
		}
		//CALL fill_pricecode FUNCTION TO REFILL THE SELECTBOX WITH NEW ENTRY
		fill_pricecode($connect);
		echo '<div class="alert alert-success">Price Code Added Successfully!</div>';
	}
	
	//SHOW THE ITEM DETAILS IN MODE VIEW
	if ($_POST['btn_action']=='item_view'){ //View Item details
		$query="
			SELECT *
			FROM
				item			
			WHERE
				item_id = ".$_POST['item_id']."  
			ORDER BY item.item_id ASC			
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
						<span style="font-weight:bold;">Item Code:</span>
						<span>'.$row['item_code'].'</span>
					</div>
					<div class="form-group" style="float:right; width:auto;">
						<span style="font-weight:bold;">Item Description:</span>
						<span>'.$row['item_desc'].'</span>						
					</div>
				</div>				
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:60%;">
						<span style="font-weight:bold;">Product Line:</span>
						<span>'.$arrpline[0].' ('.$arrpline[1].') </span>					
					</div>
					<div class="form-group" style="float:left; width:25%;">
						<span style="font-weight:bold;">Status:</span>
						<span class="label '.chkstatus($row["item_inactiveitem"]).'">'.cvstatus($row["item_inactiveitem"]).'</span>
					</div>
					<div class="form-group" style="float:right; width:auto;">
						<span style="font-weight:bold;">Quantity:</span>
						<span>'.$row['qty'].'</span>						
					</div>
				</div>				
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:70%;">
						<span style="font-weight:bold;">Price Code:</span>
						<span>'.$arrpcode[0].' ('.$arrpcode[1].')</span>
					</div>
					<div class="form-group" style="float:right; width:auto;">
						<span style="font-weight:bold;">Tax:</span>
						<span>'.formattax($row["item_tax"], $row["item_taxvalue"]).'</span>
					</div>
				</div>
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:45%;">
						<span style="font-weight:bold;">Standard Cost:</span>
						<span style="color:red;">$'.$row["item_standardcost"].'</span>
					</div>
					<div class="form-group" style="float:right; width:auto;">
						<span style="font-weight:bold;">Data Receipt:</span>
						<span>'.$daterecp.'</span>
					</div>
				</div>
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:45%;">
						<span style="font-weight:bold;">Standard Price:</span>
						<span>$'.$row["item_standardprice"].'</span>
					</div>
					<div class="form-group" style="float:right; width:auto;">
						<span style="font-weight:bold;">Retail Price:</span>
						<span>'.$row["item_retailprice"].'</span>
					</div>
				</div>
				<div class="row" style="margin:0px;">
					<div class="form-group" style="float:left; width:45%;">
						<span style="font-weight:bold;">User:</span>
						<span>'.getusername($connect, $row["item_enterby"]).'</span>
					</div>
				</div>
				';							
			}
		}
		catch(PDOException $e) { //Just in case of errors, it will show the error code		
			echo '<div class="alert alert-danger">ERROR IN ITEM VIEW! '.$e->getMessage().'</div>';				
		}
		
		$query="
			SELECT *
			FROM i_pricelvl 
			WHERE pt_item_id='".$row['item_code']."'
			ORDER BY pt_item_id ASC
		";
		
		try {
			$statement = $connect->prepare($query);//Prepare query
			$statement->execute();//Execute query
			$rdet = $statement->fetchAll();//Get results
			$mkstring=""; //Declare variable working to save markup cost.
			$output.='<div class="table-responsive">
						<table class="table table-boredered">';			
			if ($statement->rowcount() >= 1){//In case result is > or = 1 then
				$output .= '<caption style="font-weight:bold; text-align:center;">ITEM PRICING LEVELS</caption>';
			}
			foreach($rdet as $row){
				$output .= '						
						<tr>
							<td style="font-weight:bold;">Code</td>
							<td style="font-weight:bold;">Method</td>
							<td style="font-weight:bold;">From Quantity</td>
							<td style="font-weight:bold;">To Quantity</td>';
				if ($row['pricelvl_markup'] > 0){//Just in case the price is calculated with markup 
					$output .= '												
							<td style="font-weight:bold;">Markup %</td>
							';
					$mkstring = '<td style="text-align:center;">'.$row["pricelvl_markup"].'%</td>'; // Save this td in case we got the markup costs.
				}				
				$output .= '
							<td style="font-weight:bold;">Unit Price</td>
						</tr>';				
				$output .= '
						<tr>
							<td style="text-align:center;">'.$row["pricelvl_code"].'</td>
							<td style="text-align:center;">'.$row["pricelvl_method"].'</td>
							<td style="text-align:center;">'.$row["pricelvl_qty_from"].'</td>
							<td style="text-align:center;">'.$row["pricelvl_qty_to"].'</td>';
				$output .= 	$mkstring;
				$output .=	'<td style="text-align:center;">$'.$row["pricelvl_unitprice"].'</td>';
				$output .= '
						</tr>
						';
			}
			$output .= '
					</table>
				</div>
					';
			echo $output;			
		}
		catch(PDOException $e) //Just in case of errors, it will show the error code			
		{
			echo '<div class="alert alert-danger">ERROR IN ITEM VIEW/PRICE LEVEL! '.$e->getMessage().'</div>';				
		}
	}
	
	//SHOW THE ITEM DETAILS IN UPDATE MODE
	if ($_POST['btn_action']=='item_update'){ //View Item details
		//$output = array();
		$query='';
		$filtered_rows_det="";		
		
		$query="
			SELECT
				item.*
			FROM
				item			
			WHERE
				item.item_id = ".$_POST['item_id']."  
			ORDER BY item.item_id ASC			
		";
		
		try {
			$statement = $connect->prepare($query);//Prepare query
			$statement->execute();//Execute query
			$result = $statement->fetchAll();//Get results
			if ($statement->rowCount() == 0){
				echo '<div class="alert alert-danger">SOMETHING IS WRONG WITH THIS ITEM! </div>';					
			}
			foreach($result as $row){
				if ($row['item_datareceipt']<>''){
					$data['item_lrec'] = cvdate($row['item_datareceipt'], "n");
				}else{
					$data['item_lrec'] = '';
				}
				$data['item_id'] = $row['item_id'];
				$data['item_code'] = $row['item_code'];
				$data['item_desc'] = $row['item_desc'];
				$data['item_inactiveitem'] = $row['item_inactiveitem'];
				$data['qty'] = $row['qty'];
				$data['src_prodline'] = $row['item_prodline']; // OLD STRING WITH INNER JOIN $data['src_prodline'] = $row['prodline_cod'];
				$data['src_pricecode'] = $row['item_pricecode']; // OLD STRING WITH INNER JOIN $data['src_pricecode'] = $row['pricecode_code'];
				$data['item_tax'] = $row['item_tax'];
				$data['item_taxvalue'] = $row['item_taxvalue'];
				$data['uom'] = $row['item_uom']; //cvuom($connect, $row['item_uom'], "y");
				$data['item_cost'] = $row['item_standardcost'];
				$data['item_standardprice'] = $row['item_standardprice'];
				$data['item_retailprice'] = $row['item_retailprice'];								
			}
			$query = "
				SELECT *
				FROM i_pricelvl
				WHERE pt_item_id = '".$row['item_code']."' 
				ORDER BY pricelvl_code ASC
			";
			try {
				$statement = $connect->prepare($query);//Prepare query
				$statement->execute();//Execute query
				$result = $statement->fetchAll();//Get results
				$filtered_rows_det = $statement->rowCount();//set variable with total of records
				$dtable[] = "";
				$acount = 0;
				foreach($result as $row){
					$dtable[$acount]['pricelvl_code'] = $row['pricelvl_code'];
					$dtable[$acount]['pricelvl_method'] = $row['pricelvl_method'];
					$dtable[$acount]['pricelvl_qty_from'] = $row['pricelvl_qty_from'];
					$dtable[$acount]['pricelvl_qty_to'] = $row['pricelvl_qty_to'];
					$dtable[$acount]['pricelvl_markup'] = $row['pricelvl_markup'];
					$dtable[$acount]['pricelvl_unitprice'] = $row['pricelvl_unitprice'];
					$dtable[$acount]['pricelvl_id'] = $row['pricelvl_id'];
					$acount++;
				}
			}
			catch(PDOException $e) { //Just in case of errors, it will show the error code		
				echo '<div class="alert alert-danger">ERROR IN ITEM UPDATE TABLE! '.$e->getMessage().'</div>';			
			}
			$output = array(
				"rowcount"	=> $filtered_rows_det,	
				"data" 		=> $data,
				"dtable"	=> $dtable
			);
			echo json_encode($output);
		}
		catch(PDOException $e) { //Just in case of errors, it will show the error code		
			echo '<div class="alert alert-danger">ERROR IN ITEM UPDATE! '.$e->getMessage().'</div>';			
		}
	}
	
	//DELETING ITEM
	if ($_POST['btn_action']=='delete_item'){ 
		//DELETE ALL PRice lvl linked to this item.
		$query = "
		DELETE FROM i_pricelvl
		WHERE pt_item_id='".$_POST['item_code']."'";		
		try{
			$statement=$connect->prepare($query);
			$statement->execute(); // DElete item			
		}
		catch(PDOException $e) { //Just in case of errors, it will show the error code		
			echo '<div class="alert alert-danger">ERROR DELETING ALL PRICING LEVEL ITEM! '.$e->getMessage().'</div>';			
		}		
		//DELETE The item selected from the table.
		$query = "
		DELETE FROM item
		WHERE item_id=".$_POST['item_id'];
		try{
			$statement=$connect->prepare($query);
			$statement->execute(); // DElete item			
			echo '<div class="alert alert-success">Item '.$_POST['item_code'].' deleted successfully!</div>';
		}
		catch(PDOException $e) { //Just in case of errors, it will show the error code		
			echo '<div class="alert alert-danger">ERROR DELETING ITEM! '.$e->getMessage().'</div>';			
		}
		
	}
	
	//CHECK RELATION BETWEEN ITEM AND INVOICE
	if ($_POST['btn_action']=='chk_rel_item'){ 
		$query = "
		SELECT * 
		FROM item
		WHERE item_id=".$_POST['item_id'];
		$statement=$connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row){
			$item_code=$row['item_code'];
		}
		$output = array(
			"item_code"	=>	$item_code,
			"invoice_link"		=>	0
		);
		echo json_encode($output);
	}
}else{	
	echo '<div class="alert alert-warning">ERROR! No function selected -> '.$_POST['btn_action'].'</div>';
}
restore_error_handler();
?>