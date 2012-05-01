<?php 

namespace Authority;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Permission
 *
 * @package     Authority
 * @subpackage  Models
 * @category    Authentication
 * @author      Topic Deisgn
 * @link        https://github.com/topicdesign/codeigniter-authority-authorization
 * @license     http://creativecommons.org/licenses/BSD/
 */

class Permission extends \ActiveRecord\Model {

    # explicit table name  
    //static $table_name = '';

    # explicit pk 
    //static $primary_key = '';

    # explicit connection name 
    //static $connection = '';

    # explicit database name 
    //static $db = '';

    // --------------------------------------------------------------------
    // Associations
    // --------------------------------------------------------------------
    
    static $has_many = array(
        array('roles', 'class_name' => 'Authority\Role'),
    );

    // --------------------------------------------------------------------
    // Validations
    // --------------------------------------------------------------------
    
    static $validates_presence_of = array(
        array('data')
    );
    
    // --------------------------------------------------------------------
    // Public Methods
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
}

/**
 * SQL for table

CREATE TABLE `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `data` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

**/
/* End of file Permission.php */
/* Location: ./models/Permission.php */
