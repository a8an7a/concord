<?php

namespace App\Controllers\Validate;

use Valitron\Validator;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

class ValidateEditController
{
    public function validate(ServerRequestInterface $request, array $rules = [])
    {
        $params = $request->getParsedBody();
        $params['upload'] = $request->getUploadedFiles()['upload'];
        
        $validator = new Validator($params);

        $validator->rule('required', array(
            'email', 'first_name', 'last_name', 'birthday'
        ))->message('Поле {field} должно быть заполнено');

        $validator->rule('photoFormat', 'upload')
            ->message('Наш сайт поддерживает только JPEG и PNG форматы изображений');

        $validator->rule('email', 'email')
            ->message('Пожалуйста, введите корректный адрес электронной почты');

        $validator->rule('date', 'birthday')
            ->message('Пожалуйста, введите корректную дату рождения');

        $validator->labels(array(
            'email'      => 'Электронная почта',
            'first_name' => 'Имя',
            'last_name'  => 'Фамилия',
            'birthday' => 'Дата рождения'
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