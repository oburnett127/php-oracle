<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$clientid = $_POST["clientid"];
$password = $_POST["password"];
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

//Insert the client information into the client table.
//The SQL transaction that is executed will be determined
//by the client type of the new client.
if ($newclienttype == "student") {
	$sql = "insert into client ".
		"values ('$clientid', '$password', 1, 0)";
} else if ($newclienttype == "administrator") {
	$sql = "insert into client ".
		"values ('$clientid', '$password', 0, 1)";
} else if ($newclienttype == "studentadministrator") {
	$sql = "insert into client ".
		"values ('$clientid', '$password', 1, 1)";}

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false) {
  // Error handling interface.
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"add_delete_update_client.php?sessionid=$sessionid
	&studentflag=$studentflag&adminflag=$adminflag\">

  <input type=\"hidden\" value = \"$clientid\" name=\"clientid\">
  <input type=\"hidden\" value = \"$password\" name=\"password\">
  <input type=\"hidden\" value = \"$newclienttype\" name=\"clienttype\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}	//end if

//Commit the result.
OCICommit ($connection);

if ($newclienttype == "student" || $newclienttype == "studentadministrator") {
	//The client being added is a student or a student administrator.
	Header("Location:add_student.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag");
} else {
	/* The client being added is an administrator.  The record has been inserted.  
	List the user back to the welcome page. */
	Header("Location:welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag");
}	//end if

//Close Oracle connection.
OCILogoff ($connection);
?>
