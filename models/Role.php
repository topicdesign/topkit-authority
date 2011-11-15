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
    
    static $has_many = array(
        array('users', 'class_name' => 'Authentic\User')
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
