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
<div class="text-info">Добавлено: <?= count($added) ?></div>
<?if (count($added)):?>
    <button id="showCerts" class="btn btn-default">Отобразить список</button>
<? endif?>


    <div class="cert-container good" style="margin-left: 15px; margin-bottom: 30px; display: none">
        <div class="row">
            <ol>

                <?
                $i = 0;
                foreach ($added as $item):?>



                    <li><?= $item ?></li>

                    <? $i++; endforeach ?>
            </ol>
        </div>
    </div>


<div class="text-info">Были заняты: <?= count($error) ?></div>
<?if (count($error)):?>
    <button id="showBusy" class="btn btn-default">Отобразить список</button>
<? endif?>
<div class="cert-container busy" style="margin-left: 15px; margin-bottom: 30px; display: none">
    <div class="row">
        <ol>

            <?
            $i = 0;
            foreach ($error as $item):?>



                <li><?= $item ?></li>
                <? $i++; endforeach ?>
        </ol>
    </div>
</div>
