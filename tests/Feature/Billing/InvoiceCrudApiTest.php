<?php

namespace Tests\Feature\Billing;

use Martin\Billing\Invoice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoInvoice(array $overrides = []): Invoice {
        return factory(Invoice::class)->make($overrides);
    }

    private function createDemoInvoice(array $overrides = []): Invoice {
        $invoice = $this->makeDemoInvoice($overrides);
        $invoice->save();
        return $invoice->fresh();
    }

    private function getDataArrayWithoutMutations(Invoice $invoice): array {
        $invoiceDataArray = $invoice->toArray();
        unset($invoiceDataArray['generated_at']);
        unset($invoiceDataArray['sent_at']);
        unset($invoiceDataArray['paid_at']);
        $invoiceDataArray['amount_usd'] = round($invoiceDataArray['amount_usd'] * 100);
        $invoiceDataArray['usd_to_cad_rate'] = round($invoiceDataArray['usd_to_cad_rate'] * 100000);
        $invoiceDataArray['amount_cad'] = round($invoiceDataArray['amount_cad'] * 100);
        return $invoiceDataArray;
    }

    /** @test */
    public function invoice__index__happyPath() {
        $this->createDemoInvoice(['invoice_no' => 1241251]);

        $response = $this->callGet('/api/invoices');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'invoice_no' => 1241251
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'project_id',
                    'invoice_no',
                    'amount_usd',
                    'usd_to_cad_rate',
                    'amount_cad',
                    'generated_at',
                    'sent_at',
                    'paid_at',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function invoice__show__happyPath() {
        $invoice = $this->createDemoInvoice(['invoice_no' => 124512412]);

        $response = $this->callGet('/api/invoices/' . $invoice->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $invoice->id
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'invoice_no',
                'amount_usd',
                'usd_to_cad_rate',
                'amount_cad',
                'generated_at',
                'sent_at',
                'paid_at',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function invoice__show__404_for_invalid_id() {
        $invoice = $this->createDemoInvoice(['invoice_no' => 1235132]);
        $nonExistingId = ++ $invoice->id;
        $randomString = $invoice->invoice_no;

        $response = $this->callGet('/api/invoices/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/invoices/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function invoice__store__happyPath() {
        $invoiceData = $this->makeDemoInvoice(['invoice_no' => 12312512])->toArray();

        $response = $this->callPost('/api/invoices', $invoiceData);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'invoice_no' => 12312512,
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'invoice_no',
                'amount_usd',
                'usd_to_cad_rate',
                'amount_cad',
                'generated_at',
                'sent_at',
                'paid_at',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/invoices/' . $id);
    }

    /** @test */
    public function invoice__update__happyPath() {
        $invoice = $this->createDemoInvoice(['invoice_no' => 70034534]);
        $invoiceData = $this->getDataArrayWithoutMutations($invoice);

        $this->assertDatabaseHas('invoices', $invoiceData);
        $invoice->invoice_no = 10034534;

        $response = $this->callPatch('/api/invoices/' . $invoice->id, $invoice->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'project_id',
                'invoice_no',
                'amount_usd',
                'usd_to_cad_rate',
                'amount_cad',
                'generated_at',
                'sent_at',
                'paid_at',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'invoice_no'  => 10034534,
            ]);
    }

    /** @test */
    public function invoice__delete__happyPath() {
        $invoice = $this->createDemoInvoice(['invoice_no' => 1245123]);
        $invoiceData = $this->getDataArrayWithoutMutations($invoice);
        $this->assertDatabaseHas('invoices', $invoiceData);

        $response = $this->callDelete('/api/invoices/' . $invoice->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function invoice__delete__404_for_invalid_id() {
        $invoice = $this->createDemoInvoice(['invoice_no' => 3434256]);
        $nonExistingId = ++ $invoice->id;
        $randomString = $invoice->invoice_no;

        $response = $this->callDelete('/api/invoices/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/invoices/' . $randomString);
        $response->assertStatus(404);
    }
}
