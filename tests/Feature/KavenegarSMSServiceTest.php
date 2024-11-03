<?php

use Illuminate\Support\Facades\Http;
use Rezak\KavenegarSMS\KavenegarSMSService;

beforeEach(function () {
    $this->service = new KavenegarSMSService('your_kavenegar_token');
});

it('sends a templated SMS successfully', function () {
    Http::fake([
        'https://api.kavenegar.com/v1/*' => Http::response(['return' => ['status' => 200]], 200)
    ]);

    $this->service->setTemplateName('test_template');
    $this->service->setPhone('09123456789');
    $this->service->setParams(['param1']);

    $result = $this->service->sendTemplatedSMS();
    expect($result)->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.kavenegar.com/v1/your_kavenegar_token/verify/lookup.json'
            && $request->data()['receptor'] === '09123456789'
            && $request->data()['template'] === 'test_template'
            && $request->data()['sender'] === '2000500666';
    });
});

it('returns false if sending SMS fails', function () {
    Http::fake([
        'https://api.kavenegar.com/v1/*' => Http::response(status: 500),
    ]);

    $this->service->setTemplateName('test_template');
    $this->service->setPhone('09123456789');
    $this->service->setParams(['param1']);

    // Call sendTemplatedSMS and expect a failure result
    $result = $this->service->sendTemplatedSMS();
    expect($result)->toBeFalse();
});
