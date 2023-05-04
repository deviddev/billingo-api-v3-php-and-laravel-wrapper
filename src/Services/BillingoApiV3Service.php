<?php

namespace Deviddev\BillingoApiV3Wrapper\Services;

use Exception;
use Illuminate\Support\Arr;
use Swagger\Client\Configuration as SwaggerConfig;
use Deviddev\BillingoApiV3Wrapper\Traits\ProcessErrorsTrait;
use Deviddev\BillingoApiV3Wrapper\Exceptions\BillingoApiException;

class BillingoApiV3Service
{

    use ProcessErrorsTrait;

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
     * Store with http info for methods
     *
     * @var boolean
     */
    protected $withHttpInfo = false;

    /**
     * Call the default configuration and set up api key
     *
     * @param string $apiKey
     */
    protected function __construct(string $apiKey = null)
    {
        $this->config = SwaggerConfig::getDefaultConfiguration()
            ->setApiKey('X-API-KEY', $this->isLaravel() ? config('billingo-api-v3-wrapper.api_key') : $apiKey);
    }

    /**
     * Check that the environment is Laravel
     *
     * @return boolean
     */
    protected function isLaravel(): bool
    {
        return \function_exists('app') && app() instanceof \Illuminate\Foundation\Application;
    }

    /**
     * Check if given class is exists.
     *
     * @param string $className
     *
     * @throws Exception
     * @return void
     */
    protected function classExists(string $className): void
    {
        if (!class_exists($className)) {
            throw new Exception($className . ' class does not exists!');
        }
    }

    /**
     * Create response
     *
     * @param string $methodName
     * @param array $params
     * @param bool $methodSuffix
     * @param bool $customResponse
     *
     * @return void
     */
    protected function createResponse(string $methodName, array $params, bool $methodSuffix = false, bool $customResponse = false)
    {
        try {
            $this->response =
                $customResponse ?: \call_user_func_array(
                    array(
                        $this->api,
                        $this->setMethodName($methodName, $methodSuffix)
                    ),
                    $params
                );
        } catch (\Throwable $th) {
            $message = $this->error($th->getMessage())->response();

            throw new BillingoApiException($message);
        }

        $this->setResponse();
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
        if (is_null($this->data) && is_null($data)) {
            throw new Exception('Data not set!');
        }
    }

    /**
     * Check if given method exists in given class (api instance)
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
        $methodName = $name;

        if ($suffix) {
            $methodName .= $this->apiName;

            if ($this->withHttpInfo) {
                $methodName .= 'withHttpInfo';
            }
        }

        $this->methodExists($methodName);

        return $methodName;
    }

    /**
     * Set response
     *
     * @return void
     */
    protected function setResponse(): void
    {
        if (\is_object($this->response)) {
            $this->response = Arr::collapse($this->toArray((array)$this->response));
        }

        if (\is_array($this->response)) {
            $this->response = $this->toArray($this->response);
        }

        $this->response = (array)$this->response;
    }

    /**
     * Mapping array and if it's contains object convert it to array because swagger return mixed arrays and objects with protected and private properties
     *
     * @param array $item
     *
     * @return array
     */
    protected function toArray(array $item): array
    {
        return \array_map(function ($item) {
            if (\is_object($item)) {
                if ($item instanceof \DateTime) {
                    return $item->format('Y-m-d');
                }
                return Arr::collapse((array)$item);
            }
            if (\is_array($item)) {
                return $this->toArray($item);
            }
            return $item;
        }, $item);
    }

    /**
     * Make a new api instance
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
     * Get the underlaying Swagger API Object
     *
     * @return Swagger\Client\Api\$Object
     */
    public function getApi()
    {
        return $this->api;
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
     * Get response
     *
     * @return Array
     */
    public function getResponse(): array
    {
        return $this->response;
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

    /**
     * Set withHttpInfo
     *
     * @return void
     */
    public function withHttpInfo()
    {
        $this->withHttpInfo = true;

        return $this;
    }
}
