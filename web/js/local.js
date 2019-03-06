  $( function() {
    $( ".draggable" ).draggable();
    $( ".resizable" ).resizable();
  } );

 /*$(function(){

 			//$('.mecha').freetrans();
			// do a selector group
			$('.mecha').each(function() {
				var mx = $(this).css('transform'); 
				alert(mx);
				$(this).css('top',$(this).position().top);
				$(this).css('left', $(this).position().left);
				$(this).css('height', $(this).height());
				$(this).css('width',$(this).width());
				$(this).css('transform', mx);
				alert(mx);
				//alert($(this).height());

	            $(this).freetrans({
				x: $(this).position().left,
				y: $(this).position().top,
				width: $(this).height(),
				height: $(this).width(),
				matrix: ""+mx+"",

			})

                });

		})*/
		
		