<?php
$current_univer_id = $Act->university_id;
if ($_SESSION['univer_id']) {
    $current_univer_id = $_SESSION['univer_id'];
}
if (!$current_univer_id) {
    $current_univer = current($Universities);
    $current_univer_id = $current_univer->id;
}

$current_university_dogovor_id = $Act->university_dogovor_id;
if (!$current_university_dogovor_id) {
    $dogovors = Univesity_dogovors::getByUniversity($current_univer_id);
    if (count($dogovors)) {
        $current_dogovor = current($dogovors);
        $current_university_dogovor_id = $current_dogovor->id;
    }
}
?>
<script>
    $(function () {
        var init_dogovor_id = '<?php echo $current_university_dogovor_id; ?>';

    <?php if (!$_SESSION['univer_id']) { ?>
        $('#university_id').on('change', function () {
            //alert($(this).find(':selected').val());
            var univer_id = $(this).find(':selected').val();
            $.getJSON('index.php?action=act_universities_list&id=' + univer_id, function (Response) {
                $('#university_dogovor_id').find('option').remove();

                $.each(Response, function (index, Element) {
                    //dump(Element);
                    var $select = $('<option>' + Element.number + ' ' + Element.date + ' ' + Element.caption + '</option>').val(Element.id);
                    if (Element.id == init_dogovor_id) {
                        $select.attr('selected', 'selected');
                    }
                    $('#university_dogovor_id').append($select);
                });


            });
        });

        $('#university_id').change();
        <?php } ?>

        //$( "#testing_date" ).datepicker( { "dateFormat":'yy-mm-dd'} );

    });
</script>
<a href="/sdt/index.php?action=act_fs_view&id=<?php echo $Act->id ?>" class="btn btn-info">Вернуться в акт</a>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
      class="form-horizontal">
    <?php if (!empty($Legend)): ?>
    <legend>
        <?php echo $Legend; ?>
    </legend>
    <?php endif; ?>
    <div class="control-group">
        <label class="control-label" for="number">Номер</label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="number"
                   id="number"
                   value="<?php  echo $Act->number; ?>">


        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="university_id">ВУЗ </label>

        <div class="controls">
            <select id="university_id" name="university_id" class="input-xxlarge"
                <?php if ($_SESSION['univer_id']) { ?>readonly=readonly<?php } ?>
                    >
                <?php  foreach ($Universities as $university): ?>
                <?php if (!$_SESSION['univer_id']) { ?>
                    <option <?php if ($current_univer_id == $university->id): ?>
                            selected="selected" <?php endif;?>
                            value="<?php echo $university->id;?>">
                        <?php echo $university->name;?>
                    </option>
                    <?php
                }
                else {
                    ?>
                    <?php if ($current_univer_id == $university->id): ?>
                        <option
                                selected="selected"
                                value="<?php echo $university->id;?>">
                            <?php echo $university->name;?>
                        </option>
                        <?php endif; ?>
                    <?php } ?>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="university_dogovor_id">Договор ВУЗа
        </label>

        <div class="controls">
            <select id="university_dogovor_id" name="university_dogovor_id"
                    class="input-xxlarge">
                <?php  foreach ($University_dogovors as $university): ?>
                <?php if (($_SESSION['univer_id'] && $university->university_id == $current_univer_id)
                    || (!$_SESSION['univer_id'])
                ):?>
                    <option <?php if ($current_university_dogovor_id == $university->id): ?>
                            selected="selected" <?php endif;?>
                            class="univer_<?php echo $university->university_id;?>"
                            value="<?php echo $university->id;?>">
                        <?php echo $university->number;?>
                        <?php echo $C->date($university->date);?>
                        <?php echo $university->caption;?>
                    </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="testing_date">Дата тестирования </label>

        <div class="controls">

            <div class="input-prepend date datepicker"
                 data-date="<?php echo $C->date($Act->testing_date ? $Act->testing_date : date('Y-m-d'))?>"
               >
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-xxlarge" name="testing_date" id="testing_date"
                    readonly="readonly" size="16" type="text"
                    value="<?php  echo $C->date($Act->testing_date ? $Act->testing_date : date('Y-m-d'))?>">
            </div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="official">Должностное лицо, утверждающее акт</label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="official"
                   placeholder="Проректор Сидоров И.П."
                   id="official"
                   value="<?php  echo $Act->official; ?>">


        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="responsible">Ответственный за проведение тестрования </label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="responsible"
                   id="responsible"
                   value="<?php  echo $Act->responsible; ?>">


        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comment">Комментарий</label>

        <div class="controls">
            <textarea class="input-xxlarge" name="comment" id="comment"><?php  echo $Act->comment; ?></textarea>
        </div>
    </div>


    <div class="form-actions">
        <button class="btn btn-success" type="submit">Сохранить</button>

    </div>
</form>
