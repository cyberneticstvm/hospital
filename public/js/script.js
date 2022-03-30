$(function() {
    "use strict";
    var branch = $("#branch_selector").val();
    if(!branch){
        $(".branchSelector").modal({backdrop: 'static'});
        $(".branchSelector").modal({keyboard: false});
        $(".branchSelector").modal("show");
    }
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

    $(document).on('click', '.btn-ajax-submit', function(e){
        e.preventDefault();
        var frm = $(this).closest('form');
        var ddl = frm.find(".ddl").val();
        var form_data = frm.serialize();
        var url = $(this).closest('form').attr('action');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        $.ajax({
            type: 'POST',
            url: url + ddl,
            data: form_data,
            success: function(data){
                $.ajax({
                    type: 'GET',
                    url: '/symptom/index/'+ddl
                }).then(function (data){
                    var xdata = $.map(data, function(obj){
                        obj.text = obj.name || obj.id;  
                        return obj;
                    });
                    $("#"+ddl).select2({data:xdata});
                });
                $('.message').html(data.success);
                $(frm)[0].reset();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log(XMLHttpRequest);
            },
            beforeSend: function(){
                $(".btn-ajax-submit").html("<span class='spinner-grow spinner-grow-sm' role='status' aria-hidden='true'></span>&nbsp;Loading...");
            },
            complete: function(){
                $(".btn-ajax-submit").html("Save");
            }
        });
    });

    $(".medicineAdvise").click(function(){    
        $(".medicineAdviseContainer").append("<div class='row mb-3'><div class='col-sm-4'><select class='form-control form-control-md select2 selMedicine' data-placeholder='Select' name='medicine_id[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-4'><input type='text' name='dosage[]' class='form-control form-control-md' placeholder='Eg: Daily 3 Drops'/></div><div class='col-sm-3'><select class='form-control form-control-md select2 selDosage' data-placeholder='Select' name='dosage1[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></a></div></div>");        
        $('.selMedicine, .selDosage').select2();
        bindDDL('medicine', 'selMedicine'); bindDDL('dosage', 'selDosage');
    });

    $(".medicineRow").click(function(){
        $(".tblMedicine tbody").append("<tr><td><select class='form-control form-control-md select2 selMedicine' data-placeholder='Select' name='product_id[]' required='required'></select></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='qty[]' required='required' /></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='price[]' required='required' /></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='total[]' required='required' /></td><td><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></a></td></tr>");
        $('.selMedicine').select2();
        bindDDL('medicine', 'selMedicine');
    });
});

function bindDDL(type, ddl){
    $.ajax({
        type: 'GET',
        url: '/symptom/index/'+type
    }).then(function (data){
        xdata = $.map(data, function(obj){
            obj.text = obj.name || obj.id;  
            return obj;
        });
        $('.'+ddl).select2({data:xdata});
    });
}