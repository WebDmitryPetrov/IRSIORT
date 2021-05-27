<script>
    $(function () {
        var showHideUniversityPrintDocument = function () {
            var v = $('#type_id').val();
            var $control = $('.control-group-document_type');
            $control.toggle(v == 2);
            if (v == 1) {
                $control.find('[type=radio]').prop('checked', false);
            }
        };

        $('#type_id').on('change', function () {
            showHideUniversityPrintDocument();
        });
        showHideUniversityPrintDocument();
    });
</script>