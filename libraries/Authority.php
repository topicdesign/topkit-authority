<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Authority
 *
 * Authority is an authorization library for CodeIgniter 2+ and PHPActiveRecord
 * This library is inspired by, and largely based off, Ryan Bates' CanCan gem
 * for Ruby on Rails.  It is not a 1:1 port, but the essentials are available.
 * Please check out his work at http://github.com/ryanb/cancan/
 *
 * @package     Authority
 * @version     0.0.3
 * @author      Matthew Machuga
 * @license     MIT License
 * @copyright   2011 Matthew Machuga
 * @link        http://github.com/machuga
 *
 **/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'authority/ability.php';
require 'authority/rule.php';

class Authority extends Authority\Ability {

    public static function initialize($user)
    {
        if ( ! $user || ! $user->permissions)
        {
            return FALSE;
        }

        Authority::action_alias('manage', array('create', 'read', 'update', 'delete'));
        Authority::action_alias('moderate', array('update', 'delete', 'edit'));

        foreach ($user->permissions as $p)
        {
            foreach (json_decode($p->data, TRUE) as $resource => $actions)
            {
                if (is_array($actions)) 
                {
                    foreach ($actions as $action => $val)
                    {
                        if ($val == 'own')
                        {
                            Authority::allow($action, $resource,
                                function($obj) use ($user, $resource)
                                {
                                    foreach ($user->$resource as $r)
                                    {
                                        if ($r->id == $obj->id)
                                        {
                                            return TRUE;
                                        }
                                    }
                                    return FALSE;
                                }
                            );
                        }
                        $val = (bool) $val;
                        if ($val === TRUE)
                        {
                            Authority::allow($action, $resource);
                        }
                    }
                } 
                else 
                {
                    Authority::allow($actions, $resource);
                }
            }
        }
    }

    // --------------------------------------------------------------------

    protected static function current_user()
    {
        if ( ! function_exists('get_user'))
        {
            return FALSE;
        }
        return get_user();
    }

    // --------------------------------------------------------------------

    /**
     * grant role to user
     *
     * @return void
     **/
    public static function grant_role($title, $user = NULL)
    {
        if (is_null($user))
        {
            $user = static::current_user();
        }

        // check if user already has this role
        $role = \Authority\Role::first(array(
            'conditions' => array('user_id = ?', $user->id)
        ));
        if ($role)
        {
            return FALSE;
        }

        // check to see if role/permissions exist
        $role = \Authority\Role::first(array(
            'conditions' => array('title = ?', $title), 
        ));
        if ( ! $role)
        {
            // create permission from $config
            $CI = get_instance();
            $CI->config->load('roles');
            $roles = config_item('roles');
            if ( ! isset($roles[$title]))
            {
                return FALSE;
            }

            $permission = new \Authority\Permission();
            $permission->data = json_encode($roles[$title]);
            $permission->save();

            $permission_id = $permission->id;
        }
        else
        {
            $permission_id = $role->permission_id;
        }

        $user_role = new \Authority\Role();
        $user_role->title = $title;
        $user_role->user_id = $user->id;
        $user_role->permission_id = $permission_id;
        $user_role->save();
    }

    // --------------------------------------------------------------------

    /**
     * remove role from a user
     *
     * @return void
     **/
    public static function remove_role($title, $user = NULL)
    {
        if (is_null($user))
        {
            $user = static::current_user();
        }

        // check if user has this role
        $role = \Authority\Role::first(array(
            'conditions' => array(
                'user_id = ? AND title = ?', 
                $user->id,
                $title
            )
        ));
        if ( ! $role)
        {
            return FALSE;
        }

        // if this is the only user with this role, remove permissions
        $other_roles = \Authority\Role::first(array(
            'conditions' => array('permission_id = ?', $role->permission_id)
        ));
        if ( ! $other_roles) 
        {
            $permission = \Authority\Permission::find($role->permission_id);
            $permission->delete();
        }

        $role->delete();
    }

}
/* End of file Authority.php */
/* Location: ./libraries/Authority.php */
