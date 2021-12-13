<?php
if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
        die('Access Denied');
    }
} else {
    die('Access Denied');
}
function add_tag() {
    global $wpdb;
    $lists['published'] = '<input type="radio" name="published" id="published0" value="0" class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" checked="checked" class="inputbox">
							<label for="published1">Yes</label>';
    $lists['required'] = '<input type="radio" name="required" id="required0" value="0" class="inputbox">
							<label for="required0">No</label>
							<input type="radio" name="required" id="required1" value="1" checked="checked" class="inputbox">
							<label for="required1">Yes</label>';
// display function
    html_add_tag($lists);
}
function show_tag() {
    global $wpdb;
    $sort["default_style"] = "manage-column column-autor sortable desc";
    $sort["custom_style"] = 'manage-column column-autor sortable desc';
    $sort["1_or_2"] = 1;
    $where = '';
    $order = '';
    $search_tag = '';
    $sort["sortid_by"] = 'name';
    if (isset($_POST['page_number'])) {
        if ($_POST['asc_or_desc']) {
            $columns = array("id", "name","ordering","required", "published");
			
			if(in_array($_POST['order_by'], $columns )) 
			{
				$sort["sortid_by"]=esc_sql(esc_html(stripslashes($_POST['order_by'])));
			}
			
            if ($_POST['asc_or_desc'] == 1) {
                $sort["custom_style"] = "manage-column column-title sorted asc";
                $sort["1_or_2"] = "2";
                $order = "ORDER BY " . $sort["sortid_by"] . " ASC";
            } else {
                $sort["custom_style"] = "manage-column column-title sorted desc";
                $sort["1_or_2"] = "1";
                $order = "ORDER BY " . $sort["sortid_by"] . " DESC";
            }
        }
        if ($_POST['page_number']) {
            $limit = (esc_sql(esc_html(stripslashes($_POST['page_number']))) - 1) * 20;
        } else {
            $limit = 0;
        }
    } else {
        $limit = 0;
    }
    if (isset($_POST['search_events_by_title'])) {
        $search_tag = esc_sql(esc_html(stripslashes($_POST['search_events_by_title'])));
    } else {
        $search_tag = "";
    }
    if ($search_tag) {
        $where = ' WHERE name LIKE "%' . $search_tag . '%"';
    }
    if (isset($_POST['saveorder'])) {
        $id_order = array();
        if ($_POST['saveorder'] == "save") {
            foreach ($_POST as $key => $order1) {
                if (is_numeric(str_replace("order_", "", $key))) {
                    $id_order[str_replace("order_", "", $key)] = $order1;
                }
            }
        }
        if (isset($id_order)) {
            $my_id_order = array();
            $i = 1;
            $my_key = 0;
            $saved_order = 100000000;
            while ($saved_order != 1000000000000000) {
                $saved_order = 100000000;
                foreach ($id_order as $key => $order1) {
                    if ($saved_order > $order1) {
                        $saved_order = $order1;
                        $my_key = $key;
                    }
                }
                if (isset($id_order[$my_key]) && $id_order[$my_key] == 100000000)
                    break;
                $my_id_order[$my_key] = $i;
                $id_order[$my_key] = 100000000;
                $i++;
            }
        }
        foreach ($my_id_order as $key => $order1) {
            $wpdb->update($wpdb->prefix . 'Spider_Video_Player_tag', array(
                'ordering' => $order1,
                    ), array('id' => str_replace("order_", "", $key)), array('%d')
            );
        }
    }
    if (isset($_POST["oreder_move"]) && $_POST['serch_or_not'] != 'search') {
        $ids = explode(",", esc_sql(esc_html(stripslashes($_POST["oreder_move"]))));
        if($ids[0]!='' && $ids[1]!=''){
        $this_order = $wpdb->get_var($wpdb->prepare("SELECT ordering FROM " . $wpdb->prefix . "Spider_Video_Player_tag WHERE id=%d", $ids[0]));
            $next_order = $wpdb->get_var($wpdb->prepare("SELECT ordering FROM " . $wpdb->prefix . "Spider_Video_Player_tag WHERE id=%d", $ids[1]));
        $wpdb->update($wpdb->prefix . 'Spider_Video_Player_tag', array(
            'ordering' => $next_order,
                ), array('id' => $ids[0]), array('%d')
        );
            $wpdb->update($wpdb->prefix . 'Spider_Video_Player_tag', array(
                'ordering' => $this_order,
                    ), array('id' => $ids[1]), array('%d')
            );
        }
    }
    // get the total number of records
    $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "Spider_Video_Player_tag" . $where;
    $total = $wpdb->get_var($query);
    $pageNav['total'] = $total;
    $pageNav['limit'] = $limit / 20 + 1;
    $query = "SELECT * FROM " . $wpdb->prefix . "Spider_Video_Player_tag" . $where . " " . $order . " " . " LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
    html_show_tag($rows, $pageNav, $sort); // display function
}
function save_tag() {
    global $wpdb;
    $order = $wpdb->get_var("SELECT MAX(ordering) FROM " . $wpdb->prefix . "Spider_Video_Player_tag") + 1;
    $save_or_no = $wpdb->insert($wpdb->prefix . 'Spider_Video_Player_tag', array(
        'id' => NULL,
        'name' => esc_sql(esc_html(stripslashes($_POST["name"]))),
        'required' => esc_sql(esc_html(stripslashes($_POST["required"]))),
        'published' => esc_sql(esc_html(stripslashes($_POST["published"]))),
        'ordering' => $order
            ), array(
        '%d',
        '%s',
        '%d',
        '%d',
        '%d'
            )
    );
    if (!$save_or_no) {
        ?>
        <div class="updated"><p><strong> <?php _e('Error. Please install plugin again'); ?></strong></p></div>
        <?php
        return false;
    }
    ?>
    <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
    <?php
    return true;
}
function edit_tag($id) {
    global $wpdb;
    if (!$id) {
        $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "Spider_Video_Player_tag");
    }
    $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "Spider_Video_Player_tag WHERE `id`=%d", $id);
    $row = $wpdb->get_row($query);
    $published0 = "";
    $published1 = "";
    $required0 = "";
    $required1 = "";
    if (!$row->published) {
        $published0 = 'checked="checked"';
    } else {
        $published1 = 'checked="checked"';
    }
    if (!$row->required) {
        $required0 = 'checked="checked"';
    } else {
        $required1 = 'checked="checked"';
    }
    $lists['published'] = '<input type="radio" name="published" id="published0" value="0" ' . $published0 . ' class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" ' . $published1 . '  class="inputbox">
							<label for="published1">Yes</label>';
    $lists['required'] = '<input type="radio" name="required" id="required0" value="0" ' . $required0 . ' class="inputbox">
							<label for="required0">No</label>
							<input type="radio" name="required" id="required1" value="1" ' . $required1 . ' class="inputbox">
							<label for="required1">Yes</label>';
    // display function 
    html_edit_tag($lists, $row, $id);
}
function remove_tag($id) {
    global $wpdb;
    $sql_remov_tag = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "Spider_Video_Player_tag WHERE id=%d", $id);
    if (!$wpdb->query($sql_remov_tag)) {
        ?>
        <div id="message" class="error"><p>Spider Video Player Tag Not Deleted</p></div>
        <?php
    } else {
        ?>
        <div class="updated"><p><strong><?php _e('Item Deleted.'); ?></strong></p></div>
        <?php
    }
}
function change_tag($id) {
    global $wpdb;
    $published = $wpdb->get_var($wpdb->prepare("SELECT published FROM " . $wpdb->prefix . "Spider_Video_Player_tag WHERE `id`=%d", $id));
    if ($published)
        $published = 0;
    else
        $published = 1;
    $savedd = $wpdb->update($wpdb->prefix . 'Spider_Video_Player_tag', array(
        'published' => $published,
            ), array('id' => $id), array('%d')
    );
    if (!$savedd) {
        ?>
        <div class="error"><p><strong><?php _e(' Please install plugin again'); ?></strong></p></div>
        <?php
        return false;
    }
    ?>
    <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
    <?php
    return true;
}
function required_tag($id) {
    global $wpdb;
    $requerid = $wpdb->get_var($wpdb->prepare("SELECT required FROM " . $wpdb->prefix . "Spider_Video_Player_tag WHERE `id`=%d", $id));
    if ($requerid)
        $requerid = 0;
    else
        $requerid = 1;
    $savedd = $wpdb->update($wpdb->prefix . 'Spider_Video_Player_tag', array(
        'required' => $requerid,
            ), array('id' => $id), array('%d')
    );
    if (!$savedd) {
        ?>
        <div class="error"><p><strong><?php _e(' Please install plugin again'); ?></strong></p></div>
        <?php
        return false;
    }
    ?>
    <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
    <?php
    return true;
}
function apply_tag($id) {
    global $wpdb;
    $save_or_no = $wpdb->update($wpdb->prefix . 'Spider_Video_Player_tag', array(
        'name' => esc_sql(esc_html(stripslashes($_POST["name"]))),
        'required' => esc_sql(esc_html(stripslashes($_POST["required"]))),
        'published' => esc_sql(esc_html(stripslashes($_POST["published"]))),
            ), array('id' => $id), array('%s',
        '%d',
        '%d',
            )
    );
    if (!is_int($save_or_no)) {
        ?>
        <div class="error"><p><strong><?php _e('.- Please install plugin again'); ?></strong></p></div>
        <?php
        return false;
    }
    ?>
    <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
    <?php
    return true;
}
?>