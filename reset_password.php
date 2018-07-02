<HTML>
<HEAD>
<TITLE>Reset Password</TITLE>
</HEAD>
<BODY>
<H1>Reset Password</H1>
<?
//include utility functions
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];

//Verify the sessionid.
verify_session($sessionid);

//Generate the content of the Reset User Password form here.  Display text field on the form
//for the User ID (clientid).  Display a button used to
//pass client information to reset__password_action.php, the button says "Confirm Password Reset".
echo("<FORM name=\"reset_user_password\" method=\"POST\"
	 action=\"reset_password_action.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag\">" .
"User ID: <input type=\"text\" name=\"clientid\" size=\"8\" maxlength=\"8\">" .
"<br /> <br />" .
"<input type=\"submit\" name=\"submit\" value=\"Confirm Password Reset\">" .
"</FORM>");

echo("<br />");

//Display link to the welcome page and a link to logout.
echo("Click <A HREF = \"welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag
	\">here</A> to return to the welcome page." . "<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
