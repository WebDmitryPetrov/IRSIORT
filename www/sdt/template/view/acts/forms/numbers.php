
<h3>
	Дата тестирования:
	<?php echo $C->date($Act->testing_date);?>
	</h3>
	<h4>
		Университет:
		<?php echo $Act->getUniversity();?>
	</h4>
	<h4>
		Договор:
		<?php echo $Act->getUniversityDogovor();?>
	</h4>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
		class="form-horizontal marks">

		<legend> Заполните номера сертификатов </legend>


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
					<td><strong>Номер документа</strong></td>
					<td>Печать</td>
					</td>
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

					<td><div class="input-append">
							<input type="text" name="user[<?php echo $Man->id; ?>][passport]"
								class="input-medium" value="<?php echo $Man->passport; ?>"
								placeholder="Паспорт"><span class="add-on"><a href="#"
								class="passport_upload custom-icon-upload"
								data-id="<?php echo $Man->id; ?>"></a> </span>

						</div>
							<?php if ($Man->getFilePassport()):?>
							<a href="<?php echo $Man->getFilePassport()->getDownloadUrl()?>" class="btn  btn-small btn-success"><span class=" custom-icon-download icon-white"></span></a>
							<?php endif;?>
					</td>
					<td><?php echo $Man->getTest()->getLevel()->caption; ?>
					</td>
					<td><span class="<?php echo $Man->document;?> ">
                        <?php echo $Man->document=="certificate"?'Сертификат':'Справка' ?>
                        </span>
					</td>
					<td><input type="text"
						name="user[<?php echo $Man->id; ?>][document_nomer]"
						class="input-medium" value="<?php echo $Man->document_nomer; ?>"
						placeholder="номер документа">
					</td>
					<td><?php if ( $Man->document_nomer && $Man->document=="certificate"):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_man_print_pril_cert&id=<?php echo $Man->id; ?>">Приложение</a>

						<?php endif;?> <?php if($Man->document_nomer && $Man->document!='certificate' && $Man->getTest()->getLevel()->id==8):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_grazhdan&id=<?php echo $Man->id; ?>">Гражданство</a>
						<?php endif;?> <?php if($Man->document_nomer && $Man->document!='certificate' && $Man->getTest()->getLevel()->id!=8):?>
						<a target="_blank" class="btn btn-info btn-small"
						href="index.php?action=act_rki&id=<?php echo $Man->id; ?>">РКИ</a>
						<?php endif;?>
						<div><a href="#" class="btn soprovod_upload btn-small" data-id="<?php echo $Man->id; ?>"><span class="custom-icon-upload"></span></a>
						<?php if ($Man->getSoprovodPassport()):?>
							<a href="<?php echo $Man->getSoprovodPassport()->getDownloadUrl()?>" class="btn btn-success btn-small"><span class=" custom-icon-download icon-white"></span></a>
							<?php endif;?></div>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>


		<div class="form-actions">
			<button class="btn btn-success" type="submit">Сохранить</button>

		</div>
	</form>

	<div class="modal hide fade" id="passport_upload" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form method="post" enctype="multipart/form-data"
			action="index.php?action=man_passport_upload" class="form-inline">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Загрузить отсканированный паспорт и миграционную карту в одном файле</h3>
			</div>
			<div class="modal-body">


				<legend>Выберите файл</legend>

				<input type="hidden" value="" name="man_id" class="man_id"> <input
					type="file" name="file" class="input-xlarge">

			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
				<button class="btn btn-primary save" type="submit">Загрузить</button>
			</div>
		</form>
	</div>
	<script>
	$(function(){
		  
$('.passport_upload').on('click',function(e){
	e.preventDefault();
	//alert($(this).data('id'));
	$('#passport_upload').find('.man_id').val($(this).data('id'));
		$('#passport_upload').modal();
	});
	});

</script>
	
	<div class="modal hide fade" id="soprovod_upload" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form method="post" enctype="multipart/form-data"
			action="index.php?action=man_soprovod_upload" class="form-inline">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Загрузить документ</h3>
			</div>
			<div class="modal-body">


				<legend>Выберите файл</legend>

				<input type="hidden" value="" name="man_id" class="man_id"> <input
					type="file" name="file" class="input-xlarge">

			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
				<button class="btn btn-primary save" type="submit">Загрузить</button>
			</div>
		</form>
	</div>
	<script>
	$(function(){
		  
$('.soprovod_upload').on('click',function(e){
	e.preventDefault();
	//alert($(this).data('id'));
	$('#soprovod_upload').find('.man_id').val($(this).data('id'));
		$('#soprovod_upload').modal();
	});
	});

</script>