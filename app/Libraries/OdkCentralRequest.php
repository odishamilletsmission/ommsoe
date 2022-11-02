<?
namespace App\Libraries;
use CodeIgniter\API\ResponseTrait;

class OdkCentralRequest
{

    public $api_url;

    private $token;

    private $client;

    private $response;

    /**
     * Init
     *
     */
    public function __construct() {

        $this->api_url = env('odk.url');

        $auth = new OdkCentralAuth();

        $this->token = $auth->getAccessToken();

        $this->client = \Config\Services::curlrequest();

    }

    /**
     * GET METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     */
    public function get(string $endpoint, array $params = [], array $headers = [])
    {

        $params['headers']=$headers;
        //print_r($params);
        try {
            $this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                            ->get($this->api_url . $endpoint, $params);
                                        
           
        } catch (\Exception $exception) {

            return $exception;

        }

        return $this->response();

    }

    /**
     * GET RAW METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     */
    public function getBody(string $endpoint, array $params = [], array $headers = [])
    {

        $params['headers']=$headers;
        $response='';
        try {

            $response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                     ->get($this->api_url . $endpoint, $params);


        } catch (\Exception $exception) {

            return $exception;

        }

        return $response;

    }

    /** POST METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @param object $file
     */
    public function post(string $endpoint, array $params = [], array $headers = [], $file = null)
    {
        //print_r($file);
        $response='';
        $parameter=[];
       // $parameter['headers']=$headers;
        $parameter['http_errors']= false;
        $parameter['json']=$params;
        //print_r($parameter);
        if(!is_null($file)) {
            
            try {

                $this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                         ->setBody($file)
                                         ->post($this->api_url . $endpoint);
                                         

            } catch (\Exception $exception) {
                
                return $exception;

            }

        } else {
            
            try {

                $this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                     ->post($this->api_url . $endpoint, $parameter);
                //print_r($this->response);
            } catch (\Exception $exception) {
                return $exception;

            }

        }

        return $this->response();

    }


    /** PATCH METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     */
    public function patch(string $endpoint, array $params = [], array $headers = [])
    {
        $parameter['http_errors']= false;
        $parameter['json']=$params;
        try {
            $this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                    ->patch($this->api_url . $endpoint,$parameter);

        } catch (\Exception $exception) {

            return $exception;

        }

        return $this->response();

    }

    /** PUT METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     */
    public function put(string $endpoint,  $params, array $headers = [])
    {

        //echo $this->token;
        $parameter['http_errors']= false;
        $parameter['headers']= $headers;
        if(is_array($params)){
            $parameter['json']=$params;
        }else{
            $parameter['body']=$params;
        }
        //print_r($parameter);
        try {
            $this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                            ->setBody($params)
                                            ->put($this->api_url . $endpoint,$parameter);

        } catch (\Exception $exception) {

            return $exception;

        }

        return $this->response();

    }

    /** DELETE METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     */
    public function delete(string $endpoint, array $params = [], array $headers = [])
    {

        try {
            $this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                            ->delete($this->api_url . $endpoint,$params);

            

        } catch (\Exception $exception) {

            return $exception;

        }

        return $this->response();

    }

    /** DOWNLOAD METHOD
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     */
    public function download(string $endpoint, array $params = [], array $headers = [])
    {
		//$parameter['http_errors']= false;
        //$parameter['json']=$params;
        try {
			$this->response = $this->client->setHeader("Authorization","Bearer ".$this->token)
                                            ->get($this->api_url . $endpoint,$params);


           

        } catch (\Exception $exception) {

            return $exception;

        }
		return $this->response();
        //return \Response::make($this->response->body(), $this->response->status(), $this->response->headers());

    }

    /** Return the formated response
     *
     */
    public function response() {
        $body= $this->response->getBody();
		//print_r($body);
        return json_decode($body,true);
    }

}