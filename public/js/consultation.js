let odpoints = []; let ospoints = []; let odpoints1 = []; let ospoints1 = []; let odpoints2 = []; let ospoints3 = []; let odpoints3 = []; let ospoints2 = []; let odpoints_paths_array = []; let ospoints_paths_array = []; let odpoints1_paths_array = []; let ospoints1_paths_array = []; let odpoints2_paths_array = []; let ospoints2_paths_array = []; let odpoints3_paths_array = []; let ospoints3_paths_array = [];
let lwidth = 3; let favcolor = '#00bdaa';
$(function(){
    $(".favcolor").val('#00bdaa');
    /*$(document).on('change', ".medAdvised", function(){
        var mid = $(this).val();
        var dis = $(this).parent().parent();
        if(mid === ""){
            dis.find(".form-control").each(function(){
                $(this).val('');
            })
        }else{
            $.ajax({
                type: 'GET',
                url: '/helper/getmedicinetype/'+mid,
                data: {'mid': mid},
                success: function(data){
                    dis.find('.qty').val(data.default_qty);           
                    dis.find('.dos').val(data.default_dosage);           
                }
            });
        };
    })*/
})
$(".pen, .brush").click(function(){
    lwidth = $(this).data('line-width');
});

$(document).on("change", ".favcolor1, .favcolor2, .favcolor3, .favcolor4", function(){
    favcolor = $(this).val();
    $(".favcolor").val(favcolor);
});

$(function(){
    "use strict";
    $(".odpoints span").each(function(){
        odpoints.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".ospoints span").each(function(){
        ospoints.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".odpoints1 span").each(function(){
        odpoints1.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".ospoints1 span").each(function(){
        ospoints1.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".odpoints2 span").each(function(){
        odpoints2.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".ospoints2 span").each(function(){
        ospoints2.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".odpoints3 span").each(function(){
        odpoints3.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });
    $(".ospoints3 span").each(function(){
        ospoints3.push({description: $(this).text(), color: $(this).data('color'), type: $(this).data('itype')});
    });

    $(document).on('click', '.btn-consultation', function(e){
        e.preventDefault();
        var form_data = $(this).closest('form').serializeArray();       
        var url = $(this).closest('form').attr('action');
        var btn = $("#btn_text").val();
        var vision_od_canvas = document.getElementById('re_eye');
        var vision_os_canvas = document.getElementById('le_eye');
        var vision_od_canvas1 = document.getElementById('re_eye1');
        var vision_os_canvas1 = document.getElementById('le_eye1');
        var vision_od_canvas2 = document.getElementById('re_eye2');
        var vision_os_canvas2 = document.getElementById('le_eye2');
        var vision_od_canvas3 = document.getElementById('re_eye3');
        var vision_os_canvas3 = document.getElementById('le_eye3');
        var vision_od_canvas_url = vision_od_canvas.toDataURL();
        var vision_os_canvas_url = vision_os_canvas.toDataURL();
        var vision_od_canvas_url1 = vision_od_canvas1.toDataURL();
        var vision_os_canvas_url1 = vision_os_canvas1.toDataURL();
        var vision_od_canvas_url2 = vision_od_canvas2.toDataURL();
        var vision_os_canvas_url2 = vision_os_canvas2.toDataURL();
        var vision_od_canvas_url3 = vision_od_canvas3.toDataURL();
        var vision_os_canvas_url3 = vision_os_canvas3.toDataURL();
        form_data.push({name: 'vision_od_img1', value: vision_od_canvas_url});
        form_data.push({name: 'vision_os_img1', value: vision_os_canvas_url});
        form_data.push({name: 'vision_od_img2', value: vision_od_canvas_url1});
        form_data.push({name: 'vision_os_img2', value: vision_os_canvas_url1});

        form_data.push({name: 'vision_od_img3', value: vision_od_canvas_url2});
        form_data.push({name: 'vision_os_img3', value: vision_os_canvas_url2});
        form_data.push({name: 'vision_od_img4', value: vision_od_canvas_url3});
        form_data.push({name: 'vision_os_img4', value: vision_os_canvas_url3});
        var odospoints1 = $.merge($.merge(odpoints, ospoints), $.merge(odpoints1, ospoints1));
        var odospoints2 = $.merge($.merge(odpoints2, ospoints2), $.merge(odpoints3, ospoints3));
        var odospoints = $.merge(odospoints1, odospoints2);
        //var odospaths = $.merge($.merge(odpoints_paths_array, ospoints_paths_array), $.merge(odpoints1_paths_array, ospoints1_paths_array));
        form_data.push({name: 'odospoints', value: JSON.stringify(odospoints)});
        //form_data.push({name: 'odospaths', value: JSON.stringify(odospaths)});
        $.ajax({
            type: 'POST',
            url: url,
            data: form_data,
            success: function(data){
                if(data.trim() == 'success'){
                    console.log(data);
                    alert("Record updated successfully.");
                    window.location.href = '/consultation/medical-records/';
                }else{
                    alert(data);
                    console.log(data);
                }              
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(XMLHttpRequest[0]);
                console.log(XMLHttpRequest)
                console.log(textStatus)
                console.log(url)
            },
            beforeSend: function(){
                $(".btn-consultation").html("<span class='spinner-grow spinner-grow-sm' role='status' aria-hidden='true'></span>&nbsp;Loading...");
            },
            complete: function(){
                $(".btn-consultation").html(btn);
            }
        });
    });

    $(".retina_od, .retina_os").change(function(e){
        e.preventDefault();
        var type = $(this).data("type");
        var container = $(this).data("container");
        var file = $(this).get(0).files[0];
        var labid = $(this).data("labid"); // Only applicable in Lab test images
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                $("."+container).append("<div class='imgrow'><img src='"+reader.result+"' class='img-fluid mt-1 mb-1' alt=''/><div class='row '><div class='col-sm-10'><input type='text' class='form-control' name='retina_desc[]' placeholder='Description'><input type='hidden' name='retina_img[]' value='"+reader.result+"'><input type='hidden' name='retina_type[]' value='"+type+"'><input type='hidden' name='lab_test_id[]' value='"+labid+"'></div><div class='col-sm-2 '><a href='javascript:void(0)'><i class='fa fa-trash text-danger removeImg'></i></a></div></div></div>");
            }
            reader.readAsDataURL(file);
            //console.log(file);
        }
        $(this).val('');
    });

    $(document).on('click', '.removeImg', function(){
        $(this).closest('.imgrow').remove();
    });
});

$(window).on('load', function () {
    var canvas = document.getElementById("re_eye");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye");
    //ctx.drawImage(img, 0, 0);
    var cls = "odpoints";
    drawOnImage(img, canvas, ctx, cls);

    var canvas1 = document.getElementById("le_eye");
    var ctx1 = canvas1.getContext("2d");
    var img1 = document.getElementById("imgleye");
    var cls1 = "ospoints";
    drawOnImage(img1, canvas1, ctx1, cls1);

    var canvas2 = document.getElementById("re_eye1");
    var ctx2 = canvas2.getContext("2d");
    var img2 = document.getElementById("imgreye1");
    var cls2 = "odpoints1";
    drawOnImage(img2, canvas2, ctx2, cls2);

    var canvas3 = document.getElementById("le_eye1");
    var ctx3 = canvas3.getContext("2d");
    var img3 = document.getElementById("imgleye1");
    var cls3 = "ospoints1";
    drawOnImage(img3, canvas3, ctx3, cls3);

    var canvas4 = document.getElementById("re_eye2");
    var ctx4 = canvas4.getContext("2d");
    var img4 = document.getElementById("imgreye2");
    var cls4 = "odpoints2";
    drawOnImage(img4, canvas4, ctx4, cls4);

    var canvas5 = document.getElementById("le_eye2");
    var ctx5 = canvas5.getContext("2d");
    var img5 = document.getElementById("imgleye2");
    var cls5 = "ospoints2";
    drawOnImage(img5, canvas5, ctx5, cls5);

    var canvas6 = document.getElementById("re_eye3");
    var ctx6 = canvas6.getContext("2d");
    var img6 = document.getElementById("imgreye3");
    var cls6 = "odpoints3";
    drawOnImage(img6, canvas6, ctx6, cls6);

    var canvas7 = document.getElementById("le_eye3");
    var ctx7 = canvas7.getContext("2d");
    var img7 = document.getElementById("imgleye3");
    var cls7 = "ospoints3";
    drawOnImage(img7, canvas7, ctx7, cls7);
});

let points = [];
function drawOnImage(image, canvasElement, context, cls){
    if (image) {
        const imageWidth = image.width;
        const imageHeight = image.height;
        // rescaling the canvas element
        canvasElement.width = imageWidth;
        canvasElement.height = imageHeight;
        context.drawImage(image, 0, 0, imageWidth, imageHeight);
    }
    let isDrawing;
    canvasElement.onmousedown = (e) => {
        isDrawing = true;
        context.beginPath();
        context.lineWidth = lwidth;
        //context.strokeStyle = favcolor;
        context.lineJoin = "round";
        context.lineCap = "round";
        context.moveTo(e.offsetX, e.offsetY);
        points = [];
        points.push({x:e.offsetX, y:e.offsetY, color: favcolor});
    };
    
    canvasElement.onmousemove = (e) => {
        if (isDrawing) {
        context.strokeStyle = favcolor;     
        points.push({x:e.offsetX, y:e.offsetY, color: favcolor});
        context.lineTo(e.offsetX , e.offsetY);
        context.stroke();      
        }
    };
    
    canvasElement.onmouseup = function () {
        isDrawing = false;
        context.closePath();
        if(cls == 'odpoints'){odpoints_paths_array.push(points)};
        if(cls == 'ospoints'){ospoints_paths_array.push(points)};
        if(cls == 'odpoints1'){odpoints1_paths_array.push(points)};
        if(cls == 'ospoints1'){ospoints1_paths_array.push(points)};
        if(cls == 'odpoints2'){odpoints2_paths_array.push(points)};
        if(cls == 'ospoints2'){ospoints2_paths_array.push(points)};
        if(cls == 'odpoints3'){odpoints3_paths_array.push(points)};
        if(cls == 'ospoints3'){ospoints3_paths_array.push(points)};
        $("#visionModal .vision_description").val("");
        $("#visionModal .vision_canvas").val(cls);
        $("#visionModal").modal("show");
    };
}

$(".btnaddpoints").click(function(){
    var cls = $(this).parent().parent().find(".vision_canvas").val();
    var value = $(this).parent().parent().find(".vision_description").val();
    if(cls == 'odpoints'){
        odpoints.push({description: value, color: $(".favcolor").val(), type: 'vision_od_img1'});
        $(".odpoints").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'ospoints'){
        ospoints.push({description: value, color: $(".favcolor").val(), type: 'vision_os_img1'});
        $(".ospoints").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'odpoints1'){
        odpoints1.push({description: value, color: $(".favcolor").val(), type: 'vision_od_img2'});
        $(".odpoints1").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'ospoints1'){
        ospoints1.push({description: value, color: $(".favcolor").val(), type: 'vision_os_img2'});
        $(".ospoints1").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'odpoints2'){
        odpoints2.push({description: value, color: $(".favcolor").val(), type: 'vision_od_img3'});
        $(".odpoints2").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'ospoints2'){
        ospoints2.push({description: value, color: $(".favcolor").val(), type: 'vision_os_img3'});
        $(".ospoints2").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'odpoints3'){
        odpoints3.push({description: value, color: $(".favcolor").val(), type: 'vision_od_img4'});
        $(".odpoints3").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }else if(cls == 'ospoints3'){
        ospoints3.push({description: value, color: $(".favcolor").val(), type: 'vision_os_img4'});
        $(".ospoints3").append("<span class='badge bg-light' style='color:"+$(".favcolor").val()+"'>" +value+ "</span>");
    }
    $("#visionModal").modal("hide");
});

$("#odundo").click(function(){
    var canvas = document.getElementById("re_eye");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye");
    var cls = 'odpoints';
    $(".odpoints span:last").remove();
    odpoints.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    odpoints_paths_array.splice(-1, 1);
    drawPaths(ctx, odpoints_paths_array);
});

$("#osundo").click(function(){
    var canvas = document.getElementById("le_eye");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye");
    var cls = 'ospoints';
    $(".ospoints span:last").remove();
    ospoints.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    ospoints_paths_array.splice(-1,1);
    drawPaths(ctx, ospoints_paths_array);
});

$("#odundo1").click(function(){
    var canvas = document.getElementById("re_eye1");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye1");
    var cls = 'odpoints1';
    $(".odpoints1 span:last").remove();
    odpoints1.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    odpoints1_paths_array.splice(-1,1);
    drawPaths(ctx, odpoints1_paths_array);
});

$("#osundo1").click(function(){
    var canvas = document.getElementById("le_eye1");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye1");
    var cls = 'ospoints1';
    $(".ospoints1 span:last").remove();
    ospoints1.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    ospoints1_paths_array.splice(-1,1);
    drawPaths(ctx, ospoints1_paths_array);
});

$("#odundo2").click(function(){
    var canvas = document.getElementById("re_eye2");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye2");
    var cls = 'odpoints2';
    $(".odpoints2 span:last").remove();
    odpoints2.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    odpoints2_paths_array.splice(-1,1);
    drawPaths(ctx, odpoints2_paths_array);
});

$("#osundo2").click(function(){
    var canvas = document.getElementById("le_eye2");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye2");
    var cls = 'ospoints2';
    $(".ospoints2 span:last").remove();
    ospoints2.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    ospoints2_paths_array.splice(-1,1);
    drawPaths(ctx, ospoints2_paths_array);
});

$("#odundo3").click(function(){
    var canvas = document.getElementById("re_eye3");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye3");
    var cls = 'odpoints3';
    $(".odpoints3 span:last").remove();
    odpoints3.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    odpoints3_paths_array.splice(-1,1);
    drawPaths(ctx, odpoints3_paths_array);
});

$("#osundo3").click(function(){
    var canvas = document.getElementById("le_eye3");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye3");
    var cls = 'ospoints3';
    $(".ospoints3 span:last").remove();
    ospoints3.splice(-1, 1);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    ospoints3_paths_array.splice(-1,1);
    drawPaths(ctx, ospoints3_paths_array);
});

$("#odclear").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgreye").attr('src', $("#imgreye_1").attr('src'));
    if(mrid > 0) $("#odundo").removeClass('d-none');
    var canvas = document.getElementById("re_eye");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye");
    var cls = 'odpoints';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".odpoints").html("");
    odpoints = [];
});
$("#osclear").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgleye").attr('src', $("#imgleye_1").attr('src'));
    if(mrid > 0) $("#osundo").removeClass('d-none');
    var canvas = document.getElementById("le_eye");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye");
    var cls = 'ospoints';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".ospoints").html("");
    ospoints = [];
});
$("#odclear1").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgreye1").attr('src', $("#imgreye1_1").attr('src'));
    if(mrid > 0) $("#odundo1").removeClass('d-none');
    var canvas = document.getElementById("re_eye1");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye1");
    var cls = 'odpoints1';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".odpoints1").html("");
    odpoints1 = [];
});
$("#osclear1").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgleye1").attr('src', $("#imgleye1_1").attr('src'));
    if(mrid > 0) $("#osundo1").removeClass('d-none');
    var canvas = document.getElementById("le_eye1");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye1");
    var cls = 'ospoints1';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".ospoints1").html("");
    ospoints1 = [];
});

$("#odclear2").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgreye2").attr('src', $("#imgreye2_1").attr('src'));
    if(mrid > 0) $("#odundo2").removeClass('d-none');
    var canvas = document.getElementById("re_eye2");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye2");
    var cls = 'odpoints2';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".odpoints2").html("");
    odpoints2 = [];
});
$("#osclear2").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgleye2").attr('src', $("#imgleye2_1").attr('src'));
    if(mrid > 0) $("#osundo2").removeClass('d-none');
    var canvas = document.getElementById("le_eye2");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye2");
    var cls = 'ospoints2';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".ospoints2").html("");
    ospoints2 = [];
});

$("#odclear3").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgreye3").attr('src', $("#imgreye3_1").attr('src'));
    if(mrid > 0) $("#odundo3").removeClass('d-none');
    var canvas = document.getElementById("re_eye3");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgreye3");
    var cls = 'odpoints3';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".odpoints3").html("");
    odpoints3 = [];
});
$("#osclear3").click(function(){
    var mrid = $("#mrid").val();
    if(mrid > 0) $("#imgleye3").attr('src', $("#imgleye3_1").attr('src'));
    if(mrid > 0) $("#osundo3").removeClass('d-none');
    var canvas = document.getElementById("le_eye3");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("imgleye3");
    var cls = 'ospoints3';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawOnImage(img, canvas, ctx, cls);
    $(".ospoints3").html("");
    ospoints3 = [];
});

function drawPaths(ctx, arr){
    arr.forEach(path=>{
    ctx.beginPath();
    ctx.lineWidth = lwidth;
    ctx.strokeStyle = $(".favcolor").val();
    ctx.lineJoin = "round";
    ctx.lineCap = "round";
    ctx.moveTo(path[0].x,path[0].y);  
    for(let i = 1; i < path.length; i++){
        ctx.strokeStyle = path[i].color;
        ctx.lineTo(path[i].x,path[i].y); 
    }
        ctx.stroke();
    })
}  