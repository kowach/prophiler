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
    // Prepend profiler_toolbar from AJAX JSON response
    // { "profiler_toolbar" : "..." }
    if(typeof jqxhr.responseJSON !== 'undefined' && typeof jqxhr.responseJSON.profiler_toolbar !== 'undefined') {
        console.log('Injecting AJAX JSON profiler toolbar');
        $('#prophiler').prepend(jqxhr.responseJSON.profiler_toolbar);
    }
    // Prepend profiler_toolbar from AJAX HTML response from
    //  &lt;script id="profiler_toolbar" type="text/x-custom-template"&gt;
    //     ...
    // &lt;/script&gt;
    var profiler_toolbar=$('#profiler_toolbar');
    if(profiler_toolbar.length>0) {
        console.log('Injecting AJAX HTML profiler toolbar');
        $('#prophiler').prepend(profiler_toolbar.html());
        profiler_toolbar.remove();
    }
});