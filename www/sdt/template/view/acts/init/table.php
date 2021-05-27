<?php
/** @var Act $Act */
$i = 0;
$C = AbstractController::getInstance();

if ($Act->getMeta()->special_price_group == 2) {
    $table_echo_cert_after = $table_echo_cert_before = "Номер предъявленного документа (либо номер старого сертификата РКИ)";
} else {
    $table_echo_cert_before = "Введите номер старого сертификата, предъявленного соискателем"; //Текст над номером серта до ввода (и проверки) человека
    $table_echo_cert_after = "Номер предъявленного сертификата"; // Текст над номером серта после ввода человека (автоматом или вручную)
}


$table_echo_note_before = "Введите номер старой справки, предъявленной соискателем"; //Текст над номером серта до ввода (и проверки) человека
$table_echo_note_after = "Номер предъявленной справки"; // Текст над номером серта после ввода человека (автоматом или вручную)

$is_free_text = '<span style="color:red">Бесплатная пересдача</span><br>';

require_once('check_people/check_cert.php');
require_once('check_people/check_note.php');

?>
<script>

    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
//                var main_form = $('#main_form');
//                ajaxSubmit(main_form);
                return false;
            }
        });
    });

    var initEvents = function (skipDates) {
        if (!skipDates) {
            $('.datepicker').datepicker({
                format: 'dd.mm.yyyy',
                language: "ru",
                autoclose: true
//			startDate: '-3d'
            });
        }
        $(':text').not('.scores').keyup(function () {
            var $this = $(this);
            if ($this.val().length) {
                //   alert($this.val());

                $this.removeClass('filled');
            } else {

                $this.addClass('filled');
            }
        });

        $('.scores').keyup(function () {
            var $this = $(this);
            if (parseInt($this.val())) {
                //alert($this.val());

                $this.removeClass('filled');
            } else {

                $this.addClass('filled');
            }
        });

        $(':text').keyup();

        $('.passport_upload').on('click', function (e) {
            //
            //alert($(this).data('id'));

            //
            checkMarks(e);
            if (!e.isDefaultPrevented()) {
                e.preventDefault();
                $('#content .float-save').trigger('click');
                $('#passport_upload').find('.man_id').val($(this).data('id'));
                $('#passport_upload').modal();
            }
        });



        <?=$cert_script;?>
        <?=$note_script;?>

    };

    var floatSaveWorking = function () {
        floatSaveClicked = true;
        var find = $('#content').find('.float-save');
        find.addClass('disabled');
    };


    var floatSaveDoneWorking = function () {
        floatSaveClicked = false;
        var find = $('#content').find('.float-save');
        find.removeClass('disabled');
    };

    var floatSaveClicked = false;
    $(function () {
        initEvents(1);

        var $content = $('#content');

        $content.on('click', '.float-save', function (E) {
            //        E.preventDefault();
            if (floatSaveClicked) return;
            floatSaveWorking();
            var form = $(this).closest('form');
            checkMarks(E);
            if (!E.isDefaultPrevented()) {
                ajaxSubmit(form);
            } else {
                floatSaveDoneWorking();
            }

        });

        $content.on('click', '.js-surname-button', function (E) {
            E.preventDefault();
            var container = $(this).closest('.js-fio-container');

            container.find('.js-surname-en').val(translit(container.find('.js-surname-rus').val()));
        });

        $content.on('click', '.js-name-button', function (E) {
            E.preventDefault();
            var container = $(this).closest('.js-fio-container');
            container.find('.js-name-en').val(translit(container.find('.js-name-rus').val()));
        });

        $content.on('submit', '.marks', function (E) {
            checkMarks(E);
        });
    });

    var checkMarks = function (Event) {
        var marks = $('.mark');
        //var isError = false;
        marks.each(function () {
            var $this = $(this);
            var val = parseFloat($this.val());
            var max = $this.data('max');
            var offset = $this.offset();
            if (val > max) {
                Event.preventDefault();
                $this.addClass('error');
                $this.one('keypress', function () {
                    $this.removeClass('error');
                });
                $(document).scrollTop(offset.top - 150);
                alert('Вы ввели недопустимое значение балла: ' + val + '  (<= ' + max + ')');
                return false;
            }
        });
    };
    var ajaxSubmit = function (form) {
        form.ajaxSubmit({
            dataType: 'json',
            beforeSubmit: function (arr, $form, options) {
                var ajax = {};
                ajax.name = 'ajax';
                ajax.value = 1;
                arr.push(ajax);


            },
            success: function (Response) {
                if (Response.Ok) {
                    var html = $(Response.html).find('.table-content');
                    $('#content').find('.table-content').replaceWith(html);
                    initEvents();

                }
            },
            complete: function () {
                floatSaveDoneWorking();
            }
        });
    }
</script>


<script>
    $(function () {


    });

    <?=$paste_data_and_modal_cert;?>
    <?=$paste_data_and_modal_note;?>

</script>


<div class="alert alert-error">
    <strong style="font-size: 20px; color: red">Проверить правильность введенного количества тестируемых в данную
        сессию!!!</strong>
</div>
<h3>Сводная таблица. Дата тестирования: <?php echo $C->date($Act->testing_date); ?></h3>
<h4>Университет: <?php echo $Act->getUniversity(); ?></h4>
<h4>Договор: <?php echo $Act->getUniversityDogovor(); ?></h4>
<a href="/sdt/index.php?action=act_fs_view&id=<?php echo $Act->id ?>" class="btn btn-info">Вернуться в акт</a>
<? if (count($Act->getPeople())): ?>
    <a href="/sdt/index.php?action=act_passport&id=<?php echo $Act->id ?>" class="btn btn-info">Загрузить сканы
        документов</a>
    <? if ($Act->test_level_type_id == 2): ?>
        <a href="/sdt/index.php?action=act_man_files&id=<?php echo $Act->id ?>" class="btn btn-primary">Загрузить
            фотографии тестируемых</a>
    <? endif ?>
<? endif ?>


<a href="/sdt/index.php?action=act_old_cert_scan_upload&id=<?php echo $Act->id ?>"
   class="btn btn-info scan_upload_button" <? echo (!$Act->haveAdditionalExam()) ? 'style="display:none"' : '' ?>>Загрузить
    файлы сканов, предъявленных сертификатов</a>


<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" id="main_form"
      class="form-horizontal marks">
    <?php if (!empty($Legend)): ?>
        <legend>
            <?php echo $Legend; ?>
        </legend>
    <?php endif; ?>

    <div class="bg_highlight">
        Для сохранения введенных в ячейки таблицы данных нажмите клавишу "ENTER" или кнопку
        "Сохранить"!
    </div>
    <div class="table-content">
        <fieldset>
            <legend>Тесторы</legend>
            <div style="margin-bottom: 2px"><input class="bg-required" name="tester1"
                                                   value="<?php echo $Act->tester1 ?>"
                                                   style="width:400px"
                                                   placeholder="ФИО тестора"
                ></div>
            <div><input name="tester2" class="bg-required" value="<?php echo $Act->tester2 ?>" placeholder="ФИО тестора"
                        style="width:400px"></div>
        </fieldset>

        <?php foreach ($Act->getLevels() as $ActTest):
            $level = $ActTest->getLevel();
            $sub_tests = $level->getSubTests();;

            $sub_tests_count = count($sub_tests);
            ?>
            <table
                    class="table table-bordered  table-condensed  summary_table">
                <thead>

                <tr>
                    <td colspan="<?= $sub_tests_count + 7 ?>"><h4>Уровень тестирования
                            "<strong><?php echo $level->caption ?></strong>"</h4></td>
                </tr>
                <tr>
                    <td rowspan="2"><strong>№
                            <nobr>п/п</nobr>
                        </strong></td>

                    <?
                    $is_retry_session = $is_additional_exam = 0;
                    foreach ($ActTest->getPeople() as $Man) {
                        /** @var ActMan $Man */
                        if (!empty($Man->is_retry)) {
                            $is_retry_session = 1;
                        }
                        if (!empty($Man->getAdditionalExam())) {
                            $is_additional_exam = 1;
                        }

                    }
                    ?>

                    <td rowspan="2" colspan="<? echo(!empty($level->is_additional) ? 5 : 4) ?>"><strong>Сведения о
                            тестируемых</strong></td>


                    <td colspan="<?= $sub_tests_count + 1 ?>" class="center"><strong>Результаты</strong> (баллы /
                        %)
                    </td>
                    <td rowspan="2" class="center"><strong>Итог</strong>
                    </td>
                </tr>
                <tr>
                    <? foreach ($sub_tests as $subTest): ?>
                        <td class="center"><?= $subTest->short_caption ?>
                            <? if (
                                in_array($level->id, array(22, 23, 24))
                                && $subTest->max_ball == 125
                                && in_array($subTest->short_caption, array('Ист', 'Зак'))
                            ): ?>
                                <span class="max-ball-sum">&sum;&nbsp;баллов</span>
                            <? endif ?>
                            <span
                                    class="percent"><?= $subTest->max_ball ?></span></td>
                    <? endforeach ?>
                    <td class="center">Общ<span class="percent"><?php echo $level->total; ?></span></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($ActTest->getPeople() as $Man):
                    /** @var ActMan $Man */


                    if (!empty($level->is_additional) && !$Man->getAdditionalExam()) {
                        $disabled = 'disabled="disabled"';
                    } else {
                        $disabled = '';
                    }

                    if (!empty($Man->getAdditionalExam()->cert_exists)) {
                        $cert_exists = ' readonly="readonly" ';
                    } else {
                        $cert_exists = ' ';
                    }

                    $retry_disabled = '';
                    if ($Man->is_retry) {
                        $disabled = '';
                        $retry_disabled = 'disabled="disabled"';
                    }

                    ?>
                    <tr style="height: 80px"
                        class=" summary <?php if ($Man->is_retry): ?> man-retry-top <?php endif ?> <?php if ($Man->id % 2): ?> odd <?php endif ?>">
                        <td rowspan="2" class="npp"><?= ++$i ?></td>

                        <? if (!empty($level->is_additional) && !$Man->is_retry): ?>
                            <td rowspan="2">
                                <div class="cert_div_<?php echo $Man->id; ?>">
                                    <? if (!$Man->getAdditionalExam()) {
                                        $hidden = 'style="display:none"';
                                        ?>
                                        <form>

                                            <?= $table_echo_cert_before; ?>
                                            <input type="text" name="cert_id"
                                                   class="old_cert_num bg-required man_<?php echo $Man->id; ?>">


                                            <button data-new_man_id="<?= $Man->id ?>"
                                                    data-name="user[<?php echo $Man->id; ?>]"
                                                    data-test_id="<?= $Man->test_id; ?>"
                                                    data-act_id="<?= $_GET['id']; ?>"
                                                    class="btn  js-check-cert-button btn-warning"
                                            >
                                                Проверить
                                            </button>

                                            <!--                        <input type="button" value="Проверить">-->

                                        </form>


                                    <? } else {
                                        $hidden = '';
                                        echo $table_echo_cert_after . ':<br>';

                                        /* if (empty($Man->getAdditionalExam()->cert_exists)) {
                                             echo '<input type="text" name="user[' . $Man->id . '][old_blank]" value="' . $Man->getAdditionalExam(
                                                 )->old_blank_number . '">';
                                         } else {
                                             echo $Man->getAdditionalExam()->old_blank_number;
                                         }*/
                                        echo $Man->getAdditionalExam()->old_blank_number;


                                    } ?>
                                </div>


                                <div class="clean_<?= $Man->id ?>" <?= $hidden ?>>
                                    <button data-new_man_id="<?= $Man->id ?>"
                                            data-act_id="<?= $Man->act_id ?>"
                                            data-test_id="<?= $Man->test_id ?>"
                                            class="btn js-clean-cert-button btn-danger"
                                    >
                                        Очистить
                                    </button>
                                </div>


                            </td>


                        <? endif; ?>




















                        <? if (!empty($Man->is_retry)): ?>
                            <td rowspan="2">
                                <? if ($Man->is_retry != $Man::RETRY_ALL): ?>
                                    <b>Субтестов пересдает: <?= $Man->is_retry; ?></b>
                                <? endif ?>
                                <? if ($Man->is_retry == $Man::RETRY_ALL): ?>
                                    <b style="color:red">Бесплатная пересдача</b>
                                <? endif ?>
                                <div class="note_div_<?php echo $Man->id; ?>">
                                    <? if (!$Man->getReExam()) {
                                        $hidden = 'style="display:none"';
                                        ?>
                                        <form>

                                            <?= $table_echo_note_before; ?>
                                            <input type="text" name="cert_id"
                                                   class="old_note_num bg-required man_<?php echo $Man->id; ?>">


                                            <button data-new_man_id="<?= $Man->id ?>"
                                                    data-name="user[<?php echo $Man->id; ?>]"
                                                    data-test_id="<?= $Man->test_id; ?>"
                                                    data-act_id="<?= $_GET['id']; ?>"
                                                    data-level_id="<?= $level->id; ?>"
                                                    data-is_free="<?= $Man->is_retry == $Man::RETRY_ALL ?>"
                                                    class="btn  js-check-note-button btn-warning"
                                            >
                                                Проверить
                                            </button>


                                        </form>


                                    <? } else {
                                        $hidden = '';
//                                        if ($Man->isFreeRetry()) echo $is_free_text;
                                        echo $table_echo_note_after . ':<br>';


                                        echo $Man->getReExam()->document_number;


                                    } ?>
                                </div>


                                <div class="clean_<?= $Man->id ?>" <?= $hidden ?>>
                                    <button data-new_man_id="<?= $Man->id ?>"
                                            data-name="user[<?php echo $Man->id; ?>]"
                                            data-test_id="<?= $Man->test_id; ?>"
                                            data-act_id="<?= $_GET['id']; ?>"
                                            data-level_id="<?= $level->id; ?>"
                                            class="btn js-clean-note-button btn-danger"
                                    >
                                        Очистить
                                    </button>
                                </div>


                            </td>


                        <? endif; ?>
























                        <?
                        $cols = 2;
                        if ($is_retry_session == 1) {
                            if ((!empty($Man->is_retry) || $is_additional_exam == 1) && !$level->is_additional) {
                                $cols = 1;
                            }
                        }
                        ?>
                        <td rowspan="2" colspan="<? echo $cols; ?>">
                            <table>
                                <tr>


                                    <td colspan="2">
                                        <fieldset class="js-fio-container">
                                            <legend>ФИО тестируемого</legend>

                                            <input type="text"
                                                   name="user[<?php echo $Man->id; ?>][surname_rus]"
                                                   class="bg-required input-large js-surname-rus only-rus man_<?php echo $Man->id; ?>"
                                                   placeholder="Фамилия по-русски"
                                                <?php echo $disabled;
                                                echo $cert_exists;
                                                echo $retry_disabled;
                                                ?>
                                                   value="<?php echo $Man->surname_rus; ?>"><br>

                                            <input type="text"
                                                   name="user[<?php echo $Man->id; ?>][name_rus]"
                                                   class="bg-required input-large  only-rus  js-name-rus man_<?php echo $Man->id; ?>"
                                                <?php echo $disabled;
                                                echo $cert_exists;
                                                echo $retry_disabled; ?>
                                                   placeholder="Имя по-русски" value="<?php echo $Man->name_rus; ?>">
                                            <br>

                                            <div class="input-append div-input-large">
                                                <input type="text" name="user[<?php echo $Man->id; ?>][surname_lat]"
                                                       class="bg-required input-large  only-lat  js-surname-en man_<?php echo $Man->id; ?>"
                                                       placeholder="Фамилия латиницей"
                                                    <?php echo $disabled; /*echo $cert_exists;*/
                                                    echo $retry_disabled; ?>
                                                       value="<?php echo $Man->surname_lat; ?>">
                                                <? if (!$Man->is_retry): ?>
                                                    <span class="add-on js-surname-button"><i
                                                                class="icon-refresh"></i></span>
                                                <? endif; ?>
                                            </div>


                                            <br>

                                            <div class="input-append div-input-large">
                                                <input type="text" name="user[<?php echo $Man->id; ?>][name_lat]"
                                                       class="bg-required input-large  only-lat  js-name-en man_<?php echo $Man->id; ?>"
                                                       placeholder="Имя латиницей"
                                                    <?php echo $disabled; /*echo $cert_exists;*/
                                                    echo $retry_disabled; ?>
                                                       value="<?php echo $Man->name_lat; ?>">
                                                <? if (!$Man->is_retry): ?>
                                                    <span class="add-on js-name-button"><i
                                                                class="icon-refresh"></i></span>
                                                <? endif; ?>
                                            </div>

                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <fieldset>
                                            <legend>Документ удостоверяющий личность</legend>

                                            <input placeholder="Наименование" type="text"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                   name="user[<?php echo $Man->id; ?>][passport_name]"
                                                   class="bg-required input-large man_<?php echo $Man->id; ?>"
                                                   value="<?php echo $Man->passport_name; ?>">

                                            <div>
                                                <input placeholder="Серия " type="text"
                                                    <? echo $disabled;
                                                    echo $retry_disabled; ?>
                                                       name="user[<?php echo $Man->id; ?>][passport_series]"
                                                       class="input-mini man_<?php echo $Man->id; ?>"
                                                       value="<?php echo $Man->passport_series; ?>">

                                                <input type="text" name="user[<?php echo $Man->id; ?>][passport]"
                                                    <? echo $disabled;
                                                    echo $retry_disabled; ?>
                                                       class="bg-required input-medium man_<?php echo $Man->id; ?>"
                                                       value="<?php echo $Man->passport; ?>"
                                                       placeholder="Номер" style="width:132px"><span class="add-on">

                                            </div>
                                            <div class="input-prepend date <? if (!$Man->is_retry): ?>datepicker<? endif; ?> man_<?php echo $Man->id; ?>"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                 data-date="<?php if ($Man->passport_date != '0000-00-00' && $Man->passport_date) {
                                                     echo $C->date($Man->passport_date);

                                                 } else {
//                                                     echo date('d.m.Y');
                                                 } ?>"
                                            >
                                                <span class="add-on man_<?php echo $Man->id; ?>"><i class="icon-th"></i> </span>
                                                <input
                                                        placeholder="Дата выдачи"
                                                    <? echo $disabled;
                                                    echo $retry_disabled; ?>
                                                        class="input-small man_<?php echo $Man->id; ?>"
                                                        name="user[<?php echo $Man->id; ?>][passport_date]"
                                                        id="passport_date" readonly="readonly" size="16" type="text"
                                                        value="<?php if ($Man->passport_date != '0000-00-00' && $Man->passport_date) {
                                                            echo $C->date($Man->passport_date);
                                                        } else {
//                                                        echo date('d.m.Y');
                                                        } ?>">

                                            </div>
                                            <input placeholder="Орган выдавший " type="text"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                   name="user[<?php echo $Man->id; ?>][passport_department]"
                                                   class="input-large man_<?php echo $Man->id; ?>"
                                                   value="<?php echo $Man->passport_department; ?>">
                                            <?php if ($Man->getFilePassport()): ?>
                                                <a href="<?php echo $Man->getFilePassport()->getDownloadUrl() ?>"
                                                   target="_blank"
                                                   class="btn  btn-small btn-success man_<?php echo $Man->id; ?> user_<?php echo $Man->id; ?>_passport_file"><span
                                                            class="custom-icon-download man_<?php echo $Man->id; ?>"></span>Скачать</a>
                                            <?php endif; ?>
                                            <?php if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($Man->id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)): ?>
                                                <a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                                                   target="_blank"
                                                   class="btn  btn-small btn-primary"> Фотография</a>
                                            <?php endif; ?>

                                            <div id="user_<?php echo $Man->id; ?>_passport_file"
                                                 class="user_<?php echo $Man->id; ?>_passport_file"></div>
                                        </fieldset>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td rowspan="2" colspan="2">
                            <table border="0" style="border:0">
                                <tr>
                                    <td style="border:0">
                                        <fieldset>
                                            <legend>Страна (гражданство)</legend>
                                            <select name="user[<?php echo $Man->id; ?>][country_id]"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                    class="input-large man_<?php echo $Man->id; ?>" style="width: 100%">
                                                <? if (!$Man->country_id): ?>
                                                    <option disabled="disabled" selected="selected">Выберите страну
                                                    </option><? endif ?>
                                                <?php foreach ($Countries as $country): ?>
                                                    <option value="<?php echo $country->id; ?>"
                                                        <?php if ($country->id == $Man->country_id) {
                                                            echo 'selected=selected';
                                                        } ?>>
                                                        <?php echo $country->name; ?>
                                                    </option>

                                                <?php endforeach; ?>
                                            </select>
                                        </fieldset>
                                    </td>

                                </tr>

                                <tr>
                                    <td style="border:0">
                                        <fieldset>
                                            <legend>Дата теста</legend>
                                            <div class="input-prepend date datepicker man_<?php echo $Man->id; ?>"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                 data-date="<?php if ($Man->testing_date != '0000-00-00') {
                                                     echo $C->date($Man->testing_date);
                                                 } else {
                                                     echo $C->date($Act->testing_date);
                                                 } ?>"
                                            >
                                                <span class="add-on man_<?php echo $Man->id; ?>"><i class="icon-th"></i> </span>
                                                <input
                                                        class="input-small man_<?php echo $Man->id; ?>"
                                                    <? echo $disabled;
                                                    echo $retry_disabled; ?>
                                                        name="user[<?php echo $Man->id; ?>][testing_date]"
                                                        id="testing_date" readonly="readonly" size="16" type="text"
                                                        value="<?php if ($Man->testing_date != '0000-00-00') {
                                                            echo $C->date($Man->testing_date);
                                                        } else {
                                                            echo $C->date($Act->testing_date);
                                                        } ?>">
                                            </div>

                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0">
                                        <fieldset>
                                            <legend>Дата рождения</legend>

                                            <div class="input-prepend date <? if (!$Man->is_retry): ?>datepicker<? endif; ?> man_<?php echo $Man->id; ?>"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                 data-date="<?php if ($Man->birth_date != '0000-00-00') {
                                                     echo $C->date($Man->birth_date);
                                                 } ?>"
                                            >
                                                <span class="add-on man_<?php echo $Man->id; ?>"><i class="icon-th"></i> </span>
                                                <input
                                                        class="input-small bg-required man_<?php echo $Man->id; ?>"
                                                    <? echo $disabled;
                                                    echo $retry_disabled; ?>
                                                        name="user[<?php echo $Man->id; ?>][birth_date]"
                                                        placeholder="Дата"
                                                        id="birth_date" readonly="readonly" size="16" type="text"
                                                        value="<?php if ($Man->birth_date != '0000-00-00') {
                                                            echo $C->date($Man->birth_date);
                                                        } ?>">
                                            </div>
                                            <input placeholder="Место рождения" type="text"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                   name="user[<?php echo $Man->id; ?>][birth_place]"
                                                   class="input-large man_<?php echo $Man->id; ?>"
                                                   value="<?php echo $Man->birth_place; ?>">

                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0">
                                        <fieldset>
                                            <legend>Миграционная карта</legend>
                                            <input placeholder="Серия" type="text"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                   name="user[<?php echo $Man->id; ?>][migration_card_series]"
                                                   class="input-mini man_<?php echo $Man->id; ?>"
                                                   value="<?php echo $Man->migration_card_series; ?>">
                                            <input placeholder="Номер" type="text"
                                                <? echo $disabled;
                                                echo $retry_disabled; ?>
                                                   name="user[<?php echo $Man->id; ?>][migration_card_number]"
                                                   class="input-small man_<?php echo $Man->id; ?>"
                                                   value="<?php echo $Man->migration_card_number; ?>">
                                        </fieldset>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <? $ManResults = $Man->getResults();
                        foreach ($ManResults as $result): ?>
                            <td>
                                <input type="text" maxlength="5"
                                    <?= $disabled; ?>
                                       placeholder="<?= $sub_tests->getByOrder($result->order)->short_caption ?>"
                                       data-max="<?= $sub_tests->getByOrder($result->order)->max_ball ?>"
                                       name="user[<?php echo $Man->id; ?>][test_<?= $result->order ?>_ball]"
                                       class="bg-required scores mark man_<?php echo $Man->id; ?>"
                                       value="<?php echo $result->balls ? $result->balls : ''; ?>">
                                <span class="percent man_<?php echo $Man->id; ?>"><?php echo $result->percent; ?>
                                    %</span>
                            </td>

                        <? endforeach; ?>
                        <td><input type="text" maxlength="5"
                                   name="user[<?php echo $Man->id; ?>][total]"
                                   class="scores man_<?php echo $Man->id; ?>"
                                   value="<?php echo $Man->total; ?>" readonly="readonly">
                            <span class="percent man_<?php echo $Man->id; ?>"><?php echo $Man->total_percent; ?>%</span>
                        </td>
                        <td>  <span class="<?php echo $Man->document; ?> man_<?php echo $Man->id; ?>">
                        <?php echo $Man->document == "certificate" ? 'Сертификат' : 'Справка' ?>
                        </span>

                        </td>
                    </tr>
                    <tr class="<?php if ($Man->is_retry): ?> man-retry-bottom <?php endif ?>">
                        <td colspan="<?= count($ManResults) + 2 ?>">

                            <? /*if ($C->userHasRole(Roles::ROLE_CENTER_PFUR_API)):
                                $apiData = ApiUserData::getByManID($Man->id);
//var_dump($apiData);
                                if ($apiData):?>
                                    <div class="control-group <? if ($apiData->doc_type != $Man->document):?>error<? endif ?>">
                                        <label>Указанный тип документа<br>
                                            <input
                                                    type="text"
                                                    class="input-large  " disabled="disabled"
                                                    value="<?= $apiData->doc_type == 'certificate' ? 'Сертификат' : 'Справка' ?>"

                                            ></label>
                                    </div>
                                    <div class="control-group  ">
                                        <label>id - мигранта из внешней системы<br>
                                            <input
                                                    type="text"
                                                    class="input-large  " disabled="disabled"
                                                    value="<?= $apiData->ext_id ?>"

                                            ></label>
                                    </div>
                                <? endif;
                            endif */?>
                            <? if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)): ?>

                                <? if ($Man->isNote()): ?>
                                    <label>Номер справки:<br>
                                        <input
                                                type="text" name="user[<?php echo $Man->id; ?>][blank_number]"
                                                class="input-large  js-blank"
                                                value="<?= $Man->blank_number ?>"
                                                placeholder="Номер справки"
                                        ></label>

                                <? endif; ?>

                                <? if ($Man->isCertificate()): ?>
                                    <? if ($Act->test_level_type_id == 2): ?>
                                        <label>Регистрационный номер:<br>
                                            <input type="text" name="user[<?php echo $Man->id; ?>][document_nomer]"
                                                   class="input-large"
                                                   value="<?= $Man->document_nomer ?>"
                                                   placeholder="Номер документа"></label>
                                    <? endif ?>
                                    <label>Номер бланка:<br>
                                        <input type="text" name="user[<?php echo $Man->id; ?>][blank_number]"
                                               class="input-large js-blank"
                                               value="<?= $Man->blank_number ?>" placeholder="Номер бланка">
                                    </label>

                                    <label>Дата печати:<br>
                                        <input placeholder="Дата печати" type="text"
                                               name="user[<?php echo $Man->id; ?>][blank_date]"
                                               class="input-large datepicker"
                                               value="<?php
                                               if (!is_null($Man->blank_date) && $Man->blank_date != '0000-00-00') {
                                                   echo date('d.m.Y', strtotime($Man->blank_date));
                                               }
                                               ?>"></label>
                                    <label>Срок действия:<br>
                                        <input type="text"
                                               disabled="disabled" class="input-large datepicker"
                                               value="<?php
                                               if (!is_null($Man->valid_till) && $Man->valid_till != '0000-00-00') {
                                                   echo date('d.m.Y', strtotime($Man->valid_till));
                                               }
                                               ?>">
                                    </label>
                                <? endif; ?>
                            <? endif; ?>


                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>

    </div>

    <div class="form-actions">

        <button class="btn btn-success standard-save" type="submit">Сохранить</button>
        <button class="btn btn-success float-save" type="button">Сохранить</button>
    </div>
</form>

<?
/*
?>
<div class="modal hide fade" id="passport_upload" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=man_passport_upload" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">Загрузить отсканированный паспорт и миграционную карту в одном файле</h3>
        </div>
        <div class="modal-body">


            <legend>Выберите файл</legend>

            <input type="hidden" value="" name="man_id" class="man_id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit">Загрузить</button>
        </div>
    </form>
</div>

*/

echo $modal_div_cert;
echo $modal_div_note;

?>


<style>
    .max-ball-sum {
        color: red;
        display: block;
    }
</style>
