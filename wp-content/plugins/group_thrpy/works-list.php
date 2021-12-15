<?php

function group_thrpy_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/group_thrpy/style-admin.css" rel="stylesheet" />
    <div class="wrap work_list_div">
        <h2>Works</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=group_thrpy_create'); ?>">Add New Project</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "projects";

        $rows = $wpdb->get_results("SELECT * from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <!-- <th class="manage-column ss-list-width">ID</th> -->
                <th class="manage-column ss-list-width">Title</th>
                <th class="manage-column ss-list-width">Description</th>
                <th class="manage-column ss-list-width">Video URL</th>
                <th class="manage-column ss-list-width">CD</th>
                <th class="manage-column ss-list-width">Director</th>
                <th class="manage-column ss-list-width">Executive Producers</th>
                <th class="manage-column ss-list-width">Produced By</th>
                <th class="manage-column ss-list-width">Producer</th>
                <th class="manage-column ss-list-width">Editor</th>
                <th class="manage-column ss-list-width">Director of Photography</th>
                <th class="manage-column ss-list-width">Sound design</th>
                <th class="manage-column ss-list-width">Sound mix/mastering</th>
                <th class="manage-column ss-list-width">Makeup</th>
                <th class="manage-column ss-list-width">Styling</th>
                <th class="manage-column ss-list-width">Hair</th>
                <th class="manage-column ss-list-width">Manicurist</th>
                <th class="manage-column ss-list-width">VFX Supervisor</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->title; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->description; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->video_url; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->cd; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->director; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->executive_producers; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->produced_by; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->producer; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->editor; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->director_of_photography; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->sound_design; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->sound_mix_mastering; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->makeup; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->styling; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->hair; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->manicurist; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->VFX_supervisor; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=group_thrpy_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}