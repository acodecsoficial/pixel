<?php

namespace App\Traits;

trait RolloverSum
{
    public function sumRollover($data)
    {
        $user = $data->user;
        $rollover = $data->amount * $data->rollover;

        if ($user->deposit_sum > 0) {
            $bonus = 0;
        } else {
            $bonus = $data->amount * ($data->bonus_percent / 100);
        }

        $calcData = (object) [
            'bonus' => $bonus,
            'rollover' => $rollover
        ];

        return $calcData;
    }
}
