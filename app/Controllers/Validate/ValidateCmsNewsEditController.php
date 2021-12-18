<?php

namespace App\Controllers\Validate;

use Valitron\Validator;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

class ValidateCmsNewsEditController
{
    public function validate(ServerRequestInterface $request, array $rules = [])
    {
        $params = $request->getParsedBody();
        $params['preview_image'] = $request->getUploadedFiles()['preview_image'];
        
        $validator = new Validator($params);

        $validator->rule('required', array(
            'news_title', 'preview_text', 'content'
        ))->message('{field} должно быть заполнено');

        $validator->rule('required', 'content')
            ->message('Напишите основной текст');

        $validator->rule('required', 'publication_date')
            ->message('Выберите дату публикации статьи');

        $validator->rule('photoFormat', 'preview_image')
            ->message('Наш сайт поддерживает только JPEG и PNG форматы изображений');

        $validator->rule('date', 'publication_date')
            ->message('Пожалуйста, введите корректную дату публикации статьи');

        $validator->labels(array(
            'news_title' => 'Название статьи',
            'preview_text' => 'Краткое описание статьи',
            'content' => 'Основное содержимое статьи',
            'publication_date' => 'Дата публикации'
        ));

        $validator->mapFieldsRules($rules);

        if(!$validator->validate()){
            throw new ValidationException(
                $validator->errors(),
                $request->getServerParams()['HTTP_REFERER']
            );
        }

        return $params;
    }
}