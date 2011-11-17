<?php 

namespace Authority;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
    
    // --------------------------------------------------------------------
    // Public Methods
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
}

/* End of file model.sample.php */
/* Location: ./application/models/model.sample.php */
