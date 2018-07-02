<?
//include utility functions
include "utility_functions.php";

//Get the sessionid, client type, semester, and course number.
$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$clientid = $_GET["clientid"];
$coursetoenroll = $_GET["courseseqid"];
$maxnumseats = $_GET["maxnumseats"];

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

//$studentmeetsprereq = check_prerequisites($connection, $clientid, $coursetoenroll);
$availableseats = get_number_of_available_seats($connection, $courseseqid, $maxnumseats);
$studentmeetsprereq = true;

$sql = "select studentid " .
	"from student " .
	"where clientid='$clientid'";

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor);

if ($result == false) {
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

OCIFetchInto ($cursor, $values);

$studentid = $values[0];

/* Enrollment will fail if there are no seats available or if the student
has not taken some or all of the prerequisite courses or has not passed
some or all of the courses. */
if ($availableseats <= 0) {
	echo("Enrollment failed.  There are no seats available in the selected course.");
} else if ($studentmeetsprereq == true) {
	//There is at least one available seat in the course and the student has met the
	//prerequisites.  Enroll student in course.
	$sql = "insert into coursestaken(studentid, courseseqid) " .
		"values('$studentid', '$coursetoenroll')";

	echo "You have been enrolled in the course you selected. <br /> <br /> <br />";
}	//end if

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor);

if ($result == false) {
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag" .
		"&adminflag=$adminflag&clientid=$clientid\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
