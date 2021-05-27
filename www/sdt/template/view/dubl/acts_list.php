<?php
/** @var Act $act */
$act = current($list);
if (empty($act)) {
    echo '<h2>���������� �� �������</h2>';

    return;
}
$university = $act->getUniversity();

$listing = ' onchange="filter()"><option value=2>���</option><option value=1>��</option><option value=0>���</option></select>';
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


    <h1>������ �������� ������ �� ������ ��������� ������ - <?php
        echo $university; echo $caption ?><!-- - �� --><?/*= $type */?></h1>
<? if ( $university->parent_id): ?>
    <h2>������: <?=  $university->getParent()->name ?></h2>
<? endif ?>
    <h3>����� <?= count($list) ?></h3>

    <table class="table table-bordered  table-striped">
        <thead>
        <tr>
<!--            <th valign="top">
                <nobr>�</nobr>
            </th>-->
            <th valign="top">���� �������� ����</th>
<!--            <th valign="top">���� ������������</th>-->
<!--            <th valign="top">���� ���������</th>-->
            <th valign="top">C���<br>�����/����<br>�����</th>
<!--            <th valign="top">������� ������ ����������
                <div class="sel"><select id="f_blanks"<?/*= $listing; */?></div>
            </th>--> <? /**/ ?>
            <th valign="top">��������� ����
                <div class="sel"><select id="f_invoice"<?= $listing; ?></div>
            </th> <? /**/ ?>
           <!-- <th valign="top">��������
                <div class="sel"><select id="f_paid"<?/*= $listing; */?></div>
            </th>-->


            <? /*<th valign="top">�����������</th>*/ ?>
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