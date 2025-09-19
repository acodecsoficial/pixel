<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GameToken;
use App\Models\GameProvider;
use Illuminate\Http\Request;
use App\Traits\AffiliateRewards;

class TltController extends Controller
{
    use AffiliateRewards;

    public function authenticate(Request $request)
    {
        try {
            $game_token = GameToken::where('token', $request->data['token'])->first();
            if(!$game_token){
                return response()->json([
                    "error_code" => "OP_21",
                    "error_message" => "Invalid token",
                ]);
            }

            $user = User::find($game_token->user_id);
            if (!$user) {
                return response()->json([
                    "error_code" => "OP_22",
                    "error_message" => "Authorization failed",
                ]);
            }

            $data = [
                "user_id" => $user->id,
                "user_name" => $user->username,
                "user_country" => "BRA",
                "currency_code" => "BRL",
                "balance" => $user->wallet,
            ];

            return $data;

        } catch (\Exception $e) {
            return response()->json([
                "error_code" => "OP_50",
                "error_message" => "Internal error",
            ],200);
        }
    }

    public function balance(Request $request)
    {
        try {
            $game_token = GameToken::where("token", $request->data["token"])->first();
            if (!$game_token) {
                return response()->json([
                    "error_code" => "OP_21",
                    "error_message" => "Invalid token",
                ]);
            }

            $user = User::find($game_token->user_id);
            if (!$user) {
                return response()->json([
                    "error_code" => "OP_22",
                    "error_message" => "Authorization failed",
                ]);
            }

            $data = [
                "currency_code" => "BRL",
                "balance" => $user->wallet,
            ];

            return $data;

        } catch (\Exception $e) {
            return response()->json([
                "error_code" => "OP_50",
                "error_message" => "Internal error",
            ],200);
        }
    }

    public function changebalance(Request $request)
    {
        try {
            $data = $request->data;

            $game_token = GameToken::where("token", $request->data["token"])->first();
            if (!$game_token) {
                return response()->json([
                    "error_code" => "OP_21",
                    "error_message" => "Invalid token",
                ]);
            }

            $user = User::find($game_token->user_id);
            if (!$user) {
                return response()->json([
                    "error_code" => "OP_22",
                    "error_message" => "Authorization failed",
                ]);
            }

            TltTransacationsProd::create($data);

            $amountValue = $data['amount'];
            $amountRev = $amountValue;

            if ($data["transaction_type"] === "BET" && $user->wallet < $amountValue) {
                return response()->json([
                    "error_code" => "OP_31",
                    "error_message" => "Insufficient funds",
                ],200);
            }

            $game = GamesApi::where('slug','like', "%" . $data["game_id"])->first();
            if ($game) {
                $gameProvider = $game->provider_name;
            } else {
                $gameProvider = 'Evolution';
            }

            if ($data['transaction_type'] == "WIN"){
                $user->increment('wallet', $data['amount']);

                $amountRev *= -1;
                $this->generateGGR("win", $data["amount"], $user->id, $data["game_id"], $gameProvider, $data["transaction_id"]);
                $this->revAfiliado($user, $amountRev);
            }

            if ($data['transaction_type'] == "BET"){
                $user->decrement('wallet', $data['amount']);

                $this->generateGGR("bet", $data["amount"], $user->id, $data["game_id"], $gameProvider, $data["transaction_id"]);
                $this->revAfiliado($user, $amountRev);
            }

            if ($data['transaction_type'] == "REFUND"){
                $user->increment('wallet', $data['amount']);

                $amountRev *= -1;
                $this->revAfiliado($user, $amountRev);
            }

            $data_response = [
                "currency_code" => "BRL",
                "balance" => $user->wallet,
            ];

            return $data_response;

        } catch (\Exception $e) {
            return response()->json([
                "error_code" => "OP_50",
                "error_message" => "Internal error",
            ],200);
        }
    }

    public function status(Request $request)
    {
        try {
            $data = $request->data;
            $transaction = TltTransacationsProd::where('user_id', $data['user_id'])
                ->where('transaction_id', $data['transaction_id'])
                ->first();

            if (!$transaction) {
                return response()->json([
                    "error_code" => "OP_41",
                    "error_message" => "Transaction not found",
                ]);
            }

            $data_response =  [
                "transaction_id" => $data['transaction_id'],
                "user_id" => $data['user_id'],
                "transaction_status" => isset($transaction->transaction_status) && !empty($transaction->transaction_status) ? $transaction->transaction_status :  "OK"
            ];

        return $data_response;

        } catch (\Exception $e) {
            return response()->json([
                "error_code" => "OP_50",
                "error_message" => "Internal error",
            ],200);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $data = $request->data;
            $transaction = TltTransacationsProd::where('user_id', $data['user_id'])
                ->where('transaction_id', $data['transaction_id'])
                ->first();

            if (!$transaction) {
                return response()->json([
                    "error_code" => "OP_41",
                    "error_message" => "Transaction not found",
                ]);
            }

            $user = User::find($data['user_id']);

            if ($transaction->transaction_status !== "CANCELED") {
                switch ($transaction->transaction_type) {
                    case 'BET':
                        $user->increment('wallet', $transaction->amount);
                        break;
                    case 'WIN':
                        $user->decrement('wallet', $transaction->amount);
                        break;
                    default:
                        break;
                }
            }

            TltTransacationsProd::where('user_id', $data['user_id'])
                ->where('transaction_id', $data['transaction_id'], )
                ->update(["round_finished" => $data["round_finished"], "transaction_status" => "CANCELED"]);


            $data_response =  [
                "transaction_id" => $data['transaction_id'],
                "user_id" => $data['user_id'],
                "transaction_status" => "CANCELED",
            ];

            return $data_response;

        } catch (\Exception $e) {
            return response()->json([
                "error_code" => "OP_50",
                "error_message" => "Internal error",
            ],200);
        }
    }

    private function generateGGR($action, $amount, $user_code,$game_code, $provider_code, $txn_id)
    {
        GameHistory::firstOrCreate([
            'user_id' => $user_code,
            'amount' => $amount,
            'provider' => $provider_code,
            'provider_tx_id' => $txn_id,
            'game' => $game_code,
            'action' => $action,
            'round_id' => $txn_id,
            'session_token' => $txn_id,
        ]);
    }
}
