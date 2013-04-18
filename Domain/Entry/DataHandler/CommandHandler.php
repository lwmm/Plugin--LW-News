<?php

/**
 * Creation, update ,deletion of news entries.
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Domain\Entry\DataHandler;

class CommandHandler
{

    private $db;

    /**
     * An instance of lw_db is needed.
     * 
     * @param object $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * A new news entry will be added.
     * 
     * @param array $array
     * @return boolean
     */
    public function addEntry($array)
    {
        if (empty($array["pageid"]))
            $array["pageid"] = 0;
        $this->db->setStatement("INSERT INTO t:lw_master ( lw_object, name, language, opt1text, opt2text, opt3text, opt4text, opt1number, opt2number, lw_first_date, lw_last_date) VALUES ( :lw_object, :name, :language, :opt1text, :opt2text, :opt3text, :opt4text, :opt1number, :opt2number, :date, :date ) ");
        $this->db->bindParameter("lw_object", "s", "bionrw_news");
        $this->db->bindParameter("name", "s", $array["name"]);
        $this->db->bindParameter("language", "s", $array["language"]);
        $this->db->bindParameter("opt1text", "s", $array["headline1"]);
        $this->db->bindParameter("opt2text", "s", $array["headline2"]);
        $this->db->bindParameter("opt3text", "s", $array["exturl"]);
        $this->db->bindParameter("opt4text", "s", $array["teasertext"]);
        $this->db->bindParameter("opt1number", "i", $array["pageid"]);
        $this->db->bindParameter("opt2number", "i", $array["date"]);
        $this->db->bindParameter("date", "i", date("YmdHis"));
        $id = $this->db->pdbinsert($this->db->gt("lw_master"));

        if ($id > 0) {
            return $this->db->saveClob($this->db->gt("lw_master"), "opt1clob", $this->db->quote($array["maintext"]), $id);
        }
        else {
            return false;
        }
    }

    /**
     * An existing entry will be updated.
     * 
     * @param int $id
     * @param array $array
     * @return boolean
     */
    public function saveEntry($id, $array)
    {
        if (empty($array["pageid"]))
            $array["pageid"] = 0;
        $this->db->setStatement("UPDATE t:lw_master SET opt1text = :opt1text, opt2text = :opt2text, opt3text = :opt3text, opt4text = :opt4text, opt1number = :opt1number, opt2number = :opt2number, lw_last_date = :date  WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("opt1text", "s", $array["headline1"]);
        $this->db->bindParameter("opt2text", "s", $array["headline2"]);
        $this->db->bindParameter("opt3text", "s", $array["exturl"]);
        $this->db->bindParameter("opt4text", "s", $array["teasertext"]);
        $this->db->bindParameter("opt1number", "i", $array["pageid"]);
        $this->db->bindParameter("opt2number", "i", $array["date"]);
        $this->db->bindParameter("date", "i", date("YmdHis"));
        $this->db->pdbquery();

        if ($id > 0) {
            return $this->db->saveClob($this->db->gt("lw_master"), "opt1clob", $this->db->quote($array["maintext"]), $id);
        }
        else {
            return false;
        }
    }

    /**
     * An entry with certain id will be deleted.
     * 
     * @param int $id
     * @return bool
     */
    public function deleteEntry($id)
    {
        $this->db->setStatement("DELETE FROM t:lw_master WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}