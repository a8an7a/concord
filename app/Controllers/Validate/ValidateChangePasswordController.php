<?php

namespace App\Controllers\Validate;

use Valitron\Validator;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

class ValidateChangePasswordController
{
    public function validate(ServerRequestInterface $request, array $rules = [])
    {
        $validator = new Validator(
            $params = $request->getParsedBody()
        );

        $validator->rule('required', array(
            'current_password', 'password'
        ))->message('Поле {field} должно быть заполнено');
        
        $validator->labels(array(
            'current_password' => 'Текущий пароль',
            'password' => 'Пароль',
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