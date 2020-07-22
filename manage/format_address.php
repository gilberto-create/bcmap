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



/**
 *	ログインチェック
 */
$stmt = $db->query(sprintf(
		'select * from user where username = "%s" and password = "%s"',
		$_SESSION['login_user']['username'],
		$_SESSION['login_user']['password']
	));
$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

if( empty( $result ) || !( $result[0]['group'] == 0 || $result[0]['manager'] == 1 ) ) {
	
	header( 'location: ../login?url='. urlencode( $_SERVER['REQUEST_URI'] ) );
} else {
	
	$login_user = $_SESSION['login_user'];
}



/**
 *	郵便番号取得
 */
$postal = array();
$stmt = $db->query( 'select * from postal_master' );
$result = $stmt->fetchAll( PDO::FETCH_ASSOC );
foreach( $result as $item ) {
	
	$postal[sprintf( "%07d", $item['postal'] )] = $item['prefecture'].$item['city'].$item['region'];
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
<link rel="shortcut icon" href="favicon.ico">

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
<body data-user="<?php echo $login_user['id']; ?>">
<?php
/**
 *	住所一覧
 */
$stmt = $db->query( 'select * from marker where deleted is null and formated is null limit 5000' );
$marker_list = $stmt->fetchAll( PDO::FETCH_ASSOC );
?>
<table>
<?php
foreach( $marker_list as $marker ):

$splited = explode( ' ', $marker['address'] );
if( !preg_match( "/^\d{3}\-\d{4}$/", mb_substr( $splited[0], 1, mb_strlen( $splited[0] ) ) ) ) {
	continue;
}
	
$postal_code = $splited[0];
$postal_code = str_replace( '-', '', $postal_code );
$postal_code = str_replace( '〒', '', $postal_code );

if( !$postal[$postal_code] ) {
	continue;
}
	
$detail = $splited[1];
if( strpos( $detail, '大字' ) ) {
	$detail = str_replace( '大字', '', $detail );
}
$detail = mb_convert_kana( str_replace( $postal[$postal_code], '', $detail ), 'a' );
$detail = mb_convert_kana( str_replace( $postal[$postal_code], '', $detail ), 'a' );
$detail = str_replace( '丁目', '-', $detail );
$detail = str_replace( '番', '-', $detail );
$detail = str_replace( '号', '', $detail );
	
$building = '';
if( count( $splited ) > 2 ) {
	
	for( $i = 2; $i <= count( $splited ); $i++ ) {
		
		$building .= $splited[$i];
	}
}
$building = mb_convert_kana( str_replace( $postal[$postal_code], '', $building ), 'a' );
$building = mb_convert_kana( str_replace( $postal[$postal_code], '', $building ), 'a' );
$building = str_replace( '丁目', '-', $building );
$building = str_replace( '番', '-', $building );
$building = str_replace( '号', '-', $building );
$building = mb_convert_kana( str_replace( $detail, '', $building ), 'a' );
	
$new_marker = array(
		'id' => $marker['id'],
		'postal' => $postal_code,
		'city' => $postal[$postal_code],
		'detail' => $detail,
		'building' => $building
	);
$query = sprintf(
		'update marker set postal = "%7d", city = "%s", detail = "%s", building = "%s", formated = 1 where id = %d',
		$new_marker['postal'],
		$new_marker['city'],
		$new_marker['detail'],
		$new_marker['building'],
		$new_marker['id']
	);
$rs = $db->query($query);
?>
	<tr>
		<td><?php echo $marker['address']; ?></td>
		<td><?php echo var_dump($rs,$query); ?></td>
	</tr>
<?php endforeach; ?>
</table>
</body>
</html>