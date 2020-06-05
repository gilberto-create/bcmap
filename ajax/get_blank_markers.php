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
$stmt = $db->query( 'select * from marker where address is null' );
$result = $stmt->fetchAll( PDO::FETCH_ASSOC );



/**
 *	return
 */
echo json_encode( $result );