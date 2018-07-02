<HTML>
<HEAD>
<TITLE>Add/Delete/Update User Information</TITLE>
</HEAD>
<BODY>
<H1>Add/Delete/Update User Information</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

//content of page is generated here
?>
<H2>Add User</H2>
<?
echo("<FORM name=\"add_client\" method=\"POST\" 
		action=\"add_client.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">
	User ID: <input type=\"text\" name=\"clientid\" size=\"8\" maxlength=\"8\"> <br />
	Password: <input type=\"password\" name=\"password\" size=\"12\" maxlength=\"20\"> <br />
	User Type: " . "<select name=\"newclienttype\">
			<option value=\"student\">Student</option>
			<option value=\"administrator\">Administrator</option>
			<option value=\"studentadministrator\">Student Administrator</option> </select>
	<br /> <br />
	<input type=\"submit\" name=\"submit\" value=\"Confirm Add User\"> </FORM>");

echo("<br />");
?>
<H2>Delete User</H2>
<?

echo("<FORM name=\"delete_client\" method=\"POST\" 
		action=\"delete_client.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">
	User ID: <input type=\"text\" name=\"clientid\" size=\"8\" maxlength=\"8\">
	<br /> <br />
	<input type=\"submit\" name=\"submit\" value=\"Confirm Delete User\"> </FORM>");

echo("<br />");
?>
<H2>Update User Information</H2>
<?

echo("<FORM name=\"update_client\" method=\"POST\" 
		action=\"update_client.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">
	Current User ID: <input type=\"text\" name=\"currentclientid\" size=\"8\" maxlength=\"8\"> <br />
	New User ID: <input type=\"text\" name=\"newclientid\" size=\"8\" maxlength=\"8\"> <br />
	New User Type: " . "<select name=\"newclienttype\">
			<option value=\"student\">Student</option>
			<option value=\"administrator\">Administrator</option>
			<option value=\"studentadministrator\">Student Administrator</option> </select>
	<br /> <br />
	<input type=\"submit\" name=\"submit\" value=\"Confirm Update User Information\"> </FORM>");

echo("<br />");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\"
	>here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
