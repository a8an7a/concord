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
use App\Controllers\Validate\ValidateCmsNewsEditController;

class CmsNewsEditController extends ValidateCmsNewsEditController
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

    public function index(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $result = News::find($args['id']);

        $id            = $result->id;
        $news_title    = $result->news_title;
        $preview_text  = $result->preview_text;
        $content       = $result->content;

        $publication_date = date('d.m.Y H:i', strtotime($result->publication_date));

        return $this->view->render($response, 'pages/cms/news/edit.twig', compact(
            'id',
            'news_title',
            'publication_date',
            'preview_text',
            'content'
        ));
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $data = $this->validate($request, [
            'news_title'    => ['required'],
            'preview_text'  => ['required'],
            'preview_image' => ['photoFormat'],
            'content'       => ['required'],
            'publication_date' => ['required', 'date']
        ]);

        $data['id'] = $args['id'];
        $data['publication_date'] = date('Y-m-d H:i:s', strtotime($data['publication_date']));
        
        try {
            if ($request->getUploadedFiles()['preview_image']->getClientFilename()) {
                $upload = new UploadController($request->getUploadedFiles()['preview_image'], 'news');
                $data['preview_image'] = $upload->upload();

                unlink(__DIR__ . '/../../../../public_html/upload/image/news/' . News::find($args['id'])->preview_image);
            } else {
                $data['preview_image'] = News::find($args['id'])->preview_image;
            }

            News::edit($data);
        } catch (Exception $e) {

            $this->flash->addMessage('status', 'Ошибка, статья не была отредактирована!');
            return $response->withHeader('Location', $this->routeParser->urlFor('cms.news.edit', array(
                'id' => $args['id']
            )));
        }

        $this->flash->addMessage('status', 'Статья успешно отредактирована!');

        return $response->withHeader('Location',$this->routeParser->urlFor('cms.news.view', array(
            'id' => $args['id']
        )));
    }
}