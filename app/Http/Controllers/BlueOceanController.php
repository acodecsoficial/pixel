<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Config;
use App\Models\GameHistory;
use Illuminate\Http\Request;
use App\Models\GamesApi;
use App\Traits\AffiliateRewards;

class BlueOceanController extends Controller
{
    use AffiliateRewards;

    private $max_risk;
    private $manage_risk;

    public function __construct()
    {
        $config = Config::first();
        $this->max_risk = $config->value('max_risk');
        $this->manage_risk = $config->value('manage_risk');
    }

    private function getUserBalance($user_code)
    {
        $user = User::find($user_code);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return [
            "status" => "200",
            "balance" => round($user->wallet, 2)
        ];
    }

    public function debitUserBalance($userId, $amount, $game_id, $provider)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        if ($user->wallet < $amount) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        // Aplica regra de rollover
        if ($user->rollover > 0.0) {
            $novoRollover = $user->rollover - $amount;
            $user->rollover = $novoRollover < 0 ? 0.0 : $novoRollover;
        }

        $user->wallet -= $amount;
        $user->save();

        if ($user->user_demo == 0 && $user->wallet_bonus == 0.0) {
            $this->revXGaming($user, $amount, "WIN");
        }

        GameHistory::create([
            'user_id' => $userId,
            'amount' => $amount,
            'provider' => $provider,
            'provider_tx_id' => "",
            'game' => $game_id,
            'action' => 'loss',
            'round_id' => "",
            'session_token' => "",
            'demo' => $user->user_demo
        ]);

        return response()->json([
            'status' => '200',
            'balance' => round($user->wallet, 2),
        ]);
    }

    public function creditUserBalance($userId, $amount, $game_id, $provider)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $user->wallet += $amount;
        $user->save();

        if ($user->user_demo == 0 && $user->wallet_bonus == 0.0 && $amount > 0) {
            $this->revXGaming($user, $amount, "LOSS");
        }

        if ($amount > 0) {
            GameHistory::create([
                'user_id' => $userId,
                'amount' => $amount,
                'provider' => $provider,
                'provider_tx_id' => "",
                'game' => $game_id,
                'action' => 'win',
                'round_id' => "",
                'session_token' => "",
                'demo' => $user->user_demo
            ]);
        }

        return response()->json([
            'status' => '200',
            'balance' => round($user->wallet, 2),
        ]);
    }

    public function webhookBlueOcean(Request $request)
    {
        \Log::info('Requisição recebida:', $request->all());

        $action = $request->input('action');
        $game_id = $request->input('game_id');
        $amountRaw = $request->input('amount', 0);
        $amount = floatval(str_replace(',', '.', $amountRaw));

        $usernameRaw = $request->input('username');
        $usernameParts = explode('-', $usernameRaw);
        $userId = $usernameParts[0];

        if (!is_numeric($userId)) {
            \Log::error('ID de usuário inválido recebido: ' . $userId);
            return response()->json(['error' => 'ID de usuário inválido'], 400);
        }

        $provider = GamesApi::where('game_id', $game_id)->value('provider_name');

        switch ($action) {
            case 'balance':
                return $this->getUserBalance($userId);

            case 'credit':
                return $this->creditUserBalance($userId, $amount, $game_id, $provider);

            case 'debit':
                return $this->debitUserBalance($userId, $amount, $game_id, $provider);

            default:
                \Log::warning("Ação desconhecida recebida: " . $action);
                return response()->json(['error' => 'Ação inválida'], 400);
        }
    }
}
