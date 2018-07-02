<HTML>
<HEAD>
<TITLE>Change Password</TITLE>
</HEAD>
<BODY>
<H1>Change Password</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

//Generate the content of the Change Password form here.  Display text fields on the form
//for the User ID (clientid), Old Password, and New Password.  Display a button used to
//pass client information to change_password_action.php, the button says "Confirm
//Password Change".
echo("<FORM name=\"change_password\" method=\"POST\" action=\"change_password_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">" .
"User ID: <input type=\"text\" name=\"clientid\" size=\"8\" maxlength=\"8\"> <br />" .
"Old Password: <input type=\"password\" name=\"oldpassword\" size=\"12\" maxlength=\"20\"> <br />" .
"New Password: <input type=\"password\" name=\"newpassword\" size=\"12\" maxlength=\"20\">" .
"<br /> <br />" .
"<input type=\"submit\" name=\"submit\" value=\"Confirm Password Change\">" .
"</FORM>");

echo("<br />");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
