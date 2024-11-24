<?php

use Vluzrmos\Precodahora\Models\BaseModel;
use Vluzrmos\Precodahora\Models\HasAttributes;

it('should be instance of BaseModel', function () {
    $model = new BaseModel([
        'id' => 1,
        'name' => 'John Doe'
    ]);

    expect(BaseModel::class)->toUseTrait(HasAttributes::class);
    
    $this->assertInstanceOf(BaseModel::class, $model);
    $this->assertEquals(1, $model->getKey());
    $this->assertEquals('id', $model->getKeyName());
    $this->assertTrue($model->is(new BaseModel(['id' => 1, 'name' => 'Jane Doe'])));

    $this->assertEquals('John Doe', $model->getAttribute('name'));
    $this->assertEquals('John Doe', $model->name);
    $this->assertEquals('John Doe', $model['name']);

    $this->assertEquals(['id' => 1, 'name' => 'John Doe'], $model->toArray());
    $this->assertEquals(['id' => 1, 'name' => 'John Doe'], $model->jsonSerialize());
    
    $model->fill(['name' => 'Jane Doe 2']);
    $this->assertEquals('Jane Doe 2', $model->getAttribute('name'));
    $this->assertEquals('Jane Doe 2', $model->name);
    $model->setAttribute('name', 'Jane Doe 3');
    $this->assertEquals('Jane Doe 3', $model->getAttribute('name'));
    $this->assertEquals('Jane Doe 3', $model->name);
    $model->name = 'Jane Doe 4';
    $this->assertEquals('Jane Doe 4', $model->getAttribute('name'));
    $this->assertEquals('Jane Doe 4', $model->name);

    $this->assertTrue(isset($model->name));
    unset($model->name);
    $this->assertFalse(isset($model->name));

    $this->assertTrue(isset($model['id']));

    $model->name = 'Jane Doe 4';

    $this->assertTrue($model->offsetExists('name'));
    $this->assertEquals('Jane Doe 4', $model->offsetGet('name'));
    $model->offsetSet('name', 'Jane Doe 5');
    $this->assertEquals('Jane Doe 5', $model->offsetGet('name'));
    $model->offsetUnset('name');
    $this->assertNull($model->offsetGet('name'));
    
});