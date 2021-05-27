<?php
/** @var Act $Act */
$i = 0;
?>

<h3>Проверка и <span style="color:red">подтверждение подлинности сертификата</span>, представленного соискателем при тестировании по упрощенному уровню.<br> Дата тестирования: <?php echo $C->date($Act->testing_date); ?></h3>
<h4>Локальный центр: <?php echo $Act->getUniversity(); ?></h4>
<h4>Договор: <?php echo $Act->getUniversityDogovor(); ?></h4>
<a href="/sdt/index.php?action=act_second_view&id=<?php echo $Act->id ?>" class="btn btn-info">Вернуться в акт</a>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
      class="form-horizontal marks">
    <?php if (!empty($Legend)): ?>
        <legend>
            <?php echo $Legend; ?>
        </legend>
    <?php endif; ?>


    <table class="table table-bordered  table-condensed">
        <thead>
        <tr>
            <th colspan="2" scope="col">Новые данные</th>
            <th colspan="2" scope="col">Старые данные</th>
            <th scope="col">Данные о сертификате</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($people as $Man):





            /** @var ActMan $Man */
            ?>
            <tr class=" summary
                <?php if ($Man->is_retry): ?> man-retry <?php endif ?>
                <?php if ($Man->id % 2): ?> info <?php endif ?>">
            <tr>
                <?php echo $this->import('acts/act_check/people_additional_exam_man', array('Man' => $Man)); ?>
                <? if ($old = $Man->getAdditionalExam()->getPrevious()): ?>
                    <?php echo $this->import('acts/act_check/people_additional_exam_man', array('Man' => $old)); ?>
                <? else: ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                <? endif?>
                <td>
                    <? $ex= $Man->getAdditionalExam(); ?>
                    <table class="table table-condensed">
                        <tr>
                            <th>Найден в ситеме</th>
                            <td><?=$ex->cert_exists?'Да':'Нет'?></td>
                        </tr>
                        <tr>
                            <th>Введёный номер</th>
                            <td><?=$ex->old_blank_number ?></td>
                        </tr>
                        <tr>
                            <th>Скан старого сертификата</th>
                            <td><a class="btn btn-mini btn-block btn-info" href="<?=$ex->getFileOldBlankScan()->getDownloadURL() ?>">Скачать</a></td>
                        </tr>

                        <tr>
                            <th>Подтверждение старого сертификата</th>
                            <td>
                                <? if (!$ex->approve):?>
                                <a class="btn btn-mini btn-block btn-danger"
                                   onclick="return confirm('Подтвердить сертификат?')" href="./?action=oncheck_approve_additional_exam&id=<?=$Man->id?>">Подтвердить</a>
                            <?else:?>
                                    <span class="text-success">Подтверждено</span>
                                <?endif?>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>


        <?php endforeach; ?>
        </tbody>
    </table>


</form>

