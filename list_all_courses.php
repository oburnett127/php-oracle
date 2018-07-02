<HTML>
<HEAD>
<TITLE>List All Courses</TITLE>
</HEAD>
<BODY>
<H1>List All Courses</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$clientid = $_GET["clientid"];

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

//Select all tuples from courseoffering table
$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours,
		co.semseason, co.semyear, co.time, co.daysofweek,
		co.startdate, co.enddate, maxnumseats " .
       "from courseoffering co, coursedesc cd " .
	"where co.coursenumber = cd.coursenumber";

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor);

if ($result == false) {	//Client query was not successful.
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

//Display the course offering information for every course offering
echo "<table border=1>";
echo "<tr> <th></th><th>Course Sequence ID</th> <th>Course Number</th> <th>Course Title</th>
	<th>Credit Hours</th> <th>Semester</th> <th>Year</th> <th>Time of Day</th> 
	<th>Days of Week</th> <th>Start Date</th> <th>End Date</th>
	<th>Maximum Seats</th> <th>Seats Available</th> </tr>";

// fetch the result from the cursor one by one
while (OCIFetchInto ($cursor, $values)) {
  $courseseqid = $values[0];
  $coursenumber = $values[1];
  $coursetitle = $values[2];
  $credithrs = $values[3];
  $semseason = $values[4];
  $semyear = $values[5];
  $timeofday = $values[6];
  $daysofweek = $values[7];
  $startdate = $values[8];
  $enddate = $values[9];
  $maxnumseats = $values[10];

  $availableseats = get_number_of_available_seats($connection, $courseseqid, $maxnumseats);

  if($studentflag == 1) {
  	echo "<tr> <td><FORM name=\"enroll\" method=\"POST\" " .
  		"action=\"enroll_student_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag&clientid=$clientid&courseseqid=$courseseqid&maxnumseats=$maxnumseats\"> <input type=\"submit\" name=\"submit\" value=\"Enroll\"> </FORM></td>";
  } else echo "<tr> <td></td>";
  echo "<td>$courseseqid</td> <td>$coursenumber</td> <td>$coursetitle</td>
  <td>$credithrs</td> <td>$semseason</td> <td>$semyear</td>
  <td>$timeofday</td> <td>$daysofweek</td> <td>$startdate</td>
  <td>$enddate</td> <td>$maxnumseats</td> <td>$availableseats</td>
  </tr>";
}	//end while

echo "</table>";
echo("<br /> <br />");
//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag
	\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
