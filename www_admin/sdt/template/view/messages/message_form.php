<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 20.03.15
 * Time: 12:27
 * To change this template use File | Settings | File Templates.
 */

if (!empty($data))
{
    $text=$data['text'];
    $user_type=$data['user_type'];
    $hc_id=$data['hc_id'];
}
else
{
    $text = $user_type = $hc_id = null;
}


if (!empty($message_errors))
{
    echo '<h3 style="color:red">'.implode('<br>',$message_errors).'</h3>';
}

?>
    <style>
        #message_sending_form hr
        {
            margin:0 0 5px;
        }
        #message_sending_form span
        {
            font-style: italic;
            color:gray;
        }
        #message_sending_form select
        {
            border: 1px solid #bbb;
        }

    </style>

    <script src="../ckeditor/ckeditor.js"></script>


    <div id="message_sending_form">
        <h4>Отправка сообщений</h4>
        <form method="post" name="asd">

            <span>Текст сообщения:</span><br>

<!--            <textarea name="message" placeholder="Сообщение..." style="width: 470px; height:100px">--><?//=$text?><!--</textarea>-->
            <textarea name="message" placeholder="Сообщение..." style="width: 470px; height:100px" id="message"><?=$text?></textarea>

            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'message' );
            </script>

            <input type="hidden" name="sender_id" value="<?=$_SESSION['u_id'];?>">

            <hr>
            <span>Получатели:</span><br>
            <select name="user_type">
                <option value="0">Локальные центры</option>
                <option value="6" <?=($user_type == 6)? 'selected="selected"': ''?>>Супервизоры</option>
                <option value="5" <?=($user_type == 5)? 'selected="selected"': ''?>>Администраторы</option>
            </select>


            <hr>
            <span>Получатели только конкретного головного центра:</span><br>
            <select name="hc_id">
                <option value="0">Все головные центры</option>
                <option value="-1" <?=($hc_id==-1)?'selected="selected"':''?>>Всем головным центрам РУДН</option>
                <?

                //$HC=getAllHeadCenters();
                foreach ($HC as $key => $item)
                {
                    if (!empty($item->short_name))
                    {
                        $hc_name=$item->short_name;
                    }
                    else
                    {
                        $hc_name=$item->name;
                    }

                    if ($item->id == $hc_id) $selected='selected="selected"'; else $selected='';
                    echo '<option value="'.$item->id.'" '.$selected.'>'.$hc_name.'</option>';
                }
                ?>

            </select>

            <hr>
            <input type="submit" value="Отправить" class="btn btn-success">


        </form>
    </div>
    <br>
<?