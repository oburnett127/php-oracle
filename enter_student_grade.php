<HTML>
<HEAD>
<TITLE>Enter Student Grade</TITLE>
</HEAD>
<BODY>
<H1>Enter Student Grade</H1>
<?
//include utility functions
include "utility_functions.php";

//Get the sessionid and clientid and 
$sessionid = $_GET["sessionid"];
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

echo ("<br />");
echo("<FORM name=\"enter_student_grade\" method=\"POST\" " .
	"action=\"enter_student_grade_action.php?sessionid=$sessionid&studenflag=$studentflag&adminflag=$adminflag\">" .
	"Student ID: <input type=\"text\" name=\"studentid\" size=\"8\" maxlength=\"8\"> <br />" .
	"Course Sequence ID:  <input type=\"number\" name=\"coursesequenceid\" size=\"5\" maxlength=\"5\"> <br />" .
	"Grade: <input type =\"text\" name=\"grade\" size =\"1\" maxlength =\"1\">" .
	"<br /> <br />" .
	"<input type=\"submit\" name=\"submit\" value=\"Confirm Grade Entry\">" .
	"</FORM>");

echo ("<br />");
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);
?>
</BODY>
</HTML>
