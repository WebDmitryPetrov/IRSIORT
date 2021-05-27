<style>
    .counter
    {
        color:red;
    }
    .list
    {
        display: none;
        border-top: 1px dashed grey;
        border-bottom: 1px dashed grey;
        margin-bottom: 10px;
        padding: 10px 0;
    }
    h4 a
    {
        cursor: pointer;
    }
</style>
<form class="form-search" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="input-append">
        <select class="input-xxlarge search-query" name="type" style="padding-left: 9px;">
            <option value="2">Интеграционный экзамен</option>
            <option value="1" <?php if ($type==1) echo 'selected="selected"'; ?> >Лингводидактическое тестирование</option>
        </select>
        <button type="submit" class="btn">Найти</button>
    </div>
    <div><input name="certificate" type="text" class="input-xxlarge search-query"
                placeholder="Номер бланка сертификата \ номер справки"
                value="<?php echo $certificate; ?>">
        <p>Необходимо указать номера блакнов через пробел</p>
    </div>

</form>
<?php
if ($Result):?>
    <?

    $people_need_archive = $people_is_ready =
    $people_need_archive_blanks =
    $people_is_ready_id =
   /* $people_annuled_blanks =*/
    $people_annuled = array();
    foreach ($Result as $Man){

        if ($Man->getAct()->state != 'archive') $people_need_archive[] = $Man;
        else if ($Man->isCertificate() && !$Man->needBlank()) $people_is_ready[] = $Man;
        else if ($Man->isCertificate() && $Man->needBlank() && AnnulCert::getByManID($Man->id)) $people_annuled[] = AnnulCert::getByManID($Man->id)->blank_number;
}


    foreach ($people_need_archive as $p_val)
{
$people_need_archive_blanks[] = $p_val->blank_number;
}
    foreach ($people_is_ready as $r_val)
{
    $people_is_ready_id[] = $r_val->id;
}
/*    foreach ($people_annuled as $a_val)
{
    var_dump($a_val);
    $people_annuled_blanks[] = AnnulCert::getByManID($a_val->id)->blank_number;
}*/


//    die($people_is_ready_id);
?>
    <h4>Найдено сертификатов: <span class="counter"><?=count($Result).'/'.$certs_count;?></span></h4>

    <h4>Из них:</h4>
    <ul>
    <li><h4>уже аннулированы: <span class="counter"><?=count($people_annuled);?></span> <? if($people_annuled):?><a onclick="$('#annuled').toggle();">Список</a><? endif ?></h4>

    <div id="annuled" class="list">
        <?=implode($people_annuled,"<br>");?>
    </div>
    </li>
        <li>


   <h4>не переведены в архив: <span class="counter"><?=count($people_need_archive);?></span> <? if($people_need_archive):?><a onclick="$('#not_archived').toggle();">Список</a><? endif ?></h4>

    <div id="not_archived" class="list">
        <?=implode($people_need_archive_blanks,"<br>");?>
    </div>
</li>
    </ul>
<?
    if (!empty($people_is_ready_id)):
    $people_is_ready_id = implode($people_is_ready_id,",");
?>

    <button
       <?/*

        <? /*data-people_need_archive_count = "<?= count($people_need_archive)?>"
        data-people_need_archive_blanks = "<?= implode($people_need_archive_blanks,',')?>"*/?>
        data-id="<?= $people_is_ready_id ?>"
        class="btn btn-danger   js-people-invalid-button">Аннулировать
        сертификаты
    </button>

    <table
            class="table table-bordered  table-striped table-condensed">
        <thead>
        <tr>
            <td rowspan="2"><strong>Фамилия</strong><br/> русскими <br/>
                латинскими
            </td>
            <td rowspan="2"><strong>Имя</strong><br/> русскими<br/> латинскими</td>
            <td rowspan="2"><strong>Страна</strong></td>

            <td rowspan="2"><strong>Дата тестирования</strong></td>
            <td rowspan="2"><strong>Уровень тестирования</strong></td>
            <!--			<td colspan="6" class="center"><strong>Результаты</strong> (баллы /-->
            <!--				%)</td>-->
            <td rowspan="2" class="center"><strong>Итог</strong>
            </td>
            <td rowspan="2" class="center"><strong>Акт</strong>
            </td>
        </tr>


        </thead>
        <tbody>
<?php //foreach ($Result as $Man):
        foreach ($people_is_ready as $Man):
            /** @var ActMan $Man */
            ?>
            <tr
                    class="">

                <td><span><?php echo $Man->getSurname_rus(); ?> </span> <br> <span><?php echo $Man->getSurname_lat(); ?>
			</span>
                </td>
                <td><span><?php echo $Man->getName_rus(); ?> </span> <br> <span><?php echo $Man->getName_lat(); ?>
			</span>
                </td>
                <td><?php echo $Man->getCountry()->name; ?></td>
                <td><?php echo $C->date($Man->testing_date) ?>
                </td>
                <td><?php echo $Man->getTest()->getLevel()->caption ?>
                </td>

                <td>
                <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->isCertificate() ? 'Сертификат' : 'Справка' ?>


                        </span>
                    <? if ($Man->duplicate): ?>
                        - <strong>дубликат</strong>
                    <? endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($Man->document_nomer) {
                            $res[] = $Man->document_nomer;
                        }
                        if ($Man->blank_number) {
                            $res[] = $Man->getBlank_number();
                        } elseif ($Man->is_anull() && $Man->getAnull()->blank_number) {
                            $res[] = $Man->getAnull()->blank_number;
                        }
                        if (!$Man->duplicate) {
                            $res[] = HeadCenter::getNameByActID($Man->act_id);
                        } else {
                            $res[] = HeadCenter::getNameByCertificateID($Man->duplicate->certificate_id);
                        }
                        echo implode(' / ', $res); ?></strong>
                    <? if ($Man->blank_date != '0000-00-00' && !is_null($Man->blank_date)): ?>
                        <br>
                        <?= $C->date($Man->blank_date); ?>
                    <? endif ?>
                    <? if ($Man->is_anull()): ?>
                        <p class="text-error"><strong>Аннулирован</strong><br><?= $C->date($Man->getAnull()->date_annul)?><br><strong>Причина:</strong><br><?= $Man->getAnull()->reason ?></p>
                    <? endif ?>
                </td>
                <td>
                    <? if (!$Man->is_anull()): ?>
                    <a class="btn btn-info  btn-block  btn-small"
                       href="index.php?action=act_received_view&id=<?php echo $Man->getAct()->id; ?>">Карточка
                        акта</a>

                    <? if ($Man->isCertificate() && $Man->duplicate && $Man->getAct()->state == Act::STATE_ARCHIVE
                    ): ?>
                        <a class="btn btn-warning btn-block  btn-small" target="_blank"
                           href="index.php?action=man_duplicate&id=<?php echo $Man->id; ?>&h_id=<?php echo HeadCenter::getByActID($Man->act_id)->id; ?>">Дубликат</a>
                        <? endif; ?>

                    <? endif ?>

                    <div></div>
                    <?php if (/*$C->userHasRole(Roles::ROLE_ACT_INVALID) &&*/
                        $Man->isCertificate() && !$Man->needBlank()
                    ): ?>
                        <? /*href="./?action=man_issue_duplicate&id=<?= $Man->id ?>"*/ ?>
                        <button
                                data-id="<?= $Man->id ?>"
                                data-surname_rus="<?= $Man->getSurname_rus() ?>"
                                data-name_rus="<?= $Man->getName_rus() ?>"
                                data-surname_lat="<?= $Man->getSurname_lat() ?>"
                                data-name_lat="<?= $Man->getName_lat() ?>"
                                data-blank_number="<?= $Man->getBlank_number() ?>"
                                data-reg_number="<?= $Man->document_nomer ?>"
                                data-state="<?= $Man->getAct()->state ?>"
                                data-univer="<?= htmlspecialchars($Man->getAct()->getUniversity()->name) ?>"
                                data-hc="<?= htmlspecialchars($Man->getAct()->getUniversity()->getHeadCenter()->name) ?>"
                                data-act_id="<?= ($Man->getAct()->id) ?>"
                                data-num_id="<?= htmlspecialchars($Man->getAct()->number) ?>"
                                class="btn btn-danger  btn-block  btn-small js-man-invalid-button">Аннулировать
                            сертификат
                        </button>
                    <? endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
endif;
?>

<script>
    $(function () {
        $('.js-man-invalid-button').on('click', function (E) {
            E.preventDefault();


            var $this = $(this);

            if ($this.data('state') !== 'archive') {
                var modalArchive = $('.js-man-invalid-need-archive-modal');
                modalArchive.find('.js-univer').text($this.data('univer'));
                modalArchive.find('.js-hc').text($this.data('hc'));
                modalArchive.find('.js-act-id').text($this.data('act_id'));
                modalArchive.find('.js-act-num').text($this.data('num_id'));
                modalArchive.modal();
                return;
            }

            var manId = $this.data('id');
            var row = $this.closest('.js-man-row');
            var blank = row.find('.js-blank').val();
            var name = row.find('.js-surname-rus').text() + ' ' + row.find('.js-name-rus').text();

            $('#surname_rus').val($this.data('surname_rus'));
            $('#name_rus').val($this.data('name_rus'));
            $('#surname_lat').val($this.data('surname_lat'));
            $('#name_lat').val($this.data('name_lat'));
            $('#blank_number').val($this.data('blank_number'));
            $('#reg_number').val($this.data('reg_number'));

            var modalMan = $('.js-man-invalid-modal');
            modalMan.find('.js-blank').text(blank);
            modalMan.find('.js-name').text(name);
            modalMan.find('.js-reason').val('');
            modalMan.find('.js-accept').off('click');
            modalMan.find('.js-accept').on('click', function (E) {
                var reason = $('.js-reason').val();

                var annul_date = $('#annul_date').val();

                if (reason.length < 10) {
                    alert('Причина должна быть длинее 10 символов');
                    return;
                }
                else {
                    if (!confirm('Операция необратима.\n Вы уверены?')) return;
                }

                $.ajax({
                    type: 'POST',
                    url: './index.php?action=annul_cert',
                    data: {
                        id: manId,
                        reason: reason,
                        annul_date: annul_date
                    }
                }).always(function () {
                        location.reload();
                    }
                );

//                $(this).off(E);
            });

            modalMan.modal();


        })
        ;

        $('.js-people-invalid-button').on('click', function (E) {
            E.preventDefault();


            var $this = $(this);

       /* пока не надо     if ($this.data('people_need_archive_count') !== 0) {

                var modalArchive = $('.js-people-invalid-need-archive-modal');
                modalArchive.find('.need_archive_blanks').text($this.data('people_need_archive_blanks'));
                modalArchive.modal();
                return;
            }
            */

            var manId = $this.data('id');

//alert (manId);
            var modalMan = $('.js-people-invalid-modal');
            /*modalMan.find('.js-blank').text(blank);
            modalMan.find('.js-name').text(name);*/
            modalMan.find('.js-reason-range').val('');
            modalMan.find('.js-accept-range').off('click');
            modalMan.find('.js-accept-range').on('click', function (E) {
                var reason = $('.js-reason-range').val();

                var annul_date = $('#annul_date_range').val();

                if (reason.length < 10) {
                    alert('Причина должна быть длинее 10 символов');
                    return;
                }
                else {
                    if (!confirm('Операция необратима.\n Вы уверены?')) return;
                }

                $.ajax({
                    type: 'POST',
                    url: './index.php?action=annul_cert_by_range',
                    data: {
                        id: manId,
                        reason: reason,
                        annul_date: annul_date
                    }
                }).always(function () {
                        location.reload();
                    }
                );

//                $(this).off(E);
            });

            modalMan.modal();


        })
        ;
    })
    ;
</script>

<style>
    .modal {
        left: 42%;
        /* top:42%;*/
        width: 750px;
    }

    .modal.fade.in {
        top: 40%;
    }

    .modal-body {
        max-height: inherit;
    }
</style>

<div class="js-man-invalid-need-archive-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Аннулировать сертификат</h3>
    </div>
    <div class="modal-body">
        <p>Тестовую сессию необходимо отправить в архив<br>
            <strong>Головной центр:</strong> <span class="js-hc"></span><br>
            <strong>Локальный центр:</strong> <span class="js-univer"></span><br>
            <strong>Номер тестовой сессии:</strong> <span class="js-act-num"></span><br>
            <strong>ID тестовой сессии:</strong> <span class="js-act-id"></span><br>

        </p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Закрыть</a>

    </div>
</div>



<? /*пока не надо
<div class="js-people-invalid-need-archive-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Аннулировать сертификаты</h3>
    </div>
    <div class="modal-body">
        <p>Тестовые сессии следующих бланков необходимо отправить в архив<br>
            <strong>Номера бланков:</strong> <span class="need_archive_blanks"></span><br>

        </p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Закрыть</a>

    </div>
</div>
*/?>




<div class="js-man-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Аннулировать сертификат</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <p>Вы пытаетесь аннулировать сертификат <span class="js-blank"></span>
                у <span class="js-name"></span>
            </p>
            <div class="control-group"><label class="control-label">Фамилия rus</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="surname_rus"></div>
            </div>
            <div class="control-group"><label class="control-label">Имя rus</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="name_rus"></div>
            </div>
            <div class="control-group"><label class="control-label">Фамилия lat</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="surname_lat"></div>
            </div>
            <div class="control-group"><label class="control-label">Имя lat</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="name_lat"></div>
            </div>
            <div class="control-group"><label class="control-label">Номер бланка</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="blank_number"></div>
            </div>
            <div class="control-group"><label class="control-label">Регистрационный номер</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="reg_number"></div>
            </div>
            <div class="control-group"><label class="control-label">Дата аннуляции</label>
                <div class="controls">


                    <input type="text" name="annul_date" id="annul_date" class="input-large datepicker"
                           value="<?php
                           echo date('d.m.Y');
                           ?>">


                    <!--                    <input type="text" value="" readonly="readonly" id="annul_date">-->
                </div>
            </div>
            <div class="control-group"><label class="control-label">Укажите причину: </label>
                <div class="controls">
                <textarea style="width: 450px" class="js-reason" rows="3"
                          placeholder="Например: испорчен бланк"></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Отменить</a>
        <a class="btn btn-primary js-accept">Подтвердить</a>
    </div>
</div>






<div class="js-people-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Аннулировать сертификат</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <p>Вы пытаетесь аннулировать сертификаты:
                <? foreach ($people_is_ready as $r_val)
                {
                    echo "<br>".$r_val->blank_number;
                }

                ?>
            </p>

            <div class="control-group"><label class="control-label">Дата аннуляции</label>
                <div class="controls">


                    <input type="text" name="annul_date" id="annul_date_range" class="input-large datepicker"
                           value="<?php
                           echo date('d.m.Y');
                           ?>">


                    <!--                    <input type="text" value="" readonly="readonly" id="annul_date">-->
                </div>
            </div>
            <div class="control-group"><label class="control-label">Укажите причину: </label>
                <div class="controls">
                <textarea style="width: 450px" class="js-reason-range" rows="3"
                          placeholder="Например: испорчен бланк"></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Отменить</a>
        <a class="btn btn-primary js-accept-range">Подтвердить</a>
    </div>
</div>
<? endif; ?>
<? //var_dump($_SESSION);?>