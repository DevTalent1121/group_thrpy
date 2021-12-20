function getScaleScreen(w, h) {
    var int_x = w/16;
    var int_y = h/9;
  var scale = 1.000;
  console.log("width:" + w + " height: " + h +" x_int: "+int_x + " y_int: " + int_y);
  if(h>int_x*9)
    scale = h/(int_val*9);
  else{
    scale = w/(int_y*16);
  }
  console.log(scale);
  return scale+0.004;
}
jQuery(document).ready(function($){
    var sc_width = $(document).width();
    var sc_height = $(document).height();
    // alert(getScaleScreen(sc_width,sc_height));
    $('<style>.background-video { transform: scale('+getScaleScreen(sc_width,sc_height)+'); }</style>').appendTo('body');
    $(".dc_project").mouseenter(function(){
        var video_id = "background-video_"+$(this).attr("data-video");
        $(this).css("text-decoration","underline");
        $(".background-video").each(function(){
            $(this).removeClass("active");
            $(this)[0].muted=true;
        });
        $("body").css("background","transparent");
        $("#"+video_id).addClass("active");    
    });
    $(".dc_project").mouseleave(function(){
        var video_id = "background-video_"+$(this).attr("data-video");
        $(this).css("text-decoration","none");
        $("#"+video_id).removeClass("active");
    });
});
