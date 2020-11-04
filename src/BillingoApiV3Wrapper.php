<?php

namespace Deviddev\BillingoApiV3Wrapper;

use Deviddev\BillingoApiV3Wrapper\Services\BillingoApiV3Service;
use Exception;
use Illuminate\Support\Facades\Storage;

class BillingoApiV3Wrapper extends BillingoApiV3Service
{
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
     * Call parent constructor
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey = null)
    {
        parent::__construct($apiKey);
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
        $this->createResponse('cancel', [$id], true);

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
        $this->createResponse('checkTaxNumber', [$taxNumber]);

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
        $this->createResponse('create', [$this->model], true);

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
        $this->createResponse('createDocumentFromProforma', [$id]);

        return $this;
    }

    /**
     * Delete delete$apiName method
     *
     * @param integer $id
     *
     * @return self
     */
    public function delete(int $id): self
    {
        $this->createResponse('delete', [$id], true);

        return $this;
    }

    /**
     * Delete payment
     *
     * @param integer $id
     *
     * @return self
     */
    public function deletePayment(int $id): self
    {
        $this->createResponse('deletePayment', [$id]);

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
        $filename = $id . ($extension ?? $this->extension);

        Storage::put(
            ($path ?? $this->downloadPath) . $filename,
            $this->createResponse('download', [$id], true, true)
        );

        $this->response = ['path' => ($path ?? $this->downloadPath) . $filename];

        return $this;
    }

    /**
     * Get get$apiName method
     *
     * @param integer $id
     *
     * @return self
     */
    public function get(int $id): self
    {
        $this->createResponse('get', [$id], true);

        return $this;
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
        $this->createResponse('getPublicUrl', [$id]);

        return $this;
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
        $this->createResponse(
            'list',
            [
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
            ],
            true
        );

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
        $this->createResponse('update', [$this->model, $id], true);

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
        $this->createResponse('send', [$id], true);

        return $this;
    }
}
