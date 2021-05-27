<?
/** @var ActMan $man */
?>
    <h1>������ ��������� �����������</h1>
    <table class="table table-bordered">
        <tr>
            <th>�������</th>
            <td><?= $man->surname_rus ?></td>
        </tr>
        <tr>
            <th>���</th>
            <td><?= $man->name_rus ?></td>
        </tr>
        <tr>
            <th>������� ���������</th>
            <td><?= $man->surname_lat ?></td>
        </tr>
        <tr>
            <th>��� ���������</th>
            <td><?= $man->name_lat ?></td>
        </tr>
        <tr>
            <th>�����������</th>
            <td><?= $man->getCountry() ?></td>
        </tr>
        <tr>
            <th>���� ��������</th>
            <td><?= $C->date($man->birth_date) ?></td>
        </tr>
        <tr>
            <th>����� ��������</th>
            <td><?= $man->birth_place ?></td>
        </tr>
        <tr>
            <th>��������</th>
            <td><?= $man->passport_name ?>
                <?= $man->passport_series ?>/<?= $man->passport ?>
                �� <?= $C->date($man->passport_date) ?> <?= $man->passport_department ?></td>
        </tr>
        <tr>
            <th>������������ �����</th>
            <td><?= $man->migration_card_series ?>/<?= $man->migration_card_number ?></td>
        </tr>
        <tr>
            <th>������� ������������</th>
            <td><?= $man->getLevel()->caption ?></td>
        </tr>
        <tr>
            <th>����������</th>
            <td><?
                $res = array();
                if ($man->document_nomer) {
                    $res[] = $man->document_nomer;
                }
                if ($man->blank_number) {
                    $res[] = $man->blank_number;
                }

                echo implode(' / ', $res);

                ?>
                <? if ($man->blank_date != '0000-00-00' && !is_null($man->blank_date)): ?>
                    ��
                    <?= $C->date($man->blank_date); ?>
                <? endif ?>
            </td>
        </tr>
        <tr>
            <th>�������� �����</th>
            <td>              <?= $man->getAct()->getUniversity()->getHeadCenter()->getTitle(); ?>

            </td>
        </tr>
        <tr>
            <th>��������� �����</th>
            <td>              <?= $man->getAct()->getUniversity()->name ?>

            </td>
        </tr>
        <? if ($duplicates): ?>
            <tr class="warning">
                <td colspan="2">�������� ���������</td>
            </tr>
            <? foreach ($duplicates as $dup):
                /** @var CertificateDuplicate $dup */
                ?>
                <tr class="info">
                    <td>�������� ��</td>
                    <td><?= $C->date($dup->certificate_issue_date); ?></td>
                </tr>
                <tr>
                    <td>����� ������ �����������</td>
                    <td><?= $dup->certificate_number ?> </td>
                </tr>
                <tr>
                    <td>���������</td>
                    <td><a href="<?= $dup->getRequestFile()->getDownloadURL() ?>">�������</a></td>
                </tr>
                <? if ($dup->personal_data_changed): ?>
                <tr>
                    <th>�������</th>
                    <td><?= $dup->surname_rus ?></td>
                </tr>
                <tr>
                    <th>���</th>
                    <td><?= $dup->name_rus ?></td>
                </tr>
                <tr>
                    <th>������� ���������</th>
                    <td><?= $dup->surname_lat ?></td>
                </tr>
                <tr>
                    <th>��� ���������</th>
                    <td><?= $dup->name_lat ?></td>
                </tr>
                <tr>
                    <td>�������</td>
                    <td><a href="<?= $dup->getPassportFile()->getDownloadURL() ?>">�������</a></td>
                </tr>

            <? endif ?>
                <tr>
                    <th>�������� �����</th>
                    <td>
                        <?= $dup->getCert()->getHeadCenter()->getTitle(); ?>
                    </td>
                </tr>

            <? endforeach ?>
        <? endif ?>


    </table>


<? if (1 == 2): ?>

    <? $signings = ActSignings::get4Certificate();
    $signingsVidacha = ActSignings::get4VidachaCert();
    ?>

    <?php if ($man->blank_number
        && $man->blank_date
        && $man->blank_date != '0000-00-00' && $man->document == "certificate"): ?>
        <div class="btn-group">
            <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
               href="">���������� <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signings as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=print_certificate&type=<?= $sign->id ?>&id=<?php echo $man->id; ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div></div>
        <div class="btn-group">
            <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
               href="">���������� <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=act_man_print_pril_cert&type=<?= $sign->id ?>&id=<?php echo $man->id; ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div></div>


    <?php endif; ?>


    <div class="btn-group">
        <a
                class="btn btn-danger  dropdown-toggle  btn-b lock" data-toggle="dropdown"
                href="#">������ ��������� ������
            ������������ <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach ($signingsVidacha as $sign): ?>
                <li><a target="_blank"
                       href="index.php?action=act_vidacha_cert&id=<?php echo $man->act_id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
            <ul>
    </div>
    <div></div>
    <div class="btn-group">
        <a
                class="btn btn-danger  btn-blo ck dropdown-toggle" data-toggle="dropdown"
                href="#">������ ������� ������ ������������ <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach ($signingsVidacha as $sign): ?>
                <li><a target="_blank"
                       href="index.php?action=act_vidacha_reestr&id=<?php echo $man->act_id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<? endif ?>