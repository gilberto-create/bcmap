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
 *	Get POST
 */
$id = $_POST['id'];
$name = $_POST['name'];
$tel = $_POST['tel'];
$searched = $_POST['searched'];
$user = $_POST['user'];

if( !empty( $tel ) ) {
	
	$result = $db->query(sprintf(
			'update marker set name = "%s", tel = "%s", searched = %d where id = %d',
			$name,
			$tel,
			$user,
			$id
		));
} else {
	
	$result = $db->query(sprintf(
			'update marker set searched = %d where id = %d',
			$user,
			$id
		));
}
echo 1;