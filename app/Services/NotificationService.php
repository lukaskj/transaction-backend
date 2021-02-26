<?php

namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    private const URL = "https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04";

    /**
     * Notify user with message
     *
     * @param User $user User to be notified
     * @param string $message Message to be sent
     */
    public static function notify(User $user, string $message): bool
    {
        if (config('app.env') === 'testing') {
            return true;
        }

        try {
            $response = Http::post(self::URL);
            $json = $response->json();
            if (!isset($json["message"]) || strtolower($json["message"]) !== "enviado") {
                throw new ReportableException("Transaction not authorized.", null, 500);
            }
            return true;
        } catch (\Throwable $th) {
            report($th);
            throw $th;
        }
    }
}
