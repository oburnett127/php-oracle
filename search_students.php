<HTML>
<HEAD>
<TITLE>Search for Students</TITLE>
</HEAD>
<BODY>
<H1>Search for Students</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

/* Generate the content of the search students form here.  Display text fields on the form
allowing administrative/student clients and administrators who are not students to search
for students.  The administrator can select a student from the search results and
update the student's information.  The administrator can search by name, id, course number,
student type, and probation status.  The search criteria are passed to 
search_students_action.php */
echo("<FORM name=\"searchstudents\" method=\"POST\" " .
"action=\"search_students_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">" .
"First name: <input type=\"text\" name=\"firstname\" size=\"8\" maxlength=\"12\">" .
"		<select name=\"fnamesearchspec\">
		<option value=\"isexactly\">is exactly</option>
		<option value=\"startswith\">starts with</option>
		<option value=\"endswith\">ends with</option>
		<option value=\"contains\">contains</option> </select> <br />" .
"Last name: <input type=\"text\" name=\"lastname\" size=\"12\" maxlength=\"16\">" .
"		<select name=\"lnamesearchspec\">
		<option value=\"isexactly\">is exactly</option>
		<option value=\"startswith\">starts with</option>
		<option value=\"endswith\">ends with</option>
		<option value=\"contains\">contains</option> </select> <br />" .
"Student ID: <input type=\"text\" name=\"studentid\" size=\"8\" maxlength=\"8\">" .
"		<select name=\"sidsearchspec\">
		<option value=\"isexactly\">is exactly</option>
		<option value=\"startswith\">starts with</option>
		<option value=\"endswith\">ends with</option>
		<option value=\"contains\">contains</option> </select> <br />" .
"Course number: <input type=\"text\" name=\"coursenumber\" size=\"9\" maxlength=\"9\">" .
"		<select name=\"coursenumsearchspec\">
		<option value=\"isexactly\">is exactly</option>
		<option value=\"startswith\">starts with</option>
		<option value=\"endswith\">ends with</option>
		<option value=\"contains\">contains</option> </select> <br />" .
"Student type: <select name=\"studenttype\">
		    <option value=\"undergraduate\">Undergraduate</option>
		    <option value=\"graduate\">Graduate</option> </select>" . 
"<br />" .
"Probation status: <select name=\"probationalstatus\">
		    <option value=\"goodstanding\">Good Standing</option>
		    <option value=\"probationalstudent\">Probational Student</option> </select>" . "<br /> <br />" .
"<input type=\"submit\" name=\"submit\" value=\"Search for Students\">" .
"</FORM>");

echo("<br />");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
