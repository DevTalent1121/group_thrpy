<?php
function group_thrpy_director_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "group_thrpy_directors";
    $id = $_GET["id"];
    
    $name = $_POST["name"];
    $s_id = $_POST["s_id"];
    //update
    if (isset($_POST['update'])) {
        $wpdb->update(
                $table_name, //table
                array(
                    'name' => $name,
                    's_id' => $s_id,
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
        $directors = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id=%s", $id));
        foreach ($directors as $director) {
            $name = $director->name;
            $s_id = $director->s_id;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/group_thrpy/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Director Update/Delete</h2>
        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Director Deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=group_thrpy_directors') ?>">&laquo; Back to Directors</a>

        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>Director Updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=group_thrpy_directors') ?>">&laquo; Back to Directors</a>

        <?php } else { ?>
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
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Confirm to Delete?')">
            </form>
        <?php } ?>

    </div>
    <?php
}