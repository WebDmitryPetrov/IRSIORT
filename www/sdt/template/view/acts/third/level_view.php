<?php

/** @var Act $object


 */

$show_free = Reexam_config::isShowInAct($object->test_level_type_id);
$dogovor = $object->getUniversityDogovor();
if ($object->is_changed_checker):?>
    <div class="row-fluid">
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            �������� ������� ����������� ��������
        </div>
    </div>
<?php endif; ?>
<div class="btn-toolbar">
    <div class="btn-group">
        <button class="btn dropdown-toggle btn-info " data-toggle="dropdown">
            ������
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">


            <?if ($dogovor->isPrintAct()):?>
            <li class="dropdown-submenu">

                <a tabindex="-1" href="#">���</a>
                <ul class="dropdown-menu">
                    <? $signers = ActSignings::get4Act();
                    if ($object->isMigrantSession()) {
                        $href = 'index.php?action=act_print_migrant&id=';
                    } else {
                        $href = 'index.php?action=act_print&id=';
                    }
                    foreach ($signers as $signer) {
                        echo '<li><a class=""   target="_blank"
               href="' . $href . $object->id . '&s_id=' . $signer->id . '">' . $signer->position . ' ' . $signer->caption . '</a>
        </li>';
                    }

                    ?>
                </ul>
            </li>
            <?endif?>



            <li><a class="" target="_blank"
                   href="index.php?action=act_table_print&id=<?php echo $object->id; ?>"
                   target="_blank">������� �������</a></li>


        </ul>
    </div>
    <div class="btn-group">
        <button class="btn dropdown-toggle btn-warning " data-toggle="dropdown">
            ��������� ����
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?if ($dogovor->isPrintAct()):?>
            <li><a class="act_upload">���</a>
            </li>
            <?endif?>
            <li><a class="act_tabl_upload">������� �������</a>
        </ul>
    </div>

    <? if ($object->allow_summary_table() && $dogovor->isPrintProtocol()):?>

        <div style="display:inline-block" class="btn-group">
            <a class="summary_table btn invoice btn-danger  btn-block print-all">������������ ������� ��������</a>
        </div>
    <? endif;?>

</div>

<table class="table table-bordered  table-striped">
    <tr>
        <th>�����</th>
        <td><?php echo $object->number ?>
        </td>
    </tr>

    <tr>
        <th>��������� �����</th>
        <td><?php echo $object->getUniversity()->name ?>
        </td>
    </tr>
    <tr>
        <th>�������</th>
        <td><?php echo $object->getUniversityDogovor() ?>
        </td>
    </tr>
    <tr>
        <th>������������ ���</th>
        <td><?php echo $object->official ?>
        </td>
    </tr>
    <tr>
        <th>������������� �� ���������� �����������</th>
        <td><?php echo $object->responsible ?>
        </td>
    </tr>
    <tr>
        <th>���� ������������</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>

    <tr>
        <th>������ �� ��������� ������ <?= TEXT_HEADCENTER_SHORT_IP ?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>�����������</th>
        <td class="text-success"><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>��������</th>
        <td><?php echo $object->paid ? '��' : '���' ?>
        </td>
    </tr>

    <tr>
        <th>����������� �����</th>
        <td><?php  $fileact = $object->getFileAct();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">���������������
                        ���</a>
                </div> <?php endif; ?> <?php  $fileact = $object->getFileActTabl();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">���������������
                        ������� �������</a>
                </div> <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>������������</th>
        <td>
            <?
            $template_buttons = '';
            echo $this->import('acts/act_table_template', array('object' => $object, 'show_free' => $show_free, 'template_buttons' => $template_buttons));
            ?>

            <a class="btn btn-info btn-small"
               href="index.php?action=act_table_view&id=<?php echo $object->id; ?>">����������� ������� �������</a></td>
    </tr>


</table>


<div class="modal hide fade" id="act" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=act_upload_scan" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">��������� ��������������� ���</h3>
        </div>
        <div class="modal-body">


            <legend>�������� ����</legend>

            <input type="hidden" value="<?php echo $object->id; ?>" name="id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
            <button class="btn btn-primary save" type="submit">���������</button>
        </div>
    </form>
</div>
<div class="modal hide fade" id="act_tabl" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=act_upload_tabl_scan" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">��������� ��������������� ������� �������</h3>
        </div>
        <div class="modal-body">


            <legend>�������� ����</legend>

            <input type="hidden" value="<?php echo $object->id; ?>" name="id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
            <button class="btn btn-primary save" type="submit">���������</button>
        </div>
    </form>
</div>








<div class="modal hide fade" id="summary_table" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post"
          action="index.php?action=summary_table_print" class="form-horizontal" target="_blank">
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
<? if (empty($object->responsible) && empty($object->official)):?><option value="responsible"></option><? else: ?>
                        <? if(!empty($object->responsible)):?><option value="responsible"><?php echo $object->responsible; ?></option><? endif;?>
                        <? if(!empty($object->official)):?><option value="official"><?php echo $object->official; ?></option><? endif;?>
<? endif;?>
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

        </div>
        <input type="hidden" value="<?php echo $object->id; ?>" name="id">
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
            <button class="btn btn-primary save" type="submit" onclick="$('#summary_table').modal('hide')"><!--������������-->���������</button>
        </div>
    </form>
</div>

<script>
    $(function () {

        $('.act_upload').on('click', function (e) {
            e.preventDefault();
            $('#act').modal();
        });
        $('.act_tabl_upload').on('click', function (e) {
            e.preventDefault();
            $('#act_tabl').modal();
        });
        $('.summary_table').on('click', function (e) {
            e.preventDefault();
            $('#summary_table').modal();
        });

    });
</script>
