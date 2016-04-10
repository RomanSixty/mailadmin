<?php
require_once("../_class.dba.inc.php");
require_once("../_conf.dba.inc.php");
require_once("../_static.session.inc.php");
validate_session();

function vcrypt($clear) {
	// generate a password for exim and courier
	//$salt = join '', ('.','/', 0..9,'A'..'Z', 'a'..'z')[rand 64, rand 64];
	//$vcrypt = crypt($clear, $salt)."\n";
	//We need a Standard DES-Hash so we need to set a 2-Char salt
	$salt = "he";
	$vcrypt = crypt($clear,$salt);
	return $vcrypt;
}

if ( $_SESSION [ 'admin' ] )
	$query = "SELECT * FROM accounts WHERE id_user='".$_REQUEST['id_user']."' LIMIT 1";
else
	$query = "SELECT * FROM accounts a LEFT JOIN domains d ON a.domain=d.id LEFT JOIN hosts h ON d.host=id_host WHERE id_user='".$_REQUEST['id_user']."' AND id_host=" . $_SESSION [ 'user' ] . " LIMIT 1";

$result = $dba->query($query);

if ( $dba -> num_rows ( $result ) == 0 )
{
	header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/list.php");
	exit(0);
}

$row = $dba->fetch_assoc($result);



if ( $_SERVER [ 'REQUEST_METHOD' ] == 'POST' )
{
	// if no password is set or the new one is too short, we won't change it.
	$query = "UPDATE accounts SET forward='".$_POST['forward']."',cc='".$_POST['cc']."',name='".$_POST['name']."',";
	if(strlen($_POST['pwclear']) >= 5) {
		$_SESSION['flash'] = "Password changed.<br>\n";
		$pwcrypt = vcrypt($_POST['pwclear']);
		$query .= "pwclear='".$_POST['pwclear']."',pwcrypt='$pwcrypt',";
	} else {
		$_SESSION['flash'] = "Password not changed.<br>\n";
	}
	$query .= "is_away='".$_POST['is_away']."',away_subject='".$_POST['away_subject']."',away_text='".$_POST['away_text']."',spam_check='".$_POST['spam_check']."',spam_purge='".$_POST['spam_purge']."',virus_check='".$_POST['virus_check']."',is_enabled='".$_POST['is_enabled']."',updated_at='".time()."' WHERE id_user='".$_POST['id_user']."'";
	
	$dba->query($query);
	
	$_SESSION['flash'] = "Entry Nr. ".$_POST['id_user']." updated.";
	header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/list.php");
	exit(0);
}



?>
<html>
<head>
<title>Edit</title>
<link rel="stylesheet" type="text/css" media="screen" href="../css/default.css" />

</head>
<body>

<form action="edit.php" method="POST">
<table border="0">
<input type="hidden" value="<?= $row['id_user'] ?>" name="id_user">
<tr>
	<td>email address</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['local_part'] ?>" name="local_part" disabled="disabled"> @
	<select name="domain">
<?php
if ( $_SESSION [ 'admin' ] )
	$query = "SELECT * FROM domains ORDER BY domain_name ASC";
else
	$query = "SELECT * FROM domains LEFT JOIN hosts h ON host=id_host WHERE id_host=" . $_SESSION [ 'user' ] . " ORDER BY domain_name ASC";
$results = $dba->query($query);
while($rowi = $dba->fetch_assoc($results)) {
	if($rowi['id'] == $row['domain']) {
		$selected = ' selected';
	} else {
		$selected = '';
	}
	echo("<option value='".$rowi['id']."'$selected>".$rowi['domain_name']."</option>");
}
?>
</select>
<?php if ( $_SESSION [ 'admin' ] ) { ?>
<a href="../domains/new.php" target="_new">new domain</a>
<?php } ?></td>
</tr>
<tr>
	<td>deliver to</td>
	<td>
		<input type="text" size="50" maxlength="255" value="<?= $row['forward'] ?>" name="forward"><br/>
		leave empty to deliver to a virtual mailbox (will be created if it doesn't exist yet)<br/>
		<em>&lt;username&gt;</em> delivers locally to an existing system user account<br/>
		<em>&lt;username@domain.ext&gt;</em> forwards<br/>
		use <em>spamtrap</em> for unused accounts that only receive spam mails
	</td>
</tr>
<tr>
	<td>cc</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['cc'] ?>" name="cc"></td>
</tr>
<tr>
	<td>comment</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['name'] ?>" name="name"></td>
</tr>
<tr>
	<td>password</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['pwclear'] ?>" name="pwclear">
</td>
</tr>
<tr>
	<td>is away</td>
	<td>
		<select name="is_away">
		<option value="1"<?php if($row['is_away']==1) echo ' selected="selected"'; ?>>yes</option>
		<option value="0"<?php if($row['is_away']==0) echo ' selected="selected"'; ?>>no</option>
		</select>
	</td>
</tr>
<tr>
	<td>away subject</td>
	<td><input type="text" size="50" maxlength="255" value="<?= $row['away_subject'] ?>" name="away_subject"></td>
</tr>
<tr>
	<td>away text</td>
	<td><textarea name="away_text" cols="80" rows="24"><?= $row['away_text'] ?></textarea></td>
</tr>
<tr>
	<td>spam check</td>
	<td>
		<select name="spam_check">
		<option <?php if($row['spam_check']=='yes') echo("selected"); ?>>yes</option>
		<option <?php if($row['spam_check']=='no') echo("selected"); ?>>no</option>
		</select>
	</td>
</tr>
<tr>
	<td>spam purge</td>
	<td>
		<select name="spam_purge">
		<option <?php if($row['spam_purge']=='yes') echo("selected"); ?>>yes</option>
		<option <?php if($row['spam_purge']=='no') echo("selected"); ?>>no</option>
		</select><br>
		immediately discards messages classified as spam with a score higher than 8
	</td>
</tr>
<tr>
	<td>virus check</td>
	<td>
		<select name="virus_check">
		<option <?php if($row['virus_check']=='yes') echo("selected"); ?>>yes</option>
		<option <?php if($row['virus_check']=='no') echo("selected"); ?>>no</option>
		</select>
	</td>
</tr>
<tr>
	<td>is enabled</td>
	<td>
		<select name="is_enabled">
		<option <?php if($row['is_enabled']=='yes') echo("selected"); ?>>yes</option>
		<option <?php if($row['is_enabled']=='no') echo("selected"); ?>>no</option>
		</select>
	</td>
</tr>

</table>
<br />
<input type="submit" value="Ok"> | <input type="reset" value="Reset"> | <a href="list.php">Back</a> | <a href="new.php">Create new Entry</a>
</form>

</body>
</html>
