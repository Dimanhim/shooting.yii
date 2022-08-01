/**
 * удаление колонки
 * */
function deleteColumn(jObj) {
    let id = jObj.attr('data-id');

    // убираем чекбокс
    unCheckCheckbox(id);

    setCashPlaces();

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

function getTimeArray() {
    let times = [9,10,11,12,13,14,15,16,17,18,19,20,21,22];
    let result = [];
    times.forEach((item) => {
        result.push(item * 3600);
    });
    return result;
}

/**
 * Равняет все строки по высоте
 *
 * */
function alignColumns() {
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
}

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
     * работа DatePicker в сайдбаре
     * */

    $('#panel-datepicker').datepicker({
        defaultDate: $('#main-o').attr('data-date'),
    }).on('change', function() {
        let date = $(this).val();
        setCashDate(date);
    });
    $('.change-date-column-o').datepicker().on('change', function() {
        console.log('change', $(this));
        let value = $(this).val();
        let place_id = $(this).attr('data-place');
        console.log('place_id', place_id)
        console.log('value', value)
        $.get('timetable/set-place-date', {place_id: place_id, date: value}, function(data) {
            if(data.result) {
                updateTable();
            }
            console.log('data', data)
        });

    });
}


function maskInit() {
    $(".phone-mask").inputmask({"mask": "+7 (999) 999-9999", "clearIncomplete": true});
    $(".select-time").inputmask({"mask": "99:99"});
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
            alert('Error!');
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
            alert('Error!');
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
        success: function (res) {
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
            console.log(data)
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
            alert('Error!');
        }
    });
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
    setTimeout(function() {
        $.get('site/update-main', function(res) {
            if(res) {
                updateTable();
                console.log('update table')
            }
            //console.log('request')
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




    $('.column-item-o:not(.column-line-o)').draggable({
        start: function (event, ui) {
            dragObj = $(event.target);
            start_time = dragObj.attr('data-time');
            start_timetable_id = dragObj.attr('data-id');


        }
    });
    $('.column-line-o').droppable({
        drop: function (event, ui) {
            dropObj = $(event.target);
            stop_time = dropObj.attr('data-time');
            stop_date = dropObj.attr('data-date');
            stop_place = dropObj.attr('data-place');

            console.log('start_time', start_time);
            console.log('start_timetable_id', start_timetable_id);
            console.log('stop_time', stop_time);
            console.log('stop_date', stop_date);
            console.log('stop_place', stop_place);

            $.get('/timetable/drop-record', {start_timetable_id: start_timetable_id, start_time: start_time, stop_time: stop_time, stop_date: stop_date, stop_place: stop_place}, function(json) {
                console.log(json)
                if(json.result) {
                    updateTable();
                }
            });
        }
    });
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
            alignColumns();
            let date = new Date();
            let time_end = date.getTime();
            console.log('duration ms ', (time_end - time_begin));
            initDatepicker();
            initDragNDrop();
        },
        error: function () {
            console.log('Error!');
        }
    });
}
