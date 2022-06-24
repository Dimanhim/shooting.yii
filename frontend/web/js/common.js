$(document).ready(function() {

    /**
     * смена даты в шапке
     * */
    $('body').on('click', '#btn-change-date', function(e) {
        e.preventDefault();
        $('#change-date-menu').toggle();
    });

    /**
     * смена даты в шапке - клик на ссылку из всплывающего меню
     * */
    $('body').on('click', '#change-date-menu a', function(e) {
        e.preventDefault();
        let self = $(this);
        let text = self.text();
        let val = self.attr('data-val');
        $('#btn-change-date .date-name').text(text);
        $('#change-date-menu').toggle();
        updateTable();
    });

    /**
     * работа DatePicker справа вверху
     * */
    $('#select-date').datepicker($.datepicker.regional["ru"], {

    }).on('change', function() {
        console.log('change')
    });

    /**
     * работа DatePicker в сайдбаре
     * */

    $('#panel-datepicker').datepicker($.datepicker.regional["ru"]).on('change', function() {
        console.log('change main')
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
    $('body').on('click', '.column-item-o', function(e) {
        e.preventDefault();
        $('#timetable-item').modal('show');
    });

    /**
     * Удаляет колонку по клику на иконку удаления
     * */
    $('body').on('click', '.delete-column-o', function(e) {
        e.preventDefault();
        $(this).parents('.column-o').remove();
    });

    /**
     * Равняет все строки по высоте
     *
     * */
    function alignColumns() {

        if($(window).width() < 576) return false;
        let timesArray = [9,10,11,12,13,14,15,16,17,18,19,20,21,22];

        let columnsHeight = [];
        let itemColumns = [];

        $('.column-line').each(function(index, element) {
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
        checkHeights(columnsHeight);
    }
    function checkHeights(arr) {
        arr.forEach((item) => {
           $(`.column-line[data-time="${item.time}"]`).height(item.height);
        });
    }
    alignColumns();
    /**
     * END AlignColumns
     * */

    // test
    //$('#timetable-item').modal('show');
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





    function updateTable() {
        console.log('update');
    }














});
