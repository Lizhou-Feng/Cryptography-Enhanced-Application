<?php namespace CTF\CRYPTO;
// ref: http://rumkin.com/tools/cipher/morse.php
class Morse {
    private static $morse_valid = array('.', '-');
    private static $morse_decode = array(
        '.-'=>'A',
        '-...'=>'B',
        '-.-.'=>'C',
        '-..'=>'D',
        '.'=>'E',
        '..-.'=>'F',
        '--.'=>'G',
        '....'=>'H',
        '..'=>'I',
        '.---'=>'J',
        '-.-'=>'K',
        '.-..'=>'L',
        '--'=>'M',
        '-.'=>'N',
        '---'=>'O',
        '.--.'=>'P',
        '--.-'=>'Q',
        '.-.'=>'R',
        '...'=>'S',
        '-'=>'T',
        '..-'=>'U',
        '...-'=>'V',
        '.--'=>'W',
        '-..-'=>'X',
        '-.--'=>'Y',
        '--..'=>'Z',
        '.----'=>'1',
        '..---'=>'2',
        '...--'=>'3',
        '....-'=>'4',
        '.....'=>'5',
        '-....'=>'6',
        '--...'=>'7',
        '---..'=>'8',
        '----.'=>'9',
        '-----'=>'0',
        '.-.-.-'=>'.',
        '--..--'=>',',
        '..--..'=>'?',
        '-....-' => '-',
        '-...-'=>'=',
        '---...'=>':',
        '-.-.-.'=>';',
        '-.--.'=>'(',
        '-.--.-'=>')',
        '-..-.'=>'/',
        '.-..-.'=>'"',
        '...-..-'=>'$',
        '.----.'=>"'",
        '.-.-..'=>'¶',
        '-.-.--'=>'!',
        '..--.-'=>'_',
        '.--.-.'=>'@',
        '.-.-.'=>'+',
        '.-...'=>'~',
        '...-.-'=>'#',
        '-..-.'=>'/'
    );

    public static function encode($plaintext, $keep_invalid = true) {
        if(empty($plaintext)) {
            return $plaintext;
        }

        $encoded = "";
        $morse_encode = array_flip(self::$morse_decode);

        for($i = 0; $i < strlen($plaintext); $i++) {
            $el = strtoupper(substr($plaintext, $i, 1));
            if(array_key_exists($el, $morse_encode)) {
                $encoded .= " " . $morse_encode[$el] ." ";
            } else {
                if($keep_invalid) {
                    $encoded .= $el;
                }
            }
        }

        return $encoded;
    }

    public static function decode($encoded, $keep_invalid = true) {
        if(empty($encoded)) {
            return $encoded;
        }
        $el = "";
        $decoded = "";
        $streak = false;
        for($i = 0; $i < strlen($encoded); $i++) {
            if(in_array(substr($encoded, $i, 1), self::$morse_valid)) {
                $el .= substr($encoded, $i, 1);
                $streak = true;
            } else {
                if($streak) {
                    $decoded .= self::$morse_decode[$el];
                    $el = "";
                    $streak = false;
                } else {
                    if($keep_invalid) {
                        $decoded .= substr($encoded, $i, 1);
                    }
                }
            } 
        }   

        return $decoded;
    }

}
