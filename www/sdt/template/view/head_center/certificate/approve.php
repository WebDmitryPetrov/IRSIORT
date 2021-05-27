<script type="text/javascript">
    (function ($) {
        $(function () {
            $('.cert-container ol').autocolumnlist({
                columns: 4,
                classname: 'span3',
                min: 3
            });

            $('#showCerts').on('click', function (E) {
                    E.preventDefault();
                    $('.cert-container.good').slideToggle();
                }
            );
            $('#showBusy').on('click', function (E) {
                    E.preventDefault();
                    $('.cert-container.busy').slideToggle();
                }
            );
        })
    })(jQuery)
</script>
<h2>Добавление сертификатов для "<?=$type->caption?>"</h2>
<div class="text-info">К добавлению: <?= count($items) ?></div>
<?if (count($items)):?>
<button id="showCerts" class="btn btn-default">Отобразить список</button>
<? endif?>
<form method="post" action="./?action=certificate_submit">
    <input type="hidden" name="type" value="<?=$type->id?>">
    <input type="hidden" name="blank_type" value="<?=$blank_type?>">
    <div class="cert-container good" style="margin-left: 15px; margin-bottom: 30px; display: none">
        <div class="row">
            <ol>

                <?
                $i = 0;
                foreach ($items as $item):?>



                    <li><?= $item['number'] ?></li>
                    <input type="hidden" name="item[]" value="<?= $item['number'] ?>">
                    <? $i++; endforeach ?>
            </ol>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Подтвердить добавление</button>
</form>
<div class="text-info">Занятые: <?= count($busy) ?></div>
<?if (count($busy)):?>
<button id="showBusy" class="btn btn-default">Отобразить список</button>
<? endif?>
<div class="cert-container busy" style="margin-left: 15px; margin-bottom: 30px; display: none">
    <div class="row">
        <ol>

            <?
            $i = 0;
            foreach ($busy as $item):?>



                <li><?= $item['number'] ?></li>
                <? $i++; endforeach ?>
        </ol>
    </div>
</div>
