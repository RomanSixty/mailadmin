<?php
session_start();

$path  = explode('/', dirname($_SERVER [ 'PHP_SELF' ]));
$realm = array_pop($path);

function validate_session()
{
    if (empty($_SESSION [ 'user' ])) {
        loginform();
    }
    
    global $realm;
    
    switch ($realm) {
        case 'hosts':
        case 'domains':
            if (!$_SESSION [ 'admin' ]) {
                loginform();
            }
                
            break;
    }
}

function loginform()
{
    header('Location: http://' . $_SERVER [ 'HTTP_HOST' ] . '/mailadmin/login.php');
            
    exit(0);
}

function remove_session()
{
    global $dba;
    // initialize the session
    
    // remove all session information
    $_SESSION = array();

    // remove the whole session information.
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }

    // remove the session itself.
    session_destroy();
    return true;
}
