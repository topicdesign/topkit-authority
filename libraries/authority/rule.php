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

if ( ! defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300) 
    die('Authority requires PHP 5.3 or higher');

//use Authority;

class Rule {

    protected $_allowed     = false;
    protected $_resource    = null;
    protected $_action      = null;
    protected $_callback    = null;

    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access  public
     * @param   bool        $allowed
     * @param   string      $action
     * @param   string      $resource
     * @param   closure     $callback
     *
     * @return  void
     **/
    public function __construct($allowed, $action, $resource, \Closure $callback = null)
    {
        $this->_allowed     = $allowed;
        $this->_action      = $action;
        $this->_resource    = $resource;
        $this->_callback    = $callback;
    }

    // --------------------------------------------------------------------

    /**
     * allowed
     *
     * @access  public
     * @param   void
     *
     * @return  bool
     **/
    public function allowed()
    {
        return $this->_allowed;
    }

    // --------------------------------------------------------------------

    /**
     * matches_action
     *
     * @access  public
     * @param   string  $action
     *
     * @return  bool
     **/
    public function matches_action($action)
    {
        return is_array($action)    ? in_array($this->_action, $action) 
                                    : $this->_action === $action;
    }

    // --------------------------------------------------------------------

    /**
     * matches_resource
     *
     * @access  pubic
     * @param   string  $resource
     *
     * @return  bool
     **/
    public function matches_resource($resource)
    {
        $resource = is_object($resource) ? strtolower(get_class($resource)) : $resource;
        return $this->_resource === $resource || $this->_resource === 'all';
    }

    // --------------------------------------------------------------------

    /**
     * relevant
     *
     * @access  public
     * @param   string  $action
     * @param   string  $resource
     *
     * @return  bool
     **/
    public function relevant($action, $resource)
    {
        return $this->matches_action($action) && $this->matches_resource($resource);
    }

    // --------------------------------------------------------------------

    /**
     * callback
     *
     * @access  public
     * @param   object  $resource
     *
     * @return  bool
     **/
    public function callback($resource)
    {
        if (isset($this->_callback) && is_string($resource)) {
            return false;
        }
        return (isset($this->_callback)) ? $this->_callback($resource) : true;
    }

    // --------------------------------------------------------------------

    /**
     * Allow callbacks to be called
     *
     * @access  public
     * @param   string  $method
     * @param   mixed   $args
     *
     * @return  bool
     **/
    public function __call($method, $args)
    {
        return (isset($this->$method)) ? call_user_func_array($this->$method, $args) : true;
    }

    // --------------------------------------------------------------------

}
/* End of file rule.php */
/* Location: ./libraries/authority/rule.php */
