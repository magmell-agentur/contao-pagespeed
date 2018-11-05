jQuery(function ($) {
    $('a[data-lightbox]').map(function () {
        $(this).colorbox({
            // Put custom options here
            loop: false,
            rel: $(this).attr('data-lightbox'),
            maxWidth: '95%',
            maxHeight: '95%'
        });
    });
});
