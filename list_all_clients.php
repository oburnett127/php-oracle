<HTML>
<HEAD>
<TITLE>List of all Users</TITLE>
</HEAD>
<BODY>
<H1>List of all Users</H1>
<?
//include utility functions
include "utility_functions.php";

/* $studentflag and $adminflag hold the
student/administrator status of the client
viewing the list of all clients in the
client table.  $clientstudentflag and
$clientadminflag hold the student/administrator
status of the client whose client information
is to be displayed in the list of all clients
in the client table. */
$sessionid =$_GET["sessionid"];
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

//Select all tuples from client table.
$sql = "select * " .
       "from client";

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

//Display the client information of every client.
echo "<table border=1>";
echo "<tr> <th>User ID</th> <th>Password</th> <th>Client Type</th> </tr>";

// fetch the result from the cursor one by one
while (OCIFetchInto ($cursor, $values)){
  $clientid = $values[0];
  $password = $values[1];
  $clientstudentflag = $values[2];
  $clientadminflag = $values[3];

  //The client type needs to be displayed to the user in a form that is readable.
  if ($clientstudentflag == 1 and $clientadminflag == 0) $clienttype = "student";
  else if ($clientstudentflag == 0 and $clientadminflag == 1) $clienttype = "administrator";
  else if ($clientstudentflag == 1 and $clientadminflag == 1) $clienttype = "student administrator";

  echo "<tr><td>$clientid</td> <td>$password</td> <td>$clienttype</td>".
        "</tr>";
}	//end while

echo "</table>";
echo("<br /> <br />");
//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>

