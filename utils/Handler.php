<?php

abstract class Handler
{
    protected ?Handler $handler = null;

    public function setNext(Handler $handler): Handler
    {
        $this->handler = $handler;

        return $handler;
    }

    public function handle($request): ?string
    {
        return $this->handler?->handle($request);

    }
}
