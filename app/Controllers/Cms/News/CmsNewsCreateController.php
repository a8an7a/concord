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
use App\Controllers\Validate\ValidateCmsNewsCreateController;

class CmsNewsCreateController extends ValidateCmsNewsCreateController
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

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'pages/cms/news/create.twig');
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->validate($request, [
            'news_title'    => ['required'],
            'preview_text'  => ['required'],
            'preview_image' => ['photoRequired', 'photoFormat'],
            'content'       => ['required'],
            'publication_date' => ['required', 'date']
        ]);
        
        $data['publication_date'] = date('Y-m-d H:i:s', strtotime($data['publication_date']));

        try {
            if ($request->getUploadedFiles()['preview_image']) {
                $upload = new UploadController($request->getUploadedFiles()['preview_image'], 'news');
                $data['preview_image'] = $upload->upload();
            } 
            
            if (!$results = News::store($data)) {
                throw new Exception('Ошибка, статья не была опубликована!');
            }
        } catch (Exception $e) {

            $this->flash->addMessage('status', $e->getMessage());
            return $response->withHeader('Location', $this->routeParser->urlFor('cms.news.create'));
        }
        
        $this->flash->addMessage('status', 'Статья успешно опубликована!');

        return $response->withHeader(
            'Location',
            $this->routeParser->urlFor('cms.news.view', array(
                'id' => $results
            ))
        );
    }
}