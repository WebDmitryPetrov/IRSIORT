
    <h2>Управление сертификатами</h2>


    <p><a href="./?action=certificate_add">Добавить новые</a></p>

    <p>
        <strong>Просмотреть доступные</strong>
    </p>
    <ul>
        <?php
        foreach ($types as $type):
            ?>
            <li><a href="./?action=certificate_list&type=<?= $type->id ?>"><?= $type->caption ?></a></li>
        <? endforeach ?>
    </ul>


    <p><a href="./?action=used_certificates_list" style="color: red">Получить список использованных бланков</a></p>
