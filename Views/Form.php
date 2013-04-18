<?php

/**
 * Generating html output for new/edit form.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_news
 */

namespace LwNews\Views;

class Form
{

    /**
     * The html template will be rendered and return.
     * 
     * @param array $data
     * @param bool $admin
     * @return  string
     */
    public function render($data, $admin = false)
    {
        if (!$admin) {
            \LwNews\Services\Page::reload(\LwNews\Services\Page::getUrl());
        }
        else {
            if (array_key_exists("notvalid", $data)) {
                $temp = $data["c_media"];
                $tempOid = $data["oid"];
                $data = $data["notvalid"];
                $data["c_media"] = $temp;
                $data["oid"] = $tempOid;
            }

            $view = new \lw_view(dirname(__FILE__) . '/Templates/EntryForm.phtml');

            $view->admin = $admin;
            $view->baseUrl = \LwNews\Services\Page::getUrl();
            $view->calendaricon = $data["c_media"] . "pics/fatcow_icons/16x16_0180/calendar.png";
            $view->mce = $data["c_media"] . "tinymce/jscripts/tiny_mce/tiny_mce.js";

            if ($data["cmd"] == "add") {
                $view->formAction = \LwNews\Services\Page::getUrl() . "&show=form&cmd=add&oid=".$data["oid"];
                $view->formTitle = "Neuen News-Eintrag anlegen";
            }

            if ($data["cmd"] == "edit") {
                $view->formAction = \LwNews\Services\Page::getUrl() . "&show=form&cmd=edit&id=" . $data["id"]."&oid=".$data["oid"];
                $view->formTitle = "News-Eintrag bearbeiten";
            }

            if (array_key_exists("formData", $data)) {
                $view->formData = $data["formData"];

                $year = substr($data["formData"]['date'], 0, 4);
                $month = substr($data["formData"]['date'], 4, 2);
                $day = substr($data["formData"]['date'], 6, 2);

                $view->date = $day . '.' . $month . '.' . $year;
            }
            else {
                $view->formData = false;
                $view->date = false;
            }

            if (array_key_exists("errors", $data)) {
                $view->errors = $data["errors"];
            }
            else {
                $view->errors = false;
            }

            return $view->render();
        }
    }

}