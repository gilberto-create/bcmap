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

if( empty( $result ) ) {
	
	header( 'location: ../login?url='. urlencode( $_SERVER['REQUEST_URI'] ) );
} else {
	
	$login_user = $_SESSION['login_user'];
}
$page = ( $_GET['page'] )? $_GET['page']:1;
$area = ( $_GET['area'] )? $_GET['area']:'兵庫';
$amount = 1000;
$marker_list = $db->query(sprintf(
		'select * from marker where deleted is null and formated = 1 and searched is null and city like "%%%s%%" limit %d offset %d',
		$area,
		$amount,
		( $page - 1 ) * $amount
	));
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
<script type="text/javascript" src="../js/add_tel.js"></script>

</head>
<body data-user="<?php echo $login_user['id']; ?>" id="add_tel">
	<form>
		<p>作業エリア変更：<input type="text" name="area" value="<?php echo $area; ?>"> <input type="submit" name="変更"></p>
   </form>
<table>
<?php foreach( $marker_list as $marker ): ?>
	<tr data-id="<?php echo $marker['id']; ?>">
		<td class="noborder"><input type="text" name="city" value="<?php echo $marker['city']; ?>" readonly></td>
		<td class="noborder"><input type="text" name="detail" value="<?php echo $marker['detail']; ?>" readonly></td>
		<td rowspan="2" class="large">名前：<input type="text" name="name" value=""></td>
		<td rowspan="2" class="large">TEL：<input type="text" name="tel" value=""></td>
		<td rowspan="2"><label><input type="checkbox" name="searched">該当なし</label></td>
		<td rowspan="2"><button>登録</button></td>
	</tr>
	<tr data-id="<?php echo $marker['id']; ?>">
		<td colspan="2"><?php echo $marker['address']; ?></td>
	</tr>
<?php endforeach; ?>
</table>
<footer>
<a href="<?php echo $url; ?>?page=<?php echo $page - 1; ?>&area=<?php echo $area; ?>">前へ</a>
<a href="<?php echo $url; ?>?page=<?php echo $page + 1; ?>&area=<?php echo $area; ?>">次へ</a>
</footer>
</body>
</html>