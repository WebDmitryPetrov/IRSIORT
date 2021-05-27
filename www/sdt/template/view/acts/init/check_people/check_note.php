<?php
/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 18.09.2017
 * Time: 17:42
 */
$date_now=date('d.m.Y');

$note_script = /** @lang javascript */
    <<<EOFN

        $('.js-check-note-button').off('click');
        $('.js-check-note-button').on('click', function (E) {
            E.preventDefault();
var isFree = $(this).data('is_free');
            if ($('.js-check-note-button').prop('disabled')) {
                alert('Поиск...');
                return;
            }

            var tthis = $(this);
            var name = tthis.data('name');
            var new_man_id = tthis.data('new_man_id');
            var level_id = tthis.data('level_id');

            var num = $('.old_note_num.man_' + new_man_id).val();


            if (!num.trim().length) {

                alert('Введите номер справки!');
                return;
            }

            $('.js-check-note-button').prop('disabled', true);





            $.ajax({
                    type: 'POST',
                    url: './index.php?action=check_old_note',
                    data: {
                        is_free: isFree,
                        num: num,
                        new_man_id: new_man_id,
                        level_id: level_id
                    },
                    dataType: "json",
                    complete: function () {
                        $('.js-check-note-button').prop('disabled', false);
                    },
                    success: function (response) {
                        if (response.error == 'ok') {
                            var result = response["result"];


                            var text = "Найден пользователь:<br><strong>"
                                + result.name_rus + " " + result.surname_rus
                                + "</strong>";

                            modal_confirm_note(name, result, text, num, new_man_id);


                        }
                        else if (response.error == 'no_free'){
                            alert ("Бесплатная пересдача невозможна!");
                        }
                        
                        else if (response.error == 'denied_level') {
                            alert("Указанная справка выдана по другому уровню тестирования!");
                        }
                        else if (response.error == 'number_more_1') {
                            alert("Бесплатная пересдача невозможна, так как экзамен пересдается в " + response.number + " раз");
                        }

                        else if (response.error == 'too_fast'){
                        alert("Нарушено условие временного интервала после экзамена - оформить пересдачу позже");
                        }
                        else {
                            var text = "Указанная справка НЕ НАЙДЕНА!";


                            modal_confirm_note(name, 'not_found', text, num, new_man_id);
                        }
                    }
                }
            );

        });


        $('.js-clean-note-button').off('click');
        $('.js-clean-note-button').on('click', function (E) {
            E.preventDefault();
            if (!confirm('Вы действительно хотите удалить всю информацию данного тестируемого?')) return false;
            if ($('.js-clean-note-button').prop('disabled')) {
                alert('Очистка');
                return;
            }

            var tthis = $(this);

            var new_man_id = tthis.data('new_man_id');
            var new_man_act_id = tthis.data('new_man_act_id');
            var new_man_test_id = tthis.data('new_man_test_id');
            var level_id = tthis.data('level_id');


            $('.js-clean-note-button').prop('disabled', true);


            $.ajax({
                    type: 'POST',
                    url: './index.php?action=clean_man_note',
                    data: {

                        new_man_id: new_man_id
                    },
                    dataType: "json",
                    complete: function () {
                        $('.js-clean-note-button').prop('disabled', false);
                    },
                    success: function (response) {
                        if (response.error == 'ok') {

//                            $('*[name="' + name + '[surname_rus]"]').val(result.surname_rus);
                            $('.man_' + new_man_id).val('');
                            $('.clean_' + new_man_id).hide();

                            var date_now = '$date_now';
                            //  alert (date_now);
                            $('*[name="user[' + new_man_id + '][testing_date]"]').val(date_now);
//                        $('*[name="user[' + new_man_id + '][testing_date]"]').closest('.datepicker').data('date', date_now);


                            $('.percent.man_' + new_man_id).html('0 %');
                            $('.certificate.man_' + new_man_id).addClass('note');
                            $('.certificate.man_' + new_man_id).removeClass('certificate');
                            $('.cert.man_' + new_man_id).html('Справка');
                            $('.man_' + new_man_id).prop("disabled", true);

                            $('.user_' + new_man_id + '_passport_file').html('');
                            $('a.user_' + new_man_id + '_passport_file').hide();

                            /*initEvents();
                             checkMarks(E);*/


                            $('.note_div_' + new_man_id).html('$table_echo_note_before'
                                +
                                '<input type="text" class="old_note_num bg-required man_' + new_man_id + ' filled" name="cert_id">'
                                +
                                '<button class="btn  js-check-note-button btn-warning" data-act_id="' + new_man_act_id + '" data-test_id="' + new_man_act_id + '" data-name="user[' + new_man_id + ']" data-new_man_id="' + new_man_id + '" data-level_id="' + level_id + '">'
                                +
                                'Проверить'
                                +
                                '</button>');

                            initEvents();
                            checkMarks(E);


//                        alert('ok');


                        }
                        else if (response.error == 'not_found') {

                            alert("Ошибка выбора тестируемого");

                        }

                        else {

                            alert('Ошибка выбора тестируемого!');
                        }
                    }
                }
            );

        });
EOFN;



$paste_data_and_modal_note =<<<EOTN
function paste_note_data(name, result, text, num, new_man_id) {


        var old_man_id = result['old_man_id'];
        var result1 = '';

        $.ajax({
                type: 'POST',
                url: './index.php?action=paste_old_note',
                data: {

                    old_man_id: old_man_id,
                    new_man_id: new_man_id,
                    num: num
                },
                dataType: "json",
                success: function (response) {
                    if (response.error == 'ok') {
                        var result = response["result"];


                        $('*[name="' + name + '[surname_rus]"]').val(result.surname_rus);
                        $('*[name="' + name + '[surname_lat]"]').val(result.surname_lat);
                        $('*[name="' + name + '[name_rus]"]').val(result.name_rus);
                        $('*[name="' + name + '[name_lat]"]').val(result.name_lat);

                        $('*[name="' + name + '[country_id]"]').val(result.country_id);


                        $('*[name="' + name + '[passport_name]"]').val(result.passport_name);
                        $('*[name="' + name + '[passport_series]"]').val(result.passport_series);
                        $('*[name="' + name + '[passport]"]').val(result.passport);
                        $('*[name="' + name + '[passport_date]"]').val(result.passport_date);
                        $('*[name="' + name + '[passport_date]"]').closest('.datepicker').data('date', result.passport_date);
                        $('*[name="' + name + '[passport_department]"]').val(result.passport_department);

                        $('*[name="' + name + '[birth_place]"]').val(result.birth_place);
                        $('*[name="' + name + '[birth_date]"]').val(result.birth_date);
                        $('*[name="' + name + '[birth_date]"]').closest('.datepicker').data('date', result.birth_date);

                        $('*[name="' + name + '[migration_card_number]"]').val(result.migration_card_number);
                        $('*[name="' + name + '[migration_card_series]"]').val(result.migration_card_series);
//die(result.passport_file);
                        if (result.passport_file)
                        {
                            $('#user_' + new_man_id + '_passport_file').html('<a href="' + result.passport_file + '" target="_blank" class="btn  btn-small btn-success"><span class="custom-icon-download"></span>Скачать</a>');
                            //$('#user_' + new_man_id + '_passport_file').html('asd');
                        }

                        result1 = result.test_results;
                        result1.forEach(function(value) {
                        $('*[name="' + name + '[' + value[0] + ']"]').val(value[1]);
                        });

                        initEvents();

                        paste_people_additional_exam_note(old_man_id, new_man_id, num, result.is_free, name);

                    }
                    else {
                        alert('Указанная справка НЕ НАЙДЕНА!');
                    }
                }
            }
        );




    }

    function paste_people_additional_exam_note(old_man_id, new_man_id, num, is_free, name) {


        $.ajax({
                type: 'POST',
                url: './index.php?action=paste_old_note_manually',
                data: {
                    num: num,
                    old_man_id: old_man_id,
                    new_man_id: new_man_id

                },
                dataType: "json"

            }
        );
        var fio_blocked = 0;
        if (old_man_id == 0) {
            $('.scan_upload_button').show();
        }
        else {

            fio_blocked = 1;

        }
        var free_text = '';
if (is_free) free_text = '$is_free_text';


        $('.note_div_' + new_man_id).html(free_text + '$table_echo_note_after:<br>' + num);
        $('.clean_' + new_man_id).show();


        //$('.scan_upload_button').show();
        $('.man_' + new_man_id).prop("disabled", false);
        if (fio_blocked == 1) {
            $('.js-surname-rus.man_' + new_man_id).prop("disabled", true);
            $('.js-name-rus.man_' + new_man_id).prop("disabled", true);





                        $('*[name="' + name + '[surname_rus]"]').prop("disabled", true);
                        $('*[name="' + name + '[surname_lat]"]').prop("disabled", true);
                        $('*[name="' + name + '[name_rus]"]').prop("disabled", true);
                        $('*[name="' + name + '[name_lat]"]').prop("disabled", true);

                        $('*[name="' + name + '[country_id]"]').prop("disabled", true);


                        $('*[name="' + name + '[passport_name]"]').prop("disabled", true);
                        $('*[name="' + name + '[passport_series]"]').prop("disabled", true);
                        $('*[name="' + name + '[passport]"]').prop("disabled", true);
                        $('*[name="' + name + '[passport_date]"]').prop("disabled", true);
                        //$('*[name="' + name + '[passport_date]"]').closest('.datepicker').data('date', result.passport_date);
                        $('*[name="' + name + '[passport_department]"]').prop("disabled", true);

                        $('*[name="' + name + '[birth_place]"]').prop("disabled", true);
                        $('*[name="' + name + '[birth_date]"]').prop("disabled", true);
                        //$('*[name="' + name + '[birth_date]"]').closest('.datepicker').data('date', result.birth_date);

                        $('*[name="' + name + '[migration_card_number]"]').prop("disabled", true);
                        $('*[name="' + name + '[migration_card_series]"]').prop("disabled", true);

        }

    }


    function modal_confirm_note(name, result, text, num, new_man_id) {


        $("#dialog-message_note").dialog("destroy");
        $('.modal_text_note').html(text);


        var buttons = {


            "Отмена": function () {
                $(this).dialog("close");
            }

        };


       /* if (result == 'not_found') {


            buttons["Заполнить вручную"] = function () {


                paste_people_additional_exam_note(0, new_man_id, num);

                $(this).dialog("close");

            };

        }

        else {*/
        if (result != 'not_found') {

            buttons["Заполнить автоматически"] = function () {


                paste_note_data(name, result, text, num, new_man_id);

                $(this).dialog("close");

            };

        }

        $("#dialog-message_note").dialog({
            modal: true,
            width: 800,
            autoOpen: true,
            closeOnEscape: false,
            buttons: buttons
        });
    }
EOTN;


$modal_div_note =<<<EEEN
<div id="dialog-message_note" title="Результаты поиска:" style="display:none">

    <p class="modal_text_note">

    </p>
</div>
EEEN;
