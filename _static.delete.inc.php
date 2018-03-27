<?php

$id = mysql_real_escape_string($_REQUEST [ $_id ]);

if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST') {
    $dba -> query('DELETE FROM ' . $realm . ' WHERE ' . $_id . '=' . $id . ' LIMIT 1');
    
    $_SESSION['flash'] = 'Entry number ' . $id . ' deleted.';
    
    header('Location: http://' . $_SERVER [ 'HTTP_HOST' ] .
                rtrim(dirname($_SERVER [ 'PHP_SELF' ]), '/\\') . '/list.php');
    
    exit(0);
}

?>
<html>
<head>
<title>Delete</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" />
</head>
<body>
<?php
$row = $dba -> query_first('SELECT * FROM ' . $realm . ' WHERE ' . $_id . '=' . $id . ' LIMIT 1');
?>
<p>Do you really want to delete Entry Nr. <?= $id ?> (<?= $row[$_name] ?>)?</p>
<form action="delete.php" method="POST">
<input type="hidden" name="<?= $_id ?>" value="<?= $id ?>"/>
<input type="submit" value="Yes"/>
<a href="list.php">No</a>
</form>
</body>
</html>