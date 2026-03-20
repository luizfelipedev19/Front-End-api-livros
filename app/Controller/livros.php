//responsavel por chamar todos os recursos que eu preciso usar de Livro da minha api-livros (back-end)

//uma função para chamar os recursos


//criar uma coluna na tabela de usuarios UUID Varchar(30) // quando eu criar o usuario, ele tem que criar esse id (chave) que nao se altere. Vai registrar essa chave
//na row do usuario 

toda vez que eu for chamar a api, eu passo o UUID - criar como alfa numero (letras e numeros) e unique



usar o curl na função para chamar o endpoint da api

$curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);


 //tarefa minha api tem que ser modificada para ser usada no postman, modificar para o UUID. Tem que funcionar no postam fazendo consulta via uuid do usuario logado

        SELECT * FROM livros WHERE id_user = (SELECT id FROM users WHERE uuid = :uuid)
    toda vez que foi inserir ou consultar, tenho que converter o id user para uuid para transitar tem que ser o uuid
        passo o uuid na consulta da api

        mudar a logica da api, a autenticação dela deve ser acionada quando for ter o uso do endpoint. Vai inseri? tem que autenticar


