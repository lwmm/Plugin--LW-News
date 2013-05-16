<?php

/**
 * Generating html output of news list.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Views;

class All
{

    /**
     * The html template will be rendered and return.
     * 
     * @param array $data
     * @param bool $admin
     * @return string
     */
    public function render($data, $admin = false, $baseUrl = false)
    {
        $count = $data["count"];
        $pagesize = $data["pagesize"];
        $page = $data["page"];
        $language = $data["lang"];
        $oid = $data["oid"];
        unset($data["count"]);
        unset($data["pagesize"]);
        unset($data["page"]);
        unset($data["lang"]);
        unset($data["oid"]);


        #$baseUrl = \LwNews\Services\Page::getUrl() . "&oid=" . $oid;
        $baseUrl = $baseUrl . "&oid=" . $oid;
        $pagecount = ceil(intval($count) / intval($pagesize));

        $view = new \lw_view(dirname(__FILE__) . '/Templates/NewsList.phtml');

        $view->pagecount = $pagecount;
        $view->admin = $admin;
        $view->baseUrl = $baseUrl;
        #$view->baseUrlWithoutIndex = substr(\LwNews\Services\Page::getUrl(), 0, strpos(\LwNews\Services\Page::getUrl(), "index=") + strlen("index="));
        $view->baseUrlWithoutIndex = substr($baseUrl, 0, strpos($baseUrl, "index=") + strlen("index="));
        $view->data = $data;
        $view->page = $page;
        $view->paging = $this->buildPaging($pagecount, $page, $baseUrl);
        $view->lang = $language;

        return $view->render();
    }

    /**
     * The paging navigation will be build.
     * 
     * @param int $count
     * @param int $page
     * @return string
     */
    private function buildPaging($count, $page, $baseUrl)
    {
        if ($count > 1) {
            if ($count >= 2) {
                $out = '<a class="page"  href="' . $baseUrl . '&news_page=0">1</a>' . PHP_EOL;
                $out.= '<a class="page"  href="' . $baseUrl . '&news_page=1">2</a>' . PHP_EOL;
                if ($count >= 3) {
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=2">3</a>' . PHP_EOL;
                }
            }

            if ($count < 10) {
                if ($count == 4) {
                    $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=3">4</a>' . PHP_EOL;
                }
                if ($count == 5) {
                    $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=3">4</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=4">5</a>' . PHP_EOL;
                }

                if ($count >= 6) {
                    $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=3">4</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=4">5</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=5">6</a>' . PHP_EOL;

                    if ($count == 7) {
                        $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                        $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 1 ) . '">' . ($count) . '</a>' . PHP_EOL;
                    }
                    if ($count == 8) {
                        $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                        $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 2 ) . '">' . ($count - 1) . '</a>' . PHP_EOL;
                        $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 1 ) . '">' . ($count) . '</a>' . PHP_EOL;
                    }
                    if ($count == 9) {
                        $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                        $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 3 ) . '">' . ($count - 2) . '</a>' . PHP_EOL;
                        $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 2 ) . '">' . ($count - 1) . '</a>' . PHP_EOL;
                        $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 1 ) . '">' . ($count) . '</a>' . PHP_EOL;
                    }
                }
            }
            else {
                if ($page < 5) {
                    $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=3">4</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=4">5</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=5">6</a>' . PHP_EOL;
                }
                elseif ($page < ($count - 4)) {
                    $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($page - 1) . '">' . ($page) . '</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($page) . '">' . ($page + 1) . '</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($page + 1) . '">' . ($page + 2) . '</a>' . PHP_EOL;
                }
                else {
                    $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 6) . '">' . ($count - 5) . '</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 5) . '">' . ($count - 4) . '</a>' . PHP_EOL;
                    $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 4) . '">' . ($count - 3 ) . '</a>' . PHP_EOL;
                }

                $out.= '<div class="clearer" >&nbsp;</div>' . PHP_EOL;
                $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 3 ) . '">' . ($count - 2) . '</a>' . PHP_EOL;
                $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 2 ) . '">' . ($count - 1) . '</a>' . PHP_EOL;
                $out.= '<a class="page"  href="' . $baseUrl . '&news_page=' . ($count - 1 ) . '">' . ($count) . '</a>' . PHP_EOL;
            }
        }
        return $out;
    }

}