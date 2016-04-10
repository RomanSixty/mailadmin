<?php
require_once("_class.dba.inc.php");
require_once("_conf.dba.inc.php");
require_once("_static.session.inc.php");
validate_session();
?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Navigation</title>
       <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
       <link type="text/css" href="default.css" rel="stylesheet"/>
       <base target="detail">
</head>

<body class="nav" text="#000000" link="#0000A0" vlink="#0000A0" alink="#0000A0">
<div class="nav">
    <a href="accounts/list.php" target="detail">Mail Accounts</a><br />
<?php if ( $_SESSION [ 'admin' ] ) { ?>
    <a href="domains/list.php" target="detail">Domains</a><br />
    <a href="hosts/list.php" target="detail">Admins</a><br />
<?php } ?>
    <br />
    <br />
    <a href="logout.php" target="_top">Logout</a>
</div>
</body>
</html>
