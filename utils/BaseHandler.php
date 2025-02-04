<?php

abstract class BaseHandler implements Handler{
    protected ?Handler $next = null;

    #[\Override] public function setNext(Handler $handler): Handler
    {
        $this->next = $handler;
        return $handler;
    }

    #[\Override] public function getNext(): ?Handler
    {
        return $this->next;
    }

    abstract protected function handle(DOMElement $element): ?string;
}