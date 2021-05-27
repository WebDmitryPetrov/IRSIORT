<?php
$signings=ActSignings::get4Certificate();
$signingsVidacha = ActSignings::get4VidachaCert();

?>
<h3>
	���� ������������:
	<?php echo $C->date($Act->testing_date);?>
	</h3>
	<h4>
		��������� �����:
		<?php echo $Act->getUniversity();?>
	</h4>
	<h4>
		�������:
		<?php echo $Act->getUniversityDogovor();?>
	</h4>



		<h4>������ ������������ </h4>


		<table
			class="table table-bordered  table-striped table-condensed ">
			<thead>
				<tr>
					<td><strong>�������</strong><br /> �������� <br /> ����������</td>
					<td><strong>���</strong><br /> ��������<br /> ����������</td>
					<td><strong>�������
					
					</td>
					<td><strong>������� ������������</strong></td>
					<td><strong>��� ���������</strong></td>
					<td><strong>����� ������<br>���� ������</strong></td>
					<td>������</td>

				</tr>

			</thead>
			<tbody>
				<?php foreach($Act->getPeople() as $Man):?>
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
					<td><?php echo $Man->document=="certificate"?'����������':'�������' ?>
					</td>
					<td>

						<? $blank_type=BlankType::getBlankType($Act->test_level_type_id,$Man->blank_number);
						if (empty($blank_type->default) && !empty($blank_type->name)) echo '<span style="color:red">'.$blank_type->name.'</span><br>';
						?>

                        <?= $Man->blank_number ?><br>
                        <? if ($Man->blank_date != '0000-00-00' && !empty($Man->blank_date)):?>
<?=$C->date($Man->blank_date)?>
<?endif?>
					</td>
					<td>
                        <? if ($Man->is_anull()): ?>
                            <p class="text-error"><strong>�����������</strong><br><?= $C->date($Man->getAnull()->date_annul)?><br><strong>�������:</strong><br><?= $Man->getAnull()->reason ?></p>
                        <? endif ?>
                        <?php if (  $Man->blank_number
                        && $Man->blank_date
                        && $Man->blank_date != '0000-00-00' && $Man->document=="certificate"):?>
                            <div class="btn-group">
                                <a  class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
                                    href="">����������  <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php foreach($signings as $sign):?>
                                        <li><a target="_blank"
                                               href="index.php?action=print_certificate&type=<?=$sign->id?>&id=<?php echo $Man->id; ?>"><?=$sign->caption?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>

<div></div>
                            <div class="btn-group">
                                <a  class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
                                    href="">����������  <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php foreach($signingsVidacha as $sign):?>
                                        <li><a target="_blank"
                                               href="index.php?action=act_man_print_pril_cert&type=<?=$sign->id?>&id=<?php echo $Man->id; ?>"><?=$sign->caption?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>

                            <div></div>


						
						<?php endif;?> 
						
						
						<?php /*if($Man->document_nomer && $Man->document!='certificate' && $Man->getTest()->getLevel()->id==8):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_grazhdan&id=<?php echo $Man->id; ?>">�����������</a>
						<?php endif;?> <?php if($Man->document_nomer && $Man->document!='certificate' && $Man->getTest()->getLevel()->id!=8):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_rki&id=<?php echo $Man->id; ?>">���</a>
						<?php endif;*/?>
						
						<?php if (  $Man->document!='certificate'):?>

                            <div class="btn-group">
                                <a  class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
                                    href="">���  <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php foreach($signingsVidacha as $sign):?>
                                        <li><a target="_blank"
                                               href="index.php?action=act_rki&type=<?=$sign->id?>&id=<?php echo $Man->id; ?>"><?=$sign->caption?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>

						<?php endif;?>
						
						<div>
						<?php if ($Man->getSoprovodPassport()):?>
							<a href="<?php echo $Man->getSoprovodPassport()->getDownloadUrl()?>" class="btn btn-success btn-small"><span class=" custom-icon-download"></span></a>
							<?php endif;?></div>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>


