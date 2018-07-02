<HTML>
<HEAD>
<TITLE>Search User Information</TITLE>
</HEAD>
<BODY>
<H1>Search User Information</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

/* Generate the content of the Search Client Information form here.  Display text field on the form 
for the User ID (clientid) allowing administrative/student clients and administrators who are not
students to search for clients by clientid.  Display drop down allowing user to search for client
records by the clienttype.  The search criteria are passed to search_client_action.php */
echo("<FORM name=\"search_client\" method=\"POST\" " .
"action=\"search_client_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">" .
"User ID: <input type=\"text\" name=\"clientid\" size=\"8\" maxlength=\"8\">" . "<br />" .
"User Type: " . "<select name=\"clienttypesearchmethod\">
			<option value=\"student\">Student</option>
			<option value=\"administrator\">Administrator</option>
			<option value=\"studentadministrator\">Student Administrator</option> </select>" .
"<br /> <br />" .
"<input type=\"submit\" name=\"submit\" value=\"Search User Information\">" .
"</FORM>");

echo("<br />");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
