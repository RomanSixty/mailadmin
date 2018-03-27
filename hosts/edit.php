<?php

require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST') {
    $query = "UPDATE hosts SET host_name='".$_REQUEST['host_name']."',notes='".$_REQUEST['notes']."' WHERE id_host='".mysqli_real_escape_string($dba->link_id, $_REQUEST['id_host'])."'";
    $dba->query($query);
    
    $_SESSION['flash'] = "Entry Nr. ".$_POST['id_host']." updated.";
    header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/list.php");
    exit(0);
}

$query = "SELECT * FROM hosts WHERE id_host='".$_REQUEST['id_host']."' LIMIT 1";
$row = $dba->query_first($query);
?>
<html>
<head>
<title>Scaffoldr - Edit</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" />

</head>
<body>

<form action="edit.php" method="POST">
<table border="0">
<input type="hidden" value="<?= $row['id_host'] ?>" name="id_host">
<tr>
	<td>name</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['host_name'] ?>" name="host_name">
</td>
</tr>
<tr>
	<td>notes</td>
	<td><textarea name="notes" cols="80" rows="24"><?= $row['notes'] ?></textarea>
</td>
</tr>

</table>
<br />
<input type="submit" value="Ok"> | <input type="reset" value="Reset"> | <a href="list.php">Back</a> | <a href="new.php">Create new Entry</a>
</form>

</body>
</html>
