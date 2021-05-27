<?php
/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 18.09.2017
 * Time: 17:41
 */
$date_now=date('d.m.Y');

$cert_script =<<<EOFC
$('.js-check-cert-button').off('click');
        $('.js-check-cert-button').on('click', function (E) {
            E.preventDefault();

            if ($('.js-check-cert-button').prop('disabled')) {
                alert('Поиск...');
                return;
            }

            var tthis = $(this);
            var name = tthis.data('name');
            var new_man_id = tthis.data('new_man_id');

            var num = $('.old_cert_num.man_' + new_man_id).val();


            if (!num.trim().length) {
//                $('.js-check-cert-button').removeClass('disabled');
                alert('Введите номер сертификата!');
                return;
            }
//            $('.js-check-cert-button').addClass('disabled');
            $('.js-check-cert-button').prop('disabled', true);


//


            $.ajax({
                    type: 'POST',
                    url: './index.php?action=check_old_cert',
                    data: {

                        num: num,
                        new_man_id: new_man_id
                    },
                    dataType: "json",
                    complete: function () {
                        $('.js-check-cert-button').prop('disabled', false);
                    },
                    success: function (response) {
                        if (response.error == 'ok') {
                            var result = response["result"];


                            var text = "Найден пользователь:<br><strong>"
                                + result.name_rus + " " + result.surname_rus
                                + "</strong>";
                            $('.js-check-cert-button').removeClass('disabled');
                            modal_confirm(name, result, text, num, new_man_id);


                        }
                        else if (response.error == 'denied_level') {
                           $('.js-check-cert-button').removeClass('disabled');
                            alert("Уровень тестирования старого сертификата не позволяет досдать на новый уровень!");
                            //modal_confirm(name,'not_found',text,num,new_man_id);
                        }

                        else {
                            var text = "Сертификат лингводидактического тестирования по русскому языку НЕ НАЙДЕН!";


                            modal_confirm(name, 'not_found', text, num, new_man_id);
                        }
                    }
                }
            );

        });


        $('.js-clean-cert-button').off('click');
        $('.js-clean-cert-button').on('click', function (E) {
            E.preventDefault();
            if (!confirm('Вы действительно хотите удалить всю информацию данного тестируемого?')) return false;
            if ($('.js-clean-cert-button').prop('disabled')) {
                alert('Очистка');
                return;
            }

            var tthis = $(this);

            var new_man_id = tthis.data('new_man_id');
            var new_man_act_id = tthis.data('new_man_act_id');
            var new_man_test_id = tthis.data('new_man_test_id');


            $('.js-clean-cert-button').prop('disabled', true);


            $.ajax({
                    type: 'POST',
                    url: './index.php?action=clean_man',
                    data: {

                        new_man_id: new_man_id
                    },
                    dataType: "json",
                    complete: function () {
                        $('.js-clean-cert-button').prop('disabled', false);
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
                            $('.note.man_' + new_man_id).html('Справка');
                            $('.man_' + new_man_id).prop("disabled", true);

                            /*initEvents();
                             checkMarks(E);*/


                            $('.cert_div_' + new_man_id).html('$table_echo_cert_before'
                                +
                                '<input type="text" class="old_cert_num bg-required man_' + new_man_id + ' filled" name="cert_id">'
                                +
                                '<button class="btn  js-check-cert-button btn-warning" data-act_id="' + new_man_act_id + '" data-test_id="' + new_man_act_id + '" data-name="user[' + new_man_id + ']" data-new_man_id="' + new_man_id + '">'
                                +
                                'Проверить'
                                +
                                '</button>');

                            initEvents();
                            checkMarks(E);




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

EOFC;


$paste_data_and_modal_cert =<<<EOTC
function paste_cert_data(name, result, text, num, new_man_id) {


        var old_man_id = result['old_man_id'];

        $.ajax({
                type: 'POST',
                url: './index.php?action=paste_old_cert',
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


                        initEvents();

                        paste_people_additional_exam(old_man_id, new_man_id, num);

                    }
                    else {
                        alert('Сертификат лингводидактического тестирования по русскому языку НЕ НАЙДЕН!');
                    }
                }
            }
        );




    }

    function paste_people_additional_exam(old_man_id, new_man_id, num) {


        $.ajax({
                type: 'POST',
                url: './index.php?action=paste_old_cert_manually',
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


        $('.cert_div_' + new_man_id).html('$table_echo_cert_after:<br>' + num);
        $('.clean_' + new_man_id).show();


        //$('.scan_upload_button').show();
        $('.man_' + new_man_id).prop("disabled", false);
        if (fio_blocked == 1) {
            $('.js-surname-rus.man_' + new_man_id).prop("disabled", true);
            $('.js-name-rus.man_' + new_man_id).prop("disabled", true);

        }

    }


    function modal_confirm(name, result, text, num, new_man_id) {


        $("#dialog-message").dialog("destroy");
        $('.modal_text').html(text);


        var buttons = {


            "Отмена": function () {
                $(this).dialog("close");
            }

        };


        if (result == 'not_found') {


            buttons["Заполнить вручную"] = function () {


                paste_people_additional_exam(0, new_man_id, num);

                $(this).dialog("close");

            };

        }

        else {

            buttons["Заполнить автоматически"] = function () {


                paste_cert_data(name, result, text, num, new_man_id);

                $(this).dialog("close");

            };

        }

        $("#dialog-message").dialog({
            modal: true,
            width: 800,
            autoOpen: true,
            closeOnEscape: false,
            buttons: buttons
        });
    }

EOTC;

$modal_div_cert =<<<EEEC
<div id="dialog-message" title="Результаты поиска:" style="display:none">

    <p class="modal_text">

    </p>
</div>
EEEC;
