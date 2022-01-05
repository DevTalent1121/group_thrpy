<?php /* Template Name: Single Work Page Template */ ?>
<?php get_header(); ?>

<?php

global $wpdb;
$table_name = $wpdb->prefix . "projects";
$work_id = $_GET['id'];
if(isset($_GET['id']))
{
    $rows = $wpdb->get_results("SELECT * from $table_name where id=$work_id");
    if(count($rows)>0)
    {
        $row = $rows[0];
        echo "<div class='video_div'>";
        echo "<iframe class='video_box' src='".$row->video_url."' frameborder='0' width='450' height='255'></iframe><br />";
        echo "<span>".$row->title."</span>";
        echo "</div>";
        echo "<div class='video_content'>";
        if($row->description!="")
        {
            echo "<span>".$row->description."</span><br />";
        }
        if($row->cd!="")
        {
            echo "<span>CD: ".$row->cd."</span><br />";
        }
        if($row->director!="")
        {
            echo "<span>Director: ".$row->director."</span><br />";
        }
        if($row->executive_producer!="")
        {
            echo "<span>Executive Producers: ".$row->executive_producer."</span><br />";
        }
        if($row->produced_by!="")
        {
            echo "<span>Produced By: ".$row->produced_by."</span><br />";
        }
        if($row->producer!="")
        {
            echo "<span>Producer: ".$row->producer."</span><br />";
        }
        if($row->editor!="")
        {
            echo "<span>Editor: ".$row->editor."</span><br />";
        }
        if($row->director_of_photography!="")
        {
            echo "<span>Director of Photography: ".$row->director_of_photography."</span><br />";
        }
        if($row->sound_design!="")
        {
            echo "<span>Sound Design: ".$row->sound_design."</span><br />";
        }
        if($row->sound_mix_mastering!="")
        {
            echo "<span>Sound mix/mastering:".$row->sound_mix_mastering."</span><br />";
        }
        if($row->makeup!="")
        {
            echo "<span>Makeup: ".$row->makeup."</span><br />";
        }
        if($row->styling!="")
        {
            echo "<span>Styling: ".$row->styling."</span><br />";
        }
        if($row->hair!="")
        {
            echo "<span>Hair: ".$row->hair."</span><br />";
        }
        if($row->manicurist!="")
        {
            echo "<span>Manicurist: ".$row->manicurist."</span><br />";
        }
        if($row->VFX_supervisor!="")
        {
            echo "<span>VFX Supervisor: ".$row->VFX_supervisor."</span><br />";
        }
        echo "</div>";
    }

}
?>

<?php get_footer(); ?>
