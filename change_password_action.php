<?
//include utility functions
include "utility_functions.php";

//Get the sessionid, old password and new password,
$sessionid = $_GET["sessionid"];
$clientid = $_POST["clientid"];
$oldpassword = $_POST["oldpassword"];
$newpassword = $_POST["newpassword"];
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

//Check client table for clientid
//and see if clientid and the old
//password are part of the same tuple.
$sql = "select clientid " .
       "from client " .
       "where clientid='$clientid'
         and password ='$oldpassword'";

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
  //The client has been found and the provided old
  //password matches the password stored in the client
  //table.
  $clientid = $values[0];
}
else { 
  //Either the client username was not found or the provided
  // old password does not match the password stored in the
  // client table.  Prompt client to verify login credentials.
  die ("Password change failed.  Please verify that the " .
	"client id and old password are correct and try again.");
}	//end if

//Change the client's password from the old password to the new one.
$query="update client set password = '$newpassword' " .
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

echo("<br />");
echo("Your password has been changed." . "<br />" . "<br />");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page.");
echo("<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
