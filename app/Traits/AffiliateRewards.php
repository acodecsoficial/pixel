<?php

namespace App\Traits;

use App\Models\User;
use App\Models\AffiliateTransaction;
trait AffiliateRewards
{

    public function revFivers($user,$total){

        if ($user->inviter) {
            $afiliado = User::where('email', $user->inviter)->first();

            if ($afiliado->affiliate) {
                $refAff = $afiliado->referPercent;
                $refRewards = $total * ($refAff / 100);

                $afiliado->increment('referRewards', $refRewards);

                $typeBet = $refRewards > 0 ? "win" : "loss";

                AffiliateTransaction::create([
                    'affiliate_id' => $afiliado->id,
                    'affiliate_email' => $afiliado->email,
                    'indicated_id' => $user->id,
                    'indicated_email' => $user->email,
                    'transaction_value' => $total,
                    'affiliate_rewards' => $refRewards,
                    'affiliate_method' => 'rev',
                    'reward_type' => strtolower($typeBet),
                ]);

                if($afiliado->inviter){
                    $dad_aff = User::where('email', $afiliado->inviter)->first();
                    $dad_rewards = $refRewards * ($dad_aff->subPercent / 100);

                    $dad_aff->increment('referRewards', $dad_rewards);

                    AffiliateTransaction::create([
                        'affiliate_id' => $dad_aff->id,
                        'affiliate_email' => $dad_aff->email,
                        'indicated_id' => $afiliado->id,
                        'indicated_email' => $afiliado->email,
                        'transaction_value' => $refRewards,
                        'affiliate_rewards' => $dad_rewards,
                        'affiliate_method' => 'sub-rev',
                        'reward_type' => strtolower($typeBet),
                    ]);
                }

            }
        }
    }

    public function revXGaming($user,$total,$typeBet){
        if ($user->inviter) {
            $afiliado = User::where('email', $user->inviter)->first();
            if ($afiliado->affiliate) {
                $refAff = $afiliado->referPercent;
                $refRewards = $total * ($refAff / 100);

                if($typeBet == "WIN"){
                    $afiliado->increment('referRewards', $refRewards);
                } else {
                    $afiliado->decrement('referRewards', $refRewards);
                    $refRewards *= -1;
                }

                AffiliateTransaction::create([
                    'affiliate_id' => $afiliado->id,
                    'affiliate_email' => $afiliado->email,
                    'indicated_id' => $user->id,
                    'indicated_email' => $user->email,
                    'transaction_value' => $total,
                    'affiliate_rewards' => $refRewards,
                    'affiliate_method' => 'rev',
                    'reward_type' => strtolower($typeBet),
                ]);

                if($afiliado->inviter){
                    $dad_aff = User::where('email', $afiliado->inviter)->first();
                    $dad_rewards = $refRewards * ($dad_aff->subPercent / 100);

                    if($typeBet == "WIN"){
                        $dad_aff->increment('referRewards', $dad_rewards);
                    } else {
    					$dad_aff->decrement('referRewards', abs($dad_rewards));
                    }

                    AffiliateTransaction::create([
                        'affiliate_id' => $dad_aff->id,
                        'affiliate_email' => $dad_aff->email,
                        'indicated_id' => $afiliado->id,
                        'indicated_email' => $afiliado->email,
                        'transaction_value' => $refRewards,
                        'affiliate_rewards' => $dad_rewards,
                        'affiliate_method' => 'sub-rev',
                        'reward_type' => strtolower($typeBet),
                    ]);
                }

            }
        }
    }

    public function cpaAffiliate($user,$value){

        if ($user->deposit_sum <= 0 && $user->inviter) {
            $afiliado = User::where('email', $user->inviter)->first();

            if($afiliado && $afiliado->affiliate){

                if ($value >= $afiliado->baseline) {
                    $randomNumber = rand(0, 100);

                    if ($randomNumber <= $afiliado->cpa_chance) {
                        $value_cpa = $afiliado->value_cpa;
                        $afiliado->increment('referRewards', $value_cpa);

                        AffiliateTransaction::create([
                            'affiliate_id' => $afiliado->id,
                            'affiliate_email' => $afiliado->email,
                            'indicated_id' => $user->id,
                            'indicated_email' => $user->email,
                            'transaction_value' => $value,
                            'affiliate_rewards' => $value_cpa,
                            'affiliate_method' => 'cpa',
                            'reward_type' => 'win',
                        ]);

                        if($afiliado->inviter){
                            $dad_aff = User::where('email', $afiliado->inviter)->first();
                            $dad_rewards = $value_cpa * ($dad_aff->subPercent / 100);

                            $dad_aff->increment('referRewards', $dad_rewards);

                            AffiliateTransaction::create([
                                'affiliate_id' => $dad_aff->id,
                                'affiliate_email' => $dad_aff->email,
                                'indicated_id' => $afiliado->id,
                                'indicated_email' => $afiliado->email,
                                'transaction_value' => $afiliado->value_cpa,
                                'affiliate_rewards' => $dad_rewards,
                                'affiliate_method' => 'sub-cpa',
                                'reward_type' => 'win',
                            ]);
                        }

                    }
                }
            } else {
                if ($value >= $afiliado->baseline) {

                    $value_cpa = $afiliado->value_cpa;
                    $afiliado->increment('wallet_bonus', $value_cpa);
                    $afiliado->increment('rollover', $value_cpa);
                }
            }
        }
    }

}
