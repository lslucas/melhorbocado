<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ Config::get('app.name') }}, dados de acesso <a href='{{ \Config::get('app.url') }}/admin'>Painel Administrativo</a></h2>

        <div>
            <a href='{{ \Config::get('app.url') }}/admin'>URL de acesso</a>
            <br/><b>Login: </b> {{ $email }}
            <br/><b>Senha: </b> {{ $password }}
        </div>
    </body>
</html>