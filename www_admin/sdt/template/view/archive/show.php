<?php
/** @var \SDT\models\Archive\Man $man */
/** @var AbstractController $C */
$i=0;
?>
<style>
    .duplicate
    {
        color:red;
        font-weight:normal;
    }
    .duplicate_header
    {
        font-style:italic;

    }
</style>
 
<h3>Карточка тестируемого</h3>
<h4>Организация: <?php echo $man->head_org;?></h4>
<br>

<?php if (!empty($Legend)): ?>
    <legend>
        <?php echo $Legend; ?>
    </legend>
<?php endif;?>

 
 <?php

    $level = $man->getTestLevel();
    $sub_tests = $level->getSubTests();;

    $sub_tests_count = count($sub_tests);
    ?>
    <table
        class="table table-bordered  table-condensed summary_table">
        <thead>

        <tr>
            <td colspan="<?= $sub_tests_count + 7 ?>"><h4>Уровень тестирования "<strong><?php echo $level->caption ?></strong>"</h4></td>
        </tr>
        <tr>
            <td rowspan="2"><strong>№
                    <nobr>п/п</nobr>
                </strong></td>
            <td rowspan="2" colspan="4"><strong>Сведения о тестируемых</strong></td>


            <td colspan="<?= $sub_tests_count + 1 ?>" class="center"><strong>Результаты</strong> (баллы /
                %)
            </td>
            <td rowspan="2" class="center"><strong>Итог</strong>
            </td>
        </tr>
        <tr>
            <? foreach ($sub_tests as $subTest): ?>
                <td class="center"><?= $subTest->short_caption ?></td>
            <? endforeach ?>
            <td class="center">Общ</td>
        </tr>
        </thead>
        <tbody>
      
          
            <tr class=" summary <?php if ($man->is_retry): ?> man-retry <?php endif ?> <?php if ($man->id % 2): ?> info <?php endif ?>">
                <td class="npp"><?= ++$i ?></td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend>ФИО</legend>

                                    <span class="value"><?php echo $man->surname_rus; ?></span><br>

                                    <span class="value"><?php echo $man->name_rus; ?></span>
                                    <br>

                                    <span class="value"><?php echo $man->surname_lat; ?></span>

                                    <br>
                                    <span class="value"><?php echo $man->name_lat; ?></span>



                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend>Документ удостоверяющий личность</legend>

                                    <span class="value"><?php echo $man->passport_name; ?></span><br>


                                    <span class="value"><?php echo $man->passport_series; ?></span>

                                    <span class="value"><?php echo $man->passport; ?></span></br>
                                    <span class="value"><?php echo $C->date($man->passport_date); ?></span><br>
                                    <span class="value"><?php echo $man->passport_department; ?></span> <br>
                                    <?php if ($man->getFilePassport()): ?>
                                        <a href="<?php echo $man->getFilePassport()->getDownloadUrl() ?>"
                                           target="_blank"
                                           class="btn  btn-mini btn-block btn-success"><span
                                                class="custom-icon-download"></span>Скачать</a>
                                    <?php endif; ?>

                                    <?php if ($man->getDuplicateFilePassport()): ?>
                                        <a href="<?php echo $man->getDuplicateFilePassport()->getDownloadUrl() ?>"
                                           target="_blank"
                                           class="btn  btn-mini btn-block btn-success"><span
                                                class="custom-icon-download"></span>Скачать по новому ФИО</a>
                                    <?php endif; ?>

                                    <?php if ($man->getDuplicateFileRequest()): ?>
                                        <a href="<?php echo $man->getDuplicateFileRequest()->getDownloadUrl() ?>"
                                           target="_blank"
                                           class="btn  btn-mini btn-block btn-success"><span
                                                class="custom-icon-download"></span>Скачать заявление на дубликат</a>
                                    <?php endif; ?>



                                    <?php if ($uploadedPhoto = \SDT\models\Archive\PhotoFile::getByUserType($man->id, \SDT\models\Archive\PhotoFile::TYPE_PHOTO)): ?>
                                        <a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                                           target="_blank"
                                           class="btn  btn-mini btn-block btn-primary"> Фотография</a>
                                    <?php endif; ?>


                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="border:0">
                                <fieldset >
                                    <legend>Страна (гражданство)</legend>
                                    <span class="value"><?php echo $man->getCountry(); ?></span>
                                </fieldset>
                            </td>

                        </tr>
                        <?//?>

                        <tr>
                            <td style="border:0">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border:0">
                                <fieldset>
                                    <legend>Рождение</legend>
                                    <span class="value"><?php echo $C->date($man->birth_date); ?></span>
                                    <span class="value"><?php echo $man->birth_place; ?></span>

                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:0">
                                <fieldset>
                                    <legend>Миграционная карта</legend>
                                    <span class="value"><?php echo $man->migration_card_series; ?></span>
                                    <span class="value"><?php echo $man->migration_card_number; ?></span>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
                <? $manResults = $man->getResults();
                foreach ($manResults as $result):        ?>
                    <td><span class="value"><?php echo $result->balls; ?></span><br>
                        <span class="percent"><?php echo $result->percent; ?>%</span>
                    </td>


                <? endforeach; ?>


                <td><span class="value"><?php echo $man->total; ?></span><br>
                    <span class="percent"><?php echo $man->total_percent; ?>%</span>
                </td>
                <td>  <span class="<?php echo $man->document; ?> ">
                        <?php echo $man->isCertificate() ? 'Сертификат' : 'Справка' ?>


                        </span>
                    <? if ($man->original_blank_number): ?>
                        - <strong>дубликат</strong>
                    <? endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($man->document_nomer) {
                            $res[] = $man->document_nomer;
                        }
                        if ($man->blank_number) {
                            $res[] = $man->blank_number;
                        }
                        //                        elseif($man->is_anull() && $man->getAnull()->blank_number){
                        //                            $res[] = $man->getAnull()->blank_number;
                        //                        }

                        echo implode(' / ', $res); ?></strong>
                 


                    <?php
                    if ($man->blank_date != '0000-00-00' && !is_null($man->blank_date)): ?>
                        <br>
                     Дата выдачи:   <?= $C->date($man->blank_date); ?>
                    <? endif ?>
                    
                    </td>
            </tr>

        </tbody>
    </table>



