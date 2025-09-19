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

    'accepted'             => ':attribute 받아들여져야만 한다',
    'active_url'           => ':attribute 유효한 URL이 아닙니다',
    'after'                => ':attribute 다음 날짜 이후여야 합니다 :date.',
    'alpha'                => ':attribute 문자만 포함할 수 있습니다',
    'alpha_dash'           => ':attribute 문자, 숫자, 하이픈만 포함할 수 있습니다',
    'alpha_num'            => ':attribute 문자와 숫자만 포함할 수 있습니다.',
    'array'                => ':attribute 배열이어야 합니다.',
    'before'               => ':attribute 그 전에 데이트를 해라 :date.',
    'between'              => [
        'numeric' => ':attribute :min과 :max 사이여야 합니다.',
        'file'    => ':attribute :min과 :max킬로바이트 사이여야 합니다.',
        'string'  => ':attribute :min에서 :max 문자 사이여야 합니다.',
        'array'   => ':attribute :min에서 :max 사이의 항목이 있어야 합니다.',
    ],
    'boolean'              => ':attribute 거짓이거나 참이어야 합니다',
    'confirmed'            => '확인 :attribute 맞지 않는다',
    'date'                 => ':attribute 유효한 날짜가 아닙니다.',
    'date_format'          => ':attribute 유효한 형식이 아닙니다(:format).',
    'different'            => ':attribute 그것은 :other 달라야 해.',
    'digits'               => ':attribute 가지고 있어야 한다 :digits 숫자.',
    'digits_between'       => ':attribute :min과 :max 숫자 사이여야 합니다.',
    'dimensions'           => ':attribute 치수가 잘못되었습니다.',
    'distinct'             => ':attribute 중복된 값이 포함되어 있습니다.',
    'email'                => ':attribute 유효한 이메일이어야 합니다.',
    'exists'               => ':attribute 선택한 것은 유효하지 않습니다.',
    'file'                 => ':attribute 파일이어야 합니다.',
    'filled'               => ':attribute 필드는 필수입니다.',
    'image'                => ':attribute 이미지여야 합니다.',
    'in'                   => ':attribute 선택한 것은 유효하지 않습니다.',
    'in_array'             => '필드 :attribute 에는 존재하지 않습니다 :other.',
    'integer'              => ':attribute 정수여야 합니다.',
    'ip'                   => ':attribute 유효한 IP 주소여야 합니다.',
    'json'                 => ':attribute 유효한 JSON 문자열이어야 합니다.',
    'max'                  => [
        'numeric' => '필드 :attribute 다음보다 클 수 없습니다 :max.',
        'file'    => '필드 :attribute 다음보다 클 수 없습니다 :max 킬로바이트.',
        'string'  => '필드 :attribute 이상을 가질 수 없습니다 :max.',
        'array'   => '필드 :attribute ~보다 더 많다 :max 아이템.',
    ],
    'mimes'                => '필드 :attribute 다음 유형의 파일이어야 합니다 :values.',
    'mimetypes'            => '필드 :attribute 다음 유형의 파일이어야 합니다 :values.',
    'min'                  => [
        'numeric' => '필드 :attribute 적어도 포함해야 합니다 :min.',
        'file'    => '필드 :attribute 적어도 있어야합니다 :min 킬로바이트.',
        'string'  => '필드 :attribute 적어도 포함해야 합니다 :min 글자.',
        'array'   => '필드 :attribute 적어도 있어야합니다 :min 아이템.',
    ],
    'not_in'               => ':attribute 선택한 항목이 잘못되었습니다.',
    'numeric'              => '필드 :attribute 숫자여야 합니다.',
    'present'              => '필드 :attribute 반드시 존재해야 한다.',
    'regex'                => '필드 :attribute 잘못된 형식이 포함되어 있습니다.',
    'required'             => '필드 :attribute 필수.',
    'required_if'          => '필드 :attribute 때 필수입니다 :other :value.',
    'required_unless'      => '필드 :attribute 않는 한 필수입니다 :other 안에 있다 :values.',
    'required_with'        => '필드 :attribute 때 필수입니다 :values 존재하다.',
    'required_with_all'    => '필드 :attribute 때 필수입니다 :values 존재하다.',
    'required_without'     => '필드 :attribute 때 필수입니다 :values 존재하지 않는다.',
    'required_without_all' => '필드 :attribute 때 필수입니다 해당 사항 없음 :values 존재하다.',
    'same'                 => '필드 :attribute e :other 동일해야합니다.',
    'size'                 => [
        'numeric' => '필드 :attribute 그래야만 해 :size.',
        'file'    => '필드 :attribute 그래야만 해 :size 킬로바이트.',
        'string'  => '필드 :attribute 반드시 포함해야 함 :size.',
        'array'   => '필드 :attribute 반드시 포함해야 함 :size 아이템.',
    ],
    'string'               => '필드 :attribute 문자열이어야 합니다.',
    'timezone'             => '필드 :attribute 유효한 시간대를 포함해야 합니다.',
    'unique'               => ':attribute 이미 사용되고 있습니다.',
    'uploaded'             => '필드 :attribute 업로드하지 못했습니다.',
    'url'                  => '필드 형식 :attribute 유효하지 않습니다.',
    'current_password'     => '현재 비밀번호가 사용자 비밀번호와 일치하지 않습니다',

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
        'address' => '주소',
        'age' => '나이',
        'body' => '콘텐츠',
        'city' => '도시',
        'country' => '국가',
        'date' => '날짜',
        'day' => '일',
        'description' => '설명',
        'excerpt' => '요약',
        'first_name' => '이름',
        'gender' => '성별',
        'hour' => '시간',
        'last_name' => '수리남',
        'message' => '메시지',
        'minute' => '분',
        'mobile' => '휴대폰',
        'month' => '월',
        'name' => '이름',
        'password_confirmation' => '비밀번호를 확인하세요',
        'new_password' => '새 비밀번호',
        'password' => '비밀번호',
        'phone' => '전화기',
        'second' => '두번째',
        'sex' => '섹스',
        'state' => '정부',
        'subject' => '주제',
        'text' => '텍스트',
        'time' => '시간',
        'title' => '제목',
        'year' => '년도',
    ],

];
