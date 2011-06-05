<?
// datenbank-editing-admin mit generischer struktur
// single-file version ( db.php )
// v 0.996b
// dbadmin (c) 2011/02 klaus oblasser
// mail: ls@ls.to
// bereich: auth

$url_action = (empty($_REQUEST['action'])) ? 'logIn' : $_REQUEST['action'];
$auth_realm = (isset($auth_realm)) ? $auth_realm : '';

function htmla()
{

 echo '
  <html>
   <head>
    <LINK href="dbf.style.css" type="text/css" rel=stylesheet>
   </head>
    <BODY text="#000000" bottomMargin=0 vLink="#000000" link="#000000" bgColor="#ffffff" leftMargin=0 topMargin=0 rightMargin=0 marginwidth="0" marginheight="0" alink="#000000">
    <center><br><br><br><br><table><tr><td>
 ';
}

function htmle()
{
 echo '</td></tr></table></body></html>';
}

if (isset($url_action)) {
    if (is_callable($url_action)) {
        call_user_func($url_action);
    } else {
	    htmla();
            echo 'funktion nicht gefunden, anfrage eingestellt';
	    htmle();
    };
};

function logIn() {
    global $auth_realm;

    if (!isset($_SESSION['_user_name'])) {
        if (!isset($_SESSION['login'])) {
            $_SESSION['login'] = TRUE;
            header('WWW-Authenticate: Basic realm="'.$auth_realm.'"');
            header('HTTP/1.0 401 Unauthorized');
	    htmla();
            echo 'bitte richtigen username und passwort eingeben';
            echo '<p><a href="?action=logOut">neuer versuch</a></p>';
	    htmle();
            exit;	
        } else {
            $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
            $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $result = authenticate($user, $password);
            if ($result == 0) {
                $_SESSION['_user_name'] = $user;
            } else {
                session_unset($_SESSION['login']);
                errMes($result);
		htmla();
        	echo '<p><a href="">neuer versuch</a></p>';
    		htmle();
                exit;
            };
        };
    };
}

function authenticate($user, $password) {
    global $_user_;
    global $_password_;

    if (($user == $_user_)&&($password == $_password_)) { return 0; }
    else { return 1; };
}

function errMes($errno) {
    switch ($errno) {
        case 0:
            break;
        case 1:
	    htmla();
            echo 'username und passwort sind falsch';
	    htmle();
            break;
        default:
	    htmla();
            echo 'fehler. irgend einer!';
	    htmle();
    };
}

function logOut() {

    session_destroy();
    if (isset($_SESSION['_user_name'])) {
        session_unset($_SESSION['_user_name']);
	htmla();
        echo "loggout erfolgreich<br>";
        echo '<p><a href="?action=logIn">LogIn</a></p>';
	htmle();
        header("Location: ?action=logIn", TRUE, 301);
    } else {
        header("Location: ?action=logIn", TRUE, 301);
    };
    if (isset($_SESSION['login'])) { session_unset($_SESSION['login']); };
    exit;
}

$phpau = $_SERVER['PHP_AUTH_USER'];
?>
