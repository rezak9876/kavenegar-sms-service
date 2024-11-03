<?php

use Illuminate\Support\Facades\Http;
use Rezak\KavenegarSMS\KavenegarSMSService;

beforeEach(function () {
    $this->service = new KavenegarSMSService('your_kavenegar_token');
});

// Test that an exception is thrown when required properties are not set
it('throws an exception when template name or phone is not set before sending', function () {
    // Attempt to send SMS without setting template or phone
    $this->service->sendTemplatedSMS();
})->throws(\InvalidArgumentException::class, 'Template name and phone number are required.');