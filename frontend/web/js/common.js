$(document).ready(function() {

    $('body').on('click', '#btn-change-date', function(e) {
        e.preventDefault();
        $('#change-date-menu').toggle();
    });
    $('body').on('click', '#change-date-menu a', function(e) {
        e.preventDefault();
        let self = $(this);
        let text = self.text();
        let val = self.attr('data-val');
        $('#btn-change-date .date-name').text(text);
        $('#change-date-menu').toggle();
        updateTable();
    });

    $('#select-date').datepicker($.datepicker.regional["ru"], {

    }).on('change', function() {
        console.log('change')
    });

    $('#panel-datepicker').datepicker($.datepicker.regional["ru"]).on('change', function() {
        console.log('change main')
    });

    $('body').on('click', '.panel-block-email .emails-list-o a', function(e) {
        e.preventDefault();
        $(this).parents('.accordion-item').find('button').text($(this).text());
    });

    function updateTable() {
        console.log('update');
    }











});
