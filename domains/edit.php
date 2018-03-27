<?php

require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST') {
    $query = "UPDATE domains SET domain_name='".$_REQUEST['domain_name']."',host='".$_REQUEST['host']."' WHERE id='".mysqli_real_escape_string($dba->link_id, $_REQUEST['id'])."'";
    $dba->query($query);
    
    $_SESSION['flash'] = "Entry Nr. ".$_POST['id']." updated.";
    header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/list.php");
    
    exit(0);
}

$query = "SELECT * FROM domains WHERE id='".$_REQUEST['id']."' LIMIT 1";
$result = $dba->query($query);
$row = $dba->fetch_assoc($result);
?>
<html>
<head>
<title>Edit</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" />

</head>
<body>

<form action="edit.php" method="POST">
<table border="0">
<input type="hidden" value="<?= $row['id'] ?>" name="id">
<tr>
	<td>domain</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['domain_name'] ?>" name="domain_name">
</td>
</tr>
<tr>
	<td>domain admin</td>
	<td><select name="host">
<?php
$query = "SELECT * FROM hosts ORDER BY host_name";
$results = $dba->query($query);
while ($rowi = $dba->fetch_assoc($results)) {
    if ($rowi['id_host'] == $row['host']) {
        $selected = ' selected';
    } else {
        $selected = '';
    }
    echo("<option value='".$rowi['id_host']."'$selected>".$rowi['host_name']."</option>");
}
?>
</select>
<a href="../hosts/new.php" target="_new">New FK</a></td>
</tr>

</table>
<br />
<input type="submit" value="Ok"> | <input type="reset" value="Reset"> | <a href="list.php">Back</a> | <a href="new.php">Create new Entry</a>
</form>

</body>
</html>
