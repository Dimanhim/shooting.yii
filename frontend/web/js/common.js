$(document).ready(function() {

    /**
     * смена даты в шапке
     * */
    /*$('body').on('click', '#btn-change-date', function(e) {
        e.preventDefault();
        $('#change-date-menu').toggle();
    });*/

    /**
     * смена даты в шапке - клик на ссылку из всплывающего меню
     * */
    /*$('body').on('click', '#change-date-menu a', function(e) {
        e.preventDefault();
        let self = $(this);
        let text = self.text();
        let val = self.attr('data-val');
        $('#btn-change-date .date-name').text(text);
        $('#change-date-menu').toggle();
        updateTable();
    });*/

    /*$('body').on('click', '.change-date-column-o', function(e) {
        e.preventDefault();
        $(this).datepicker();
    });*/





    $('body').on('change', '.form-check-input-o', function(e) {
        e.preventDefault();
        setCashPlaces();
        checkAccordeonCheckboxes();
    });

    /**
     * Устанавливает Email в сайдбаре при выборе
     * */
    $('body').on('click', '.panel-block-email .emails-list-o a', function(e) {
        e.preventDefault();
        $(this).parents('.accordion-item').find('button').text($(this).text());
    });

    /**
     * показать модальное окно при клике на элемент расписания
     * */
    $('body').on('click', '.column-item-o:not(.column-line-o)', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let timetable_id = $(this).attr('data-id');
        $.ajax({
            url: 'site/show-view',
            type: 'GET',
            data: {id: timetable_id},
            success: function (res) {
                $('#view-modal').html(res);
                $('#timetable-item').modal('show');
            },
            error: function () {
                alert('Error!');
            }
        });
    });

    /**
     * Изменение цвета плашки в расписании через окно просмотра
     * */
    $('body').on('change', '.timetable-change-color-o', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let color_id = $(this).val();
        $.ajax({
            url: 'site/change-timetable-color',
            type: 'GET',
            data: {id: id, color_id: color_id},
            success: function (json) {
                let res = JSON.parse(json);
                if(res.result) {
                    updateTable();
                }
                return false;
            },
            error: function () {
                alert('Error!');
            }
        });
    });

    /**
     * Удаляет колонку по клику на иконку удаления
     * */
    $('body').on('click', '.delete-column-o', function(e) {
        e.preventDefault();
        if($(this).parents('.column-o').hasClass('temp-column')) {
            deleteColumnPlaceDate($(this));
        }
        else {
            deleteColumn($(this));
        }
    });

    /**
     * В форме создания записи подтягивает время ДО на основе ОТ
     * */
    $('body').on('change', '.timetableform-time_from-o', function(e) {
        e.preventDefault();
        let time = $(this).val();
        fillTimeTo(time)
    });

    /**
     * показываем форму создания события по клику на кнопку
     * */
    $('body').on('click', '.new-event-o', function(e) {
        e.preventDefault();
        showCreateModal();
    });

    /**
     * показываем форму создания события по клику на поле
     * */
    $('body').on('click', '.column-line-o:not(.column-item-o)', function(e) {
        e.preventDefault();
        let time = $(this).attr('data-time');
        let place = $(this).attr('data-place');
        let date = $(this).attr('data-date');
        showCreateModal(time, date, place);
    });

    /**
     * submit формы добавления записи
     * */
    $('body').on('submit', '#form-create-timetable', function(e) {
        e.preventDefault();
        let form = $(this);
        let data = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'GET',
            data: data,
            success: function (json) {
                let res = JSON.parse(json);
                $('#timetable-create').modal('hide');
                $('#timetable-result').modal('show');
                $('.modal-body-result-o').text(res.message);
                updateTable();
            },
            error: function () {
                alert('Error!');
            }
        });
    });



    //showCreateModal();

    initDatepicker();

    initDragNDrop();

    alignColumns();

    updateTimeoutMain();
});
