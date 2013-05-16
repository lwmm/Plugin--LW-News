<?php

/**
 * Tagcloud controller collects necessary informations and pass them
 * to the specific classes in case of which job has to be done.
 *
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Controller;

class NewsController extends \lw_object
{

    private $response;
    private $request;
    private $admin;

    /**
     * @param object $response
     * @param object $request
     * @param bool   $admin
     */
    public function __construct($response, $request, $admin)
    {
        $this->response = $response;
        $this->request = $request;
        $this->admin = $admin;
    }

    /**
     * 
     */
    public function execute()
    {
        $plugindata = $this->response->getDataByKey("plugindata");
        
        if($this->request->getInt("oid") == $plugindata["oid"]) {
            if ($this->request->getInt("delete")) {
                $this->deleteEntryById($this->request->getInt("delete"));
            }


            if ($this->request->getAlnum("show")) {
                $show = ucfirst($this->request->getAlnum("show"));
            }
            else {
                $show = "All";
            }
        }
        else{
            $show = "All";
        }
        
        $class = "\\LwNews\\Domain\\Entry\\" . $show;
        $class_view = "\\LwNews\\Views\\" . $show;

        $controller = new $class($this->response, $this->request);

        $view = new $class_view($this->request);
        $this->response->setOutputByKey("lw_news_".$plugindata["oid"], $view->render($controller->execute(), $this->admin, $this->response->getDataByKey("baseUrl")));
    }

    /**
     * A new entry will be deleted.
     * 
     * @param int $id
     */
    private function deleteEntryById($id)
    {
        $commandHandler = new \LwNews\Domain\Entry\DataHandler\CommandHandler($this->response->getDbObject());
        $commandHandler->deleteEntry($id);

        \LwNews\Services\Page::reload($this->response->getDataByKey("baseUrl"));
    }

}
