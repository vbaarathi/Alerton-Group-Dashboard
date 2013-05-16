<?php
	//include header
	include('header.php');
	
	include('../db.php');
	
	if (array_key_exists('_submit_check', $_POST)) {
	
	$searchtext = '%' . $_POST['searchtext'] . '%';
	
	if($_POST['searchtype']=='')
	{
	$searchtype = 'name';
	}else{
	$searchtype = $_POST['searchtype'];
	}
	
	$sQuery = "SELECT id, Name, Login, Email, admin, banned FROM users WHERE $searchtype LIKE '$searchtext' ORDER BY Name";
	$result = mysql_query($sQuery);
	if (empty($result)) {
        trigger_error('MySQL ERROR ('.mysql_errno().'): "'.
            mysql_error().'", the query was: "' . $query . '".',
            E_USER_ERROR);
			}
	}else{
	
	$sQuery = "SELECT id, Name, Login, Email, admin, banned FROM users ORDER BY Name";
	$result = mysql_query($sQuery);
	if (empty($result)) {
        trigger_error('MySQL ERROR ('.mysql_errno().'): "'.
            mysql_error().'", the query was: "' . $query . '".',
            E_USER_ERROR);
    }
	}
?>

	<!-- Main -->
	<div class="userform">
	<h4>Manage Users</h4>
	<div id="landingform">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="edituser-search">
     	<div id="useroption">
	     	<label>Search</label><input type="text" name="searchtext" size="15"/> 
	     	<div><input type="checkbox" name="searchtype" value="Name" >
	     	<span>Name</span></div> 
	     	<div><input type="checkbox" name="searchtype" value="Email" >
	     	<span>Email</span> </div>
	     	<div><input type="checkbox" name="searchtype" value="Login" >
	     	<span>Login</span></div>
     	</div>
      <input type="submit" name="submit" value="Submit">
      <input type="hidden" name="_submit_check" value="1"/>
      </form>
	</div>
	</div>

		
		<div class="userform">
			<p>Which user you want to manage?</p>
			<?php	
	

	echo "<table><tr><th>Name</th><th>Login</th><th>Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Admin&nbsp;&nbsp;&nbsp;</th><th>Banned&nbsp;&nbsp;&nbsp;</th><th>Delete&nbsp;&nbsp;&nbsp;</th><th></th></tr>";
	
while($row = mysql_fetch_array($result))
  {	
    echo "<form action='edituser-exec.php' method='post' name='edituser'>";
  echo "<tr>";
  echo "<td><input type='text' value='" . $row['id'] . "' name='id' size='1' style=\"border-style:hidden; background:#f6f6f6; color:#f6f6f6; display:none;\" readonly /><input type='text' value='" . $row['Name'] . "' name='Name' size='15' style=\"border-style:hidden; background:#f6f6f6;\" /></td> ";
  echo "<td><input type='text' value='" . $row['Login'] . "' name='Login' size='15' style=\"border-style:hidden; background:#f6f6f6;\" /> </td>";
  echo "<td><input type='text' value='" . $row['Email'] . "' name='Email' size='30' style=\"border-style:hidden; background:#f6f6f6;\" /> </td>";
  
    if ( $row['admin'] > "0" ) {
  echo "<td><input type='checkbox' name='admin' value='1' checked></td> ";
  }
  if ( $row['admin'] < "1" ) {
  echo "<td><input type='checkbox' name='admin' value='1'></td> ";
  }
    if ( $row['banned'] > "0" ) {
  echo "<td><input type='checkbox' name='banned' value='1' checked></td> ";
  }
  if ( $row['banned'] < "1" ) {
  echo "<td><input type='checkbox' name='banned' value='1'></td> ";
  }
  
  echo "<td><center><input type='checkbox' name='delete' value='1'></center></td> ";
  echo "<td><input type='submit' name='submit' value='Submit' onClick='submitFunction(2)'></td>";
  echo "</tr>";
    echo "</form>";
  }

  echo "</table>";
  
 ?>
		</div>
