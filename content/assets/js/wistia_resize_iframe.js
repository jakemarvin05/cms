$(function () {
	function setIFrameSize() {
	  var $myIFRAMES   = $(".wistia_embed")
		ogWidth     = 640,
		ogHeight    = 360,
		ogRatio     = ogWidth / ogHeight,
		windowWidth = 0,
		resizeTimer = null;
		
		if (windowWidth < 480) {
			$myIFRAMES.each(function(i){
				var parentDivWidth = $($myIFRAMES[i]).parent().width(),
					newHeight      = (parentDivWidth / ogRatio);
				
				$($myIFRAMES[i]).addClass("iframe-class-resize").css({ height : newHeight, width : parentDivWidth });
			});
		} else {
			$myIFRAMES.removeClass("iframe-class-resize").css({ width : '', height : '' });
	  	}
	}
	$(window).resize(function () {
		setIFrameSize();
	});
	
	$(window).on("load", function () {
		setIFrameSize();
	});
});