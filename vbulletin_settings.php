<?php

// not for directly open
if (! defined('IN_ADMIN'))
{
    exit;
}


//current case
$current_case = g('case', 'str', 'view');

//current template
$stylee = 'admin_vbulletin_settings';

// template variables
$styleePath = dirname(__FILE__);
$action     = basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');

$H_FORM_KEYS	     = kleeja_add_form_key('adm_vbulletin_settings');
$H_FORM_KEYS_GET	 = kleeja_add_form_key_get('adm_vbulletin_settings');


switch ($current_case)
{
    /**
     * show a list of current ftp accounts
     */
    default:
    case 'view':

        break;

    /**
     * test connection
     */
    case 'test':

        if (! kleeja_check_form_key_get('adm_vbulletin_settings'))
        {
            header('HTTP/1.1 405 Method Not Allowed');
            $adminAjaxContent = $lang['INVALID_FORM_KEY'];
        }
        else
        {
            if (! defined('MYSQL_NO_ERRORS'))
            {
                define('MYSQL_NO_ERRORS', true);
            }

            $adminAjaxContent = vbulletin_auth_connect() !== false ? 'done' : 'none';
        }

        break;


    case 'update':

        if (! kleeja_check_form_key('adm_vbulletin_settings', 1000))
        {
            header('HTTP/1.1 405 Method Not Allowed');
            $adminAjaxContent = $lang['INVALID_FORM_KEY'];
        }
        else
        {
            $list  = [
                'vbulletin_intr_enabled',
                'vbulletin_intr_type',
                'vbulletin_intr_path',
                'vbulletin_intr_config_path',
                'vbulletin_intr_version5',
                'vbulletin_intr_encoding',
                'vbulletin_intr_link',
                'vbulletin_intr_db_server',
                'vbulletin_intr_db_port',
                'vbulletin_intr_db_user',
                'vbulletin_intr_db_password',
                'vbulletin_intr_db_name',
                'vbulletin_intr_db_prefix',
            ];

            if(! ip('vbulletin_intr_enabled'))
            {
                $_POST['vbulletin_intr_enabled'] = 0;
            }

            foreach ($list as $item)
            {
                if(! ig('force') && $item == 'vbulletin_intr_enabled')
                {
                     continue;
                }

                update_config($item, p($item, 'str'));
            }

            delete_cache('data_config');

            $adminAjaxContent = 'done';
        }

        break;
}
