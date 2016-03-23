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

    public function updateSettings( $value )
    {
        $this->db->executeUpdate( "UPDATE options SET value = ? WHERE name = 'settings'", array( $value ) );
    }

    public function getSettings()
    {
        return $this->db->fetchAssoc( "SELECT value FROM options WHERE name = 'settings'" );
    }

}