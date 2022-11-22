/**
 * удаление колонки
 * */
function deleteColumn(jObj) {
    let id = jObj.attr('data-id');

    // убираем чекбокс
    unCheckCheckbox(id);

    // устанавливаем новые стрельбища в кэш
    setCashPlaces();

    // обновляем аккордеон
    //updatePlaceAccordeon(id);

    // скрываем аккордеон
    checkAccordeonCheckboxes();

    // удаляем колонку
    jObj.parents('.column-o').remove();


}

/**
 * удаление временной колонки
 * */
function deleteColumnPlaceDate(jObj) {
    let id = jObj.attr('data-id');
    let date = jObj.attr('data-date');

    setCashPlaceDates(id, date);

    //updatePlaceAccordeon(id);
}
/**
 * убирает чекбокс
 *
 * */
function unCheckCheckbox(id) {
    $('.form-check-input-o[data-id="' + id + '"]').removeAttr('checked');
}

/**
 * убирает чекбокс
 *
 * */
function checkAccordeonCheckboxes() {
    $('.accordion-item-o').each(function(index, element) {
        let el = $(element);
        if(el.find('.accordion-body-o input[type="checkbox"]:checked').length <1) {
            hideAccordeon(el);
        }
    });
}

/**
 * скрывает определенный аккордеон по вызову
 *
 * */
function hideAccordeon(jObj) {
    jObj.find('.accordion-button-o').addClass('collapsed');
    jObj.find('.accordion-collapse-o').removeClass('show');
}

/*function getTimeArray() {
    let times = [9,10,11,12,13,14,15,16,17,18,19,20,21,22];
    let result = [];
    times.forEach((item) => {
        result.push(item * 3600);
    });
    return result;
}*/

/**
 * Равняет все строки по высоте
 *
 * */
/*function alignColumns() {
    if($(window).width() < 576) return false;
    $('.column-row-o').each(function (ind, elem) {
        let timesArray = getTimeArray();

        let columnsHeight = [];
        let itemColumns = [];

        $(elem).find('.column-line-o').each(function(index, element) {
            let el = $(element);
            let time = el.attr('data-time');
            let height = el.height();
            itemColumns.push({
                time: time,
                height: height,
            });
        });
        timesArray.forEach((item) => {
            let heights = [];
            itemColumns.forEach((column) => {
                if(column.time == item) {
                    heights.push(column.height);
                }
            });
            let maxHeight = Math.max.apply(null, heights);
            columnsHeight.push({
                time: item,
                height: maxHeight,
            });
        });
        checkHeights(columnsHeight, $(elem));
    })

}
function checkHeights(arr, jObj) {
    arr.forEach((item) => {
        jObj.find(`.column-line[data-time="${item.time}"]`).height(item.height);
    });
}*/

/**
 * END AlignColumns
 * */


function initDatepickerForm() {
    /**
     * работа DatePicker в форме
     * */
    $('.select-date-form').datepicker({
        defaultDate: $('#main-o').attr('data-date'),
    });
    $('.change-date-column-o').datepicker();
}

function initDatepicker() {
    /**
     * работа DatePicker справа вверху
     * */
    $('#select-date').datepicker({
        defaultDate: $('#main-o').attr('data-date'),
    }).on('change', function() {
        let date = $(this).val();
        setCashDate(date);
    });

    /**
     * работа DatePicker во вью заявки
     * */
    $('#select-date-timetable-view').datepicker().on('change', function() {
        let date = $(this).val();
        changeAttribute('date', date, false, false)
    });


    /**
     * работа DatePicker в сайдбаре
     * */

    $('#panel-datepicker').datepicker({
        defaultDate: $('#main-o').attr('data-date'),
    }).on('change', function() {
        let date = $(this).val();
        setCashDate(date);
    });
    $('.change-date-column-o').datepicker().on('change', function() {
        let value = $(this).val();
        let place_id = $(this).attr('data-place');
        $.get('timetable/set-place-date', {place_id: place_id, date: value}, function(data) {
            if(data.result) {
                updateTable();
            }
        });

    });
}


function maskInit() {
    $(".phone-mask").inputmask({"mask": "+7 (999) 999-9999", "clearIncomplete": true});
    $(".select-time").inputmask({"mask": "99:99"});
}

/**
 * меняет значение отдельного аттрибута в модальном окне view записи
 * */
function changeAttribute(attribute, value, jObj, jObjSpan) {
    let timetable_id = $('#timetable-item').attr('data-id');
    $.get('timetable/change-value', {timetable_id: timetable_id, attributeName: attribute, value: value}, function(json) {
        if(json.result) {
            //displaySuccessMessage(json.message)
            if(jObj && jObjSpan && json.html) {
                jObjSpan.text(json.html).css('display', 'inherit');
                jObj.css('display', 'none')
            }
            displaySuccessMessage(json.message)
            updateTable()
        }
        else {
            displayErrorMessage(json.message)
            $(this).val(json.html)
        }
    });
}
/**
 * показывает модяльное окно просмотра
 * */
function showViewModal(timetable_id) {
    //console.log('showViewModal', timetable_id)
    $.ajax({
        url: 'site/show-view',
        type: 'GET',
        data: {id: timetable_id},
        success: function (res) {
            $('#view-modal').html(res);
            $('#timetable-item').modal('show');
            initDatepicker();
            initDatepickerForm();
            maskInit();

        },
        error: function () {
            console.log('Error!');
        }
    });
}
function validateRepeat() {
    displayErrorMessage('Введите повтор');
    return false;
}
function checkValue(inputObj) {
    console.log('checkValue')
    if(inputObj.is(':checked')) {
        inputObj.parents('label').addClass('active');
    }
    else {
        inputObj.parents('label').removeClass('active');
    }
}
function checkValueImp(inputObj) {
    inputObj.parents('label').addClass('active');
}
function unCheckValueImp(inputObj) {
    inputObj.parents('label').removeClass('active');
}
/**
 * показывает форму создания события
 * */
function showCreateModal(time, date, place) {
    let action = '/site/show-create-modal'
    $.ajax({
        url: action,
        type: 'GET',
        data: {time: time, date: date, place: place},
        success: function (json) {
            let data = JSON.parse(json);
            if(data.result) {
                $('#create-modal').html(data.html);
                $('#timetable-create').modal('show');
            }
            initDatepickerForm();
            maskInit();
            fillTimeTo();
        },
        error: function () {
            console.log('Error!');
        }
    });
}
/**
 * показывает форму редактирования события
 * */
function showEditModal(id) {
    let action = '/site/show-edit-modal'
    $.ajax({
        url: action,
        type: 'GET',
        data: {id: id},
        success: function (data) {
            if(data.result) {
                $('#timetable-item').modal('hide');
                $('#view-modal').html('');
                $('#edit-modal').html(data.html);
                $('#timetable-edit').modal('show');
            }
            initDatepickerForm();
            maskInit();
            fillTimeTo();
        },
        error: function () {
            console.log('Error!');
        }
    });
}
/**
 * отправляет запрос на бэк - там меняется дата и перезагружается страница
 * */
function setCashDate(date) {
    $.ajax({
        url: 'site/change-date',
        type: 'GET',
        data: {date: date},
        success: function (res) {
            window.location.reload();
            return false;
        },
        error: function () {
            console.log('Error!');
        }
    });
}
/**
 * отправляет запрос на бэк - там меняются стрельбища и перезагружается страница
 * */
function setCashPlaces() {
    let places = [];
    $('.accordion-body-o input[type="checkbox"]:checked').each(function(index, element) {
        let el = $(element);
        if(el.is(':checked')) {
            places.push({
                id: el.attr('data-id')
            });
        }
    })
    $.ajax({
        url: 'site/change-places',
        type: 'GET',
        data: {data: places},
        success: function (json) {
            if(json.result) {
                $('#adresses').html(json.adresses);
            }
            updateTable();
            //window.location.reload();
            return false;
        },
        error: function () {
            console.log('Error!');
        }
    });
}
/**
 * устанавливает в кэш новое доп. значение стрельбища на дату
 * */
function setCashPlaceDates(id, date) {
    $.ajax({
        url: 'site/change-place-dates',
        type: 'GET',
        data: {id: id, date: date},
        success: function (data) {
            updateTable();
            //window.location.reload();
            return false;
        },
        error: function () {
            console.log('Error!');
        }
    });
}

/**
 * добавляет время ДО в форме timetable
 * */
function fillTimeTo() {

    let time;
    if($('.timetableform-time_from-o').val()) {
        time = $('.timetableform-time_from-o').val();
    }
    else {
        time = 9 * 3600;
    }
    let action = '/site/get-times-to';
    $.ajax({
        url: action,
        type: 'GET',
        data: {time: time},
        success: function (json) {
            let data = JSON.parse(json);
            if(data.result) {
                $('.timetableform-time_to').html(data.html);
            }
        },
        error: function () {
            console.log('Error!');
        }
    });
}

function displaySuccessMessage(message) {
    $('.info-message').text(message);
    setTimeout(function() {
        $('.info-message').text('');
    }, 3000)
}
function displayErrorMessage(message) {
    $('.info-message').addClass('error').text(message);
    setTimeout(function() {
        $('.info-message').text('');
    }, 3000)
}

/*function updatePlaceAccordeon(place_id) {
    $.ajax({
        url: 'timetable/update-place-accordeon',
        type: 'GET',
        data: {place_id: place_id},
        success: function (json) {
            console.log('updatePlaceAccordeon', json)
            if(json.result) {
                $('.accordion-collapse-o[data-id="' + json.adress_id + '"]').html(json.html);
                updateTable();
            }

        },
        error: function () {
            console.log('Error!');
        }
    });
}*/
function sortPlaceColumns() {
    setCashPlaces()
}



/**
 * loader
 * */
function addPreloader() {
    $('.loader-block').css('display', 'block').addClass('loader');
}
function removePreloader() {
    $('.loader-block').css('display', 'none').removeClass('loader');
}
/**
 * END loader
 * */


/**
 * делает запрос раз в 5 секунд и если есть новые записи, обновляет таблицу
 * */
function updateTimeoutMain() {
    return true;
    setTimeout(function() {
        $.get('site/update-main', function(json) {
            if(json.result) {
                if(json.logout) {
                    window.location = '/site/logout';
                }
                if(json.update_table) {
                    updateTable();
                }
            }
            updateTimeoutMain();
        })
    }, 5000)
}

/**
 * инициализировать перетаскивание в таблице
 * */

function initDragNDrop() {
    let dragObj,
        dropObj,
        start_time,
        start_timetable_id,
        stop_date,
        stop_time,
        stop_place;




    /**
     * перетаскивание записей
     * */
    //$('.column-item-o:not(.column-line-o)').draggable({
    $('.column-drag-o').draggable({
        start: function (event, ui) {
            dragObj = $(event.target);
            dragObj.css('z-index', 999);
            start_time = dragObj.attr('data-time');
            start_timetable_id = dragObj.attr('data-id');
        }
    });
    //$('.column-line-o').droppable({
    $('.column-drop-o').droppable({
        drop: function (event, ui) {
            dropObj = $(event.target);
            stop_time = dropObj.attr('data-time');
            stop_date = dropObj.attr('data-date');
            stop_place = dropObj.attr('data-place');
            console.log('drop')
            $.get('/timetable/drop-record', {start_timetable_id: start_timetable_id, start_time: start_time, stop_time: stop_time, stop_date: stop_date, stop_place: stop_place}, function(json) {
                console.log(json)
                if(json.result) {
                    displaySuccessMessage('Сохранено успешно')
                    updateTable();
                }
            });
        },
        activate: function(event, ui) {
            /*dropObj = $(event.target);
            let time = dropObj.attr('data-time');
            let timeObj = dropObj.parents('.calendar-column-body').find('.column-time-o[data-time="' + time + '"]')
            timeObj.css({
                borderTop: "medium double #dc3545",
            });*/
        },
        deactivate: function(event, ui) {
            //dropObj = $(event.target);
        },
        over: function(event, ui) {
            dropObj = $(event.target);
            let time = dropObj.attr('data-time');
            let timeObj = dropObj.parents('.calendar-column-body').find('.column-time-o[data-time="' + time + '"]')
            timeObj.css({
                border: "medium double #dc3545",
                backgroundColor: "#dc3545"
            });
        },
        out: function(event, ui) {
            dropObj = $(event.target);
            let time = dropObj.attr('data-time');
            let timeObj = dropObj.parents('.calendar-column-body').find('.column-time-o[data-time="' + time + '"]')
            timeObj.css("border", "").css("background-color", "");
        },
        tolerance: 'pointer',
    });
}
function initResizable() {
    let id,
        start_height,
        stop_height,
        resizeObj;
    $('.column-item-o').resizable({
        start: function(ev, ui) {
            resizeObj = $(ev.target);
            start_height = resizeObj.height();
            resizeObj.css('z-index', 100);
            resizeObj.addClass('resizeble-obj')
        },
        resize: function(ev, ui) {
            //console.log('resize ev', ev);
            //console.log('resize ui', ui);
        },
        stop: function(ev, ui) {
            resizeObj = $(ev.target);
            id = resizeObj.attr('data-id');
            stop_height = resizeObj.height();
            //console.log('id', id);
            //console.log('start_height', start_height);
            //console.log('stop_height', stop_height);
            $.get('/timetable/resize-record', {timetable_id: id, start_height: start_height, stop_height: stop_height}, function(json) {
                if(json.result) {
                    updateTable();
                }
                resizeObj.css('z-index', 10);
                resizeObj.removeClass('resizeble-obj')
            });

        }
    });
}
function initSortable() {
    $('.accordion-body-o').sortable({
        stop(ev, ui) {
            sortPlaceColumns();
            //console.log('sortable ev', ev);
            //console.log('sortable ui', ui);
        }
    })
}

function updateLogs() {
    if($('#logs').length) {
        let timetable_id = $('#timetable-item').attr('data-id');
        $.get('timetable/update-logs', {timetable_id: timetable_id}, function(data) {
            if(data.result) {
                $('#logs').html(data.html)
            }
        });
    }
}



/**
 * Полностью обновляет таблицу записей
 * */
function updateTable() {
    let date = new Date();
    let time_begin = date.getTime();
    $.ajax({
        url: '/site/update-table',
        type: 'GET',
        success: function (res) {
            $('.column-o').remove();
            //$('.column-sidebar-o').after(res);
            $('.main-columns-o').html(res);
            //alignColumns();
            let date = new Date();
            let time_end = date.getTime();
            //console.log('duration ms ', (time_end - time_begin));
            alignRecords();
            initPlugins();
            updateLogs();

        },
        error: function () {
            console.log('Error!');
        }
    });
}
function initPlugins() {
    initDatepicker();
    initDragNDrop();
    initResizable();
    initSortable();
}

function alignRecords() {
    return true;
    console.log('align records');
    let empty_html = '<div class="col-sm column-calendar-block" data-rows="1"></div>';
    // перебираем все корты
    $('.column-o').each(function(index, element) {
        // сначала вычисляем самую длинную по записям строку корта
        // это будет количество столбцов в каждой строке корта
        let el = $(element);
        let column_line = el.find('.column-line-o');
        let max_rows = 0;
        column_line.each(function(index_column_line, element_column_line) {
            let el_column_line = $(element_column_line);
            let count_blocks = el_column_line.find('.column-calendar-block-o').length;
            if(max_rows < count_blocks) {
                max_rows = count_blocks;
            }
        });
        // ---

        // далее

        // для теста - далее перебираем каждую строку корта
        // и дополняем нужным количеством пустых строк, чтобы была сетка

        // Сетка построена

        column_line.each(function(index_column_line, element_column_line) {
            let el_column_line = $(element_column_line);
            let first_time_block = el_column_line.find('.column-calendar-block-time-o');
            let column_calendar_block = el_column_line.find('.column-calendar-block-o');
            let count_blocks = column_calendar_block.length;
            let count_empty_blocks = max_rows - count_blocks;
            if(count_empty_blocks) {
                for(let i = 0; i < count_empty_blocks; i++) {

                    // вставляет в начало
                    //first_time_block.after(empty_html);

                    // вставляет в конец
                    el_column_line.find('.row').append(empty_html);
                }
            }
        });

        // нужно сделать массив
    })
}
