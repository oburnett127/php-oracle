<HTML>
<HEAD>
<TITLE>Search for Courses</TITLE>
</HEAD>
<BODY>
<H1>Search for Courses</H1>
<?
//include utility functions
include "utility_functions.php";

//Get the sessionid and clientid and 
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

echo ("Search for courses by course number and/or by semester.");
echo ("<br /> <br />");
echo ("<FORM name=\"search_courses\" method=\"POST\" " .
	"action=\"search_courses_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag
		&clientid=$clientid\">" .
	"Course Number:  <input type=\"text\" name=\"coursenumber\" size=\"9\" maxlength=\"9\">" .
"		<select name=\"coursenumsearchspec\">
		<option value=\"isexactly\">is exactly</option>
		<option value=\"startswith\">starts with</option>
		<option value=\"endswith\">ends with</option>
		<option value=\"contains\">contains</option> </select> <br />" .
	"Semester: <select name=\"semester\">
		   <option value=\"spring2012\">Spring 2012</option>
		   <option value=\"fall2011\">Fall 2011</option>
		   <option value=\"summer2011\">Summer 2011</option>
		   <option value=\"spring2011\">Spring 2011</option>
		   <option value=\"anysemester\">Any Semester</option> </select>" .
	"<br /> <br />" .
	"<input type=\"submit\" name=\"submit\" value=\"Search for Courses\">" .
	"</FORM>");

echo ("<br />");
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag&clientid=$clientid\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);
?>
</BODY>
</HTML>
