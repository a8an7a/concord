<?php

namespace App\Controllers\Validate;

use Valitron\Validator;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

class ValidateResetController
{
    public function validate(ServerRequestInterface $request, array $rules = [])
    {
        $validator = new Validator(
            $params = $request->getParsedBody()
        );

        $validator->rule('required', array('password'))
            ->message('Пожалуйста, придумайте новый пароль');

        $validator->labels(array(
            'password' => 'Новый пароль'
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