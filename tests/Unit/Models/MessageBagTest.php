<?php

use Vluzrmos\Precodahora\Models\MessageBag;

covers(MessageBag::class);

it('should instantiate a new MessageBag', function () {
    $messageBag = new MessageBag();

    expect($messageBag)->toBeInstanceOf(MessageBag::class);
});

it('should add a message to the bag', function () {
    $messageBag = new MessageBag();

    $messageBag->add('name', 'The name is required');

    expect($messageBag->has('name'))->toBeTrue();
    expect($messageBag->get('name'))->toBe(['The name is required']);
    expect($messageBag->all())->toBe(['The name is required']);
    expect($messageBag->first('name'))->toBe('The name is required');
    expect($messageBag->messages())->toBe(['name' => ['The name is required']]);
    expect($messageBag->isEmpty())->toBeFalse();

    $messageBag->clear();

    expect($messageBag->isEmpty())->toBeTrue();
    expect($messageBag->get('name'))->toBe(null);
    expect($messageBag->first('name'))->toBeNull();
});

it('can add messages from array', function () {
    $messageBag = new MessageBag([
        'name' => 'The name is required',
        'email' => 'The email is required',
    ]);

    $messageBag->fromArray(['document' => 'The document is required']);

    expect($messageBag->has('name'))->toBeTrue();
    expect($messageBag->has('email'))->toBeTrue();
    expect($messageBag->get('name'))->toBe(['The name is required']);
    expect($messageBag->get('email'))->toBe(['The email is required']);
    expect($messageBag->get('document'))->toBe(['The document is required']);
    expect($messageBag->first('document'))->toBe('The document is required');
    expect($messageBag->all())->toBe([
        'The name is required',
        'The email is required',
        'The document is required',
    ]);

    expect($messageBag->first('name'))->toBe('The name is required');
    expect($messageBag->first('email'))->toBe('The email is required');
    expect($messageBag->messages())->toBe([
        'name' => ['The name is required'],
        'email' => ['The email is required'],
        'document' => ['The document is required'],
    ]);
    expect($messageBag->isEmpty())->toBeFalse();

    $messageBag->clear();

    expect($messageBag->isEmpty())->toBeTrue();
    expect($messageBag->get('name'))->toBeNull();
    expect($messageBag->first('name'))->toBeNull();
    expect($messageBag->get('email'))->toBeNull();
    expect($messageBag->first('email'))->toBeNull();
    expect($messageBag->get('document'))->toBeNull();
    expect($messageBag->first('document'))->toBeNull();
});
