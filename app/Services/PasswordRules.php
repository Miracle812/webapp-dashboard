<?php namespace Services;

class PasswordRules extends \BaseController
{
    static $options = [
        'length'    => 6,
        'lowercase' => 0,
        'uppercase' => 2,
        'numbers'   => 1,
        'symbols'   => 0,
    ];

    static function check($x)
    {
        $rank = $messages = [];
        foreach (self::$options as $option => $value){
            $matches = [];
            switch ($option){
                case 'length': preg_match_all("/(.+)/", $x, $matches);
                    break;
                case 'lowercase': preg_match_all("/([a-z]+)/", $x, $matches);
                    break;
                case 'uppercase': preg_match_all("/([A-Z]+)/", $x, $matches);
                    break;
                case 'numbers': preg_match_all("/([0-9]+)/", $x, $matches);
                    break;
                case 'symbols': preg_match_all("/([^a-zA-Z0-9]+)/", $x, $matches);
                    break;
            }
            $text = 'Your password should contain (characters) at least : ';
            $result = strlen(implode('', $matches[0]));
            $rank[$option] = ['req' => $value, 'was' => $result];
            if($result < $value) {
                if ($option != 'length') {
                    $messages[] = $value . ' ' . $option . ' (typed ' . $rank[$option]['was'] . ')';
                }
            }
        }
        return (count($messages)) ? ($text . implode(', ', $messages) . '.') : null;
    }
}