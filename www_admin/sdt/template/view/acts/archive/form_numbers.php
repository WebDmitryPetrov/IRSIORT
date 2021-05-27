<?php
/** @var Act $Act */
$signings=ActSignings::get4Certificate();
$head_id=$Act->getUniversity()->head_id;
?>
<h3>
	Дата тестирования:
	<?php echo $C->date($Act->testing_date);?>
	</h3>
	<h4>
		Университет:
		<?php echo $Act->getUniversity().'<br>('.HeadCenter::getNameByActID($Act->id).')'?>
	</h4>
	<h4>
		Договор:
		<?php echo $Act->getUniversityDogovor();?>
	</h4>



		<h4>Номера сертификатов </h4>


		<table
			class="table table-bordered  table-striped table-condensed ">
			<thead>
				<tr>
					<td><strong>Фамилия</strong><br /> русскими <br /> латинскими</td>
					<td><strong>Имя</strong><br /> русскими<br /> латинскими</td>
					<td><strong>Паспорт 
					
					</td>
					<td><strong>Уровень тестирования</strong></td>
					<td><strong>Тип документа</strong></td>
                    <?if ($Act->test_level_type_id==2):?>
                        <td>Регистрационный номер</td>
                    <?endif?>
					<td><strong>Номер бланка<br>Дата печати</strong></td>
					<td>Печать</td>

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
					<td><?php echo $Man->document=="certificate"?'Сертификат':'Справка' ?>
					</td>

                    <?if ($Act->test_level_type_id==2):?>
                    <td><?php echo $Man->document_nomer ?>                    </td>
                    <?endif?>
<td>
                        <?= $Man->blank_number ?><br>
                        <? if ($Man->blank_date != '0000-00-00'):?>
<?=$C->date($Man->blank_date)?>
<?endif?>


					</td>
					<td><?php if (  $Man->blank_number
                        && $Man->blank_date
                        && $Man->blank_date != '0000-00-00' && $Man->document=="certificate"):?>
                            <div class="btn-group">
                                <a  class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
                                    href="">Сертификат  <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php foreach($signings as $sign):?>
                                        <li><a target="_blank"
                                               href="index.php?action=print_certificate&type=<?=$sign->id?>&id=<?php echo $Man->id; ?>&h_id=<?php echo $head_id; ?>"><?=$sign->caption?></a></li>
                                    <?php endforeach; ?>
                                    <ul>
                            </div>
                            <a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_man_print_pril_cert&id=<?php echo $Man->id; ?>&h_id=<?php echo $head_id; ?>">Приложение</a>
						
						<?php endif;?> 
						
						
						<?php /*if($Man->document_nomer && $Man->document!='certificate' && $Man->getTest()->getLevel()->id==8):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_grazhdan&id=<?php echo $Man->id; ?>">Гражданство</a>
						<?php endif;?> <?php if($Man->document_nomer && $Man->document!='certificate' && $Man->getTest()->getLevel()->id!=8):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_rki&id=<?php echo $Man->id; ?>">РКИ</a>
						<?php endif;*/?>
						
						<?php if ( $Man->document!='certificate'):?>
                            <div class="btn-group">
                                <a  class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
                                    href="">РКИ<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php foreach($signings as $sign):?>
                                        <li><a target="_blank"
                                               href="index.php?action=act_rki&type=<?=$sign->id?>&id=<?php echo $Man->id; ?>&h_id=<?php echo $head_id; ?>"><?=$sign->caption?></a></li>
                                    <?php endforeach; ?>
                                    <ul>
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


