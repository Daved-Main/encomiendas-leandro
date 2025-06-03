<?php
namespace app\infrastructure\mail;

use DateTime;

class EmailJsService
{
    private string $serviceId;
    private string $templateIdWelcome;
    private string $templateIdVerify;
    private string $userId;
    private string $accessToken;
    private string $apiUrl;

    public function __construct()
    {
        // LEER desde $_ENV, no getenv()
        $this->serviceId         = $_ENV['EMAILJS_SERVICE_ID']       ?? '';
        $this->templateIdWelcome = $_ENV['EMAILJS_TEMPLATE_WELCOME'] ?? '';
        $this->templateIdVerify  = $_ENV['EMAILJS_TEMPLATE_VERIFY']  ?? '';
        $this->userId            = $_ENV['EMAILJS_USER_ID']          ?? '';
        $this->accessToken       = $_ENV['EMAILJS_ACCESS_TOKEN']     ?? '';
        // Si en .env no existe, usa este valor por defecto
        $this->apiUrl            = $_ENV['EMAILJS_API_URL']
                                   ?? 'https://api.emailjs.com/api/v1.0/email/send';

        // DEBUG temporal: lanza en el log si falta algo
        if (
            empty($this->serviceId) ||
            empty($this->templateIdWelcome) ||
            empty($this->templateIdVerify) ||
            empty($this->userId) ||
            empty($this->accessToken)
        ) {
            error_log("[EmailJsService] 🔴 Alguna variable EMAILJS NO está definida");
        } else {
            error_log("[EmailJsService] ✅ Variables EMAILJS cargadas correctamente");
        }
    }

    private function callApi(array $payload): bool
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        error_log("[EmailJsService] HTTP Code: $httpCode");
        error_log("[EmailJsService] Response: $response");

        curl_close($ch);
        return $httpCode === 200;
    }

    public function sendWelcomeEmail(string $email, string $userName): bool
    {
        $payload = [
            'service_id'      => $this->serviceId,
            'template_id'     => $this->templateIdWelcome,
            'user_id'         => $this->userId,
            'accessToken'     => $this->accessToken,
            'template_params' => [
                'user_name'    => $userName,
                'email'        => $email,
                'company_name' => 'Encomiendas Leandro',
                'website_link' => 'https://tu-dominio.local',
            ],
        ];

        return $this->callApi($payload);
    }

    public function sendVerifyEmail(string $email, string $userName, string $code, DateTime $expiresAt): bool
    {
        $payload = [
            'service_id'      => $this->serviceId,
            'template_id'     => $this->templateIdVerify,
            'user_id'         => $this->userId,
            'accessToken'     => $this->accessToken,
            'template_params' => [
                'user_name'    => $userName,
                'email'        => $email,
                'passcode'     => $code,
                'time'         => $expiresAt->format('H:i'),
                'company_name' => 'Encomiendas Leandro',
            ],
        ];

        return $this->callApi($payload);
    }
}
