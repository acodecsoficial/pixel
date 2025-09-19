<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use App\Models\Config;
use App\Models\GameProvider;
use App\Models\SidebarLink;
use App\Models\SocialUrl;
use App\Models\User;
use App\Traits\SendMail;

class SiteController extends Controller
{
    use SendMail;

    protected $configs;

    public function __construct()
    {
        $this->configs = Config::first(['*', 'scripts']);
    }

    public function __invoke()
    {
        if ($this->configs->maintenance == 1) {
            return view('errors.503');
        }

        if (auth()->check()){
    		$refcode = Cookie::get('refcode');
            $token = hash('sha256','5<Grwtf`CKzk~(Fu'.md5(auth()->user()->email.'-'.time()));
            $referred = DB::table('users')->select('email')->where('code', $refcode)->first();

            if($refcode != null && strlen($refcode) == 7 && $referred != null) {
                if(auth()->user()->inviter == NULL && $referred->email != auth()->user()->email) {
                    DB::table('users')
                        ->where('email', auth()->user()->email)
                        ->update([
                            'inviter' => $referred->email
                        ]);
                }
            }
            //if((auth('api')->user()->cpf == NULL || auth('api')->user()->last_name == NULL || auth('api')->user()->phone == NULL || auth('api')->user()->name == NULL) && auth('api')->user()->rank != 'siteAdmin') return view('authenticator', ['token' => $token, 'time' => $time]);
        }

        $banners = Banner::all()->sortBy('order_value');
        $carouselBanners = $banners->where("type", "carousel");
        $desktop_action_btns = $banners->where("type", "desktop_action_btns");
        $mobile_action_btns = $banners->where("type", "mobile_action_btns");

        $socialurls = SocialUrl::first();

        $sidebarLinks = SidebarLink::all()
            ->sortBy('order_value')
            ->groupBy('group_name')
            ->map(function ($group) {
                return [
                    'group_name' => $group->first()->group_name,
                    'links' => $group->map(function ($link) {
                        return [
                            'url' => $link->url,
                            'name' => $link->name,
                            'icon' => $link->icon,
                            'color' => $link->color,
                        ];
                    })->toArray(),
                ];
            })
            ->values()
            ->toArray();

        $sportEnabled = GameProvider::where('provider_name', 'TBS2API')->exists();
        $googleLoginEnabled = env('GOOGLE_CLIENT_ID') !== null;

        // !Caution: Esses dados ficarão visíveis no frontend, não coloque dados sensíveis
        $data = [
            'website_name' => $this->configs->website_name,
            'website_url' => $this->configs->websiteurl,
            'logo' => $this->configs->logomarca,
            'favicon' => $this->configs->favicon,
            'icon_img' => $this->configs->logomarca,
            'primary_color' => $this->configs->primary_color,
            'banners' => $carouselBanners->values()->toArray(),
            'desktop_action_btns' => $desktop_action_btns->values()->toArray(),
            'mobile_action_btns' => $mobile_action_btns->values()->toArray(),
            'sidebar_links' => $sidebarLinks,
            'footer_emails' => [
                'LEGAL_EMAIL' => 'compliance@' . $_SERVER['SERVER_NAME'],
                'PARTNER_EMAIL' => 'afiliados@' . $_SERVER['SERVER_NAME'],
                'SUPPORT_EMAIL' => 'sac@' . $_SERVER['SERVER_NAME'],
            ],
            'social' => [
                'instagram' => $socialurls->instagram_url,
                'whatsapp' => $socialurls->whatsapp_url,
                'telegram' => $socialurls->telegram_url,
                'youtube' => $socialurls->youtube_url,
                'jivo_url' => $socialurls->jivo_url
            ],
            'withdraw' => [
                'tax_active' => $this->configs->tax_active,
                'tax_value' => $this->configs->tax_withdraw,
                'min_amount' => $this->configs->minimosaque,
                'daily_limit' => $this->configs->daily_limit_whitdrawal,
            ],
            'cpa' => $this->configs->cpa_value,
            'bonus_percent' => $this->configs->bonus_percentage,
            'cpa_min' => $this->configs->cpa_min,
            'deposit' => [
                'min_amount' => $this->configs->minimodeposit,
                'show_bonus_banner' => $this->configs->activate_bonus == 1,
            ],
            'cloudflare_sitekey' => $this->configs->cf_key,
            'sports_enabled' => $sportEnabled,
            'google_login_enabled' => $googleLoginEnabled,
        ];

        return view('app', [
            'data' => $data,
            'configs' => $this->configs,
        ]);
    }

    public function sendWithdrawEmail (Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric',
        ]);
        $user = User::where('email', $request->email)->firstOrFail();

        $this->sendWithdrawMail($user, $request->amount);

        return response();
    }
}
