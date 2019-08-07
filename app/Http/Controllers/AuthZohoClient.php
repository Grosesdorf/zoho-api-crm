<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AuthZohoClient extends Controller
{
    private $auth_uri = 'https://accounts.zoho.com/oauth/v2/auth';
    private $token_uri = 'https://accounts.zoho.com/oauth/v2/token';
    private $scope = 'ZohoCRM.modules.all';
    private $access_type = 'online';
    private $redirect_uri = 'https://7569567e.ngrok.io/oauth2/callback';
    private $client = '';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getGrantToken()
    {
        try {
            $response = $this->client->request('GET', $this->auth_uri, [
                // Headers берем с браузера при первой генерации grant token
                'headers' => [
                    'User-Agent'        => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
                    'Accept'            => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                    'Accept-Encoding'   => 'gzip, deflate, br',
                    'Accept-Language'   => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,de;q=0.6,id;q=0.5,uk;q=0.4',
                    'Cookie'            => 'a8c61fa0dc=8db261d30d9c85a68e92e4f91ec8079a; rtk=fa185471-1324-4777-8a66-e5f67adcbe4a; stk=9986883694356dec1f01c0b0b9b1812f; dcl_pfx_lcnt=0; dcl_pfx=us; dcl_bd=zoho.com; is_pfx=false; IAMTFATICKET_652046608=652046607.652046608.309a8b55db3170533e37c8f2845dd5bf37b2235bc8dcbe0e0974f6bf6f73201718257d5a406ed1b046769d18d0212a8b375b8a8228ff6fc50975c20a94c76024; zabUserId=1564386006276zabu0.8409993679409238; a6c51d9354=dcb92d0f99dd7421201f8dc746d54606; _iamadt=18f546a53a3bbce4b7c444c3d0c6433c64d23d3a12874ef172a029a88b73117f9bc4cde51ac305e93a5b5be294ab855cfed7b3a671896fa7a4c08d4767d5c4ff; _iambdt=1f0a9b28756b1983d8f0c482681abc7cc4def2d5cc4258e0221aa7868c4754dfbae3decabb47fba20fedea5024610335c9c35f97298e708faa90b7c50bb5224e; _z_identity=true; zidp=P; com_chat_owner=1565149789643; iamcsr=36cec783221d98060dc4f8f2877c69768ecf01912f1e137cc568cceec54dc986c4889476d26e45b0f73dfd0733e6e678579259bf2c5c52d8fa8bb45010726996; JSESSIONID=FEA8144C3C7F78FD198FB290284114BC',
                    'Host'              => 'accounts.zoho.com',
                ],
                'query' => [
                    'scope' => $this->scope,
                    'client_id' => env('ZOHO_ID_CLIENT'),
                    'response_type' => 'code',
                    'access_type' => $this->access_type,
                    'redirect_uri' => $this->redirect_uri,
                ]
            ]);
            Log::info('Status code(getGrantToken): '.$response->getStatusCode().$response->getReasonPhrase());
        } catch (GuzzleException $e) {
            Log::warning('Error get grant token: '.$e->getMessage());
        }
    }

    public function getAccessToken(Request $request)
    {
        $params = $request->query();
        if ($params['code'])
        {
            Log::info('Request grant token: '. $params['code']);

            try {
                $response = $this->client->request('POST', $this->token_uri, [
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'client_id' => env('ZOHO_ID_CLIENT'),
                        'client_secret' => env('ZOHO_SECRET'),
                        'redirect_uri' => $this->redirect_uri,
                        'code' => $params['code'],
                    ]
                ]);
                Log::info('Status code(getAccessToken): '.$response->getStatusCode().$response->getReasonPhrase());
                Log::info('Body(getAccessToken): '.$response->getBody());
            } catch (GuzzleException $e) {
                Log::warning('Error get grant token: '.$e->getMessage());
            }
        }
    }
}
