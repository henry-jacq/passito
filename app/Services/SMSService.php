<?php


namespace App\Services;

use App\Core\Config;
use Twilio\Rest\Client;


class SMSService
{
    protected Client $client;
    protected string $sender;
    protected string $defaultCountryCode;

    public function __construct(
        private readonly Config $config
    )
    {
        $this->initialize($this->config->get('notification.sms.twilio'));
    }
    
    public function initialize($config)
    {
        $this->client = new Client($config['sid'], $config['token']);
        $this->sender = $config['from'];
        $this->defaultCountryCode = $config['country_code'];
    }

    public function send(string $phoneNumber, string $message)
    {
        $validatedNumber = $this->validateAndFormatNumber($phoneNumber);

        $message = $this->client->messages->create(
            $validatedNumber,
            [
                'from' => $this->sender,
                'body' => $message
            ]
        );

        return $message;
    }

    protected function validateAndFormatNumber(string $number): string
    {
        $number = preg_replace('/[\s\-]/', '', $number);

        // If number starts with 0 or doesn't start with +, assume it needs the default country code
        if (!str_starts_with($number, '+')) {
            // Remove leading 0s
            $number = ltrim($number, '0');
            $number = $this->defaultCountryCode . $number;
        }

        // Final validation
        if (!preg_match('/^\+\d{10,15}$/', $number)) {
            throw new \InvalidArgumentException("Invalid phone number format after applying country code.");
        }

        return $number;
    }
}
