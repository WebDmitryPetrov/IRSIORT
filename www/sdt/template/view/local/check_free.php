<form class="form-search" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <div class="input-append">
        <input name="note" type="text" class="input-xxlarge search-query"
               placeholder="������� ����� �������"
               value="<?php echo $note; ?>">
        <button type="submit" class="btn">�����</button>
    </div>


</form>
<?php

$text = '';
if ($man && $old_man) {

    if ($man->is_free && $test_interval) $text = '�������� ���������� ��������� �� ������ ������������ "' . $old_man->getTest()->getLevel() . '"';

    else $text='���������� ��������� ����������!';

    /*else if ($man->is_free && !$test_interval) $text = '�������� ���������� ���������, �� �������� ������� ' . ReExamCheck::DAYS_BEFORE . ' �������� ��������� ����� �������� - �������� ��������� �����';
    else {

        if (in_array($old_man->getTest()->getLevel()->id, Reexam_config::getTestLevels())) {
            $text = '���������� ��������� ����������, ��� ��� ������� ����������� � ' . $man->number . ' ���';
        } else $text = '������� �� ������������. ���������� ��������� �������� ������ ��� ������� ��';
    }*/


} else if ($note && empty($old_man)) {
    $text = '������� "' . $note . '" �� �������!';
}
echo '<h1>' . $text . '</h1>';
