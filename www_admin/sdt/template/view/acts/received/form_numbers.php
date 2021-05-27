<?php
$signings=ActSignings::get4Certificate();
$signingsVidacha = ActSignings::get4VidachaCert();
?>
<h3>���� ������� ���������� � ������</h3>
<h4>
    ���� ������������:
    <?php echo $C->date($Act->testing_date); ?>
</h4>
<h4>
    �����������:
    <?php echo $Act->getUniversity(); ?>
</h4>
<h4>
    �������:
    <?php echo $Act->getUniversityDogovor(); ?>
</h4>
<? if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>
    <a href="/sdt/index.php?action=scan_blank_upload&id=<?php echo $Act->id ?>" class="btn btn-info">������ ������ ������� ������������</a>
<?endif?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
      class="form-horizontal marks">

    <legend>��������� ������ ������������</legend>


    <table
        class="table table-bordered  table-striped table-condensed">
        <thead>
        <tr>
            <td><strong>�������</strong><br/> �������� <br/> ����������</td>
            <td><strong>���</strong><br/> ��������<br/> ����������</td>
            <td><strong>�������</strong></td>
            <td><strong>������� ������������</strong></td>
            <td><strong>��� ���������</strong></td>
            <td><strong>����� ���������</strong></td>
            <td>������</td>
        </tr>

        </thead>
        <tbody>
        <?php foreach ($Act->getPeople() as $Man): ?>
            <tr
                class="">

                <td><span><?php echo $Man->surname_rus; ?> </span> <br> <span><?php echo $Man->surname_lat; ?>
					</span>
                </td>
                <td><span><?php echo $Man->name_rus; ?> </span> <br> <span><?php echo $Man->name_lat; ?>
					</span>
                </td>

                <td><span><?php echo $Man->passport; ?></span></td>
                <td><?php echo $Man->getTest()->getLevel()->caption; ?>
                </td>
                <td>
                        <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->document == "certificate" ? '����������' : '�������' ?>
                        </span>
                </td>
                <td>
                    <?php if ($C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT)): ?>
                    <input type="text"
                           name="user[<?php echo $Man->id; ?>][document_nomer]"
                           class="input-medium" value="<?php echo $Man->document_nomer; ?>"
                           placeholder="����� ���������">
                    <?php elseif (!$C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT)):?>
                        <span><?php echo $Man->document_nomer; ?></span>
                    <?php endif;?>


                </td>
                <td><?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT) && $Man->document_nomer && $Man->document == "certificate"): ?>
                        <div class="btn-group">
                            <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
                               href="#">���������� <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php foreach($signings as $sign):?>
                                <li><a target="_blank"
                                       href="index.php?action=print_certificate&type=<?=$sign->id?>&id=<?php echo $Man->id; ?>"><?=$sign->caption?></a></li>
<?php endforeach; ?>
                            <ul>
                        </div>
                        <div></div>
                        <div class="btn-group">
                            <a
                                class="btn btn-info btn-small  dropdown-toggle" data-toggle="dropdown"
                                href="#">���������� <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php foreach ($signingsVidacha as $sign): ?>
                                    <li><a target="_blank"
                                           href="index.php?action=act_man_print_pril_cert&id=<?php echo $Man->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                    </li>
                                <?php endforeach; ?>
                                <ul>
                        </div>


                    <?php endif; ?>



                    <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT) && $Man->document != 'certificate'): ?>
                        <div></div>
                        <div class="btn-group">
                            <a
                                class="btn btn-info btn-small  dropdown-toggle" data-toggle="dropdown"
                                href="#">��� <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php foreach ($signingsVidacha as $sign): ?>
                                    <li><a target="_blank"
                                           href="index.php?action=act_rki&id=<?php echo $Man->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                    </li>
                                <?php endforeach; ?>
                                <ul>
                        </div>


                    <?php endif; ?>
                    <div><?php if ($Man->getSoprovodPassport()): ?>
                            <a href="<?php echo $Man->getSoprovodPassport()->getDownloadUrl() ?>"
                               class="btn btn-success btn-small"><span class="custom-icon-download"></span></a>
                        <?php endif; ?></div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <div class="form-actions">
        <button class="btn btn-success" type="submit">���������</button>
    </div>
</form>


