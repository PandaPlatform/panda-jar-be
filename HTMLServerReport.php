<?php

namespace Panda\Jar;

use DOMElement;

/**
 * HTML Server Report
 * Creates an asynchronous server report in HTML format according to user request.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
class HTMLServerReport extends JSONServerReport
{
    /**
     * The replace method identifier.
     *
     * @type    string
     */
    const REPLACE_METHOD = "replace";

    /**
     * The append method identifier.
     *
     * @type    string
     */
    const APPEND_METHOD = "append";

    /**
     * The extra content "popup" type.
     *
     * @type    string
     */
    const CONTENT_POPUP = "popup";

    /**
     * Adds a content report to the report stack.
     *
     * @param DOMElement|string $content The body of the report content.
     * @param string            $type    The content's type.
     *                                   See class constants.
     *                                   It is CONTENT_HTML by default.
     * @param string            $holder  The holder where the content will be inserted in the DOM.
     *                                   It's a CSS selector.
     *                                   Empty by default.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     *                                   See class constants.
     *                                   It is REPLACE_METHOD by default.
     * @param string            $key     The content key value.
     *                                   If set, the content will be available at the given key, otherwise it will
     *                                   inserted in the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addReportContent($content, $type = self::CONTENT_HTML, $holder = "", $method = self::REPLACE_METHOD, $key = "")
    {
        // Get report content
        $report = $this->getHTMLReportContent($content, $holder, $method);

        // Append to reports
        return parent::addReportContent($report, $key, $type);
    }

    /**
     * Creates a report content as a DOMElement inside the report.
     *
     * @param DOMElement|string $content The report content.
     *                                   It is NULL by default.
     * @param string            $holder  The holder where the content will be inserted in the DOM.
     *                                   It's a CSS selector.
     *                                   Empty by default.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     *                                   See class constants.
     *                                   It is REPLACE_METHOD by default.
     *
     * @return array The report content array for the server report.
     */
    protected function getHTMLReportContent($content = null, $holder = null, $method = self::REPLACE_METHOD)
    {
        // Create content array
        $reportContent = array();
        $reportContent['holder'] = $holder;
        $reportContent['method'] = $method;

        // If content is null, return report content as is
        if (is_null($content)) {
            return $reportContent;
        }

        // Parse DOMElement content
        if ($content instanceof DOMElement) {
            // Parse and get XML (use XML to avoid formatting)
            $content = $content->ownerDocument->saveXML($content);
        }

        // Set content
        $reportContent['content'] = $content;

        // Generate content from parent
        return $reportContent;
    }
}