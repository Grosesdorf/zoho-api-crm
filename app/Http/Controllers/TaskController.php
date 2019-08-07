<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    private $uri = 'https://www.zohoapis.com/crm/v2/tasks';
    private $client = '';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function add($who_id, $module)
    {
        $input = [
            "data" => [
                [
                    'Subject' => "MegaTasks555",
                    '$se_module' => $module,
                    'What_Id' => $who_id,
                    'Status' => "Не запущена",
                    'Due_Date' => "2019-08-10",
                    'Priority' => "Low",
                ]
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
            Log::info('Status code(addTask: '.$response->getStatusCode().$response->getReasonPhrase());

        } catch (GuzzleException $e) {
            Log::warning('Error(addTask): '.$e->getMessage());
        }
    }
}
