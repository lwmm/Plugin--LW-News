<?php

/**
 * Generating html output for detailed news view.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Views;

class Detail
{

    /**
     * The html template will be rendered and return. 
     * 
     * @param array $data
     * @return string
     */
    public function render($data)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Detail.phtml');
        $view->data = $data["formData"];
        $view->page = $data["news_page"];
        $view->lang = $data["lang"];
        $view->baseUrl = \LwNews\Services\Page::getUrl()."&oid=".$data["oid"];

        return $view->render();
    }

}
