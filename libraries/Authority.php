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
 * @version     0.0.6
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

    /**
     * Constructor
     *
     * @access  public
     * @param   void
     *
     * @return  void
     **/
    public function __construct()
    {
        $user = $this->current_user();
        if ( ! $user || ! $user->permissions)
        {
            return FALSE;
        }

        $this->action_alias('admin', array('create', 'read', 'update', 'delete'));
        $this->action_alias('manage', array('create', 'read', 'update'));

        foreach ($user->permissions as $p)
        {
            foreach (json_decode($p->data, TRUE) as $resource => $actions)
            {
                if (is_array($actions))
                {
                    foreach ($actions as $action => $val)
                    {
                        if ($val === 'own')
                        {
                            $this->allow($action, $resource,
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
                            $this->allow($action, $resource);
                        }
                    }
                }
                else
                {
                    $this->allow($actions, $resource);
                }
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * get current user
     *
     * @access  public
     * @param   void
     *
     * @return  object
     **/
    protected function current_user()
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
     * @access  public
     * @param   string  $title  roles.title
     * @param   object  $user   user to act on
     *
     * @return  void
     **/
    public function grant_role($title, $user = NULL)
    {
        if (is_null($user))
        {
            $user = $this->current_user();
        }
        if ( ! $user)
        {
            return FALSE;
        }

        // check if user already has this role
        if ( ! empty($user->roles))
        {
            foreach ($user->roles as $role)
            {
                if ($role->title == $title)
                {
                    return FALSE;
                }
            }
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
     * @access  public
     * @param   string  $title  roles.title
     * @param   object  $user   user to act on
     *
     * @return  void
     **/
    public function remove_role($title, $user = NULL)
    {
        if (is_null($user))
        {
            $user = $this->current_user();
        }
        if ( ! $user)
        {
            return FALSE;
        }

        // check if user has this role
        $role = NULL;
        $has_role = FALSE;
        foreach ($user->roles as $r)
        {
            if ($r->title == $title)
            {
                $has_role = TRUE;
                $role = $r;
                break;
            }
        }
        if ( ! $has_role)
        {
            return $has_role;
        }

        // if this is the only user with this role, remove permissions
        $other_roles = \Authority\Role::first(array(
            'conditions' => array(
                'permission_id = ? AND id != ?',
                $role->permission_id,
                $role->id
            )
        ));
        if ( ! $other_roles)
        {
            $role->permission->delete();
        }

        $role->delete();
    }

    // --------------------------------------------------------------------

}
/* End of file Authority.php */
/* Location: ./libraries/Authority.php */
