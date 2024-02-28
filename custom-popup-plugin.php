<?php
/*
Plugin Name: Supervised AI Bots
Plugin URI: https://supervised.co/wordpress
Description: Add your Supervised AI chatbots directly into your WordPress website as a chatbot.
Version: 1.0.0
Author: Supervised AI
Author URI: http://supervised.co
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Add settings menu
function custom_popup_menu() {
    add_options_page( 'Supervised Settings', 'Supervised AI Bots', 'manage_options', 'supervised-ai-settings', 'custom_popup_settings_page' );
}
add_action( 'admin_menu', 'custom_popup_menu' );

// Register settings
function custom_popup_register_settings() {
    register_setting( 'custom-popup-settings-group', 'custom_popup_bot_urls' );
}
add_action( 'admin_init', 'custom_popup_register_settings' );

// Settings page callback function
function custom_popup_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Popup Settings</h1>
        
        <h2>Instructions</h2>
        <p>Follow these steps to add bots and use the shortcode:</p>
        <ol>
			<li>Go to <a href="https://open.supervised.co">https://open.supervised.co</a> and create a free account.</li>
			<li>Create a no-code agent and customise it as you want it to be.</li>
			<li>Copy the agent's URL and paste it below</li>
            <li>Enter one agent URL per line in the field below.</li>
            <li>Use the shortcode <code>[custom_popup bot_id="X"]</code> to display the bot on your site, replacing <code>X</code> with the agent ID (starting from 1).</li>
        </ol>
        
        <form method="post" action="options.php">
            <?php settings_fields( 'custom-popup-settings-group' ); ?>
            <?php do_settings_sections( 'custom-popup-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Bot URLs</th>
                    <td>
                        <textarea name="custom_popup_bot_urls" rows="5" cols="50"><?php echo esc_attr( get_option( 'custom_popup_bot_urls' ) ); ?></textarea>
                        <p class="description">Enter one bot URL per line.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Enqueue styles and scripts
function custom_popup_scripts() {
    // Enqueue CSS
    wp_enqueue_style( 'custom-popup-style', plugins_url( 'custom-popup-style.css', __FILE__ ) );

    // Enqueue JavaScript
    wp_enqueue_script( 'custom-popup-script', plugins_url( 'custom-popup-script.js', __FILE__ ), array(), false, true );
}
add_action( 'wp_enqueue_scripts', 'custom_popup_scripts' );

// Add shortcode for popup
function custom_popup_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'bot_id' => '1',
    ), $atts );

    $bot_urls = explode( "\n", get_option( 'custom_popup_bot_urls' ) );
    $bot_url = isset( $bot_urls[ $atts['bot_id'] - 1 ] ) ? trim( $bot_urls[ $atts['bot_id'] - 1 ] ) : '';

    ob_start(); ?>
    <div class="popup-container" id="popup" style="display: none;">
        <div class="popup-body">
            <div class="iframe-container">
                <iframe src="<?php echo esc_url( $bot_url ); ?>" frameborder="0"></iframe>
            </div>
        </div>
        <div class="popup-footer">
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <div class="circle-button" onclick="togglePopup()">?</div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'custom_popup', 'custom_popup_shortcode' );
