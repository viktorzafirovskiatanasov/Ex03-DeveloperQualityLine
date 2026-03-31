<?php
/*
Plugin Name: Personal Details Manager
Description: A plugin to manage personal details with Add, Update, Remove, Search, and Refresh.
Version: 1.0
Author: Viktor
*/

if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, 'pdm_create_table');

function pdm_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'personal_details';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        mobile VARCHAR(50) NOT NULL,
        email VARCHAR(150) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('admin_menu', 'pdm_admin_menu');

function pdm_admin_menu() {
    add_menu_page(
        'Personal Details Manager',
        'Personal Details',
        'manage_options',
        'pdm-personal-details',
        'pdm_admin_page',
        'dashicons-id',
        20
    );
}

function pdm_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'personal_details';

    $search = '';
    $edit_record = null;

    if (isset($_POST['refresh_records'])) {
        $search = '';
    }

    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);
        if ($delete_id > 0) {
            $wpdb->delete($table_name, array('id' => $delete_id));
            echo '<div class="updated notice"><p>Record deleted successfully.</p></div>';
        }
    }

    if (isset($_POST['add_record'])) {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name  = sanitize_text_field($_POST['last_name']);
        $mobile     = sanitize_text_field($_POST['mobile']);
        $email      = sanitize_email($_POST['email']);

        if ($first_name && $last_name && $mobile && $email) {
            $wpdb->insert(
                $table_name,
                array(
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'mobile'     => $mobile,
                    'email'      => $email
                )
            );
            echo '<div class="updated notice"><p>Record added successfully.</p></div>';
        } else {
            echo '<div class="error notice"><p>All fields are required.</p></div>';
        }
    }

    if (isset($_POST['update_record'])) {
        $record_id  = intval($_POST['record_id']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name  = sanitize_text_field($_POST['last_name']);
        $mobile     = sanitize_text_field($_POST['mobile']);
        $email      = sanitize_email($_POST['email']);

        if ($record_id && $first_name && $last_name && $mobile && $email) {
            $wpdb->update(
                $table_name,
                array(
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'mobile'     => $mobile,
                    'email'      => $email
                ),
                array('id' => $record_id)
            );
            echo '<div class="updated notice"><p>Record updated successfully.</p></div>';
            echo "<script>window.location.href='?page=pdm-personal-details';</script>";
exit;
        } else {
            echo '<div class="error notice"><p>All fields are required for update.</p></div>';
        }
    }

    if (isset($_GET['edit_id'])) {
        $edit_id = intval($_GET['edit_id']);
        if ($edit_id > 0) {
            $edit_record = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $edit_id)
            );
        }
    }

    if (isset($_POST['search_record'])) {
        $search = sanitize_text_field($_POST['search_term']);
    }

    if (!empty($search)) {
        $records = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name
                 WHERE first_name LIKE %s
                 OR last_name LIKE %s
                 OR mobile LIKE %s
                 OR email LIKE %s
                 ORDER BY id DESC",
                '%' . $search . '%',
                '%' . $search . '%',
                '%' . $search . '%',
                '%' . $search . '%'
            )
        );
    } else {
        $records = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
    }
    ?>
    <div class="wrap">
        <h1>Personal Details Manager</h1>

        <form method="post">
            <input type="hidden" name="record_id" value="<?php echo $edit_record ? esc_attr($edit_record->id) : ''; ?>">

            <table class="form-table">
                <tr>
                    <th><label for="first_name">First Name</label></th>
                    <td>
                        <input type="text" name="first_name" id="first_name" class="regular-text"
                               value="<?php echo $edit_record ? esc_attr($edit_record->first_name) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <th><label for="last_name">Last Name</label></th>
                    <td>
                        <input type="text" name="last_name" id="last_name" class="regular-text"
                               value="<?php echo $edit_record ? esc_attr($edit_record->last_name) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <th><label for="mobile">Mobile</label></th>
                    <td>
                        <input type="text" name="mobile" id="mobile" class="regular-text"
                               value="<?php echo $edit_record ? esc_attr($edit_record->mobile) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <th><label for="email">Email</label></th>
                    <td>
                        <input type="email" name="email" id="email" class="regular-text"
                               value="<?php echo $edit_record ? esc_attr($edit_record->email) : ''; ?>">
                    </td>
                </tr>
            </table>

            <p>
                <?php if ($edit_record) : ?>
                    <input type="submit" name="update_record" class="button button-primary" value="Update">
                <?php else : ?>
                    <input type="submit" name="add_record" class="button button-primary" value="Add">
                <?php endif; ?>

                <input type="submit" name="refresh_records" class="button" value="Refresh">
            </p>
        </form>

        <form method="post" style="margin-top:20px;">
            <input type="text" name="search_term" value="<?php echo esc_attr($search); ?>" placeholder="Search records">
            <input type="submit" name="search_record" class="button" value="Search">
            <input type="submit" name="refresh_records" class="button" value="Refresh">
        </form>

        <h2 style="margin-top:20px;">Saved Records</h2>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)) : ?>
                    <?php foreach ($records as $record) : ?>
                        <tr>
                            <td><?php echo esc_html($record->id); ?></td>
                            <td><?php echo esc_html($record->first_name); ?></td>
                            <td><?php echo esc_html($record->last_name); ?></td>
                            <td><?php echo esc_html($record->mobile); ?></td>
                            <td><?php echo esc_html($record->email); ?></td>
                            <td>
                                <a href="?page=pdm-personal-details&edit_id=<?php echo esc_attr($record->id); ?>" class="button">Edit</a>
                                <a href="?page=pdm-personal-details&delete_id=<?php echo esc_attr($record->id); ?>"
                                   class="button button-secondary"
                                   onclick="return confirm('Are you sure you want to delete this record?');">
                                   Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}