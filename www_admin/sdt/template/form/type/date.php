<?php
$value = $object->$field;
//var_dump($value);
if ($value && $value != '0000-00-00') {
    $print = $C->date($value);
}
else{
    $print='';
}
?>

<div class="input-prepend date datepicker" id="div_<?php echo $field; ?>"
     data-date="<?php echo $print; ?>"
    >
    <span class="add-on"><i class="icon-th"></i> </span> <input
        class="input-xxlarge" name="<?php echo $field; ?>" id="<?php echo $field; ?>"
        readonly="readonly" size="16" type="text"
        value="<?php echo $print; ?>">
</div>