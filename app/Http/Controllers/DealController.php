<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class DealController extends Controller
{
    private $uri = 'https://www.zohoapis.com/crm/v2/deals';
    private $client = '';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function add()
    {
        $input = [
            "data" => [
                [
                    "Deal_Name" => "MegaSuperPuper",
                    "Amount" => "15000",
                    "Account_Name" => "Иван Олэнь"
                ]
            ],
            "trigger" => [
                "approval",
                "workflow",
                "blueprint"
            ]
        ];

        try {
            $response = $this->client->request('POST', $this->uri, [
                'body' => json_encode($input),
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken '.env('ZOHO_ACCESS_TOKEN'),
                    'Content-Type' => 'application/json',
                ]
            ]);
            Log::info('Status code(addDeal): '.$response->getStatusCode().$response->getReasonPhrase());

            if ($response->getStatusCode() == 201)
            {
                $data = json_decode($response->getBody(), true);

                $task = new TaskController();
                $task->add($data['data'][0]['details']['id'], 'Deals');
            }
        } catch (GuzzleException $e) {
            Log::warning('Error(addDeal): '.$e->getMessage());
        }


    }
}
