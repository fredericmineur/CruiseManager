<?php
/**
 * Created by PhpStorm.
 * User: filipw
 * Date: 29-01-20
 * Time: 9:32 PM
 */

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImisService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getPersons($searchparameter)//: Array
    {
        if ($searchparameter === null || $searchparameter === ''){
            $response = $this->httpClient->request('GET',
                'http://www.vliz.be/imis?module=person&show=json');
        } else {
            $response = $this->httpClient->request('GET',
                'http://www.vliz.be/imis?module=person&show=json',
                array('query' => ['Field'=>$searchparameter])
            );

        }
        return $response->getContent();
    }


}
