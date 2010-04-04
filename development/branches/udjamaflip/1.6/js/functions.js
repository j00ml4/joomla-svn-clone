
function enable_draggable()
{
    jQuery('.window').draggable({
		containment: '#window-container',
		handle: jQuery('.title'),
		opacity: 0.8,
		
        stop: function(e, ui) {
            jQuery('.window').find('.screen').remove();
        }
	});
	
	
	jQuery('.window').bind('dragstart',function( event ){
        jQuery('.window').append('<div id="screen'+jQuery('.window').attr('id')+'" class="screen"></div>');
        jQuery('.window').find('.screen').css('width',jQuery('.window').find('.content').width());
        jQuery('.window').find('.screen').css('height',jQuery('.window').find('.content').height());
        jQuery('.window').find('.screen').css('position','absolute');
        jQuery('.window').find('.screen').css('top','21px');
        jQuery('.window').find('.screen').css('left','4px');
        jQuery('.window').find('.screen').css('background-color','Transparent');
        jQuery('.window').find('.screen').css('z-index','9999');
    });
}

function enable_resizeable()
{
    jQuery(".window").resizable({
		containment: '#window-container'
	});
	
	jQuery('.window').bind('resizestart', function(event, ui) {
	    var contentHeight = (jQuery(this).height() - 48);
	    var contentWidth = (jQuery(this).width() - 34);
	    var oldZindex = (jQuery.myGlobals.zIndex+1);
	    jQuery(this).css('z-index',(oldZindex+1));
	    jQuery('#window-container').append('<div id="backScreen" style="width:100%; height:100%; position:absolute; z-index:'+oldZindex+'; background-color:Transparent;"></div>');
	    jQuery('.window').children('.contentWrapper').append('<div id="screen'+jQuery('.window').attr('id')+'" class="screen" style="width:'+contentWidth+';height:'+contentHeight+';position:absolute;top:21px;left:4px;z-index:9999;"></div>');
	});
	
	jQuery('.window').bind('resize', function(event, ui) {
	    var height = jQuery(this).height();
	    var width = jQuery(this).width();
        var contentHeight = (height - 50);
        var contentWidth = (width - 36);
        var rightBorder = (height - 36);
        var leftBorder = (height - 35);
        var bottomBorder = (width - 35);
        
        jQuery(this).children('.contentWrapper').children('div.screen').css('height', (contentHeight+2) + 'px');
        jQuery(this).children('.contentWrapper').children('div.screen').css('width', (contentWidth+2) + 'px');
        jQuery(this).children('.contentWrapper').children('iframe.content').css('height', contentHeight + 'px');
        jQuery(this).children('.contentWrapper').children('iframe.content').css('width', contentWidth + 'px');
        jQuery(this).children('.leftBorder').css('height', leftBorder + 'px');
        jQuery(this).children('.rightBorder').css('height', rightBorder + 'px');
        jQuery(this).children('.bottomBorder').css('width', bottomBorder + 'px');
    });
    
    jQuery('.window').bind('resizestop', function(event, ui) {
        var oldZindex = jQuery.myGlobals.zIndex;
	    jQuery(this).css('z-index',(oldZindex));
	    jQuery('#window-container').find('div#backScreen').remove();
        jQuery(this).find('.screen').remove(); 
    });
}

function addWindow(winCount,zIndex,src,rel,title)
{
    if (rel != '') {
        rel = rel.split('-');
        var height = rel[1];
        var width = rel[0];        
    }
    else {
        var height = 500;
        var width = 1050;
    }
	var contentHeight = (height - 50);
	var contentWidth = (width - 36);
	var rightBorder = (height - 36);
	var leftBorder = (height - 35);
	var bottomBorder = (width - 35);
    
	var coordX = jQuery.myGlobals.winPos;
	var coordY = jQuery.myGlobals.winPos;
	if (jQuery.myGlobals.winPos == 25)
	{
		jQuery.myGlobals.winPos = 5;	
	}
	else
	{
		jQuery.myGlobals.winPos = (jQuery.myGlobals.winPos + 5);	
	}
    
    jQuery("#window-container").append('<div id="win' + winCount + '" class="window" style="z-index:' + zIndex + '; height:' + height + 'px; width:' + width + 'px; top:' + coordY + '; left:' + coordX + ';"><div class="leftBorder" style="height:' + leftBorder + 'px;"></div><div class="rightBorder" style="height:' + rightBorder + 'px;"></div><img class="bottomLeftCorner" alt="" src="templates/udjamaflip/images/window/bottomLeftCorner.png" /><img class="bottomRightCorner resizeable" alt="" src="templates/udjamaflip/images/window/bottomRightCornerExpand.png" /><div class="bottomBorder" style="width:' + bottomBorder + 'px;"></div><div class="contentWrapper"><div class="title"><img src="templates/udjamaflip/images/window/miniLogo.png" alt="" /> <span>' + title + '</span><div class="icons"><a href="#" class="refresh"><img src="templates/udjamaflip/images/refresh.png" alt="[*]" /></a><a href="#" class="minimise"><img src="templates/udjamaflip/images/minimise.png" alt="[-]" /></a><a href="#" class="toggleSize"><img src="templates/udjamaflip/images/maximise.png" alt="[O]" /></a><a href="#" class="close"><img src="templates/udjamaflip/images/close.png" alt="[X]" /></a></div></div><iframe id="content'+winCount+'" class="content" style="height:' + contentHeight + 'px; width:' + contentWidth + 'px;" src="' + src + '"></iframe></div><img class="topLeftCorner" alt="" src="templates/udjamaflip/images/window/topLeftCorner.png" /><img class="topRightCorner" alt="" src="templates/udjamaflip/images/window/topRightCorner.png" /></div>');
    
    //document.location.href = document.location.href +'#?url='+ src;
    enable_draggable();
    //enable resizeable
    enable_resizeable();
}

function refresh_dock()
{
    jQuery('#dock').Fisheye(
    {
		maxWidth: 60,
		items: 'a',
		itemsText: 'span',
		container: '.dock-container',
		itemWidth: 30,
		proximity: 50,
		alignment : 'left',
		valign: 'bottom',
		halign : 'center'
	});
}