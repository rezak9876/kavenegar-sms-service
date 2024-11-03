<?php

namespace Rezak\KavenegarSMS;

use Illuminate\Support\Facades\Http;

class KavenegarSMSService
{
    /**
     * Kavenegar API token
     *
     * @var string
     */
    protected string $kavenegarToken;

    /**
     * Kavenegar API base URL
     *
     * @var string
     */
    protected string $kavenegarBaseUrl = 'https://api.kavenegar.com/v1/';

    /**
     * The template name for the SMS
     *
     * @var string|null
     */
    protected ?string $templateName = null;

    /**
     * The phone number to send SMS to
     *
     * @var string|null
     */
    protected ?string $phone = null;

    /**
     * Additional parameters for the SMS template
     *
     * @var array
     */
    protected array $params = [];

    /**
     * Constructor to initialize Kavenegar token
     */
    public function __construct(string $kavenegarToken)
    {
        $this->kavenegarToken = $kavenegarToken;
    }

    /**
     * Set the template name
     */
    public function setTemplateName(string $templateName): void
    {
        $this->templateName = $templateName;
    }

    /**
     * Set the phone number
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * Set the parameters for the template
     */
    public function setParams(array $params): void
    {
        $this->params = $this->formatParams($params);
    }

    /**
     * Send the templated SMS using Kavenegar API
     *
     * @return bool
     */
    public function sendTemplatedSMS(): bool
    {
        if (!$this->templateName || !$this->phone) {
            throw new \InvalidArgumentException('Template name and phone number are required.');
        }

        $body = array_merge(
            [
                'receptor' => $this->phone,
                'template' => $this->templateName,
                'sender' => '2000500666',
            ],
            $this->params
        );

        $response = Http::post($this->kavenegarBaseUrl . $this->kavenegarToken . '/verify/lookup.json', $body);

        if ($response->failed()) {
            return false;
        }

        $result = $response->json();

        return isset($result['return']['status']) && $result['return']['status'] === 200;
    }

    /**
     * Format parameters to Kavenegar template tokens
     */
    protected function formatParams(array $params): array
    {
        if (empty($params)) {
            return ['token' => '.'];
        }

        $formattedParams = [];

        foreach (array_values($params) as $key => $param) {
            switch ($key) {
                case 0:
                    $formattedParams['token'] = $param;
                    break;
                case 1:
                    $formattedParams['token10'] = $param;
                    break;
                case 2:
                    $formattedParams['token20'] = $param;
                    break;
                case 3:
                    $formattedParams['token2'] = $param;
                    break;
                case 4:
                    $formattedParams['token3'] = $param;
                    break;
            }
        }

        return $formattedParams;
    }
}
