<?php

namespace App\Services;

use Google\Client;
use Google\Exception;
use Google\Service\Sheets;

class GoogleSheetsService
{
    private Client $client;
    private Sheets $service;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Google Sheets API Symfony');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(__DIR__ . '/../../config/google_credentials.json');
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
    }


    /**
     * @throws \Google\Service\Exception
     */
    public function writeData(string $spreadsheetId, string $range, array $values): void
    {
        $body = new Sheets\ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];

        $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    }
}
