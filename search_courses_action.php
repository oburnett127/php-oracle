<?
//include utility functions
include "utility_functions.php";

//Get the sessionid, client type, semester, and course number.
$sessionid = $_GET["sessionid"];
$semester = $_POST["semester"];
$coursenumber = $_POST["coursenumber"];
$coursenumsearchspec = $_POST["coursenumsearchspec"];
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

$season = "";
$year = "";

if ($semester == "spring2011") {
	$season = "Spring";
	$year = 2011;
} else if ($semester == "summer2011") {
	$season = "Summer";
	$year = 2011;
} else if ($semester == "fall2011") {
	$season = "Fall";
	$year = 2011;
} else if ($semester == "spring2012") {
	$season = "Spring";
	$year = 2012;
}	//end if

/* Search for courses based on the information entered in the semester
and course number text boxes on the searchcourses form. It is possible
that one of three SQL transactions will be executed determined by the
user entering information only in the semester text box, only in the
coursenumber text box, or in both text boxes.  It is also possible
that the user will not enter information in either text box, in which
case a message is displayed informing the user that they need to 
enter information in at least one of the text boxes to get a list of
courses to appear in their search results. */
if ($coursenumber == "") {
	/* The user entered information in the semester text box, but no
	information in the coursenumber text box. */
	$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
			"co.semseason, co.semyear, co.time, co.daysofweek, " .
			"co.startdate, co.enddate, maxnumseats " .
	       "from courseoffering co, coursedesc cd " .
		"where co.coursenumber = cd.coursenumber " .
			"and co.semseason = '$season' " .
			"and co.semyear = '$year'";
} else if ($semester == "anysemester") {
	if ($coursenumsearchspec == "isexactly") {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber = '$coursenumber'";
	} else if ($coursenumsearchspec == "startswith") {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber like '$coursenumber%'";
	} else if ($coursenumsearchspec == "endswith") {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber like '%$coursenumber'";
	} else {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber like '%$coursenumber%'";
	}	//end if
} else {
/* The user entered information in both the semester and course
number text boxes. */
	if ($coursenumsearchspec == "isexactly") {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber = '$coursenumber' " .
				"and co.semseason = '$season' " .
				"and co.semyear = '$year'";
	} else if ($coursenumsearchspec == "startswith") {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber = '$coursenumber%' " .
				"and co.semseason = '$season' " .
				"and co.semyear = '$year'";
	} else if ($coursenumsearchspec == "endswith") {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber = '%$coursenumber' " .
				"and co.semseason = '$season' " .
				"and co.semyear = '$year'";
	} else {
		$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, cd.credithours, " .
				"co.semseason, co.semyear, co.time, co.daysofweek, " .
				"co.startdate, co.enddate, maxnumseats " .
		       "from courseoffering co, coursedesc cd " .
			"where co.coursenumber = cd.coursenumber " .
				"and cd.coursenumber like '%$coursenumber%' " .
				"and co.semseason = '$season' " .
				"and co.semyear = '$year'";
	}	//end if
}	//end if

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

//Display the course offering information for every course offering
echo "<table border=1>";
echo "<tr> <th>Update Information</th> <th>Course Sequence ID</th> <th>Course Number</th> <th>Course Title</th>
	<th>Credit Hours</th> <th>Semester</th> <th>Year</th> <th>Time of Day</th> 
	<th>Days of Week</th> <th>Start Date</th> <th>End Date</th>
	<th>Maximum Seats</th> <th>Seats Available</th> </tr>";

// fetch the result from the cursor one by one
while (OCIFetchInto ($cursor, $values)){
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

  	echo "<tr> <td><FORM name=\"enroll\" method=\"POST\" " .
  		"action=\"enroll_student_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag&clientid=$clientid&courseseqid=$courseseqid&maxnumseats=$maxnumseats\"> <input type=\"submit\" name=\"submit\" value=\"Enroll\"> </FORM></td> <td>$courseseqid</td> <td>$coursenumber</td> <td>$coursetitle</td> <td>$credithrs</td> <td>$semseason</td> <td>$semyear</td> <td>$timeofday</td> <td>$daysofweek</td> <td>$startdate</td> <td>$enddate</td> <td>$maxnumseats</td> <td>$availableseats</td> </tr>";
}	//end while

echo "</table> <br /> <br />";

//Commit the result.
OCICommit ($connection);

echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");

//Close Oracle connection.
OCILogoff ($connection);
?>
