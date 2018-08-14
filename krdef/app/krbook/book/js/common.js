$(document).ready(function()
{
    $('.nav-system-book').addClass('active');
    $('#article' + v.objectID).addClass('active');
    if(v.fullScreen)
    {
        $('html, body').css('height', '100%');

        curPos = sessionStorage.getItem('curPos');
        if(curPos) $('.fullScreen-catalog').animate({scrollTop: curPos}, 0);

        $('.article').click(function(){sessionStorage.setItem('curPos', $('.fullScreen-catalog').scrollTop());});
    }
    /* Set current active moduleMenu. */
    if(typeof(v.path) != 'undefined')
    {
        $('.leftmenu li.active').removeClass('active');
        $.each(v.path, function(index, bookID) 
        { 
            $(".book-list a[href$='bookID=" + bookID + "']").parent().css('font-weight', 'bold');
        })
    }
    else
    {
        $(".book-list a[href*='" + config.currentMethod + "']").css('font-weight', 'bold');
    }
});
