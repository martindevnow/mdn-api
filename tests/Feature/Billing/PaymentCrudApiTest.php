<?php

namespace Tests\Feature\Payments;

use Martin\Billing\Payment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoPayment(array $overrides = []): Payment {
        return factory(Payment::class)->make($overrides);
    }

    private function createDemoPayment(array $overrides = []): Payment {
        $payment = $this->makeDemoPayment($overrides);
        $payment->save();
        return $payment->fresh();
    }

    private function getDataArrayWithoutMutations(Payment $payment): array {
        $paymentDataArray = $payment->toArray();
        unset($paymentDataArray['received_at']);
        $paymentDataArray['amount_usd'] = round($paymentDataArray['amount_usd'] * 100);
        $paymentDataArray['usd_to_cad_rate'] = round($paymentDataArray['usd_to_cad_rate'] * 100000);
        $paymentDataArray['amount_cad'] = round($paymentDataArray['amount_cad'] * 100);
        return $paymentDataArray;
    }

    /** @test */
    public function payment__index__happyPath() {
        $this->createDemoPayment(['cheque_number' => 'TestPayment']);

        $response = $this->callGet('/api/payments');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'cheque_number' => 'TestPayment'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'received_at',
                    'cheque_number',
                    'amount_cad',
                    'amount_usd',
                    'usd_to_cad_rate',
                    'client_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function payment__show__happyPath() {
        $payment = $this->createDemoPayment(['cheque_number' => 'DemoPayment']);

        $response = $this->callGet('/api/payments/' . $payment->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $payment->id
            ])
            ->assertJsonStructure([
                'id',
                'received_at',
                'cheque_number',
                'amount_cad',
                'amount_usd',
                'usd_to_cad_rate',
                'client_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function payment__show__404_for_invalid_id() {
        $payment = $this->createDemoPayment(['cheque_number' => 'DemoPayment3']);
        $nonExistingId = ++ $payment->id;
        $randomString = $payment->cheque_number;

        $response = $this->callGet('/api/payments/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/payments/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function payment__store__happyPath() {
        $paymentData = $this->makeDemoPayment(['cheque_number' => 'ValidPaymentName'])->toArray();

        $response = $this->callPost('/api/payments', $paymentData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'cheque_number' => 'ValidPaymentName',
            ])
            ->assertJsonStructure([
                'id',
                'received_at',
                'cheque_number',
                'amount_cad',
                'amount_usd',
                'usd_to_cad_rate',
                'client_id',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/payments/' . $id);
    }

    /** @test */
    public function payment__update__happyPath() {
        $payment = $this->createDemoPayment(['cheque_number' => 'DemoPayment']);
        $paymentData = $this->getDataArrayWithoutMutations($payment);

        $this->assertDatabaseHas('payments', $paymentData);
        $payment->cheque_number = 'RenamedPayment';

        $response = $this->callPatch('/api/payments/' . $payment->id, $payment->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'received_at',
                'cheque_number',
                'amount_cad',
                'amount_usd',
                'usd_to_cad_rate',
                'client_id',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'cheque_number'  => 'RenamedPayment',
            ]);
    }

    /** @test */
    public function payment__delete__happyPath() {
        $payment = $this->createDemoPayment(['cheque_number' => 'TerriblePayment']);
        $paymentData = $this->getDataArrayWithoutMutations($payment);
        $this->assertDatabaseHas('payments', $paymentData);

        $response = $this->callDelete('/api/payments/' . $payment->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function payment__delete__404_for_invalid_id() {
        $payment = $this->createDemoPayment(['cheque_number' => 'DemoPayment3']);
        $nonExistingId = ++ $payment->id;
        $randomString = $payment->cheque_number;

        $response = $this->callDelete('/api/payments/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/payments/' . $randomString);
        $response->assertStatus(404);
    }
}
