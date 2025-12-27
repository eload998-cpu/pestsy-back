<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class FirebaseService
{
    private Messaging $messaging;

    public function __construct()
    {
        $factory         = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $this->messaging = $factory->createMessaging();
    }

    public function subscribeToTopic(string $topic, array $tokens): void
    {
        if (empty($tokens)) {
            return;
        }

        $this->messaging->subscribeToTopic($topic, $tokens);
    }

    public function unsubscribeFromTopic(string $topic, array $tokens): void
    {
        if (empty($tokens)) {
            return;
        }

        $this->messaging->unsubscribeFromTopic($topic, $tokens);
    }
}
