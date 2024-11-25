<?php

use Vluzrmos\Precodahora\RandomUserAgent;

it('can generate a random user agent', function () {
    $userAgent = RandomUserAgent::random();
    expect($userAgent)->toBeString();
    expect($userAgent)->not->toBeEmpty();
});

it('can get especific user agent', function () {
    $userAgent = RandomUserAgent::get('chrome-windows');
    expect($userAgent)->toBeString();
    expect($userAgent)->not->toBeEmpty();
});

it('can find index of especific user agent', function () {
    $agentIndex = 'chrome-windows';

    $userAgent = RandomUserAgent::get($agentIndex);
    $userAgentIndex = RandomUserAgent::findIndex($userAgent);

    expect($userAgentIndex)->toBe($agentIndex);

    expect(RandomUserAgent::get('not-found'))->toBeFalse();
    expect(RandomUserAgent::findIndex('Mozilla/5.0 (X11; Linux x86_64) Debian/11 AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.2849.68'))->toBeFalse();
});