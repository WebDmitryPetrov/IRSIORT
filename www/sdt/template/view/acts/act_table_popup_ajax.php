<?php
/**
 * Created by PhpStorm.
 * User: lwr
 * Date: 23.11.2017
 * Time: 12:46
 */

switch($popup_type) {
    case 'summary_table':// делим запись
        summary_table($act_id);
        break;

    case 'act_table_print':// делим запись
        $popup_content=act_table_print($act_id);
        break;

    case 'act_print':// делим запись
        $popup_content=act_print($act_id);
        break;

    default:
        break;
}




function summary_table($act_id)
{
    $act=Act::getByID($act_id);
//    ob_start();
?>
<div class="modal hide fade" id="summary_table" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post"
          action="index.php?action=summary_table_print" class="form-horizontal" target="_blank">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">Сформировать Сводный протокол</h3>
        </div>
        <div class="modal-body">

            <?=achtung_message()?>
<? /*
            <div class="control-group">
                <label class="control-label" for="invoice_signing">Ответственный от локального центра</label>

                <div class="controls">
                    <select name="ls" id="signers2">
                        <? if (empty($act->responsible) && empty($act->official)):?><option value="responsible"></option><? else: ?>
                            <? if(!empty($act->responsible)):?><option value="responsible"><?php echo $act->responsible; ?></option><? endif;?>
                            <? if(!empty($act->official)):?><option value="official"><?php echo $act->official; ?></option><? endif;?>
                        <? endif;?>
                    </select>
                </div>
            </div>
*/ ?>
            <input type="hidden" name="ls" id="signers2" value="official">


            <div class="control-group">
                <label class="control-label" for="invoice_signing">Ответственный от головного центра</label>

                <div class="controls">
                    <select name="hs_id" id="signers">
                        <?php
                        $signers = ActSignings::get4Act();
                        foreach ($signers as $signer): ?>
                            <option value="<?php echo $signer->id; ?>"><?php echo $signer->caption; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </div>
        <input type="hidden" value="<?php echo $act->id; ?>" name="id">
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit" onclick="change_buttons('<?=$act_id?>','summary_table');$('#summary_table').modal('hide')"><!--Сформировать-->Сохранить</button>
        </div>
    </form>
</div>
<?
//    $return = ob_get_clean();
//    return $return;
}



function act_table_print($act_id)
{
    $act=Act::getByID($act_id);
    ?>
    <div class="modal hide fade" id="act_table_print" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <form method="post"
              action="index.php?action=act_table_print&id=<?=$act_id?>" class="form-horizontal" target="_blank">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3 id="myModalLabel">Сформировать Сводную таблицу</h3>
            </div>
            <div class="modal-body">


                <?=achtung_message()?>

            </div>
<!--            <input type="hidden" value="--><?php //echo $act->id; ?><!--" name="id">-->
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                <button class="btn btn-primary save" type="submit" onclick="change_buttons('<?=$act_id?>','act_table_print');$('#act_table_print').modal('hide')"><!--Сформировать-->Сохранить</button>
            </div>
        </form>
    </div>
    <?
}


function act_print($act_id)
{
    $act=Act::getByID($act_id);
//    ob_start();
    if ($act->isMigrantSession()) {
        $href = 'index.php?action=act_print_migrant&id=';
    } else {
        $href = 'index.php?action=act_print&id=';
    }



    ?>
    <div class="modal hide fade" id="act_print" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <form method="post"
              action="<?echo $href.$act_id; ?>" class="form-horizontal" target="_blank">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3 id="myModalLabel">Сформировать Акт</h3>
            </div>
            <div class="modal-body">

                <?=achtung_message()?>



                <div class="control-group">
                    <label class="control-label" for="invoice_signing">Ответственный от головного центра</label>

                    <div class="controls">
                        <select name="s_id" id="signers">
                            <?php
                            $signers = ActSignings::get4Act();
                            foreach ($signers as $signer): ?>
                                <option value="<?php echo $signer->id; ?>"><?php echo $signer->caption; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
            <input type="hidden" value="<?php echo $act->id; ?>" name="id">
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                <button class="btn btn-primary save" type="submit" onclick="change_buttons('<?=$act_id?>','act_print');$('#act_print').modal('hide')"><!--Сформировать-->Сохранить</button>
            </div>
        </form>
    </div>
    <?

}




function achtung_message()
{
    return '<h4 style="color:red">
    Внимание! Проверьте данные! Повторно сформировать документ будет невозможно!
</h4>';
}