<?php

function group_thrpy_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "projects";
    $id = $_GET["id"];
    $name = $_POST["name"];
//update
    if (isset($_POST['update'])) {
        $wpdb->update(
                $table_name, //table
                array('name' => $name), //data
                array('ID' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
    }
//delete
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    } else {//selecting value to update	
        $works = $wpdb->get_results($wpdb->prepare("SELECT id,name from $table_name where id=%s", $id));
        foreach ($works as $s) {
            $name = $s->name;
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
                    <tr><th>Name</th><td><input type="text" name="name" value="<?php echo $name; ?>"/></td></tr>
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('&iquest;Est&aacute;s seguro de borrar este elemento?')">
            </form>
        <?php } ?>

    </div>
    <?php
}