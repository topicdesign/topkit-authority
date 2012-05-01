<?php 

namespace Authority;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Role
 *
 * @package     Authority
 * @subpackage  Models
 * @category    Authentication
 * @author      Topic Deisgn
 * @link        https://github.com/topicdesign/codeigniter-authority-authorization
 * @license     http://creativecommons.org/licenses/BSD/
 */

class Role extends \ActiveRecord\Model {

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
    
    static $belongs_to = array(
        array('user', 'class_name' => 'User'),
        array('permission', 'class_name' => 'Authority\Permission')
    );

    // --------------------------------------------------------------------
    // Validations
    // --------------------------------------------------------------------
    
    static $valdiates_presence_of = array(
        array('title')
    );

    static $validates_length_of = array(
        array('title', 'maximum' => 40)
    );
    // --------------------------------------------------------------------
    // Public Methods
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
}

/**
 * SQL for table

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(40) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

**/
/* End of file Role.php */
/* Location: ./models/Role.php */
