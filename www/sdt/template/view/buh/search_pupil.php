<form class="form-search" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <input type="hidden" name="action" value="search_pupil">
    <input type="hidden" name="do" value="search">
    <div class="input-append">
        <input name="query" type="text" class="input-xxlarge search-query"
               placeholder="������� ������� ������������"
               value="<?php echo $query; ?>">
        <button type="submit" class="btn">�����</button>
    </div>
    <div><input name="name" type="text" class="input-xxlarge search-query"
                placeholder="��� � ��������"
                value="<?php echo $name; ?>">
    </div>
    <div><input name="certificate" type="text" class="input-xxlarge search-query"
                placeholder="����� ������ ����������� \ ����� �������"
                value="<?php echo $certificate; ?>">
    </div>

    <!--<div class="input-append">
        <input name="query" type="text" class="input-xxlarge search-query"
               placeholder="������� ������� ������������"
               value="<?php /*echo $query; */ ?>">
        <button type="submit" class="btn">�����</button>
    </div>
    <div>
        <input name="certificate" type="text" class="input-xxlarge search-query"
                placeholder="����� ������ ����������� \ ����� �������"
                value="<?php /*echo $certificate; */ ?>">
    </div>-->

</form>
<?php
if ($Result):?>

    <?
    //require_once('paginator_tab.php');


    // echo $paginator;
    ?>

    <table
        class="table table-bordered  table-striped table-condensed">
        <thead>
        <tr>
            <td rowspan="2"><strong>�������</strong><br/> �������� <br/>
                ����������
            </td>
            <td rowspan="2"><strong>���</strong><br/> ��������<br/> ����������</td>
            <td rowspan="2"><strong>������</strong></td>

            <td rowspan="2"><strong>���� ������������</strong></td>
            <td rowspan="2"><strong>������� ������������</strong></td>
            <!--			<td colspan="6" class="center"><strong>����������</strong> (����� /-->
            <!--				%)</td>-->
            <td rowspan="2" class="center"><strong>����</strong>
            </td>
            <td rowspan="2" class="center"><strong>���</strong>
            </td>
        </tr>
        <!--		<tr>-->
        <!--			<td class="center">��</td>-->
        <!--			<td class="center">���</td>-->
        <!--			<td class="center">����</td>-->
        <!--			<td class="center">���</td>-->
        <!--			<td class="center">���</td>-->
        <!--			<td class="center">���</td>-->
        <!--		</tr>-->

        </thead>
        <tbody>
        <?php foreach ($Result as $Man):
            /** @var ActMan $Man */
            ?>
            <tr
                class="">

                <td><span><?php echo $Man->getSurname_rus(); ?> </span> <br> <span><?php echo $Man->getSurname_lat(); ?>
			</span>
                </td>
                <td><span><?php echo $Man->getName_rus(); ?> </span> <br> <span><?php echo $Man->getName_lat(); ?>
			</span>
                </td>
                <td><?php echo $Man->getCountry()->name; ?></td>
                <td><?php echo $C->date($Man->testing_date) ?>
                </td>
                <td><?php echo $Man->getTest()->getLevel()->caption ?>
                </td>
                <? /*
			<td><span class="percent"><?php echo $Man->reading ?> </span> <span
				class="percent">(<?php echo $Man->reading_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->writing; ?> </span> <span
				class="percent">(<?php echo $Man->writing_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->grammar; ?> </span> <span
				class="percent">(<?php echo $Man->grammar_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->listening; ?> </span> <span
				class="percent">(<?php echo $Man->listening_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->speaking; ?> </span> <span
				class="percent">(<?php echo $Man->speaking_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->total; ?> </span> <span
				class="percent">(<?php echo $Man->total_percent; ?>%)
			</span>
			</td>
 */ ?>
                <td>
                <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->isCertificate() ? '����������' : '�������' ?>


                        </span>
                    <? if ($Man->duplicate): ?>
                        - <strong>��������</strong>
                    <? endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($Man->document_nomer) {
                            $res[] = $Man->document_nomer;
                        }
                        if ($Man->blank_number) {
                            $res[] = $Man->getBlank_number();
                        }
                        elseif($Man->is_anull() && $Man->getAnull()->blank_number){
                            $res[] = $Man->getAnull()->blank_number;
                        }

                        echo implode(' / ', $res); ?></strong>
                    <br>
                    <?php
                    if (!$Man->duplicate) {
                        echo HeadCenter::getNameByActID($Man->act_id);
                    } else {
                        echo HeadCenter::getNameByCertificateID($Man->duplicate->certificate_id);
                    }

                    if ($Man->blank_date != '0000-00-00' && !is_null($Man->blank_date)): ?>
                        <br>
                        <?= $C->date($Man->blank_date); ?>
                    <? endif ?>

                    <? if ($Man->is_anull()): ?>
                        <p class="text-error"><strong>�����������</strong><br><?= $C->date($Man->getAnull()->date_annul)?><br><strong>�������:</strong><br><?= $Man->getAnull()->reason ?></p>
                    <? endif ?>
                </td>
                <td>
                    <? if (Act::isHeadCenter($Man->act_id, CURRENT_HEAD_CENTER) && !$Man->is_anull()): ?>
                        <a class="btn btn-info btn-mini btn-block" target="_blank"
                           href="index.php?action=buh_view_act&id=<?php echo $Man->act_id; ?>">�������� ����</a>
                    <? endif ?>

                    <?
                    $dublList = DublActList::getByMan($Man->id);
//                    var_dump($dublList);
                    if (
                        $Man->isCertificate()
                        && $dublList
                        && count($dublList)
                        && (
                        ($C->userHasRole(Roles::ROLE_SUPERVISOR) && Act::getActState($Man->act_id) == Act::STATE_ARCHIVE)
                            /*|| ($C->userHasRole(Roles::ROLE_CENTER_EDITOR) && $Man->getBlank_number())*/
                        )
                    ): ?>
                        <a class="btn btn-warning btn-mini btn-block" target="_blank"
                           href="dubl.php?action=dubl_man_acts&id=<?php echo $Man->id; ?>">������ �������� ��
                            ���������</a>

                    <? endif ?>

                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
endif;
