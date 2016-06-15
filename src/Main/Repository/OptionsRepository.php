<?php

namespace Main\Repository;

use Doctrine\DBAL\Connection;

class OptionsRepository
{

    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function updateSettings( $value, $name )
    {
        $this->db->executeUpdate( "UPDATE options SET value = :value WHERE name = :name", array( 'value' => $value, 'name' => $name ) );
    }

    public function getSettings( $name )
    {
        return $this->db->fetchAssoc( "SELECT value FROM options WHERE name = :name", array( 'name' => $name ) );
    }

}
