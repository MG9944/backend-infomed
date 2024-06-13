<?php

namespace App\Service;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Twilio\Rest\Verify\V2\ServiceContext;

class OTPService
{
    private ServiceContext $twilio;

    /**
     * @throws ConfigurationException
     */
    public function __construct(private readonly string $twilioSID, private readonly string $twilioToken, private readonly string $twilioVerificationSID
    ) {
        $client = new Client($this->twilioSID, $this->twilioToken);
        $this->twilio = $client->verify->v2->services($this->twilioVerificationSID);
    }

    /**
     * @throws TwilioException
     */
    public function generateOTP(string $phoneNumber): void
    {
        $this->twilio->verifications->create($phoneNumber, 'sms');
    }

    /**
     * @throws TwilioException
     */
    public function isValidOTP(string $otp, string $phoneNumber): bool
    {
        $verificationResponse = $this->twilio->verificationChecks->create([
            'code' => $otp,
            'to' => $phoneNumber,
        ]);

        return 'approved' === $verificationResponse->status;
    }
}
