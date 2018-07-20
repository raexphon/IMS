<!DOCTYPE html>

<?php
if(isset($_SESSION['active']))
{
	header("location:index.php");
} 
if (isset($_POST['btnsubmit'])){
	include('phpfunc/database_connection.php');
	include('phpfunc/function.php');
	if(user_auth($connect, $_POST['username'], $_POST['password'])==TRUE){		
		header("location:index.php?r=".base64url_encode("dashbrd"));
	}else{
		$message="";
		$message = "<label>Username/Password is wrong!</label>";
		include('head.php');
		echo "<div class='fglayout'>";		
		include('header.php');		
	}			
}else{	
	include('head.php');
	echo "<div class='fglayout'>";		
	include('header.php');		
}?>
<form name="flogin" id="flogin" class="form-horizontal ewForm ewLoginForm" action="login.php" method="post">
	<div id="msLoginDialog" class="modal fade in" style="display: block;">
		<div class="modal-dialog">
			<div class="modal-content" style="margin-top: 200px;">
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">x</span></button>
					<h4 class="modal-title">Login &nbsp;<a href="javascript:void(0);" id="helponline" onclick="msHelpDialogShow()"><span class="glyphicon glyphicon-question-sign ewIconHelp"></span></a> 
					</h4>
				</div>
				<div class="modal-body">
				<br>
					<input type="hidden" name="token" value="9ymZlFrVu1vruB1pOnsjoA..">
					<?php if (isset($message)){
						echo '<div class="form-group">';
						echo '	<label class="col-sm-6 control-label ewLabel" for="error">'.$message.'</label>';
						echo '</div>';
					}?>
					<div class="form-group">
						<label class="col-sm-4 control-label ewLabel" for="username">User Name</label>
						<div class="col-sm-8"><input type="text" name="username" id="username" class="form-control ewControl" value="" placeholder="User Name"></div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label ewLabel" for="password">Password</label>
						<div class="col-sm-8"><input type="password" name="password" id="password" class="form-control ewControl" placeholder="Password"></div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<a id="ewLoginOptions" class="collapsed" data-toggle="collapse" data-target="#flogin_options">Options <span class="icon-arrow"></span></a>
							<div id="flogin_options" class="collapse">
								<div class="radio ewRadio">
								<label for="type1"><input type="radio" name="type" id="type1" value="a">Auto login until I logout explicitly</label>
								</div>
								<div class="radio ewRadio">
								<label for="type2"><input type="radio" name="type" id="type2" value="u">Save my user name</label>
								</div>
								<div class="radio ewRadio">
								<label for="type3"><input type="radio" name="type" id="type3" value="" checked="checked">Always ask for my user name and password</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit">Login</button>
							<button class="btn btn-danger ewButton" name="btnreset" id="btnreset" type="reset">Reset</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="pull-left">
						<a class="ewLink ewLinkSeparator" href="forgotpwd.php">Forgot Password</a>
						<a class="ewLink ewLinkSeparator" href="register.php">Register</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>

var txt;
var username = prompt("Username:", "");
var password = prompt("Password:", "");
if (username == null || username == "") || (password == null || password == "") {
	txt = "Insert username/password";
} else {
	txt = "Authentication complete!";
}
document.getElementById("demo").innerHTML = txt;

</script>

</body>
</html>