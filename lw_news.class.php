<?php

/**
 * This plugin supports the creation of a tag cloud.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */
class lw_news extends lw_plugin
{

    protected $repository;

    /**
     * For the functionality of this plugin is it necessary to include
     * the "Autoloader" and the instances of "in_auth" and "auth"
     * objects.
     */
    public function __construct()
    {
        parent::__construct();
        include_once(dirname(__FILE__) . '/Services/Autoloader.php');
        $autoloader = new \LwNews\Services\Autoloader();
        $this->auth = \lw_registry::getInstance()->getEntry("auth");
    }

    /**
     * The HTML frontend output will be build.
     * 
     * @return string
     */
    public function buildPageOutput()
    {
        $plugindata = $this->repository->plugins()->loadPluginData($this->getPluginName(), $this->params['oid']);
        $plugindata["parameter"]["oid"] = $this->params['oid'];
        
        $checkPlugindataArray = $plugindata;
        unset($checkPlugindataArray["parameter"]["oid"]);
        unset($checkPlugindataArray["parameter"]["content"]);
        unset($checkPlugindataArray["parameter"]["item_id"]);
        
        if (!empty($checkPlugindataArray["parameter"])) {
            $response = new \LwNews\Services\Response();
            $response->setDbObject($this->db);
            $response->setDataByKey("plugindata", $plugindata["parameter"]);
            $response->setDataByKey("c_media", $this->config["url"]["media"]);
            $response->setDataByKey("baseUrl", $this->config["url"]["client"]."index.php?index=".$this->request->getIndex());

            $controller = new \LwNews\Controller\NewsController($response, $this->request, $this->auth->isLoggedIn());
            $controller->execute();

            return $response->getOutputByKey("lw_news_".$this->params['oid']);
        }
        else{
            return "Das Plugin wurde noch nicht konfiguriert. <br> Bitte in der Backendkonfiguration auf speichern klicken.";
        }
    }

    /**
     * The HTML backend output will be build.
     * 
     * @return string
     */
    public function getOutput()
    {
        $backend = new \LwNews\Controller\Backend($this->config, $this->request, $this->repository, $this->getPluginName(), $this->getOid());
        if ($this->request->getAlnum("pcmd") == "save") {
            $backend->backend_save();
        }
        return $backend->backend_view();
    }

    /**
     * Here will be set if the plugin-conetentbox is deleteable from a page.
     * 
     * @return boolean
     */
    function deleteEntry()
    {
        return true;
    }

}