jQuery(document).ready(function ($) {
    var month = new Date().getMonth() + 1;
    var year = new Date().getFullYear();
    function reloadCalendar(m, y) {
        window.location.search = '?page=dl-woo-estimated-delivery-settings&dl_ed_month=' + m + '&dl_ed_year=' + y;
    }

    $('#dl-ed-calendar').on('click', '.dl-ed-day', function () {
        var $td = $(this);
        var date = $td.data('date');
        $td.toggleClass('dl-ed-holiday');
    });

    $('#dl-ed-save-holidays').on('click', function () {
        var holidays = [];
        $('#dl-ed-calendar .dl-ed-holiday').each(function () {
            holidays.push($(this).data('date'));
        });
        $.post(dlEdCalendar.dlEdAjaxUrl, {
            action: 'dl_ed_save_holidays',
            holidays: holidays
        }, function (resp) {
            $('#dl-message').html(resp.success ? '¡Guardado!' : 'Error: ' + resp.data);
            $('#dl-message').slideDown().delay(3000).slideUp();
        });
    });

});
