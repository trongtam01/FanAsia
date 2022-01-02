<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'AsiaFan' );

/** Username của database */
define( 'DB_USER', 'AsiaFan' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', 'khongcho3' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '$!v;Ux.QoO q=Gou{UX*FwJawmqYZ%TB5Fu$Cp&o-D6IhvW2<(?yNxJ4&g(L3{tg' );
define( 'SECURE_AUTH_KEY',  'T`bN7#wR0)*i$@P:W W,zMy/n%_;_x{TV@{ZKMIBQA ~`i5G FcPDkQ7x;E+e!A]' );
define( 'LOGGED_IN_KEY',    'n_uS_gTNuO/=z8[ya(4If|t:V&~&f#dw]c`5Omi^m 3GuLj0{~,#I++#Dd9T~sM/' );
define( 'NONCE_KEY',        'qb*x6(=CPYkP EE8-)*/qPD!o+C[IH5`2|i.%c1|[(PUe1)(j&&fSg4g^:elfV l' );
define( 'AUTH_SALT',        '}uw0Exw4D%Nh8B!)/[s$]_=~n~!>=]=*XJ~;.|qqs]$0NM?RBHta{q+34R!an`9`' );
define( 'SECURE_AUTH_SALT', 'U{#&ZJ6Jjzz7[ E?gz9HJqZe>_p:4dQ<&Q[WzSw}Ja3Cmdo>4ClfT5@~<d_#vBGM' );
define( 'LOGGED_IN_SALT',   '%<t9!5,cyGuxh(>L()}_h,5YjAA+NMcKr6K#AEc76uzOk%R2]s!zD6T}uq`%4p1[' );
define( 'NONCE_SALT',       '$wi![6cPC>il7&*y+R2rsTGW51l;Oy}pds$Y)=M[xFZ)8tAx-ie&,a?k[rz[2+Jc' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
