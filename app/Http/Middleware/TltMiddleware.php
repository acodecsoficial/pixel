<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Models\Config;
use Illuminate\Http\Request;

class TltMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $config = Config::first();
        $data = json_decode($request->getContent());
        $status = "OK";
        $data_response = null;

        if ($this->generateHash($data->command, $data->request_timestamp, $config->ttl_secret_key) !== $data->hash) {
            $status = "ERROR";
            $data_response = [
                "error_code" => "OP_20",
                "error_message" => "Invalid hash",
            ];
        }

        $response = $next($request);

        if ($response->getContent()) {
            $data_response = json_decode($response->getContent());
        }

        Log::info('Response Body TTL:', ['body' => $response->getContent()]);

        if (isset($data_response->error_code)) {
            $status = "ERROR";
        }

        $response_timestamp = now()->format('Y-m-d H:i:s');
        $response_hash = $this->generateHash($status, $response_timestamp, $config->ttl_secret_key);

        $response_json = [
            "status" => $status,
            "response_timestamp" => $response_timestamp,
            "hash" => $response_hash,
            "data" => $data_response,
        ];

        return response()->json([
            "request" => $data,
            "response" => $response_json,
        ], 200);
    }

    private function generateHash($command, $requestTimestamp, $secretKey)
    {
        return sha1($command . $requestTimestamp . $secretKey);
    }
}
