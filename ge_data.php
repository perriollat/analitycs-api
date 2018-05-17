<?php

require_once 'vendor/autoload.php';
class GA{
    private $dimensions;
    private $metrics;
    private $start;
    private $end;

    public function __construct($dimensions, $metrics, $start, $end){
        $this->dimensions   = (string) $dimensions;
        $this->metrics      = (string) $metrics;
        $this->start        = $start;
        $this->end          = $end;
    }

    function initializeAnalytics(){
        $KEY_FILE_LOCATION = 'js/Prime-1476dd53b3dc.json';

        // criando e configurando um novo objeto cliente
        $client = new Google_Client();
        $client->setApplicationName("Projeto Dash");
        $client->setAuthConfig($KEY_FILE_LOCATION);

        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

        return $analytics;
    }

    public function getBrowser(){
        return $this->Browser;
    }

    function getFirstProfileId($analytics) {

        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();
                }else{
                    throw new Exception('No views (profiles) found for this user.');
                }
            }else{
                throw new Exception('No properties found for this user.');
            }
        }else{
            throw new Exception('No accounts found for this user.');
        }
    }

    function BrowserTraffic(){
        $analytics  = $this->initializeAnalytics();
        //$profileId  = $this->getFirstProfileId($analytics);
        $profileId = '132200738';
        $start_date = $this->start;
        $end_date   = $this->end;

        $Params = array(
            'dimensions'=>$this->dimensions,
            //'filters'=>'ga:medium==organic',
            'metrics'=>$this->metrics
        );

        return $analytics->data_ga->get(
            'ga:'.$profileId,
            $start_date,
            $end_date,
            $this->metrics,
            $Params
        );
    }//BrowserTraffic

    function OutputDataBrowser(){
        $dados = array();
        //echo "numero de acessos: ".$this->organicTraffic()['totalsForAllResults']['ga:sessions'];
        //echo "<br>";
        foreach($this->OrganicTraffic()['rows'] as $linha){
            array_push($dados, array('Navegador'=>$linha[0], 'Valor'=>$linha[1])); 
        }

        return $dados;
    }//OutputData

    function OrganicTraffic(){
        $analytics = $this->initializeAnalytics();
        //$profileId = $this->getFirstProfileId($analytics);
        $profileId = '132200738';
        $start_date = $this->start;
        $end_date = $this->end;

        $Params = array(
            'dimensions'=>$this->dimensions,
            'filters'=>'ga:medium==organic',
            'metrics'=>$this->metrics,

        );

        return $analytics->data_ga->get(
            'ga:'.$profileId,
            $start_date,
            $end_date,
            $this->metrics,
            $Params
        );
    }//organicTraffic

    function OutputData(){
        $dados = array();
        //echo "numero de acessos: ".$this->organicTraffic()['totalsForAllResults']['ga:sessions'];
        //echo "<br>";
        foreach($this->OrganicTraffic()['rows'] as $linha){
            array_push($dados, array('index'=>$linha[0], 'value'=>$linha[1])); 
        }

        return $dados;
    }//OutputData



}//fim da classe