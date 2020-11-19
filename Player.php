<?php
@session_start();

class Player
{
            //matching cards and points
    private $cards = ['2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,
                '9' => 9,'T' => 10, 'J' => 10, 'Q' => 10, 'K' => 10, 'A' => 11],
            //card suits
            $types = ['Cl', 'Sp', 'He', 'Di'],
            //all cards
            $list = ['2Cl','2Sp','2He','2Di',
                '3Cl','3Sp','3He','3Di',
                '4Cl','4Sp','4He','4Di',
                '5Cl','5Sp','5He','5Di',
                '6Cl','6Sp','6He','6Di',
                '7Cl','7Sp','7He','7Di',
                '8Cl','8Sp','8He','8Di',
                '9Cl','9Sp','9He','9Di',
                'TCl','TSp','THe','TDi',
                'JCl','JSp','JHe','JDi',
                'QCl','QSp','QHe','QDi',
                'KCl','KSp','KHe','KDi',
                'ACl','ASp','AHe','ADi'];
    function __construct($name) {
        $this->name = $name;
    }
    function addCard($out){
        $isOut = true;
        while ($isOut) {
            $rand_card = ($dropped= array_rand($this->cards)) . $this->types[array_rand($this->types)];
            if (in_array($rand_card, $out)) {
                continue;
            } else {
                if (sizeof($_SESSION[$this->name . '_deck']) >= 2 and $rand_card[0] == 'A') {
                    $_SESSION[$this->name . '_points'] += 1;
                } else {
                    $_SESSION[$this->name . '_points'] += $this->cards[$dropped];
                }
                array_push($_SESSION[$this->name . '_deck'], $rand_card);
                $_SESSION['out'][] = $rand_card;
                $isOut = false;
                break;
            }
        }
    }
}