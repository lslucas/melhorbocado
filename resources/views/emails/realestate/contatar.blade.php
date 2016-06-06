<p>{{ $data['input']['nome'] }} tem interesse no imóvel {{ $data['imovel']['titulo'] }}</p>

<p>
    Contato:
    <br/>Email: {{ $data['input']['email'] }}
    <br/>Telefone: {{ $data['input']['telefone'] }}
    <br/>Mensagem: {{ nl2br($data['input']['mensagem']) }}
</p>

<p>
    Dados do Imóvel:
    <br/>Ação: {{ $data['imovel']['acao'] }}
    <br/>Imóvel: {{ $data['imovel']['imovel'] }}
    <br/>Tipo: {{ $data['imovel']['tipo'] }}
    <br/>Código Vivo no Rio: {{ $data['imovel']['codigo'] }}
    <br/>Código do Anúncio: {{ $data['imovel']['codigo_custom'] }}
    <br/>Link do Imóvel: http://vivonorio.com.br/imovel/{{ $data['imovel']['id'] }}/{{ str_slug($data['imovel']['titulo'], '-') }}
</p>
