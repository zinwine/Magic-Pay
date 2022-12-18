<?php

use Hashids\Hashids;


function data2Hash($data)
{
    $hash = new Hashids('magicpay12345#$@&^%', 8);
    return $hash->encode($data);
}

function hash2Data($hash_data)
{
    $hash = new Hashids('magicpay12345#$@&^%', 8);
    return $hash->decode($hash_data)[0];
}

?>