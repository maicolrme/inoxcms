<?php

test('hook system works', function () {
    \App\Core\HookSystem\Hook::filter('test.filter', fn ($v) => $v . ' filtered');
    \App\Core\HookSystem\Hook::action('test.action', fn () => true);

    $result = \App\Core\HookSystem\Hook::apply('test.filter', 'value');
    expect($result)->toBe('value filtered');
});
