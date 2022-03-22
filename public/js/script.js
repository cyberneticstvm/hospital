$(function() {
    "use strict";
    $('#dataTbl').dataTable({
        responsive: true
    });

    $('.select2').select2();

    $(".search-select").select2({
        allowClear: true
    });

    $(".dtpicker").pickadate({
        format: "dd/mmm/yyyy",
        selectYears: 100,
        selectMonths: true,
        max: true
    });

    $('form').submit(function(){
        $(".btn-submit").attr("disabled", true);
        $(".btn-submit").html("<span class='spinner-grow spinner-grow-sm' role='status' aria-hidden='true'></span>&nbsp;Loading...");
    });
});