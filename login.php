<?php
include_once("_class.dba.inc.php");
include_once("_conf.dba.inc.php");
include_once("_static.session.inc.php");

if ( $_SERVER [ 'REQUEST_METHOD' ] == 'POST' )
{
	$res = $dba -> query ( 'SELECT id_host, admin FROM hosts
							WHERE host_name="' . mysql_real_escape_string ( $_POST [ 'name' ] ) . '"
							AND passwd="' . md5 ( $_POST [ 'passwd' ] ) . '"' );
	
	if ( $dba -> num_rows() == 1 )
	{
		$row = $dba -> fetch_assoc ( $res );

		$_SESSION [ 'user'  ] = $row [ 'id_host' ];
		$_SESSION [ 'admin' ] = ( $row [ 'admin' ] == 'yes' ) ? true : false;
		
		header ( 'Location: http://' . $_SERVER [ 'HTTP_HOST' ] .
	            rtrim ( dirname ( $_SERVER [ 'PHP_SELF' ] ), '/\\' ) . '/index.php' );
	}

}

?>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/default.css" />

</head>
<body>
<form action="login.php" method="POST" target="_top">
<table border="0">
<input type="hidden" value="<?= $row['id'] ?>" name="id">
<tr>
	<td>name</td>
	<td><input type="text" size="50" maxlength="255" value="" name="name"></td>
</tr>
<tr>
	<td>passwd</td>
	<td><input type="password" size="50" maxlength="255" value="" name="passwd"></td>
</tr>
</table>
<br />
<input type="submit" value="Login">
</form>
</body>
</html>