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
        $this->db->insert( $this->table, array() );
        return $this->db->lastInsertId();
    }

    public function delete( $id )
    {
        $this->db->delete( $this->table, array( 'id' => $id ) );
    }

    public function getArticles( $limit, $offset = 0 )
    {
        return $this->db->fetchAll( "SELECT * FROM " . $this->table . " WHERE 1 ORDER BY date_creation DESC LIMIT $offset, $limit", array() );
    }

    public function countArticles() {
        return $this->db->fetchAssoc( "SELECT count(*) as total FROM " . $this->table . " WHERE status=1", array() );
    }

}
