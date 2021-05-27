<a class="btn btn-info" href="?action=federal_dc">Назад</a>

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 25.08.15
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */
echo '<h1>'.$caption.'</h1>';
echo '<form action="" method="post">';
while($res=mysql_fetch_assoc($result))
{

    if (!empty($res['region_id']))
    {
        $checked='checked="checked"';
    }
    else
    {
        $checked='';
    }

    echo '<input type="checkbox" value="'.$res['id'].'" name="regions[]" '.$checked.'>'.$res['caption'].'<br>';

}
echo '<input type="hidden" value="'.$id.'" name="DC">
        <input type="submit" value="Сохранить">
        </form>
        ';