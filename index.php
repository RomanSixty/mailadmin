<?php
include_once("_class.dba.inc.php");
include_once("_conf.dba.inc.php");
include_once("_static.session.inc.php");
validate_session();
?>
<html>
<head>
	<title>Mail Admin</title>
	<meta name="robots" content="noindex">
	<meta name="robots" content="nofollow">
</head>

<frameset rows="1*" cols="150, 80%" frameborder="0" framespacing="0" border="0">
	<frame name="contents" scrolling="auto" marginwidth="10"
        marginheight="14" target="detail" src="navigation.php" frameborder="0">
	<frame name="detail" scrolling="yes" marginwidth="10" marginheight="14" src="accounts/list.php" frameborder="0">
	<noframes>
		<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
			<p>This interface uses frames to enable a good navigation. Sadly you're using a browser not capable of
			displaying frames. You can try to use the interface this way, it should work. Forget about the banner frame,
			it just displays status information. Select the desired subpage at the navigation frame and do whatever you want.</p>
		</body>
	</noframes>
</frameset>
</html>
