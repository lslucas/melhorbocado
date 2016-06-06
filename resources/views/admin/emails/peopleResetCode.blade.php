<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ Config::get('app.name') }}</h2>
        <p>Siga as intruções abaixo para realizar seu primeiro acesso</p>

        <p>
            1. Acesse a url <a href='{{ \Config::get('app.url') }}/primeiro-acesso/{{ $resetCode }}'>{{ \Config::get('app.url') }}/primeiro-acesso/{{ $resetCode }}</a><br/>
            2. Informe seu email {{ $email }}<br/>
            2. Cole o seguinte codigo no campo Código: {{ $resetCode }}<br/>
            3. Informe sua nova senha juntamente da confirmação de senha
        </p>
    </body>
</html>