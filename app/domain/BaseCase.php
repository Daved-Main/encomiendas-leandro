<?php
// app/domain/BaseCase.php


namespace app\domain;

use app\domain\entities\BaseResponse;

abstract class BaseCase{

    private mixed $data = null;
    private mixed $attributes;

    protected function setData(mixed $data): void {
        $this->data = $data;
    }

    protected function getData(): mixed{
        return $this->data;
    }

    public function getAttributes(){
        return $this->attributes;
    }

    public function setAttributes($attributes): BaseCase{
        $this->attributes = $attributes;
        return $this;
    }

    abstract public function execute() : BaseCase;

    abstract protected function transform() : array;

    public function getResponse(): BaseResponse{
        return new BaseResponse('OK', $this->data);
    }

}

?>