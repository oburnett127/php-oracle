<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$firstname = $_POST["firstname"];
$fnamesearchspec = $_POST["fnamesearchspec"];
$lnamesearchspec = $_POST["lnamesearchspec"];
$coursenumsearchspec = $_POST["coursenumsearchspec"];
$sidsearchspec = $_POST["sidsearchspec"];
$lastname = $_POST["lastname"];
$studentid = $_POST["studentid"];
$coursenum = $_POST["coursenumber"];
$studenttype = $_POST["studenttype"];
$probationstatus = $_POST["probationalstatus"];
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

/* Search for students based on the information entered in the first
name, last name, studentid, coursenumber text boxes and on the
selections made in the  studenttype, and probationstatus drop-down
lists on the searchstudents form of "search_students.php".  It is
possible that one of many SQL transactions will be executed
determined by the user entering information in only one of the text
boxes, in all of the text boxes, or in any combination of the text
boxes.  It is also possible that the user will not enter information
in any of the text boxes, in which case a message is displayed
informing the user that they need to enter information in at least
one of the text boxes to get a list of students to appear in the
search results. */

/* $notfirstcondition specifies whether a condition being concatenated
to the where clause of the following query is the first condition.  
If the condition is not the first condition then the word "and" should
be concatenated to the where clause before the condition. */
$notfirstcondition = false;	

$sql = "select s.studentid, fname, lname, age, " .
		"streetaddress, city, state, zipcode, " .
		"status, graduateflag " .
	"from student s, coursestaken ct, courseoffering co";
	
if ($studentid <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	if ($sidsearchspec == "isexactly") $sql = "$sql" . " s.studentid = '$studentid'";
	else if ($sidsearchspec == "startswith") $sql = "$sql" . " s.studentid like '$studentid%'";
	else if ($sidsearchspec == "endswith") $sql = "$sql" . " s.studentid like '%$studentid'";
	else if ($sidsearchspec == "contains") $sql = "$sql" . " s.studentid like '%$studentid%'";
	$notfirstcondition = true;
}	//end if

if ($firstname <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	if ($fnamesearchspec == "isexactly") $sql = "$sql" . " fname = '$firstname'";
	else if ($fnamesearchspec == "startswith") $sql = "$sql" . " fname like '$firstname%'";
	else if ($fnamesearchspec == "endswith") $sql = "$sql" . " fname like '%$firstname'";
	else if ($fnamesearchspec == "contains") $sql = "$sql" . " fname like '%$firstname%'";
	$notfirstcondition = true;
}	//end if

if ($lastname <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	if ($lnamesearchspec == "isexactly") $sql = "$sql" . " lname = '$lastname'";
	else if ($lnamesearchspec == "startswith") $sql = "$sql" . " lname like '$lastname%'";
	else if ($lnamesearchspec == "endswith") $sql = "$sql" . " lname like '%$lastname'";
	else if ($lnamesearchspec == "contains") $sql = "$sql" . " lname like '%$lastname%'";
	$notfirstcondition = true;
}	//end if

if ($age <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	$sql = "$sql" . " age = '$age'";
	$notfirstcondition = true;
}	//end if

if ($streetaddress <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	$sql = "$sql" . " streetaddress = '$streetaddress'";
	$notfirstcondition = true;
}	//end if

if ($city <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	$sql = "$sql" . " city = '$city'";
	$notfirstcondition = true;
}	//end if

if ($state <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	$sql = "$sql" . " state = '$state'";
	$notfirstcondition = true;
}	//end if

if ($zipcode <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	$sql = "$sql" . " zipcode = '$zipcode'";
	$notfirstcondition = true;
}	//end if

if ($coursenum <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	if ($coursenumsearchspec == "isexactly") $sql = "$sql" . " s.studentid = ct.studentid" .
		" and ct.courseseqid = co.courseseqid and co.coursenumber = '$coursenum'";
	else if ($coursenumsearchspec == "startswith") $sql = "$sql" . " s.studentid = ct.studentid" .
		" and ct.courseseqid = co.courseseqid and co.coursenumber like '$coursenum%'";
	else if ($coursenumsearchspec == "endswith") $sql = "$sql" . " s.studentid = ct.studentid" .
		" and ct.courseseqid = co.courseseqid and co.coursenumber like '%$coursenum'";
	else if ($coursenumsearchspec == "contains") $sql = "$sql" . " s.studentid = ct.studentid" .
		" and ct.courseseqid = co.courseseqid and co.coursenumber like '%$coursenum%'";
	$notfirstcondition = true;
}	//end if

if ($probationstatus <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	if ($probationstatus == "probationalstudent") $probationflag = 1;
	else $probationflag = 0;
	$sql = "$sql" . " status = '$probationflag'";
	$notfirstcondition = true;
}	//end if

if ($studenttype <> NULL) {
	if ($notfirstcondition == true) $sql = "$sql" . " and";
	else $sql = "$sql" . " where";
	if ($studenttype == "graduate") $graduatestatus = 1;
	else $graduatestatus = 0;
	$sql = "$sql" . " graduateflag = '$graduatestatus'";
	$notfirstcondition = true;
}	//end if

echo("<br /> $sql <br />");

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

/* Display the student information for student records contained in the
result of the query. */
echo "<table border=1>";
echo "<tr> <th>Student ID</th> <th>First Name</th>
	<th>Last Name</th> <th>Age</th> <th>Street Address</th> <th>City</th>
	<th>State</th> <th>Zip Code</th> <th>Graduate Status</th>
	 <th>Probational Status</th> </tr>";

//Fetch the result from the cursor one by one.
while (OCIFetchInto ($cursor, $values)){
  $studentid = $values[0];
  $fname = $values[1];
  $lname = $values[2];
  $age = $values[3];
  $streetaddress = $values[4];
  $city = $values[5];
  $state = $values[6];
  $zipcode = $values[7];
  $gradflag = $values[8];
  $probation = $values[9];

  //The graduate status should appear in a form readable to users.
  if ($gradflag == 1) $gradstatus = "graduate";
  else $gradstatus = "undergraduate";

  //The probation status should appear in a form readable to users.
  if ($probation == 1) $probationstatus = "probational";
  else $probationstatus = "good standing";

  echo "<tr>
  <td>$studentid</td> <td>$fname</td> <td>$lname</td>
  <td>$age</td> <td>$streetaddress</td> <td>$city</td> <td>$state</td>
  <td>$zipcode</td> <td>$gradstatus</td> <td>$probationstatus</td>" .
  "</tr>";
}	//end while

echo "</table>";
echo("<br />");
echo"<FORM name=\"update_student method=\"POST\" action=\"update_student.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\" value=\"Update Student\">" .
  "Student ID: <input type=\"text\" name=\"studentid\" size=\"8\" maxlength=\"8\">" .
  "<input type=\"submit\" name=\"submit\" value=\"Update Student Information\" </FORM> <br /> <br />";

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

/* Display link to search_students page to start a new search,
a link to the welcome page, and a link to logout. */
echo("Click <A HREF = \"search_students.php?sessionid=$sessionid
	&studentflag=$studentflag&adminflag=$adminflag\">here</A> to start a new search.");
echo("<br />");
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid
	&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page.");
echo("<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
