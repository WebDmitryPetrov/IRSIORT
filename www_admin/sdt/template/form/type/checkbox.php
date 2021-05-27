<script>
    function checking (a)
    {
        if ($(a).attr("checked")=="checked") $(a).val("1");
        else $(a).val("0");

    }
</script>
<? $check_box_value=htmlspecialchars($object->$field,ENT_QUOTES);
if ($check_box_value==1) $che= 'checked="checked"'; else $che='';
?>
<input type="hidden" name="<?php echo $field; ?>" value="0">
<input class="input-xxlarge" type="checkbox" name="<?php echo $field; ?>" <?php echo $che; ?> id="<?php echo $field; ?>" value="<?php echo $check_box_value;?>" onchange="checking(this)">