$( 'div[data-check="true"]' ).each(function() {
    $( this ).html( '<img src="images/check-true.png">' );
});
var score = $( 'p[id^="score"]' );
var sum = 0;
score.each(function() {
	end = Math.round($( this ).html()/10);
	html = '';
	if($( this ).html()!='s'){
		sum += parseInt($( this ).html());
		$( '#scoreall' ).html(Math.round(sum/7)); 
	}
	for( i = 1; i <= end; i++){
		html += '<div class="stargood"></div>';
	}
	for( i = 1; i <= 10-end; i++)
		html += '<div class="starbad"></div>';
	$( this ).siblings( ".stars" ).html(html);	
});