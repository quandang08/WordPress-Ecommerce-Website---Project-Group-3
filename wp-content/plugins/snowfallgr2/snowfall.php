<?php
/*
Plugin Name: Snowfall Plugin
Description: A plugin to add snowfall effect with adjustable settings.
Version: 1.0
Author: Your Name
*/

// Hook vào admin_menu để thêm trang cài đặt vào admin dashboard
add_action('admin_menu', 'snowfall_add_admin_menu');

// Hook vào admin_init để đăng ký các tùy chọn cài đặt
add_action('admin_init', 'snowfall_settings_init');

// Thêm menu vào admin dashboard
function snowfall_add_admin_menu()
{
    add_menu_page(
        'Snowfall Settings',          // Tiêu đề trang
        'Snowfall Settings',          // Tiêu đề menu
        'manage_options',             // Quyền cần thiết để xem trang
        'snowfall-settings',          // Slug của trangi
        'snowfall_options_page',      // Hàm hiển thị nội dung trang
        'dashicons-snow'              // Icon menu
    );
}

// Khởi tạo các tùy chọn cài đặt
function snowfall_settings_init()
{
    register_setting('snowfallSettingsGroup', 'snowflake_count');
    register_setting('snowfallSettingsGroup', 'snowflake_speed');
    register_setting('snowfallSettingsGroup', 'snowflake_color');

    add_settings_section(
        'snowfallSettingsSection',
        __('Snowfall Settings', 'snowfall'),
        null,
        'snowfall-settings'
    );

    add_settings_field(
        'snowflake_count',
        __('Number of Snowflakes', 'snowfall'),
        'snowflake_count_render',
        'snowfall-settings',
        'snowfallSettingsSection'
    );

    add_settings_field(
        'snowflake_speed',
        __('Snowflake Speed', 'snowfall'),
        'snowflake_speed_render',
        'snowfall-settings',
        'snowfallSettingsSection'
    );

    add_settings_field(
        'snowflake_color',
        __('Snowflake Color', 'snowfall'),
        'snowflake_color_render',
        'snowfall-settings',
        'snowfallSettingsSection'
    );
}

// Form input cho số lượng tuyết rơi
function snowflake_count_render()
{
    $value = get_option('snowflake_count', '100');
    echo '<input type="number" name="snowflake_count" value="' . esc_attr($value) . '" min="10" max="500">';
}

// Form input cho tốc độ tuyết rơi
function snowflake_speed_render()
{
    $value = get_option('snowflake_speed', '3');
    echo '<input type="range" name="snowflake_speed" value="' . esc_attr($value) . '" min="1" max="10">';
}

// Form input cho màu sắc tuyết rơi
function snowflake_color_render()
{
    $value = get_option('snowflake_color', '#ffffff');
    echo '<input type="color" name="snowflake_color" value="' . esc_attr($value) . '">';
}

// Hàm hiển thị trang cài đặt trong admin dashboard
function snowfall_options_page()
{
?>
    <form action="options.php" method="post">
        <h1><?php esc_html_e('Snowfall Settings', 'snowfall'); ?></h1>
        <?php
        settings_fields('snowfallSettingsGroup');
        do_settings_sections('snowfall-settings');
        submit_button();
        ?>
    </form>
<?php
}

// Thêm JavaScript tuyết rơi vào front-end
add_action('wp_enqueue_scripts', 'snowfall_enqueue_scripts');
function snowfall_enqueue_scripts()
{
    wp_enqueue_script('snowfall-script', plugin_dir_url(__FILE__) . 'snowfall.js');

    // Truyền các tùy chọn từ PHP sang JavaScript
    wp_localize_script('snowfall-script', 'snowfallSettings', array(
        'count' => get_option('snowflake_count', '100'),
        'speed' => get_option('snowflake_speed', '3'),
        'color' => get_option('snowflake_color', '#ffffff'),
    ));
}
// Tạo shortcode để hiển thị hiệu ứng tuyết rơi
function snowfall_shortcode()
{
    // Output HTML để chứa hiệu ứng tuyết
    ob_start();
?>
    <div id="snowfall-container" style="position: fixed;top: 0;left: 0;width: 100%;height: 100vh;"></div>

<?php
    return ob_get_clean(); // Trả về HTML chứa snowfall container
}
add_shortcode('snowfall', 'snowfall_shortcode'); // Đăng ký shortcode [snowfall]

// Đảm bảo rằng JavaScript tuyết rơi chỉ chạy khi shortcode được sử dụng
add_action('wp_footer', 'snowfall_enqueue_if_shortcode');

function snowfall_enqueue_if_shortcode()
{
    global $post;

    // Kiểm tra nếu shortcode [snowfall] tồn tại trong nội dung trang
    if (has_shortcode($post->post_content, 'snowfall')) {
        wp_enqueue_script('snowfall-script', plugin_dir_url(__FILE__) . 'snowfall.js', array(), null, true);

        // Truyền các tùy chọn từ PHP sang JavaScript
        wp_localize_script('snowfall-script', 'snowfallSettings', array(
            'count' => get_option('snowflake_count', '100'),
            'speed' => get_option('snowflake_speed', '3'),
            'color' => get_option('snowflake_color', '#ffffff'),
        ));
    }
}
