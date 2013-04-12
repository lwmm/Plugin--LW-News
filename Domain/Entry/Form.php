<?php

/**
 * Preparations for the form view.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Domain\Entry;

class Form
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
        $this->isValid = new \LwNews\Domain\Entry\Service\isValid();
        switch ($this->request->getAlnum("cmd")) {
            case "add":
                if ($this->request->getInt("sent") == 1) {
                    $bool = $this->validate();

                    $array2 = $this->response->getDataByKey("plugindata");
                    $array = $this->request->getRaw("news");
                    $array["name"] = $array2["category"];
                    $array["language"] = $array2["language"];
                    
                    if($this->request->getAlnum("type") == "text" && !empty($array["maintext"])) {
                        $array["pageid"] = 0;  
                        $array["exturl"] = "";  
                    }
                    elseif($this->request->getAlnum("type") == "cms" && $array["pageid"] > 0) {
                        $array["maintext"] = "";   
                        $array["exturl"] = "";  
                    }
                    elseif($this->request->getAlnum("type") == "ext" && !empty($array["exturl"])) {
                        $array["maintext"] = "";   
                        $array["pageid"] = 0;  
                    }

                    if ($bool) {
                        $commandHandler = new \LwNews\Domain\Entry\DataHandler\CommandHandler($this->response->getDbObject());
                        $commandHandler->addEntry($array);

                        \LwNews\Services\Page::reload(\LwNews\Services\Page::getUrl(array("show" => "all")));
                    }
                    else {
                        $plugindata = $this->response->getDataByKey("plugindata");
                        return array("notvalid" => $this->returnErrorArray("add"), "c_media" => $this->response->getDataByKey("c_media"), "oid" => $plugindata["oid"]);
                    }
                }
                else {
                    $plugindata = $this->response->getDataByKey("plugindata");
                    return array("cmd" => "add", "c_media" => $this->response->getDataByKey("c_media"), "oid" => $plugindata["oid"]);
                }
                break;

            case "edit":
                if ($this->request->getInt("sent") == 1) {
                    $bool = $this->validate();
                    $array = $this->request->getRaw("news");
                    
                    if($this->request->getAlnum("type") == "text" && !empty($array["maintext"])) {
                        $array["pageid"] = 0;  
                        $array["exturl"] = "";  
                    }
                    elseif($this->request->getAlnum("type") == "cms" && $array["pageid"] > 0) {
                        $array["maintext"] = "";   
                        $array["exturl"] = "";  
                    }
                    elseif($this->request->getAlnum("type") == "ext" && !empty($array["exturl"])) {
                        $array["maintext"] = "";   
                        $array["pageid"] = 0;  
                    }

                    if ($bool) {
                        $commandHandler = new \LwNews\Domain\Entry\DataHandler\CommandHandler($this->response->getDbObject());
                        $commandHandler->saveEntry($this->request->getInt("id"), $array);

                        \LwNews\Services\Page::reload(\LwNews\Services\Page::getUrl(array("show" => "all")));
                    }
                    else {
                        $plugindata = $this->response->getDataByKey("plugindata");
                        return array("notvalid" => $this->returnErrorArray("edit"), "c_media" => $this->response->getDataByKey("c_media"), "oid" => $plugindata["oid"]);
                    }
                }
                else {
                    $plugindata = $this->response->getDataByKey("plugindata");
                    $queryHandler = new \LwNews\Domain\Entry\DataHandler\QueryHandler($this->response->getDbObject());
                    $result = $queryHandler->selectEntry($this->request->getInt("id"));

                    $array = array("formData" => array(
                            "date" => $result["opt2number"],
                            "headline1" => $result["opt1text"],
                            "headline2" => $result["opt2text"],
                            "exturl" => $result["opt3text"],
                            "maintext" => $result["opt1clob"],
                            "pageid" => $result["opt1number"]
                        ),
                        "cmd" => "edit", "id" => $result["id"],
                        "c_media" => $this->response->getDataByKey("c_media"),
                        "oid" => $plugindata["oid"]
                    );
                    return $array;
                }
                break;
        }
    }

    /**
     * Validates the form input.
     * 
     * @return bool
     */
    private function validate()
    {
        $this->isValid->setValues($this->request->getRaw("news"));
        return $this->isValid->validate();
    }

    /**
     * Returns the occured errors of the form input validation.
     * 
     * @param string $flagg
     * @return array
     */
    private function returnErrorArray($flagg)
    {
        $array = $this->request->getRaw("news");
        $array["type"] = $this->request->getAlnum("type");

        if ($flagg == "add") {
            return array("formData" => $array, "errors" => $this->isValid->getErrors(), "cmd" => "add", "id" => $this->request->getInt("id"));
        }

        if ($flagg == "edit") {
            return array("formData" => $array, "errors" => $this->isValid->getErrors(), "cmd" => "edit", "id" => $this->request->getInt("id"));
        }
    }

}