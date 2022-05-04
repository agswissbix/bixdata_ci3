<?php
class NtlmClient extends SoapClient
{
    protected $options;



    public function __construct($url='test\\test.wsdl', $options = [])
    {
        $this->options = $options; // so we can access the credentials
        parent::__construct($url, $options);
    }


    public function __doRequest($request, $location, $action, $version, $one_way = false)
    {
        $this->__last_request = $request;

        $handle = curl_init($location);

        $credentials = $this->options['login'] . ':' . $this->options['password'];
        $headers = [
            'Method: POST',
            'User-Agent: PHP-SOAP-CURL',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "' . $action . '"'
        ];

        curl_setopt($handle, CURLINFO_HEADER_OUT, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        // Authentication
        curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($handle, CURLOPT_USERPWD, $credentials);

        $response = curl_exec($handle);

        return $response;
    }
}
?>