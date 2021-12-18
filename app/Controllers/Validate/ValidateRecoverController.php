<?php

namespace App\Controllers\Validate;

use Valitron\Validator;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

class ValidateRecoverController
{
    public function validate(ServerRequestInterface $request, array $rules = [])
    {
        $validator = new Validator(
            $params = $request->getParsedBody()
        );

        $validator->rule('required', array('email'))
            ->message('Поле {field} должно быть заполнено');

        $validator->rule('email', 'email')
            ->message('Пожалуйста, введите корректный адрес электронной почты');

        $validator->labels(array(
            'email' => 'Электронная почта'
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