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
require_once( 'config/define.inc' );
require_once( 'config/functions.inc' );



/**
 *	init
 */
$db = db();
$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];



/**
 *	ログインチェック
 */
$stmt = $db->query(sprintf(
		'select * from user where username = "%s" and password = "%s"',
		$_SESSION['login_user']['username'],
		$_SESSION['login_user']['password']
	));
$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

if( empty( $result ) ) {
	
	header( 'location: ./login?url='. urlencode( $_SERVER['REQUEST_URI'] ) );
} else {
	
	$login_user = $_SESSION['login_user'];
}



/**
 *	setting
 */
$defaultLat = 34.6937127;
$defaultLng = 135.4951963;
$areaRate = 0.02;



/**
 *	get params
 */
$lat = ( $_GET['lat'] )? $_GET['lat']:0;
$lng = ( $_GET['lng'] )? $_GET['lng']:0;
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
<link rel="shortcut icon" href="favicon.ico">

<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/notosansjapanese.css">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" type="text/css" href="css/initialize.css">
<link rel="stylesheet" type="text/css" href="css/shared.css">
<link rel="stylesheet" type="text/css" href="css/template.css">
<link rel="stylesheet" type="text/css" href="css/content.css">
<link rel="stylesheet" type="text/css" href="css/tablet/template.css">
<link rel="stylesheet" type="text/css" href="css/tablet/content.css">
<link rel="stylesheet" type="text/css" href="css/mobile/template.css">
<link rel="stylesheet" type="text/css" href="css/mobile/content.css">
	AIzaSyBrl3xYXt8ezwSeZrXSsqkJtNDl0-0h6pE
<!-- app -->
<meta name="apple-mobile-web-app-capable" content="no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Solar Panel Search">
<link rel="apple-touch-icon" href="http://dev.strong-pt.org/bcmap/icons/icon-512x512.png">
<link rel="manifest" href="http://dev.strong-pt.org/bcmap/manifest.json">

<script type="text/javascript" src="js/jquery1.7.2.min.js"></script>
<script type="text/javascript" src="js/generic_js_v2.0.2/jquery.GenericLibrary.js"></script>
<script type="text/javascript" src="js/generic_js_v2.0.2/jquery.GenericSmoothScroll.js"></script>
<script type="text/javascript" src="js/generic_js_v2.0.2/jquery.GenericController.js"></script>

<script type="text/ecmascript" src="js/geocording.js"></script>
<script type="text/ecmascript" src="js/map.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyDaa0rsAiEDcBen_Z4TVYhqY66jTp2_kUs&callback=InitMap" async defer></script>

</head>
<body data-user="<?php echo $login_user['id']; ?>" oncontextmenu="return false;">
	
	
	<div id="Map"
		 data-offset-lat="<?php echo $lat; ?>"
		 data-offset-lng="<?php echo $lng; ?>"
		 data-default-lat="<?php echo $defaultLat; ?>"
		 data-default-lng="<?php echo $defaultLng; ?>"
		 data-area-rate="<?php echo $areaRate; ?>">
	</div>
	
	
<?php if( $_SESSION['login_user']['group'] == 0 ): ?>
	<nav id="Control">
		<a href="<?php echo $url; ?>?lat=<?php echo $lat + 1; ?>&lng=<?php echo $lng; ?>" class="move-top">北</a>
		<a href="<?php echo $url; ?>?lat=<?php echo $lat; ?>&lng=<?php echo $lng + 1; ?>" class="move-right">東</a>
		<a href="<?php echo $url; ?>?lat=<?php echo $lat - 1; ?>&lng=<?php echo $lng; ?>" class="move-bottom">南</a>
		<a href="<?php echo $url; ?>?lat=<?php echo $lat; ?>&lng=<?php echo $lng - 1; ?>" class="move-left">西</a>
	</nav>
<?php endif; ?>
	
	
	<section id="information">
	<!-- information -->
<?php if( $_SESSION['login_user']['manager'] == 1 || $_SESSION['login_user']['group'] == 0 ): ?>
		<a href="./manage/" class="manage"><span class="material-icons">trending_up</span></a>
<?php endif; ?>
<?php if( $_SESSION['login_user']['group'] != 0 ): ?>
		<span class="login-user-name"><?php echo $login_user['first_name']; ?> <?php echo $login_user['last_name']; ?> 様</span>
<?php else: ?>
		<a href="./view/" class="view"><span class="material-icons">map</span></a>
<?php endif; ?>
		<a href="./logout/" class="logout"><span class="material-icons">lock</span></a>
		
	<!-- information --></section>
	<div id="contextmenu">
	<!-- contextmenu -->
		
		<ul>
			<li class="is-ambiguous">あいまい登録</li>
		</ul>
		
	<!-- contextmenu --></div>
	
	
</body>
</html>