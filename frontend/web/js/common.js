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
        //updatePlaceAccordeon($(this).attr('data-adress'))
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
        showViewModal(timetable_id);
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

    $('body').on('change', '.option-input-from-o', function(e) {
        e.preventDefault();
        changeAttribute('time_from', $(this).val(), false, false)
    });

    $('body').on('change', '.option-input-to-o', function(e) {
        e.preventDefault();
        changeAttribute('time_to', $(this).val(), false, false)
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
        //if(!validateRepeat()) return false;
        let data = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'GET',
            data: data,
            success: function (json) {
                if(json.result) {
                    $('#timetable-create').modal('hide');
                    $('#timetable-result').modal('show');
                    $('.modal-body-result-o').text(json.message);
                    updateTable();
                }
                else if(json.message) {
                    displayErrorMessage(json.message)
                }

            },
            error: function () {
                console.log('Error!');
            }
        });
    });
    /**
     * submit формы редактирования записи
     * */
    $('body').on('submit', '#form-edit-timetable', function(e) {
        e.preventDefault();
        let form = $(this);
        let id = form.attr('data-id');
        let data = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'GET',
            data: data,
            success: function (json) {
                $('#timetable-edit').modal('hide');
                $('#edit-modal').html('');
                showViewModal(json.id)
                updateTable();
            },
            error: function () {
                console.log('Error!');
            }
        });
    });
    $('body').on('change', '#timetable-repeat_id', function(e) {
        e.preventDefault();
        let repeat_block = $('.repeat-group');
        if($(this).is(':checked')) {
            repeat_block.css('display', 'block');
        }
        else {
            repeat_block.css('display', 'none');
        }
    });
    /**
     * смена просмотра записи в модали на редактирование
     * */
    $('body').on('click', '.btn-edit-timetable-form-o', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        showEditModal(id);
    });
    /**
     * клик на поле в модальном окне разворачивает его редактирование
     * */
    $('body').on('click', '.timetable-editable-view .editable-field-text-o', function(e) {
        e.preventDefault();
        let input = $(this).next();
        input.css('display', 'inherit');
        $(this).css('display', 'none')
        input.focus()
    });
    $('body').on('blur', '.timetable-editable-view .editable-field-input-o', function(e) {
        e.preventDefault();
        let span = $(this).prev();
        let value = $(this).val();
        let attribute = $(this).attr('data-attribute');
        changeAttribute(attribute, value, $(this), span)
    });

    /**
     * Удаляет запись
     * */
    $('body').on('click', '.btn-delete-record-o', function(e) {
        e.preventDefault();
        if(!confirm('Вы действительно хотите удалить запись?')) return false;
        let timetable_id = $('#timetable-item').attr('data-id');
        $.get('timetable/delete-record', {timetable_id: timetable_id}, function(json) {
            if(json.result) {
                displaySuccessMessage(json.message)
            }
            if(json.result) {
                displayErrorMessage(json.message)

            }
            $('#timetable-item').modal('hide');
            updateTable()
        })
    });

    /**
     * Выделяет дни недели в модальном окне
     * */
    $('body').on('change', '.repeats-days-o input', function(e) {
        e.preventDefault();
        let value = $(this).val();
        let self = $(this);
        let type = self.attr('data-type');
        if(value == 100) {
            checkValue(self)
            if(self.is(':checked')) {
                $('.repeats-days-o input').each(function(index, element) {
                    if($(element).val() != 100) {
                        checkValueImp($(element));
                    }
                })
            }
            else {
                $('.repeats-days-o input').each(function(index, element) {
                    if($(element).val() != 100) {
                        unCheckValueImp($(element));
                    }
                })
            }
        }
        else {
            if(type == 'radio') {
                $('.repeats-days-o input').each(function(index, element) {
                    let el = $(element);
                    el.prop('checked', false);
                    el.parents('label').removeClass('active');
                });
                self.prop('checked', true);
            }
            checkValue(self);
        }
    });

    /**
     * Выдвигает нужные поля дл язаполнения в повторах
     * */
    $('body').on('change', '.select-period-o', function(e) {
        e.preventDefault();
        $('.hidden-field').css('display', 'none');
        let type_id = $(this).find('option:selected').attr('data-type-id');
        let field = $('.hidden-field[data-type-id="' + type_id + '"]');
        field.css('display', 'table-row')
    });

    /**
     * Сворачивает или разворачивает логи
     * */
    $('body').on('click', '.show-logs-o', function(e) {
        e.preventDefault();
        if($('.logs-table-o').is(':visible')) {
            $('.logs-table-o').css('display', 'none');
            $(this).find('i.bi').removeClass('bi-chevron-up').addClass('bi-chevron-down');
        }
        else {
            $('.logs-table-o').css('display', 'table');
            $(this).find('i.bi').removeClass('bi-chevron-down').addClass('bi-chevron-up');
        }
    });



    //showCreateModal();

    //alignColumns();

    updateTimeoutMain();

    initPlugins();
});
// https://dwweb.ru/rastyanut_myishkoy.html - растягивание блока мышью на js
// https://api.jqueryui.com/resizable/ вроде как с использованием jquery-ui
