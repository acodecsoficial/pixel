<?php

// resources/lant/pt/validation.php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
	 | Antes de usar, sete 'locale' para 'pt' em config/app.php
 	 |
    */

    'accepted'             => ':attribute debe ser aceptado.',
    'active_url'           => ':attribute no es una URL válida.',
    'after'                => ':attribute debe ser una fecha posterior a :date.',
    'alpha'                => ':attribute solo puede contener letras.',
    'alpha_dash'           => ':attribute solo puede contener letras, números y guiones.',
    'alpha_num'            => ':attribute solo puede contener letras y números.',
    'array'                => ':attribute debe ser un array.',
    'before'               => ':attribute debe ser una fecha anterior a :date.',
    'between'              => [
        'numeric' => ':attribute debe estar entre :min y :max.',
        'file'    => ':attribute debe estar entre :min y :max kilobytes.',
        'string'  => ':attribute debe tener entre :min y :max caracteres.',
        'array'   => ':attribute debe tener entre :min y :max elementos.',
    ],
    'boolean'              => ':attribute debe ser falso o verdadero',
    'confirmed'            => 'La confirmación de :attribute no coincide',
    'date'                 => ':attribute no es una fecha válida.',
    'date_format'          => ':attribute no tiene el formato (:format) válido.',
    'different'            => ':attribute y :other deben ser diferentes.',
    'digits'               => ':attribute debe tener :digits dígitos.',
    'digits_between'       => ':attribute debe estar entre :min y :max dígitos.',
    'dimensions'           => ':attribute tiene dimensiones incorrectas.',
    'distinct'             => ':attribute contiene un valor duplicado.',
    'email'                => ':attribute debe ser un correo electrónico válido.',
    'exists'               => ':attribute seleccionado es inválido.',
    'file'                 => ':attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute es obligatorio.',
    'image'                => ':attribute debe ser una imagen.',
    'in'                   => ':attribute seleccionado es inválido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => ':attribute debe ser un número entero.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'file'    => 'El campo :attribute no puede ser mayor que :max kilobytes.',
        'string'  => 'El campo :attribute no puede tener más de :max caracteres.',
        'array'   => 'El campo :attribute no puede tener más de :max elementos.',
    ],
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo :values.',
    'min'                  => [
        'numeric' => 'El campo :attribute debe contener al menos :min.',
        'file'    => 'El campo :attribute debe tener al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe contener al menos :min caracteres.',
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'not_in'               => 'El campo :attribute seleccionado es inválido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El campo :attribute contiene un formato inválido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values exista.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values exista.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no exista.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los :values exista.',
    'same'                 => 'El campo :attribute y :other deben ser iguales.',
    'size'                 => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El campo :attribute debe ser :size kilobytes.',
        'string'  => 'El campo :attribute debe contener :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size elementos.',
    ],
    'string'               => 'El campo :attribute debe ser una cadena de texto.',
    'timezone'             => 'El campo :attribute debe contener una zona horaria válida.',
    'unique'               => ':attribute ya está en uso.',
    'uploaded'             => 'El campo :attribute falló al subir.',
    'url'                  => 'El formato del campo :attribute no es válido.',
    'current_password'     => 'La contraseña actual no coincide con la contraseña del usuario',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Nomes em português dos atributos, caso eles sejam em inglês ou deseje
    | trocar ele na mensagem.
    |
    */

    'attributes' => [
        'address' => 'dirección',
        'age' => 'edad',
        'body' => 'contenido',
        'city' => 'ciudad',
        'country' => 'país',
        'date' => 'fecha',
        'day' => 'día',
        'description' => 'descripción',
        'excerpt' => 'extracto',
        'first_name' => 'nombre',
        'gender' => 'género',
        'hour' => 'hora',
        'last_name' => 'apellido',
        'message' => 'mensaje',
        'minute' => 'minuto',
        'mobile' => 'móvil',
        'month' => 'mes',
        'name' => 'nombre',
        'password_confirmation' => 'confirmación de contraseña',
        'new_password' => 'nueva contraseña',
        'password' => 'contraseña',
        'phone' => 'teléfono',
        'second' => 'segundo',
        'sex' => 'sexo',
        'state' => 'estado',
        'subject' => 'asunto',
        'text' => 'texto',
        'time' => 'hora',
        'title' => 'título',
        'year' => 'año',
    ],

];
