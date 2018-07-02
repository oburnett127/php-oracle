<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$clienttypesearchmethod = trim($_POST["clienttypesearchmethod"]);
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

/* Content of sql variable is determined by the selection made in the 
clienttypesearchmethod drop down list and the clientid entered on the
search_client.php page. */
if (!isset($clientid) or $clientid == "") {
	if( $clienttypesearchmethod == "student") {
		$sql =  "select clientid, password, studentflag, adminflag " .
			"from client " .
			"where studentflag = 1 and adminflag = 0";
	} else if ($clienttypesearchmethod == "administrator") {
		$sql =  "select clientid, password, studentflag, adminflag " .
			"from client " .
			"where studentflag = 0 and adminflag = 1";
	} else if ($clienttypesearchmethod == "studentadministrator") {
		$sql =  "select clientid, password, studentflag, adminflag " .
			"from client " .
			"where studentflag = 1 and adminflag = 1";
	}	//end if
} else {
	if ($clienttypesearchmethod == "student") {	
		$sql =  "select clientid, password, studentflag, adminflag " .
			"from client " .
			"where studentflag = 1 and adminflag = 0 " .
				"and clientid like '%$clientid%'";
	} else if ($clienttypesearchmethod == "administrator") {
		$sql =  "select clientid, password, studentflag, adminflag " .
			"from client " .
			"where studentflag = 0 and adminflag = 1 " .
				"and clientid like '%$clientid%'";
	} else if ($clienttypesearchmethod == "studentadministrator") {
		$sql =  "select clientid, password, studentflag, adminflag " .
			"from client " .
			"where studentflag = 1 and adminflag = 1 " .
				"and clientid like '%$clientid%'";
	}	//end if
}	//end if

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor, OCI_DEFAULT);

if ($result == false){	//Client query was not successful.
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

//Display the client information of every client.
echo "<table border=1>";
echo "<tr> <th>User ID</th> <th>Password</th> <th>User Type</th> </tr>";

// fetch the result from the cursor one by one
while (OCIFetchInto ($cursor, $values)){
  $clientid = $values[0];
  $password = $values[1];
  $clientstudentflag  = $values[2];
  $clientadminflag = $values[3];

//The client type needs to be displayed to the user in a form that is readable.
if ($clientstudentflag == 1 and $clientadminflag == 0) $clienttype = "Student";
else if ($clientstudentflag == 0 and $clientadminflag == 1) $clienttype = "Administrator";
else if ($clientstudentflag == 1 and $clientadminflag == 1) $clienttype = "Student Administrator";


  echo "<tr><td>$clientid</td> <td>$password</td> <td>$clienttype</td>" .
        "</tr>";
}	//end while

echo "</table>";
echo ("<br />");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to client_search page to start a new search, a link to the welcome page, and a link to logout.
echo("Click <A HREF = \"search_client.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to start a new search.");
echo("<br />");
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page.");
echo("<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
