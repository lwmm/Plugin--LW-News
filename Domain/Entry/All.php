<?php

/**
 * Preparations for the list all view.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Domain\Entry;

class All
{

    private $response;
    private $request;

    /**
     * @param object $response
     * @param object $request
     */
    public function __construct($response, $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * Informations will be collected that the template can be rendered.
     * 
     * @return array
     */
    public function execute()
    {
        $plugindata = $this->response->getDataByKey("plugindata");
        
        if ($this->request->getInt("news_page") && $plugindata["oid"] == $this->request->getInt("oid")) {
            $page = $this->request->getInt("news_page");
        }
        else {
            $page = 0;
        }

        

        $queryHandler = new \LwNews\Domain\Entry\DataHandler\QueryHandler($this->response->getDbObject());
        $temp = $queryHandler->countEntries($plugindata);

        $pages = ceil(intval($temp["COUNT(*)"]) / intval($plugindata["pagesize"]));

        if ($page > $pages) {
            $page = 0;
        }

        $array = $queryHandler->selectEntryPage($plugindata, $page);
        $array["count"] = $temp["COUNT(*)"];
        $array["pagesize"] = $plugindata["pagesize"];
        $array["page"] = $page;
        $array["lang"] = $plugindata["language"];
        $array["oid"] = $plugindata["oid"];

        return $array;
    }

}