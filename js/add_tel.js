$(function(){
	
	
	
	var ajax_dir = 'http://dev.strong-pt.org/bcmap/ajax/';
	
	$( 'input[name="city"],input[name=detail]' ).click(function(){
		
		$(this).select();
		document.execCommand("copy");
	});
	
	$('button').click(function(){
		
		var target = $(this).parents('tr');
		
		var id = target.attr( 'data-id' );
		var name = target.find('*[name="name"]').val();
		var tel = target.find('*[name="tel"]').val();
		var searched = ( target.find('*[name="searched"]:checked')[0] )? 1:0;
		
		if( tel != "" || searched == 1 ) {
			
	
			$.ajax({
					url: ajax_dir+ 'add_tel.php',
					type:'POST',
					data: {
							id: id,
							name: name,
							tel: tel,
							searched: searched,
							user: $('body').attr( 'data-id' )
						}
				}).done(function(data) {
				
					if( data == 1 ) {

						$('tr[data-id="'+ id+ '"]').hide();
					}
				});
		}
	});
});