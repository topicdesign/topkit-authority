<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Authority Helpers
 *
 * @package		Authority
 * @subpackage	Helpers
 * @category	Authentication
 * @author		Topic Design
 * @link		https://github.com/topicdesign/codeigniter-authority-authorization
 */

// ------------------------------------------------------------------------

if ( ! function_exists('can') && ! function_exists('cannot'))
{
    /**
     * Alias $this->authority->can()
     *
     * @param   string  $action
     * @param   mixed   $resource   string||object
     * @param   ?       $resource_val
     *
     * @return  bool
     **/
    function can($action, $resource, $resource_val = null)
    {
        $CI = get_instance();
        if ( ! isset($CI->authority))
        {
            $CI->load->library('authority');
        }
        return $CI->authority->can($action, $resource, $resource_val);
    }

    /**
     * Alias to NOT $this->authority->can()
     *
     * @param   string  $action
     * @param   mixed   $resource   string||object
     * @param   ?       $resource_val
     *
     * @return  bool
     **/
    function cannot($action, $resource, $resource_val = null)
    {
        return ! can($action, $resource, $resource_val);
    }
}

// --------------------------------------------------------------------

/**
 * Alias for $this->authority->grant_role()
 *
 * @access  public
 * @param   string  $title  roles.title
 * @param   object  $user   user to act on
 *
 * @return  void
 **/
if ( ! function_exists('grant_role'))
{
    function grant_role($title, $user = NULL)
    {
        $CI = get_instance();
        if ( ! isset($CI->authority))
        {
            $CI->load->library('authority');
        }
        return $CI->authority->grant_role($title, $user);
    }
}

// --------------------------------------------------------------------

/**
 * Alias for $this->authority->remove_role()
 *
 * @access  public
 * @param   string  $title  roles.title
 * @param   object  $user   user to act on
 *
 * @return  void
 **/
if ( ! function_exists('remove_role'))
{
    function remove_role($title, $user = NULL)
    {
        $CI = get_instance();
        if ( ! isset($CI->authority))
        {
            $CI->load->library('authority');
        }
        return $CI->authority->remove_role($title, $user);
    }
}

// ------------------------------------------------------------------------

/* End of file authority_helper.php */
/* Location: ./helpers/authority_helper.php */
