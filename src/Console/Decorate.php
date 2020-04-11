<?php

namespace Console;

/**
 * Outputs cursor movement and color codes to the terminal
 */

class Decorate
{

    const FG_256_PREFIX = '38;5;';
    const BG_256_PREFIX = '48;5;';

    /**
     * @var array
     */
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
     * 
     * @param string the text to color
     * @param mixed the colors or attributes to apply
     * 
     * @return string
     */
    public static function color(string $text, $colors): string
    {
        $colors = !is_array($colors) ? explode(' ', $colors) : $colors;
        $modBold = false;
        $modUnderline = false;
        $backgroundColors = [];
        $foregroundColors = [];
        $vgaColors = [];
        // Look for bold, underline and hi modifiers
        foreach($colors as $color) {
            if ($color == 'bold') {
                $modBold = true;
                continue;
            }
            if ($color == 'underline') {
                $modUnderline = true;
                continue;
            }
            if (substr($color, 0, 3) == 'bg_') {
                if (isset(static::$validColors[$color])) {
                    $backgroundColors[] = $color;
                }
            } else {
                if (isset(static::$validColors[$color])) {
                    $foregroundColors[] = $color;
                } else
                if (substr($color, 0, strlen(static::FG_256_PREFIX)) == static::FG_256_PREFIX || substr($color, 0, strlen(static::BG_256_PREFIX)) == static::BG_256_PREFIX) {
                    $vgaColors[] = $color;
                }
            }
        }
        $colorCode = '';
        // Foreground colors first
        foreach($foregroundColors as $color) {
            $color = ($modBold ? 'bold_' : ($modUnderline ? 'underline_' : '')) . $color;
            $colorCode .= "\033[" . static::$validColors[$color] . "m";
        }
        // Background colors next
        foreach($backgroundColors as $color) {
            $colorCode .= "\033[" . static::$validColors[$color] . "m";
        }
        // Finally VGA (256) colors
        foreach($vgaColors as $color) {
            $colorCode .= "\033[" . $color . "m";
        }
        return $colorCode . $text . "\033[0m";
    }

    /**
     * Return a 256-color palette color code
     * 
     * @param int the color number
     * 
     * @return string
     */
    public static function vgaColor(int $color): string
    {
        return static::FG_256_PREFIX . $color;
    }

    /**
     * Return a 256-color palette background color code
     * 
     * @param int the color number
     * 
     * @return string
     */
    public static function vgaBackground(int $color)
    {
        return static::BG_256_PREFIX . $color;
    }

    /**
     * Make a sound
     * 
     * @return string
     */
    public static function beep(): string
    {
        return "\007";
    }

    /**
     * Save the cursor position
     * 
     * @return string
     */
    public static function saveCursorPos(): string
    {
        return "\033[s";
    }

    /**
     * Restore the cursor position
     * 
     * @return string
     */
    public static function restoreCursorPos(): string
    {
        return "\033[u";
    }

    /**
     * Move the cursor forward
     * 
     * @return string
     */
    public static function cursorForward(): string
    {
        return "\033[C";
    }

    /**
     * Move the cursor backward
     * 
     * @return string
     */
    public static function cursorBackward(): string
    {
        return "\033[D";
    }

    /**
     * Move the cursor to row, col
     * 
     * @param int the row
     * @param int the column
     * 
     * @return string
     */
    public static function cursorTo(int $row, int $col): string
    {
        return "\033[" . $row . ";" . $col . "H";
    }

    /**
     * Move the cursor to a specific column
     * 
     * @param int the column
     * 
     * @return string
     */
    public static function cursorToCol(int $col): string
    {
        return "\033[" . $col . "G";
    }

    /**
     * Move the cursor up to row
     * 
     * @param int the row
     * 
     * @return string
     */
    public static function cursorUp(int $row): string
    {
        return "\033[" . $row . "A";
    }

    /**
     * Move the cursor down to row
     * 
     * @param int the row
     * 
     * @return string
     */
    public static function cursorDown(int $row): string
    {
        return "\033[" . $row . "B";
    }

    /**
     * Move to the start of the line
     * 
     * @return string
     */
    public static function moveToStart(): string
    {
        return "\r";
    }

    /**
     * Clear to the end of the line
     * 
     * @return string
     */
    public static function clearToEnd(): string
    {
        return "\033[K";
    }

}