<?php

require_once plugin_dir_path(__FILE__) . 'inc/datastore-api.php';

class ProfilePagesTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        // init vars here
    }

    public function tearDown()
    {
        // undo stuff here
    }

    public function testIncorrectCkanDomain()
    {
        $results = get_datastore_resource('incorrect_domain','some_resource_id');
        $this->assertEmpty($results);
    }
}

?>
