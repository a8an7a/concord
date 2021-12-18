<?php

namespace App\Controllers\Cms\News;

use Exception;
use App\Models\News;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use App\Controllers\Upload\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Pagination\LengthAwarePaginator;

class CmsNewsController
{
    protected $view;
    protected $flash;
    protected $routeParser;

    public function __construct(Twig $view, Messages $flash, RouteParserInterface $routeParser)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $news_collection = News::get();
        $news_collection = json_decode(json_encode($news_collection), true); 

        $page = $request->getQueryParams()['page'] ?? 1;
        $perPage = $request->getQueryParams()['perPage'] ?? 5;

        $news = new LengthAwarePaginator(
            array_slice($news_collection, ($page - 1) * $perPage, $perPage),
            count($news_collection),
            $perPage,
            $page,
            ['path' => $request->getUri()->getPath(), 'query' => $request->getQueryParams()]
        );

        return $this->view->render($response, 'pages/cms/news/news.twig', compact('news'));
    }
}