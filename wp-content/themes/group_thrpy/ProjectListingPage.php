<?php /* Template Name: Project Listing Page Template */ ?>
<?php get_header(); ?>
<div id="project_listing_page">
<?php
    global $wpdb;
    $table_name = $wpdb->prefix . "group_thrpy_projects";
    $rows = $wpdb->get_results("SELECT * from $table_name order by short_title");
    $name_arr = array();
    $video_id_arr = array();
    $url_arr = array();
    $additional_param = "&background=1&autoplay=0&mute=1&loop=1&byline=0&title=0";
    foreach($rows as $row){
        if(isset($row->short_title))
        {
            // array_push($name_arr,$row->short_title);
            $video_id = get_video_id($row->video_url);
            // $name_arr[$video_id] = $row->short_title;
            // array_push($name_arr,$row->short_title);
            // array_push($video_id_arr,$video_id);
            $name_arr[$row->id] = $row->short_title;
            $video_id_arr[$row->id] = $video_id;
            // var_dump($video_id."->".$row->short_title);
        }
        if(isset($row->video_url))
        {
            // $video_id = get_video_id($row->video_url);
            if(!array_search($row->video_url,$url_arr)){
                // array_push($url_arr,$video_url);
                $url_arr[$row->id] = $row->video_url;
            }
        }
    }
    // sort($name_arr);
    // var_dump($name_arr);
    // var_dump($name_arr);

    function get_video_id($video_url){
        $ret_val = "default";
        $tmp_arr1 = explode("?",$video_url);
        if(count($tmp_arr1)>0){
            $tmp_arr2 = explode("/",$tmp_arr1[0]);
            $ret_val = $tmp_arr2[count($tmp_arr2)-1];
            if($ret_val=="");
            // var_dump($ret_val);
        }
        return $ret_val;
    }
?>
<div class="div_container">
    <?php
        foreach($name_arr as $key=>$short_name){
        $tmp_arr = explode(" ",$row->short_title);
        $prefix_str = strtolower($tmp_arr[0]);
        echo "<a href='".get_site_url()."/single-work?id=".$key."' data-video='".$video_id_arr[$key]."' id='".$video_id_arr[$key]."' class='dc_project'> $short_name</a>";
    }
    ?>
  <!-- <a href="/rem-ariana-grande" data-video="aime" id="aime" class="dc_project"> Aime Leon Dore </a>
  <a href="/rem-ariana-grande" data-video="ariana" id="ariana" class="dc_project"> Ariana Grande </a>
  <a href="/rem-ariana-grande" data-video="madewell" id="chloe_fineman" class="dc_project"> Chloe Fineman </a>  
  <a href="/rem-ariana-grande" data-video="zegna" id="gabriel" class="dc_project"> Gabriel-Kane Day-Lewis </a>  
  <a href="/rem-ariana-grande" data-video="madewell" id="madewell" class="dc_project"> Madewell </a>
  <a href="/rem-ariana-grande" data-video="madhappy_aspen" id="madhappy_aspen" class="dc_project"> Madhappy x Aspen </a>
  <a href="/rem-ariana-grande" data-video="madhappy_laker" id="madhappy-laker" class="dc_project"> Madhappy x Lakers </a>
  <a href="/rem-ariana-grande" data-video="nyc" id="nyc" class="dc_project"> NYC Go </a>
  <a href="/rem-ariana-grande" data-video="aime" id="porsche" class="dc_project"> Porsche </a>  
  <a href="/rem-ariana-grande" data-video="ariana" id="rem_beauty" class="dc_project"> REM Beauty </a>  
  <a href="/rem-ariana-grande" data-video="saysh" id="saysh" class="dc_project"> Saysh </a>
  <a href="/rem-ariana-grande" data-video="zegna" id="zegna" class="dc_project"> Zegna </a> -->
</div>
<div class="div_video">
    <?php
    foreach($url_arr as $url){
        echo "<iframe class='background-video' id='background-video_".get_video_id($url)."' title='background' src='".$url.$additional_param."' frameborder='0'></iframe>";
    }
    ?>
<!-- <iframe class="background-video" id="background-video_saysh" title="background" src="https://player.vimeo.com/video/604959402?h=c0b96a2ca2&background=1&autoplay=0&mute=1&loop=1&byline=0&title=0" frameborder="0"></iframe>
<iframe class="background-video" id="background-video_ariana" title="background" src="https://www.youtube.com/embed/gvjsx8PZVkI?background=1&autoplay=0&loop=1&mute=1&controls=0" frameborder="0"></iframe>
<iframe class="background-video" id="background-video_zegna" title="background" src="https://player.vimeo.com/video/642483920?h=d241b98d12&background=1&autoplay=0&mute=1&loop=1&byline=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<iframe class="background-video" id="background-video_madewell" title="background" src="https://player.vimeo.com/video/604973415?h=9fd2b1c44f&background=1&autoplay=0&mute=1&loop=1&byline=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<iframe class="background-video" id="background-video_aime" title="background" src="https://player.vimeo.com/video/640028317?h=117ba3b2cf&background=1&autoplay=0&loop=1&mute=1&byline=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<iframe class="background-video" id="background-video_nyc" title="background" src="https://player.vimeo.com/video/584990676?h=f7761a90a5&background=1&autoplay=0&loop=1&mute=1&byline=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<iframe class="background-video" id="background-video_madhappy_laker" title="background" src="https://player.vimeo.com/video/604959142?h=61e1ff8846&background=1&autoplay=0&loop=1&mute=1&byline=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<iframe class="background-video" id="background-video_madhappy_aspen" title="background" src="https://player.vimeo.com/video/654655382?h=79f65f9dea&background=1&autoplay=0&loop=1&mute=1&byline=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> -->
</div>
</div>

<?php get_footer(); ?>
<script>

</script>