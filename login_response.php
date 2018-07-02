<?
//Get the clientid and password from the login.html page.
$clientid = $_POST["clientid"];
$password = $_POST["password"];

//Set up environment.
putenv("ORACLE_HOME=/home/oracle/OraHome1");
putenv("ORACLE_SID=orcl");

//Establish connection with Oracle.
$connection = OCILogon ("gq004", "ldlwog");
if($connection == false){
  // failed to connect
  echo OCIError($connection)."<BR>";
  die("Failed to connect");
}	//end if

//The connetion is good.  Select the clientid if the clientid
//exists in the client table and the provided password is
//contained in the same tuple as the clientid.
$sql = "select clientid, studentflag, adminflag " .
      "from client " .
      "where clientid='$clientid' and password='$password'";

$cursor = OCIParse($connection, $sql);

if ($cursor == false) {
  echo OCIError($cursor)."<BR>";
  OCILogoff ($connection);
  // query failed - login impossible
  die("Client Query Failed");
}	//end if

//The query is good.  If any rows are contained in the result
//set then we know that the client was found in the client
//table and the provided password matches the password for
//that client.
$result = OCIExecute($cursor);
if ($result == false){
  echo OCIError($cursor)."<BR>";
  OCILogoff($connection);
  die("Client Query Failed");
}	//end if

if(!OCIFetchInto ($cursor, $values)){
  OCILogoff ($connection);
  // client username not found
  die ("Client not found.");
}	//end if

//The client has been found.  Get the clientid and the clienttype.
$clientid = $values[0];
$studentflag = $values[1];
$adminflag = $values[2];

// create a new session for the client
$sessionid = md5(uniqid(rand()));

// store the link between the sessionid and the clientid
// and when the session started in the clientsession table
$sql = "insert into clientsession " .
  "(sessionid, clientid, sessiondate) " .
  "values ('$sessionid', '$clientid', sysdate)";

$cursor = OCIParse($connection, $sql);

if($cursor == false){
  echo OCIError($cursor)."<BR>";
  OCILogoff ($connection);
  // insert Failed
  die ("Failed to create a new session");
}	//end if

$result = OCIExecute($cursor);
if ($result == false){
  //a new session was not created
  echo OCIError($cursor)."<BR>";
  OCILogoff($connection);
  die("Failed to create a new session");
}	//end if

// insert OK - we have created a new session
OCILogoff ($connection);

// jump to welcome page
Header("Location:welcomepage.php?sessionid=$sessionid&studentflag=$studentflag&adminflag=$adminflag&clientid=$clientid");
?>
