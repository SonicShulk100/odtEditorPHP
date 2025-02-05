<?php

abstract class Handler{
    protected Handler $nextHandler;

    public function setNext(Handler $handler): Handler{
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(XMLReader $request){
        return $this->nextHandler?->handle($request);

    }
}
