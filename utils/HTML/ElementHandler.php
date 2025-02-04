<?php

require_once "utils/XMLReader.php";

class ElementHandler extends BaseHandler{
    private SimpleXMLElement|null $currentNode = null;
    private array $nodeStack = [];
    private array $attributeMap = [];

    private $xmlReader;

    public function __construct() {
        $this->xmlReader = new XMLReader();
    }

    /**
     * @throws Exception
     */
    public function open(string $uri): true
    {
        if (!file_exists($uri)) {
            throw new InvalidArgumentException("File '$uri' does not exist");
        }

        $this->currentNode = new SimpleXMLElement(file_get_contents($uri));
        return true;
    }

    public function read(): bool
    {
        if ($this->currentNode === null) {
            return false;
        }

        // First try to get next node from existing stack
        while (!empty($this->nodeStack)) {
            $nextNode = array_shift($this->nodeStack);
            if ($nextNode instanceof SimpleXMLElement &&
                !$nextNode->getName()) {
                continue;
            }

            $this->currentNode = $nextNode;
            return true;
        }

        // If stack is empty, process children recursively
        if ($this->processChildren($this->currentNode)) {
            return true;
        }

        return false;
    }

    private function processChildren(SimpleXMLElement $node): bool
    {
        foreach ($node->children() as $child) {
            $this->nodeStack[] = $child;

            // Update attribute map for current node
            $this->updateAttributeMap($child);

            if (!empty($this->nodeStack)) {
                return true;
            }
        }
        return false;
    }

    private function updateAttributeMap(SimpleXMLElement $node): void
    {
        foreach ($node->attributes() as $key => $value) {
            $this->attributeMap[$node->getName()][$key] = (string)$value;
        }
    }

    public function getName(): string
    {
        return $this->currentNode?->getName();
    }

    public function getValue(): string
    {
        return $this->currentNode ?
            trim((string)$this->currentNode) : '';
    }

    public function getAttribute(string $name): ?string
    {
        return $this->getAttributeValue($name);
    }

    private function getAttributeValue($name) {
        if ($this->currentNode &&
            isset($this->attributeMap[$this->currentNode->getName()][$name])) {
            return $this->attributeMap[$this->currentNode->getName()][$name];
        }
        return null;
    }

    public function close(): void
    {
        $this->currentNode = null;
        $this->nodeStack = [];
        $this->attributeMap = [];
    }

    #[\Override] public function handle(DOMElement $element): ?string
    {
        // Convert ODT attributes to HTML
        $attributes = array_filter([
            'class' => $this->getAttributeValue('text:style-name'),
            'style' => $this->convertStyleAttributes($element->attributes)
        ]);

        return '<' . $element->tagName
            . ($attributes ? ' ' . http_build_query($attributes, '', ' ') : '')
            . '>' . $element->textContent . '</' . $element->tagName . '>';
    }

    private function convertStyleAttributes(DOMNamedNodeMap $attributes): string {
        $styles = [];
        foreach ($attributes as $attr) {
            if (str_starts_with($attr->name, 'fo:') || str_starts_with($attr->name, 'style:')) {
                $styles[$this->convertAttributeName($attr->name)] = $attr->value;
            }
        }
        return implode('; ', array_map(static function($k, $v) {
            return "$k: $v";
        }, array_keys($styles), $styles));
    }

    private function convertAttributeName(string $name): string {
        return str_replace(['fo:', 'style:'], '', $name);
    }
}
