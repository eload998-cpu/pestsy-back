<?php

use App\Mail\NotifyErrorMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;

it('returns a real dollar price', function () {
    $res = $this->postJson('/api/resources/get-dollar-price');

    if ($res->status() !== 200) {
        Mail::mailer('smtp')->to('felcast999@gmail.com')->send(new NotifyErrorMail('GetDolarPriceTest'));
        test()->fail("API failed with status {$res->status()}");
    }

    try {
        $res->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('price'));

        expect($res->json('price'))->toBeNumeric()->toBeGreaterThan(0.0);

    } catch (\Throwable $e) {
        Mail::mailer('smtp')->to('felcast999@gmail.com')->send(new NotifyErrorMail('GetDolarPriceTest'));
        throw $e;
    }
});
