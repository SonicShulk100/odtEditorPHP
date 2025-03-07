<?php

require_once "utils/Handler.php";

class TableHandler extends Handler
{
    public function handle($request): ?string
    {
        // Check if this is a table element
        if ($request['node']->nodeName === 'table:table') {
            $content = '';
            $styleClass = '';

            // Extract style class if available
            if ($request['node']->hasAttribute('table:style-name')) {
                $styleClass = $request['node']->getAttribute('table:style-name');
            }

            // Process child nodes (table rows)
            foreach ($request['node']->childNodes as $childNode) {
                if ($childNode->nodeType === XML_ELEMENT_NODE) {
                    // Process table rows using the chain
                    $content .= parent::handle([
                        'node' => $childNode,
                        'styles' => $request['styles'],
                        'mapping' => $request['mapping']
                    ]);
                }
            }

            // Create HTML table with appropriate class
            return "<table" . ($styleClass ? " class=\"$styleClass\"" : "") . ">$content</table>";
        }

        // Not a table, pass to the next handler
        return parent::handle($request);
    }
}
