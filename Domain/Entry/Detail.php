<?php

/**
 * Preparations for the detail view.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Domain\Entry;

class Detail
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
        
        if ($this->request->getInt("np") && $plugindata["oid"] == $this->request->getInt("oid")) {
            $page = $this->request->getInt("np");
        }
        else {
            $page = 0;
        }

        $queryHandler = new \LwNews\Domain\Entry\DataHandler\QueryHandler($this->response->getDbObject());
        $result = $queryHandler->selectEntry($this->request->getInt("id"));

        return array("formData" => $result, "news_page" => $page, "lang" => $plugindata["language"], "oid" => $plugindata["oid"]);
    }

}