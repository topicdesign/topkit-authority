<?php 

namespace Authority;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
    
    // --------------------------------------------------------------------
    // Public Methods
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
}

/* End of file model.sample.php */
/* Location: ./application/models/model.sample.php */
