<form class="form-horizontal" method="post" action="./?action=certificate_approve">
    <div class="control-group">
        <label class="control-label" for="type">��� ������������</label>

        <div class="controls">

            <? //echo count($types);
            foreach ($types as $ctype): ?>
                <? $blank_types=BlankTypes::getBlankTypesByType($ctype->id);
//var_dump($blank_types[1]);
                ?>

                <label class="radio"><input type="radio" value="<?= $ctype->id ?>"
                                id="ctype_<?= $ctype->id ?>"            name="type"  <?if ($type == $ctype->id):?>
                        selected="selected"
                    <?endif;?>

                        onclick="$('.btypes').removeAttr('checked'); $('input[name=blank_type_tt_<?=$ctype->id;?>]:first').attr('checked', 'checked');"



                        >

                    <?= $ctype->caption ?>

                <? if (count($blank_types) <= 1): ?> <b>(��������: <?=CertificateReservedList::countActiveByType($ctype->id) ?>)</b><? endif ?>
                </label>

                <?
                if (count($blank_types) > 1):
                    foreach ($blank_types as $btype): ?>
                        <label class="radio" ><input style="margin-left: 0px;" type="radio" value="<?= $btype->id ?>"
                            class="btypes"   name="blank_type_tt_<?=$ctype->id?>"  <?if ($blank_type == $btype->id):?>
                            selected="selected"

                        <?endif;?>
                       onclick="$('#ctype_<?=$ctype->id?>').attr('checked', 'checked');"
                                >


                        <?= $btype->name ?>
                            <b>(��������: <?=BlankTypes::countActiveByTypeAndBlankType($ctype->id,$btype->id)?>)</b>
                        </label>
                    <? endforeach ?>


                <? endif ?>

            <? endforeach ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <span style="color:red">����� ������� <strong>������</strong> ����� � �������� ������� ������������ � ��� �� ����� ������� ������������.</span>
    </div>
    <div class="control-group">
        <label class="control-label" for="series">�����</label>

        <div class="controls">
            <input type="text" placeholder="�����" id="series" name="series" value="<?= $series ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="start">��������</label>

        <div class="controls">
            <input type="text" placeholder="��" id="start" name="start" value="<?= $start ?>">
            - <input type="text" placeholder="��" id="end" name="end" value="<?= $end ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success">��������</button>
    <div  class="control-group"><span style="color:red">���� ����� � ������� �� ������������ � ��� ������� (� ����������� ��������� �� � ����������������) �������� � ������ �������� ������� � ������ �������� �������!</span></div>
</form>