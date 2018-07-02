<?
//include utility functions
include "utility_functions.php";

//Get the sessionid and clientid and 
$sessionid =$_GET["sessionid"];
$clientid = $_POST["clientid"];
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

//Get clientid from client table.
$sql = "select clientid " .
       "from client " .
       "where clientid='$clientid'";

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor);

if ($result == false){	//Client query was not successful.
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

if(OCIFetchInto ($cursor, $values)){
  //The client has been found
  $clientid = $values[0];
}
else { 
  //Either the client username was not found or the provided
  // old password does not match the password stored in the
  // client table.  Prompt client to verify login credentials.
  die ("Password reset failed.  Please verify that the User ID entered is correct.");
}	//end if

//reset the client's password
$query="update client set password = 'abc123'" .
	"where clientid = '$clientid'";

//Make a cursor for the SQL command contained in $query.
$cursor = OCIParse ($connection, $query);

if ($cursor == false){
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

//Execute the cursor.
$result = OCIExecute ($cursor);

if ($result == false){
   echo OCIError($cursor)."<BR>";
   exit;
}	//end if

echo("The user's password has been reset." . "<br />" . "<br />");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag
	\">here</A> to return to the welcome page.");
echo("<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
