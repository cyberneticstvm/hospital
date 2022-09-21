$(function(){
    "use strict";
    $(".spectacle .ipd").change(function(){
        var ipd = $(this).val();
        $(".npd").val(ipd-4);
        $(".rpd, .lpd").val(ipd/2);
    });
    $(".spectacle .re_dist_va").focusout(function(){
        $(".re_dist_add").focus();
    });
    $(".spectacle .re_int_add").focusout(function(){
        var int_add = $(this).val();
        var int_sph = $(".re_int_sph").val();
        if(int_add){
            var age = $("#age").val();
            var re_dist_sph = $(".re_dist_sph").val();
            if(age < 52){
                //var int_sph = (parseFloat(int_add)) ? parseFloat(int_add)+parseFloat(re_dist_sph): int_sph;
                //$(".re_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '0.00');
            }else{
                var int_sph = (parseFloat(int_add)) ? parseFloat(int_add)+parseFloat(re_dist_sph) : int_sph;
                $(".re_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '0.00');
            }
        }else{
            $(".re_int_sph").val(int_sph);
        }
    });
    $(".spectacle .re_dist_add").focusout(function(){
        var re_dist_add = $(this).val();
        var re_dist_sph = $(".re_dist_sph").val();
        var re_dist_cyl = $(".re_dist_cyl").val();
        var re_dist_axis = $(".re_dist_axis").val();
        var age = $("#age").val();
        var re_near = (parseFloat(re_dist_sph)+parseFloat(re_dist_add) > 0) ? parseFloat(re_dist_sph)+parseFloat(re_dist_add) : parseFloat(re_dist_sph)+parseFloat(re_dist_add);
        $(".re_near_sph").val((re_near > 0) ? '+' + re_near.toFixed(2) : (re_near) ? re_near.toFixed(2) : '0.00');

        if(age < 52){
            var int_sph = (parseFloat(re_dist_sph)) ? parseFloat(re_dist_sph)+0.75 : 0.75;
            $(".re_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '0.00');
        }else{
            var int_sph = (parseFloat(re_dist_sph)) ? parseFloat(re_dist_sph)+1.25 : 1.25;
            $(".re_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '0.00');
        }

        if(re_dist_cyl && re_dist_add){
            $(".re_int_cyl, .re_near_cyl").val(re_dist_cyl);
        }else{
            $(".re_int_cyl, .re_near_cyl").val('');
        }
        if(re_dist_axis && re_dist_add){
            $(".re_int_axis, .re_near_axis").val(re_dist_axis);
        }else{
            $(".re_int_axis, .re_near_axis").val('');
        }
        $(".le_dist_add").val(re_dist_add);
        $(".re_near_va").focus();
    });

    $(".spectacle .le_dist_va").focusout(function(){
        $(".le_dist_add").focus();
    });

    $(".spectacle .le_int_add").focusout(function(){
        var int_add = $(this).val();
        var int_sph = $(".le_int_sph").val();
        if(int_add){
            var age = $("#age").val();
            var le_dist_sph = $(".le_dist_sph").val();
            if(age < 52){
                var int_sph = (parseFloat(int_add)) ? parseFloat(int_add)+parseFloat(le_dist_sph): int_sph;
                $(".le_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '');
            }else{
                var int_sph = (parseFloat(int_add)) ? parseFloat(int_add)+parseFloat(le_dist_sph) : int_sph;
                $(".le_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '');
            }
        }else{
            $(".le_int_sph").val(int_sph);
        }
    });

    $(".spectacle .le_dist_add").focusout(function(){
        var le_dist_add = $(this).val();
        var le_dist_sph = $(".le_dist_sph").val();
        var le_dist_cyl = $(".le_dist_cyl").val();
        var le_dist_axis = $(".le_dist_axis").val();
        var age = $("#age").val();
        var le_near = (parseFloat(le_dist_sph)+parseFloat(le_dist_add) > 0) ? parseFloat(le_dist_sph)+parseFloat(le_dist_add) : parseFloat(le_dist_sph)+parseFloat(le_dist_add);
        $(".le_near_sph").val((le_near > 0) ? '+' + le_near.toFixed(2) : (le_near) ? le_near.toFixed(2) : '0.00');

        if(age < 52){
            var int_sph = (parseFloat(le_dist_sph)) ? parseFloat(le_dist_sph)+0.75 : 0.75;
            $(".le_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '');
        }else{
            var int_sph = (parseFloat(le_dist_sph)) ? parseFloat(le_dist_sph)+1.25 : 1.25;
            $(".le_int_sph").val((int_sph > 0) ? '+' + int_sph.toFixed(2) : (int_sph) ? int_sph.toFixed(2) : '');
        }

        if(le_dist_cyl && le_dist_add){
            $(".le_int_cyl, .le_near_cyl").val(le_dist_cyl);
        }else{
            $(".le_int_cyl, .le_near_cyl").val('');
        }
        if(le_dist_axis && le_dist_add){
            $(".le_int_axis, .le_near_axis").val(le_dist_axis);
        }else{
            $(".le_int_axis, .le_near_axis").val('');
        }
        $(".le_near_va").focus();
    });

    $(".spectacle .re_int_add").change(function(){
        $(".le_int_add").val($(this).val());
    });

    $(".spectacle .re_near_va").focusout(function(){
        $(".le_dist_sph").focus();
    });

    $(".chkREtoLE").click(function(){
        if($(this).is(":checked")){
            var c = 0;
            $(".tbodyre td").each(function(){
                var val = $(this).find('input').val();
                $(".tbodyle td:eq("+c+")").find('input').val(val);
                c++;
            });
        }else{
            $(".tbodyle tr .form-control").each(function(){
                $(this).val('');
            });
        }
    });

    $(".bm_k1_od_a, .bm_k2_od_a").change(function(){
        var val1 = (parseFloat($(".bm_k1_od_a").val()) > 0) ? parseFloat($(".bm_k1_od_a").val()) : 0;
        var val2 = (parseFloat($(".bm_k2_od_a").val()) > 0) ? parseFloat($(".bm_k2_od_a").val()) : 0;
        $(".bm_od_kvalue_a").val(((val1+val2)/2).toFixed(2));
    });

    $(".bm_k1_os_a, .bm_k2_os_a").change(function(){
        var val1 = (parseFloat($(".bm_k1_os_a").val()) > 0) ? parseFloat($(".bm_k1_os_a").val()) : 0;
        var val2 = (parseFloat($(".bm_k2_os_a").val()) > 0) ? parseFloat($(".bm_k2_os_a").val()) : 0;
        $(".bm_os_kvalue_a").val(((val1+val2)/2).toFixed(2));
    });
});