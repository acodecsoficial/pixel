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

    'accepted'             => ':attribute स्वीकार किया जाना चाहिए.',
    'active_url'           => ':attribute वैध यूआरएल नहीं है.',
    'after'                => ':attribute के बाद की तारीख होनी चाहिए :date.',
    'alpha'                => ':attribute केवल अक्षर हो सकते हैं.',
    'alpha_dash'           => ':attribute इसमें केवल अक्षर, संख्याएँ और हाइफ़न हो सकते हैं।',
    'alpha_num'            => ':attribute इसमें केवल अक्षर और संख्याएँ हो सकती हैं।',
    'array'                => ':attribute एक सरणी होनी चाहिए.',
    'before'               => ':attribute एक तारीख पहले होनी चाहिए :date.',
    'between'              => [
        'numeric' => ':attribute के बीच होना चाहिए :min e :max.',
        'file'    => ':attribute के बीच होना चाहिए :min e :max किलोबाइट।',
        'string'  => ':attribute के बीच होना चाहिए :min e :max शब्द',
        'array'   => ':attribute के बीच होना चाहिए :min e :max सामान',
    ],
    'boolean'              => ':attribute गलत या सच होना चाहिए',
    'confirmed'            => 'की पुष्टि :attribute फिट नहीं बैठता',
    'date'                 => ':attribute वैध तिथि नहीं है.',
    'date_format'          => ':attribute कोई प्रारूप नहीं है (:format) वैध।',
    'different'            => ':attribute और :other अलग होने की जरूरत है.',
    'digits'               => ':attribute होना आवश्यक है :digits अंकों',
    'digits_between'       => ':attribute के बीच होना आवश्यक है :min और :max dígitos.',
    'dimensions'           => ':attribute गलत आयाम हैं.',
    'distinct'             => ':attribute डुप्लिकेट मान शामिल है।',
    'email'                => ':attribute एक वैध ईमेल होना चाहिए.',
    'exists'               => ':attribute चयनित अमान्य है.',
    'file'                 => ':attribute एक फ़ाइल होनी चाहिए.',
    'filled'               => ':attribute ये स्थान भरा जाना है।',
    'image'                => ':attribute यह एक छवि होनी चाहिए.',
    'in'                   => ':attribute चयनित अमान्य है.',
    'in_array'             => 'मैदान :attribute में मौजूद नहीं है :other.',
    'integer'              => ':attribute पूर्णांक होना चाहिए।',
    'ip'                   => ':attribute एक वैध आईपी पता होना चाहिए.',
    'json'                 => ':attribute एक वैध JSON स्ट्रिंग होनी चाहिए.',
    'max'                  => [
        'numeric' => 'फील्ड :attribute से अधिक नहीं हो सकता:max.',
        'file'    => 'फील्ड :attribute से अधिक नहीं हो सकता :max किलोबीट्स.',
        'string'  => 'फील्ड :attribute से अधिक नहीं हो सकता :max शब्दों.',
        'array'   => 'फील्ड :attribute से अधिक नहीं हो सकता than :max आइटम.',
    ],
    'mimes'                => 'फील्ड :attribute प्रकार की फ़ाइल होनी चाहिए :values.',
    'mimetypes'            => 'फील्ड :attribute प्रकार की फ़ाइल होनी चाहिए :values.',
    'min'                  => [
        'numeric' => 'फील्ड :attribute कम से कम शामिल होना चाहिए :min.',
        'file'    => 'फील्ड :attribute कम से कम शामिल होना चाहिए :min किलोबाइट.',
        'string'  => 'फील्ड :attribute कम से कम शामिल होना चाहिए :min शब्दों.',
        'array'   => 'फील्ड :attribute कम से कम शामिल होना चाहिए :min सामान.',
    ],
    'not_in'               => 'O :attribute चयनित अमान्य है.',
    'numeric'              => 'फील्ड :attribute एक संख्या होनी चाहिए.',
    'present'              => 'फील्ड :attribute अनिवार्य उपस्थिति।',
    'regex'                => 'फील्ड :attribute इसमें एक अमान्य प्रारूप है.',
    'required'             => 'फील्ड :attribute ये जरूरी है।',
    'required_if'          => 'फील्ड :attribute अनिवार्य है जब
 :other é :value.',
    'required_unless'      => 'फील्ड :attribute जब तक अनिवार्य न हो :other में हो :values.',
    'required_with'        => 'फील्ड :attribute अनिवार्य है जब
 :values अस्तित्व में है.',
    'required_with_all'    => 'फील्ड :attribute अनिवार्य है जब
 :values अस्तित्व में है.',
    'required_without'     => 'फील्ड :attribute अनिवार्य है जब
 :values अस्तित्व में नहीं',
    'required_without_all' => 'फील्ड :attribute अनिवार्य है जब इनमें से कोई भी नहीं :values अस्तित्व।',
    'same'                 => 'फील्ड :attribute और :other बराबर होने की जरूरत है.',
    'size'                 => [
        'numeric' => 'फील्ड :attribute यह होना ही चाहिए :size.',
        'file'    => 'फील्ड :attribute यह होना ही चाहिए :size किलोबाइट.',
        'string'  => 'फील्ड :attribute यह होना ही चाहिए :size शब्दों.',
        'array'   => 'फील्ड :attribute यह होना ही चाहिए :size आइटम.',
    ],
    'string'               => 'फील्ड :attribute एक स्ट्रिंग होनी चाहिए.',
    'timezone'             => 'फील्ड :attribute एक वैध समय क्षेत्र होना चाहिए.',
    'unique'               => ':attribute पहले से ही प्रयोग किया जा रहा है.',
    'uploaded'             => 'फील्ड :attribute अपलोड करने में विफल.',
    'url'                  => 'फ़ील्ड प्रारूप :attribute मान्य नहीं है.',
    'current_password'     => 'वर्तमान पासवर्ड उपयोगकर्ता के पासवर्ड से मेल नहीं खाता',

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
        'address' => 'पता',
        'age' => 'आयु',
        'body' => 'सामग्री',
        'city' => 'शहर',
        'country' => 'देश',
        'date' => 'तारीख',
        'day' => 'दिन',
        'description' => 'विवरण',
        'excerpt' => 'सारांश',
        'first_name' => 'पहला नाम',
        'gender' => 'लिंग',
        'hour' => 'घंटे',
        'last_name' => 'उपनाम',
        'message' => 'संदेश',
        'minute' => 'मिनट',
        'mobile' => 'सेलफोन',
        'month' => 'महीना',
        'name' => 'नाम',
        'password_confirmation' => 'पासवर्ड पुष्टि',
        'new_password' => 'नया पासवर्ड',
        'password' => 'पासवर्ड',
        'phone' => 'टेलीफ़ोन',
        'second' => 'दूसरा',
        'sex' => 'सेक्स',
        'state' => 'राज्य',
        'subject' => 'विषय',
        'text' => 'टेक्स्ट',
        'time' => 'घंटे',
        'title' => 'ख़िताब',
        'year' => 'वर्ष',
    ],

];
