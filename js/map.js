function InitMap() {
	
	
	
	var ajax_dir = 'http://dev.strong-pt.org/bcmap/ajax/';
	
	var Map;
	var Rectangle;
	
	var MapDivID = 'Map';
	var MapDiv = document.getElementById( MapDivID );
	
	var DefaultZoom = 14;
	var DefaultLat = parseFloat( MapDiv.getAttribute( 'data-default-lat' ) );
	var DefaultLng = parseFloat( MapDiv.getAttribute( 'data-default-lng' ) );
	var AreaRate = parseFloat( MapDiv.getAttribute( 'data-area-rate' ) );
		
	var OffsetLat = parseFloat( MapDiv.getAttribute( 'data-offset-lat' ) );
	var OffsetLng = parseFloat( MapDiv.getAttribute( 'data-offset-lng' ) );
	var DrawLat = DefaultLat + ( OffsetLat * ( AreaRate * 2 ) );
	var DrawLng = DefaultLng + ( OffsetLng * ( AreaRate * 2 ) );
	
	var user_id = $( 'body' ).attr( 'data-user' );
	
	var Markers = [];
	
	var AmbigousTriggerClass = 'is-ambiguous';
	
	
	
	/**
	 *	描画領域スタイル
	 */
	function StylingMapDiv() {
		
		$( '#'+ MapDivID ).css({
				height: $(window).height()
			});
	}
	
	
	
	/**
	 *	描画
	 */
	function DrawMap() {
	
		$.ajax({
				url: ajax_dir+ 'get_area_markers.php',
				type:'POST',
				data: {
						lat: DrawLat,
						lng: DrawLng,
						rate: AreaRate
					},
				dataType: 'json'
			}).done(function(data) {
			
			
		
				Map = new google.maps.Map(
						MapDiv,
						{
							center: new google.maps.LatLng(
									DrawLat,
									DrawLng
								),
							zoom: DefaultZoom,
							disableDefaultUI: true,
							disableDoubleClickZoom: true,
							mapTypeId: google.maps.MapTypeId.SATELLITE,
							tilt: 0,
							zoomControl: true,
							restriction: {
								latLngBounds: {
									north: ( DrawLat + AreaRate ),
									south:(  DrawLat - AreaRate ),
									west: ( DrawLng - AreaRate ),
									east: ( DrawLng + AreaRate )
								},
								strictBounds: false
							}
						}
					);

				Rectangle = new google.maps.Rectangle({
						strokeColor: '#CC9900',
						strokeOpacity: 1.0,
						strokeWeight: 2,
						fillColor: '#FFFFFF',
						fillOpacity: 0.00,
						map: Map,
						bounds: {
								north: ( DrawLat + AreaRate ),
								south:(  DrawLat - AreaRate ),
								west: ( DrawLng - AreaRate ),
								east: ( DrawLng + AreaRate )
							}
					});
			
			
			
				/**
				 *	実装
				 */
				function remove_marker( LatLng ) {

					$.ajax({
							url: ajax_dir+ 'remove_marker.php',
							type: 'POST',
							data: {
									lat: LatLng.lat(),
									lng: LatLng.lng(),
									user: user_id
								}
						}).done(function(data) {
						
							if( data != 1 ) {
								
								alert( '削除できません。' );
								return false;
							}
							var MarkerKey = LatLng.lat()+ ','+ LatLng.lng();
							Markers[MarkerKey].setMap( null );
						}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
						});
				}
				function add_marker( LatLng, ambiguous ) {
					
					var MerkerKey = LatLng.lat()+ ','+ LatLng.lng();
					var MarkerStyle = ( ambiguous === undefined || ambiguous == null )? 'http://maps.google.com/mapfiles/ms/icons/red.png':'http://maps.google.com/mapfiles/ms/icons/blue.png';

					Markers[MerkerKey] = new google.maps.Marker( {
							map: Map,
							position: new google.maps.LatLng( LatLng.lat(), LatLng.lng() ),
							icon: MarkerStyle
						});

					Markers[MerkerKey].addListener( "dblclick", function ( CurrentMarker ) {

							if( window.confirm( '削除しますか？' ) ) {

								var MarkerLatLng = CurrentMarker.latLng;
								remove_marker( MarkerLatLng );
							}
						});
				}
				$.each( data, function( key, value ){
					
					add_marker( new google.maps.LatLng( value.lat, value.lng ), value.ambiguous );
				});


				/**
				 *	右ｸリックイベント
				 */
				$(document).on( 'click', '.'+ AmbigousTriggerClass, function(){

					var TargetLatLng = new google.maps.LatLng( document.getElementById('contextmenu').getAttribute( 'data-lat' ), document.getElementById('contextmenu').getAttribute( 'data-lng' ) );
					var geocoder = new google.maps.Geocoder();

					geocoder.geocode({
						latLng: TargetLatLng
					},function( results, status ){

						if( status == google.maps.GeocoderStatus.OK ) {

							if( results[0].geometry ) {

								var address = results[0].formatted_address
								address = address.replace(/^日本, /, '');
								address = address.replace(/^日本、/, '');

								$.ajax({
										url: ajax_dir+ 'add_marker.php',
										type: 'POST',
										data: {
												lat: TargetLatLng.lat(),
												lng: TargetLatLng.lng(),
												address: address,
												user: user_id,
												ambiguous: 1
											}
									}).done(function(data) {
									
										add_marker( TargetLatLng, true );
									}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
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
				});
				Rectangle.addListener( "rightclick", function( Spot ){
					
					document.getElementById('contextmenu').style.left = Spot.tb.pageX +"px";
					document.getElementById('contextmenu').style.top = Spot.tb.pageY +"px";
					document.getElementById('contextmenu').style.display = "block";
					document.getElementById('contextmenu').setAttribute( 'data-lat',Spot.latLng.lat() );
					document.getElementById('contextmenu').setAttribute( 'data-lng',Spot.latLng.lng() );
				});
				document.body.addEventListener( 'click', function ( Spot ){
					
					document.getElementById('contextmenu').style.display = "none";
				});


				/**
				 *	ダブルクリックイベント
				 */
				Rectangle.addListener( "dblclick", function( Spot ){

					var TargetLatLng = Spot.latLng;
					var geocoder = new google.maps.Geocoder();

					geocoder.geocode({
						latLng: TargetLatLng
					},function( results, status ){

						if( status == google.maps.GeocoderStatus.OK ) {

							if( results[0].geometry ) {

								var address = results[0].formatted_address
								address = address.replace(/^日本, /, '');
								address = address.replace(/^日本、/, '');

								$.ajax({
										url: ajax_dir+ 'add_marker.php',
										type: 'POST',
										data: {
												lat: TargetLatLng.lat(),
												lng: TargetLatLng.lng(),
												address: address,
												user: user_id
											}
									}).done(function(data) {
									
										add_marker( TargetLatLng );
									}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
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
				});
			}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
			});
	}
	
	
	
	/**
	 *	ウィンドウリサイズ
	 */
	window.addEventListener( 'resize', function(){
		
		StylingMapDiv();
	});
	
	
	
	/**
	 *	コンストラクタ
	 */
	StylingMapDiv();
	DrawMap();
}