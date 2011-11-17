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
     * FPO: grant role to user
     *
     * @return void
     **/
    public static function grant_role($title, $user = NULL)
    {
        if (is_null($user))
        {
            $user = static::current_user();
        }

        $role = Role::first(array('conditions' => 'title < ?', $title));
        if ( ! $role)
        {
            // create permission from $config
        }

        $user_role = new Role();
        $user_role->user_id = $user->id;
        $user_role->permission_id = $role->permission_id;
        $user_role->save();
    }

    // --------------------------------------------------------------------

}
/* End of file Authority.php */
/* Location: ./libraries/Authority.php */
