<?php

/** @var Act $Act */
/** @var ActMan[] $people */

?>
<h3>������ ������ ������� ������������</h3>
<div class="row-fluid">
    <? if (/*!$scansLeft && */!$blanksLeft): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ��� ������ ���������
        </div>
    <? else: ?>

       <!-- <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            �������� ��������������� ����������: <strong><?/*= $scansLeft; */?></strong>
        </div>-->
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            �������� ������� �������: <strong><?= $blanksLeft; ?></strong>
        </div>

    <? endif; ?>
</div>


<form method="post" enctype="multipart/form-data">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>������� ���</th>
            <th>��� � ����� ���������</th>
            <th>����� ������</th>
<!--            <th>���������</th>-->
        </tr>
        </thead>
        <tbody>
        <? foreach ($people as $man): ?>
            <tr style="
            <?/* if ($man->getSoprovodPassport()): */?><!--
                background-color: #98fb98            ;
            --><?/* endif; */?>
                ">
                <td>
                    <?= $man->surname_rus ?> <?= $man->name_rus ?>
                 <!--   <?/* if ($man->getSoprovodPassport()): */?>
                        <br>
                        <a href="<?/*= $man->getSoprovodPassport()->getDownloadURL() */?>">�������</a>
                    --><?/* endif */?>
                </td>
                <td>
                        <span class="<?php echo $man->document; ?> ">
                        <?php echo $man->document == "certificate" ? '����������' : '�������' ?>
                        </span>
                    <? if ($man->document == "certificate"): ?>
                        <div><input type="text" class="disabled input-medium" disabled="disabled"
                                    value="<?= $man->document_nomer; ?>"></div>
                    <? endif ?>
                </td>
                <td>

                    <? if ($man->document == "certificate"): ?>
                        <input type="text" name="blank[<?= $man->id ?>]" class="input-large"
                               value="<?= $man->blank_number ?>"><br>

                        <input type="text" name="blank_date[<?= $man->id ?>]" class="input-large datepicker"
                               value="<?php
                               if (!is_null($man->blank_date) && $man->blank_date != '0000-00-00') {
                                   echo date('d.m.Y', strtotime($man->blank_date));
                               } else {
                                   echo date('d.m.Y');
                               }?>"><br>


                    <? endif ?>
                </td>
             <!--   <td><input type="file" name="file[<?/*= $man->id */?>]">
                <div style="color:red">����� ����� < 10MB</div></td>-->
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
    <input type="submit" class="btn btn-success" value="���������">
  <!--  <div style="color:red">��������� ����� ������ < 250MB</div>-->
</form>