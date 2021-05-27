<? if ($run): ?>
    <p>Было найдено: <?= $founded ?></p>
    <p>Удалено: <?= $affected ?></p>
<? endif ?>


<form method="post" action="/sdt/?action=deleteArchivePeople">
    В архиве найдено <?= $people_count ?> людей, без бланков
    <input type="hidden" name="remove" value="1">
    <button type="submit">Пометить как удалённые</button>
</form>


<? if ($ids): ?>
    <div class="well">
        <h2>id удаленных</h2>
        <?= implode(', ', $ids); ?>
    </div>
<? endif ?>
