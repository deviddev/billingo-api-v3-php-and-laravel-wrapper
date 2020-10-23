<?php

namespace Deviddev\BillingoApiV3Wrapper;

use Exception;
use Illuminate\Support\Arr;
use Swagger\Client\Configuration as SwaggerConfig;

class BillingoApiV3Service
{

    /**
     * Store called api instance
     *
     * @var Swagger\Client\Api\$Object
     */
    protected $api;

    /**
     * Store called api name
     *
     * @var string
     */
    protected $apiName = null;

    /**
     * Store config instance
     *
     * @var Swagger\Client\Configuration
     */
    protected $config;

    /**
     * Store data
     *
     * @var array
     */
    protected $data = null;

    /**
     * Store called model instance
     *
     * @var object
     */
    protected $model = null;

    /**
     * Store model class name
     *
     * @var string
     */
    protected $modelClassName = null;

    /**
     * Store responses
     *
     * @var array
     */
    protected $response = null;

    /**
     * Call the default configuration and set up api key
     */
    protected function __construct()
    {
        $this->config = SwaggerConfig::getDefaultConfiguration()
            ->setApiKey('X-API-KEY', config('billingo-api-v3-wrapper.api_key'));
    }

    /**
     * Check if given class is exsits.
     *
     * @param string $className
     *
     * @throws Exception
     * @return void
     */
    protected function classExists(string $className): void
    {
        if (!class_exists($className)) {
            throw new Exception($className . ' class does not exsits!');
        }
    }

    /**
     * Check if data is present
     *
     * @param array $data
     *
     * @throws Exception
     * @return void
     */
    protected function isData(array $data = null): void
    {
        if (is_null($this->data) and is_null($data)) {
            throw new Exception('Data not set!');
        }
    }

    /**
     * Check if gicen method exists in given class (api instance)
     *
     * @param string $methodName
     *
     * @throws Exception
     * @return void
     */
    protected function methodExists(string $methodName): void
    {
        if (!method_exists($this->api, $methodName)) {
            throw new Exception($methodName . ' method does not exsits!');
        }
    }

    /**
     * Set callable method name
     *
     * @param string $name
     * @param boolean $suffix
     *
     * @return string
     */
    protected function setMethodName(string $name, bool $suffix = false): string
    {
        $methodName = $name . ($suffix ? $this->apiName : '');

        $this->methodExists($methodName);

        return $methodName;
    }

    /**
     * Mapping array and if it's conatins object convert it to array because swagger return mixed object and arrays
     *
     * @param array $item
     *
     * @return array
     */
    protected function toArray(array $item): array
    {
        return \array_map(function ($item) {
            if (\is_object($item)) {
                return Arr::collapse((array)$item);
            }
            if (\is_array($item)) {
                return $this->toArray($item);
            }
            return $item;
        }, $item);
    }

    /**
     * Make a new api instace
     *
     * @param string $name
     *
     * @throws Exception (classExists)
     * @return self
     */
    public function api(string $name): self
    {
        $className = '\\Swagger\\Client\\Api\\' . $name . 'Api';

        $this->classExists($className);

        $this->apiName = $name;

        $this->api = new $className(
            new \GuzzleHttp\Client(),
            $this->config
        );

        return $this;
    }

    /**
     * Get id from response
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->response['id'];
    }

    /**
     * Get repsonse
     *
     * @return Array
     */
    public function getResponse(): array
    {
        if (\is_object($this->response)) {
            return Arr::collapse($this->toArray((array)$this->response));
        }

        if (\is_array($this->response)) {
            return $this->toArray($this->response);
        }

        return (array)$this->response;
    }

    /**
     * Setup data for model
     *
     * @param array $data
     *
     * @return self
     */
    public function make(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Make a new model instance
     *
     * @param string $name
     * @param array $data
     *
     * @return self
     */
    public function model(string $name, array $data = null): self
    {
        $this->modelClassName = '\\Swagger\\Client\\Model\\' . $name;

        if (!is_null($data)) {
            $this->make($data);
        }

        $this->classExists($this->modelClassName);
        $this->isData();

        $this->model = new $this->modelClassName($this->data);

        return $this;
    }
}
