function InitMap() {
	
	
	
	var ajax_dir = 'http://dev.strong-pt.org/bcmap/ajax/';
	
	var Map;
	
	var MapDivID = 'Map';
	var MapDiv = document.getElementById( MapDivID );
	
	var DefaultZoom = 6;
	var DefaultLat = parseFloat( MapDiv.getAttribute( 'data-default-lat' ) );
	var DefaultLng = parseFloat( MapDiv.getAttribute( 'data-default-lng' ) );
	var AreaRate = parseFloat( MapDiv.getAttribute( 'data-area-rate' ) );
	
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
						lat: 0,
						lng: 0,
						rate: AreaRate
					},
				dataType: 'json'
			}).done(function(data) {
			
			
		
				Map = new google.maps.Map(
						MapDiv,
						{
							center: new google.maps.LatLng(
									DefaultLat,
									DefaultLng
								),
							zoom: DefaultZoom,
							disableDefaultUI: true,
							disableDoubleClickZoom: true,
							mapTypeId: google.maps.MapTypeId.SATELLITE,
							tilt: 0,
							zoomControl: true
						}
					);
			
				function add_marker( LatLng, ambiguous ) {
					
					var MerkerKey = LatLng.lat()+ ','+ LatLng.lng();
					var MarkerStyle = ( ambiguous === undefined || ambiguous == null )? 'http://maps.google.com/mapfiles/ms/icons/red.png':'http://maps.google.com/mapfiles/ms/icons/blue.png';

					Markers[MerkerKey] = new google.maps.Marker( {
							map: Map,
							position: new google.maps.LatLng( LatLng.lat(), LatLng.lng() ),
							icon: MarkerStyle
						});
				}
				$.each( data, function( key, value ){
					
					add_marker( new google.maps.LatLng( value.lat, value.lng ), value.ambiguous );
				});



				/**
				 *	ダブルクリックイベント
				 */
				Map.addListener( "dblclick", function( Spot ){

					var TargetLatLng = Spot.latLng;
					var offsetLat;
					var offsetLng;
					
					if( DefaultLat > TargetLatLng.lat() ) {
						
						offsetLat = Math.round( 0 - ( DefaultLat - TargetLatLng.lat() ) / ( AreaRate * 2 ) );
					} else {
						
						offsetLat = Math.round( ( TargetLatLng.lat() - DefaultLat ) / ( AreaRate * 2 ) );
					}
					
					if( DefaultLng > TargetLatLng.lng() ) {
						
						offsetLng = Math.round( 0 - ( DefaultLng - TargetLatLng.lng() ) / ( AreaRate * 2 ) );
					} else {
						
						offsetLng = Math.round( ( TargetLatLng.lng() - DefaultLng ) / ( AreaRate * 2 ) );
					}
					
					window.location.href = '../?lat='+ offsetLat+ '&lng='+ offsetLng;
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