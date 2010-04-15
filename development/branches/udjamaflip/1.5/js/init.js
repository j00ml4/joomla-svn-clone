jQuery.noConflict();
jQuery(document).ready(function ($) {
    
    
    $.myGlobals = {
       winCount: 2,
       zIndex: 2,
	   winPos: 10
    };
    
    var windows = new Array();

    /* DO NOT REMOVE, NOTE OUT, OR OTHERWISE HIDE THIS CREDIT, USING THIS CODE WITHOUT THE   */
    /* CREDIT ON DISPLAY IS IN BREACH OF THE ATTACHED LICENCE AGREEMENTS                     */
    $('head').append('<meta name="generator" value="www.udjamaflip.com - Opensource jQuery Joomla template" />');
    
    //gives a nice legacy icon if legacy mode is on.
    if ($('div.status span.legacy-mode').html())
    {
        $('div.status span.legacy-mode').html('<img src="/administrator/templates/udjamaflip/images/legacy.png" alt="Legacy: 1.0" style="margin-top:-2px;" />');
    }
    
    //sorts out the dock
    refresh_dock();
    //turns on draggable windows
    enable_draggable();

    $('.time').jclock();

    var containerHeight = $(document).height();
    containerHeight = (containerHeight - 55);
    $('#window-container').height(containerHeight);
    
    /* little bit to add effect to "start button" */
    
    $('li#start').hover(function() {
        $(this).children('img').attr('src','templates/udjamaflip/images/startButton-on.png');
    },function () {
        $(this).children('img').attr('src','templates/udjamaflip/images/startButton.png');
    });
    
    /* deals with window behaviours */

    $(".window").live("click", function () {
        $.myGlobals.zIndex++;
        $(this).css('z-index', $.myGlobals.zIndex);
    });

    $(".window *").live("click", function () {
        $.myGlobals.zIndex++;
        $(this).closest('.window').css('z-index', $.myGlobals.zIndex);
    });
    
    $('a.refresh').live("click", function () {
        var theId = $(this).parents('.window').find('iframe').attr('id');
        document.getElementById(theId).contentDocument.location.reload(true);
    });
    
    $('a.toggleSize').live("click", function () {

        /*
        var contentHeight = (height - 50);
        var contentWidth = (width - 36);
        var rightBorder = (height - 36);
        var leftBorder = (height - 35);
        var bottomBorder = (width - 35);
        */

        if ($(this).parents('.window').height() != $('#window-container').height()) {
            var tmpPos = $('#window-container').position();
            windows[$(this).attr('id') + '-height'] = $(this).parents('.window').height();
            windows[$(this).attr('id') + '-width'] = $(this).parents('.window').width();
            windows[$(this).attr('id') + '-left'] = tmpPos.left;
            windows[$(this).attr('id') + '-top'] = tmpPos.top;
            
            $(this).parents('.window').css('left', '0');
            $(this).parents('.window').css('top', '0');

            $(this).parents('.window').css('height', $('#window-container').height());
            $(this).parents('.window').find('.content').css('height', ($('#window-container').height() - 50) + 'px');
            $(this).parents('.window').css('width', $('#window-container').width() + 'px');
            $(this).parents('.window').find('.content').css('width', ($('#window-container').width() - 36) + 'px');
            $(this).parents('.window').find('.leftBorder').css('height', ($('#window-container').height() - 35) + 'px');
            $(this).parents('.window').find('.rightBorder').css('height', ($('#window-container').height() - 35) + 'px');
            $(this).parents('.window').find('.bottomBorder').css('width', ($('#window-container').width() - 35) + 'px');
            $(this).parents('.window').find('.bottomRightCorner').attr('src','templates/udjamaflip/images/window/bottomRightCorner.png');
            $(this).parents('.window').find('.bottomRightCorner').removeClass('resizeable');
        }
        else {
            $(this).parents('.window').css('height', windows[$(this).attr('id') + '-height'] + 'px');
            $(this).parents('.window').find('.content').css('height', (windows[$(this).attr('id') + '-height'] - 50) + 'px');
            $(this).parents('.window').css('width', windows[$(this).attr('id') + '-width'] + 'px');
            $(this).parents('.window').find('.content').css('width', (windows[$(this).attr('id') + '-width'] - 36) + 'px');
            $(this).parents('.window').find('.leftBorder').css('height', (windows[$(this).attr('id') + '-height'] - 35) + 'px');
            $(this).parents('.window').find('.rightBorder').css('height', (windows[$(this).attr('id') + '-height'] - 35) + 'px');
            $(this).parents('.window').find('.bottomBorder').css('width', (windows[$(this).attr('id') + '-width'] - 35) + 'px');

            $(this).parents('.window').css('top', windows[$(this).attr('id') + '-top']);
            $(this).parents('.window').css('left', windows[$(this).attr('id') + '-left']);
            $(this).parents('.window').find('.bottomRightCorner').attr('src','templates/udjamaflip/images/window/bottomRightCornerExpand.png');
            $(this).parents('.window').find('.bottomRightCorner').addClass('resizeable');
        }

        $.myGlobals.zIndex++;
        $(this).css('z-index', $.myGlobals.zIndex);
    });

    $(".close").live("click", function () {
        $(this).parents(".window").remove();
    });
    
    $(".minimise").live("click", function () {
        var pid = $(this).parents("[id^=win]").attr("id")
        $(this).parents(".window").css('display', 'none');

        var title = $(this).parents('.window').find('.title').find('span').html();
        $(".dock-container").append('<a href="#" class="maximise dock-item" id="' + pid + '"><span>' + title + '</span><img src="templates/udjamaflip/images/icons/TouchPad.png" alt="' + pid + '" /></a>');
        refresh_dock();
    });

    /* after load */

    $('a.reload').live("click", function () {
        $.myGlobals.zIndex++;
        $("div[id^=win]").css('display', 'block');
        $("div[id^=win]").css('z-index', $.myGlobals.zIndex);        
        $(this).remove();
        refresh_dock();
    });

    $('a.maximise').live("click", function () {
        var pid = $(this).attr("id")
        $("div#" + pid).css('display', 'block');
        $(this).remove();
        refresh_dock();
        $.myGlobals.zIndex++;
        $(this).css('z-index', $.myGlobals.zIndex);
    });
            
    /* opens a new window and deals with menu behaviours */

    $("ul li ul li a").click(function () {
        $.myGlobals.winCount++;
        $.myGlobals.zIndex++;

        var title = $(this).attr('title');
        if (title == '') {
            title = $(this).html();
        }
        var src = $(this).attr('href');
        var rel = $(this).attr('rel');
        
        if ($(this).attr('target') != '_blank')
        {
            addWindow($.myGlobals.winCount, $.myGlobals.zIndex, src, rel, title);
            return false;
        }
    });
    
    $("div.taskbar div.status a").click(function () {
        
        if (!$(this).parent('span.logout') && $(this).attr('target') != '_blank')
        {
            $.myGlobals.winCount++;
            $.myGlobals.zIndex++;

            var title = $(this).attr('title');
            if (title == '') {
                title = $(this).html();
            }
            var src = $(this).attr('href');
            var rel = $(this).attr('rel');

            addWindow($.myGlobals.winCount, $.myGlobals.zIndex, src, rel, title);

            return false;
        }
    });

    $("a.dock-item").click(function () {
        $.myGlobals.winCount++;
        $.myGlobals.zIndex++;

        var title = $(this).attr('title');
        var src = $(this).attr('href');
        var rel = $(this).attr('rel');

        addWindow($.myGlobals.winCount, $.myGlobals.zIndex, src, rel, title);

        return false;
    });

});