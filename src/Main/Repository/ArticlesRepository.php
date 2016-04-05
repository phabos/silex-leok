<?php

namespace Main\Repository;

use Doctrine\DBAL\Connection;

class ArticlesRepository
{

    protected $db;
    protected $table = 'articles';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function update( $datas, $id )
    {
        // update article
        $this->db->update( $this->table, $datas, array( 'id' => $id ) );
    }

    public function read( $id )
    {
        // get article
        return $this->db->fetchAssoc( "SELECT * FROM " . $this->table . " WHERE id = ?", array( $id ) );
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
        return $this->db->fetchAll( "SELECT * FROM " . $this->table . " WHERE 1 LIMIT $limit", array() );
    }

}
