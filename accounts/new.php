<?php

require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

function vcrypt($clear)
{
    // generate a password for exim and courier
    //$salt = join '', ('.','/', 0..9,'A'..'Z', 'a'..'z')[rand 64, rand 64];
    //$vcrypt = crypt($clear, $salt)."\n";
    //We need a Standard DES-Hash so we need to set a 2-Char salt
    $salt = "he";
    $vcrypt = crypt($clear, $salt);
    return $vcrypt;
}

if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST') {
    $pwcrypt = vcrypt($_POST['pwclear']);

    if (!isset($_POST['is_away'])) {
        $_POST['is_away'] = 0;
    }
    if (!isset($_POST['spam_check'])) {
        $_POST['spam_check'] = "yes";
    }
    if (!isset($_POST['spam_purge'])) {
        $_POST['spam_purge'] = "no";
    }
    if (!isset($_POST['virus_check'])) {
        $_POST['virus_check'] = "no";
    }
    
    $query = "INSERT INTO accounts (local_part,domain,forward,cc,name,pwclear,pwcrypt,is_away,away_subject,away_text,spam_check,spam_purge,virus_check,is_enabled,created_at,updated_at)
                 VALUES ('".mysqli_real_escape_string($dba->link_id, $_REQUEST['local_part'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['domain'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['forward'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['cc'])
                       ."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['name'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['pwclear'])."','".mysqli_real_escape_string($dba->link_id, $pwcrypt)."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['is_away'])
                       ."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['away_subject'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['away_text'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['spam_check'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['spam_purge'])
                       ."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['virus_check'])."','".mysqli_real_escape_string($dba->link_id, $_REQUEST['is_enabled'])."','".time()."','".time()."')";
    $dba->query($query);
    
    $_SESSION['flash'] = "New Entry Nr. ".$dba->insert_id()." created.";
    header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/list.php");
    
    exit(0);
}

?>
<html>
<head>
<title>New</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" />
</head>
<body> 

<form action="new.php" method="POST">
<table border="0">
<tr>
	<td>email address</td>
	<td><input type="text" size="50" maxlength="255" value="" name="local_part"> @
		<select name="domain"><option></option>
<?php
if ($_SESSION [ 'admin' ]) {
    $query = "SELECT * FROM domains ORDER BY domain_name ASC";
} else {
    $query = "SELECT * FROM domains LEFT JOIN hosts h ON host=id_host WHERE id_host=" . $_SESSION [ 'user' ] . " ORDER BY domain_name ASC";
}
$results = $dba->query($query);
while ($row = $dba->fetch_assoc($results)) {
    if ($row['id'] == $_SESSION [ 'domain_filter' ]) {
        $selected = ' selected';
    } else {
        $selected = '';
    }
    echo("<option value='".$row['id']."'$selected>".$row['domain_name']."</option>
");
}
?>
</select>
<?php if ($_SESSION [ 'admin' ]) { ?>
<a href="../domains/new.php">new domain</a>
<?php } ?>
</td>
</tr>
<tr>
	<td>deliver to</td>
	<td>
		<input type="text" size="50" maxlength="255" value="" name="forward"><br/>
		leave empty to deliver to a virtual mailbox (will be created if it doesn't exist yet)<br/>
		<em>&lt;username&gt;</em> delivers locally to an existing system user account<br/>
		<em>&lt;username@domain.ext&gt;</em> forwards<br/>
		use <em>spamtrap</em> for unused accounts that only receive spam mails
	</td>
</tr>
<tr>
	<td>cc</td>
	<td><input type="text" size="50" maxlength="255" value="" name="cc"></td>
</tr>
<tr>
	<td>comment</td>
	<td><input type="text" size="50" maxlength="255" value="" name="name"></td>
</tr>
<tr>
	<td>password</td>
	<td><input type="text" size="50" maxlength="255" value="" name="pwclear">
</td>
</tr>
<tr>
	<td>is away</td>
	<td>
		<select name="is_away">
		<option value="1">yes</option>
		<option value="0" selected="selected">no</option>
		</select>
	</td>
</tr>
<tr>
	<td>away subject</td>
	<td><input type="text" size="50" maxlength="255" value="Abwesenheitsmeldung" name="away_subject"></td>
</tr>
<tr>
	<td>away text</td>
	<td><textarea name="away_text" cols="80" rows="24">Ich bin derzeit nicht erreichbar.</textarea>
</td>
</tr>
<tr>
	<td>spam check</td>
	<td>
		<select name="spam_check">
		<option value="yes">yes</option>
		<option value="no">no</option>
		</select>
	</td>
</tr>
<tr>
	<td>spam purge</td>
	<td>
		<select name="spam_purge">
		<option value="yes">yes</option>
		<option value="no" selected="selected">no</option>
		</select><br>
		immediately discards messages classified as spam with a score higher than 8
	</td>
</tr>
<tr>
	<td>virus check</td>
	<td>
		<select name="virus_check">
		<option value="yes">yes</option>
		<option value="yes">no</option>
		</select>
	</td>
</tr>
<tr>
	<td>is enabled</td>
	<td>
		<select name="is_enabled">
		<option value="yes">yes</option>
		<option value="no">no</option>
		</select>
	</td>
</tr>

</table>
<br />
<input type="submit" value="Ok"> | <input type="reset" value="Reset"> | <a href="list.php">Back</a> | <a href="new.php">Create new Entry</a> </form>

</body>
</html> 
