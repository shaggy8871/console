<?php

namespace Console;

/**
 * Outputs cursor movement and color codes to the terminal
 */

class Decorate
{

    static protected $validColors = [
        // Foreground colors
        'black' => '0;30',
        'red' => '0;31',
        'green' => '0;32',
        'yellow' => '0;33',
        'blue' => '0;34',
        'purple' => '0;35',
        'cyan' => '0;36',
        'light_gray' => '0;37',
        'dark_gray' => '0;90',
        'light_red' => '0;91',
        'light_green' => '0;92',
        'light_yellow' => '0;93',
        'light_blue' => '0;94',
        'light_purple' => '0;95',
        'light_cyan' => '0;96',
        'white' => '0;97',
        // Bold colors
        'bold_black' => '1;30',
        'bold_red' => '1;31',
        'bold_green' => '1;32',
        'bold_yellow' => '1;33',
        'bold_blue' => '1;34',
        'bold_purple' => '1;35',
        'bold_cyan' => '1;36',
        'bold_light_gray' => '1;37',
        'bold_dark_gray' => '1;90',
        'bold_light_red' => '1;91',
        'bold_light_green' => '1;92',
        'bold_light_yellow' => '1;93',
        'bold_light_blue' => '1;94',
        'bold_light_purple' => '1;95',
        'bold_light_cyan' => '1;96',
        'bold_white' => '1;97',
        // Underline colors
        'underline_black' => '4;30',
        'underline_red' => '4;31',
        'underline_green' => '4;32',
        'underline_yellow' => '4;33',
        'underline_blue' => '4;34',
        'underline_purple' => '4;35',
        'underline_cyan' => '4;36',
        'underline_light_gray' => '4;37',
        'underline_dark_gray' => '4;90',
        'underline_light_red' => '4;91',
        'underline_light_green' => '4;92',
        'underline_light_yellow' => '4;93',
        'underline_light_blue' => '4;94',
        'underline_light_purple' => '4;95',
        'underline_light_cyan' => '4;96',
        'underline_white' => '4;97',
        // Background colors
        'bg_black' => '40',
        'bg_red' => '41',
        'bg_green' => '42',
        'bg_yellow' => '43',
        'bg_blue' => '44',
        'bg_purple' => '45',
        'bg_cyan' => '46',
        'bg_light_gray' => '47',
        'bg_dark_gray' => '100',
        'bg_white' => '107',
    ];

    /**
     * Return the text with one or more color code prefixes applied
     */
    public static function color($text, $colors)
    {

        $colors = explode(' ', $colors);
        $modBold = false;
        $modUnderline = false;
        $backgroundColors = [];
        $foregroundColors = [];
        // Look for bold, underline and hi modifiers
        foreach($colors as $color) {
            if ($color == 'bold') {
                $modBold = true;
            }
            if ($color == 'underline') {
                $modUnderline = true;
            }
            if (substr($color, 0, 3) == 'bg_') {
                if (isset(static::$validColors[$color])) {
                    $backgroundColors[] = $color;
                }
            } else {
                if (isset(static::$validColors[$color])) {
                    $foregroundColors[] = $color;
                }
            }
        }
        $colorCode = '';
        // Foreground colors next
        foreach($foregroundColors as $color) {
            $color = ($modBold ? 'bold_' : ($modUnderline ? 'underline_' : '')) . $color;
            $colorCode .= "\033[" . static::$validColors[$color] . "m";
        }
        // Background colors first
        foreach($backgroundColors as $color) {
            $colorCode .= "\033[" . static::$validColors[$color] . "m";
        }
        return $colorCode . $text . "\033[0m";

    }

    public static function beep()
    {

        return "\007";

    }

    public static function saveCursorPos()
    {

        return "\033[s";

    }

    public static function restoreCursorPos()
    {

        return "\033[u";

    }

    public static function cursorForward()
    {

        return "\033[C";

    }

    public static function cursorBackward()
    {

        return "\033[D";

    }

    public static function cursorTo($row, $col)
    {

        return "\033[" . $row . ";" . $col . "H";

    }

    public static function cursorToCol($col)
    {

        return "\033[" . $col . "G";

    }

    public static function cursorUp($row)
    {

        return "\033[" . $row . "A";

    }

    public static function cursorDown($row)
    {

        return "\033[" . $row . "B";

    }

    public static function moveToStart()
    {

        return "\r";

    }

    public static function clearToEnd()
    {

        return "\033[K";

    }

}