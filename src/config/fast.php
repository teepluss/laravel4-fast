<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Debug Content
    |--------------------------------------------------------------------------
    |
    | Default is no debugging, when you need to find out what is error
    | in cache data you can enable, enabled mode is not cache anything.
    |
    */

    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Tag
    |--------------------------------------------------------------------------
    |
    | Cache tag is not need to change, untill the tag is using by another
    | on your application.
    |
    */

    'tags' => array('fast.cache'),

    /*
    |--------------------------------------------------------------------------
    | Default Expires Cache.
    |--------------------------------------------------------------------------
    |
    | Default time to clear each cache.
    |
    */

    'expireInSecond' => 60,

    /*
    |--------------------------------------------------------------------------
    | Maximum Time Expiration.
    |--------------------------------------------------------------------------
    |
    | Defaut is a day.
    |
    */

    'maximumAliveInSecond' => 86400,

    /*
    |--------------------------------------------------------------------------
    | Logic To trigger.
    |--------------------------------------------------------------------------
    |
    | This is logic to build cache after the human go to page.
    |
    */

    'trigger' => function($fast, $url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'fast-cache-0.1b');
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
    }

);