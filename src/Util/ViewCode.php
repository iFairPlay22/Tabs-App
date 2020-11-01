<?php

namespace App\Util;

class ViewCode
{
    private static $list = [
        "E",
        "B",
        "G",
        "C",
        "A",
        "D",
        "J",
        "I",
        "F",
        "H",
    ];

    private static $codeLength = 6;

    private static function intToString($id): string
    {
        return sprintf('%0' . self::$codeLength . 'd', $id);
    }

    public static function codeFromId($id)
    {
        $numbers = str_split(self::intToString($id));

        $code = "";
        foreach ($numbers as $number)
            $code .= self::$list[$number];

        return "#" . $code;
    }

    public static function idFromCode($code)
    {
        $codes = str_split($code);

        $id = "";
        for ($i = 1; $i <= self::$codeLength; $i++)
            $id .= array_search($codes[$i], self::$list);

        return intval($id);
    }
}
