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

$stmt = $db->query(sprintf(
		'select * from marker where address is null and deleted is null'
	));
$markers = $stmt->fetchAll( PDO::FETCH_ASSOC );
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

<script type="text/ecmascript" src="update_address.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyDaa0rsAiEDcBen_Z4TVYhqY66jTp2_kUs&callback=init" async defer></script>

</head>
<body>
	<ul>
<?php foreach( $markers as $marker ): ?>
		<li class="blank" data-id="<?php echo $marker['id']; ?>" data-lat="<?php echo $marker['lat']; ?>" data-lng="<?php echo $marker['lng']; ?>">
			<?php echo $marker['id']; ?> : <span></span>
		</li>
<?php endforeach; ?>
	</ul>
</body>
</html>