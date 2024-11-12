<?php
/* 
Plugin Name: Form-Contact
Author: Thành Đạt 21211TT2002 =))
Version: 1.0.1 
*/
?>

<head>
    <!-- Thêm FontAwesome để sử dụng các biểu tượng (icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    /* Nút nhỏ ở góc dưới bên phải */
    #contact-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    #contact-button i {
        font-size: 24px;
    }

    /* Form nổi trên trang */
    .form-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 350px;
        padding: 25px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
    }

    .form-popup h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    /* Form fields */
    .form-popup label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-popup input[type="text"],
    .form-popup input[type="tel"],
    .form-popup input[type="email"],
    .form-popup textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        background-color: #f9f9f9;
    }

    .form-popup textarea {
        resize: vertical;
        height: 80px;
    }

    /* Nút gửi trong form */
    .form-popup button[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-popup button[type="submit"]:hover {
        background-color: #218838;
    }

    /* Nút đóng form */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }

    /* Lớp phủ làm mờ nền */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
    }

    /* CSS cho alert */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        position: relative;
        font-size: 16px;
        text-align: center;
    }

    .alert.alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert.alert-error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert .icon {
        font-size: 18px;
        margin-right: 10px;
    }

    .alert .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #000;
    }
</style>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

add_shortcode("form_contact", "show_form_contact");
add_action('admin_menu', 'form_contact_add_admin_menu');

function form_contact_add_admin_menu()
{
    add_menu_page(
        'Cài đặt Form Contact',     // Tiêu đề trang cài đặt
        'Form Contact',             // Tiêu đề menu
        'manage_options',           // Khả năng (quyền) cần có để truy cập menu
        'form-contact-settings',    // Slug của menu
        'form_contact_settings_page', // Hàm hiển thị nội dung trang cài đặt
        'dashicons-email',          // Biểu tượng của menu
        81                          // Vị trí của menu
    );
}
function form_contact_settings_page()
{
    // Kiểm tra quyền truy cập
    if (!current_user_can('manage_options')) {
        return;
    }

    // Xử lý lưu cài đặt khi form được submit
    if (isset($_POST['form_contact_save_settings'])) {
        check_admin_referer('form_contact_save_settings_verify');

        update_option('form_contact_email_recipient', sanitize_email($_POST['form_contact_email_recipient']));
        update_option('form_contact_smtp_host', sanitize_text_field($_POST['form_contact_smtp_host']));
        update_option('form_contact_smtp_port', intval($_POST['form_contact_smtp_port']));
        update_option('form_contact_smtp_username', sanitize_text_field($_POST['form_contact_smtp_username']));
        update_option('form_contact_smtp_password', sanitize_text_field($_POST['form_contact_smtp_password']));
        update_option('form_contact_smtp_encryption', sanitize_text_field($_POST['form_contact_smtp_encryption']));

        echo '<div class="updated"><p>Cài đặt đã được lưu.</p></div>';
    }

    // Lấy giá trị cài đặt hiện tại
    $email_recipient = get_option('form_contact_email_recipient', get_option('admin_email'));
    $smtp_host = get_option('form_contact_smtp_host', 'smtp.gmail.com');
    $smtp_port = get_option('form_contact_smtp_port', 587);
    $smtp_username = get_option('form_contact_smtp_username', '');
    $smtp_password = get_option('form_contact_smtp_password', '');
    $smtp_encryption = get_option('form_contact_smtp_encryption', 'tls');

    // Hiển thị form cài đặt
?>
    <div class="wrap">
        <h1>Cài đặt Form Contact</h1>
        <form method="post" action="">
            <?php wp_nonce_field('form_contact_save_settings_verify'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="form_contact_email_recipient">Email nhận thông tin:</label></th>
                    <td><input name="form_contact_email_recipient" type="email" id="form_contact_email_recipient" value="<?php echo esc_attr($email_recipient); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th colspan="2">
                        <h2>Cấu hình SMTP</h2>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><label for="form_contact_smtp_host">SMTP Host:</label></th>
                    <td><input name="form_contact_smtp_host" type="text" id="form_contact_smtp_host" value="<?php echo esc_attr($smtp_host); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="form_contact_smtp_port">SMTP Port:</label></th>
                    <td><input name="form_contact_smtp_port" type="number" id="form_contact_smtp_port" value="<?php echo esc_attr($smtp_port); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="form_contact_smtp_username">SMTP Username:</label></th>
                    <td><input name="form_contact_smtp_username" type="text" id="form_contact_smtp_username" value="<?php echo esc_attr($smtp_username); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="form_contact_smtp_password">SMTP Password:</label></th>
                    <td><input name="form_contact_smtp_password" type="password" id="form_contact_smtp_password" value="<?php echo esc_attr($smtp_password); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="form_contact_smtp_encryption">SMTP Encryption:</label></th>
                    <td>
                        <select name="form_contact_smtp_encryption" id="form_contact_smtp_encryption">
                            <option value="tls" <?php selected($smtp_encryption, 'tls'); ?>>TLS</option>
                            <option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>>SSL</option>
                            <option value="" <?php selected($smtp_encryption, ''); ?>>None</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button('Lưu cài đặt', 'primary', 'form_contact_save_settings'); ?>
        </form>
    </div>
<?php
}

function show_form_contact()
{
    ob_start();
    if (isset($_POST['submit'])) {
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : "";
        $address = isset($_POST['address']) ? $_POST['address'] : "";
        $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $message = isset($_POST['message']) ? $_POST['message'] : "";
        $subject = isset($_POST['subject']) ? $_POST['subject'] : ""; // Lấy giá trị tiêu đề

        if ($fullname == "" || $phone == "" || $message == "" || $subject == "") {
            echo "<div class='alert alert-error'>
                    <span class='icon'>✖</span> 
                    Xin hãy nhập đầy đủ thông tin.
                    <button class='close-btn' onclick='this.parentElement.style.display=\"none\";'>&times;</button>
                </div>";
        } else {
            $to = get_option('form_contact_email_recipient');
            if (!$to) {
                echo "<div class='alert alert-error'>
                        <span class='icon'>✖</span> 
                        Không tìm thấy địa chỉ email của admin.
                        <button class='close-btn' onclick='this.parentElement.style.display=\"none\";'>&times;</button>
                    </div>";
            }

            $body = '
            <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 2px solid #eaeaea; border-radius: 10px; background-color: #fff; font-family: Arial, sans-serif; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h2 style="text-align: center; color: #007bff;">Thông tin liên hệ mới</h2>
                <p style="font-size: 16px; line-height: 1.6; color: #333;">
                    <strong>Họ và tên:</strong> ' . $fullname . '<br>
                    <strong>Số điện thoại:</strong> ' . $phone . '<br>
                    <strong>Email:</strong> ' . $email . '<br>
                    <strong>Nội dung:</strong> ' . $message . '
                </p>
            </div>';


            // Gửi mail qua SMTP và sử dụng tiêu đề người dùng nhập
            if (send_smtp_email($to, $subject, $body, $email, $fullname)) {
                echo "<div class='alert alert-success'>
                        <span class='icon'>✔</span> 
                        Cảm ơn bạn đã để lại thông tin!
                        <button class='close-btn' onclick='this.parentElement.style.display=\"none\";'>&times;</button>
                    </div>";
            } else {
                echo "<div class='alert alert-error'>
                        <span class='icon'>✖</span> 
                        Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.
                        <button class='close-btn' onclick='this.parentElement.style.display=\"none\";'>&times;</button>
                    </div>";
            }
        }
    }


?>
    <!-- Nút nhỏ ở góc dưới bên phải -->
    <div id="contact-button">
        <i class="fas fa-envelope"></i>
    </div>

    <!-- Form popup -->
    <div class="form-popup" id="contactForm">
        <button class="close-btn" id="closeForm">&times;</button>
        <h2>Liên hệ</h2>
        <form method="POST">
            <label for="fullname">Họ và tên:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Số điện thoại:</label>
            <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="subject">Tiêu đề:</label>

            <input type="text" id="subject" name="subject" required>
            <label for="message">Nội dung:</label>

            <textarea id="message" name="message" required></textarea>

            <button type="submit" name="submit">Gửi thông tin</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var contactButton = document.getElementById('contact-button');
            var contactForm = document.getElementById('contactForm');
            var closeButton = document.getElementById('closeForm');
            var overlay = document.createElement('div');

            // Tạo lớp overlay làm mờ nền
            overlay.className = 'overlay';
            document.body.appendChild(overlay);

            // Mở form và hiển thị overlay khi click vào nút nhỏ
            contactButton.addEventListener('click', function() {
                overlay.style.display = 'block'; // Hiển thị lớp phủ mờ
                contactForm.style.display = 'block'; // Hiển thị form
                contactButton.style.display = 'none'; // Ẩn nút liên hệ
            });

            // Đóng form và ẩn overlay khi click vào nút X
            closeButton.addEventListener('click', function() {
                overlay.style.display = 'none'; // Ẩn lớp phủ mờ
                contactForm.style.display = 'none'; // Ẩn form
                contactButton.style.display = 'flex'; // Hiển thị lại nút liên hệ
            });

            // Đóng form khi người dùng click ra ngoài form (trên overlay)
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.style.display = 'none'; // Ẩn lớp phủ mờ
                    contactForm.style.display = 'none'; // Ẩn form
                    contactButton.style.display = 'flex'; // Hiển thị lại nút liên hệ
                }
            });
        });
    </script>

<?php
    return ob_get_clean();
}

/**
 * Hàm gửi email qua SMTP
 */
function send_smtp_email($to, $subject, $body, $from_email, $from_name)
{
    // Tải thư viện PHPMailer từ WordPress
    require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
    require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
    require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = get_option('form_contact_smtp_host', 'smtp.gmail.com');
        $mail->SMTPAuth   = true;
        $mail->Username   = get_option('form_contact_smtp_username', 'your_email@gmail.com');
        $mail->Password   = get_option('form_contact_smtp_password', 'your_email_password');
        $mail->SMTPSecure = get_option('form_contact_smtp_encryption', 'tls');
        $mail->Port       = get_option('form_contact_smtp_port', 587);

        // Cấu hình email người gửi và người nhận
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to);

        // Đặt mã hóa UTF-8 cho email
        $mail->CharSet = 'UTF-8';

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Gửi email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Lỗi gửi mail: {$mail->ErrorInfo}");
        return false;
    }
}

?>