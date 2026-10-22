<?php

use Illuminate\Support\Facades\DB;

test('utiliza o banco MySQL exclusivo para testes', function () {
    expect(config('database.default'))->toBe('mysql');

    expect(config('database.connections.mysql.database'))
        ->toBe('ponto_gestor_test');

    $database = DB::selectOne('SELECT DATABASE() AS database_name');

    expect($database->database_name)->toBe('ponto_gestor_test');
});
