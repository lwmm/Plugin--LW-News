<?php

/**
 * Selection of needed news entries.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Domain\Entry\DataHandler;

class QueryHandler
{

    /**
     * An Instance of lw_db is needed.
     * @param object $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get the informations of an entry with specific id.
     * 
     * @param int $id
     * @return array
     */
    public function selectEntry($id)
    {
        $this->db->setStatement('SELECT * FROM t:lw_master WHERE id = :id ');
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }

    /**
     * All news entries for a specific page will be loaded.
     * 
     * @param array $data
     * @param int $page
     * @return array
     */
    public function selectEntryPage($data, $page)
    {
        $this->db->setStatement('SELECT * FROM t:lw_master WHERE lw_object = :lw_object AND name = :name  AND language = :language ORDER BY opt2number DESC ');
        $this->db->bindParameter("lw_object", "s", "lw_news");
        $this->db->bindParameter("name", "s", $data["category"]);
        $this->db->bindParameter("language", "s", $data["language"]);
        return $this->db->pselect($data["pagesize"] * $page, $data["pagesize"]);
    }

    /**
     * All news entries will be count.
     * 
     * @param array $data
     * @return array
     */
    public function countEntries($data)
    {
        $this->db->setStatement('SELECT COUNT(*) FROM t:lw_master WHERE lw_object = :lw_object AND name = :name  AND language = :language ORDER BY opt2number DESC ');
        $this->db->bindParameter("lw_object", "s", "lw_news");
        $this->db->bindParameter("name", "s", $data["category"]);
        $this->db->bindParameter("language", "s", $data["language"]);
        return $this->db->pselect1();
    }

}