<?php
/** @var University $object */
?>
<script>
    $(function(){
        $('#select_all_univers').on('click',function(){
            var $this=$(this);
            if($this.prop('checked')){
                $('.univer').prop('checked',true);
            }
            else{
                $('.univer').prop('checked',false);
            }
        });
    });
</script>
<table class="table table-bordered  table-striped">

    <tr>
        <th>������������</th>
        <td><?=$userName?></td>
    </tr>



	<tr>
		<th>������������
		</th>
        <td>

            <form method="post">
                <label><input type="checkbox" id="select_all_univers">������� ���</label>
                <hr>
            <?php

            foreach ($univers as $user):
        ?>
            <label>

                <input class="univer" type="checkbox" name="univers[]" value="<?=$user['id']?>"
                <? if ($user['checked']):?>
                checked="checked"
                <? endif; ?>
                > <?=$user['caption']?>
            </label>
        <?php
endforeach;
            ?>
                <input type="submit" class="btn btn-success" name="submit" value="���������">
            </form>
		</td>
	</tr>


</table>
