<?php

declare(strict_types=1);

use App\Infrastructure\Abstracts\DataTransferObject;

test('filled() will return only properties which are populated and not null or empty', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(readonly string $filled = 'TEST', readonly string $nonFilled = '')
        {
        }
    };

    expect($class->filled())->toMatchArray(['filled' => 'TEST']);
});

test('only() will return only the selected keys', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(readonly string $filled = 'TEST', readonly string $nonFilled = 'TEST 2')
        {
        }
    };

    expect($class->only('filled'))->toMatchArray(['filled' => 'TEST']);
});

test('when property on the defined class is static it will not be honored', function () {
    $class = new class() extends DataTransferObject {
        public static bool $staticProp = false;

        public function __construct(readonly string $filled = 'TEST', readonly string $nonFilled = 'TEST 2')
        {
        }
    };

    expect($class->all())->toMatchArray(['filled' => 'TEST', 'nonFilled' => 'TEST 2']);
});

test('toJson() will return json string', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(readonly string $filled = 'TEST')
        {
        }
    };

    expect($class->toJson())->toBeJson();
});

test('toSnakeCase() will return object properties in snake_case format', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(readonly string $filledProperty = 'TEST')
        {
        }
    };

    expect($class->toSnakeCase()->toArray())->toHaveKey('filled_property');
});

test('when calling toSnakeCase()->only()/except() will return only the selected or except keys in snake_case format ', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(public readonly string $filledProperty = 'TEST', public readonly string $filledPropertyTwo = 'TEST')
        {
        }
    };

    expect($class->only('filledProperty')->toSnakeCase())->toHaveKey('filled_property');
    expect($class->except('filledProperty')->toSnakeCase())->toHaveKey('filled_property_two');
});

test('calling except() or only() with snake_case prop argument before toSnakeCase() will return array with keys except or only the selected one in snake_case format', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(public readonly string $filledProperty = 'TEST', public readonly string $filledPropertyTwo = 'TEST')
        {
        }
    };

    expect($class->only('filled_property')->toSnakeCase())->toMatchArray(['filled_property' => 'TEST']);
    expect($class->only('filledProperty')->toSnakeCase())->toMatchArray(['filled_property' => 'TEST']);

    expect($class->except('filled_property')->toSnakeCase())->toMatchArray(['filled_property_two' => 'TEST']);
    expect($class->except('filledProperty')->toSnakeCase())->toMatchArray(['filled_property_two' => 'TEST']);
});

test('parseArray() can parse SELF properties', function () {
    $anotherDto = new class() extends DataTransferObject {
        public function __construct(public readonly string $name = 'test')
        {
        }
    };

    $class = new class($anotherDto) extends DataTransferObject {
        public function __construct(public readonly DataTransferObject $anotherDto)
        {
        }
    };

    expect($class->toArray())->toMatchArray(['anotherDto' => ['name' => 'test']]);
});

test('parseArray() can parse array as properties', function () {
    $class = new class() extends DataTransferObject {
        public function __construct(public readonly array $data = ['name' => 'test'])
        {
        }
    };
    expect($class->toArray())->toMatchArray(['data' => ['name' => 'test']]);
});
