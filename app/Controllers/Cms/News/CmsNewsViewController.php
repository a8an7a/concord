<?php

namespace App\Controllers\Cms\News;

use App\Models\News;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CmsNewsViewController
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $result = News::find($args['id']);

        $id            = $result->id;
        $news_title    = $result->news_title;
        $preview_image = $result->preview_image;
        $preview_text  = $result->preview_text;
        $content       = $result->content;

        $publication_date = date('d.m.Y H:i', strtotime($result->publication_date));

        return $this->view->render($response, 'pages/cms/news/view.twig', compact(
            'id',
            'news_title',
            'preview_image',
            'preview_text',
            'content',
            'publication_date'
        ));
    }
}