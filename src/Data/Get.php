<?php declare(strict_types=1);

namespace Zablose\Allog\Data;

class Get
{
    private array $data;

    public function __construct()
    {
        $this->data = $_GET;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}
