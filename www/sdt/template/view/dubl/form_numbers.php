<?php

/** @var DublAct $Act */

$signings = ActSignings::get4Certificate();
$signingsVidacha = ActSignings::get4VidachaCert();
?>
<h3>���� ������� ���������� � ������</h3>
<h4 style="color: brown"><?=$type?></h4>
<!--<h4>
    ���� ������������:
    <?php /*echo $C->date($Act->testing_date); */?>
</h4>-->
<h4>
    ��������� �����:
    <?php echo $Act->getUniversity(); ?>
</h4>
<h4>
    �������:
    <?php $university_dogovor = $Act->getUniversityDogovor();
    echo $university_dogovor; ?>
</h4>

<? /* if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>
    <a href="/sdt/index.php?action=scan_blank_upload&id=<?php echo $Act->id ?>" class="btn btn-info">������ ������ ������� ������������</a>
<?endif  */
?>
<h2 style="color:red">��������! ��� ������ ����������� ���������� ������ ������ 210*158, ���������� - ���������, ��������������� - "���������"</h2>
<!--<form method="post" action="--><?php //echo $_SERVER['REQUEST_URI'] ?><!--"-->
<!--      class="form-horizontal marks">-->

<legend>��������� ������ ������������</legend>

<?php
$needFeelNumbers = 0;
$needFeelBlanks = 0;
$anulledBlanks = 0;
$needFeelBlanks_by_test_type = array(1=>0, 2=>0);
$printCert = 0;
$printNote = 0;
foreach ($Act->getPeople() as $man) {
    $old_man=ActMan::getByID($man->old_man_id);
    $old_man=CertificateDuplicate::checkForDuplicates($old_man);

    if ($old_man->is_anull()) $anulledBlanks++;

    if (empty($man->dubl_cert_id) && !$old_man->is_anull()) {
//        $needFeelNumbers++;

//        $needFeelBlanks_by_test_type[$old_man->getAct()->test_level_type_id]++;
        $needFeelBlanks_by_test_type[$Act->test_level_type_id]++;
        $needFeelBlanks++;
    }
    else {

        $printCert++;

    }


   /* if ($man->needBlank()) {
        $needFeelBlanks++;
    }*/

}
//var_dump($needFeelBlanks_by_test_type);
?>
<div class="">
<!--    --><?// if ($needFeelNumbers && ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER))): ?>
<!--        <a href="./?action=generate_numbers&id=--><?//= $Act->id ?><!--" class="btn btn-success"-->
<!--           onclick="return confirm('�� �������?');">��������-->
<!--            ���������������-->
<!--            ������ ������������ � �������</a>-->
<!--    --><?// endif; ?>
    <? if ($needFeelBlanks && ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER))): ?>
        <a href="./dubl.php?action=dubl_act_insert_blanks&id=<?= $Act->id ?>" onclick="return confirm('��������! �� ������� ������ �������� ������� ������������!\n'+
'��������, ��� �������������, ������ ����� ����� ������ � ��������� �����, ������ ����� ����������������, � ������� ���������������� ������!');"
           class="btn btn-warning">�������� ������
            �������
            ������������
            <? $certCount_type = CertificateReservedList::countActiveByType($Act->test_level_type_id);
            /*$certCount_type1 = CertificateReservedList::countActiveByType(1);
                $certCount_type2 = CertificateReservedList::countActiveByType(2);*/
            if ($needFeelBlanks_by_test_type[$Act->test_level_type_id] > $certCount_type) {
                ?>
                <span class="label label-important">�������� : <?= $certCount_type ?></span>
                <br>
            <? } ?>

            <? /*if ($needFeelBlanks_by_test_type[1] > $certCount_type1) {
                ?>
                <span class="label label-important">�������� (���): <?= $certCount_type1 ?></span>
                <br>
            <? } ?>
                <? if ($needFeelBlanks_by_test_type[2] > $certCount_type2) {?>
                <span class="label label-important">�������� (�������): <?= $certCount_type2 ?></span>
            <? } */?>
        </a>

    <? endif; ?>

    <? if ($printCert && $C->userHasRole(Roles::ROLE_CENTER_PRINT) && empty($needFeelBlanks) && (count($Act->getPeople()) != $anulledBlanks)): ?>
        <div class="btn-group" style="display:inline-block">
            <a class="btn btn-info btn-block dropdown-toggle print-all"   data-toggle="dropdown"
               href="#">������ ������������ �� ����� ����<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signings as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_print_certificates&type=<?= $sign->id ?>&id=<?= $Act->id ?>&tt=<?=$Act->test_level_type_id?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>

        <?/*<div class="btn-group" style="display:inline-block">
            <a class="btn btn-info btn-block dropdown-toggle print-all"   data-toggle="dropdown"
               href="#">������ ������������ �� ����� ���� (�������)<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signings as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_print_certificates&type=<?= $sign->id ?>&id=<?= $Act->id ?>&tt=2"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>*/?>

        <div class="btn-group" style=";display:inline-block">
            <a class="btn btn-info btn-block  dropdown-toggle print-all" data-toggle="dropdown"
               href="#">������ ���������� � ������������ �� ����� ���� <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_act_man_print_pril_certs&type=<?= $sign->id ?>&id=<?= $Act->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>






        <div></div>
<?if ($university_dogovor->isPrintAct()):?>
    <div class="btn-group" style="display:inline-block">
        <a class="btn btn-warning
                     dropdown-toggle  btn-block print-all" data-toggle="dropdown"
           href="#">������ ����<span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach (ActSignings::get4Act() as $sign): ?>
                <li><a target="_blank"
                       href="dubl.php?action=dubl_act_print&id=<?php echo $Act->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
            <ul>
    </div>
<?endif?>





      <!--  <?/* if ($Act->allow_summary_table()):*/?>
        <div class="btn-group" style="display:inline-block">
            <a class="summary_table btn invoice btn-warning  btn-block print-all">������������ ������� ��������</a>
        </div>

        --><?/* endif;*/?>



        <? if ($Act->allow_summary_table() && $university_dogovor->isPrintProtocol()):
            if (!$Act->summary_table_id):
            ?>
                <div style="display:inline-block" class="btn-group" id="create">
                    <a class="summary_table btn invoice btn-danger  btn-block print-all">������������ ������� ��������</a>
                </div>

                <div style="display:none" class="btn-group" id="view">
                    <a target="_blank" class=" btn btn-color-black invoice btn-danger  btn-block print-all" href="dubl.php?action=dubl_act_summary_table&id=<?=$Act->id;?>" onclick="">�����������/���������� ������� ��������</a>
                </div>
            <? else : ?>

            <div style="display:inline-block" class="btn-group">
                <a target="_blank" class=" btn btn-color-black invoice btn-danger  btn-block print-all" href="dubl.php?action=dubl_act_summary_table&id=<?=$Act->id;?>" onclick="">�����������/���������� ������� ��������</a>
            </div>

            <?

            endif;
        endif;?>







    <? if ($Act->isShowInArchive()): ?>

    <div></div>
    <div class="btn-group" style="display:inline-block">
        <a
            class="btn btn-primary  dropdown-toggle  btn-block print-all" data-toggle="dropdown"
            href="#">������ ��������� ������
            ������������ <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach ($signingsVidacha as $sign): ?>
                <li><a target="_blank"
                       href="dubl.php?action=dubl_act_vidacha_cert&id=<?php echo $Act->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
            <ul>
    </div>

<!--    <div></div>-->
    <div class="btn-group" style="display:inline-block">
        <a
            class="btn btn-primary  dropdown-toggle  btn-block print-all" data-toggle="dropdown"
            href="#">������ ������� ������ ������������ <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach ($signingsVidacha as $sign): ?>
                <li><a target="_blank"
                       href="dubl.php?action=dubl_act_vidacha_reestr&id=<?php echo $Act->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
            <ul>
    </div>
<?endif?>



        <div></div>
<div class="btn-group" style="display:inline-block">
    <a data-id="<?php echo $Act->id; ?>"
       class="btn invoice btn-warning  btn-block print-all" target="_blank"
       href="dubl.php?action=dubl_print_invoice&id=<?php echo $Act->id; ?>">������ �����</a>
    </div>
    <div></div>

    <? endif; ?>

</div>
<table
    class="table table-bordered  table-striped table-condensed">
    <thead>
    <tr>
        <td><strong>�������</strong><br/> �������� <br/> ����������</td>
        <td><strong>���</strong><br/> ��������<br/> ����������</td>
        <? if ($Act->isShowInArchive()): ?><td><strong>�������</strong></td><?endif;?>
        <td><strong>������� ������������</strong></td>
<!--        <td><strong>��� ���������</strong></td>-->
        <td><strong>
<!--                <?/* if ($Act->test_level_type_id == 2): */?>
                    ����� ���������<br>
                --><?/* endif */?>
                ����� ������<br>���� ������</strong></td>
        <td>������

        </td>
    </tr>

    </thead>
    <tbody>
    <?php

    foreach ($Act->getPeople() as $Man):
        $old_man=ActMan::getByID($Man->old_man_id);
        $old_man=CertificateDuplicate::checkForDuplicates($old_man);
//echo $Man->old_man_id;
            //echo (var_dump($old_man));
        ?>
        <tr
            class="js-man-row  <?php if ($old_man->is_retry): ?> man-retry <?php endif ?>">

            <td><span class="js-surname-rus"><?php echo $Man->getSurnameRus(); ?> </span>
                <br> <span><?php echo $Man->getSurnameLat(); ?>
					</span>
            </td>
            <td><span class="js-name-rus"><?php echo $Man->getNameRus(); ?> </span> <br> <span><?php echo $Man->getNameLat(); ?>
					</span>
            </td>

            <? if ($Act->isShowInArchive()): ?><td><span><?php echo $old_man->passport; ?></span></td><?endif;?>
            <td><?php echo $old_man->getTest()->getLevel()->caption; ?>
            </td>

            <td>

                <? $blank_type=BlankType::getBlankType($Act->test_level_type_id,$Man->getCertNum());
                if (empty($blank_type->default) && !empty($blank_type->name)) echo '<span style="color:red">'.$blank_type->name.'</span><br>';
                ?>

<? if (!$old_man->is_anull()): ?>
                <input type="text" name="user[<?php echo $Man->id; ?>][blank_number]"
                       class="input-large js-blank"
                       value="<?= $Man->getCertNum() ?>" placeholder="����� ������" disabled="disabled"><br>
<? else :?>
    <p class="text-error"><strong>�����������</strong><br><?= $C->date($old_man->getAnull()->date_annul)?><br><strong>�������:</strong><br><?= $old_man->getAnull()->reason ?></p>
                <? endif; ?>









            </td>
            <td>
                <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)
                    && $Man->dubl_cert_id
                    /*&& (
                        ($Act->test_level_type_id == 2 && $Man->document_nomer)
                        ||
                        $Act->test_level_type_id != 2
                    )
                    && $Man->blank_date
//
                    && $Man->document == "certificate"*/
                ): ?>
                    <div class="btn-group">
                        <a class="btn btn-info btn-block btn-small dropdown-toggle" data-toggle="dropdown"
                           href="#">���������� <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signings as $sign): ?>
                                <li><a target="_blank"
                                       href="dubl.php?action=dubl_print_certificate&type=<?= $sign->id ?>&id=<?php echo $Man->old_man_id; ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>
                    <div></div>
                    <div class="btn-group">
                        <a
                            class="btn btn-info btn-small  btn-block  dropdown-toggle" data-toggle="dropdown"
                            href="#">���������� <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signingsVidacha as $sign): ?>
                                <li><a target="_blank"
                                       href="dubl.php?action=dubl_act_man_print_pril_cert&id=<?php echo $Man->old_man_id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>




                <?/*-------------------------------------*/?>

                    <div></div>
<!--                    --><?php //if ($C->userHasRole(Roles::ROLE_ACT_INVALID) && $Man->isCertificate() && !$Man->needBlank()): ?>
                    <?php if (1==1): ?>
                        <? /*href="./?action=man_issue_duplicate&id=<?= $Man->id ?>"*/ ?>
                        <button data-id="<?= $Man->id ?>"
                                class="btn btn-danger  btn-block  btn-small js-man-invalid-button">�������
                            ����������������
                        </button>
                    <? endif; ?>

                    <?/*-------------------------------------*/?>







                <?php endif; ?>
                <div></div>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<!--<div class="form-actions">-->
<!--    <!--        <button class="btn btn-success" type="submit">���������</button>-->
<!--</div>-->
<!--</form>-->


<script>
    $(function () {
        $('.js-man-invalid-button').on('click', function (E) {
            E.preventDefault();
            var $this = $(this);
            var manId = $this.data('id');
            var row = $this.closest('.js-man-row');
            var blank = row.find('.js-blank').val();
            var name = row.find('.js-surname-rus').text() + ' ' + row.find('.js-name-rus').text();


            var modalMan = $('.js-man-invalid-modal');
            modalMan.find('.js-blank').text(blank);
            modalMan.find('.js-name').text(name);
            modalMan.find('.js-reason').val('');
            modalMan.find('.js-accept').off('click');
            modalMan.find('.js-accept').on('click', function (E) {
                var reason = $('.js-reason').val();
                if (reason.length < 10) {
                    alert('������� ������ ���� ������ 10 ��������');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: './dubl.php?action=man_blank_invalid',
                    data: {
                        id: manId,
                        reason: reason
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

<div class="js-man-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>������� ���������������� ����� �����������</h3>
    </div>
    <div class="modal-body">
        <p>�� ��������� ������� ���������������� ����� ����������� <span class="js-blank"></span>
            � <span class="js-name"></span></p>

        <p><label style=" padding-right: 15px;">������� �������:<br>
                <textarea style="width: 100%" class="js-reason"
                          placeholder="��������: �������� �����"></textarea></label></p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">��������</a>
        <a class="btn btn-primary js-accept">�����������</a>
    </div>
</div>


<div class="modal hide fade" id="summary_table" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post"
          action="dubl.php?action=dubl_summary_table_print" class="form-horizontal" target="_blank">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">������������ ������� ��������</h3>
        </div>
        <div class="modal-body">


            <div class="control-group">
                <label class="control-label" for="invoice_signing">������������� �� ���������� ������</label>

                <div class="controls">
                    <select name="ls" id="signers2">

                        <? if(!empty($Act->responsible)):?><option value="responsible"><?php echo $Act->responsible; ?></option><? endif;?>
                        <? if(!empty($Act->official)):?><option value="official"><?php echo $Act->official; ?></option><? endif;?>

                    </select>
                </div>
            </div>


            <div class="control-group">
                <label class="control-label" for="invoice_signing">������������� �� ��������� ������</label>

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
            <span style="color:red">����� ������������ �������� ��������� �������� ��� ����� ������!</span>
        </div>
        <input type="hidden" value="<?php echo $Act->id; ?>" name="id">

        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
            <button class="btn btn-primary save" type="submit" onclick="$('#summary_table').modal('hide');$('#create').remove();$('#view').css('display','inline-block');"><!--������������-->���������</button>
        </div>
    </form>
</div>



<script>
    $(function () {
        $('.summary_table').on('click', function (e) {
            e.preventDefault();
            $('#summary_table').modal();
        });

    });
</script>