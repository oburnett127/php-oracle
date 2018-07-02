<?
//include utility functions
include "utility_functions.php";

$sessionid =$_GET["sessionid"];

//Verify the sessionid.
verify_session($sessionid);


//Good connection.  The session should now be deleted.
$sql = "delete from clientsession where sessionid = '$sessionid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  echo OCIError($cursor)."<BR>";
  die("Session removal failed");
}

//Go to the login page.
header("Location:login.html");
?>
