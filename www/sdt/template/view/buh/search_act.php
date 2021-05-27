<form class="form-inline" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <input type="hidden" name="action" value="search_act">
    <input type="hidden" name="do" value="search">
    <table class=" ">
        <tbody>
        <tr>
            <td colspan="4">
                <div align="center">Дате внесения</div>
            </td>
            <td style="width: 20px;">&nbsp;</td>
            <td colspan="4">
                <div align="center">Дата тестирования</div>
            </td>
        </tr>
        <tr>
            <td>
                <div align="right"><label for="minAddDate">От:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_add_from"
                         data-date="<?php echo $C->date($query['minAddDate']) ?>"
                    >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minAddDate" id="minAddDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['minAddDate']) ?>">
                    </div>

                    <!--<input type="text" name="add_from" id="add_from"/>-->
                </div>
            </td>
            <td>
                <div align="right"><label for="maxAddDate">&nbsp;До:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_add_to"
                         data-date="<?php echo $C->date($query['maxAddDate']) ?>"
                    >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxAddDate" id="maxAddDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['maxAddDate']) ?>">
                    </div>

                </div>
            </td>
            <td>&nbsp;</td>
            <td>
                <div align="right"><label for="minTestDate">От:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_test_from"
                         data-date="<?php echo $C->date($query['minTestDate']) ?>"
                    >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minTestDate" id="minTestDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['minTestDate']) ?>">
                    </div>

                </div>
            </td>
            <td>
                <div align="right"><label for="maxTestDate">&nbsp;До:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_test_to"
                         data-date="<?php echo $C->date($query['maxTestDate']) ?>"
                    >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxTestDate" id="maxTestDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['maxTestDate']) ?>">
                    </div>

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <div align="right"><label for="act_id">Id&nbsp;акта</label></div>
            </td>
            <td width="90px">
                <input
                    class="input-small" name="act_id" id="act_id"
                    size="16" type="text"
                    value="<?php echo $query['act_id'] ?>">
            </td>
            <td>
                <div align="right"><label for="organization">Организация</label></div>
            </td>
            <td colspan="6">
                <select name="organization" id="organization" class="input-medium" style="width: 100%">
                    <option>Не указана</option>
                    <?php foreach ($Universities as $univer): ?>
                        <option <?php if ($query['organization'] == $univer['id']): ?> selected="selected" <?php endif; ?>
                            value="<?= $univer['id'] ?>"><?= $univer['caption'] ?></option>
                    <?php endforeach; ?>
                </select></td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <div align="right"><label for="level">Уровень тестирования</label></div>
            </td>
            <td colspan="7"><select name="level" id="level" class="input-medium" style="width: 100%">
                    <option>Не указана</option>
                    <?php foreach ($Levels as $univer): ?>
                        <option <?php if ($query['level'] == $univer->id): ?>  selected="selected" <?php endif; ?>
                            value="<?= $univer->id ?>"><?= $univer ?></option>
                    <?php endforeach; ?>
                </select></td>
        </tr>

        </tbody>
    </table>
    <br>
    <input type="submit" value="Искать" class="btn btn-primary btn-large pull-right">
</form>
<? if (is_null($Result)): ?>
    <h3>Ничего не найдено</h3>
<? endif ?>
<?php
if (is_array($Result) && count($Result)) : ?>

    <?
    // require_once('paginator_tab.php');


//echo $paginator;
    ?>


    <table class="table table-bordered  table-striped">
        <thead>
        <tr>
            <th valign="top">
                <nobr>№</nobr>
            </th>
            <th valign="top">Раздел</th>
            <th valign="top">Дата акта</th>
            <th valign="top">Организация</th>
            <th valign="top">Дата тестирования</th>
            <th valign="top">Cчет<br>номер/дата</th>
            <th valign="top">Оплачено</th>


            <th valign="top">&nbsp;</th>
        </tr>
        </thead>
        <tbody>


        <?php


        foreach ($Result as $item):
            /** @var Act $item */

            ?>
            <tr>
                <td><?= $item->number ?>            </td>
                <td>  <?= $item->statusToText() ?></td>
                <td><?= $C->date($item->actDate(),true) ?></td>

                <td class="wrap">
                    <?php echo $item->getUniversity()->getLegalInfo()['name_parent'] ?>
                </td>
                <td><?php echo $C->date($item->testing_date) ?>
                </td>
                <td>
                    <?php if (strlen($item->invoice)):
                        echo $item->invoice ?><br><?php echo $C->date($item->invoice_date);
                    endif; ?>
                </td>
                <td><?php echo $item->paid ? 'Да' : 'Нет'; ?>
                </td>


                <td>

                    <a target="_blank" class="btn btn-info btn-block"
                       href="index.php?action=buh_view_act&id=<?php echo $item->id; ?>">Карточка</a>


                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
    <?
    //echo $paginator;
endif;

?>


