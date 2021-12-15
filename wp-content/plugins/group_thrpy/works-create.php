<?php

function group_thrpy_create() {
    // $id = $_POST["id"];
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
    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "projects";

        $wpdb->insert(
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
                array('%s', '%s') //data format			
        );
        $message.="Work inserted";
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/group_thrpy/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New work</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <p>Auto increase ID</p>
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
                    <td><input type="text" name="director" value="<?php echo $director; ?>" class="ss-field-width" /></td>
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
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    </div>
    <?php
}