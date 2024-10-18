<?php
/*
Plugin Name: Maintenance Mode
Description: Closing site for users
Version: 1.4
Author: n3oblog
*/

function maintenance_mode() {
    if (get_option('maintenance_mode_enabled') == '1' && !current_user_can('manage_options') && !is_user_logged_in()) {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Retry-After: 3600');
        
        echo '<!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Технические работы</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    padding: 50px;
                    background-color: #f2f2f2;
                }
                .message {
                    display: inline-block;
                    padding: 20px;
                    background: #fff;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #333;
                }
            </style>
        </head>
        <body>
            <div class="message">
                <h1>Temporarily Unavailable. Maintenance Work</h1>
            </div>
        </body>
        </html>';
        exit();
    }
}

add_action('template_redirect', 'maintenance_mode');

function maintenance_mode_menu() {
    add_menu_page(
        'Maintenance Mode',        
        'Maintenance Mode',                    
        'manage_options',                 
        'maintenance-mode-settings',      
        'maintenance_mode_settings_page', 
        'dashicons-lock',          
        200                              
    );
}
add_action('admin_menu', 'maintenance_mode_menu');

function maintenance_mode_settings_page() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $enabled = isset($_POST['maintenance_mode_enabled']) ? '1' : '0';
        update_option('maintenance_mode_enabled', $enabled);
        echo '<div class="updated"><p>Updated.</p></div>';
    }

    $is_enabled = get_option('maintenance_mode_enabled', '0');
    ?>
    <div class="wrap">
        <h1>Maintenance Mode Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Maintenance Mode</th>
                    <td>
                        <input type="checkbox" name="maintenance_mode_enabled" value="1" <?php checked('1', $is_enabled, true); ?> />
                        <label for="maintenance_mode_enabled">Tick to enable maintenance mode</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
