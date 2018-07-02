<?
//include utility functions
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
$studentflag = $_GET["studentflag"];
$adminflag = $_GET["adminflag"];
$studentid = $_POST["studentid"];
$currentfirstname = $_POST["currentfirstname"];
$currentlastname = $_POST["currentlastname"];
$currentstudentid = $_POST["currentstudentid"];
$currentage = $_POST["currentage"];
$currentstreetaddress = $_POST["currentstreetaddress"];
$currentcity = $_POST["currentcity"];
$currentstate = $_POST["currentstate"];
$currentzipcode = $_POST["currentzipcode"];
$currentstudenttype = $_POST["currentstudenttype"];
$currentprobationstatus = $_POST["currentprobationstatus"];

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

function get_studentid_to_update() {
	
}	//end of function locate_studentid_to_update

/* The administrator can update the information for
the student corresponding to the student record selected on
the "list_all_students.php" or "search_students.php" pages.
One SQL transaction will be executed to update the student's 
information.  The information the administrator
enters or does not enter on the "update_student.php" page 
will determine how the SQL transaction will be modified. */

/* $firstattribute is used to indicate the first attribute specified in
the following update SQL statement for the purpose of inserting the word
"set" into the proper place in the SQL statement. */
$firstattribute = false;

$studentidtoupdate = get_studentid_to_update();

$sql =  "update student";
	
if ($currentfirstname <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "fname = '$currentfirstname'";
}	//end if

if ($currentlastname <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "lname = '$currentlastname'";
}	//end if

if ($currentstudentid <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "studentid = '$currentstudentid'";
}	//end if

if ($currentage <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "age = '$currentage'";
}	//end if

if ($currentstreetaddress <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "streetaddress = '$currentstreetaddress'";
}	//end if

if ($currentcity <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "city = '$currentcity'";
}	//end if

if ($currentstate <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "state = '$currentstate'";
}	//end if

if ($currentzipcode <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "zipcode = '$currentzipcode'";
}	//end if

if ($currentstudenttype <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "fname = '$currentfirstname'";
}	//end if

if ($currentprobationstatus <> "")
	if ($firstattribute == true) $sql = "$sql" . " set ";
	else $sql = "$sql" . ", ";
	$sql = "$sql" . "status = '$currentprobationstatus'";
}	//end if

$sql = "$sql" . " where studentid = '$studentidtoupdate'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  	  <form method=\"post\"
		 action=\"update_student.php?sessionid=$sessionid&studentflag=$studentflag
			&adminflag=$adminflag&studentids=$studentids&radiobuttons=$radiobuttons\">
	  <input type=\"hidden\" value = \"$currentfirstname\" name=\"currentfirstname\">
	  <input type=\"hidden\" value = \"$currentlastname\" name=\"currentlastname\">
	  <input type=\"hidden\" value = \"$currentstudentid\" name=\"currentstudentid\">
	  <input type=\"hidden\" value = \"$currentage\" name=\"currentage\">
	  <input type=\"hidden\" value = \"$currentstreetaddress\" name=\"currentstreetaddress\">
	  <input type=\"hidden\" value = \"$currentcity\" name=\"currentcity\">
	  <input type=\"hidden\" value = \"$currentstate\" name=\"currentstate\">
	  <input type=\"hidden\" value = \"$currentzipcode\" name=\"currentzipcode\">
	  <input type=\"hidden\" value = \"$currentstudenttype\" name=\"currentstudenttype\">
	  <input type=\"hidden\" value = \"$currentprobationstatus\" name=\"currentprobationstatus\">
  
  	  Read the error message, and then try again:
  	  <input type=\"submit\" value=\"Go Back\">
  	  </form>

  	  </i>
  ");
}	//end if

//Commit the result.
OCICommit ($connection);

//Close Oracle connection.
OCILogoff ($connection);

//The record has been updated.  List the user back to the welcome page.
Header("Location:welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag");
?>
