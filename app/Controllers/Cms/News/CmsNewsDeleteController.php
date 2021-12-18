<?php

namespace App\Controllers\Cms\News;

use Exception;
use App\Models\News;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;

class CmsNewsDeleteController
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

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        try {
            unlink(__DIR__ . '/../../../../public_html/upload/image/news/' . News::find($args['id'])->preview_image);

            if(!$news = News::destroy($args['id'])) {
                throw new Exception('Ошибка, статья не была удалена!');
            }
        } catch (Exception $e) {
            $this->flash->addMessage('status', $e->getMessage());
            return $response->withHeader('Location', $this->routeParser->urlFor('cms.news'));
        }

        $this->flash->addMessage('status', 'Статья была успешно удалена!');
        return $response->withHeader('Location', $this->routeParser->urlFor('cms.news'));
    }
}