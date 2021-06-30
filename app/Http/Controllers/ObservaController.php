<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Http\Request;
use Goutte\Client;

class ObservaController extends Controller
{
    /**
     * Función que retorna la vista principal del buscador.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('observa.index');
    }

    /**
     * Función para realizar la busqueda en la página https://scielo.org/
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscarScielo(Request $request)
    {
        $palabra = str_replace(' ', '+', $request['palabra']);
        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client(HttpClient::create(['timeout' => 120]));
        //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // junto con la URL a la que se le aplicará el scraping
        try {
            $crawler = $client->request('GET', 'https://search.scielo.org/?lang=es&count=50&from=1&output=site&sort=&format=summary&fb=&page=1&q=' . $palabra);
            $resultados = $crawler->filter('.searchFilters  > .filterTitle ')->text();
            $paginas = str_replace(['Página de ', ' Próxima'], '', $crawler->filter('.searchOptions > .right ')->text());

            $int = (int) $paginas;
            $a = [];
            $p = 1;
            for ($i = 1; $i <= $int; $i++) {
                $crawler = $client->request('GET', 'https://search.scielo.org/?lang=es&count=50&from=' . $p . '&output=site&sort=&format=summary&fb=&page=1&q=' . $palabra);
                $r = $crawler->filter('.results')->children()->each(function ($node) {
                    // $client = new Client();
                    // $palabraClave = $client->request('GET', $node->filter('a')->attr('href'));
                    // $clave = $palabraClave->filter('#article-front > .abstract > p')->last();
                    return [
                        // 'clave' => $clave->text('Datos no disponibles'),
                        'titulo' => $node->filter('a')->text('Datos no disponibles'),
                        'href' => $node->filter('a')->attr('href'),
                        'autor' => $node->filter('div.authors')->text('Datos no disponibles'),
                        'idioma' => $node->filter('div.versions')->text('Datos no disponibles'),
                        'tipo' => str_replace('Métricas del periódico Sobre o periódico SciELO Analytics', '', $node->filter('div.source')->text('Datos no disponibles'))
                    ];
                });
                $p = $p + 50;
                array_push($a, $r);
            }
            return view('observa.scielo', ['r' => $a, 'resultados' => $resultados]);
        } catch (\Throwable $th) {
            echo 'No se encontraron resultados para "' . $request['palabra'] . '"';
        }
    }

    /**
     * Función que retorna la vista principal del buscador.
     *
     * @return \Illuminate\Http\Response
     */
    public function ejemplo()
    {

        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client();
        // //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // // junto con la URL a la que se le aplicará el scraping
        $links = $client->request('GET', 'http://www.oncti.gob.ve/NOTICIAS.html');
        // se realiza el filtrado de los nodos encontrados, buscar documentación de la libreria crawler de symphony
        $links->filter("article.blog_item > div.blog_details > a")->each(function ($node) use ($request) {
            $client = new Client();
            $url = $node->attr('href');
            $noticias = $client->request('GET', 'http://www.oncti.gob.ve/' . $url);
            $noticias->filter("[class='desc']")->each(function ($titulo) use (&$request) {
                if (preg_match('/' . $request['palabra'] . '/i', $titulo->html())) {
                    print_r($titulo->html());
                }
            });
            // if (preg_match('/' . $request['palabra'] . '/i', $node->html())) {
            //     // return $node->html();
            //     print_r($node->attr('href'));
            //     // print_r( '<a href="http://www.oncti.gob.ve/'.$node->attr('href').'">'.$node->text().'</a><br>');
            // }
        });
    }

    public function exportarScielo(Request $request)
    {
        $fileName = 'tasks.csv';
        $tasks = Task::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Title', 'Assign', 'Description', 'Start Date', 'Due Date');

        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row['Title']  = $task->title;
                $row['Assign']    = $task->assign->name;
                $row['Description']    = $task->description;
                $row['Start Date']  = $task->start_at;
                $row['Due Date']  = $task->end_at;

                fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Función para buscar resultados en la http://www.lareferencia.info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscarLaReferencia(Request $request)
    {
        $palabra = str_replace(' ', '+', $request['palabra']);
        //Ejemplo funcional de scraping y crawling en la pagina del ontic noticias
        //se instancia un onjeto de la clase goutte
        $client = new Client(HttpClient::create(['timeout' => 120]));
        //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // junto con la URL a la que se le aplicará el scraping
        try {
            $crawler = $client->request('GET', 'http://www.lareferencia.info/vufind/Search/Results?lookfor=' . $palabra . '&type=AllFields&page=1');
            $paginas = str_replace(']', '', str_replace('[', '', $crawler->filter('ul.pagination')->children()->last()->text()));
            $datos = [];
            for ($i = 1; $i <= (int) $paginas; $i++) {
                $crawler = $client->request('GET', 'http://www.lareferencia.info/vufind/Search/Results?lookfor=' . $palabra . '&type=AllFields&page=' . $i);
                $crawler->filter('.result')->each(function ($node)  use (&$datos) {
                    $href = ['href' => 'http://www.lareferencia.info' . $node->filter('.title')->attr('href')];
                    array_push($datos, $href);
                });
            }
            return $datos;
        } catch (\Throwable $th) {
            echo 'No se encontraron resultados para "' . $request['palabra'] . '"';
        }
    }

    public function extraerData(Request $request)
    {
        dd( $request);
        // $client = new Client(HttpClient::create(['timeout' => 120]));
        // //se declara una variable y se utiliza el metodo request colocando el method (GET-POST-PUT)
        // // junto con la URL a la que se le aplicará el scraping
        // try {
        //     foreach ($request as $key) {
        //         $client = new Client();
        //         $crawler = $client->request('GET', $key);
        //         $datos = [];
        //         // for ($i = 1; $i <= (int) $paginas; $i++) {
        //         //     $crawler = $client->request('GET', 'http://www.lareferencia.info/vufind/Search/Results?lookfor=' . $palabra . '&type=AllFields&page=' . $i);
        //         //     $crawler->filter('.result')->each(function ($node)  use (&$datos) {
        //         //         // $client = new Client();
        //         //         // $resp =  $client->request('GET', 'http://www.lareferencia.info'.$node->filter('a.title ')->attr('href'));
        //         //         // $clave = $resp->filter('.media');
        //         //         $href = 'http://www.lareferencia.info' . $node->filter('.title')->attr('href');
        //         //         $array = [
        //         //             // 'clave' => $clave->text('Datos no disponibles'),
        //         //             'titulo' => $node->filter('.result-title')->text('Datos no disponibles'),
        //         //             'href' => 'http://www.lareferencia.info' . $node->filter('.title')->attr('href'),
        //         //             'autor' => str_replace('por ', '', $node->filter('.row')->children()->text('Datos no disponibles')),
        //         //             'pais' => $node->filter('.result-country')->text('Datos no disponibles'),
        //         //             // 'idioma' => $node->filter('div.versions')->text('Datos no disponibles'),
        //         //             'tipo' => $node->filter('.result-formats')->text('Datos no disponibles')
        //         //         ];
        //         //         array_push($datos, $href);
        //         //     });
        //         // }
        //         return $crawler->html();
        //     }
        // } catch (\Throwable $th) {
        //     echo 'No se encontraron resultados para "' . $request['palabra'] . '"';
        // }
    }
}
