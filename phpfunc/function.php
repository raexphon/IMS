<?php // Function.php ?>

<?php
function checklogo($con, $user = null) {
	$result="";
	$query="SELECT * FROM setting";
	$statement = $con->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	return $result;
}

//Verify Password
function user_auth($con, $user, $pwd){
	//$phash=password_hash($pwd,PASSWORD_BCRYPT);
	$stmt="";
	$query="SELECT * FROM user where username = :username";
	$stmt=$con->prepare($query);

	$stmt->execute(
		array(
			'username' => $user
			)
		);
	$count = $stmt->rowCount();
	if($count > 0)
	{
		$result = $stmt->fetchAll();
		foreach($result as $row){
			if($row['active'] == 'Y')
			{
				// $_SESSION["print"] = $pwd;
				printf($row["password"]);
				if(password_verify($pwd, $row["password"]))
				{
					$_SESSION["uid"]=$row['user_id'];
					$_SESSION["uname"]=$row['username'];
					$_SESSION["active"]=$row['password'];
					$_SESSION["lock"]=$row['locked'];
					// to avoid csrf use token in every submit form
					$token = md5(uniqid(rand(), TRUE));
					$_SESSION["token"] = $token;
					$_SESSION["token_time"] = $time;
					return true;
				}
				else
				{
					$_SESSION["usermessage"] = "<label>Wrong Password</label>";
					$token = $_SESSION["token"];
					return false;
				}
			}
			else
			{
				$_SESSION["usermessage"] = "<label>Your account is disabled, Contact Master</label>";
				return false;
			}
		}
	}
}

//Getting userid by user logged
function getuserid($connect, $user){
	$query="
	SELECT user_id, username
	FROM user
	WHERE username='" . $user ."'
	";
	$stmt=$connect->prepare($query);
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach($result as $row){
		$id=$row["user_id"];
	}
	return $id;
}

//Getting uname by user table
function getusername($connect, $userid){
	$query="
	SELECT user_id, username
	FROM user
	WHERE user_id=" . $userid;
	$stmt=$connect->prepare($query);
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach($result as $row){
		$uname=$row["username"];
	}
	return $uname;
}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}
//CHECK WHAT PAGE GOING TO OPEN UP AND RETURN TEXT FOR BREADCUMB
function chkclickmenu($wordfilter){
	if (!isset($wordfilter) || !$wordfilter){//Check what page open
			echo "Dashboard";
	}else{
		switch (base64url_decode($wordfilter)) {
			case "dashbrd":
				$ret = "Dashboard";
				break;
			case "itemmntc":
				$ret = "Item Maintenance";
				break;
			case "pline":
				$ret = "Product Line Maintenance";
				break;
			default:
				$ret = "Dashboard";
		}
	echo $ret;
	}
}
function acv($getvar, $comparevar, $hsh="y"){	//Write active or not inside the class
	if ($hsh=='y') {
		switch ($getvar) {
			case "":
				$result= "active";
				break;

			case base64url_encode($comparevar):
				$result= "active";
				break;

			default:
				$result= "";
		}
	}else{
		switch ($getvar) {
			case "":
				$result= "active";
				break;

			case $comparevar:
				$result= "active";
				break;

			default:
				$result= "";
		}
	}
	return $result;
}

function countnumrow($con, $table,$cond=""){
	$query=($cond=="")? "Select * FROM ".$table."" : "Select * FROM ".$table." WHERE ".$cond;
	if ($result=mysqli_query($con,$query)){
		$rowcount=mysqli_num_rows($result);
		return $rowcount;
	}else{
		return 0;
	}
}

function selecttable($con, $table, $cond=""){
	$query="Select * FROM ".$table;
	if ($cond!=""){
		$query.=" WHERE ";
		$query.="item_code=".$cond;
		$query.=" AND item_desc=".$cond;
	}
	$result=mysqli_query($con, $query);
	return $result;
}

function selectmultitb($con, $table, $fromtable, $typejoin="", $cond=""){
	$query="SELECT ";
	$q_table="";
	$totalElements = count( $table );

	for ( $i=0; $i < $totalElements; $i++ ) {//Count how many table have in the array
		$q_table.=($i>0)? ", ":"";
		$q_table.=$table[$i];//add the tables into the query;
	//  if ( $i < $totalElements -1 ) echo ", ";
	}
	$query.=$q_table." FROM ".$fromtable;
	if ($typejoin<>""){
		$query.=" ".$typejoin;
	}
	if ($cond<>""){
		$query.=" ".$cond;
	}

	$result=mysqli_query($con, $query);
	return $result;
}

function FullTableView($con, $table){
	$query="Select * FROM ".$table;

	// Numeric array
	if($result=mysqli_query($con,$query)){
		/* Get field information for all columns */
        $finfo = $result->fetch_fields();
		$i=0;
		$data="";
		while ($row=mysqli_fetch_row($result)){	//start the cycle to fill the array with the field's value
			$ifield=0;
			foreach($finfo as $tfield){//Creating an array putting inside all field's value
				$data[$tfield->name]= $row[$ifield];
				$ifield++;
			}
			$fieldvalue[]=$data;
			$data="";
			$i++;
		}
	}else echo ($con->error);
	mysqli_free_result($result);
	mysqli_close($con);
	if (isset($fieldvalue)) {
		return $fieldvalue;
	}else{
		$fieldvalue=array();
	}
}

function fill_prodline($connect, $path=""){
	$query = "
	SELECT prodline_id, prodline_cod, prodline_desc
	FROM i_prodline
	ORDER BY prodline_cod ASC
	";
	$statement = $connect->prepare($query); //Prepare the query
	$statement->execute(); //Execute query
	$result = $statement->fetchAll();	//Get results
	$data = array();//Initialize the array
	$filtered_rows = $statement->rowCount();//set variable with total of records
	foreach($result as $row){
		$sub_array = array();	//Set the array to fill with datafields
		$sub_array["id"] = $row['prodline_id'];
		$sub_array['code'] = $row['prodline_cod'];
		$sub_array['desc'] = $row['prodline_desc'];
		$data[] = $sub_array;
	}
	//creates the file
	if ($path != ""){
		$fp = fopen($path, 'w');
	}else{
		$fp = fopen('json/item_prodline.json', 'w');
	}
	fwrite($fp, json_encode(['data'=>$data]));
	fclose($fp);
}

function fill_uom($connect, $path=""){
	$query = "
	SELECT uom_id, uom_code
	FROM i_uom
	order by uom_code ASC
	";
	$statement = $connect->prepare($query); //Prepare the query
	$statement->execute(); //Execute query
	$result = $statement->fetchAll();	//Get results
	$data = array();//Initialize the array
	$filtered_rows = $statement->rowCount();//set variable with total of records
	foreach($result as $row){
		$sub_array = array();	//Set the array to fill with datafields
		$sub_array["id"] = $row['uom_id'];
		$sub_array['code'] = $row['uom_code'];
		$data[] = $sub_array;
	}
	//creates the file
	if ($path != ""){
		$fp = fopen($path, 'w');
	}else{
		$fp = fopen('json/uom.json', 'w');
	}
	fwrite($fp, json_encode(['data'=>$data]));
	fclose($fp);
}

function fill_pricecode($connect, $path=""){
	$query = "
	SELECT pricecode_id, pricecode_code, pricecode_desc
	FROM i_pricecode
	order by pricecode_code ASC
	";
	$statement = $connect->prepare($query); //Prepare the query
	$statement->execute(); //Execute query
	$result = $statement->fetchAll();	//Get results
	$data = array();//Initialize the array
	$filtered_rows = $statement->rowCount();//set variable with total of records
	foreach($result as $row){
		$sub_array = array();	//Set the array to fill with datafields
		$sub_array["id"] = $row['pricecode_id'];
		$sub_array["code"] = $row['pricecode_code'];
		$sub_array['desc'] = $row['pricecode_desc'];
		$data[] = $sub_array;
	}
	//creates the file
	if ($path != ""){
		$fp = fopen($path, 'w');
	}else{
		$fp = fopen('json/pricecode.json', 'w');
	}
	fwrite($fp, json_encode(['data'=>$data]));
	fclose($fp);
}

function fieldvalue(array $ar_value, $id_seqarray, $nfield){

	return $ar_value["sequence".$id_seqarray][$nfield];

}
//Find the PRODUCT LINE ID thanks to $type
function cvprodline($connect, $type, $reverse="n"){
	if ($reverse == "n"){
		$query="
		SELECT prodline_id, prodline_cod
		FROM i_prodline
		WHERE prodline_cod='".$type."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->fetchAll();
		foreach($result as $row)
		{
			return $row['prodline_id'];
		}
	}elseif ($reverse == "y"){
		//REVERSE YES
		$query="
		SELECT prodline_id, prodline_cod, prodline_desc
		FROM i_prodline
		WHERE prodline_cod='".$type."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->fetchAll();
		foreach($result as $row)
		{
			return array($row['prodline_cod'],$row['prodline_desc']);
		}
	}
}
//Find the ID thanks to $type
function cvuom($connect, $type, $reverse="n"){
	if ($reverse == "n"){
		$query="
		SELECT uom_id, uom_code
		FROM i_uom
		WHERE uom_code='".$type."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->fetchAll();
		foreach($result as $row)
		{
			return $row['uom_id'];
		}
	}elseif ($reverse == "y"){
		$query = "
		SELECT *
		FROM i_uom
		WHERE uom_id=".$type;
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->fetchAll();
		foreach($result as $row)
		{
			return $row['uom_code'];
		}
	}
}
//Find the ID thanks to $type
function cvpricecode($connect, $type, $reverse="n"){
	if ($reverse == "n"){
		$query="
		SELECT pricecode_id, pricecode_code
		FROM i_pricecode
		WHERE pricecode_code='".$type."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->fetchAll();
		foreach($result as $row)
		{
			return $row['pricecode_id'];
		}
	}elseif ($reverse == "y"){
		$query="
		SELECT pricecode_id, pricecode_code, pricecode_desc
		FROM i_pricecode
		WHERE pricecode_code='".$type."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->fetchAll();
		foreach($result as $row)
		{
			return array($row['pricecode_code'], $row['pricecode_desc']);
		}
	}
}

function cvdate($dtime, $ext="y"){
	if (!is_null($dtime)){
		$strtime = strtotime($dtime);//Convert mysql date in string
		if ($ext == "y"){ //If I want an extense date in View Mode
			$newdate = date("M d, Y", $strtime); //Convert date in Long American date
		}elseif ($ext == "n"){//I want the short date
			$newdate = date("m/d/Y", $strtime); //Convert date in American date
		}
		return $newdate;
	}else{
		return "";
	}
}
function my_error_handler($errno,$errstr) {
  /* handle the issue */
	if ($errno == 8){
		return true; // if you want to bypass php's default handler
	}else{
		echo "Error N°:".$errno. " - Err. Description: ".$errstr;
		return false;
	}
}
function formattax($codetax, $vtax=0){
	//Format tax result in string
	if ($codetax == "NT"){
		$output = "No Tax";
	}elseif ($codetax == "TX"){
		$output = $vtax;
	}
	return $output;
}

function is_even($num){
	$even = ($num % 2) ? "odd" : "even" ;
	return $even;
}

function get_total_all_records($connect, $query)
{
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
//I can read an ID from a in a string like this: <input type='checkbox' name='chkdet' class='chk_plvl' id='123456789012'/>
function getidfromstr($str){
	$str=htmlentities($str);//CONVERT THE HTML code in string
	$idstr = strpos($str, "id=");//Find the position of the FIRST occurence with name
	$result=0;
	$i=1;
	while (is_numeric($result)){
		$result= substr($str,$idstr+4,$i); //Extract string start from idstr+6 so: chkdet or newfb
		$i++;
		if (is_numeric($result)){$saveid = $result;}//Incase its numeric it save the variable
	}
	return $saveid;
}
//FIND THE ITEM_CODE FROM ITEM_ID and return item_code
function findcode($connect, $id){
	$code="";
	$query = "
		SELECT * item_id=".$id;
	try{
		$statement=$connect->prepare($query);
		$statement->execute(); // DElete item
		$result = $statement->fetchAll();//Get results
		foreach($result as $row){
			$code=$row['item_code'];
		}
	}
	catch(PDOException $e) { //Just in case of errors, it will show the error code
		echo '<div class="alert alert-danger">ERROR SLECTING ITEM FROM ITEM TABLE! '.$e->getMessage().'</div>';
	}
	return $code;
}
//Extract the name of the hidden inputbox from the detail item price level.
function getnameibox($str){
	$idstr = strrpos($str, "name");//Find the position of the last occurence with name
	$str= substr($str,$idstr+6,5); //Extract string start from idstr+6 so: chkdet or newfb
	$compare=strcmp( $str, 'newfb' );//Compare the result with the string
	if ($compare == 0) {
		return true;
	} else {
		return false;
	}
}

//COMPARE HOW MANY RECORD WE HAVE into the price level details and into the table and in case it make delete
function cprecords($connect, $pid, $ct){ //pid is item_product_id while $ct is the counter that actually we have in the datatable
	$query = "
		SELECT *
		FROM i_pricelvl
		WHERE pt_item_id='".$pid."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$count = $statement->rowCount();
	$ct=(int)$ct;
	$count=(int)$count;
	if ($ct >= $count){
		return 0; //No record deleted in the datatable
	}elseif ($ct < $count){
		return 1; //Record deleted in the datatable
	}

}

//DELETE records from the table item
function dltrecplvl($connect, $pid, $det_array=null){
	if (!is_null($det_array)){//We got records into the datatable
		$query = "
			SELECT *
			FROM i_pricelvl
			WHERE pt_item_id=".$pid."
			ORDER BY pricelvl_code ASC
			";
		//Search records inside the table
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		//Scroll inside the table to compare with the array and check if the record is deleted from the datatable
		$i=0;
		foreach($result as $row) {
			$find=1;//THis is the var with True or False to decide if I gotta delete the record or not.
			for ($i=0; $i<count($det_array); $i++){//Open the array and check inside it
				//CHECKING WHAT TYPE OF METHOD IS WORKING ON
				if ($det_array[$i][1]=="COST MARKUP"){
					$pricelvl_id= getidfromstr($det_array[$i][6]);
				}else{
					$pricelvl_id= getidfromstr($det_array[$i][6]);
				}
				if ($pricelvl_id == $row['pricelvl_id']){
					$find=0;//The record is into the table so I don't have delete it.
				}
			}
			if ($find == 1){
				//DELETING RECORD FROM THE TABLE
				$query = "
					DELETE FROM i_pricelvl
					WHERE
						pricelvl_id=".$row['pricelvl_id'];
				$statement=$connect->prepare($query);
				$statement->execute();
			}
		}
	}
}

function chkstatus($value){
	$result=($value=="N")? "label-success" : "label-warning";
	return $result;
}
//Return text label depend by status item.
function cvstatus($value){
	if ($value == "N"){
		return "Active";
	}else{
		return "Inactive";
	}
}
//If item status is Active so put a check in the checkbox else don't do nothing
function chkvaluestatus($value){
	$result=($value=="Inactive")? 'checked="checked"' : "";
	return $result;
}

//RETURN THE FIELD VALUE FROM THE ARRAY (EX: if I want to find prod_code form array I cal valueassoc(array,prodcode))
function valueassoc($v_array="", $fieldname){
	$x=$v_array->fetch_assoc();
	return $x[$fieldname];
}
?>
