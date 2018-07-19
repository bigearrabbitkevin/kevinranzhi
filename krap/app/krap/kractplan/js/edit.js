$(document).ready(function()
{
    $.setAjaxForm('#editForm');
    $('.user-chosen').chosen({no_results_text: '', placeholder_text:' ', disable_search_threshold: 1, search_contains: true, width: '100%'});
})
