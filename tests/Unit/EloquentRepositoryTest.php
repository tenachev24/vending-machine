<?php

use App\Infrastructure\Abstracts\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

uses(TestCase::class);


test('update will return instance of model', function () {
    $stub = new class () extends Model {
    };

    $model = mock($stub)
        ->shouldReceive('update')
        ->with([])
        ->once()
        ->andReturnSelf();

    $repo = new class ($model->getMock()) extends EloquentBaseRepository {
    };

    $result = $repo->update($model->getMock(), []);

    expect($result)->toBeInstanceOf($stub::class);
});

test('columns() will set the desired columns and return self', function () {
    $stub = new class () extends Model {
    };
    $repo = new class ($stub) extends EloquentBaseRepository {
    };

    $result = $repo->columns(['id']);

    expect($result)->toBeInstanceOf($repo::class)
        ->and(invade($repo)->columns)
        ->toMatchArray(['id']);
});

test('withRelations will set the desired relationships and return self', function () {
    $stub = new class () extends Model {
    };
    $repo = new class ($stub) extends EloquentBaseRepository {
    };

    $result = $repo->withRelations(['users']);

    expect($result)->toBeInstanceOf($repo::class)
        ->and(invade($repo)->with)
        ->toMatchArray(['users']);
});

test('withoutGlobalScopes will set withoutGlobalScopes to true and return self', function () {
    $stub = new class () extends Model {
    };
    $repo = new class ($stub) extends EloquentBaseRepository {
    };

    $result = $repo->withoutGlobalScopes();

    expect($result)->toBeInstanceOf($repo::class)
        ->and(invade($repo)->withoutGlobalScopes)
        ->toBeTrue();
});
