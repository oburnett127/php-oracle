<HTML>
<HEAD>
<TITLE>Courses I am Taking/have Taken</TITLE>
</HEAD>
<BODY>
<H1>Courses I am Taking/have Taken</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$clientid = $_GET["clientid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

//Environment set up.
putenv("ORACLE_HOME=/home/oracle/OraHome1");
putenv("ORACLE_SID=orcl");

//Connect to Oracle
$connection = OCILogon ("gq004", "ldlwog");

if ($connection == false) {
   echo OCIError($connection)."<BR>";
   exit;
}	//end if

function get_student_id($connection) {
  $sql = "select studentid " .
	 "from student " .
	 "where clientid='$clientid'";  

  $cursor = OCIParse ($connection, $sql);

  if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }  	//end if

  $result = OCIExecute($cursor);

  if ($result == false) {
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }	//end if


  OCIFetchInto ($cursor, $values);
  $studentid = $values[0];


  return $studentid;
}	//end of function get_student_id 

function get_number_of_courses_completed($connection) {
  $sql = "select count(*) " .
	 "from coursestaken " .
	 "where studentid = '$studentid'";  

  $cursor = OCIParse ($connection, $sql);

  if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }  	//end if

  $result = OCIExecute($cursor);

  if ($result == false) {
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }	//end if
  
  $numberofcourses = 0;

  if (OCIFetchInto ($cursor, $values)) {
	$numberofcourses = $values[0];
  }	//end if

  return $numberofcourses;
} 	//end of function get_number_of_courses_completed

function get_total_credit_hours_earned($connection) {
  //add the credit hours earned for all courses in which the student earned
  //either an A, B, C, or D.  Other possible values that may be entered for
  //grade could include F,  I (incomplete), or W (withdrawal).
  $sql = "select sum(credithours) " .
	 "from student s, coursestaken ct, courseoffering co, coursedesc cd " .
	 "where s.studentid = '$studentid' and s.studentid = ct.studentid " .
	    "and ct.courseseqid = co.courseseqid " .
	    "and co.coursenumber = cd.coursenumber " .
	    "and grade = 'A' or grade = 'B' or grade = 'C' or grade = 'D'";

  $cursor = OCIParse ($connection, $sql);

  if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }  	//end if

  $result = OCIExecute($cursor);

  if ($result == false) {
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }	//end if

  $totalcredithoursearned = 0;

  if (OCIFetchInto ($cursor, $values)) {
	$totalcredithoursearned = $values[0];
  }	//end if

  return $totalcredithoursearned;
}	//end of function get_total_credit_hours_earned
 
function calculate_credit_hours_attempted($connection) {
  //Attempted credit hours include credit hours associated with
  //courses the student took for which a grade of A, B, C, D, or F
  //was earned.  Credit hours associated with grades of W or I are
  //not included.
  $sql = "select sum(credithours) " .
	 "from student s, coursestaken ct, courseoffering co, coursedesc cd " .
	 "where s.studentid = '$studentid' and s.studentid = ct.studentid " .
		"and ct.courseseqid = co.courseseqid " .
		"and co.coursenumber = cd.coursenumber " .
		"and grade = 'A' or grade = 'B' or grade = 'C' or grade = 'D' " .
		"or grade = 'F'";

  $cursor = OCIParse ($connection, $sql);

  if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }  	//end if

  $result = OCIExecute($cursor);

  if ($result == false) {
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }	//end if

  $credithoursattempted = 0;

  if (OCIFetchInto ($cursor, $values)) {
	$credithoursattempted = $values[0];
  }	//end if

  return $credithoursattempted;
}	//end of function calculate_credit_hours_attempted 

function calculate_gpa($connection) {
  //calculate the quality points earned for the student's performance.
  $sql = "select sum(grade * credithours) as qualitypoints " .
	 "from student s, coursestaken ct, courseoffering co, coursedesc cd " .
	 "where s.studentid = '$studentid' and s.studentid = ct.studentid " .
		"and ct.courseseqid = co.courseseqid " .
		"and co.coursenumber = cd.coursenumber";
  $cursor = OCIParse ($connection, $sql);

  if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }  	//end if

  $result = OCIExecute($cursor);

  if ($result == false) {
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }	//end if

  $gpa = 0.000;

  if (OCIFetchInto ($cursor, $values)) {
	$gpa = $values[0];
  }	//end if

  $credithoursattempted = calculate_credit_hours_attempted($connection);

  //Determine the gpa using the quality points and the credit hours attempted.
  $values[0] = $values[0] / $credithoursattempted;

  return $gpa;
} 	//end of function calculate_gpa

$studentid = get_student_id($connection);

//Display the student's course information for the studentid
//corresponding to the clientid of the currently logged in user
$sql = "select co.courseseqid, cd.coursenumber, cd.coursetitle, co.semseason, co.semyear, " .
	"credithours, grade " .
	"from student s, coursestaken ct, courseoffering co, coursedesc cd " .
	"where s.studentid='$studentid' and s.studentid = ct.studentid " .
		"and ct.courseseqid = co.courseseqid and co.coursenumber = cd.coursenumber";

$cursor = OCIParse ($connection, $sql);

if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
}	//end if

$result = OCIExecute ($cursor);

if ($result == false){	//Query was not successful.
  echo OCIError($cursor)."<BR>";
  exit;
}	//end if

$totalcoursescompleted = get_number_of_courses_completed($connection);
$totalcredithoursearned = get_total_credit_hours_earned($connection);
$gpa = calculate_gpa($connection);

//Display the course information for all courses the student has
//taken or is currently taking.
echo "<table border=1>";
echo "<tr> <th>Course Sequence ID</th> <th>Course Number</th> <th>Course Title</th>
	<th>Season</th> <th>Year</th> <th>Credit Hours</th> <th>Grade</th> </tr>";

//Fetch the result from the cursor
while(OCIFetchInto ($cursor, $values)) {
  $courseseqid = $values[0];
  $coursenumber = $values[1];
  $coursetitle = $values[2];
  $season = $values[3];
  $year = $values[4];
  $credithours = $values[5];
  $grade = $values[6];

  echo "<tr> <td>$courseseqid</td>  <td>$coursenumber</td> <td>$coursetitle</td>
	<td>$season</td> <td>$year</td> <td>$credithours</td> <td>$grade</td> </tr>";
}	//end while

echo "</table>";
echo("<br />");

echo "<table border=1>";
echo "<tr> <th>Total Number of Courses Completed</th> <th>Total Credit Hours Earned</th>
	<th>GPA</th> </tr>";

echo "<tr> <td>$totalcoursescompleted</td> <td>$totalcredithoursearned</td> 
	<td>$gpa</td> </tr>";

echo "</table>";
echo("<br />");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=
	$studentflag&adminflag=$adminflag&clientid=$clientid\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
