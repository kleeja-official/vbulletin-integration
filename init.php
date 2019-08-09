<?php
// kleeja plugin
// developer: kleeja team

// prevent illegal run
if (! defined('IN_PLUGINS_SYSTEM'))
{
    exit;
}


// plugin basic information
$kleeja_plugin['vbulletin_integration']['information'] = [
    // the casual name of this plugin, anything can a human being understands
    'plugin_title' => [
        'en' => 'VBulletin Integration',
        'ar' => 'التكامل مع VBulletin'
    ],
    //settings page, if there is one (what after ? like cp=j_plugins)
    'settings_page' => 'cp=vbulletin_settings',
    // who wrote this plugin?
    'plugin_developer' => 'kleeja.com',
    // this plugin version
    'plugin_version' => '1.0.3',
    // explain what is this plugin, why should i use it?
    'plugin_description' => [
        'en' => 'VBulletin Membership Integration',
        'ar' => 'التكامل مع عضويات VBulletin'
    ],

    // min version of kleeja that's required to run this plugin
    'plugin_kleeja_version_min' => '3.1.0',
    // max version of kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '3.9.9',
    // should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 0
];

//after installation message, you can remove it, it's not requiered
$kleeja_plugin['vbulletin_integration']['first_run']['ar'] = '
يجب ضبط إعدادات الإضافة من خلال صفحة الإضافة لضمان عملها وتفعيلها <br><br>
شكراً لاستخدامك الإضافة، قم بمراسلتنا بالأخطاء عند ظهورها على: <br>
https://github.com/kleeja-official/kleeja/issues
';

$kleeja_plugin['vbulletin_integration']['first_run']['en'] = '
In order for this plugin to works, you need to adjust its settings from its page.<Br><br>
Thank you for using our plugin. If you encounter any bugs and errors, report them on: <br>
https://github.com/kleeja-official/kleeja/issues
';


// plugin installation function
$kleeja_plugin['vbulletin_integration']['install'] = function ($plg_id) {
    //new options
    $options = [
        'vbulletin_intr_enabled' =>
        [
            'value'  => '0',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_type' =>
        [
            'value'  => 'path',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_path' =>
        [
            'value'  => '../vb',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_link' =>
        [
            'value'  => 'http://example.com/vbulletin',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_config_path' =>
        [
            'value'  => '/core/includes/config.php',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_version5' =>
        [
            'value'  => '1',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_encoding' =>
        [
            'value'  => 'utf8',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_db_server' =>
        [
            'value'  => 'localhost',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_db_port' =>
        [
            'value'  => '3306',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_db_user' =>
        [
            'value'  => '',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_db_password' =>
        [
            'value'  => '',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_db_name' =>
        [
            'value'  => 'vbulletin',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
        'vbulletin_intr_db_prefix' =>
        [
            'value'  => '',
            'plg_id' => $plg_id,
            'type'   => 'vb_integration'
        ],
    ];


    add_config_r($options);


    //new language variables
    add_olang([
        'R_VBULLETIN_SETTINGS'  => 'إعدادات VBulletin',
        'VBULLETIN_INTR_ENABLE' => 'تفعيل التكامل مع عضويات VBulletin',
        'VBULLETIN_INTR_TYPE' => 'طريقة التكامل مع VBulletin',
        'VBULLETIN_INTR_VERSION5' => 'نسخة VBulletin هي الخامسة أو أعلى',
        'VBULLETIN_INTR_TYPE_PATH' => 'التكامل عبر مسار المجلدات',
        'VBULLETIN_INTR_TYPE_DB' => 'التكامل عبر الإتصال بقاعدة البيانات',
        'VBULLETIN_INTR_PATH' => 'مسار مجلد VBulletin بالنسبة لمجلد كليجا',
        'VBULLETIN_INTR_LINK' => 'رابط منتدى VBulletin',
        'VBULLETIN_INTR_API_KEY' => 'مفتاح الربط العشوائي',
        'VBULLETIN_INTR_CONFIG_PATH' => 'مسار ملف كونفق داخل مجلد VBulletin',
        'VBULLETIN_INTR_ENCODING' => 'ترميز قاعدة البيانات',
        'VBULLETIN_INTR_DB_SERVER' => 'الخادم',
        'VBULLETIN_INTR_DB_PORT' => 'منفذ الخادم',
        'VBULLETIN_INTR_DB_USER' => 'اسم مستخدم القاعدة',
        'VBULLETIN_INTR_DB_PASSWORD' => 'كلمة مرور القاعدة',
        'VBULLETIN_INTR_DB_NAME' => 'اسم القاعدة',
        'VBULLETIN_INTR_DB_PREFIX' => 'سابقة الجداول',
        'VBULLETIN_INTR_TEST'       => 'فحص التكامل',
        'VBULLETIN_INTR_TEST_NOTE'       => 'لتفعيل التكامل قم بعمل فحص للتكامل.',
        'VBULLETIN_INTR_TEST_NOTE_ERR'       => 'فشل الإتصال، قم بالتأكد من الإعدادات قبل التجربة مرة أخرى!',
        'VBULLETIN_INTR_TEST_NOTE_SUCCESS'    => 'الاتصال ناجح! يمكنك الآن تفعيل التكامل لو أردت ذلك.',
    ],
        'ar',
        $plg_id);

    add_olang([
        'R_VBULLETIN_SETTINGS' => 'VBulletin Intergration',
        'VBULLETIN_INTR_ENABLE' => 'Enable VBulletin membership Integration',
        'VBULLETIN_INTR_TYPE' => 'VBulletin Integration Type',
        'VBULLETIN_INTR_VERSION5' => 'VBulletin version is 5 and up',
        'VBULLETIN_INTR_TYPE_PATH' => 'Relative Path',
        'VBULLETIN_INTR_TYPE_DB' => 'Database Connection',
        'VBULLETIN_INTR_ENCODING' => 'Database char encoding',
        'VBULLETIN_INTR_PATH' => 'VBulletin relative path',
        'VBULLETIN_INTR_LINK' => 'Link to VBulletin forum',
        'VBULLETIN_INTR_API_KEY' => 'Integration generated key',
        'VBULLETIN_INTR_CONFIG_PATH' => 'config.php path relative to VBulletin folder',
        'VBULLETIN_INTR_DB_SERVER' => 'DB Server',
        'VBULLETIN_INTR_DB_PORT' => 'DB Port',
        'VBULLETIN_INTR_DB_USER' => 'DB User',
        'VBULLETIN_INTR_DB_PASSWORD' => 'DB Password',
        'VBULLETIN_INTR_DB_NAME' => 'DB Name',
        'VBULLETIN_INTR_DB_PREFIX' => 'Tables prefix',
        'VBULLETIN_INTR_TEST'       => 'Test Integration',
        'VBULLETIN_INTR_TEST_NOTE'       => 'In order to enable integration, test the integration first.',
        'VBULLETIN_INTR_TEST_NOTE_ERR'       => 'Connection failed! Check settings and test again!',
        'VBULLETIN_INTR_TEST_NOTE_SUCCESS'    => 'Connection succeeded! You can enable integration now if you want.',
    ],
        'en',
        $plg_id);
};


//plugin update function, called if plugin is already installed but version is different than current
$kleeja_plugin['vbulletin_integration']['update'] = function ($old_version, $new_version) {
    // if(version_compare($old_version, '0.5', '<')){
    // 	//... update to 0.5
    // }
    //
    // if(version_compare($old_version, '0.6', '<')){
    // 	//... update to 0.6
    // }

    //you could use update_config, update_olang
};


// plugin uninstalling, function to be called at uninstalling
$kleeja_plugin['vbulletin_integration']['uninstall'] = function ($plg_id) {
    //delete options
    delete_config([
        'vbulletin_intr_enabled',
        'vbulletin_intr_type',
        'vbulletin_intr_path',
        'vbulletin_intr_link',
        'vbulletin_intr_version5',
        'vbulletin_intr_encoding',
        'vbulletin_intr_config_path',
        'vbulletin_intr_db_server',
        'vbulletin_intr_db_port',
        'vbulletin_intr_db_user',
        'vbulletin_intr_db_password',
        'vbulletin_intr_db_name',
        'vbulletin_intr_db_prefix',
    ]);


    //delete language variables
    foreach (['ar', 'en'] as $language)
    {
        delete_olang(null, $language, $plg_id);
    }
};


// plugin functions
$kleeja_plugin['vbulletin_integration']['functions'] = [
    //add to admin menu
    'begin_admin_page' => function ($args)
    {
        $adm_extensions = $args['adm_extensions'];
        $ext_icons = $args['ext_icons'];

        $adm_extensions[] = 'vbulletin_settings';
        $ext_icons['vbulletin_settings'] = 'users';
        return compact('adm_extensions', 'ext_icons');
    },
    //add as admin page to reach when click on admin menu item we added.
    'not_exists_vbulletin_settings' => function()
    {
        $include_alternative = dirname(__FILE__) . '/vbulletin_settings.php';

        return compact('include_alternative');
    },
    'data_func_usr_class' => function ($args) {
        global $config;

        if (defined('DISABLE_INTR') || $config['vbulletin_intr_enabled'] != 1)
        {
            return;
        }

        $return_now = true;

        $login_status = vbulletin_auth_login($args['name'], $args['pass'], $args['hashed'], $args['expire'], $args['loginadm']);

        return compact('return_now', 'login_status');
    },

    'auth_func_usr_class' => function ($args) {
        global $config;

        if (defined('DISABLE_INTR') || $config['vbulletin_intr_enabled'] != 1)
        {
            return;
        }

        $return_now = true;

        $auth_status = vbulletin_auth_username($args['user_id']);

        return compact('return_now', 'auth_status');
    },

    'login_before_submit' => function($args) {
        if ($args['config']['vbulletin_intr_enabled'] == 1) 
        {
            $args['forget_pass_link'] = rtrim($args['config']['vbulletin_intr_link'], '/').'/lostpw';
            return $args;
        }
    },
    'get_pass_resetpass_link' => function($args) {
        if ($args['config']['vbulletin_intr_enabled'] == 1) 
        {
            $args['forgetpass_link'] = rtrim($args['config']['vbulletin_intr_link'], '/').'/lostpw';
            return $args;
        }
    },
    'register_not_default_sys' => function($args) {
        if ($args['config']['vbulletin_intr_enabled'] == 1) 
        {
            $args['goto_forum_link'] = rtrim($args['config']['vbulletin_intr_link'], '/').'/register';
            return $args;
        }
    },
    'no_submit_profile' => function($args) {
        if ($args['config']['vbulletin_intr_enabled'] == 1) 
        {
            $args['goto_forum_link'] = rtrim($args['config']['vbulletin_intr_link'], '/') . '/settings/profile';
            return $args;
        }
    },
    'end_common' => function($args) {
        if ($args['config']['vbulletin_intr_enabled'] == 1) 
        {
            $args['config']['user_system'] = 'vbulletin';
            return $args;
        }
    },
];

//includes integration functions
include_once __DIR__ . '/vbulletin.php';
