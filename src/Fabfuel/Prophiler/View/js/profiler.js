$('json').each(function (index, el) {
    el.innerHTML = JSON.stringify(JSON.parse(el.innerHTML), null, '    ');
});

$(document).on('click','#prophiler nav li', function() {
    var hs = $(this).hasClass("selected");
    $('#prophiler .datacollector').hide();
    $('#prophiler nav li').removeClass('selected');
    if(hs) {
        $($(this).data('target')).hide();
        return;
    }
    $(this).addClass("selected");
    $($(this).data('target')).toggle();
});

// Only for development - toolbar - fetch ajax prophiler events
$( document ).ajaxComplete(function(event, jqxhr, settings) {
    if(typeof jqxhr.responseJSON !== 'undefined' && typeof jqxhr.responseJSON.profiler_toolbar !== 'undefined') {
        console.log('Injecting profiler toolbar');
        $('#prophiler').prepend(jqxhr.responseJSON.profiler_toolbar);
    }
});