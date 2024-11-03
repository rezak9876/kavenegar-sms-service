<?php

use Rezak\KavenegarSMS\KavenegarSMSService;

beforeEach(function () {
    $this->service = new KavenegarSMSService('test-token');
});

it('throws exception when template name is missing during sending', function () {
    $this->service->setPhone('09123456789');
    $this->service->sendTemplatedSMS();
})->throws(\InvalidArgumentException::class, 'Template name and phone number are required.');

it('throws exception when phone number is missing during sending', function () {
    $this->service->setTemplateName('welcome_template');
    $this->service->sendTemplatedSMS();
})->throws(\InvalidArgumentException::class, 'Template name and phone number are required.');
