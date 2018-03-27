<?php

require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST') {
    $query = "INSERT INTO domains (domain_name,host) VALUES ('".mysqli_real_escape_string($dba->link_id, $_REQUEST['domain_name'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['host'])."')";
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
	<td>domain</td>
	<td><input type="text" size="50" maxlength="255" value="" name="domain_name">
</td>
</tr>
<tr>
	<td>domain admin</td>
	<td>
		<select name="host">
<?php
$query = "SELECT * FROM hosts ORDER BY host_name";
$results = $dba->query($query);
while ($row = $dba->fetch_assoc($results)) {
    echo("<option value='".$row['id_host']."'>".$row['host_name']."</option>
");
}
?>
		</select>
		<a href="../hosts/new.php">New FK</a>
	</td>
</tr>

</table>
<br />
<input type="submit" value="Ok"> | <input type="reset" value="Reset"> | <a href="list.php">Back</a> | <a href="new.php">Create new Entry</a> </form>

</body>
</html> 
