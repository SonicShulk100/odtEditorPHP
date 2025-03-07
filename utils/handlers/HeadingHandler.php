<?php

class HeadingHandler extends Handler
{
    public function handle($request): ?string
    {
        // Check if this is a heading element
        if ($request['node']->nodeName === 'text:h') {
            $content = '';
            $styleClass = '';
            $level = 1; // Default heading level

            // Extract heading level if available
            if ($request['node']->hasAttribute('text:outline-level')) {
                $level = (int)$request['node']->getAttribute('text:outline-level');
                // Ensure level is between 1 and 6
                $level = max(1, min(6, $level));
            }

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

            // Create HTML heading with appropriate level and class
            return "<h$level" . ($styleClass ? " class=\"$styleClass\"" : "") . ">$content</h$level>";
        }

        // Not a heading, pass to the next handler
        return parent::handle($request);
    }
}
