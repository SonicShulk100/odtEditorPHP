<?php

require_once "utils/Handler.php";

class SpanHandler extends Handler
{
    public function handle($request): ?string
    {
        // Check if this is a span element
        if ($request['node']->nodeName === 'text:span') {
            $content = '';
            $styleClass = '';

            // Extract style class if available
            if ($request['node']->hasAttribute('text:style-name')) {
                $styleClass = $request['node']->getAttribute('text:style-name');
            }

            // Process child nodes
            foreach ($request['node']->childNodes as $childNode) {
                if ($childNode->nodeType === XML_TEXT_NODE) {
                    $content .= htmlspecialchars($childNode->nodeValue);
                } elseif ($childNode->nodeType === XML_ELEMENT_NODE) {
                    // Process nested elements using the chain
                    $content .= parent::handle([
                        'node' => $childNode,
                        'styles' => $request['styles'],
                        'mapping' => $request['mapping']
                    ]);
                }
            }

            // Create HTML span with appropriate class
            return "<span" . ($styleClass ? " class=\"$styleClass\"" : "") . ">$content</span>";
        }

        // Not a span, pass to the next handler
        return parent::handle($request);
    }
}
