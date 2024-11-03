<?php

namespace Rezak\KavenegarSMS;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class KavenegarSMSService
{
    /**
     * Kavenegar API token.
     *
     * @var string
     */
    protected string $kavenegarToken;

    /**
     * Kavenegar API base URL.
     *
     * @var string
     */
    protected string $kavenegarBaseUrl = 'https://api.kavenegar.com/v1/';

    /**
     * The template name for the SMS.
     *
     * @var string|null
     */
    protected ?string $templateName;

    /**
     * The phone number to send SMS to.
     *
     * @var string|null
     */
    protected ?string $phone;

    protected ?string $token;
    protected ?string $token2;
    protected ?string $token3;
    protected ?string $token10;
    protected ?string $token20;

    /**
     * Constructor to initialize Kavenegar token.
     *
     * @param string $kavenegarToken
     */
    public function __construct(string $kavenegarToken)
    {
        $this->kavenegarToken = $kavenegarToken;
    }

    /**
     * Set the template name.
     *
     * @param string $templateName
     * @return static
     */
    public function setTemplateName(string $templateName): static
    {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * Set the phone number.
     *
     * @param string $phone
     * @return static
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getParams(): array
    {
        $params = [];

        $tokens = [
            'token' => $this->token ?? null,
            'token2' => $this->token2 ?? null,
            'token3' => $this->token3 ?? null,
            'token10' => $this->token10 ?? null,
            'token20' => $this->token20 ?? null,
        ];
    
        foreach ($tokens as $key => $value) {
            if ($value !== null) {
                $params[$key] = $value;
            }
        }
    
        return $params;
    }

    /**
     * Send the templated SMS using Kavenegar API.
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function sendTemplatedSMS(): bool
    {
        $this->validateParameters();

        $body = $this->prepareRequestBody();

        $response = Http::post($this->kavenegarBaseUrl . $this->kavenegarToken . '/verify/lookup.json', $body);

        return $this->isResponseSuccessful($response);
    }

    /**
     * Validate the template name and phone number.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    protected function validateParameters(): void
    {
        if (!isset($this->templateName) || !isset($this->phone)) {
            throw new InvalidArgumentException('Template name and phone number are required.');
        }
    }

    /**
     * Prepare the request body for the SMS API.
     *
     * @return array
     */
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

    /**
     * Check if the response from the API is successful.
     *
     * @param \Illuminate\Http\Client\Response $response
     * @return bool
     */
    protected function isResponseSuccessful($response): bool
    {
        if ($response->failed()) {
            return false;
        }

        $result = $response->json();

        return isset($result['return']['status']) && $result['return']['status'] === 200;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function setToken2(string $token2): static
    {
        $this->token2 = $token2;
        return $this;
    }
    
    public function setToken3(string $token3): static
    {
        $this->token3 = $token3;
        return $this;
    }

    public function setToken10(string $token10): static
    {
        $this->token10 = $token10;
        return $this;
    }

    public function setToken20(string $token20): static
    {
        $this->token20 = $token20;
        return $this;
    }
}
