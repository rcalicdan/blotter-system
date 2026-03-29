<?php

declare(strict_types=1);

namespace App\Services;

class RedirectNotification
{
    /**
     * Flash a notification message to the session
     */
    public static function flash(string $message, string $type = 'success'): void
    {
        session()->flash('notify', [
            'message' => $message,
            'type' => $type,
        ]);
    }

    /**
     * Flash a success notification
     */
    public static function success(string $message): void
    {
        self::flash($message, 'success');
    }

    /**
     * Flash an error notification
     */
    public static function error(string $message): void
    {
        self::flash($message, 'error');
    }

    /**
     * Flash a warning notification
     */
    public static function warning(string $message): void
    {
        self::flash($message, 'warning');
    }

    /**
     * Flash an info notification
     */
    public static function info(string $message): void
    {
        self::flash($message, 'info');
    }
}
