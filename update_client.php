<?
//include utility functions
include "utility_functions.php";

//Get the sessionid, clientid and the personal information of the
//student being added to the system.
$sessionid = $_GET["sessionid"];
$currentclientid = $_POST["currentclientid"];
$newclientid = $_POST["newclientid"];
$newclienttype = $_POST["newclienttype"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

//Environment set up.
putenv("ORACLE_HOME=/home/oracle/OraHome1");
putenv("ORACLE_SID=orcl");

//Connect to Oracle
$connection = OCILogon ("gq004", "ldlwog");

if ($connection == false){
   echo OCIError($connection)."<BR>";
   exit;
}	//end if

/* An administrative user can enter information in the
currentclientid, newclientid, and newclienttype text
boxes on the update_client form of 
"add_delte_update_client.php" to update the clientid
and clienttype of a client.  
  If the user does not enter information in any of the text
boxes then a message should inform the user that they must 
enter a clientid in the currentclientid text box, and they
must either choose a new client type in the newclienttype
drop down list, or they must enter a value in the
newclientid text box.
  If the user enters a clientid in the currentclientid
text box but does not enter a new clientid or choose a
new clienttype, then the user is informed they must do
this to update the client information.  
  If the user enters a new clientid and chooses a new
clienttype but does not enter a current clientid, then
a message will be displayed informing the user they
must do this to update the client information for a
client. 
  If the user enters the current clientid and either
enters a new clientid, chooses a new clienttype,
or does both, then one of several SQL statements will
be executed to update the client information for
the client corresponding to the current clientid
provided.  The SQL statement to be executed will
be determined by whether the user entered a new
clientid, chose a new clienttype, or did both, and
on which selection the user made for the new
clienttype. */
if ((!isset($newclientid) or $newclientid == "")
	and (!isset($newclienttype) or $newclienttype == "") 
	and (!isset($currentclientid) or $currentclientid == "")) {
	/* The user has not entered information in
	any of the three text boxes on the
	update_client form. */
	echo("You must enter the current user ID
		and either enter a new user ID
		or choose a new user type to
		update a user's information.");
} else if (!isset($newclientid)
	and !isset($newclienttype))  {
	/* The user has entered the current clientid, but
	has not entered the new clientid or chosen a new
	clienttype. */
	echo("You must either enter a new user ID or
		choose a new user type to update
		the user's information.");
} else if (!isset($currentclientid) or $currentclientid == "") {
	/* The user did not enter a current clientid, but
	either entered a new clientid or chose a new
	clienttype or both. */
	echo("You must provide a user ID in the
		\"Current User ID\" text box
		in the \"Update User Information\"
		section to update a user's information.");
} else if (!isset($newclientid) or $newclientid == "") {
	/* The user entered the current clientid
	and chose a new clienttype, but did not
	enter a new clientid. */
	if($newclienttype == "student") {
		$sql =  "update client " .
			"set studentflag = 1, adminflag = 0 " .
			"where clientid = '$currentclientid'";
	} else if($newclienttype == "administrator") {
		$sql =  "update client " .
			"set studentflag = 0, adminflag = 1 " .
			"where clientid = '$currentclientid'";
	} else if($newclienttype == "studentadministrator") {
		$sql =  "update client " .
			"set studentflag = 1, adminflag = 1 " .
			"where clientid = '$currentclientid'";
	}	//end if
} else if (!isset($newclienttype) or $newclienttype == "") {
	/* The user entered the current clientid
	and entered a new clientid, but did not
	choose a new clienttype. */
	$sql =  "update client " .
		"set clientid = '$newclientid' " .
		"where clientid = '$currentclientid'";
} else {
	/* The user entered the current clientid, the
	new clientid, and chose a new clienttype. */
	if($newclienttype == "student") {
		$sql =  "update client " .
			"set clientid = '$newclientid',
				studentflag = 1, adminflag = 0 " .
			"where clientid = '$currentclientid'";
	} else if($newclienttype == "administrator") {
		$sql =  "update client " .
			"set clientid = '$newclientid',
				 studentflag = 0, adminflag = 1 " .
			"where clientid = '$currentclientid'";
	} else if($newclienttype == "studentadministrator") {
		$sql =  "update client " .
			"set clientid = '$newclientid',
				 studentflag = 1, adminflag = 1 " .
			"where clientid = '$currentclientid'";
	}	//end if
}	//end if
	
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  	  <form method=\"post\"
		 action=\"insert_delete_update_client.php?sessionid=$sessionid&studentflag=$studentflag
			&adminflag=$adminflag\">
	  <input type=\"hidden\" value = \"$currentclientid\" name=\"currentclientid\">
	  <input type=\"hidden\" value = \"$newclientid\" name=\"newclientid\">
	  <input type=\"hidden\" value = \"$newclienttype\" name=\"newclienttype\">
  
  	  Read the error message, and then try again:
  	  <input type=\"submit\" value=\"Go Back\">
  	  </form>

  	  </i>
  ");
}	//end if

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//The record has been updated.  List the user back to the welcome page.
Header("Location:welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag");
?>
</BODY>
</HTML>
