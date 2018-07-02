<?
// Contains commonly used functions.


//********************
// Run the sql, and return the error flag and the cursor in an array
// The array index "flag" contains the flag.
// The array index "cursor" contains the cursor.
//********************
function execute_sql_in_oracle($sql) {
  putenv("ORACLE_HOME=/home/oracle/OraHome1");
  putenv("ORACLE_SID=orcl");

  $connection = OCILogon ("gq004", "ldlwog");
  if($connection == false){
    // failed to connect
    echo OCIError($connection)."<BR>";
    die("Failed to connect");
  }	//end if

  $cursor = OCIParse($connection, $sql);

  if ($cursor == false) {
    echo OCIError($cursor)."<BR>";
    OCILogoff ($connection);
    // sql failed 
    die("SQL Parsing Failed");
  }	//end if

  $result = OCIExecute($cursor);

  // commit the result
  //OCICommit ($connection);

  // close the connection with oracle
  OCILogoff ($connection);  

  $return_array["flag"] = $result;
  $return_array["cursor"] = $cursor;

  return $return_array;
}	//end of function execute_sql_in_oracle

//********************
// Verify the session id.  
// Return normally if it is verified.
// Terminate the script otherwise.
//********************
function verify_session($sessionid) {
  // lookup the sessionid in the session table to ascertain the clientid 
  $sql = "select clientid " .
    "from clientsession " .
    "where sessionid='$sessionid'";  

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  $result = OCIExecute($cursor);
  if ($result == false) {
    echo OCIError($cursor)."<BR>";
    die("SQL Execution problem.");
  }	//end if

  if(!OCIFetchInto ($cursor, $values)) {
    // no active session - clientid is unknown
    die("Invalid client!");
  }	//end if
}	//end function verify_session

//********************
// Takes an executed errored oracle cursor as input.
// Display an initerpreted error message.
//********************
function display_oracle_error_message($cursor) {
  $err = OCIError($cursor);
  echo "<BR />";
  echo "Oracle Error Code: " . $err['code'] . "<BR />";
  echo "Oracle Error Message: " . $err['message'] . "<BR />" . "<BR />";
  
  if ($err['code'] == 1)
    echo("Duplicate Values.  <BR /><BR />");
  else if ($err['code'] == 984 or $err['code'] == 1861 
    or $err['code'] == 1830 or $err['code'] == 1839 or $err['code'] == 1847
    or $err['code'] == 1858 or $err['code'] == 1841)
    echo("Wrong type of value entered.  <BR /><BR />");
  else if ($err['code'] == 1400 or $err['code'] == 1407)
    echo("Required field not correctly filled.  <BR /><BR />");
  else if ($err['code'] == 2292)
    echo("Child records exist.  Need to delete or update them first.  <BR /><BR />");
}	//end of function display_oracle_error_message

function get_number_of_available_seats($connection, $courseseqid, $maxnumseats) {
  //Determine how many seats have already been taken.
  $sql = "select count(*) " .
    "from coursestaken " .
    "where courseseqid = '$courseseqid'";

  $cursor = OCIParse ($connection, $sql);

  if ($cursor == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }	//end if

  $result = OCIExecute ($cursor);

  if ($result == false) {
	echo OCIError($cursor)."<BR>";
	exit;
  }	//end if

  OCIFetchInto ($cursor, $values);

  //Subtract the number of seats that have already been taken from
  //the maximum number of seats for the course to determine the number
  //of seats available.
  $seatstaken = $values[0];
  $seatsavailable = $maxnumseats - $seatstaken;

  return $seatsavailable;
}	//end function get_number_of_available_seats 

function check_prerequisites($connection, $clientid, $coursetoenroll) {
	$meetsprereq = true;

	$sql =	"create or replace view courseprerequisite (prereq) as " .
		"select pq.prereqcourse " .
		"from prerequisite pq, courseoffering co " .
		"where pq.coursenumber=co.coursenumber and co.coursenumber='$coursetoenroll'";

	$cursor = OCIParse ($connection, $sql);

	if ($cursor == false) {
		echo OCIError($cursor)."<BR>";
		exit;
	}	//end if

	$result = OCIExecute ($cursor);

	if ($result == false) {
	  echo OCIError($cursor)."<BR>";
	  exit;
	}	//end if
		
	//Find prerequisite courses the student has not taken.
	$sql = "select prereq " .
		"from courseprerequisite cq " .
		"where not exists(select co.coursenumber " .
					"from student s, coursestaken ct, courseoffering co " .
					"where s.clientid='$clientid' and s.studentid=ct.studentid and " .
						"ct.courseseqid=co.courseseqid and cq.prereq=co.coursenumber)";

	$cursor = OCIParse ($connection, $sql);

	if ($cursor == false) {
		echo OCIError($cursor)."<BR>";
		exit;
	}	//end if

	$result = OCIExecute ($cursor);

	if ($result == false) {
	  echo OCIError($cursor)."<BR>";
	  exit;
	}	//end if

	if (OCIFetchInto ($cursor, $values)) {
		$reasonforfailure = $reasonforfailure . "The student has not taken the following prerequisite courses: " .
		$meetsprereq = false;
	}	//end if

	$firstinlist = true;

	while (OCIFetchInto ($cursor, $values)) {
		if ($firstinlist == false) $reasonforfailure = $reasonforfailure . ", "; 
		$firstinlist = false;
		$prequisite = $values[0];
		$reasonforfailure = $reasonforfailure . $prequisite;
	}	//end while

	$firstinlist = true;

	//Find prerequisite courses in which the student has earned a grade of F
	$sql = "select prereq " .
		"from courseprerequisite cq " .
		"where not exists(select co.coursenumber " .
					"from student s, coursestaken ct, courseoffering co " .
					"where s.clientid='$clientid' and s.studentid=ct.studentid and " .
						"ct.courseseqid=co.courseseqid and cq.prereq=co.coursenumber " .
						"and (ct.grade='F' or ct.grade='f'))";

	$cursor = OCIParse ($connection, $sql);

	if ($cursor == false) {
		echo OCIError($cursor)."<BR>";
		exit;
	}	//end if

	$result = OCIExecute ($cursor);

	if ($result == false) {
	  echo OCIError($cursor)."<BR>";
	  exit;
	}	//end if

	if (OCIFetchInto ($cursor, $values)) {
		$reasonforfailure = $reasonforfailure . "\n\nThe student enrolled in but did not earn a passing grade in " .
							"the following courses: ";
		$meetsprereq = false;
	}	//end if

	$firstinlist = true;

	while (OCIFetchInto ($cursor, $values)) {
		if ($firstinlist == false) $reasonforfailure = $reasonforfailure . ", "; 
		$firstinlist = false;
		$prequisite = $values[0];
		$reasonforfailure = $reasonforfailure . $prequisite;
	}	//end while

	echo ($reasonforfailue);	

	return $meetsprereq;
}	//end of function check_prerequisites
?>
