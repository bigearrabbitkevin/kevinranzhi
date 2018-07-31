


/* hide side. */
$('.side-handle').click(function()
{
	// $('#fixedHeader').remove();
	if($(this).parents('.with-side').hasClass('hide-side'))
	{
		$('.with-side').removeClass('hide-side');
		$('.side-handle i').removeClass('icon-caret-right');
		$('.side-handle i').removeClass('icon-collapse-full');
		$('.side-handle i').addClass('icon-expand-full');
		$('.side-handle i').addClass('icon-caret-left');
		$.cookie('todoCalendarSide', 'show', {path: config.webRoot});
	}
	else
	{
		$('.side-handle i').removeClass('icon-expand-full');
		$('.side-handle i').removeClass('icon-caret-left');
		$('.side-handle i').addClass('icon-collapse-full');
		$('.side-handle i').addClass('icon-caret-right');
		$('.with-side').addClass('hide-side');
		$.cookie('todoCalendarSide', 'hide', {path: config.webRoot});
	}
});