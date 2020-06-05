<?php



/**
 *	language
 */
mb_language('Japanese');
mb_internal_encoding('UTF-8');



/**
 *	include
 */
require_once( '../config/define.inc' );
require_once( '../config/functions.inc' );



/**
 *	init
 */
$db = db();



/**
 *	get
 */
$lat = (float)$_POST['lat'];
$lng = (float)$_POST['lng'];
$rate = (float)$_POST['rate'];
$query = 'select * from marker where deleted is null';
$query .= ' and lat > '. ( $lat - $rate );
$query .= ' and lat < '. ( $lat + $rate );
$query .= ' and lng > '. ( $lng - $rate );
$query .= ' and lng < '. ( $lng + $rate );
$stmt = $db->query( $query );
$result = $stmt->fetchAll( PDO::FETCH_ASSOC );



/**
 *	return
 */
echo json_encode( $result );