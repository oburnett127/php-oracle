<HTML>
<HEAD>
<TITLE>Update Student Information</TITLE>
</HEAD>
<BODY>
<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$studentid = $_GET["studentid"];

//Verify the sessionid.
verify_session($sessionid);

//Get the student's personal information that needs to be updated.
echo ("Enter current student information in fields for which the system has old information.");
echo ("<br />");
echo("<FORM name=\"add_student\" method=\"POST\" " .
	"action=\"update_student_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag&studentid=$studentid" .
	"First name: <input type=\"text\" name=\"currentfirstname\" size=\"8\" maxlength=\"12\"> <br />" .
	"Last name:  <input type=\"text\" name=\"currentlastname\" size=\"12\" maxlength=\"16\"> <br />" .
	"Student ID: <input type=\"text\" name=\"currentstudentid\" size=\"8\" maxlength=\"8\"> <br />" .
	"Age:  <input type=\"number\" name=\"currentage\" size=\"3\" maxlength=\"3\"> <br />" .
	"Street address:  <input type=\"text\" name=\"currentstreetaddress\" size=\"25\" maxlength=\"35\"> <br />" .
	"City:  <input type=\"text\" name=\"currentcity\" size=\"16\" maxlength=\"20\"> <br />" .
	"State:  <input type=\"text\" name=\"currentstate\" size=\"2\" maxlength=\"2\"> <br />" .
	
	"Zip code: <input type=\"text\" name=\"currentzipcode\" size=\"9\" maxlength=\"9\"> <br />" .
	"Student type: <input type =\"text\" name=\"currentstudenttype\" size =\"1\"" .
		"maxlength =\"1\"> <br />" .
	"Probation status: <input type =\"text\" name=\"currentprobationstatus\" size =\"1\"" .
		"maxlength =\"1\"> <br />" .
	"<input type=\"submit\" name=\"submit\" value=\"Update Student Personal Information\">" .
	"</FORM>");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag" .
		"&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
