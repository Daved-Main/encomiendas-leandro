<?php

// app/domain/entities/BaseResponse.php

    namespace app\domain\entities;

    class BaseResponse {

        public string $message;
        public mixed $data;

        public function __construct(string $message, mixed $data) {

            $this->message= $message;
            $this->data= $data;
        }
        public function getData(): mixed
        {
            return $this->data;
        }
    }

?>