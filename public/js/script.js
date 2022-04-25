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

    /*$(".dtpicker").pickadate({
        format: "dd/mmm/yyyy",
        selectYears: 100,
        selectMonths: true,
        //max: true
    });*/
    $("body").delegate('.dtpicker', "focusin", function(){
		$(this).pickadate({
            format: "dd/mmm/yyyy",
            selectYears: 100,
            selectMonths: true,
        });
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

    $(".spectacle .ipd").change(function(){
        var ipd = $(this).val();
        $(".npd").val(ipd-4);
        $(".rpd, .lpd").val(ipd/2);
    });
    $(".spectacle .re_dist_add").change(function(){
        var re_dist_add = $(this).val();
        var re_dist_va =$(".re_dist_va").val()
        if(re_dist_add){
            $(".re_near_va").focus();
            $(".re_near_va").val(parseFloat(re_dist_add)+parseFloat(re_dist_va));
        }else{
            $(".le_dist_sph").focus();
        }
    });
    $(".spectacle .le_dist_add").change(function(){
        var le_dist_add = $(this).val();
        var le_dist_va =$(".le_dist_va").val()
        if(le_dist_add){
            $(".le_near_va").focus();
            $(".le_near_va").val(parseFloat(le_dist_add)+parseFloat(le_dist_va));
        }
    });
    $(".spectacle .re_dist_add").change(function(){
        var re_dist_cyl = $(".re_dist_cyl").val();
        var re_dist_add = $(this).val();
        if(re_dist_add){
            $(".re_int_cyl, .re_near_cyl").val(re_dist_cyl);
        }
    });
    $(".spectacle .le_dist_add").change(function(){
        var le_dist_cyl = $(".le_dist_cyl").val();
        var le_dist_add = $(this).val();
        if(le_dist_add){
            $(".le_int_cyl, .le_near_cyl").val(le_dist_cyl);
        }
    });

    $(".medicineAdvise").click(function(){    
        $(".medicineAdviseContainer").append("<div class='row mb-3'><div class='col-sm-3'><select class='form-control form-control-md select2 selMedicine' data-placeholder='Select' name='medicine_id[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-2'><input type='text' name='dosage[]' class='form-control form-control-md' placeholder='Eg: Daily 3 Drops'/></div><div class='col-sm-2'><select class='form-control form-control-md select2 selDosage' data-placeholder='Select' name='dosage1[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-2'><input type='number' class='form-control form-control-md' name='qty[]' placeholder='0' /></div><div class='col-sm-2'><input type='text' class='form-control form-control-md' name='notes[]' placeholder='Notes'/></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>");        
        $('.selMedicine, .selDosage').select2();
        bindDDL('medicine', 'selMedicine'); bindDDL('dosage', 'selDosage');
    });

    $(".medicineRow").click(function(){
        $(".tblMedicine tbody").append("<tr><td><select class='form-control form-control-md select2 selMedicine' data-placeholder='Select' name='product_id[]' required='required'></select></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='qty[]' required='required' /></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='price[]' required='required' /></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='total[]' required='required' /></td><td><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></td></tr>");
        $('.selMedicine').select2();
        bindDDL('medicine', 'selMedicine');
    });

    $(".addPurchaseRow").click(function(){
        $(".purchaseRow").append("<div class='row mt-3'><div class='col-sm-4'><select class='form-control form-control-md show-tick ms select2 selProductForPurchase' data-placeholder='Select' name='product[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-2'><input type='text' name='batch_number[]' class='form-control form-control-md' placeholder='Batch Number' required='required'></div><div class='col-sm-2'><input type='text' name='expiry_date[]' class='form-control form-control-md dtpicker' placeholder='dd/mm/yyyy' required='required'></div><div class='col-sm-1'><input type='number' name='qty[]' class='form-control form-control-md' placeholder='0' required='required'></div><div class='col-sm-2'><input type='number' name='price[]' class='form-control form-control-md' placeholder='0.00' required='required'></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>");
        $('.selProductForPurchase').select2();
        bindDDL('medicine', 'selProductForPurchase');
    });

    $(".addStockTransferRow").click(function(){
        $(".stockTransferRow").append("<div class='row mt-3'><div class='col-sm-5'><select class='form-control form-control-md show-tick ms select2 selProductForTransfer' data-placeholder='Select' name='product[]' required='required'></select></div><div class='col-sm-3'><input type='text' name='batch_number[]' class='form-control form-control-md' placeholder='Batch Number' required='required'></div><div class='col-sm-1'><input type='number' name='qty[]' class='form-control form-control-md' placeholder='0' required='required'></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>");
        $('.selProductForTransfer').select2();
        bindDDL('medicine', 'selProductForTransfer');
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