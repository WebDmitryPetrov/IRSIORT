<form class="form-search" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <div class="input-append">
        <input name="note" type="text" class="input-xxlarge search-query"
               placeholder="Введите номер спарвки"
               value="<?php echo $note; ?>">
        <button type="submit" class="btn">Найти</button>
    </div>


</form>
<?php

$text = '';
if ($man && $old_man) {

    if ($man->is_free && $test_interval) $text = 'Возможна бесплатная пересдача по уровню тестирования "' . $old_man->getTest()->getLevel() . '"';

    else $text='Бесплатная пересдача невозможна!';

    /*else if ($man->is_free && !$test_interval) $text = 'Возможна бесплатная пересдача, но нарушено условие ' . ReExamCheck::DAYS_BEFORE . ' дневного интервала после экзамена - оформить пересдачу позже';
    else {

        if (in_array($old_man->getTest()->getLevel()->id, Reexam_config::getTestLevels())) {
            $text = 'Бесплатная пересдача невозможна, так как экзамен пересдается в ' . $man->number . ' раз';
        } else $text = 'Уровень не соотвествует. Бесплатная пересдача возможна только для уровней ИР';
    }*/


} else if ($note && empty($old_man)) {
    $text = 'Справка "' . $note . '" не найдена!';
}
echo '<h1>' . $text . '</h1>';
