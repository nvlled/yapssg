<?php
function generateUrlSlug($string, $maxlen=0)
{
    $string = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($string)), '-');
    if ($maxlen && strlen($string) > $maxlen) {
        $string = substr($string, 0, $maxlen);
        $pos = strrpos($string, '-');
        if ($pos > 0) {
            $string = substr($string, 0, $pos);
        }
    }
    return $string;
}
