<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('cards');
    }

    public function createPlayers() {
        $session = session();
        $players = $this->request->getPost("players");
        if($players <= 0) {
            return; 
        }
        $pattern = ["S", "H", "C", "D"];
        $number  = [
            13 => "K",
            12 => "Q",
            11 => "J",
            10 => "X",
            9  => "9",
            8  => "8",
            7  => "7",
            6  => "6",
            5  => "5",
            4  => "4",
            3  => "3",
            2  => "2",
            1  => "A"
        ];
        $deal = [];
        foreach($pattern as $suit) {
            foreach($number as $value) {
                $deck[] = "{$suit}-{$value}";
            }
        }
        shuffle($deck);
        $player_hand = array_fill(0, $players, []);
        for($i = 0; $i < count($deck); $i++) {
            $player_index = $i % $players;
            $player_hand[$player_index][] = $deck[$i];
        }
        $lines = [];
        foreach ($player_hand as $hand) {
            $lines[] = implode(",", $hand);
        }
        $output = implode("\n", $lines);
        return $this->response->setBody($output);
    }

}
