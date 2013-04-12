<?php

/**
 * The backend controller saves done changes and generate the backend
 * html output.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Controller;

class Backend extends \lw_object
{

    private $config;
    private $request;
    private $repository;
    private $pluginname;
    private $oid;

    /**
     * @param object $request
     * @param object $repository
     * @param string $pluginname
     * @param int $oid
     */
    function __construct($config, $request, $repository, $pluginname, $oid)
    {
        $this->config = $config;
        $this->request = $request;
        $this->repository = $repository;
        $this->pluginname = $pluginname;
        $this->oid = $oid;
    }

    /**
     * The informations which are set in the backend formular will be saved.
     */
    function backend_save()
    {
        $parameter = array();
        if ($this->request->getInt("pagesize") > 0) {
            $parameter["pagesize"] = $this->request->getInt("pagesize");
        }
        else {
            $parameter["pagesize"] = 10;
        }
        $parameter["language"] = $this->request->getAlnum("language");
        $parameter["category"] = $this->request->getAlnum("category");

        $content = false;
        $this->repository->plugins()->savePluginData($this->pluginname, $this->oid, $parameter, $content);

        $this->pageReload($this->config["url"]["client"] . "admin.php?obj=content");
    }

    /**
     * Form output will be prepared.
     * @return string
     */
    function backend_view()
    {
        $view = new \lw_view(dirname(__FILE__) . '/../Views/Templates/Backendform.tpl.phtml');
        $view->bootstrapCSS = $this->config["url"]["media"] . "bootstrap/css/bootstrap.min.css";
        $view->bootstrapJS = $this->config["url"]["media"] . "bootstrap/js/bootstrap.min.js";

        $data = $this->repository->plugins()->loadPluginData($this->pluginname, $this->oid);
        if(empty($data["parameter"])) {
            $view->noConfigurationSaved = true;
        }
        $view->pagesize = $data["parameter"]["pagesize"];
        $view->language = $data["parameter"]["language"];
        $view->category = $data["parameter"]["category"];

        $view->actionUrl = $this->buildUrl(array("pcmd" => "save"));

        return $view->render();
    }

}
