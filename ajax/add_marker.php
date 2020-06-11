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
if( @$_POST['ambiguous'] == 1 ) {
	
	$stmt = $db->query(sprintf(
				'insert into marker( lat, lng, address, user, ambiguous ) value( "%s", "%s", "%s", "%s", "%s" )',
				$_POST['lat'],
				$_POST['lng'],
				$_POST['address'],
				$_POST['user'],
				$_POST['ambiguous']
			));
} else {
	
	$stmt = $db->query(sprintf(
				'insert into marker( lat, lng, address, user ) value( "%s", "%s", "%s", "%s" )',
				$_POST['lat'],
				$_POST['lng'],
				$_POST['address'],
				$_POST['user']
			));
}