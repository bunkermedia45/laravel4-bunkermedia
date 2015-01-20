<?php
namespace BunkerMedia;

use BunkerMedia\Html\A;
use BunkerMedia\Html\Abbr;
use Carbon\Carbon;

class Formatter {
    static function mailto($email, $title = null) {
        if ($title === null) {
            // Use the email address as the title
            $title = $email;
        }
        return A::factory()->setHref('mailto:' . $email)->setContent($title);
    }

    static function date($date = null) {
        if(!$date) return '';
        return Abbr::factory()->setContent($date->diffForHumans())->setTitle($date->format('d.m.Y H:i:s'));
    }

    static function number($number, $decimals = 2, $dec_point = ',', $thousand_sep = '.') {
        return number_format($number, $decimals, $dec_point, $thousand_sep);
    }
}