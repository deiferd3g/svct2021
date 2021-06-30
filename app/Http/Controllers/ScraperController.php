<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;

class ScraperController extends Controller
{

    public function get_data()
    {
        $palabra = 'arduino';
        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client(HttpClient::create(['timeout' => 120]));
        //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // junto con la URL a la que se le aplicará el scraping
        $crawler = $client->request('GET', 'http://www.lareferencia.info/vufind/Search/Results?lookfor=' . $palabra . '&type=AllFields&page=1');
        $resultados = $crawler->filter('.search-stats')->text();
        $paginas = str_replace(']', '', str_replace('[', '', $crawler->filter('ul.pagination')->children()->last()->text()));
        $int = (int) $paginas;
        $a = [];
        $p = 1;
        // for ($i = 1; $i <= $int; $i++) {

            // $crawler = $client->request('GET', 'http://www.lareferencia.info/vufind/Search/Results?lookfor=' . $palabra . '&type=AllFields&page=' . $i);
            $r = $crawler->filter('.result')->each(function ($node) {
                // $client = new Client();
                // $palabraClave = $client->request('GET', $node->filter('a')->attr('href'));
                // $clave = $palabraClave->filter('#article-front > .abstract > p')->last();
                return [
                    // 'clave' => $clave->text('Datos no disponibles'),
                    'titulo' => $node->filter('.result-title')->text('Datos no disponibles'),
                        'href' => 'http://www.lareferencia.info'.$node->filter('.title')->attr('href'),
                        'autor' => str_replace('por ', '', $node->filter('.row')->children()->text('Datos no disponibles')),
                        'pais' => $node->filter('.result-country')->text('Datos no disponibles'),
                        // 'idioma' => $node->filter('div.versions')->text('Datos no disponibles'),
                        'tipo' => $node->filter('.result-formats')->text('Datos no disponibles')
                ];
            });
            // $p = $p + 50;
            array_push($a, $r);
        // }


        // dd($a);
        return view('observa.laReferencia', ['r' => $a, 'resultados' => $resultados]);
    }


    public function ejemplo()
    {
        $palabra = 'ganado';
        $q = str_replace(" ", "+", $palabra);
        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client();
        //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // junto con la URL a la que se le aplicará el scraping
        $crawler = $client->request('GET', 'https://github.com/login');
        $form = $crawler->selectButton('commit')->form();
        $vista = $client->submit($form, array(
            'login' => 'deifertecnico@gmail.com',
            'password' => 'xxxxxx'
        ));

        echo $vista->html();
    }
    public function ejemplo2()
    {
        $palabra = $request['palabra'];
        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client();
        //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // junto con la URL a la que se le aplicará el scraping
        $crawler = $client->request('GET', 'https://scielo.org/');
        //se selecciona el formulario sobre el que se ralizara la petición
        $form = $crawler->filter('#searchForm')->form();
        //se rellenan los campoos del formulario
        $vista = $client->submit($form, array(
            'q' => $palabra,
        ));
        $r = $vista->filter('.results')->children()->each(function ($node) {
            // return $node;
            return [
                'titulo' => $node->filter('a')->text('Datos no disponibles'),
                'href' => $node->filter('a')->attr('href'),
                'autor' => $node->filter('div.authors')->text('Datos no disponibles'),
                'idioma' => $node->filter('div.versions')->text('Datos no disponibles'),
                'tipo' => $node->filter('div.source')->text('Datos no disponibles')
            ];
        });
        // echo $vista->html();
        return view('observa.scielo', ['r' => $r]);
    }
    public function get_data1()
    {
        $palabra = 'ganado vacuno';
        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client();
        //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // junto con la URL a la que se le aplicará el scraping
        $crawler = $client->request('GET', 'https://www.redalyc.org/');
        //se selecciona el formulario sobre el que se ralizara la petición
        $crawler = $crawler->filter('div.container-input');
        // $form = $crawler->filter('#boton-buscar-articulo')->form();

        //se rellenan los campoos del formulario

        // $vista = $client->submit($form, array(
        //     'q' => $palabra,
        // ));
        // $r = $vista->filter('.results')->children()->each(function($node){
        //      echo $node->html();
        //     // return [
        //     //    'titulo' => $node->filter('a')->text('Datos no disponibles'),
        //     //    'href' => $node->filter('a')->attr('href'),
        //     //    'autor' => $node->filter('div.authors')->text('Datos no disponibles'),
        //     //    'idioma' => $node->filter('div.versions')->text('Datos no disponibles'),
        //     //    'tipo' => $node->filter('div.source')->text('Datos no disponibles')
        //     // ];
        // });
        echo $crawler->html();
        // return $request['palabra'];
        // return view('observa.scielo', ['r' => $r]);
    }
}
