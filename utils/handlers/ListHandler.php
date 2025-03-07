<?php

require_once "utils/Handler.php";

class ListHandler extends Handler
{
    public function handle($request): ?string
    {
        // Check if this is a list element
        if ($request['node']->nodeName === 'text:list') {
            $content = '';
            $styleClass = '';

            // Extract style class if available
            if ($request['node']->hasAttribute('text:style-name')) {
                $styleClass = $request['node']->getAttribute('text:style-name');
            }

            // Process child nodes (list items)
            foreach ($request['node']->childNodes as $childNode) {
                if ($childNode->nodeType === XML_ELEMENT_NODE) {
                    // Process list items using the chain
                    $content .= parent::handle([
                        'node' => $childNode,
                        'styles' => $request['styles'],
                        'mapping' => $request['mapping']
                    ]);
                }
            }

            // Create HTML unordered list with appropriate class
            return "<ul" . ($styleClass ? " class=\"$styleClass\"" : "") . ">$content</ul>";
        }

        // Not a list, pass to the next handler
        return parent::handle($request);
    }
}