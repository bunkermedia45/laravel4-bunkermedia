<?php
namespace BunkerMedia\Helper;

class Text {

    /**
     * Передаем дробь, на выходе имеем текст в процентах
     * Text::percent(0.7458) => "74,58%"
     *
     * @param float $val
     *
     * @return string
     */
    static function percent($val) {
        $val = floatval($val) * 100;

        return number_format($val, 0, ',', '.') . '%';
    }

    static function slug($str) {
        $tr = [
            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Е" => "E",
            "Ж" => "J",
            "З" => "Z",
            "И" => "I",
            "Й" => "Y",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "H",
            "Ц" => "TS",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SCH",
            "Ъ" => "",
            "Ы" => "YI",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "YU",
            "Я" => "YA",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ж" => "j",
            "з" => "z",
            "и" => "i",
            "й" => "y",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sch",
            "ъ" => "y",
            "ы" => "yi",
            "ь" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya"
        ];

        $title = strtr($str, $tr);

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        // Trim separators from the beginning and end
        return trim($title, $separator);

    }

    static function plural_with_number($cnt, $form1, $form2, $form5, $form0 = null) {
        $cnt = intval($cnt);
        $ret = self::plural($cnt, $form1, $form2, $form5, $form0);
        if ($cnt) {
            $ret = $cnt . ' ' . $ret;
        }

        return $ret;
    }

    static function plural($cnt, $form1, $form2, $form5, $form0 = null) {
        $cnt = intval($cnt);
        if (!$cnt) {
            if (is_null($form0)) {
                return 'нет ' . $form5;
            }
            else {
                return $form0;
            }
        }
        $form1 = ($form1);
        $form2 = ($form2);
        $form5 = ($form5);
        $n     = abs($cnt) % 100;
        $n1    = $cnt % 10;
        if ($n > 10 && $n < 20) {
            return $form5;
        }
        if ($n1 > 1 && $n1 < 5)
            return $form2;
        if ($n1 == 1)
            return $form1;

        return $form5;
    }

    static function mb_ucfirst($text) {
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }

    static function num2str($num, $without_units = true) {
        $nul     = 'ноль';
        $ten     = [
            [
                '',
                'один',
                'два',
                'три',
                'четыре',
                'пять',
                'шесть',
                'семь',
                'восемь',
                'девять'
            ],
            [
                '',
                'одна',
                'две',
                'три',
                'четыре',
                'пять',
                'шесть',
                'семь',
                'восемь',
                'девять'
            ],
        ];
        $a20     = [
            'десять',
            'одиннадцать',
            'двенадцать',
            'тринадцать',
            'четырнадцать',
            'пятнадцать',
            'шестнадцать',
            'семнадцать',
            'восемнадцать',
            'девятнадцать'
        ];
        $tens    = [
            2 => 'двадцать',
            'тридцать',
            'сорок',
            'пятьдесят',
            'шестьдесят',
            'семьдесят',
            'восемьдесят',
            'девяносто'
        ];
        $hundred = [
            '',
            'сто',
            'двести',
            'триста',
            'четыреста',
            'пятьсот',
            'шестьсот',
            'семьсот',
            'восемьсот',
            'девятьсот'
        ];
        $unit    = [ // Units
                     [
                         'копейка',
                         'копейки',
                         'копеек',
                         1
                     ],
                     [
                         'рубль',
                         'рубля',
                         'рублей',
                         0
                     ],
                     [
                         'тысяча',
                         'тысячи',
                         'тысяч',
                         1
                     ],
                     [
                         'миллион',
                         'миллиона',
                         'миллионов',
                         0
                     ],
                     [
                         'миллиард',
                         'милиарда',
                         'миллиардов',
                         0
                     ],
        ];
        //
        //        $rub = intval($num);
        //        \Debug::info($rub);
        //        $kop = 0;
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = [];
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
                //                \Debug::info($uk, '$uk');
                //                \Debug::info($v, '$v');
                if (!intval($v))
                    continue;
                $uk     = sizeof($unit) - $uk - 1; // unit key
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1)
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1)
                    $out[] = self::plural($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            } //foreach
        }
        else $out[] = $nul;
        //        $out[] = self::plural(
        //            intval($rub),
        //            $unit[1][0],
        //            $unit[1][1],
        //            $unit[1][2]
        //        )
        //        ; // rub
        //        $out[] = str_pad(
        //                $kop,
        //                2,
        //                '0',
        //                STR_PAD_LEFT
        //            ) . ' ' . self::plural(
        //                $kop,
        //                $unit[0][0],
        //                $unit[0][1],
        //                $unit[0][2],
        //                'копеек'
        //            )
        //        ; // kop

        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }


}