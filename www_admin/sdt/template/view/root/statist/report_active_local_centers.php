<h1><?=  $caption ?></h1>

<form action="" method="POST">

    <label>�� :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="from"

                readonly="readonly" size="16" type="text"
                value="<?= $from ?>">
        </div>
    </label> <label>�� :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="to"

                readonly="readonly" size="16" type="text"
                value="<?= $to ?>">
        </div>
    </label>


    <input type="submit" value="�������������">
</form>

<?if ($search):?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>�������� �����</th>
            <th>��������</th>
            <th>��������</th>

        </tr>
        </thead>
        <tbody>
        <?foreach($array as $row):?>
        <tr>
            <td><?=$row['caption']?></td>
            <td><?=$row['active']?></td>
            <td><?=$row['deleted']?></td>
        </tr>
        <?endforeach;?>
        </tbody>
    </table>
<?endif?>
