<HTML>
<HEAD>
<TITLE>Add Student</TITLE>
</HEAD>
<BODY>
<?
//include utility functions
include "utility_functions.php";

//Get the sessionid and the personal information of the
//student being added to the system.
$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$age = $_POST["age"];
$streetaddress = $_POST["streetaddress"];
$city = $_POST["city"];
$state = $_POST["state"];
$zipcode = $_POST["zipcode"];
$probationstatus = $_POST["probationstatus"];
$studenttype = $_POST["studenttype"];

//Verify the sessionid.
verify_session($sessionid);

/* function generate_studentid generates a studentid to be used
in processing enrollment and grade information.  The format of
a student id is XX123456, where XX is the student's initials
and 123456 represents a sequence number. The new student
sequence number should be the current maximum sequence number
in the database plus one. */
function generate_studentid() {
  //Find the current maximum studentid in the student table.
  $sql = "select studentid " .
    "from Student s1 " .
    "where not exists(select studentid " .
			"from Student s2 " .
			"where cast(substr(s2.studentid, 3) as number(6)) > cast(substr(s1.studentid, 3) as number(6)))";

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  $result = OCIExecute($cursor);
  if ($result == false){
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }

  OCIFetchInto ($cursor, $values);

  //Cast the sequence number part of the current maximum studentid
  //to integer data type.  Add one to generate sequence number for
  //new studentid.
  $currentmaxseqnumber = substr($values[0], 3);
  $newseqnumber = $currentmaxseqnumber + 1;

  $newstudentid = substr($firstname, 1, 1) . substr($lastname, 1, 1) . $newseqnumber;
  
  return $newstudentid;
}	//end function generate_studentid

//Environment set up.
putenv("ORACLE_HOME=/home/oracle/OraHome1");
putenv("ORACLE_SID=orcl");

//Connect to Oracle
$connection = OCILogon ("gq004", "ldlwog");

if ($connection == false){
   echo OCIError($connection)."<BR>";
   exit;
}	//end if

$newstudentid = generate_studentid();

//Insert the student's personal information into the student table.
$sql2 = "insert into student ".
	"values ('$studentid', '$clientid', '$firstname', '$lastname', " .
			"'$age', '$streetaddress', '$city', '$state', " .
			"'$zipcode', '$status', '$studenttype')";

$result_array = execute_sql_in_oracle ($sql2);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  	  <form method=\"post\" action=\"add_client?sessionid=$sessionid&clientid=$clientid\">
	  <input type=\"hidden\" value = \"$clientid\" name=\"clientid\">
	  <input type=\"hidden\" value = \"$password\" name=\"password\">
	  <input type=\"hidden\" value = \"$newclienttype\" name=\"clienttype\">
	  <input type=\"hidden\" value = \"$firstname\" name=\"firstname\">
	  <input type=\"hidden\" value = \"$lastname\" name=\"lastname\">
	  <input type =\"hidden\" value = \"$age\" name=\"age\">
	  <input type =\"hidden\" value = \"$streetaddress\" name=\"streetaddress\">
	  <input type =\"hidden\" value = \"$city\" name=\"city\">
	  <input type =\"hidden\" value = \"$state\" name=\"state\">
	  <input type =\"hidden\" value = \"$zipcode\" name=\"zipcode\">
	  <input type =\"hidden\" value = \"$studenttype\" name=\"studenttype\">
	  <input type =\"hidden\" value = \"$probationstatus\" name =\"probationstatus\">
  
  	  Read the error message, and then try again:
  	  <input type=\"submit\" value=\"Go Back\">
  	  </form>

  	  </i>
  ");
}	//end if

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//The record has been inserted.  List the user back to the welcome page.
Header("Location:welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag");
?>
</BODY>
</HTML>
