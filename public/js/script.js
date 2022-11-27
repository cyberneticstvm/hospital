$(function() {
    "use strict";
    var branch = $("#branch_selector").val();
    $(".visionModal").modal({backdrop: 'static'});
    $(".visionModal").modal({keyboard: false});
    if(!branch){
        $(".branchSelector").modal({backdrop: 'static'});
        $(".branchSelector").modal({keyboard: false});
        $(".branchSelector").modal("show");
    }
    $('#dataTbl').dataTable({
        responsive: true
    });

    $(".alert").show().delay(5000).fadeOut();

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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.btn-ajax-submit', function(e){
        e.preventDefault();
        var frm = $(this).closest('form');
        var ddl = frm.find(".ddl").val();
        var form_data = frm.serialize();
        var url = $(this).closest('form').attr('action'); 
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
                    $("."+ddl).select2({data:xdata});
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
        $(".medicineAdviseContainer").append("<div class='row mb-3'><input type='hidden' name='price[]' value='0.00' /><input type='hidden' name='discount[]' value='0.00' /><input type='hidden' name='tax_amount[]' value='0.00' /><input type='hidden' name='tax_percentage[]' value='0.00' /><input type='hidden' name='total[]' value='0.00' /><div class='col-sm-2'><select class='form-control form-control-md select2 medType' data-placeholder='Select' name='medicine_type[]' required='required'><option value='0'>Select</option></select></div><div class='col-sm-3'><select class='form-control form-control-md select2 medAdvised' data-placeholder='Select' name='medicine_id[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-1'><input type='text' name='dosage[]' class='form-control form-control-md dos' placeholder='Eg: Daily 3 Drops'/></div><div class='col-sm-1'><input type='text' name='duration[]' class='form-control form-control-md dos' placeholder='Duration'/></div><div class='col-sm-1'><select class='form-control' name='eye[]'><option value='B'>Both</option><option value='R'>RE</option><option value='L'>LE</option></select></div><div class='col-sm-1'><input type='number' class='form-control form-control-md qty' name='qty[]' placeholder='0' /></div><div class='col-sm-2'><input type='text' class='form-control form-control-md' name='notes[]' placeholder='Notes'/></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>");        
        $('.medAdvised').select2(); $('.medType').select2();
        //bindDDL('medicine', 'medAdvised'); 
        bindDDL('medtype', 'medType');
    });

    $(".medicineRow").click(function(){
        $(".tblMedicine tbody").append("<tr><td><select class='form-control form-control-md select2 selMedicine' data-placeholder='Select' name='product_id[]' required='required'></select></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='qty[]' required='required' /></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='price[]' required='required' /></td><td><input type='number' class='form-control form-control-md text-right' placeholder='0' name='total[]' required='required' /></td><td><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></td></tr>");
        $('.selMedicine').select2();
        bindDDL('medicine', 'selMedicine');
    });

    $(".addPharmacyRow").click(function(){
        $(".tblPharmacy").append("<tr><td><select class='form-control form-control-sm select2 selProduct selProductForTransfer' data-placeholder='Select' required='required' name='product[]'><option value=''>Select</option></select></td><td><select class='form-control form-control-sm select2 selBatchNo bno' name='batch_number[]' required='required'><option value=''>Select</option></select></td><td><input type='number' class='form-control form-control-sm text-end qty' step='any' min='1' name='qty[]' placeholder='0' required='required'/></td><td><input type='text' class='form-control form-control-sm' name='dosage[]' placeholder='Dosage'/></td><td><input type='number' class='form-control form-control-sm text-end price' step='any' name='price[]' placeholder='0.00' required='required'/></td><td><input type='number' class='form-control form-control-sm text-end discount' step='any' name='discount[]' placeholder='0.00' /></td><td><input type='number' class='form-control form-control-sm text-end tax' step='any' name='tax[]' placeholder='0%' /></td><td><input type='number' class='form-control form-control-sm text-end tax_amount' step='any' name='tax_amount[]' placeholder='0.00' /></td><td><input type='number' class='form-control form-control-sm text-end total' step='any' name='total[]' placeholder='0.00' required='required' /></td><td class='text-center'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></td></tr>");
        $('.selProduct').select2(); $('.selBatchNo').select2();
        bindDDL('medicine', 'selProduct');
    });

    $(".addPurchaseRow").click(function(){
        $(".purchaseRow").append("<div class='row mt-3'><div class='col-sm-2'><select class='form-control form-control-md show-tick ms select2 selProductForPurchase' data-placeholder='Select' name='product[]' required='required'><option value=''>Select</option></select></div><div class='col-sm-2'><input type='text' name='batch_number[]' class='form-control form-control-md bno' placeholder='Batch Number' required='required'></div><div class='col-sm-2'><input type='date' name='expiry_date[]' class='form-control form-control-md' placeholder='dd/mm/yyyy' required='required'></div><div class='col-sm-1'><input type='number' name='qty[]' class='form-control form-control-md calcTot qty' placeholder='0' required='required'></div><div class='col-sm-1'><input type='number' name='purchase_price[]' step='any' class='form-control form-control-md calcTot purchasePrice' placeholder='0.00'></div><div class='col-sm-1'><input type='number' name='price[]' step='any' class='form-control form-control-md calcTot' placeholder='0.00' required='required'></div><div class='col-sm-1'><input type='number' name='mrp[]' step='any' class='form-control form-control-md calcTot' placeholder='0.00' required='required'></div><div class='col-sm-1'><input type='number' name='adjustment[]' step='any' class='form-control form-control-md adjust calcTot' placeholder='0.00'></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove();'><i class='fa fa-trash text-danger calcTot'></i></a></div></div>");
        $('.selProductForPurchase').select2();
        bindDDL('medicine', 'selProductForPurchase');
    });

    $(".addStockTransferRow").click(function(){
        $(".stockTransferRow").append("<div class='row mt-3'><div class='col-sm-5'><select class='form-control form-control-md show-tick ms select2 selProductForTransfer' data-placeholder='Select' name='product[]' required='required'></select></div><div class='col-sm-3'><select name='batch_number[]' class='form-control form-control-md bno' required='required'><option value=''>Select</option></select></div><div class='col-sm-1'><input type='number' name='qty[]' class='form-control form-control-md' placeholder='0' required='required'></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger '></i></a></div></div>");
        $('.selProductForTransfer').select2();
        bindDDL('medicine', 'selProductForTransfer');
    });

    $(".addLabTest").click(function(){
        var type = $(this).data('category');
        $(".labtestRow").append("<div class='row mt-3'><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2 selLabTest' data-placeholder='Select' name='test_id[]' required='required'></select></div><div class='col-sm-5'><input type='text' name='notes[]' class='form-control' placeholder='Notes' /></div><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2' data-placeholder='Select' name='tested_from[]' required='required'><option value='1'>Own Laboratory</option><option value='0'>Outside Laboratory</option></select></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>");
        $('.selLabTest').select2();
        bindDDL(type, 'selLabTest');
    });

    $(".selFromBranch").change(function(){
        $('.selProductForTransfer').val(0);
        bindDDL('medicine', 'selProductForTransfer');
    });

    $(document).on("change", ".selProductForTransfer", function(){
        var batch = $(this).parent().parent().find(".bno");
        var branch = $(".selFromBranch").val();
        var product = $(this).val();
        $.ajax({
            type: 'GET',
            url: '/helper/getproductfortransfer/',
            data: {'product': product, 'branch': branch},
            success: function(data){
                batch.html(data);
                batch.select2();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log(XMLHttpRequest);
            }
        });
        return false;
    });

    $(".vEModal").click(function(){
        var ddl = $(this).data('ddl');
        $("#frm-vision-extras").find(".ddl").val(ddl);
        $("#visionExtrasModal").modal("show");
    });

    $(document).on('click', '.dlt', function(){
        var url = $(this).data('url');
        $.ajax({
            type: 'DELETE',
            url: url,
            data: {},
            success: function(data){
                alert(data);
                location.reload();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log(XMLHttpRequest);
            }
        });
        return false;
    });

    $(".daybook").click(function(){        
        var modal = $(this).data('modal');
        var title = $(this).data('title');
        var fdate = $(this).data('fdate');
        var tdate = $(this).data('tdate');
        var branch = $(this).data('branch');
        var type = $(this).data('type');
        $("#"+modal).on('shown.bs.modal', function(){
            $(this).find(".modal-title").html(title);
            $.ajax({
                type: 'GET',
                url: '/helper/daybook/',
                data: {'fdate': fdate, 'tdate': tdate, 'branch': branch, 'type': type},
                success: function(data){
                    $("#"+modal).find(".dayBookDetailed").html(data);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log(XMLHttpRequest);
                }
            });
        });
    }); 
    
    $(document).on("change", ".medType", function(){
        var type = parseInt($(this).val());
        //if(type > 0){
            var dis = $(this).parent().next();
            dis.find('.medAdvised').empty();
            $.ajax({
                type: 'GET',
                url: '/symptom/products/'+type
            }).then(function (data){
                var xdata = $.map(data, function(obj){
                    obj.text = obj.name || obj.id;  
                    return obj;
                });
                dis.find('.medAdvised').select2({data:xdata});
            });
        //}        
    });

    $(".appo").change(function(){
        var date = convertToDate($(".dtpicker").val());
        var branch = $(".br").val();
        var doctor = $(".dr").val();
        $.ajax({
            type: 'GET',
            url: '/appointment/gettime/'+date+'/'+branch+'/'+doctor,
            data: {},
            success: function(response){
                $(".atime").html(response);
                $('.atime').select2();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log(XMLHttpRequest);
            }
        });
    });

    $(document).on("change", ".calcTot", function(){
        var tot = calculatePurchaseTotal();
        $(".purchase_total").text(tot.toFixed(2));
    });
    $(document).on("click", ".calcTot", function(){
        var tot = calculatePurchaseTotal();
        $(".purchase_total").text(tot.toFixed(2));
    });
});

/*$(window).on('load', function () {
    taggingPhoto.init($('img.tagging-photo'), {
        onAddTag: function (points) {
            console.log(points); // Event function to return array of tags after add or edit and delete tag
        }
    });
});*/

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

function convertToDate(date){
    var dt = date.split("/");
    var m = new Date(dt[1]+'-1-01').getMonth()+1;
    m = (parseInt(m) > 9) ? m : "0"+m;
    return [dt[2], m, dt[0]].join('-');
}

function calculatePurchaseTotal(){
    var price = 0; var qty = 0; var adjust = 0; var tot = 0;
    $(".purchasePrice").each(function(){
        qty = (parseInt($(this).parent().parent().find(".qty").val()) > 0) ? parseInt($(this).parent().parent().find(".qty").val()) : 0;
        adjust = (parseFloat($(this).parent().parent().find(".adjust").val()) > 0 || parseFloat($(this).parent().parent().find(".adjust").val()) < 0) ? parseFloat($(this).parent().parent().find(".adjust").val()) : 0;
        price = ($(this).val() > 0) ? parseFloat($(this).val()) : 0;
        tot += (qty*price)+adjust;
    });
    return (tot >= 0) ? tot : 0;
}