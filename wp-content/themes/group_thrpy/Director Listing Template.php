<?php /* Template Name: Director Listing Page Template */ ?>
<?php get_header(); ?>
<div class="content_div">
    <ul class="direcotr_list">
<?php
    global $wpdb;
    $table_name = $wpdb->prefix . "group_thrpy_directors";
    $rows = $wpdb->get_results("SELECT * from $table_name ");


    $arr_directors = array();
    foreach($rows as $row){
        if($row->name!="")
        {
            echo "<li class='director_item'><a href=''>".$row->name."</a></li>";
        }
    }
    
?>
    </ul>
</div>
<?php get_footer(); ?>
