<?php
/** @var Act $act */
$act = current($list);
if (empty($act)) {
    echo '<h2>Документов не найдено</h2>';

    return;
}
$university = $act->getUniversity();

$listing = ' onchange="filter()"><option value=2>Все</option><option value=1>Да</option><option value=0>Нет</option></select>';
?>
    <style>
        .sel {
            overflow: hidden;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            width: 60px;
            height: 22px;
            background: url('content/img/filter_s.png') no-repeat scroll 43px 6px rgba(0, 0, 0, 0)
        }

        .sel select {
            font-size: 12px;
            height: 22px;
            padding: 0;
            width: 80px;
            background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        }


    </style>


    <h1>Список тестовых сессий на выдачу дубликата центра - <?php
        echo $university; echo $caption ?><!-- - по --><?/*= $type */?></h1>
<? if ( $university->parent_id): ?>
    <h2>Партнёр: <?=  $university->getParent()->name ?></h2>
<? endif ?>
    <h3>Всего <?= count($list) ?></h3>

    <table class="table table-bordered  table-striped">
        <thead>
        <tr>
<!--            <th valign="top">
                <nobr>№</nobr>
            </th>-->
            <th valign="top">Дата создания Акта</th>
<!--            <th valign="top">Дата тестирования</th>-->
<!--            <th valign="top">Дата получения</th>-->
            <th valign="top">Cчет<br>номер/дата<br>сумма</th>
<!--            <th valign="top">Введены номера документов
                <div class="sel"><select id="f_blanks"<?/*= $listing; */?></div>
            </th>--> <? /**/ ?>
            <th valign="top">Выставлен счет
                <div class="sel"><select id="f_invoice"<?= $listing; ?></div>
            </th> <? /**/ ?>
           <!-- <th valign="top">Оплачено
                <div class="sel"><select id="f_paid"<?/*= $listing; */?></div>
            </th>-->


            <? /*<th valign="top">Комментарий</th>*/ ?>
            <th valign="top">&nbsp;</th>
        </tr>
        </thead>
        <tbody>


        <?php



        foreach ($list as $item):
            /** @var Act $item */
            ?>

            <?php echo $this->import('dubl/act_table_view', array('item' => $item)); ?>

        <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        function filter() {

            $('.js-act-row').show();

            show_hide('blanks', $("#f_blanks").val());
            show_hide('invoice', $("#f_invoice").val());
            show_hide('paid', $("#f_paid").val());

        }
        function show_hide(name, value) {
            if (value != 2) {
                if (value == 1) {
                    $('.' + name + '_off').hide();
                }
                if (value == 0) {
                    $('.' + name + '_on').hide();
                }
            }
        }
    </script>
<?php
echo $this->import('dubl/act_table_popups', array('signings' => $signings,'university'=>$university));