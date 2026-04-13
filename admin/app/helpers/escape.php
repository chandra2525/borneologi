<?php

function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function e_attr($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function e_js($string)
{
    return json_encode($string, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

function e_url($string)
{
    return rawurlencode($string);
}