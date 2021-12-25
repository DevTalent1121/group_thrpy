<?php

function group_thrpy_directors() {
    // $id = $_POST["id"];
    $s_id = $_POST["s_id"];
    $name = $_POST["name"];
    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "group_thrpy_directors";

        $wpdb->insert(
                $table_name, //table
                array(
                    's_id' => $s_id,
                    'name' => $name,
            ), //data
                array('%s', '%s') //data format			
        );
        $message.="New director inserted";
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/group_thrpy/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Director</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
            <tr>
                    <th class="ss-th-width">Name</th>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">ID</th>
                    <td><input type="text" name="s_id" value="<?php echo $s_id; ?>" class="ss-field-width" /></td>
                </tr>
           </table>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    </div>
    <?php
    group_thrpy_director_list();
}

function group_thrpy_director_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/group_thrpy/style-admin.css" rel="stylesheet" />
    <div class="wrap work_list_div">
        <h2>Directors List</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <!-- <a href="<?php echo admin_url('admin.php?page=group_thrpy_create'); ?>">Add New Project</a> -->
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "group_thrpy_directors";

        $rows = $wpdb->get_results("SELECT * from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <!-- <th class="manage-column ss-list-width">ID</th> -->
                <th class="manage-column ss-list-width">Name</th>
                <th class="manage-column ss-list-width">ID</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->s_id; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=group_thrpy_director_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}