<?php declare(strict_types=1);

namespace Zablose\Allog\Data;

use Zablose\Allog\Db;

/**
 * Class with some data from the $_SERVER array as attributes.
 */
class Server
{
    /**
     * $_SERVER['HTTP_USER_AGENT']
     *
     * @var string
     */
    public string $http_user_agent = '';

    /**
     * $_SERVER['HTTP_REFERER']
     *
     * @var string
     */
    public string $http_referer = '';

    /**
     * $_SERVER['REMOTE_ADDR']
     *
     * @var string
     */
    public string $remote_addr = '';

    /**
     * $_SERVER['REQUEST_METHOD']
     *
     * @var string
     */
    public string $request_method = '';

    /**
     * $_SERVER['REQUEST_URI']
     *
     * @var string
     */
    public string $request_uri = '';

    /**
     * $_SERVER['REQUEST_TIME']
     *
     * @var integer
     */
    public int $request_time = 0;

    public function __construct()
    {
        $this->load();
    }

    private function load(): void
    {
        foreach (array_keys(get_object_vars($this)) as $attribute) {
            $this->$attribute = $_SERVER[strtoupper($attribute)] ?? '';
        }
    }

    public function toArray(): array
    {
        $data = (array) $this;

        $data['request_time'] = date(Db::DATE_FORMAT, $this->request_time);

        return $data;
    }
}
