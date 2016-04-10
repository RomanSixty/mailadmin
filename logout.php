<?php
include_once("_static.session.inc.php");

remove_session();

header ( 'Location: http://' . $_SERVER [ 'HTTP_HOST' ] .
        rtrim ( dirname ( $_SERVER [ 'PHP_SELF' ] ), '/\\' ) . '/login.php' );
?>