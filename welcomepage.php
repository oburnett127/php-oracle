<HTML>
<HEAD>
<TITLE>Student Enrollment Information System</TITLE>
</HEAD>
<BODY>
<H1>Student Enrollment Information System</H1>
<?
//include utility functions
include "utility_functions.php";

//get the sessionid and the clienttype
$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$clientid = $_GET["clientid"];

//verify the sessionid
verify_session($sessionid);

//The session has been verified, the user is a legal user.
echo("<br />");

//The welcome page content is generated here.
//Display a link to search for course offerings based on semester and/or partial course number.
echo("<UL>
<LI><A HREF=\"search_courses.php?sessionid=$sessionid&studentflag=
	$studentflag&adminflag=$adminflag&clientid=$clientid\">Search for Courses</A></LI>
</UL>");

//Display a link to display a list of all course offerings.
echo("<br />" . "<br />");
echo("<UL>
<LI><A HREF=\"list_all_courses.php?sessionid=$sessionid&studentflag=
	$studentflag&adminflag=$adminflag&clientid=$clientid\">List All Courses</A></LI>
</UL>");

//Display a link to display a list of general information for all course offerings.
echo("<br />" . "<br />");
echo("<UL>
<LI><A HREF=\"display_general_course_info.php?sessionid=$sessionid&studentflag=
	$studentflag&adminflag=$adminflag&clientid=$clientid\">Display General Course Information</A></LI>
</UL>");

if($studentflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
	<LI><A HREF=\"display_student_course.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Display My Courses</A></LI>
	</UL>");
}	//end if

//If the client is a student or a student administrator
//display a link to list all personal information for the student.
if($studentflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
	<LI><A HREF=\"display_student_personal.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Display My Personal Information</A></LI>
	</UL>");
}	//end if

//If the client is an administrator or a student administrator
//display a link to search for students.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"search_students.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Search for Students</A></LI>
  	</UL>");
}	//end if

//If the client is an administrator or a student administrator
//display a link to list all students.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"list_all_students.php?sessionid=$sessionid&studentflag=" .
		"$studentflag&adminflag=$adminflag&clientid=$clientid\">List All Students</A></LI>" .
  	"</UL>");
}	//end if

//If the client is an administrator or a student administrator
//display a link to enter a student grade.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"enter_student_grade.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Enter a Student Grade</A></LI>
  	</UL>");
}	//end if

//Display a link to change the client's own password.
echo("<br />" . "<br />");
echo("<UL>
  <LI><A HREF=\"change_password.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Change Your Password</A></LI>
  </UL>");

//If the client is an administrator or a student administrator
//display a link to reset another client's password.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"reset_password.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Reset User Password</A></LI>
  	</UL>");
}	//end if

//If the client is an administrator or a student administrator
//display link to search client information.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"search_client.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Search User Information</A></LI>
  	</UL>");
}	//end if

//If the client is an administrator or a student administrator
//display link to list all client information.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"list_all_clients.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">List All User Information</A></LI>
  	</UL>");
}	//end if

//If the client is an administrator or a student administrator
//display link to add or delete or update client information.
if($adminflag == 1) {
	echo("<br />" . "<br />");
	echo("<UL>
  	<LI><A HREF=\"add_delete_update_client.php?sessionid=$sessionid&studentflag=
		$studentflag&adminflag=$adminflag&clientid=$clientid\">Add/Delete/Update User Information</A></LI>
  	</UL>");
}	//end if

echo("<br />");
echo("<br />");
echo("Click <A HREF = \"logout_response.php?sessionid=$sessionid\">here</A> to logout.");
?>
</BODY>
</HTML>
