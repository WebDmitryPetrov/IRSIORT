<h1>��������� ������ � �������� � ���� ��������� ���������� �� ����</h1>
<form action="" method="POST">

    <label>���� �������� :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="date"

                    readonly="readonly" size="16" type="text"
                    value="<?= $date ?>">
        </div>
    </label>
    </label>


    <input type="submit" value="�������������">
</form>
<? if (!empty($search)): ?>
    <h1>��������� ������ � �������� � ���� ��������� ���������� ��
        � <?= $date ?>  </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>������������ �����������</th>
            <th>�����</th>



        </tr>

        </thead>
        <tbody>
        <? foreach ($array as $item): ?>

                <tr>
                    <td><?= $item['name'] ?></td>

                    <td><?= $item['legal_address'] ?></td>

                </tr>

        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>