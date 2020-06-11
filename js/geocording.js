function getAddress( latlng, id ) {

	var geocoder = new google.maps.Geocoder();

	geocoder.geocode({
		latLng: latlng
	},function( results, status ){

		if( status == google.maps.GeocoderStatus.OK ) {

			if( results[0].geometry ) {

				var address = results[0].formatted_address
				address = address.replace(/^日本, /, '');
				address = address.replace(/^日本、/, '');

				$.ajax({
						url: ajax_dir+ 'add_address.php',
						type: 'POST',
						data: {
								address: address,
								id: id
							}
					});
			}
		} else if ( status == google.maps.GeocoderStatus.ERROR ) {

			console.log( "サーバとの通信時に何らかのエラーが発生！" );
		} else if ( status == google.maps.GeocoderStatus.INVALID_REQUEST ) {

			console.log( "リクエストに問題アリ！geocode()に渡すGeocoderRequestを確認せよ！！" );
		} else if ( status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT ) {

			console.log( "短時間にクエリを送りすぎ！落ち着いて！！" );
		} else if ( status == google.maps.GeocoderStatus.REQUEST_DENIED ) {

			console.log( "このページではジオコーダの利用が許可されていない！・・・なぜ！？" );
		} else if ( status == google.maps.GeocoderStatus.UNKNOWN_ERROR ) {

			console.log( "サーバ側でなんらかのトラブルが発生した模様。再挑戦されたし。" );
		} else if ( status == google.maps.GeocoderStatus.ZERO_RESULTS ) {

			console.log( "見つかりません" );
		} else {

			console.log( "えぇ～っと・・、バージョンアップ？" );
		}
	});
}