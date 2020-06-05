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
	
	var Markers = [];
	
	
	
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
				 *	ダブルクリックイベント
				 */
				Rectangle.addListener( "dblclick", function( Spot ){

					if( window.confirm( '登録しますか？' ) ) {

						var TargetLatLng = Spot.latLng;
						var MerkerKey = TargetLatLng.lat()+ ','+ TargetLatLng.lng();

						Markers[MerkerKey] = new google.maps.Marker( {
								map: Map,
								position: new google.maps.LatLng( TargetLatLng.lat(), TargetLatLng.lng() )
							});

						Markers[MerkerKey].addListener( "dblclick", function ( CurrentMarker ) {

								if( window.confirm( '削除しますか？' ) ) {

									var MarkerLatLng = CurrentMarker.latLng;
									var CurrentMarkerKey = MarkerLatLng.lat()+ ','+ MarkerLatLng.lng();

									Markers[CurrentMarkerKey].setMap( null );
								}
							});
					}
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