<?php

return [
   'ibge' => [
       'base_url' => env('IBGE_API_URL', 'https://servicodados.ibge.gov.br/api/v1'),
       'endpoints' => [
           'cities' => '/localidades/municipios'
       ]
   ]
];