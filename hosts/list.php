<?php


require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

if(IsSet($_GET['offset'])) {
	$offset = $_GET['offset'];
} else {
	$offset = 0;
}

$list_limit = 30;
switch($_REQUEST['order']) {
	case 'name_dsc':
		$order_clause = " ORDER BY host_name DESC";
		$order_link_arg_head = "name_asc";
		$order_link_arg = "name_dsc";
		break;
	case 'notes':
	case 'notes_asc':
		$order_clause = " ORDER BY notes ASC";
		$order_link_arg_head = "notes_dsc";
		$order_link_arg = "notes_asc";
		break;
	case 'notes_dsc':
		$order_clause = " ORDER BY notes DESC";
		$order_link_arg_head = "notes_asc";
		$order_link_arg = "notes_dsc";
		break;
	default:
		$order_clause = " ORDER BY host_name ASC";
		$order_link_arg_head = "name_dsc";
		$order_link_arg = "name_asc";
}


$query = "SELECT * FROM hosts".$order_clause." LIMIT ".$offset.",".$list_limit;
$query_c = "SELECT count(*) FROM hosts";

$num = $dba->query_first($query_c);
if($num[0] > 0) { 
	$num_result_pages = $num[0] / $list_limit;
} else {
	$num_result_pages = 1;
	$query = "SELECT * FROM hosts".$order_clause;
}
$results = $dba->query($query);
?>
<html>
<head>
<title>List</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" /> 

</head>
<body>
<?php
if(IsSet($_SESSION['flash'])) {
	echo("<div id=\"flash\"><b>Notice:</b><br />\n");
	echo($_SESSION['flash']);
	unset($_SESSION['flash']);
	echo("</div>\n");
}
?>

<table border="1">
<?php
if(substr($order_link_arg_head,0,-4) == 'name') {
	$ola=$order_link_arg_head;
} else {
	$ola='name';
}
?>
<th><a href="list.php?order=<?= $ola ?>">name</a></th>
<?php
if(substr($order_link_arg_head,0,-4) == 'notes') {
	$ola=$order_link_arg_head;
} else {
	$ola='notes';
}
?>
<th><a href="list.php?order=<?= $ola ?>">notes</a></th>
<th> </th></tr>
<?php 
while($row = $dba->fetch_assoc($results)) {
echo("<tr>\n");
	echo("\t<td><a href='edit.php?id_host=".$row['id_host']."'>".$row['host_name']."</a></td>\n");
	echo("\t<td>".substr(htmlentities($row['notes']),0,80)." ...</td>\n");
	echo("\t<td><a href=\"edit.php?id_host=".$row['id_host']."\">Edit</a> <a href=\"delete.php?id_host=".$row['id_host']."\">Delete</a></td>");
	echo("</tr> ");
}
?>
</table>
<br />

</table>
<br />
<!-- Navigation //-->
<?php
if($num > $list_limit_per_page) {
	if($offset > 0) {
		$newoffset = max(0,($offset-$list_limit));
		echo("<a href=\"list.php?offset=".$newoffset."&order=".$order_link_arg."\">&laquo; Previous</a> | ");
	} else {
		echo("&laquo; Previous | ");
	}
	for($i = 0; $i < $num_result_pages; $i++) {
		$newoffset = $i * $list_limit;
		if($offset == $newoffset) {
			echo("<b>".($i+1)."</b> | ");
		} else {
			echo("<a href=\"list.php?offset=".$newoffset."&order=".$order_link_arg."\">".($i+1)."</a> | ");
		}
	}
	$newoffset = $offset + $list_limit;
	if($newoffset < $num[0]) {
		echo("<a href=\"list.php?offset=".$newoffset."&order=".$order_link_arg."\">Next &raquo;</a>");
	} else {
		echo("Next &raquo;");
	}
}
?>
<br />
<br />
<a href="new.php">Create new Entry</a>

</body>
</html>
