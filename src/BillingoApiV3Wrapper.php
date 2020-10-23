<?php

namespace Deviddev\BillingoApiV3Wrapper;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class BillingoApiV3Wrapper
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
     * Download files path
     *
     * @var string
     */
    protected $downloadPath = 'invoices/';

    /**
     * Downloaded file extension
     *
     * @var string
     */
    protected $extension = '.pdf';

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
    public function __construct()
    {
        $this->config = \Swagger\Client\Configuration::getDefaultConfiguration()
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
     * Delete the invoice
     *
     * @param integer $id
     *
     * @return self
     */
    public function cancelInvoice(int $id): self
    {
        $methodName = 'cancel' . $this->apiName;

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($id);

        return $this;
    }

    /**
     * Check valid tax number
     *
     * @param string $tax_number
     *
     * @return self
     */
    public function checkTaxNumber(string $taxNumber): self
    {
        $methodName = 'checkTaxNumber';

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($taxNumber);

        return $this;
    }

    /**
     * Call create$apiName method
     *
     * @throws Exception (methodExists)
     * @return self
     */
    public function create(): self
    {
        $methodName = 'create' . $this->apiName;

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($this->model);

        return $this;
    }

    /**
     * Create invoice from proforma
     *
     * @param integer $id
     *
     * @return self
     */
    public function createInvoiceFromProforma(int $id): self
    {
        $methodName = 'createDocumentFromProforma';

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($id);

        return $this;
    }

    /**
     * Download document
     *
     * @param integer $id
     *
     * @return string
     */
    public function downloadInvoice(int $id, string $path = null, string $extension = null): self
    {
        $methodName = 'download' . $this->apiName;

        $this->methodExists($methodName);

        $filename = $id . ($extension ?? $this->extension);

        Storage::put(
            ($path ?? $this->downloadPath) . $filename,
            $this->api->$methodName($id)
        );

        $this->response['path'] = ($path ?? $this->downloadPath) . $filename;

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
     * Get invoice public url
     *
     * @param integer $id
     *
     * @return self
     */
    public function getPublicUrl(int $id): self
    {
        $methodName = 'getPublicUrl';

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($id);

        return $this;
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
     * Call list$apiName method
     *
     * @param array $conditions
     *
     * @return self
     */
    public function list(array $conditions): self
    {
        $methodName = 'list' . $this->apiName;

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName(
            $conditions['page'] ?? null,
            $conditions['per_page'] ?? 25,
            $conditions['block_id'] ?? null,
            $conditions['partner_id'] ?? null,
            $conditions['payment_method'] ?? null,
            $conditions['payment_status'] ?? null,
            $conditions['start_date'] ?? null,
            $conditions['end_date'] ?? null,
            $conditions['start_number'] ?? null,
            $conditions['end_number'] ?? null,
            $conditions['start_year'] ?? null,
            $conditions['end_year'] ?? null
        );

        return $this;
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
     * Call get$apiName method
     *
     * @param integer $id
     *
     * @return self
     */
    public function update(int $id): self
    {
        $methodName = 'update' . $this->apiName;

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($this->model, $id);

        return $this;
    }

    /**
     * Send invoice in email
     *
     * @param integer $id
     *
     * @return self
     */
    public function sendInvoice(int $id): self
    {
        $methodName = 'send' . $this->apiName;

        $this->methodExists($methodName);

        $this->response = $this->api->$methodName($id);

        return $this;
    }
}
