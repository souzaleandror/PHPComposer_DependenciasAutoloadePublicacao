#### 01/02/2024

Curso de PHP Composer: Dependências, Autoload e Publicação

```
composer require guzzlehttp/guzzle
composer require symfony/dom-crawler
composer require symfony/css-selector
composer install
composer update
```

@01-Instalando o Composer 

@@01
Introdução

Olá pessoal, sejam muito bem vindos(as) ao curso de Composer da Alura! Meu nome é Vinicius Dias e serei seu instrutor nesse treinamento, no qual construiremos um pacote para ser disponibilizado para instalação no Composer. Primeiro, entenderemos o que essa é ferramenta chamada Composer, que significa construtor e cujo logo é um Maestro. Antes de prosseguirmos, repare que, na página do Composer, toda vez atualizamos o site as cores desse maestro mudam. Legal, não?
Adiante, conversaremos sobre esse gerenciador de dependências e sobre as possibilidades que ele fornece, aprenderemos sobre o autoload e os scripts que podem ser executados ou automatizados com isso, falaremos um pouco sobre pipeline de produção para deploy, adicionando uma série de scrips a serem executados, além de fazermos testes com o PHPUnit e analisarmos o nosso código com o PHP CodeSniffer. Ou seja, temos bastante coisa para trabalhar!

Ao final, teremos finalizado um componente que ficará disponível para ser baixado por outro projeto e ainda criaremos um novo projeto no qual utilizaremos esse pacote criado. No caso, o pacote desenvolvido por nós buscará todos os cursos da Alura a partir de uma URL, como um spider do HTML.

Porém, temos um pedido! Por favor não execute esse código indiscriminadamente. Isso evitará que o curso saia do ar por excesso de requisições na Alura. Ou seja, utilize-o com parcimônia!

Esperamos que você tire proveito desse treinamento e, caso tenha qualquer dúvida, não hesite em abrir um tópico no nosso fórum. Bons estudos!

@@02
Instalando o Composer

Vamos configurar o nosso ambiente, instalando tudo que é necessário para começarmos a desenvolver. Primeiro, obviamente, precisamos ter instalado o PHP, algo que você provavelmente já aprendeu (ou já fez) nos treinamentos anteriores.
Tendo instalado o PHP, acessaremos o site do Composer e buscaremos pela página de downloads. Existem duas formas de instalar esse gerenciador. A primeira é por meio da linha de comando, algo que geralmente é feito no Mac e no Linux. Para isso, basta copiar e colar o código disponibilizado na página:

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"COPIAR CÓDIGO
Quando rodamos esse comando, geramos um arquivo composer.phar no diretório onde ele foi executado. Para acessar o comando composer diretamente pelo terminal, será necessário mover esse arquivo (mv composer.phar) para uma pasta que esteja no PATH, ou seja, na variável de ambiente onde o Bash procura os arquivos, por exemplo "/usr/bin/". Depois disso, comandos como composer --version, que retornam a versão instalada do Composer, poderão ser executados corretamente.

Em computadores com Windows, basta baixarmos e executarmos o instalador Composer-Set.exe disponibilizado na página, e então seguir as instruções de instalação.

Com isso, temos o necessário para começarmos a trabalhar com o Composer. Durante o treinamento, utilizaremos uma IDE chamada PhpStorm, que pode ser baixada no site da JetBrains. Ela se integra muito bem o Composer e disponibiliza diversas ferramentas interessantes. Se você preferir utilizar outra IDE, como Eclipse ou Netbeans, não tem problema. Ao longo do curso, utilizaremos o terminal (a linha de comando) para executarmos os códigos, dispensando as features que o PhpStorm nos traz com o Composer.

Para darmos o pontapé inicial, criaremos um projeto PHP chamado buscador-cursos-alura. No próximo vídeo, começaremos a conversar efetivamente sobre o Composer.


@@03
Composer

O Composer, de forma bastante resumida, é um gerenciador de dependências. Porém, diferente do Yum e do Apt, que são gerenciadores de pacotes/dependências do Linux e por meio dos quais podemos instalar programas e bibliotecas no sistema operacional, o Composer, por padrão, instala as dependências específicas necessárias para um projeto.
Sendo assim, podemos ter um projeto X que utiliza Symfony, Doctrine e Monolog, e um projeto Y que utiliza outras dependências, como Laravel, Eloquent e Redis. Se por algum motivo precisarmos de uma dependência do global, o Composer também nos dá suporte a isso, por meio do comando global, mas essa não é a forma que ele trabalha por padrão.

Mas como informamos ao Composer que ele deverá gerenciar as dependências de um projeto? Existem duas formas de fazermos isso. A primeira delas é criando, no próprio projeto, um arquivo composer.json, estruturando-o em seguida. Porém, existe também uma forma mais automatizada de fazermos isso.

No PhpStorm, podemos acessar "Tools > Composer -> Init Composer...", ou podemos, no terminal, navegar até o diretório do projeto e executar o comando composer init. Após isso, receberemos uma mensagem de boas vindas ao gerador de configurações do Composer, que nos pedirá o nome de um pacote. Os nomes dos pacotes do Composer normalmente são dados da forma <vendor>/<name>, onde vendor é quem está distribuindo esse pacote (como um nickname, um nome de usuário do github ou o nome da empresa) e name é o nome do pacote que está sendo criado.

No nosso caso, estamos criando um pacote buscador de cursos, poderíamos nomeá-lo como alura/buscador-cursos. No seu caso, você pode utilizar o seu nome no github, como no exemplo do nosso instrutor: cviniciussdias/buscador-cursos. Dessa forma, mesmo que várias pessoas criem pacotes com nomes parecidos, eles serão diferentes, já que a distribuição será feita com nomes diferentes.

Após atribuirmos um nome, o gerador de configurações do Composer nos pedirá uma descrição do pacote. Utilizaremos "Projeto que busca os cursos no site da Alura". Em seguida, seremos perguntados sobre quem é o autor do pacote. Se você tiver executado um Git Init na pasta do projeto, identificando-a como um repositório do Git, o autor será reconhecido automaticamente, mas não há necessidade de fazer isso. Para prosseguir, basta digitar um nome e um e-mail no formato Vinicius Dias <carlosv775@gmail.com> ou simplesmente pressionar "Enter" para pular.

A próxima configuração, "Minimum Stability", não será preenchida agora. Aqui basicamente informarmos se queremos buscar pacotes que ainda estejam em fase de testes/desenvolvimento, ou somente pacotes que estejam instáveis.

Depois disso, em "Package Type", somos perguntados sobre qual o tipo de pacote que estamos criando. Normalmente, quando criamos um projeto completo, que basta baixar para ter uma aplicação inteira rodando, chamamos de project. No nosso caso, teremos uma biblioteca que somente busca os cursos, mas não os exibe em HTML ou salva em um banco de dados. Esse tipo de pacote é chamado de library.

Um metapackage é um projeto vazio contendo somente as informações das dependências que ele precisa, ou seja, um esqueleto para montar outros projetos, composer-plugin é uma forma de criarmos plugins para o Composer. Nesse treinamento, nosso foco será criarmos uma simples biblioteca (library) que busca os cursos da Alura.

A opção Licence, ou "licença", pode ser especificada de acordo com sua preferência, e pode ser MIT, Apache, GPL, entre outras, ou simplesmente deixada vazia. Continuando, seremos perguntados se queremos definir as nossas depenências de forma interativa. Por enquanto não faremos isso, mas se já soubéssemos as dependências necessárias para o projeto, como Doctrine (doctrine/orm) ou Monolog (monolog/monolog), poderíamos passá-las agora.

Também seremos perguntados se queremos definir as dependências de desenvolvimento, ou seja, um pacote de testes para verificarmos o nosso código. Como não usaremos isso agora, responderemos que não.

Feito isso, será gerado um arquivo JSON contendo nome, descrição, tipo e autores do projeto, além das dependências (que por enquanto estão vazias).

{
    "name": "cviniciussdias/buscador-cursos",
    "description": "Projeto que busca os cursos no site da Alura",
    "type": "library",
    "authors": [
        {
            "name": "nefo",
            "email": "rod.nef@gmail.com"
        }
    ],
    "require": {}
}COPIAR CÓDIGO
Por fim, confirmaremos a geração desse arquivo com yes. De volta ao nosso projeto, encontraremos o arquivo que foi gerado com os dados que informamos na linha de comando. Conhecendo essa estrutura, nem precisaríamos rodar o comando composer init, bastando preenchermos todos esses dados.

Além disso, nenhuma dessas informações é obrigatória, e tudo que precisamos é de um {}. Porém, se formos disponibilizar esse pacote para alguém baixar, precisaremos de um nome e de uma descrição. Se não informarmos um tipo, ele assumirá, por padrão, que estamos trabalhando com uma biblioteca (library). Apesar do preenchimento dos autores também ser opcional, é interessante que quem esteja utilizando saiba quem criou o pacote e como entrar em contato em caso de dúvidas. Já "require" é onde colocaremos as dependências do projeto que criaremos.

Agora que temos o esqueleto do nosso composer.json, podemos começar a trabalhar. Até o próximo capítulo!

@@04
Vendor name

Neste capítulo, além de entendermos o que é o Composer e como instalá-lo, vimos que existem algumas convenções para dar um nome a pacotes que criamos para distribuir. Uma das convenções é que o nome do pacote precisa começar com o Vendor Name
O que é o Vendor Name no nome do pacote distribuído com Composer?

É o nome da pessoa ou companhia que distribui o pacote
 
Alternativa correta! Claro que não precisa ser o nome real. Pode ser o famoso nickname. Meu vendor name é cviniciussdias, por exemplo. O vendor name permite que 2 distribuidores de pacote tenham pacotes com nomes iguais. Ex.: cviniciussdias/json e anamaria/json. Para mais detalhes: https://packagist.org/about#naming-your-package
Alternativa correta
É o nome do pacote em si
 
Alternativa correta
É o nome de um pacote que será vendido no futuro

https://packagist.org/about#naming-your-package

@@05
Consolidando o seu conhecimento

Chegou a hora de você seguir todos os passos realizados por mim durante esta aula. Caso já tenha feito, excelente. Se ainda não, é importante que você execute o que foi visto nos vídeos para poder continuar com a próxima aula.
Pré-requisito: Para executar a instalação você precisa ter o PHP instalado.

Como IDE usamos o PHP Storm mas fique a vontade de usar um outro editor de código como Atom, VSCode ou Sublime.

Instalação do Composer
Instalação no Windows
Acesse o site https://getcomposer.org e vá para a página do Download, baixe o Instalador (Composer-Setup.exe) e execute o instalador.

Uma vez instalado teste na linha de comando:

composer --versionCOPIAR CÓDIGO
Instalação no Mac/Linux
Acesse o site https://getcomposer.org e vai para a página do Download. Copie o script de instalação e cole no seu terminal para instalar o composer.

Depois de executar o script, mova o arquivo composer.phar (sem a extensão) para uma pasta disponível em seu PATH (/usr/local/bin, por exemplo). Resultado: /usr/local/bin/composer

Primeiro projeto
1) No seu editor de código favorito crie um novo projeto buscador-cursos-alura.

2) Na linha de comando entre na pasta do projeto buscador-cursos-alura e digite:

composer init COPIAR CÓDIGO
Use as seguinte informações:

Package Name: seu-nickname/buscador-cursos
Description: Projeto que busca cursos no site da alura
Author: Seu Nome
Minimum Stability: deixa em branco
Package Type: library
License: deixa em branco
E nas duas perguntas sobre as dependências digite no. Por fim, confirma a geração do arquivo composer.json:

{
    "name": "cviniciussdias/buscador-cursos",
    "description": "Projeto que busca os cursos no site da Alura",
    "type": "library",
    "authors": [
        {
            "name": "Vinicius Dias",
            "email": "carlosv775@gmail.com"
        }
    ],
    "require": {}
}COPIAR CÓDIGO
Pronto, na próxima aula vamos ver como buscar e instalar dependências!

https://getcomposer.org/

https://getcomposer.org/

Continue com os seus estudos, e se houver dúvidas, não hesite em recorrer ao nosso fórum!

@@06
O que aprendemos?

Nesta aula, aprendemos:
Composer é um gerenciador de dependências PHP.
Ele guarda as dependências dentro do projeto.
Se quisermos uma dependência global, devemos usar o global command.
Um pacote sempre segue a nomenclatura: <vendor>/<name>.
As meta-informações de uma dependência ficam salvas no arquivo composer.json.
O comando composer init inicializa o composer.json.

#### 02/02/2024

@02-Gerenciando dependências

@@01
Projeto da aula anterior

Caso queira, você pode baixar aqui o projeto do curso no ponto em que paramos na aula anterior.

https://caelum-online-public.s3.amazonaws.com/1255-composer/02/1250-composer-aula1.zip

@@02
Buscando pacotes

Vamos começar a trabalhar no nosso projeto. A ideia é acessarmos o site da Alura, fazendo uma requisição do tipo GET, percorrer o HTML recebido e, a partir dele, pegar os dados que desejamos. Para isso, precisaremos de um pacote para fazermos uma requisição HTTP, e outro para lermos a estrutura DOM do HTML, ou seja, um DomCrawler. Mas como buscaremos por esses pacotes?
O repositório principal do Composer é um site chamado http://packagist.org, que agrega os pacotes públicos instaláveis com ele. Porém, também é possível fazer com que ele busque pacotes em outros lugares, como do seu próprio GitHub, do repositório privado da sua empresa ou mesmo de arquivos zipados. Acessando o site, buscaremos por "http client" e "dom crawler", e pegaremos os primeiros resultados - respectivamente, https://packagist.org/packages/guzzlehttp/guzzle e https://packagist.org/packages/symfony/dom-crawler.

Repare que o "guzzlehttp/guzzle" apresenta uma breve documentação explicando a utilização do componente, enquanto o "symfony/dom-crawler" nos fornece um link para uma documentação mais completa. Agora que entendemos onde buscar os componentes, precisamos instalá-los para começarmos o desenvolvimento da nossa ferramenta, e faremos isso no próximo vídeo.

http://packagist.org/

https://packagist.org/packages/guzzlehttp/guzzle

https://packagist.org/packages/symfony/dom-crawler

@@03
Instalando Guzzle e DomCrawler

Antes de começarmos a gerenciar as nossas dependências, você pode ter ficado com uma dúvida: será que não conseguimos realizar requisições HTTP sem o Guzzle? Ou ler um HTML sem O DomCrawler? Na verdade isso é possível - claro, pois do contrário esses pacotes não teriam sido criados, afinal eles foram construídos com PHP puro.
A questão é: existem várias formas de criarmos uma requisição HTTP somente com o PHP, mas nenhuma delas é, de antemão, orientada a objetos e tem o tratamento de erros que precisamos, fazendo com que seja necessário trabalharmos nisso manualmente. Alguém já teve esse trabalho e criou um componente que só faz uma coisa, como criar requisições HTTP, e faz isso muito bem.

Esse é o mesmo conceito por trás da criação do Unix, onde cada programa ou comando faz somente uma coisa, mas faz isso muito rapidamente e de forma funcional. Ou seja, utilizamos os componentes para facilitar a execução de determinadas tarefas. Se quiséssemos, poderíamos utilizar o cURL ou a função file_get_contents() para realizarmos requisições HTTP, mas existem vários conceitos desse tipo de requisição que o Guzzle facilita e nos entrega de forma simples, e justamente por isso usaremos esse pacote.

Lembrando que o Composer instala as dependências por projeto, vamos instalar o Guzzle no nosso projeto atual. Na página do pacote, logo ao lado do ícone de download, encontramos um comando para ser executado.

No terminal, executaremos composer require guzzlehttp/guzzle. Feito isso, o Composer acessará o Packagist, confirmará que o pacote existe e o buscará do código fonte. Todos os dados do pacote serão então copiados ao nosso projeto dentro de uma pasta chamada "vendor", dentro da qual serão instaladas todas as dependências.

Se você reparar, além do "guzzlehttp", serão adicionadas as pastas "psr" e "ralouphie". Isso porque o Guzzle depende desses pacotes, e todas as suas dependências são instaladas automaticamente, descartando a necessidade de instalarmos uma a uma manualmente.

Na documentaçao do Guzzle, encontramos algumas instruções para sua utilização:

$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');

echo $response->getStatusCode(); # 200
echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'

# Send an asynchronous request.
$request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
$promise = $client->sendAsync($request)->then(function ($response) {
    echo 'I completed! ' . $response->getBody();
});

$promise->wait();COPIAR CÓDIGO
Analisando o código, vemos que é necessário criar um cliente, o que de prontidão nos habilita a fazer nossas requisições. Simples, não? Com o Guzzle, também podemos pegar o corpo da resposta, o cabeçalho, o status code, etc.

No diretório "buscador-cursos-alura", criaremos um arquivo buscar-cursos.php. Nele, criaremos nosso $client recebendo uma instância de \GuzzleHttp\Client(). Com o PhpStorm, podemos pressionar "Alt + Enter" para importá-lo automaticamente.

<?php

use GuzzleHttp\Client;

$client = new Client(['verify' => false]);COPIAR CÓDIGO
PS.: Esta opção verify ignora alguns erros de HTTPS que possam vir a ocorrer. Seu uso não é recomendado, mas em nosso caso, é apenas um projeto de testes então não tem problema.

De posse do $client, usaremos o método request() para fazermos uma requisiçao do tipo GET para a URL https://www.alura.com.br/cursos-online-programacao/php, referente aos cursos de PHP da Alura, o que nos devolverá uma $resposta.

use GuzzleHttp\Client;

$client = new Client();
$resposta = $client->request('GET', 'https://www.alura.com.br/cursos-online-programacao/php');COPIAR CÓDIGO
Por fim, conseguiremos o nosso $html a partir do corpo da resposta, ou seja, $resposta->getBody().

use GuzzleHttp\Client;

$client = new Client();
$resposta = $client->request('GET', 'https://www.alura.com.br/cursos-online-programacao/php');

$html = $resposta->getBody();COPIAR CÓDIGO
Agora que já começamos a implementação, vamos voltar um pouco. Repare que, na instalação do Guzzle, o Composer atualizou o nosso composer.json:

{
    "name": "nefo/buscador-cursos",
    "description": "Projeto que busca os cursos no site da Alura",
    "type": "library",
    "authors": [
        {
            "name": "nefo",
            "email": "rod.nef@gmail.com"
        }
    ],
    "require": {
        "guzzlehttp/guzzle": "^6.4"
    }
}
COPIAR CÓDIGO
Repare que, no require, foi adicionada uma entrada para o Guzzle. Ou seja, foi definido que o projeto depende desse pacote. Sendo assim, será que é possível, ao invés de executarmos o composer require, simplesmente adicionarmos os novos pacotes aqui? Por exemplo, precisamos do symfony/dom-crawler na versão 4.2, que informaremos utilizando a mesma estrutura encontrada acima:

"require": {
    "guzzlehttp/guzzle": "^6.4",
    "symfony/dom-crawler": "^4.2"
}COPIAR CÓDIGO
O PhpStorm não nos apresentará nenhum erro, mas como instalaremos essas dependências? De volta ao terminal, executaremos composer install. Com isso, o Composer lerá nosso arquivo JSON e instalará as dependências. Entretanto, temos também um arquvo composer.lock que explicita tudo que já foi instalado no projeto, incluindo suas versões. Sendo assim, se quisermos em algum momento, além de baixar os novos pacotes, atualizar os já existentes, podemos rodar o comando composer update. Assim, além de baixar as novas dependências, as já instaladas também serão atualizadas. Normalmente, recomenda-se a utilização do composer update.

Feito isso, teremos as duas dependências necessárias para trabalharmos no nosso projeto. No próximo vídeo finalizaremos a implementação da busca por cursos.

https://packagist.org/packages/guzzlehttp/guzzle

@@04
Para saber mais: Update

No vídeo anterior, ao digitar composer install a nova dependência (Symfony DomCrawler) não foi instalada. O pacote só foi buscado pelo composer ao executar composer update.
Isso se dá pelo seguinte fato: O comando composer install lê o arquivo composer.lock e instala as exatas dependências lá definidas. No nosso caso, nós já tinhamos o arquivo criado, então o composer.lock foi lido e como não havia alterações, nada foi instalado.

Já o comando composer update lê o arquivo composer.json, instala as dependências mais atuais dentro das restrições definidas e atualiza o composer.lock.

Sendo assim, em um projeto em andamento (tendo o arquivo composer.lock), para instalarmos uma nova dependência precisamos executar o composer require ou após adicionar a dependência no arquivo composer.json executar o comando composer update.

@@05
Instalando um pacote

Sabemos agora que há 2 formas diferentes de instalar um pacote utilizando o Composer.
Como posso instalar um pacote utilizando o Composer?

Selecione 2 alternativas

Executando o comando composer require <pacote>
 
Alternativa correta! Basta substituir <pacote> pelo nome do pacote a instalar. Com isso além de instalar a dependência o Composer a adicionará no composer.json
Alternativa correta
Adicionando no item require do composer.json e executando o comando composer update
 
Alternativa correta! Desta forma o Composer lerá o arquivo composer.json e instalará todas as dependências listadas nele. Após realizar este processo, o arquivo composer.lock será atualizado.
Alternativa correta
Executando o comando composer install

@@06
Buscando os cursos da Alura

Agora que temos as duas dependências instaladas, vamos continuar a implementação do nosso projeto. Antes, repassaremos rapidamente o que aprendemos sobre os arquivos composer.json e composer.lock.
O composer.json define a estrutura do nosso projeto e quais são as suas dependências. No futuro, conversaremos um pouco mais sobre os caracteres ^ na parte referente ao versionamento.

{
    "name": "nefo/buscador-cursos",
    "description": "Projeto que busca os cursos no site da Alura",
    "type": "library",
    "authors": [
        {
            "name": "nefo",
            "email": "rod.nef@gmail.com"
        }
    ],
    "require": {
        "guzzlehttp/guzzle": "^6.4",
        "symfony/dom-crawler": "^4.2"
    }
}COPIAR CÓDIGO
Nessa parte estamos informando, bem por alto, que queremos o dom-crawler na versão 4.2, por exemplo. Porém, se existir uma versão superior, como a 4.3 ou 4.5, ela pode ser instalada. Ou seja, estamos definindo a versão mínima a ser instalada, algo que podemos chamar de "constraints".

O composer.lock, sempre que instalamos uma dependência, armazena exatamente a versão que foi instalada e quais as dependências de cada um dos pacotes, entre outras informações extras. Não mexemos nesse arquivo, pois ele é gerenciado pelo próprio Composer.

Um ponto interessante é que, sempre que instalamos um pacote já existente e um novo desenvolvedor vai começar a trabalhar no projeto, o comando composer install verifica se existe um composer.lock e, em caso positivo, buscará exatamente a versão definida nele. Do contrário, o Composer buscará a versão mais recente que atenda à nossa requisição.

Agora continuaremos o desenvolvimento do projeto.

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();
$resposta = $client->request('GET', 'https://www.alura.com.br/cursos-online-programacao/php');

$html = $resposta->getBody();COPIAR CÓDIGO
Até o momento estamos fazendo a requisição e pegando o HTML no corpo da resposta, e precisamos ler esse HTML. Quando instalamos o DomCrawler, foi exibida uma mensagem informando que o pacote sugere a instalação do symfony/css-selector, que faz com que possamos utilizar seletores CSS para buscarmos um elemento, dispensando a necessidade de outra tecnologia chamada exPath.

Sendo assim, voltaremos ao terminal e executaremos symfony/css-selector. Enquanto a instalação é feita, vamos analisar a documentação do DomCrawler:

use Symfony\Component\DomCrawler\Crawler;

$html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p class="message">Hello World!</p>
        <p>Hello Crawler!</p>
    </body>
</html>
HTML;

$crawler = new Crawler($html);

foreach ($crawler as $domElement) {
    var_dump($domElement->nodeName);
}COPIAR CÓDIGO
Vemos que, para utilizá-lo, precisamos instanciá-lo a partir do namespace Symfony\Component\DomCrawler\Crawler e, em seguida, criar um $crawler que recebe o $html por parâmetro. Faremos exatamente isso no nosso código:

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();
$resposta = $client->request('GET', 'https://www.alura.com.br/cursos-online-programacao/php');

$html = $resposta->getBody();

$crawler = new Crawler($html);COPIAR CÓDIGO
Sabemos também que poderíamos definir o $html posteriormente, da seguinte forma:

$crawler->addHtmlContent($html);COPIAR CÓDIGO
Faremos, então, dessa forma, criando o $crawler vazio inicialmente e defindo o HTML depois.

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();
$resposta = $client->request('GET', 'https://www.alura.com.br/cursos-online-programacao/php');

$html = $resposta->getBody();

$crawler = new Crawler();
$crawler->addHtmlContent($html);COPIAR CÓDIGO
Não queremos fazer uma lista a partir de todos os elementos do HTML, afinal queremos somente os nomes dos cursos. Inspecionaremos então a página da alura (https://www.alura.com.br/cursos-online-programacao/php). No código, veremos que cada curso é representado por um <span> contendo a classe card-curso__nome.

Voltaremos ao nosso código e, com o $crawler, usaremos o método filter() para filtrarmos a página a partir do seletor span.card-curso__nome. Isso nos retornará os $cursos, a partir dos quais podemos fazer um loop (foreach) onde cada um dos $cursos é representado por um $curso.

$crawler = new Crawler();
$crawler->addHtmlContent($html);

$cursos = $crawler->filter('span.card-curso__nome');

foreach ($cursos as $curso) {

}COPIAR CÓDIGO
Porém, esse $curso não é uma string, ou seja, o valor do elemento, mas sim um elemento do Dom. A partir dele, pegaremos o conteúdo com textContent, o que nos permitirá exibi-lo na tela com echo.

$crawler = new Crawler();
$crawler->addHtmlContent($html);

$cursos = $crawler->filter('span.card-curso__nome');

foreach ($cursos as $curso) {
    echo $curso->textContent . PHP_EOL;
}COPIAR CÓDIGO
Aparentemente temos tudo pronto. Voltaremos ao prompt e executaremos php buscar-cursos.php. Como retorno, teremos um erro indicando que a classe GuzzleHttp\Client não foi encontrada, o que é estranho, afinal baixamos esse pacote com o composer. Será que precisaremos fazer um require, passando todo o caminho da classe dentro do nosso diretório "src"? Isso não parece prático, ainda mais se tivermos um projeto com várias dependências.

Se você se lembrar bem, o PHP fornece uma funcionalidade chamada de "autoload", que é uma forma de ensinar ao PHP como as classes devem ser encontradas. O Composer nos traz o autoload já implementado por meio do arquivo vendor/autoload.php.

<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();
$resposta = $client->request('GET', 'https://www.alura.com.br/cursos-online-programacao/php');

$html = $resposta->getBody();

$crawler = new Crawler();
$crawler->addHtmlContent($html);

$cursos = $crawler->filter('span.card-curso__nome');

foreach ($cursos as $curso) {
    echo $curso->textContent . PHP_EOL;
}COPIAR CÓDIGO
Após incluirmos o arquivo de autoload, o PHP conseguirá importar corretamente as nossas dependências e quaisquer outras classes que precisarmos que o Composer gerencia. De volta ao terminal, executaremos novamente php buscar-cursos.php. Dessa vez, a listagem dos cursos disponíveis na seção PHP será exibida com sucesso. Bem legal, não?

Assim, já temos o nosso projeto, de certa forma, desacoplado, com o cliente HTTP em um ponto, o crawler do HTML em outro, e o nosso código que une tudo isso. Como queremos disponibilizar futuramente esse código como um novo pacote do Composer, vamos separar a parte que busca um curso em uma classe específica, deixando que o comando buscar-cursos somente chame esse método da nova classe, utilizando a orientação a objetos ao nosso favor.

@@07
Autoload

Durante o desenvolvimento do nosso código, importamos o arquivo autoload.php dentro da pasta vendor.
Qual o propósito deste arquivo?

Carregar o código responsável por realizar o autoload de classes
 
Alternativa correta! Neste arquivo, o Composer faz o trabalho necessário para definir um autoload de classes de forma que seja possível utilizar as dependências sem incluir seus arquivos separadamente. Falaremos mais sobre autoload durante este treinamento.
Alternativa correta
Importar todos os outros arquivos de todas as dependências que baixamos
 
Alternativa errada! Se fosse assim, o Composer importaria inúmeros arquivos que não precisamos no momento
Alternativa correta
Carregar o arquivo necessário para buscar o pacote que faz uma requisição http
 
Alternativa errada! Se fosse apenas este pacote, precisaríamos incluir outro arquivo para o DomCrawler, por exemplo.

@@08
Extraindo classe

Repare que, com poucas linhas de código, conseguimos executar uma lógica relativamente complexa, realizando uma requisição HTTP, buscando um documento HTML e parseando uma string para recuperar os elementos específicos que desejávamos. Isso foi realizado de maneira bem simples, pois utilizamos as dependências externas, o que nos leva de volta à questão de que cada componente deve fazer somente uma coisa, mas fazê-la muito bem.
No nosso caso, temos um cliente HTTP muito poderoso, ainda que bem básico, além de um percorredor de Dom e um seletor CSS também muito poderosos. Com essas bibliotecas externas, conseguimos simplificar bastante o nosso código, mas ele ainda pode ser melhorado.

No projeto "buscador-cursos-alura", criaremos um novo diretório "src" na qual armazenaremos o código do nosso próprio componente. Nele, criaremos uma classe Buscador.php no namespace Alura\BuscadorDeCursos. Precisaremos então de um construtor recebendo um cliente HTTP, e inclusive temos uma ClientInterface (que chamaremos de $httpClient) disponibilizada pelo GuzzleHttp. Também precisaremos de um Crawler do Dom, que chamaremos de $crawler e que, pelo menos por enquanto, não tem uma interface padrão. Poderíamos criar uma, mas isso não é necessário nem faz parte do escopo desse curso.

Recebendo esses dois parâmetros, usaremos "Alt + Enter" para inicializar as propriedades da nossa classe.

class Buscador
{
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }
}COPIAR CÓDIGO
Prosseguindo, criaremos um método buscar() que recebe uma string $url da partir da qual desejamos retornar um array de cursos.

public function buscar(string $url): array
{

}COPIAR CÓDIGO
Para facilitarmos nosso trabalho, vamos começar a desenvolver o nosso código na classe buscar-cursos.php que havíamos preparado anteriormente. Nela, teremos um $buscador que recebe uma instância de Buscador, por sua vez recebendo o $cliente o $crawler. Os nossos $cursos virão do método $buscador->buscar(), que poderia receber como parâmetro a URL completa da página da Alura (https://www.alura.com.br/cursos-online-programacao/php).

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('https://www.alura.com.br/cursos-online-programacao/php');COPIAR CÓDIGO
Entretanto, o Guzzle nos permite informar, na instanciação do Client, uma base_url, que podemos informar como sendo o site da Alura.

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);COPIAR CÓDIGO
Então, faremos uma requisição a partir do site da Alura para a URL /cursos-online-programacao/php.

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');COPIAR CÓDIGO
Vamos recapitular? Estamos criando um $client no qual todas as requisições que fizermos terão como base o dominio https://www.alura.com.br/. Quando chamamos o método buscar(), fazemos a requisição para o caminho /cursos-online-programacao/php dentro desse domínio.

Para finalizar, criaremos também um $crawler:

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');COPIAR CÓDIGO
Agora, voltaremos ao nosso método buscar() e faremos a nossa implementação. Nele, receberemos uma $resposta a partir de $this->httpClient->request() passando como parâmetros o verbo GET e a $url recebida. Em seguida, pegaremos o conteúdo $html do corpo da resposta e o adicionaremos ao nosso crawler ($this->crawler->addHtmlContent()). A partir do crawler, filtraremos os dados, pegando os elementos dos cursos.

public function buscar(string $url): array
{

    $resposta = $this->httpClient->request('GET', $url);

    $html = $resposta->getBody();
    $this->crawler->addHtmlContent($html);

    $cursos = $this->crawler->filter('span.card-curso__nome');

}COPIAR CÓDIGO
Nota: quando o PhpStorm não identifica que estamos utilizando o PHP 7, que é a versão mais recente, alguns tipos podem ser sublinhados, indicando um erro. Para corrigir isso, basta pressionar "Alt + Enter" e trocar para o nível de linguagem do PHP 7.
Nosso crawler nos retornará os elementos dos cursos ($elementosCursos), e nosso desejo é adicionar cada um desses elementos em um array de string $cursos, por fim retornando esse array.

$elementosCursos = $this->crawler->filter('span.card-curso__nome');
$cursos = [];

return $cursos;COPIAR CÓDIGO
Faremos então um foreach no qual cada um dos elementosCursos se chamará $elemento, e desse $elemento pegaremos somente o conteúdo textual (textContent), que adicionaremos ao array $cursos[].

$elementosCursos = $this->crawler->filter('span.card-curso__nome');
$cursos = [];

foreach ($elementosCursos as $elemento) {
    $cursos[] = $elemento->textContent;
}

return $cursos;COPIAR CÓDIGO
Repare que $elementosCursos não é um array de string, mas sim um array de elementos do Dom - na verdade nem mesmo é um array, mas isso é um detalhe que não abordaremos no momento. Como queremos pegar somente o texto contido nessa variável, não estamos retornando-a imediatamente, mas sim percorrendo-a com o foreach e incorporando seus conteúdos textuais para, por fim, retornarmos a lista $cursos.

Em buscar-cursos.php, estamos criando um Client, no qual todas as requisições serão feitas para a Alura, e um Crawler. Na posse de ambos, criamos o nosso Buscador, que executa o método buscar() a partir da url /cursos-online-programacao/php. Para finalizar, iteramos por cada um dos $cursos retornados. Como esse já é um array de string, podemos remover o textContent que fazíamos nesse foreach.

$client = new Client(['base_uri' => 'https://www.alura.com.br']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');

foreach ($cursos as $curso) {
    echo $curso. PHP_EOL;
}COPIAR CÓDIGO
Teoricamente tudo deveria funcionar. Entretanto, se executarmos php buscar-cursos.php, teremos como retorno um erro informando que a classe Buscador não foi encontrada. Isso não deveria ocorrer, afinal o Composer já criou um autoload, certo? Na verdade, a nossa classe Buscador não foi criada pelo Composer ou trazida de algum componente gerenciado por ele. Sendo assim, precisaremos realizar um require dessa classe.

require 'vendor/autoload.php';
require 'src/Buscador.php';COPIAR CÓDIGO
Feito isso, a execução de php buscar-cursos.php passará a funcionar corretamente, nos retornando a lista de cursos. Mas e se tivermos várias classes no nosso projeto, teremos que fazer o require de cada uma delas? Será que não é possível ensinarmos o Composer a criar o autoload incluindo as classes do nosso projeto como um todo? Conversaremos sobre isso no próximo capítulo.

09
Consolidando o seu conhecimento

Chegou a hora de você seguir todos os passos realizados por mim durante esta aula. Caso já tenha feito, excelente. Se ainda não, é importante que você execute o que foi visto nos vídeos para poder continuar com a próxima aula.
1) Na linha de comando, dentro do seu projeto, execute:

composer require symfony/css-selectorCOPIAR CÓDIGO
2) No PHPStorm dentro da pasta src do seu projeto crie uma nova classe PHP chamada de Buscador.php. Segue a implementação da classe:

<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): array
    {
        $resposta = $this->httpClient->request('GET', $url);

        $html = $resposta->getBody();
        $this->crawler->addHtmlContent($html);

        $elementosCursos = $this->crawler->filter('span.card-curso__nome');
        $cursos = [];

        foreach ($elementosCursos as $elemento) {
            $cursos[] = $elemento->textContent;
        }

        return $cursos;
    }
}COPIAR CÓDIGO
3) Agora crie mais um arquivo na raiz do projeto chamado do buscador-curso.php e arquivo implemente da seguinte forma:

<?php

require 'vendor/autoload.php';
require 'src/Buscador.php';

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');

foreach ($cursos as $curso) {
    echo $curso . PHP_EOL;
}COPIAR CÓDIGO
4) Na linha de comando execute:

php buscador-cursos.php

Continue com os seus estudos, e se houver dúvidas, não hesite em recorrer ao nosso fórum!

@@10
O que aprendemos?

Nesta aula, aprendemos: O que aprendemos?
O composer possui um repositório central de pacotes: https://packagist.org/
É possível configurar repositórios de outras fontes (do github, zip etc)
O pacotes guzzlehttp/guzzle serve para executar requisições HTTP de alto nível
Para instalar uma dependência (pacote) basta executar: composer require <nome do pacote>
Composer guarda as dependências e dependências transitivas na pasta vendor do projeto
O nome e versão da dependências fica salvo no arquivo composer.json
O comando require adiciona automaticamente a dependência no composer.json
O comando composer install automaticamente baixa todas as dependências do composer.lock (ou do composer.json, caso o .lock não exista ainda)
O arquivo composer.lock define todas as versões exatas instaladas
O composer já gera um arquivo autoload.php para facilitar o carregamento das dependências
Basta usar require vendor/autoload.php

https://packagist.org/


#### 03/02/2024

@@01
Projeto da aula anterior
PRÓXIMA ATIVIDADE

Caso queira, você pode baixar aqui o projeto do curso no ponto em que paramos na aula anterior.

https://caelum-online-public.s3.amazonaws.com/1255-composer/03/1250-composer-aula2.zip

@@02
PSR-4

Na última aula nós finalizamos a implementação do nosso buscador e separamos o código em uma classe específica. Porém, ainda precisamos fazer o require desse arquivo antes de efetivamente utilizá-lo. Isso não parece eficiente, já que no futuro poderemos ter diversas outras classes no nosso projeto.
Já vimos que o Composer nos fornece um autoload, e seria interessante se pudéssemos ensiná-lo a carregar também as nossas próprias classes. Para isso, precisamos de um padrão, uma forma de mapearmos, por exemplo, nomes de arquivos para nomes de classes, e na realidade esse padrão já existe.

Você provavelmente já ouviu falar do PHP-FIG e de PSRs, mas conversaremos rapidamente sobre eles. O PHP-FIG é um grupo de interoperabilidade entre frameworks que propõe diversos padrões, interfaces e recomendações - estas chamadas de PSRs (PHP Standard Recommendations).

Essas recomendações fazem com que componentes e bibliotecas, como as disponibilizadas no Composer, sejam reutilizáveis entre frameworks. Ou seja, se criarmos uma ferramenta que trata de cache, ela funcionará tanto no Symfony, quanto no ZendFramework quanto no CakePHP. Uma dessas recomendações é a PSR-4, referente ao Autoloader, ou seja, do conceito de mapearmos as nossas classes com os nomes dos arquivos para que uma função de autoload possa encontrá-las.

Na página da PSR4 podemos encontrar todas as suas definições bem explicadas. Basicamente, o principal é que uma classe que esteja em um namespace sempre precisa ter um namespace principal, normalmente chamado de "vendor namespace". Da mesma forma que o nome do nosso projeto possui o nome do autor que está distribuindo o código, o vendor namespace deverá conter o nome do distribuidor, como um namespace padrão.

Sendo assim, se a nossa classe é um buscador de cursos, podemos dizer que o namespace padrão é Alura\BuscadorDeCursos. Esse namespace padrão será mapeado para uma pasta, por exemplo a pasta "src". Com isso, informaremos que todas as classes que pertencerem ao namespace Alura\BuscadorDeCursos estarão dentro da pasta "src".

Se tivermos mais namespaces além disso, como Alura\BuscadorDeCursos\Helper, estamos informando que a classe está dentro de uma pasta "src\helper". Esse padrão de mapeamento já foi muito bem discutido, testado e implementado. Na página da PSR-4, encontramos inclusive alguns exemplos de mapeamento:

FULLY QUALIFIED CLASS NAME	NAMESPACE PREFIX	BASE DIRECTORY	RESULTING FILE PATH
\Acme\Log\Writer\File_Writer	Acme\Log\Writer	./acme-log-writer/lib/	./acme-log-writer/lib/File_Writer.php
\Aura\Web\Response\Status	Aura\Web	/path/to/aura-web/src/	/path/to/aura-web/src/Response/Status.php
Acima, a classe File_Writer está localizada no namespace \Acme\Log\Writer, que é o namespace padrão que se refere ai diretório raiz "/acme-log-writer/lib/". Assim, a classe File_Writer será mapeada ao diretório "/acme-log-writer/lib/" com esse nome de arquivo. Note que é importante que o nome do arquivo seja idêntico ao nome da classe, apenas acrescentando o .php ao final.

O segundo exemplo é um pouco diferente. Nele, temos a classe Status dentro do namespace \Aura\Web\Response, sendo que o namespace padrão desse pacote é Aura\Web e o diretório base é "/path/to/aura-web/src/". Sendo assim, a classe será localizada em "src/Response", e o arquivo se chama Status.php.

Com essas regras, passamos a entender como funciona o mapeamento de um arquivo para uma classe, de um namespace para um caminho de diretório. É esse padrão que o Composer e as bibliotecas que disponibilizam seu código por ele utilizam. Portanto, tudo que teremos que fazer é informar ao Composer que, no nosso projeto, o namespace Alura\BuscadorDeCursos está mapeado para a pasta "src". Com isso, todas as classes que fizerem parte desse namespace estarão na pasta "src", e qualquer namespace a partir dessa raiz será mapeado para a estrutura de diretórios dentro de "src".

No próximo vídeo faremos essa implementação no nosso projeto.

https://www.php-fig.org/psr/psr-4/

https://www.php-fig.org/psr/psr-4/

@@03
PSR de Autoload
PRÓXIMA ATIVIDADE

Entendemos agora como os pacotes se organizam de forma que o Composer consiga realizar o autoload de todos com um único código.
Quais os principais pontos da PSR-4?

Todos os arquivos devem ter como seu nome o nome da classe contida nele e a extensão .php
 
Alternativa correta! A classe Teste deve estar no arquivo chamado Teste.php, por exemplo.
Alternativa correta
Cada um dos namespaces após o vendor namespace deve ser mapeados para uma estrutura de diretórios
 
Alternativa correta! Levando em consideração que Alura\Namespace\Padrao está mapeado para /src/php/code, a classe Alura\Namespace\Padrao\Helper\ClasseHelper deve estar no caminho /src/php/code/Helper/ClasseHelper.php.
Alternativa correta
Um vendor namespace (namespace raiz ou padrão) deve ser mapeado para uma pasta base da aplicação
 
Alternativa correta! Sempre precisa haver um mapeamento entre um namespace raiz para uma pasta base. Ex.: Todas as classes e namespaces que tiverem no namespace Alura\Namespace\Padrao poderão ser encontrados na pasta /src/php/code.
Alternativa correta
As chaves de abertura de classes e funções devem estar em uma nova linha

@@04
Configurando a PSR-4

Nesse vídeo vamos configurar o Composer para ele conseguir carregar nossas classes utilizando a PSR-4. Como você já deve ter imaginado, faremos isso por meio do nosso arquivo composer.json. Nele podemos passar diversas informações, e uma delas é sobre autoload.
{
    "name": "nefo/buscador-cursos",
    "description": "Projeto que busca os cursos no site da Alura",
    "type": "library",
    "authors": [
        {
            "name": "nefo",
            "email": "rod.nef@gmail.com"
        }
    ],
    "require": {
        "guzzlehttp/guzzle": "^6.4",
        "symfony/dom-crawler": "^4.2",
        "symfony/css-selector": "^5.0"
    },
    "autoload" : {

    }
}COPIAR CÓDIGO
No autoload podemos utilizar duas PSRs: a psr-0, a primeira PSR a ser sugerida e que já foi depreciada; ou a psr-4, a atual, que discutimos no vídeo anterior. A psr-4 funciona determinando um namespace, por exemplo Alura\\BuscadorDeCursos, que será o namespace padrão da nossa aplicação. Repare que nessa definição precisamos utilizar a contra-barra duas vezes (\\). Nós mapearemos esse namespace para uma pasta "src/".

"autoload" : {
    "psr-4" : {
        "Alura\\BuscadorDeCursos\\": "src/"
    }
}COPIAR CÓDIGO
Assim, todas as classes que começarem com o namespace Alura\\BuscadorDeCursos\\ serão buscadas na pasta "src/" do nosso projeto. Logo, se tentássemos encontrar uma classe Alura\BuscadorDeCursos\Service\ClasseTeste, esse arquivo estaria dentro de "src/Service/ClasseTeste.php".

Agora que nosso autoloader está configurado, podemos remover a instrução require que fazia a importação do Buscador.php em buscar-cursos.php.

<?php

require 'vendor/autoload.php';

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(['base_uri' => 'https://www.alura.com.br']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');

foreach ($cursos as $curso) {
    echo $curso. PHP_EOL;
}COPIAR CÓDIGO
Se retornarmos ao terminal e executarmos php buscar-cursos-.php...continuaremos recebendo um erro de classe não encontrada. Mas por que, se já configuramos o autoload? Isso ocorre porque vendor/autoload.php é um arquivo que precisa conhecer as pastas do mapeamento da nossa PSR-4. Porém, ele já existia antes de fazermos as configurações no composer.json. Sendo assim, precisaremos atualizá-lo. Para isso, ao invés de executarmos um composer install ou um composer require, que instalavam novas dependências no nosso projeto, usaremos o comando composer dumpautoload (ou composer dump-autoload), que gerará um novo arquivo de autoload com as configurações que fizemos.

Feito isso, se executarmos php buscar-cursos.php novamente, não teremos mais um erro e a aplicação rodará com sucesso. Assim, já temos todo o nosso código funcional utilizando bibliotecas, orientação a objetos e autoload (tanto para as bibliotecas externas quanto as nossas próprias classes).

Porém, em alguns momentos não utilizamos a orientação a objetos. Pode acontecer de termos um arquivo com várias funções auxiliares que executam lógicas pequenas. E se quisermos fazer com que o Composer já carregue no autoload esse arquivo com várias funções, ou se quisermos incluir um arquivo específico? Conversaremos sobre isso no próximo vídeo.