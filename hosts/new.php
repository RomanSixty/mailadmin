<?php

require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST') {
    $query = "INSERT INTO hosts (host_name,passwd,notes)
	          VALUES ('".mysql_real_escape_string($_REQUEST['host_name'])."','".
                         md5($_REQUEST['passwd'])."','".
                         mysql_real_escape_string($_REQUEST['notes'])."')";
    $dba->query($query);
    
    $_SESSION['flash'] = "New Entry Nr. ".$dba->insert_id()." created.";
    header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/list.php");
    
    exit(0);
}

?>
<html>
<head>
<title>New</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" />

</head>
<body> 

<form action="new.php" method="POST">
<table border="0">
<tr>
	<td>name</td>
	<td><input type="text" size="50" maxlength="255" value="" name="host_name"></td>
</tr>
<tr>
	<td>passwd</td>
	<td><input type="text" size="50" maxlength="20" value="" name="passwd"></td>
</tr>
<tr>
	<td>admin</td>
	<td>
		<select name="admin">
		<option value="no">no</option>
		<option value="yes">yes</option>
		</select>
	</td>
</tr>
<tr>
	<td>notes</td>
	<td><textarea name="notes" cols="80" rows="24"></textarea></td>
</tr>

</table>
<br />
<input type="submit" value="Ok"> | <input type="reset" value="Reset"> | <a href="list.php">Back</a> | <a href="new.php">Create new Entry</a> </form>

</body>
</html> 