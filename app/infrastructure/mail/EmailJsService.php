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
    private bool $isDev;

    public function __construct()
    {
        // Mejor uso de entorno para producción
        $this->serviceId         = getenv('EMAILJS_SERVICE_ID')       ?: '';
        $this->templateIdWelcome = getenv('EMAILJS_TEMPLATE_WELCOME') ?: '';
        $this->templateIdVerify  = getenv('EMAILJS_TEMPLATE_VERIFY')  ?: '';
        $this->userId            = getenv('EMAILJS_USER_ID')          ?: '';
        $this->accessToken       = getenv('EMAILJS_ACCESS_TOKEN')     ?: '';
        $this->apiUrl            = getenv('EMAILJS_API_URL')          ?: 'https://api.emailjs.com/api/v1.0/email/send';

        // Detectar si estamos en local/desarrollo para permitir logs
        $this->isDev = getenv('APP_ENV') === 'local';

        // Validar variables críticas solo si estamos en desarrollo
        if ($this->isDev) {
            if (
                empty($this->serviceId) ||
                empty($this->templateIdWelcome) ||
                empty($this->templateIdVerify) ||
                empty($this->userId) ||
                empty($this->accessToken)
            ) {
                error_log("[EmailJsService] 🔴 Faltan variables críticas de entorno.");
            } else {
                error_log("[EmailJsService] ✅ Variables cargadas correctamente.");
            }
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
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($this->isDev) {
            error_log("[EmailJsService] HTTP Code: $httpCode");
            error_log("[EmailJsService] Response: $response");
            if ($curlError) {
                error_log("[EmailJsService] cURL Error: $curlError");
            }
        }

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
                'website_link' => 'https://encomiendasleandro.onrender.com',
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
