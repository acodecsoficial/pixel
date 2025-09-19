<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'rollover',
        'wallet',
        'token',
        'ip_address',
        'last_ip_address',
        'wallet_bonus',
        'bonus',
        'name',
        'email',
        'referRewards',
        'collected',
        'cpf',
        'last_name',
        'phone',
        'username',
        'anti_bot',
        'password',
        'last_won',
        'last_lose',
        'deposit_sum',
        'deposit_sum_code',
        'inviter',
        'xp',
        'level',
        'code',
        'value_cpa',
        'referPercent',
        'subPercent',
        'baseline',
        'game_token',
        'ddi',
        'birth_date',
        'user_demo',
        'avatar',
        'address'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'cpa_chance',
        'referFake',
    ];

    protected $appends = [
        "affiliation_code",
        "min_deposit_amount_to_get_ref_bonus",
        "referral_bonus_active",
        "referral_bonus_amount",
        "invited_total",
        "referer_who_has_deposits",
        "timezone",
        "country_data",
        "language",
        "country",
        "currency",
        "total_balance"
    ];

    public function getAffiliationCodeAttribute()
    {
        return $this->code;
    }

    public function getTotalBalanceAttribute() {
        return $this->wallet + $this->wallet_bonus;
    }

    public function getMinDepositAmountToGetRefBonusAttribute()
    {
        $config = Config::first();
        return $config->cpa_min;
    }

    public function getReferralBonusActiveAttribute() {
        return true;
    }

    public function getTimezoneAttribute() {
        return 'America/Sao_Paulo';
    }

    public function getCurrencyAttribute() {
        return 'BRL';
    }

    public function getCountryAttribute() {
        return 'BRA';
    }

    public function getLanguageAttribute() {
        return 'pt-br';
    }

    public function getReferralBonusAmountAttribute() {
        return $this->value_cpa * 100;
    }

    public function getRefererWhoHasDepositsAttribute () {
        $config = Config::first();
        return User::join('payment_history', 'users.email', '=', 'payment_history.user')
            ->where('users.inviter', '=', $this->email)
            ->where('payment_history.credited', '>=', $config->cpa_min)
            ->count();
    }

    public function getInvitedTotalAttribute() {
        return DB::table('users')->where(['inviter' => $this->email])->count();
    }


    public function getCountryDataAttribute() {
        return [
            "id" => 254,
            "name" => "Brasil",
            "code" => "BRA",
            "flag_image_src" => "",
            "auto_update" => 1,
            "created_at" => null,
            "updated_at" => "2024-02-14T22:06:18.000000Z",
            "ddi" => "+55",
            "currency" => "BRL",
            "alpha2" => "BR"
        ];
    }
}
