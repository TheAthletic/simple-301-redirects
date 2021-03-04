<?php
namespace Simple301Redirects;

class Helper {
    /**
	 * Check Supported Post type for admin page and plugin main settings page
	 *
	 * @return bool
	 */

	public static function plugin_page_hook_suffix($hook)
	{
		if ($hook == 'settings_page_301options') {
			return true;
		}
		return false;
	}
    public static function str_ireplace($search,$replace,$subject){
        $token = chr(1);
        $haystack = strtolower($subject);
        $needle = strtolower($search);
        while (($pos=strpos($haystack,$needle))!==FALSE){
            $subject = substr_replace($subject,$token,$pos,strlen($search));
            $haystack = substr_replace($haystack,$token,$pos,strlen($search));
        }
        $subject = str_replace($token,$replace,$subject);
        return $subject;
    }

    public static function get_remote_plugin_data($slug = '')
    {
        if (empty($slug)) {
            return new \WP_Error('empty_arg', __('Argument should not be empty.'));
        }

        $response = wp_remote_post(
            'http://api.wordpress.org/plugins/info/1.0/',
            [
                'body' => [
                    'action' => 'plugin_information',
                    'request' => serialize((object) [
                        'slug' => $slug,
                        'fields' => [
                            'version' => false,
                        ],
                    ]),
                ],
            ]
        );

        if (is_wp_error($response)) {
            return $response;
        }

        return unserialize(wp_remote_retrieve_body($response));
    }

    public static function install_plugin($slug = '', $active = true)
    {
        if (empty($slug)) {
            return new \WP_Error('empty_arg', __('Argument should not be empty.'));
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

        $plugin_data = self::get_remote_plugin_data($slug);

        if (is_wp_error($plugin_data)) {
            return $plugin_data;
        }

        $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());

        // install plugin
        $install = $upgrader->install($plugin_data->download_link);

        if (is_wp_error($install)) {
            return $install;
        }

        // activate plugin
        if ($install === true && $active) {
            $active = activate_plugin($upgrader->plugin_info(), '', false, true);

            if (is_wp_error($active)) {
                return $active;
            }

            return $active === null;
        }

        return $install;
    }
}