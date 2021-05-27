<?php
$current_level_id = $Act->level_id;
if (!$current_level_id) {
    $current_level = current($Levels);
    $current_level_id = $current_level->id;
}


?>
<style>
    .form-horizontal .control-label {
        float: left;
        padding-right: 10px;
        padding-top: 5px;
        text-align: right;
        width: 230px;
    }
</style>
<div style="color:red; background-color: #0000CD; padding: 5px;">
    <strong>����� ��������� ������������ � ������ ���������� �� ��������.<br>
        ���������� ������ ������� ������������ � �������� ���������� ���������������� � ������!</strong>
</div>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
      class="form-horizontal">
    <?php if (!empty($Legend)): ?>
        <legend>
            <?php echo $Legend; ?>
        </legend>
    <?php endif; ?>
    <div class="control-group">
        <div class="controls">
            <span style="color:red; font-weight: bold; font-size: 18px">���������� ����� � �������� ������ ������ ���� ������ 60!</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="level_id">������� ������������
        </label>

        <div class="controls">
            <select id="level_id" name="level_id"
                    class="input-xxlarge">
                <?php foreach ($Levels as $level): ?>
                    <option <?php if ($current_level_id == $level->id): ?>
                        selected="selected" <?php endif; ?>
                        value="<?php echo $level->id; ?>">
                        <?php echo $level; ?>

                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <fieldset>
        <legend><strong>C������ ������ ���</strong></legend>
        <div class="control-group">
            <label class="control-label" for="people_first">���������� �����</label>

            <div class="controls">
                <input class="input-xxlarge" type="text" name="people_first"
                       id="people_first" value="0">
            </div>
        </div>
        <!--
     <div class="control-group">
             <label class="control-label" for="money_first">����� ��������� ������������</label>

         <div class="controls">

                 <input class="input-xxlarge" type="text"
                        name="money_first" id="money_first"
                        value="0">


             </div>
         </div>
         -->
    </fieldset>
    <br>
    <fieldset>
        <legend><strong>��������� <span
                    style="color:red">(��� ������, ����� "������� ��� ����������� ����������")</span></strong></legend>
        <div class="control-group">
            <label class="control-label" for="people_retry">��������� ���������� ����������� �����</label>

            <div class="controls">
                <input class="input-xxlarge" type="text" name="people_retry"
                       id="people_retry" value="0">
            </div>
        </div>
        <? if ($Type != 1): ?>
        <script>
            $(function () {
                var $peopleSubtestRetry = $('#people_subtest_retry');
                var $peopleSubtest2Retry = $('#people_subtest_2_retry');
                var $peopleSubtestAllRetry = $('#people_subtest_all_retry');
                var isGCFreeExam = <?=json_encode(in_array(CURRENT_HEAD_CENTER,Reexam_config::getHeadCenters()))?>;
                var freeExamLevels = <?=json_encode(Reexam_config::getTestLevels())?>;
                var $peopleRetry = $('#people_retry');
                var $levelId = $('#level_id');


                $levelId.on('change',function(){
                    var isShow = isGCFreeExam && _.contains(freeExamLevels, parseInt($(this).val()));
//                    console.log(isShow);
                    $peopleSubtestAllRetry.closest('.control-group').toggle(isShow);
                    if(!isShow){
                        $peopleSubtestAllRetry.val(0);
                        $peopleSubtestAllRetry.trigger('change');
                    }
                });
                $peopleRetry.prop('readonly', true);
                $('.js-retry-change-people').on('keyup change', function (E) {
//                    if (parseInt($(this).val())==NaN ) return false;

                    var retry1 = parseInt($peopleSubtestRetry.val());

                    var retry2 = parseInt($peopleSubtest2Retry.val());

                    var retry_all = parseInt($peopleSubtestAllRetry.val());

//                    alert(retry1);
                    if (isNaN(retry1)) {
                        retry1 = 0;
                        $peopleSubtestRetry.val(0);
                    }
                    if (isNaN(retry2)) {
                        retry2 = 0;
                        $peopleSubtest2Retry.val(0);
                    }
                    if (isNaN(retry_all)) {
                        retry_all = 0;
                        $peopleSubtestAllRetry.val(0);
                    }
                    $peopleRetry.val(
                        retry1
                        +
                        retry2
                        +
                        retry_all
                    );
                });

                $levelId.trigger('change');
            });
        </script>



        <div class="control-group">
            <label class="control-label" style="padding-top: 0 " for="people_subtest_retry">���������� �����,
                ����������� 1 ������� (��� ��� ���/���)</label>

            <div class="controls">
                <input class="input-xxlarge js-retry-change-people" type="text" name="people_subtest_retry"
                       id="people_subtest_retry" value="0">


            </div>

        </div>

        <div class="control-group">
            <label class="control-label" style="padding-top: 0 " for="people_subtest_2_retry">���������� �����,
                ����������� 2-� �������� (��� � ���/���)</label>

            <div class="controls">
                <input class="input-xxlarge js-retry-change-people" type="text" name="people_subtest_2_retry"
                       id="people_subtest_2_retry" value="0">

                <!--  <p style="color:red">(��������� ���������� ������������� ��������� �� ���� �����������.
                      <br>
                      ��� ������� ������������ ��������: 1 ���� �� ����.�� ��� 1 ���� �� �������/�����., ���� � ���, �
                      ������)</p>-->
            </div>

        </div>
        <? endif; ?><!---->

        <!--   <div class="control-group">
               <label class="control-label" for="money_retry">����� ��������� �������� ������������</label>

               <div class="controls">

                   <input class="input-xxlarge" type="text"
                          name="money_retry" id="money_retry"
                          value="0">


               </div>
           </div>
           -->
    </fieldset>
    <div class="form-actions">
        <button class="btn btn-success" type="submit">���������</button>

    </div>
</form>
