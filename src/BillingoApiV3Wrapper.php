<?php

namespace Deviddev\BillingoApiV3Wrapper;

use Deviddev\BillingoApiV3Wrapper\Services\BillingoApiV3Service;
use Exception;
use Illuminate\Support\Facades\Storage;

use function array_diff_key;
use function array_flip;

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
     * In list method only allow these parameters
     *
     * @var array|string[]
     */
    protected array $allowed_parameters=['page','per_page','block_id','partner_id','payment_method','payment_status','start_date','end_date','start_number','end_number','start_year','end_year','type','query','paid_start_date','paid_end_date','fulfillment_start_date','fulfillment_end_date','last_modified_date','from','to','date','q','spending_date','spending_type','categories','currencies','payment_methods'];

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
     * Call createReceipt method
     *
     * @throws Exception (methodExists)
     * @return self
     */
    public function createReceipt(): self
    {
        $this->createResponse('createReceipt', [$this->model], true);

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
     * Create invoice from draft
     *
     * @param integer $id
     *
     * @return self
     */
    public function createInvoiceFromDraft(int $id): self
    {
        $this->createResponse('createDocumentFromDraft', [$id]);

        return $this;
    }

    /**
     * Create receipt from draft
     *
     * @param integer $id
     *
     * @return self
     */
    public function createReceiptFromDraft(int $id): self
    {
        $this->createResponse('createReceiptFromDraft', [$id]);

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
        $this->createResponse('download', [$id], true);

        Storage::put(
            ($path ?? $this->downloadPath) . $filename,
            $this->response[0]
        );

        $this->response = [
            'path' => ($path ?? $this->downloadPath) . $filename,
            'status' => $this->withHttpInfo ? $this->response[1] : null,
        ];

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
            array_diff_key($conditions, array_diff_key($conditions, array_flip($this->allowed_parameters))),
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
    
    /**
     * Send invoice in email
     *
     * @param integer $id
     *
     * @return self
     */
    public function getOnlineSzamlaStatus(int $id): self
    {
        $this->createResponse('getOnlineSzamlaStatus', [$id], true);

        return $this;
    }
}
