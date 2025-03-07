<?php

class ListItemHandler extends Handler
{
    public function handle($request): ?string
    {
        // Check if this is a list item element
        if ($request['node']->nodeName === 'text:list-item') {
            $content = '';

            // Process child nodes
            foreach ($request['node']->childNodes as $childNode) {
                if ($childNode->nodeType === XML_ELEMENT_NODE) {
                    // Process nested elements using the chain
                    $content .= parent::handle([
                        'node' => $childNode,
                        'styles' => $request['styles'],
                        'mapping' => $request['mapping']
                    ]);
                }
            }

            // Create HTML list item
            return "<li>$content</li>";
        }

        // Not a list item, pass to the next handler
        return parent::handle($request);
    }
}