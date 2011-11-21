<?php
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

namespace Authority;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Ability {

    protected $_rules = array();
    protected $_action_aliases = array();

    // --------------------------------------------------------------------

    /**
     * Get current User
     * implement for your application in the Authority library
     *
     * @return  object
     **/
    protected function current_user()
    {
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Can
     *
     * @access  public
     * @param   string  $action
     * @param   mixed   $resource   string||object
     * @param   ?       $resource_val
     *
     * @return  bool
     **/
    public function can($action, $resource, $resource_val = NULL)
    {
        if (empty($this->_rules))
        {
            return FALSE;
        }

        // See if the action has been aliased to something else
        $true_action = $this->determine_action($action);

        $matches = $this->find_matches($true_action, $resource);

        if ($matches && ! empty($matches))
        {
            $resource_value = ($resource_val) ?: $resource;

            foreach ($matches as $matched_rule)
            {
                if ( ! ($matched_rule->callback($resource_value) XOR $matched_rule->allowed())) 
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * allow
     *
     * @access  public
     * @param   string      $action
     * @param   string      $action
     * @param   closure     $callback
     *
     * @return void
     **/
    public function allow($action, $resource, \Closure $callback = NULL)
    {
        $this->_rules[] = new Rule(TRUE, $action, $resource, $callback);
    }

    // --------------------------------------------------------------------

    /**
     * deny
     *
     * @access  public
     * @param   string      $action
     * @param   string      $action
     * @param   closure     $callback
     *
     * @return void
     **/
    public function deny($action, $resource, \Closure $callback = NULL)
    {
        $this->_rules[] = new Rule(FALSE, $action, $resource, $callback);
    }

    // --------------------------------------------------------------------

    /**
     * action_alias
     *
     * @access  public
     * @param   string  $action
     * @param   array
     *
     * @return  void
     **/
    public function action_alias($action, Array $aliases)
    {
        $this->_action_aliases[$action] = $aliases;
    }

    // --------------------------------------------------------------------

    /**
     * dealias
     *
     * @access  public
     * @param   string  $action
     *
     * @return  void
     **/
    public function dealias($action)
    {
        return $this->_action_aliases[$action] ?: $action; 
    }

    // --------------------------------------------------------------------

    /**
     * determine_action
     *
     * @access  protected
     * @param   string      $action
     *
     * @return  void
     **/
    protected function determine_action($action)
    {
        $actions = array();
        if ( ! empty($this->_action_aliases))
        {
            foreach ($this->_action_aliases as $aliased_action => $aliases)
            {
                if ( ! empty($aliases) && in_array($action, $aliases))
                {
                    $actions[] = $aliased_action;
                }
            }
        }

        if (empty($actions))
        {
            return $action;
        }
        else
        {
            $actions[] = $action;
            return $actions;
        }
    }

    // --------------------------------------------------------------------

    /**
     * find_matches
     *
     * @access  protected
     * @param   string      $action
     * @param   mixed       $resource   string || object?
     *
     * @return  void
     **/
    protected function find_matches($action, $resource)
    {
        $matches = array();
        if (!empty($this->_rules))
        {
            foreach($this->_rules as $rule)
            {
                if ($rule->relevant($action, $resource))
                {
                    $matches[] = $rule;
                }
            }
        }
        return $matches;
    }

    // --------------------------------------------------------------------

}
/* End of file ability.php */
/* Location: ./libraries/authority/ability.php */
