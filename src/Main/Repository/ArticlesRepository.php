<?php

namespace Main\Repository;

use Doctrine\DBAL\Connection;

class ArticlesRepository
{

    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function update( $value )
    {
        // update article
        //$this->db->executeUpdate( "UPDATE options SET value = ? WHERE name = 'settings'", array( $value ) );
    }

    public function read()
    {
        // read articles
    }

    public function create()
    {
        // create article
    }

    public function delete()
    {
        // delete article
    }

    public function getArticles( $limit )
    {
        return $this->db->fetchAll( "SELECT * FROM articles WHERE 1 LIMIT $limit", array() );
    }

}
