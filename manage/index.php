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
 *	登録総数取得
 */
$query = sprintf(
		'select * from marker join user on marker.user = user.id where marker.deleted is null'
	);
if( $result[0]['group'] != 0 ) {
	
	$query .= ' and user.group = '. $result[0]['group'];
} elseif( is_numeric( $_GET['group'] ) ) {
	
	$query .= ' and user.group = '. $_GET['group'];
}

$marker_list = array();
$marker = $db->query( $query. ' and ambiguous is null' );
$marker = $marker->fetchAll( PDO::FETCH_ASSOC );
foreach( $marker as $item ) {
	
	if( !$marker_list[ $item['user'] ] ) {
		
		$marker_list[ $item['user'] ] = array();
	}
	
	array_push( $marker_list[ $item['user'] ], $item );
}
$marker = count( $marker );

$today_marker_list = array();
$today_marker = $db->query( $query. ' and ambiguous is null and marker.created between "'. date( "Y-m-d 00:00:00", strtotime( date( "Y-m-d H:i:s" ) ) ). '" and "'. date( "Y-m-d 23:59:59", strtotime( date( "Y-m-d H:i:s" ) ) ). '"' );
$today_marker = $today_marker->fetchAll( PDO::FETCH_ASSOC );
foreach( $today_marker as $item ) {
	
	if( !$today_marker_list[ $item['user'] ] ) {
		
		$today_marker_list[ $item['user'] ] = array();
	}
	
	array_push( $today_marker_list[ $item['user'] ], $item );
}
$today_marker = count( $today_marker );

$ambiguous_list = array();
$ambiguous = $db->query( $query. ' and ambiguous is not null' );
$ambiguous = $ambiguous->fetchAll( PDO::FETCH_ASSOC );
foreach( $ambiguous as $item ) {
	
	if( !$ambiguous_list[ $item['user'] ] ) {
		
		$ambiguous_list[ $item['user'] ] = array();
	}
	
	array_push( $ambiguous_list[ $item['user'] ], $item );
}
$ambiguous = count( $ambiguous );

$today_ambiguous_list = array();
$today_ambiguous = $db->query( $query. ' and ambiguous is not null and marker.created between "'. date( "Y-m-d 00:00:00", strtotime( date( "Y-m-d H:i:s" ) ) ). '" and "'. date( "Y-m-d 23:59:59", strtotime( date( "Y-m-d H:i:s" ) ) ). '"' );
$today_ambiguous = $today_ambiguous->fetchAll( PDO::FETCH_ASSOC );
foreach( $today_ambiguous as $item ) {
	
	if( !$today_ambiguous_list[ $item['user'] ] ) {
		
		$today_ambiguous_list[ $item['user'] ] = array();
	}
	
	array_push( $today_ambiguous_list[ $item['user'] ], $item );
}
$today_ambiguous = count( $today_ambiguous );



/**
 *	ユーザー
 */
$query = sprintf(
		'select user.*, group.name as group_name from user join al5586_bcmap.group on user.group = group.id where user.deleted is null and group.deleted is null'
	);
if( $result[0]['group'] != 0 ) {
	
	$query .= ' and user.group = '. $result[0]['group'];
} elseif( is_numeric( $_GET['group'] ) ) {
	
	$query .= ' and user.group = '. $_GET['group'];
}
$user_list = $db->query( $query );
$user_list = $user_list->fetchAll( PDO::FETCH_ASSOC );
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
<body data-user="<?php echo $login_user['id']; ?>" id="dataView">
	
	<h2>本日登録数</h2>
	<table>
		<tr>
			<th>登録数</th>
			<td><?php echo $today_marker; ?></td>
		</tr>
		<tr>
			<th>あいまい登録数</th>
			<td><?php echo $today_ambiguous; ?></td>
		</tr>
		<tr>
			<th>総登録数</th>
			<td><?php echo $today_marker + $today_ambiguous; ?></td>
		</tr>
	</table>
	
	<h2>登録数</h2>
	<table>
		<tr>
			<th>登録数</th>
			<td><?php echo $marker; ?></td>
		</tr>
		<tr>
			<th>あいまい登録数</th>
			<td><?php echo $ambiguous; ?></td>
		</tr>
		<tr>
			<th>総登録数</th>
			<td><?php echo $marker + $ambiguous; ?></td>
		</tr>
	</table>
	
	<h2>ユーザー別</h2>
	<table>
		<thead>
			<tr>
				<th>名前</th>
				<th>所属</th>
				<th>登録数</th>
				<th>あいまい登録数</th>
				<th>総登録数</th>
				<th>あいまい登録率</th>
			</tr>
		</thead>
<?php foreach( $user_list as $user ): ?>
		<tr>
			<th><?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?></th>
			<td><?php echo $user['group_name']; ?></td>
			<td class="number"><?php echo @count( $marker_list[$user['id']] ); ?><br>
				( 内本日 : <?php echo @count( $today_marker_list[$user['id']] ); ?> )</td>
			<td class="number"><?php echo @count( $ambiguous_list[$user['id']] ); ?><br>
				( 内本日 : <?php echo @count( $today_ambiguous_list[$user['id']] ); ?> )</td>
			<td class="number"><?php echo @count( $marker_list[$user['id']] ) + @count( $ambiguous_list[$user['id']] ); ?><br>
				( 内本日 : <?php echo @count( $today_marker_list[$user['id']] ) + @count( $today_ambiguous_list[$user['id']] ); ?> )</td>
			<td class="number"><?php echo @floor( @( @count( $ambiguous_list[$user['id']] ) / @count( $marker_list[$user['id']] ) ) * 100 ). '%'; ?></td>
		</tr>
<?php endforeach; ?>
	</table>
	
	
	<section id="information">
	<!-- information -->
		<a href="../" class="return"><span class="material-icons">undo</span></a>
<?php if( $_SESSION['login_user']['group'] != 0 ): ?>
		<span class="login-user-name"><?php echo $login_user['first_name']; ?> <?php echo $login_user['last_name']; ?> 様</span>
<?php else: ?>
		<a href="../view/" class="view"><span class="material-icons">map</span></a>
<?php endif; ?>
		<a href="../logout/" class="logout"><span class="material-icons">lock</span></a>
		
	<!-- information --></section>
	
</body>
</html>