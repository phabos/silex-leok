<?php

namespace Main\Repository;

use Doctrine\DBAL\Connection;

class TagsReposiroty
{

    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function update( $value )
    {
        // update tag
        //$this->db->executeUpdate( "UPDATE options SET value = ? WHERE name = 'settings'", array( $value ) );
    }

    public function read()
    {
        // read tags
    }

    public function create()
    {
        // create tag
    }

    public function delete()
    {
        // delete tag
    }

}
