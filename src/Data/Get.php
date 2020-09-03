<?php declare(strict_types=1);

namespace Zablose\Allog\Data;

/**
 * Class with data from the $_GET array.
 */
class Get
{
    private array $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?? $_GET;
    }

    public function toJsonAsObject(): string
    {
        return json_encode($this->data, JSON_FORCE_OBJECT);
    }
}
