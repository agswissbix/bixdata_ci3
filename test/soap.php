<?php

class NtlmClient extends SoapClient
{
    protected $options;


    public function __construct($url, $options = [])
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

/**
 * @package SoapAccess
 * @license MIT License <http://opensource.org/licenses/mit-license.html>
 */
/*
namespace MikeFunk\SoapAccess;

use SoapVar;
use SoapHeader;
use SoapClient;

/**
 * SoapAccess
 *
 * @author Michael Funk <mike@mikefunk.com>

 /* 
class SoapAccess
{

    /**
     * holds the instantiated SOAP object. Can be used to call SOAP methods
     * directly.

    public $client;

    /**
     * setup soap object.
     *
     * example config:
     * $config['wsdl'] = 'http://api.stormpost.datranmedia.com/services/SoapRequestProcessor?wsdl';
     * $config['namespace'] = 'https://api.stormpost.datranmedia.com/services/SoapRequestProcessor';
     * $config['username'] = 'myusername';
     * $config['password'] = 'mypassword';
     * $config['soap_options'] = array(
     *     // 'soap_version'=>SOAP_1_2,
     *     'exceptions'=>true,
     *     'trace'=>1,
     *         'cache_wsdl'=>WSDL_CACHE_NONE
     * );
     *
     * @param array $config username, password, namespace, wsdl, soap_options
     *//*
    public function __construct(array $config)
    {
        // set auth header
        $credentials = (object)array('username' => $config['username'], 'password' => $config['password']);
        $auth_object = new SoapVar($credentials, SOAP_ENC_OBJECT);
        $header =  new SoapHeader($config['namespace'], "authInfo", $auth_object);

        // initialize soap object
        $this->client = @new SoapClient($config['wsdl'], $config['soap_options']);
        $this->client->__setSoapHeaders(array($header));
        var_dump($this->client->__getFunctions());
        $soapResult = $this->client->__soapCall('TestWS', array()) ;
        var_dump($soapResult);    
    }
}
*/


//$soapURL="http://dynamic1.aboutx.local:7047/DynamicsNAV90/WS/TEST%202016.09.15/Codeunit/WsManager";
$soapURL="http://Dynamic1.aboutx.local:7047/DynamicsNAV90/WS/About-X/Codeunit/WsManager";
//var_dump(file_get_contents($soapURL));
$soapURL = "http://localhost:8822/jdocweb/test/test.wsdl" ;
//libxml_disable_entity_loader(false);
//$context = ['socket' => ['bindto' => '10.0.0.8']];
//$soapParameters = Array('login' => "a.galli", 'password' => "Eraclea2014.1",'stream_context' => stream_context_create($context)) ;

//$oapClient = new SoapClient($soapURL, $soapParameters);
$config['wsdl'] = $soapURL;
$config['namespace'] = $soapURL;
$config['username'] = 'a.galli';
$config['password'] = 'Eraclea2014.1';
$config['soap_options'] = array(
'soap_version'=>SOAP_1_2,
'exceptions'=>true,
'trace'=>1,
'cache_wsdl'=>WSDL_CACHE_NONE
);
//new SoapAccess($config);
$NtlmClient = new NtlmClient($soapURL,[
    'login'      => 'aboutx\a.galli',
    'password'   => 'Eraclea2014.1',
    'exceptions' => true
]);
$arguments=array(
    'pNo'  => 'testsoap8',
    'pName' => 'azienda test soap 8',
    'pName2' => '',
    'pAddress' => '',
    'pAddress2' => '',
    'pPostCode' => '',
    'pCity' => '',
    'pCounty' => '',
    'pCountryRegion' => '',
    'pMail' => '',
    'pContact' => '',
    'pPhoneNo' => '',
    'pTelexNo' => '',
    'pFaxNo' => '',
    'pVatRegNo' => '',
    'pBlocked' => '',
    'pCustomerModel' => 'CLIE01',
    'returnCustNo' => ''
);
$testws_result=$NtlmClient->__soapCall('CustomerInsertUpdate', array('parameters' => $arguments)) ;
print_r($testws_result);

//var_dump($soapClient->__getFunctions());
//$soapResult = $soapClient->__soapCall('TestWS', array()) ;


?>

