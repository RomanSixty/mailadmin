<?php


require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

if (isset($_GET['offset'])) {
    $offset = $_GET['offset'];
} else {
    $offset = 0;
}

$list_limit = 30;
switch ($_REQUEST['order']) {
    case 'domain_dsc':
        $order_clause = " ORDER BY domain_name DESC";
        $order_link_arg_head = "domain_asc";
        $order_link_arg = "domain_dsc";
        break;
    case 'mail_host':
    case 'mail_host_asc':
        $order_clause = " ORDER BY host_name ASC";
        $order_link_arg_head = "mail_host_dsc";
        $order_link_arg = "mail_host_asc";
        break;
    case 'mail_host_dsc':
        $order_clause = " ORDER BY host_name DESC";
        $order_link_arg_head = "mail_host_asc";
        $order_link_arg = "mail_host_dsc";
        break;
    default:
        $order_clause = " ORDER BY domain_name ASC";
        $order_link_arg_head = "domain_dsc";
        $order_link_arg = "domain_asc";
}


$query = "SELECT * FROM domains d LEFT JOIN hosts h ON d.host=h.id_host".$order_clause." LIMIT ".$offset.",".$list_limit;
$query_c = "SELECT count(*) FROM domains d";

$num = $dba->query_first($query_c);
if ($num[0] > 0) {
    $num_result_pages = $num[0] / $list_limit;
} else {
    $num_result_pages = 1;
    $query = "SELECT * FROM domains d LEFT JOIN hosts h ON d.host=h.id_host".$order_clause;
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
if (isset($_SESSION['flash'])) {
    echo("<div id=\"flash\"><b>Notice:</b><br />\n");
    echo($_SESSION['flash']);
    unset($_SESSION['flash']);
    echo("</div>\n");
}
?>

<table border="1">
<?php
if (substr($order_link_arg_head, 0, -4) == 'domain') {
    $ola=$order_link_arg_head;
} else {
    $ola='domain';
}
?>
<th><a href="list.php?order=<?= $ola ?>">domain</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'mail_host') {
    $ola=$order_link_arg_head;
} else {
    $ola='mail_host';
}
?>
<th><a href="list.php?order=<?= $ola ?>">domain admin</a></th>
<th> </th></tr>
<?php
while ($row = $dba->fetch_assoc($results)) {
    echo("<tr>\n");
    echo("\t<td><a href='edit.php?id=".$row['id']."'>".$row['domain_name']."</a></td>\n");
    echo("\t<td>".$row['host_name']."</td>\n");
    echo("\t<td><a href=\"edit.php?id=".$row['id']."\">Edit</a> <a href=\"delete.php?id=".$row['id']."\">Delete</a></td>");
    echo("</tr> ");
}
?>
</table>
<br />

</table>
<br />
<!-- Navigation //-->
<?php
if ($num > $list_limit_per_page) {
    if ($offset > 0) {
        $newoffset = max(0, ($offset-$list_limit));
        echo("<a href=\"list.php?offset=".$newoffset."&order=".$order_link_arg."\">&laquo; Previous</a> | ");
    } else {
        echo("&laquo; Previous | ");
    }
    for ($i = 0; $i < $num_result_pages; $i++) {
        $newoffset = $i * $list_limit;
        if ($offset == $newoffset) {
            echo("<b>".($i+1)."</b> | ");
        } else {
            echo("<a href=\"list.php?offset=".$newoffset."&order=".$order_link_arg."\">".($i+1)."</a> | ");
        }
    }
    $newoffset = $offset + $list_limit;
    if ($newoffset < $num[0]) {
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
