<?php
require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

$join = 'LEFT JOIN domains d ON a.domain=d.id LEFT JOIN hosts h ON d.host=h.id_host';

if (!$_SESSION [ 'admin' ]) {
    $where = 'WHERE id_host="' . $_SESSION [ 'user' ] . '"';
}

if (isset($_REQUEST [ 'domain_filter' ])) {
    $_SESSION [ 'domain_filter' ] = $_REQUEST [ 'domain_filter' ];
}

if (!empty($_SESSION [ 'domain_filter' ])) {
    if (isset($where)) {
        $where .= ' AND d.id=' . intval($_SESSION [ 'domain_filter' ]);
    } else {
        $where = 'WHERE d.id=' . intval($_SESSION [ 'domain_filter' ]);
    }
}
    
if (isset($_GET['offset'])) {
    $offset = $_GET['offset'];
} else {
    $offset = 0;
}

$list_limit = 30;
switch ($_REQUEST['order']) {
    case 'local_part_dsc':
        $order_clause = " ORDER BY local_part DESC";
        $order_link_arg_head = "local_part_asc";
        $order_link_arg = "local_part_dsc";
        break;
    case 'domain':
    case 'domain_asc':
        $order_clause = " ORDER BY domain_name ASC, local_part ASC";
        $order_link_arg_head = "domain_dsc";
        $order_link_arg = "domain_asc";
        break;
    case 'domain_dsc':
        $order_clause = " ORDER BY domain_name DESC, local_part ASC";
        $order_link_arg_head = "domain_asc";
        $order_link_arg = "domain_dsc";
        break;
    case 'forward':
    case 'forward_asc':
        $order_clause = " ORDER BY forward ASC";
        $order_link_arg_head = "forward_dsc";
        $order_link_arg = "forward_asc";
        break;
    case 'forward_dsc':
        $order_clause = " ORDER BY forward DESC";
        $order_link_arg_head = "forward_asc";
        $order_link_arg = "forward_dsc";
        break;
    case 'cc':
    case 'cc_asc':
        $order_clause = " ORDER BY cc ASC";
        $order_link_arg_head = "cc_dsc";
        $order_link_arg = "cc_asc";
        break;
    case 'cc_dsc':
        $order_clause = " ORDER BY cc DESC";
        $order_link_arg_head = "cc_asc";
        $order_link_arg = "cc_dsc";
        break;
    case 'name':
    case 'name_asc':
        $order_clause = " ORDER BY name ASC";
        $order_link_arg_head = "name_dsc";
        $order_link_arg = "name_asc";
        break;
    case 'name_dsc':
        $order_clause = " ORDER BY name DESC";
        $order_link_arg_head = "name_asc";
        $order_link_arg = "name_dsc";
        break;
    case 'is_away':
    case 'is_away_asc':
        $order_clause = " ORDER BY is_away ASC";
        $order_link_arg_head = "is_away_dsc";
        $order_link_arg = "is_away_asc";
        break;
    case 'is_away_dsc':
        $order_clause = " ORDER BY is_away DESC";
        $order_link_arg_head = "is_away_asc";
        $order_link_arg = "is_away_dsc";
        break;
    case 'spam_check':
    case 'spam_check_asc':
        $order_clause = " ORDER BY spam_check ASC";
        $order_link_arg_head = "spam_check_dsc";
        $order_link_arg = "spam_check_asc";
        break;
    case 'spam_check_dsc':
        $order_clause = " ORDER BY spam_check DESC";
        $order_link_arg_head = "spam_check_asc";
        $order_link_arg = "spam_check_dsc";
        break;
    case 'spam_purge':
    case 'spam_purge_asc':
        $order_clause = " ORDER BY spam_purge ASC";
        $order_link_arg_head = "spam_purge_dsc";
        $order_link_arg = "spam_purge_asc";
        break;
    case 'spam_purge_dsc':
        $order_clause = " ORDER BY spam_purge DESC";
        $order_link_arg_head = "spam_purge_asc";
        $order_link_arg = "spam_purge_dsc";
        break;
    case 'virus_check':
    case 'virus_check_asc':
        $order_clause = " ORDER BY virus_check ASC";
        $order_link_arg_head = "virus_check_dsc";
        $order_link_arg = "virus_check_asc";
        break;
    case 'virus_check_dsc':
        $order_clause = " ORDER BY virus_check DESC";
        $order_link_arg_head = "virus_check_asc";
        $order_link_arg = "virus_check_dsc";
        break;
    case 'is_enabled':
    case 'is_enabled_asc':
        $order_clause = " ORDER BY is_enabled ASC";
        $order_link_arg_head = "is_enabled_dsc";
        $order_link_arg = "is_enabled_asc";
        break;
    case 'is_enabled_dsc':
        $order_clause = " ORDER BY is_enabled DESC";
        $order_link_arg_head = "is_enabled_asc";
        $order_link_arg = "is_enabled_dsc";
        break;
    case 'created_at':
    case 'created_at_asc':
        $order_clause = " ORDER BY created_at ASC";
        $order_link_arg_head = "created_at_dsc";
        $order_link_arg = "created_at_asc";
        break;
    case 'created_at_dsc':
        $order_clause = " ORDER BY created_at DESC";
        $order_link_arg_head = "created_at_asc";
        $order_link_arg = "created_at_dsc";
        break;
    case 'updated_at':
    case 'updated_at_asc':
        $order_clause = " ORDER BY updated_at ASC";
        $order_link_arg_head = "updated_at_dsc";
        $order_link_arg = "updated_at_asc";
        break;
    case 'updated_at_dsc':
        $order_clause = " ORDER BY updated_at DESC";
        $order_link_arg_head = "updated_at_asc";
        $order_link_arg = "updated_at_dsc";
        break;
    default:
        $order_clause = " ORDER BY local_part ASC";
        $order_link_arg_head = "local_part_dsc";
        $order_link_arg = "local_part_asc";
}


$query = "SELECT * FROM accounts a $join $where $order_clause LIMIT ".$offset.",".$list_limit;
$query_c = "SELECT count(id_user) AS anzahl FROM accounts a $join $where";

$num = $dba->query_first($query_c);
if ($num['anzahl'] > $list_limit) {
    $num_result_pages = $num['anzahl'] / $list_limit;
} else {
    $num_result_pages = 1;
    $query = "SELECT * FROM accounts a $join $where $order_clause";
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
<form action="list.php" method="POST">
		<select name="domain_filter" onchange="this.form.submit()"><option value="">--- show all ---</option>
<?php
if ($_SESSION [ 'admin' ]) {
    $query2 = "SELECT * FROM domains ORDER BY domain_name ASC";
} else {
    $query2 = "SELECT * FROM domains LEFT JOIN hosts h ON host=id_host WHERE id_host=" . $_SESSION [ 'user' ] . " ORDER BY domain_name ASC";
}
$results2 = $dba->query($query2);
while ($row = $dba->fetch_assoc($results2)) {
    if ($row['id'] == $_SESSION [ 'domain_filter' ]) {
        $selected = ' selected';
    } else {
        $selected = '';
    }
    echo("<option value='".$row['id']."'$selected>".$row['domain_name']."</option>");
}
?>
</select> domain filter
</form>

</select>
<table border="1">
<?php
if (substr($order_link_arg_head, 0, -4) == 'local_part') {
    $ola=$order_link_arg_head;
} else {
    $ola='local_part';
}
?>
<th><a href="list.php?order=<?= $ola ?>">local part</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'domain') {
    $ola=$order_link_arg_head;
} else {
    $ola='domain';
}
?>
<th><a href="list.php?order=<?= $ola ?>">domain</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'forward') {
    $ola=$order_link_arg_head;
} else {
    $ola='forward';
}
?>
<th><a href="list.php?order=<?= $ola ?>">deliver to</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'cc') {
    $ola=$order_link_arg_head;
} else {
    $ola='cc';
}
?>
<th><a href="list.php?order=<?= $ola ?>">cc</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'name') {
    $ola=$order_link_arg_head;
} else {
    $ola='name';
}
?>
<th><a href="list.php?order=<?= $ola ?>">comment</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'is_away') {
    $ola=$order_link_arg_head;
} else {
    $ola='is_away';
}
?>
<th><a href="list.php?order=<?= $ola ?>">is away</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'spam_check') {
    $ola=$order_link_arg_head;
} else {
    $ola='spam_check';
}
?>
<th><a href="list.php?order=<?= $ola ?>">spam check</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'spam_purge') {
    $ola=$order_link_arg_head;
} else {
    $ola='spam_purge';
}
?>
<th><a href="list.php?order=<?= $ola ?>">spam purge</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'virus_check') {
    $ola=$order_link_arg_head;
} else {
    $ola='virus_check';
}
?>
<th><a href="list.php?order=<?= $ola ?>">virus check</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'is_enabled') {
    $ola=$order_link_arg_head;
} else {
    $ola='is_enabled';
}
?>
<th><a href="list.php?order=<?= $ola ?>">enabled</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'created_at') {
    $ola=$order_link_arg_head;
} else {
    $ola='created_at';
}
?>
<th><a href="list.php?order=<?= $ola ?>">created</a></th>
<?php
if (substr($order_link_arg_head, 0, -4) == 'updated_at') {
    $ola=$order_link_arg_head;
} else {
    $ola='updated_at';
}
?>
<th><a href="list.php?order=<?= $ola ?>">updated</a></th>
<th> </th></tr>
<?php
while ($row = $dba->fetch_assoc($results)) {

    if ($row['is_enabled']=='yes') {
        echo("<tr>\n");
    } else {
        echo("<tr class=\"disabled\">\n");
    }

    echo("\t<td><a href='edit.php?id_user=".$row['id_user']."'>".$row['local_part']."</a></td>\n");
    echo("\t<td>".$row['domain_name']."</td>\n");
    echo("\t<td>".$row['forward']."</td>\n");
    echo("\t<td>".$row['cc']."</td>\n");
    echo("\t<td>".$row['name']."</td>\n");
    $away = ( $row['is_away'] ) ? 'yes' : 'no';
    echo("\t<td>".$away."</td>\n");
    echo("\t<td>".$row['spam_check']."</td>\n");
    echo("\t<td>".$row['spam_purge']."</td>\n");
    echo("\t<td>".$row['virus_check']."</td>\n");
    echo("\t<td>".$row['is_enabled']."</td>\n");
    echo("\t<td>".date("d.m.y H:i:s", $row['created_at'])."</td>\n");
    echo("\t<td>".date("d.m.y H:i:s", $row['updated_at'])."</td>\n");
    echo("\t<td><a href=\"edit.php?id_user=".$row['id_user']."\">Edit</a> <a href=\"delete.php?id_user=".$row['id_user']."\">Delete</a></td>");
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
