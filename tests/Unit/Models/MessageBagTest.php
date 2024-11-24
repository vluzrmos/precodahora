<?php

use Vluzrmos\Precodahora\Models\MessageBag;

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

