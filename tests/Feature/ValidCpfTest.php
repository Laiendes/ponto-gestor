<?php

use App\Rules\ValidCpf;
use Illuminate\Support\Facades\Validator;

test('aceita um CPF com dígitos verificadores válidos', function () {
    $validator = Validator::make(
        ['cpf' => '52998224725'],
        ['cpf' => ['required', new ValidCpf]]
    );

    expect($validator->passes())->toBeTrue();
});

test('rejeita CPF com dígitos verificadores incorretos', function () {
    $validator = Validator::make(
        ['cpf' => '52998224726'],
        ['cpf' => ['required', new ValidCpf]]
    );

    expect($validator->fails())->toBeTrue();
});

test('rejeita CPF formado por números repetidos', function () {
    $validator = Validator::make(
        ['cpf' => '11111111111'],
        ['cpf' => ['required', new ValidCpf]]
    );

    expect($validator->fails())->toBeTrue();
});

test('rejeita CPF com quantidade incorreta de dígitos', function () {
    $validator = Validator::make(
        ['cpf' => '5299822472'],
        ['cpf' => ['required', new ValidCpf]]
    );

    expect($validator->fails())->toBeTrue();
});

test('rejeita CPF contendo letras', function () {
    $validator = Validator::make(
        ['cpf' => '5299822472A'],
        ['cpf' => ['required', new ValidCpf]]
    );

    expect($validator->fails())->toBeTrue();
});
