<?

$reportName = !empty($caption) ? $caption : '��� ��������';
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>
    <form action="" method="POST">

        <label>������ ����������������:

            <div>
                <p>������: ������� ��� ������������(��.��.����)</p>
                <textarea type="text" name="people" required="required"
                          style="width:350px; height: 300px;"><?= htmlspecialchars($people) ?></textarea>
            </div>
        </label>
        <input type="submit" value="�������������">
    </form>
<?
if (!empty($search)): ?>

    <?php
    if ($result['error']): ?>
        <h2>������ �����</h2>
        <table class="table table-bordered">
            <?php
            foreach ($result['error'] as $er): ?>
                <tr>
                    <td><?= $er ?></td>
                </tr>
            <?
            endforeach; ?>
        </table>
    <?php
    endif; ?>
    <?php
    if ($result['not_found']): ?>
        <h2>�� �������</h2>
        <table class="table table-bordered">
            <?php
            foreach ($result['not_found'] as $er): ?>
                <tr>
                    <td><?= $er['s'] ?></td>
                    <td><?= $er['f'] ?></td>
                    <td><?= $er['d'] ?></td>
                </tr>
            <?
            endforeach; ?>
        </table>
    <?php
    endif; ?>

    <h2>�������</h2>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th rowspan="2">�������</th>
            <th rowspan="2">��� ��������</th>
            <th rowspan="2">���� ��������</th>
            <th rowspan="2">�������</th>
            <th rowspan="2">��������</th>
            <th rowspan="2">� ��������</th>
            <th rowspan="2">���� ������ ���������</th>
            <th rowspan="2">�����</th>
            <th rowspan="2">Id �������� ������</th>
            <th rowspan="2">����� �������� ������</th>
            <th rowspan="2">���� �������� �������� ������</th>
            <th rowspan="2">�������� �����</th>
            <th colspan="4">�����������, ������������� ��������� ������������</th>

        </tr>
        <tr>

            <th>������������</th>
            <th>�����</th>
            <th>���</th>
            <th>����</th>

        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result['found'] as $er): ?>
            <?
            foreach ($er['found'] as $v):/** @var ActMan $v */
                $lc = $v->getAct()->getUniversity();
                ?>
                <tr>
                    <td><?= $v->getSurname_rus() ?></td>
                    <td><?= $v->getName_rus() ?></td>
                    <td><?= $C->date($v->birth_date) ?></td>
                    <td><?= $v->getLevel()->caption ?></td>
                    <td><?php
                        echo $v->isCertificate() ? '����������' : '�������' ?></td>
                    <td><?php
                        $res = array();
                        if ($v->document_nomer) {
                            $res[] = $v->document_nomer;
                        }
                        if ($v->blank_number) {
                            $res[] = $v->getBlank_number();
                        }
                        echo implode(' / ', $res);
                        ?></td>
                    <td><?= $C->date($v->blank_date) ?></td>
                    <td>
                        <?php
                        $fnameUrl = "&download=1&fname=".str_replace(
                                ' ',
                                '_',
                                sprintf(
                                    '%s_%s_%d',
                                    $v->getSurname_rus(),
                                    $v->getName_rus(),
                                    $v->id
                                )
                            );
                        if (!empty($v->getAct()->isActTablePrinted()) && !$v->getAct()->file_act_tabl_id): ?>
                            <div><a target="_blank"
                                    href="index.php?action=act_table_print_view&id=<?php
                                    echo $v->getAct()->id; ?><?= $fnameUrl ?>">HTML ������� �������</a>
                            </div>
                        <?
                        endif; ?>
                        <?php
                        $fileact = $v->getAct()->getFileActTabl();
                        if ($fileact): ?>
                            <div>
                                <a target="_blank" href="<?php
                                echo $fileact->getDownloadURL(); ?><?= $fnameUrl ?>">���������������
                                    ������� �������</a>
                            </div> <?php
                        endif; ?>
                    </td>

                    <td><?= $v->getAct()->id ?></td>
                    <td><?= $v->getAct()->number ?></td>
                    <td><?= $C->date($v->getAct()->created) ?></td>

                    <td><?= $lc->getHeadCenter()->short_name?$lc->getHeadCenter()->short_name:$lc->getHeadCenter()->name ?></td>
                    <td><?= $lc->name ?></td>
                    <td><?= $lc->legal_address ?></td>
                    <td><?= $lc->inn ?></td>
                    <td></td>


                </tr>
            <?
            endforeach ?>
        <?
        endforeach; ?>
        </tbody>
    </table>


<?
endif; ?>