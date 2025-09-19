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

    'accepted'             => ':attribute хүлээн зөвшөөрөх ёстой.',
    'active_url'           => ':attribute хүчинтэй URL биш байна.',
    'after'                => ':attribute дараах болзоо байх ёстой :date.',
    'alpha'                => ':attribute зөвхөн үсэг агуулж болно.',
    'alpha_dash'           => ':attribute зөвхөн үсэг, тоо, зураас агуулсан байж болно.',
    'alpha_num'            => ':attribute зөвхөн үсэг, тоо агуулсан байж болно.',
    'array'                => ':attribute Энэ нь олон янз байх ёстой.',
    'before'               => ':attribute өмнөх болзоо байх ёстой :date.',
    'between'              => [
        'numeric' => ':attribute хооронд байх ёстой :min болон :max.',
        'file'    => ':attribute хооронд байх ёстой :min болон :max килобайт.',
        'string'  => ':attribute хооронд байх ёстой :min болон :max үгс.',
        'array'   => ':attribute must have between :min e :max зүйлс.',
    ],
    'boolean'              => ':attribute худал эсвэл үнэн байх ёстой',
    'confirmed'            => '-ийн баталгаажуулалт :attribute тохирохгүй байна',
    'date'                 => ':attribute хүчинтэй огноо биш байна.',
    'date_format'          => ':attribute формат биш юм (:format) хүчинтэй.',
    'different'            => ':attribute болон :other өөр байх хэрэгтэй.',
    'digits'               => ':attribute байх хэрэгтэй :digits цифрүүд.',
    'digits_between'       => ':attribute хооронд байх ёстой :min болон :max dígitos.',
    'dimensions'           => ':attribute буруу хэмжээстэй байна.',
    'distinct'             => ':attribute давхардсан утгыг агуулж байна.',
    'email'                => ':attribute хүчинтэй имэйл байх ёстой.',
    'exists'               => ':attribute сонгогдсон нь буруу байна.',
    'file'                 => ':attribute файл байх ёстой.',
    'filled'               => ':attribute талбар шаардлагатай.',
    'image'                => ':attribute дүрс байх ёстой.',
    'in'                   => ':attribute сонгогдсон нь буруу байна.',
    'in_array'             => 'талбар :attribute -д байхгүй :other.',
    'integer'              => ':attribute бүхэл тоо байх ёстой.',
    'ip'                   => ':attribute хүчинтэй IP хаяг байх ёстой.',
    'json'                 => ':attribute хүчинтэй JSON мөр байх ёстой.',
    'max'                  => [
        'numeric' => 'Хээр :attribute -аас их байж болохгүй :max.',
        'file'    => 'Хээр :attribute -аас их байж болохгүй :max килобайт.',
        'string'  => 'Хээр :attribute -аас илүү байж болохгүй :max үгс.',
        'array'   => 'Хээр :attribute түүнээс илүү байж болохгүй :max зүйлс.',
    ],
    'mimes'                => 'Хээр :attribute төрлийн файл байх ёстой :values.',
    'mimetypes'            => 'Хээр :attribute төрлийн файл байх ёстой :values.',
    'min'                  => [
        'numeric' => 'Хээр :attribute дор хаяж агуулсан байх ёстой :min.',
        'file'    => 'Хээр :attribute наад зах нь байх ёстой :min килобайт.',
        'string'  => 'Хээр :attribute дор хаяж агуулсан байх ёстой :min үгс.',
        'array'   => 'Хээр :attribute наад зах нь байх ёстой :min зүйлс.',
    ],
    'not_in'               => 'O :attribute сонгосон хүчингүй.',
    'numeric'              => 'Хээр :attribute тоо байх ёстой.',
    'present'              => 'Хээр :attribute байх ёстой.',
    'regex'                => 'Хээр :attribute буруу формат агуулж байна.',
    'required'             => 'Хээр :attribute шаардлагатай байна.',
    'required_if'          => 'Хээр :attribute үед заавал байх ёстой :other болон :value.',
    'required_unless'      => 'Хээр :attribute Энэ зайлшгүй, хэрэв :other хэрэв байгаа бол :values.',
    'required_with'        => 'Хээр :attribute үед заавал байх ёстой :values оршин байдаг.',
    'required_with_all'    => 'Хээр :attribute үед заавал байх ёстой :values оршин байдаг.',
    'required_without'     => 'Хээр :attribute үед заавал байх ёстой :values байхгүй байна.',
    'required_without_all' => 'Хээр :attribute аль нь ч байхгүй үед заавал байх ёстой :values оршин байдаг.',
    'same'                 => 'Хээр :attribute болон :other тэнцүү байх хэрэгтэй.',
    'size'                 => [
        'numeric' => 'Хээр :attribute байх ёстой :size.',
        'file'    => 'Хээр :attribute байх ёстой :size килобайт.',
        'string'  => 'Хээр :attribute агуулсан байх ёстой :size үгс',
        'array'   => 'Хээр :attribute агуулсан байх ёстой :size зүйлс.',
    ],
    'string'               => 'Хээр :attribute тэмдэгт мөр байх ёстой.',
    'timezone'             => 'Хээр :attribute хүчинтэй цагийн бүс агуулсан байх ёстой.',
    'unique'               => ':attribute аль хэдийн ашиглагдаж байна.',
    'uploaded'             => 'Хээр :attribute байршуулж чадсангүй.',
    'url'                  => 'Талбайн формат :attribute хүчин төгөлдөр бус байна.',
    'current_password'     => 'Одоогийн нууц үг нь хэрэглэгчийн нууц үгтэй таарахгүй байна',

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
        'address' => 'хаяг',
        'age' => 'нас',
        'body' => 'агуулга',
        'city' => 'хот',
        'country' => 'улс',
        'date' => 'огноо',
        'day' => 'өдөр',
        'description' => 'тайлбар',
        'excerpt' => 'хураангуй',
        'first_name' => 'анхны нэр',
        'gender' => 'хүйс',
        'hour' => 'цагаар',
        'last_name' => 'овог нэр',
        'message' => 'мессеж',
        'minute' => 'минут',
        'mobile' => 'гар утас',
        'month' => 'сар',
        'name' => 'нэр',
        'password_confirmation' => 'нууц үг баталгаажуулах',
        'new_password' => 'Шинэ нууц үг',
        'password' => 'нууц үг',
        'phone' => 'утас',
        'second' => 'секунд',
        'sex' => 'секс',
        'state' => 'муж',
        'subject' => 'сэдэв',
        'text' => 'текст',
        'time' => 'цаг',
        'title' => 'гарчиг',
        'year' => 'жил',
    ],

];
