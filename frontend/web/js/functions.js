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
    let timesArray = getTimeArray();

    let columnsHeight = [];
    let itemColumns = [];

    $('.column-line-o').each(function(index, element) {
        let el = $(element);
        let time = el.attr('data-time');
        let height = el.height();
        itemColumns.push({
            time: time,
            height: height,
        });
    });
    //console.log('itemColumns', itemColumns)
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
    checkHeights(columnsHeight);
}
function checkHeights(arr) {
    arr.forEach((item) => {
        $(`.column-line[data-time="${item.time}"]`).height(item.height);
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
}

function maskInit() {
    $(".phone-mask").inputmask({"mask": "+7 (999) 999-9999", "clearIncomplete": true});
    $(".select-time").inputmask({"mask": "99:99"});
}
/**
 * показывает форму создания события
 * */
function showCreateModal(time, place) {
    let action = '/site/show-create-modal'
    $.ajax({
        url: action,
        type: 'GET',
        data: {time: time, place: place},
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
            console.log(res);
            updateTable();
            //window.location.reload();
            return false;
        },
        error: function () {
            alert('Error!');
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

function updateTimeoutMain() {
    setTimeout(function() {
        $.get('site/update-main', function(res) {
            if(res) {
                updateTable();
                console.log('update table')
            }
            console.log('request')
            updateTimeoutMain();
        })
    }, 5000)
}



/**
 * Полностью обновляет таблицу записей
 * */
function updateTable() {
    let date = new Date();
    let time_begin = date.getTime();
    console.log('update');
    $.ajax({
        url: '/site/update-table',
        type: 'GET',
        success: function (res) {
            $('.column-o').remove();
            $('.column-sidebar-o').after(res);
            alignColumns();
            let date = new Date();
            let time_end = date.getTime();
            console.log('duration ms ', (time_end - time_begin));
        },
        error: function () {
            alert('Error!');
        }
    });
}
