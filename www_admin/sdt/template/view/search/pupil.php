<form class="form-search" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">
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


    <div>
        <div class="input-prepend date datepicker"
             data-date="<?php echo $birthday ?>">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    style="width:100px"
                    class="input-small" name="birthday" id="birthday"
                    readonly="readonly" size="16" type="text"
                    value="<?php  echo $birthday ?>" placeholder="���� ��������">
        </div>
    </div>

</form>
<?php
if ($Result || $archive):?>
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
                        } elseif ($Man->is_anull() && $Man->getAnull()->blank_number) {
                            $res[] = $Man->getAnull()->blank_number;
                        }
                        if (!$Man->duplicate) {
                            $res[] = HeadCenter::getNameByActID($Man->act_id);
                        } else {
                            $res[] = HeadCenter::getNameByCertificateID($Man->duplicate->certificate_id);
                        }
                        echo implode(' / ', $res); ?></strong>
                    <? if ($Man->blank_date != '0000-00-00' && !is_null($Man->blank_date)): ?>
                        <br>
                        <?= $C->date($Man->blank_date); ?>
                    <? endif ?>
                    <? if ($Man->is_anull()): ?>
                        <p class="text-error"><strong>�����������</strong><br><?= $C->date($Man->getAnull()->date_annul)?><br><strong>�������:</strong><br><?= $Man->getAnull()->reason ?></p>
                    <? endif ?>
                </td>
                <td>
                    <? if (!$Man->is_anull()): ?>
                    <a class="btn btn-info  btn-block  btn-small"
                       href="index.php?action=act_received_view&id=<?php echo $Man->getAct()->id; ?>">��������
                        ����</a>

                    <? if ($Man->isCertificate() && $Man->duplicate && $Man->getAct()->state == Act::STATE_ARCHIVE
                    ): ?>
                        <a class="btn btn-warning btn-block  btn-small" target="_blank"
                           href="index.php?action=man_duplicate&id=<?php echo $Man->id; ?>&h_id=<?php echo HeadCenter::getByActID($Man->act_id)->id; ?>">��������</a>
                        <? endif; ?>

                    <? endif ?>

                    <div></div>
                    <?php if (/*$C->userHasRole(Roles::ROLE_ACT_INVALID) &&*/
                        $Man->isCertificate() && !$Man->needBlank()
                    ): ?>
                        <? /*href="./?action=man_issue_duplicate&id=<?= $Man->id ?>"*/ ?>
                        <button
                                data-id="<?= $Man->id ?>"
                                data-surname_rus="<?= $Man->getSurname_rus() ?>"
                                data-name_rus="<?= $Man->getName_rus() ?>"
                                data-surname_lat="<?= $Man->getSurname_lat() ?>"
                                data-name_lat="<?= $Man->getName_lat() ?>"
                                data-blank_number="<?= $Man->getBlank_number() ?>"
                                data-reg_number="<?= $Man->document_nomer ?>"
                                data-state="<?= $Man->getAct()->state ?>"
                                data-univer="<?= htmlspecialchars($Man->getAct()->getUniversity()->name) ?>"
                                data-hc="<?= htmlspecialchars($Man->getAct()->getUniversity()->getHeadCenter()->name) ?>"
                                data-act_id="<?= ($Man->getAct()->id) ?>"
                                data-num_id="<?= htmlspecialchars($Man->getAct()->number) ?>"
                                class="btn btn-danger  btn-block  btn-small js-man-invalid-button">������������
                            ����������
                        </button>
                    <? endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        <?php
        foreach ($archive as $am):
            /** @var \SDT\models\Archive\Man $am */
            ?>
            <tr
                    class="">

                <td><span><?php
                        echo $am->surname_rus ?> </span> <br> <span><?php
                        echo $am->surname_lat ?>
			</span>
                </td>
                <td><span><?php
                        echo $am->name_rus; ?> </span> <br> <span><?php
                        echo $am->name_lat; ?>
			</span>
                </td>
                <td><?php
                    echo $am->getCountry()->name; ?></td>
                <td><?php
                    echo $C->date($am->testing_date) ?>
                </td>
                <td><?php
                    echo $am->getTestLevel()->caption ?>
                </td>

                <td>
                <span class="<?php
                echo $am->document; ?> ">
                        <?php
                        echo $am->isCertificate() ? '����������' : '�������' ?>


                        </span>
                    <?
                    if ($am->original_blank_number): ?>
                        - <strong>��������</strong>
                    <?
                    endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($am->document_nomer) {
                            $res[] = $am->document_nomer;
                        }
                        if ($am->blank_number) {
                            $res[] = $am->blank_number;
                        } elseif ($am->annul_blank) {
                            $res[] = $am->annul_blank;
                        }
                        echo implode(' / ', $res); ?></strong>
                    <br>

                    <?= $am->head_center ?>
                    <?php
                    if ($am->blank_date != '0000-00-00' && !is_null($am->blank_date)): ?>
                        <br>
                        <?= $C->date($am->blank_date); ?>
                    <?
                    endif ?>

                    <?
                    if ($am->annul): ?>
                        <p class="text-error"><strong>�����������</strong>
                            <br>
                            <?= $C->date($am->annul_date) ?><br>
                            <strong>�������:</strong><br><?= $am->annul_reason ?></p>
                    <?
                    endif ?>
                </td>
                <td>
                    <?
                    if (false == $am->annul): ?>
                        <a class="btn btn-info btn-mini btn-block" target="_blank"
                           href="index.php?action=archive_man&id=<?php
                           echo $am->id; ?>">�������� ������������</a>

                        <?php
                        if ($uploadedPhoto = \SDT\models\Archive\PhotoFile::getByUserType(
                            $am->id,
                            \SDT\models\Archive\PhotoFile::TYPE_PHOTO
                        )): ?>
                            <a href="<?php
                            echo $uploadedPhoto->getDownloadUrl() ?>"
                               target="_blank"
                               class="btn  btn-mini btn-block btn-primary"> ����������</a>
                        <?php
                        endif; ?>
                    <?php
                    endif; ?>


                </td>

            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>
    <?php
endif;
?>

<script>
    $(function () {
        $('.js-man-invalid-button').on('click', function (E) {
            E.preventDefault();


            var $this = $(this);

            if ($this.data('state') !== 'archive') {
                var modalArchive = $('.js-man-invalid-need-archive-modal');
                modalArchive.find('.js-univer').text($this.data('univer'));
                modalArchive.find('.js-hc').text($this.data('hc'));
                modalArchive.find('.js-act-id').text($this.data('act_id'));
                modalArchive.find('.js-act-num').text($this.data('num_id'));
                modalArchive.modal();
                return;
            }

            var manId = $this.data('id');
            var row = $this.closest('.js-man-row');
            var blank = row.find('.js-blank').val();
            var name = row.find('.js-surname-rus').text() + ' ' + row.find('.js-name-rus').text();

            $('#surname_rus').val($this.data('surname_rus'));
            $('#name_rus').val($this.data('name_rus'));
            $('#surname_lat').val($this.data('surname_lat'));
            $('#name_lat').val($this.data('name_lat'));
            $('#blank_number').val($this.data('blank_number'));
            $('#reg_number').val($this.data('reg_number'));

            var modalMan = $('.js-man-invalid-modal');
            modalMan.find('.js-blank').text(blank);
            modalMan.find('.js-name').text(name);
            modalMan.find('.js-reason').val('');
            modalMan.find('.js-accept').off('click');
            modalMan.find('.js-accept').on('click', function (E) {
                var reason = $('.js-reason').val();

                var annul_date = $('#annul_date').val();

                if (reason.length < 10) {
                    alert('������� ������ ���� ������ 10 ��������');
                    return;
                }
                else {
                    if (!confirm('�������� ����������.\n �� �������?')) return;
                }

                $.ajax({
                    type: 'POST',
                    url: './index.php?action=annul_cert',
                    data: {
                        id: manId,
                        reason: reason,
                        annul_date: annul_date
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

<style>
    .modal {
        left: 42%;
        /* top:42%;*/
        width: 750px;
    }

    .modal.fade.in {
        top: 40%;
    }

    .modal-body {
        max-height: inherit;
    }
</style>

<div class="js-man-invalid-need-archive-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>������������ ����������</h3>
    </div>
    <div class="modal-body">
        <p>�������� ������ ���������� ��������� � �����<br>
            <strong>�������� �����:</strong> <span class="js-hc"></span><br>
            <strong>��������� �����:</strong> <span class="js-univer"></span><br>
            <strong>����� �������� ������:</strong> <span class="js-act-num"></span><br>
            <strong>ID �������� ������:</strong> <span class="js-act-id"></span><br>

        </p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">�������</a>

    </div>
</div>
<div class="js-man-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>������������ ����������</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <p>�� ��������� ������������ ���������� <span class="js-blank"></span>
                � <span class="js-name"></span>
            </p>
            <div class="control-group"><label class="control-label">������� rus</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="surname_rus"></div>
            </div>
            <div class="control-group"><label class="control-label">��� rus</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="name_rus"></div>
            </div>
            <div class="control-group"><label class="control-label">������� lat</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="surname_lat"></div>
            </div>
            <div class="control-group"><label class="control-label">��� lat</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="name_lat"></div>
            </div>
            <div class="control-group"><label class="control-label">����� ������</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="blank_number"></div>
            </div>
            <div class="control-group"><label class="control-label">��������������� �����</label>
                <div class="controls"><input type="text" value="" readonly="readonly" id="reg_number"></div>
            </div>
            <div class="control-group"><label class="control-label">���� ���������</label>
                <div class="controls">


                    <input type="text" name="annul_date" id="annul_date" class="input-large datepicker"
                           value="<?php
                           echo date('d.m.Y');
                           ?>">


                    <!--                    <input type="text" value="" readonly="readonly" id="annul_date">-->
                </div>
            </div>
            <div class="control-group"><label class="control-label">������� �������: </label>
                <div class="controls">
                <textarea style="width: 450px" class="js-reason" rows="3"
                          placeholder="��������: �������� �����"></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">��������</a>
        <a class="btn btn-primary js-accept">�����������</a>
    </div>
</div>

<? //var_dump($_SESSION);?>