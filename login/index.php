<?php



/**
 *	language
 */
mb_language('Japanese');
mb_internal_encoding('UTF-8');
session_start();



/**
 *	include
 */
require_once( '../config/define.inc' );
require_once( '../config/functions.inc' );



/**
 *	init
 */
$db = db();
$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
$error = array();
$username = '';
$password = '';



/**
 *	POST
 */
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if( empty( $username ) ) {
		
		$error['username'] = 'ユーザー名を入力してください。';
	}
	if( empty( $password ) ) {
		
		$error['password'] = 'パスワードを入力してください。';
	}
	
	if( empty( $error ) ) {
		
		$stmt = $db->query(sprintf(
				'select * from user where username = "%s" and password = "%s"',
				$username,
				$password
			));
		$result = $stmt->fetchAll( PDO::FETCH_ASSOC );
		
		if( empty( $result ) ) {
			
			$error['login'] = 'ユーザー名かパスワードが間違っています。';
		} else {
			
			$_SESSION['login_user'] = array_shift( $result );
			if( $_GET['url'] ) {
				
				header( 'location: '. urldecode( $_GET['url'] ) );
			} else {
				
				header( 'location: ../' );
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">

<title>Solar Panel Search</title>

<meta name="description" content="">
<meta name="keywords" content="">

<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="format-detection" content="telephone=no">

<meta name="theme-color" content="#FFBF00">
<link rel="shortcut icon" href="../favicon.ico">

<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/notosansjapanese.css">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" type="text/css" href="../css/initialize.css">
<link rel="stylesheet" type="text/css" href="../css/shared.css">
<link rel="stylesheet" type="text/css" href="../css/template.css">
<link rel="stylesheet" type="text/css" href="../css/content.css">
<link rel="stylesheet" type="text/css" href="../css/tablet/template.css">
<link rel="stylesheet" type="text/css" href="../css/tablet/content.css">
<link rel="stylesheet" type="text/css" href="../css/mobile/template.css">
<link rel="stylesheet" type="text/css" href="../css/mobile/content.css">
	
<!-- app -->
<meta name="apple-mobile-web-app-capable" content="no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Solar Panel Search">
<link rel="apple-touch-icon" href="http://dev.strong-pt.org/bcmap/icons/icon-512x512.png">
<link rel="manifest" href="http://dev.strong-pt.org/bcmap/manifest.json">

<script type="text/javascript" src="../js/jquery1.7.2.min.js"></script>
<script type="text/javascript" src="../js/generic_js_v2.0.2/jquery.GenericLibrary.js"></script>
<script type="text/javascript" src="../js/generic_js_v2.0.2/jquery.GenericSmoothScroll.js"></script>
<script type="text/javascript" src="../js/generic_js_v2.0.2/jquery.GenericController.js"></script>

</head>
<body>
	
	
	
	<section id="login">
	<!-- login -->
		
		<header>
			<h1><span class="material-icons">lock_open</span>ログイン</h1>
		</header>
		
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<dl>
<dt>ユーザー名</dt>
				<dd>
					<input type="text" name="username" value="<?php echo htmlspecialchars( $username, ENT_QUOTES ); ?>">
<?php if( !empty( $error['username'] ) ) echo '<p>'. $error['username']. '</p>'; ?>
<?php if( !empty( $error['login'] ) ) echo '<p>'. $error['login']. '</p>'; ?>
				</dd>
				<dt>パスワード</dt>
				<dd>
					<input type="password" name="password" value="">
<?php if( !empty( $error['password'] ) ) echo '<p>'. $error['password']. '</p>'; ?>
				</dd>
			</dl>
			<div class="submit">
				<button type="submit">ログイン</button>
			</div>
		</form>
		
	<!-- login --></section>
	
	
	
</body>
</html>
