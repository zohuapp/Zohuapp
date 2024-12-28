<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El :attribute ha de ser acceptat.',
    'accepted_if' => 'El :attribute ha de ser acceptat quan :other és :value.',
    'active_url' => 'El :attribute no és una URL vàlida.',
    'after' => 'El :attribute ha de ser una data posterior a :date.',
    'after_or_equal' => 'El :attribute ha de ser una data posterior o igual a :date.',
    'alpha' => 'El :attribute solo ha de contenir lletres.',
    'alpha_dash' => 'El :attribute solo ha de contenir lletres, números, guiones i guiones baixos.',
    'alpha_num' => 'El :attribute solo ha de contenir lletres i números.',
    'array' => 'El :attribute ha de ser una matriz.',
    'before' => 'El :attribute ha de ser una data anterior a :date.',
    'before_or_equal' => 'El :attribute ha de ser una data anterior o igual a :date.',
    'entre' => [
        'numeric' => 'El :attribute ha d estar entre :min i :max.',
        'file' => 'El :attribute ha d estar entre :min i :max kilobytes.',
        'string' => 'El :attribute ha d estar entre :min i :max caràcters.',
        'array' => 'El :attribute ha de tenir entre :min i :max elements.',
    ],
    'boolean' => 'El camp :attribute ha de ser veritable o fals.',
    'confirmed' => 'La confirmació de :attribute no coincide.',
    'current_password' => 'La contrasenya és incorrecta.',
    'date' => 'El :attribute no és una data vàlida.',
    'date_equals' => 'El :attribute ha de ser una data igual a :date.',
    'date_format' => 'El :attribute no coincide amb el format :format.',
    'declined' => 'El :attribute debe rechazarse.',
    'declined_if' => 'El :attribute s ha de rechazar quan :other és :value.',
    'different' => 'El :attribute y :other han de ser diferents.',
    'digits' => 'El :attribute ha de tenir :digits dígitos.',
    'digits_between' => 'El :attribute ha de tenir entre :min i :max dígitos.',
    'dimensions' => 'El :attribute té dimensions d imatge no vàlides.',
    'distinct' => 'El camp :attribute tiene un valor duplicado.',
    'email' => 'El :attribute ha de ser una adreça de correu electrònic vàlida.',
    'ends_with' => 'El :attribute ha d acabar amb un dels següents: :values.',
    'enum' => 'El :attribute seleccionat no és vàlid.',
    'exists' => 'El :attribute seleccionat no és vàlid.',
    'file' => 'El :attribute ha de ser un arxiu.',
    'filled' => 'El camp :attribute ha de tenir un valor.',
    'gt' => [
        'numeric' => 'El :attribute ha de ser major que :value.',
        'file' => 'El :attribute ha de ser major que :value kilobytes.',
        'string' => 'El :attribute ha de ser major que :value caracteres.',
        'array' => 'El :attribute ha de tenir més de :value elements.',
    ],
    'gte' => [
        'numeric' => 'El atribut: ha de ser major o igual que :value.',
        'file' => 'El atribut : ha de ser major o igual que :value kilobytes.',
        'string' => 'El atribut: ha de ser major o igual que :value caracteres.',
        'array' => 'El atribut : ha de tenir :value elements o més.',
    ],
    'image' => 'El atribut: ha de ser una imatge.',
    'in' => 'El atribut: seleccionat no és vàlid.',
    'in_array' => 'El camp : no existeix en :other.',
    'integer' => 'El atribut: ha de ser un enter.',
    'ip' => 'El atribut: ha de ser una adreça IP vàlida.',
    'ipv4' => 'El atribut: ha de ser una direcció vàlida IPv4.',
    'ipv6' => 'El :attribute ha de ser una adreça IPv6 vàlida.',
    'json' => 'El :attribute ha de ser una cadena JSON vàlida.',
    'lt' => [
        'numeric' => 'El :attribute ha de ser menor que :value.',
        'file' => 'El :attribute ha de ser menor que :value kilobytes.',
        'string' => 'El :attribute ha de ser menor que :value caracteres.',
        'array' => 'El :attribute ha de tenir menys de :value elements.',
    ],
    'lte' => [
        'numeric' => 'El :attribute ha de ser menor o igual que :value.',
        'file' => 'El :attribute ha de ser menor o igual que :value kilobytes.',
        'string' => 'El :attribute ha de ser menor o igual que :value caracteres.',
        'array' => 'El :attribute no ha de tenir més de :value elements.',
    ],
    'mac_address' => 'El :attribute ha de ser una adreça MAC vàlida.',
    'max' => [
        'numeric' => 'El :attribute no ha de ser major que :max.',
        'file' => 'El :attribute no ha de ser major que :max kilobytes.',
        'string' => 'El :attribute no ha de ser major que :max caràcters.',
        'array' => 'El :attribute no ha de tenir més de :max elements.',
    ],
    'mimes' => 'El :attribute ha de ser un arxiu de tipus: :values.',
    'mimetypes' => 'El :attribute ha de ser un arxiu de tipus: :values.',
    'min' => [
        'numeric' => 'El :attribute ha de tenir al menys :min.',
        'file' => 'El :attribute ha de tenir al menys :min kilobytes.',
        'string' => 'El :attribute ha de tenir al menys :min caràcters.',
        'array' => 'El :attribute ha de tenir al menys :min elements.',
    ],
    'multiple_of' => 'El :attribute ha de ser un múltiple de :value.',
    'not_in' => 'El selected :attribute no és vàlid.',
    'not_regex' => 'El format :attribute no és vàlid.',
    'numeric' => 'El :attribute ha de ser un nombre.',
    'password' => 'La contrasenya és incorrecta.',
    'present' => 'El campo :attribute ha d estar present.',
    'prohibited' => 'El campo :attribute està prohibit.',
    'prohibited_if' => 'El camp :attribute està prohibit quan :other és :value.',
    'prohibited_unless' => 'El camp :attribute està prohibit a menys que :other estigui en :values.',
    'prohibits' => 'El camp :attribute prohíbe que :other estigui present.',
    'regex' => 'El format :attribute no és vàlid.',
    'required' => 'El camp :attribute is obligatorio.',
    'required_array_keys' => 'El camp :attribute ha de contenir les entrades per a: :values.',
    'required_if' => 'El camp :attribute és obligatori quan :other és :value.',
    'required_unless' => 'El camp :attribute és obligatori a menys que :other estigui en :values.',
    'required_with' => 'El camp :attribute és obligatori quan :values ​​​​está present.',
    'required_with_all' => 'El camp :attribute és obligatori quan :values ​​​​está present.',
    'required_without' => 'El camp :attribute és obligatori quan :values ​​​​no està present.',
    'required_without_all' => 'El camp :attribute és obligatori quan ninguno de :values ​​​​és present.',
    'same' => 'El camp :attribute y :other ha de coincidir.',
    'mida' => [
        'numeric' => 'El :attribute ha de ser :size.',
        'file' => 'El :attribute ha de ser :size kilobytes.',
        'string' => 'El :attribute ha de ser :size caràcters.',
        'array' => 'El :attribute ha de contenir :size elements.',
    ],
    'starts_with' => 'El :attribute ha de començar amb un dels següents: :values.',
    'string' => 'El :attribute ha de ser una cadena.',
    'timezone' => 'El :attribute ha de ser una zona horaria vàlida.',
    'unique' => 'El :attribute ja ha estat presa.',
    'uploaded' => 'El :attribute no es pot carregar.',
    'url' => 'El :attribute ha de ser una zona horaria vàlida. URL.',
    'uuid' => 'L atribut: ha de ser un UUID vàlid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
