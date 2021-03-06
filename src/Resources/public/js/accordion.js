(function ($) {
    $(document).ready(function () {
        $(document).accordion({
            // Put custom options here
            heightStyle: 'content',
            header: 'div.toggler',
            collapsible: true,
            create: function (event, ui) {
                ui.header.addClass('active');
            },
            activate: function (event, ui) {
                ui.newHeader.addClass('active');
                ui.oldHeader.removeClass('active');
            }
        });
    });
})(jQuery);
