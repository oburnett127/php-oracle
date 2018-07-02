<?
//include utility functions
include "utility_functions.php";

//Get the sessionid, clientid, studentid, courseseqid, and grade.
$sessionid = $_GET["sessionid"];
$studentid = $_POST["studentid"];
$courseseqid = $_POST["coursesequenceid"];
$grade = $_POST["grade"];
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

//Insert the grade the student earned, the courseseqid, and studentid
//into table coursestaken.
$sql = "insert into coursestaken ".
	"values ('$studentid', '$courseseqid', '$grade')";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\"
	 action=\"enter_student_grade.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">

  <input type=\"hidden\" value = \"$studentid\" name=\"studentid\">
  <input type=\"hidden\" value = \"$courseseqid\" name=\"courseseqid\">
  <input type=\"hidden\" value = \"$grade\" name=\"grade\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//The record has been inserted.  List the user back to the welcome page.
Header("Location:welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag");
?>
