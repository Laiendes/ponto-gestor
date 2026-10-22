<?php

use function Pest\Laravel\get;

test('a página inicial redireciona para o login', function () {
    get('/')
        ->assertRedirect(route('login'));
});
