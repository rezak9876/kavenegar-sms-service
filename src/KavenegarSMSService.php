<?php

namespace Rezak\KavenegarSMS;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class KavenegarSMSService
{
    protected string $kavenegarToken;
    protected string $kavenegarBaseUrl = 'https://api.kavenegar.com/v1/';
    protected ?string $templateName;
    protected ?string $phone;

    protected array $tokens = [
        'token' => null,
        'token2' => null,
        'token3' => null,
        'token10' => null,
        'token20' => null,
    ];

    public function __construct(string $kavenegarToken)
    {
        $this->kavenegarToken = $kavenegarToken;
    }

    public function setTemplateName(string $templateName): static
    {
        $this->templateName = $templateName;
        return $this;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function setToken(string $key, string $value): static
    {
        if (array_key_exists($key, $this->tokens)) {
            $this->tokens[$key] = $value;
        }
        return $this;
    }

    public function getParams(): array
    {
        return array_filter($this->tokens);
    }

    public function sendTemplatedSMS(): bool
    {
        $this->validateParameters();
        $body = $this->prepareRequestBody();
        $response = Http::post($this->kavenegarBaseUrl . $this->kavenegarToken . '/verify/lookup.json', $body);
        return $this->isResponseSuccessful($response);
    }

    protected function validateParameters(): void
    {
        if (empty($this->templateName) || empty($this->phone)) {
            throw new InvalidArgumentException('Template name and phone number are required.');
        }
    }

    protected function prepareRequestBody(): array
    {
        return array_merge(
            [
                'receptor' => $this->phone,
                'template' => $this->templateName,
                'sender' => '2000500666',
            ],
            $this->getParams()
        );
    }

    protected function isResponseSuccessful($response): bool
    {
        return !$response->failed() && isset($response->json()['return']['status']) && $response->json()['return']['status'] === 200;
    }
}
