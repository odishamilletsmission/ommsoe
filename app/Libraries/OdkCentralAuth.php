<?php
namespace App\Libraries;

class OdkCentralAuth
{

    private $api_url;

    private $email;

    private $password;

    private $expires = 3600;

    private $client;


    public function __construct() {

        $this->api_url = env('odk.url');
        
        $this->email = env('odk.email');

        $this->password = env('odk.password');

        $this->client = \Config\Services::curlrequest();
        

    }

    /**
     * Generate the access token that will be used to make request to the ODK Central API.
     *
     */
    public function generateAccessToken() {

        $endpoint = "/sessions";

        //echo $this->api_url . $endpoint;
        $response = $this->client->post($this->api_url . $endpoint, [
            'json'=>[
                'email' => $this->email,
                'password' => $this->password,
            ]
        ]);

        $body = $response->getBody();
       // echo "<pre>";
        //print_r($body);
        $token = json_decode($body)->token;
        cache()->save('ODKAccessToken', $token, $this->expires);
        return $token;
       
    }


    /**
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken() {
        $cache = \Config\Services::cache();
        $ODKAccessToken = $cache->get('ODKAccessToken');
        if (!$ODKAccessToken) {
            $ODKAccessToken=$this->generateAccessToken();
        }

        return $ODKAccessToken;

    }

    /**
     * Destroy the ODK Central session.
     *
     * TODO : check the call and return boolean
     * 
     * @return boolean
     */
    public function destroyAccessToken() {

        $endpoint = "/sessions";

        $response = $this->client->setHeader("Authorization","Bearer ".$this->getAccessToken())
                                            ->delete($this->api_url . $endpoint,[
                                                'token' =>  $this->getAccessToken()
                                            ]);

        return json_decode($response->getBody());
    }

}