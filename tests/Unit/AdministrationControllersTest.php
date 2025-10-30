<?php

use App\Http\Controllers\Controller as BaseController;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;

uses(Tests\TestCase::class);

function administrationControllerClasses(): array
{
    static $classes = null;

    if ($classes !== null) {
        return $classes;
    }

    $directory = app_path('Http/Controllers/API/Administration');
    $iterator  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
    $classes   = [];

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $fullPath     = $file->getRealPath();
        $relativePath = substr($fullPath, strlen(app_path()) + 1);
        $relativePath = str_replace(['/', '\\'], '\\', $relativePath);
        $class        = 'App\\' . str_replace('.php', '', $relativePath);

        $classes[] = $class;
    }

    sort($classes);

    return $classes;
}

dataset('administration_controllers', fn () => array_map(fn ($class) => [$class], administrationControllerClasses()));

it('ensures administration controller class exists', function (string $class) {
    expect(class_exists($class))->toBeTrue();
})->with('administration_controllers');

it('ensures administration controllers extend the base controller', function (string $class) {
    expect(is_subclass_of($class, BaseController::class))->toBeTrue();
})->with('administration_controllers');

it('ensures administration controllers declare at least one public method', function (string $class) {
    $reflection = new ReflectionClass($class);
    $methods    = array_filter(
        $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        fn (ReflectionMethod $method) => $method->getDeclaringClass()->getName() === $class && ! $method->isConstructor()
    );

    expect(count($methods))->toBeGreaterThan(0);
})->with('administration_controllers');
