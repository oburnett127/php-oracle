<HTML>
<HEAD>
<TITLE>Add a Student</TITLE>
</HEAD>
<BODY>
<H1>Add a Student</H1>
<?

//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

//Get the student's personal information.
echo ("Enter Student Personal Information");
echo ("<br />");
echo("<FORM name=\"add_student\" method=\"POST\" " .
	"action=\"add_student_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">" .
	"First name: <input type=\"text\" name=\"firstname\" size=\"8\" maxlength=\"12\"> <br />" .
	"Last name:  <input type=\"text\" name=\"lastname\" size=\"12\" maxlength=\"16\"> <br />" .
	"Age: <input type =\"text\" name=\"age\" size =\"3\" maxlength =\"3\"> <br />" .
	"Street address: <input type =\"text\" name=\"streetaddress\" size =\"20\"" .
		"maxlength =\"35\"> <br />" .
	"City: <input type =\"text\" name=\"city\" size =\"15\" maxlength =\"20\"> <br />" .
	"State: <input type =\"text\" name=\"state\" size =\"2\" maxlength =\"2\"> <br />" .
	"Zipcode: <input type =\"text\" name=\"zipcode\" size =\"9\" maxlength =\"9\"> <br />" .
	"Student type: " . "<select name=\"studenttype\">" .
			   "<option value=\"undergraduate\">Undergraduate</option>" .
			   "<option value=\"graduate\">Graduate</option> </select>" . "<br /> <br />" .
	"Probation status: " . "<select name=\"probationstatus\">" .
			   "<option value=\"onprobation\">Good Standing</option>" .
			   "<option value=\"goodstanding\">Probational Student</option> </select>" . "<br /> <br />" .
	"<input type=\"submit\" name=\"submit\" value=\"Confirm Student Personal Information\">" .
	"</FORM>");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag" .
		"&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
