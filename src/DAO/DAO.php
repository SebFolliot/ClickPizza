<?php

namespace ClickPizza\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO
{
    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;
    
    /**
     * Constructor
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }
    
    /**
     * @return \Doctrine\DBAL\Connection The database connection object
     */    
    protected function getDB() {
        return $this->db;
    }
    
    /**
     * Builds a entity object from a DB row.
     */    
    protected abstract function buildEntityObject(array $row);
}