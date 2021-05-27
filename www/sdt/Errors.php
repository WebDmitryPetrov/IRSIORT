<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 14.11.2014
 * Time: 15:01
 */
class Errors
{

    const CERTIFICATE_BLANK_NUMBER = 'certificate_blank_number';
    const NOTE_BLANK_NUMBER = 'note_blank_number';
    const CERTIFICATE_DOCUMENT_NUMBER = 'certificate_document_number';
    const XML = 'xml';
    const UPLOAD = 'UPLOAD';
    const PARSE = 'PARSE';
    static $items = array();

    static function add($type, $id, $value)
    {
        if (!array_key_exists($type, self::$items)) {
            self::$items[$type] = array();
        }

        if (!array_key_exists($id, self::$items[$type])) {
            self::$items[$type][$id] = array();
        }
        self::$items[$type][$id][] = $value;
    }

    static function get($type, $id, $asString = false)
    {
        if (empty(self::$items[$type][$id])) return null;

        if ($asString) {
            return implode(', ', self::$items[$type][$id]);
        }
        return self::$items[$type][$id];
    }

    public static function count()
    {
        $c = 0;
        foreach (self::$items as $g) {
            $c += count($g);
        }
        return $c;
    }


} 