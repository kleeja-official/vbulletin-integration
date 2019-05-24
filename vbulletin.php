<?php
/**
*
* @package auth
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//no for directly open
if (! defined('IN_COMMON'))
{
    exit;
}


if (! function_exists('vbulletin_auth_login'))
{
    function vbulletin_auth_connect()
    {
        global $config;

        if ($config['vbulletin_intr_type'] == 'path')
        {
            //check for last slash
            $kleeja_path = DIRECTORY_SEPARATOR == '/' ? __DIR__ : str_replace(DIRECTORY_SEPARATOR, '/', __DIR__);
            $kleeja_path = str_replace('/plugins/vbulletin_integration', '', $kleeja_path) . '/';
            $vbulletin_path        = $kleeja_path . rtrim($config['vbulletin_intr_path'], '/');
            $vbulletin_config_path =  $vbulletin_path . $config['vbulletin_intr_config_path'];

            if (! file_exists($vbulletin_config_path))
            {
                return false;
            }

            $kleeja_config = $config;
            unset($config);


            //get some useful data from vb config file
            include $vbulletin_config_path;

            $forum_srv      = $config['MasterServer']['servername'];
            $forum_db       = $config['Database']['dbname'];
            $forum_user     = $config['MasterServer']['username'];
            $forum_pass     = $config['MasterServer']['password'];
            $forum_prefix   = $config['Database']['tableprefix'];

            if ($config['MasterServer']['port'] != 3306)
            {
                $forum_srv .= ':' . $config['MasterServer']['port'];
            }

            //some people change their db charset
            if (isset($config['Mysqli']['charset']))
            {
                $forum_db_charset = $config['Mysqli']['charset'];
            }

            unset($config);
            $config = $kleeja_config;
        }
        else
        {
            //
            //custom config data
            //
            $forum_srv       = $config['vbulletin_intr_db_server'];
            $forum_db        = $config['vbulletin_intr_db_name'];
            $forum_user      = $config['vbulletin_intr_db_user'];
            $forum_pass      = $config['vbulletin_intr_db_password'];
            $forum_prefix    = $config['vbulletin_intr_db_prefix'];

            if ($config['vbulletin_intr_db_port'] != 3306)
            {
                $forum_srv .= ':' . $config['vbulletin_intr_db_port'];
            }

            $forum_db_charset = $config['vbulletin_intr_encoding'];
        }

        if (empty($forum_srv) || empty($forum_user) || empty($forum_db))
        {
            return false;
        }

        $SQLVB = new KleejaDatabase($forum_srv, $forum_user, $forum_pass, $forum_db, $forum_prefix);

        if (! $SQLVB->is_connected())
        {
            return false;
        }


        if (isset($forum_db_charset))
        {
            $SQLVB->set_names($forum_db_charset);
        }
        else
        {
            // $SQLVB->set_names('latin1');
        }

        return $SQLVB;
    }
}

if (! function_exists('vbulletin_auth_login'))
{
    function vbulletin_auth_login($name, $pass, $hashed = false, $expire, $loginadm = false, $return_username = false)
    {
        global $config, $usrcp, $userinfo;

        $SQLVB = vbulletin_auth_connect();

        if ($SQLVB == false)
        {
            return false;
        }

        $query_salt = [
            'SELECT'       => $hashed ? '*' : ($config['vbulletin_intr_version5'] == 1 ? 'token' : 'salt'),
            'FROM'         => "`{$SQLVB->dbprefix}user`",
        ];

        $query_salt['WHERE'] = $hashed ? 'userid=' . intval($name) . ' AND ' . ($config['vbulletin_intr_version5'] == 1 ? 'token' : 'password') . "='" . $SQLVB->real_escape($pass) . "' AND usergroupid != '8'" :  "username='" . $SQLVB->real_escape($name) . "' AND usergroupid != '8'";

        //if return only name let's ignore the above
        if ($return_username)
        {
            $query_salt['SELECT']    = 'username';
            $query_salt['WHERE']     = 'userid=' . intval($name);
        }

        $rowinfo = [];

        $result_salt = $SQLVB->build($query_salt);

        if ($SQLVB->num_rows($result_salt) > 0)
        {
            while ($row1=$SQLVB->fetch_array($result_salt))
            {
                if ($return_username)
                {
                    return $row1['username'];
                }

                if (! $hashed)
                {
                    $pass = $config['vbulletin_intr_version5'] != 1
                        ? md5(md5($pass) . $row1['salt'])  // without normal md5
                        : crypt(md5($pass), $row1['token']);

                    $query    = [
                        'SELECT'    => '*',
                        'FROM'      => "`{$SQLVB->dbprefix}user`",
                        'WHERE'     => "username='" . $SQLVB->real_escape($name) . "' AND " . ($config['vbulletin_intr_version5'] == 1 ? 'token' : 'password') . "='" . $SQLVB->real_escape($pass) . "' AND usergroupid != '8'"
                    ];

                    $result = $SQLVB->build($query);

                    if ($SQLVB->num_rows($result) != 0)
                    {
                        while ($row=$SQLVB->fetch_array($result))
                        {
                            $rowinfo = $row;

                            break;
                        }
                        $SQLVB->freeresult($result);
                    }
                    else
                    {
                        $SQLVB->close();
                        return false;
                    }
                }
                else
                {
                    $rowinfo = $row1;
                }
            }

            $SQLVB->freeresult($result_salt);
            $SQLVB->close();


            // $userinfo             = $row;
            $userinfo['founder']    = 1;
            $userinfo['id']         = $rowinfo['userid'];
            $userinfo['name']       = $rowinfo['username'];
            $userinfo['mail']      = $rowinfo['email'];
            $userinfo['last_visit'] = time();
            $userinfo['group_id']   =  $rowinfo['usergroupid']               == 6 ? 1 : 3;
            $userinfo['password']   = $config['vbulletin_intr_version5'] == 1 ? $rowinfo['token'] : $rowinfo['password'];


            if (! $loginadm)
            {
                define('USER_ID', $userinfo['id']);
                define('GROUP_ID', $userinfo['group_id']);
                define('USER_NAME', $userinfo['name']);
                define('USER_MAIL', $userinfo['mail']);
            }

            if (! $loginadm && ! $hashed)
            {
                $user_y               = base64_encode(serialize($userinfo));
                $hash_key_expire      = sha1(md5($config['h_key'] . $userinfo['password']) . $expire);

                $usrcp->kleeja_set_cookie('ulogu', $usrcp->en_de_crypt(
                                                    $userinfo['id'] . '|' .
                                                    $userinfo['password'] . '|' .
                                                    $expire . '|' .
                                                    $hash_key_expire . '|' .
                                                   $userinfo['group_id'] . '|' .
                                                    $user_y
                                                ), $expire);
            }


            return true;
        }
        else
        {
            $SQLVB->close();
            return false;
        }
    }
}


if (! function_exists('vbulletin_auth_username'))
{
    function vbulletin_auth_username($user_id)
    {
        return vbulletin_auth_login($user_id, false, false, 0, false, true);
    }
}
