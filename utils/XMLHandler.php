<?php

/**
 * Class mère pour le pattern "Chain of Responsibility"
 */
abstract class XMLHandler
{
    protected ?XMLHandler $nextHandler = null;

    public function setNextHandler(XMLHandler $nextHandler): void
    {
        $this->nextHandler = $nextHandler;
    }

    public function handle(string $xml): string
    {
        $result = $this->process($xml);

        if ($this->nextHandler !== null) {
            return $this->nextHandler->handle($result);
        }

        return $result;
    }

    abstract protected function process(string $xml): string;
}
