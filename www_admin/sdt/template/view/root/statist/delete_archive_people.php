<? if ($run): ?>
    <p>���� �������: <?= $founded ?></p>
    <p>�������: <?= $affected ?></p>
<? endif ?>


<form method="post" action="/sdt/?action=deleteArchivePeople">
    � ������ ������� <?= $people_count ?> �����, ��� �������
    <input type="hidden" name="remove" value="1">
    <button type="submit">�������� ��� ��������</button>
</form>


<? if ($ids): ?>
    <div class="well">
        <h2>id ���������</h2>
        <?= implode(', ', $ids); ?>
    </div>
<? endif ?>
