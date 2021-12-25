<?php

function group_thrpy_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "group_thrpy_projects";
    $id = $_GET["id"];
    
    $title = $_POST["title"];
    $short_title = $_POST["short_title"];
    $description = $_POST["description"];
    $video_url = $_POST["video_url"];
    $cd = $_POST["cd"];
    $director = $_POST["director"];
    $executive_producers = $_POST["executive_producers"];
    $produced_by = $_POST["produced_by"];
    $producer = $_POST["producer"];
    $editor = $_POST["editor"];
    $director_of_photography = $_POST["director_of_photography"];
    $sound_design = $_POST["sound_design"];
    $sound_mix_mastering = $_POST["sound_mix_mastering"];
    $makeup = $_POST["makeup"];
    $styling = $_POST["styling"];
    $hair = $_POST["hair"];
    $manicurist = $_POST["manicurist"];
    $VFX_supervisor = $_POST["VFX_supervisor"];
    //update
    if (isset($_POST['update'])) {
        $wpdb->update(
                $table_name, //table
                array(
                    'title' => $title,
                    'short_title' => $short_title,
                    'description' => $description, 
                    'video_url' => $video_url, 
                    'cd' => $cd, 
                    'director' => $director, 
                    'executive_producers' => $executive_producers, 
                    'produced_by' => $produced_by, 
                    'producer' => $producer, 
                    'editor' => $editor, 
                    'director_of_photography' => $director_of_photography, 
                    'sound_design' => $sound_design, 
                    'sound_mix_mastering' => $sound_mix_mastering, 
                    'makeup' => $makeup, 
                    'styling' => $styling, 
                    'hair' => $hair, 
                    'manicurist' => $manicurist, 
                    'VFX_supervisor' => $VFX_supervisor
                     
            ), //data
                array('ID' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
    }
//delete
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    } else {//selecting value to update	
        $works = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id=%s", $id));
        foreach ($works as $s) {
            $name = $s->name;
            $title = $s->title;
            $short_title = $s->short_title;
            $description = $s->description;
            $video_url = $s->video_url;
            $cd = $s->cd;
            $director = $s->director;

            $executive_producers = $s->executive_producers;
            $produced_by = $s->produced_by;
            $producer = $s->producer;
            $editor = $s->editor;
            $director_of_photography = $s->director_of_photography; 
            $sound_design = $s->sound_design; 
            $sound_mix_mastering = $s->sound_mix_mastering; 
            $makeup = $s->makeup; 
            $styling = $s->styling; 
            $hair = $s->hair;
            $manicurist = $s->manicurist; 
            $VFX_supervisor = $s->VFX_supervisor;
}
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/group_thrpy/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>works</h2>

        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>work deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=group_thrpy_list') ?>">&laquo; Back to works list</a>

        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>work updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=group_thrpy_list') ?>">&laquo; Back to works list</a>

        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr>
                        <th class="ss-th-width">Title</th>
                        <td><input type="text" name="title" value="<?php echo $title; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Short Title</th>
                        <td><input type="text" name="short_title" value="<?php echo $short_title; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Description</th>
                        <td><input type="text" name="description" value="<?php echo $description; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Video URL</th>
                        <td><input type="text" name="video_url" value="<?php echo $video_url; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">CD</th>
                        <td><input type="text" name="cd" value="<?php echo $cd; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Director</th>
                        <td>
                            <select name="director" id="director" class="ss-field-width">
                                <option></option>
                                <?php
                                    global $wpdb;
                                    $table_name_director = $wpdb->prefix . "group_thrpy_directors";
                                    $rows = $wpdb->get_results("SELECT * from $table_name_director");
                                    // $arr_directors = array();
                                    foreach($rows as $row){
                                        // $arr_directors[$row->id] = $row->s_id;
                                        if($director==$row->id){
                                            echo "<option selected value='".$row->id."'>".$row->s_id."</option>";
                                        }
                                        else{
                                            echo "<option value='".$row->id."'>".$row->s_id."</option>";
                                        }
                                    }

                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Executive Producers</th>
                        <td><input type="text" name="executive_producers" value="<?php echo $executive_producers; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Produced By</th>
                        <td><input type="text" name="produced_by" value="<?php echo $produced_by; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Producer</th>
                        <td><input type="text" name="producer" value="<?php echo $producer; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Editor</th>
                        <td><input type="text" name="editor" value="<?php echo $editor; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Director of Photography</th>
                        <td><input type="text" name="director_of_photography" value="<?php echo $director_of_photography; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Sound design</th>
                        <td><input type="text" name="sound_design" value="<?php echo $sound_design; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Sound mix/mastering</th>
                        <td><input type="text" name="sound_mix_mastering" value="<?php echo $sound_mix_mastering; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Makeup</th>
                        <td><input type="text" name="makeup" value="<?php echo $makeup; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Styling</th>
                        <td><input type="text" name="styling" value="<?php echo $styling; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Hair</th>
                        <td><input type="text" name="hair" value="<?php echo $hair; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Manicurist</th>
                        <td><input type="text" name="manicurist" value="<?php echo $manicurist; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">VFX Supervisor</th>
                        <td><input type="text" name="VFX_supervisor" value="<?php echo $VFX_supervisor; ?>" class="ss-field-width" /></td>
                    </tr>

                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Confirm to Delete?')">
            </form>
        <?php } ?>

    </div>
    <?php
}