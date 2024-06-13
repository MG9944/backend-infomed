<?php

namespace App\Dto;

class ResponseJson
{
    private string $result; // enum
    private ?string $errorMessage; // errorMessage
    private ?array $data; // data arrayka z danymi

    public function __construct(string $result, ?array $data, ?string $errorMessage = null)
    {
        $this->result = $result;
        $this->errorMessage = $errorMessage;
        $this->data = $data;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function toArray(): array
    {
        return [
            'result' => $this->result,
            'data' => $this->data,
            'error_message' => $this->errorMessage,
        ];
    }
}
