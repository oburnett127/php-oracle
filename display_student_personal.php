<HTML>
<HEAD>
<TITLE>My Personal Information</TITLE>
</HEAD>
<BODY>
<H1>My Personal Information</H1>
<?
//include utility functions
include "utility_functions.php";

//Get the sessionid and verify it
$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$clientid = $_GET["clientid"];

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

//select all student personal information for the studentid corresponding
//to the clientid of the logged in user.
$sql = "select studentid, fname, lname, age, streetaddress, " .
	"city, state, zipcode, graduateflag, status " .
	"from student " .
	"where clientid='$clientid'";

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor);

if ($result == false) {	//Query was not successful.
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

//Display the personal information of the student in a table.
echo "<table border=1>";
echo "<tr><th>Student ID</th> <th>First Name</th> <th>Last Name</th> <th>Age</th>
	<th>Street Address</th> <th>City</th> <th>State</th> <th>Zipcode</th>
	<th>Student Type</th> <th>Status</th></tr>";


//Fetch the result from the cursor
OCIFetchInto ($cursor, $values);
$studentid = $values[0];
$fname = $values[1];
$lname = $values[2];
$age = $values[3];
$streetaddress = $values[4];
$city = $values[5];
$state = $values[6];
$zipcode = $values[7];
$gradflag = $values[8];
$probationstat = $values[9];

//The graduate status should appear in a form readable to users.
if ($gradflag == 1) $gradstatus = "graduate";
else $gradstatus = "undergraduate";

//The probation status should appear in a form readable to users.
if ($probationstat == 1) $probationstatus = "probational";
else $probationstatus = "good standing";

echo "<tr> <td>$studentid</td> <td>$fname</td> <td>$lname</td>
<td>$age</td> <td>$streetaddress</td> <td>$city</td> <td>$state</td>
<td>$zipcode</td> <td>$gradstatus</td> <td>$probationstatus</td> " .
"</tr>";

echo "</table>";
echo("<br />");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag&clientid=$clientid\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
