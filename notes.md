#### 01/02/2024

Curso de PHP Composer: Dependências, Autoload e Publicação

```
composer require guzzlehttp/guzzle
composer require symfony/dom-crawler
composer require symfony/css-selector
composer install
composer update
composer insall --no-dev
composer require --dev phpunit/phpunit
vendor/bin/phpunit --version
composer require --dev squizlabs/php_codesniffer
vendor/bin/phpcs --standard=PSR12 src/
composer require --dev phan/phan
composer dumpautoload
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

@@05
Autoload no composer.json

Já sabemos que o autoload.php do Composer consegue buscar as classes dos componentes baixados pois eles implementam a PSR-4. Nós também vimos agora como configurar a PSR-4 em nosso projeto.
O que é necessário para configurar a PSR-4 em nosso projeto, levando em consideração que nossa estrutura de arquivos já atende seus requisitos?

Basta adicionar na chave psr-4 filha da chave autoload a chave contendo nossa pasta base e o valor contendo nosso vendor namespace
 
Alternativa correta
Basta adicionar na chave psr-4 filha da chave autoload a chave contendo nosso vendor namespace e o valor contendo nossa pasta base
 
Alternativa correta! Exemplo: { “autoload”: { “psr-4”: { “Alura\\Namespace\\Padrao\\”: “src/php/code/” } } }
Alternativa correta
Basta adicionar o nome da pasta base na chave psr-4 filha da chave autoload

@@06
Classmap e Files

Como comentamos no último vídeo, nem sempre o nosso código segue a PSR4. Podemos, por exemplo, acabar trabalhando com códigos legados ou pegando projetos de outras pessoas que não conheciam essa recomendação. Será que nesses casos não podemos utilizar o autoloader do Composer? Mas é claro que podemos!
Existe outra configuração que podemos informar ao Composer chamada Classmap, ou seja, um mapa de classes. O Classmap basicamente define que estamos mapeando uma classe para determinado caminho/diretório. Assim, sempre que tentarmos instanciar uma classe de determinado nome, iremos buscá-la no arquivo indicado, como no exemplo a seguir:

{
    "autoload": {
        "classmap": ["src/", "lib/", "Something.php"]
    }
}COPIAR CÓDIGO
Para imaginarmos um exemplo real, criaremos no nosso projeto uma classe Teste no namespace global. Essa classe terá apenas um método estático teste() que chama um echo "Teste".

class Teste
{
    public static function teste()
    {
        echo "Teste";
    }

}COPIAR CÓDIGO
Em buscar-cursos.php, antes de executarmos qualquer coisa, queremos chamar Teste::teste() e parar a aplicação com um exit().

<?php

require 'vendor/autoload.php';

Teste::teste();
exit();COPIAR CÓDIGO
Se executarmos php buscar-cursos.php, obviamente receberemos um erro indicando que a classe não foi encontrada. Tentaremos, então, fazer o mapeamento. Em composer.json, além do autoload com a psr-4, definremos um classmap contendo os diretórios nos quais temos classes por exemplo o diretório atual contendo a classe Teste.php.

"autoload" : {
    "classmap": [
        "./Teste.php"
    ],
    "psr-4" : {
        "Alura\\BuscadorDeCursos\\": "src/"
    }
}COPIAR CÓDIGO
Agora, se executarmos composer dumpautoload, teremos uma mensagem informando que uma classe foi identificada:

Generated autoload files containing 1 classes
Se você tiver um pouco mais de curiosidade, pode encontrar, dentro de "vendor/composer", o arquivo autoload_classmap.php que já tenta encontrar uma classe chamada Teste no nosso arquivo Teste.php do diretório base:

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Teste' => $baseDir . '/Teste.php',
);
COPIAR CÓDIGO
Se executarmos php buscar-cursos.php no terminal, receberemos uma mensagem de erro, dessa vez porque, por coincidência, utilizamos o nome do método igual ao nome da classe. Para corrigirmos isso, renomearemos o método teste() como metodo().

class Teste
{
    public static function metodo()
    {
        echo "Teste";
    }

}COPIAR CÓDIGO
Antigamente, o construtor da classe se dava atribuindo o mesmo nome da classe ao método.

public function Teste()
{
} COPIAR CÓDIGO
Hoje em dia não fazemos mais dessa forma, e agora utilizamos um método mágico __construct(). E o que acontece se criarmos uma nova classe Teste2 no mesmo arquivo, contendo outro método estático metodo()?

class Teste
{
    public static function metodo()
    {
        echo "Teste";
    }

}


class Teste2
{
    public static function metodo()
    {
        echo "Teste2";
    }

}COPIAR CÓDIGO
Se executarmos o composer dumpautoload no terminal, serão identificadas duas classes:

Generated autoload files containing 2 classes
Além disso, no arquivo autoload_classmap.php, teremos as duas classes apontando para o mesmo arquivo:

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Teste' => $baseDir . '/Teste.php',
    'Teste2' => $baseDir . '/Teste.php',
);
COPIAR CÓDIGO
Ou seja, o Composer lê o arquivo que definimos no classmap e mapeia as classes dentro dele para sempre executar um require quando elas forem acessadas. Com isso, já resolvemos o problema de classes que não implementem a PSR4, por exemplo - inclusive problemas maiores, como arquivos que contêm mais de uma classe ou nos quais o nome da classe não é igual ao nome do arquivo.

Mas existe ainda um outro caso, até mesmo mais comum. Para analisarmos esse caso, deletaremos o arquivo Teste.php e a chamada que fazemos ao método metodo() em buscar-cursos.php. Imagine então que temos um arquivo com várias funções, por exemplo exibeMensagem(), que insere uma quebra de linha ao final do $curso que é exibido.

$client = new Client(['base_uri' => 'https://www.alura.com.br']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');

foreach ($cursos as $curso) {
    echo exibeMensagem($curso);
}COPIAR CÓDIGO
Criaremos então um arquivo functions contendo a definição desse método:

function exibeMensagem(string $mensagem) 
{
    echo $mensagem . PHP_EOL;
}COPIAR CÓDIGO
Temos então que a função exibe uma $mensagem passada por parâmetro e quebra uma linha ao final. Queremos utilizá-la no nosso buscar-cursos.php, mas como o Composer irá encontrá-la se não temos uma classe? Para isso, podemos utilizar, no composer.json, a entrada files, passando um array que informará quais arquivos deverão sempre ser incluídos, independentemente do que estivermos tentando acessar.

"autoload" : {
    "files": ["./functions.php"],
    "psr-4" : {
        "Alura\\BuscadorDeCursos\\": "src/"
    }
}COPIAR CÓDIGO
Agora, se executarmos novamente o composer dumpautoload e em seguida php buscar-cursos.php, tudo funcionará corretamente. Ou seja, nossa função exibeMensagem(), que está em outro arquivo, foi encontrada com sucesso, já que definimos que sempre que o autoload for buscado o arquivo functions.php deve ser inserido.

Se quisermos, como files recebe um array, podemos incluir vários arquivos para serem carregados, como helpers.php, views.php, entre outros que tivermos no projeto. Com isso, encerramos todas as formas de fazer autoload!

Vamos recapitular? É possível utilizar as PSRs 0 ou 4, lembrando que a recomendação é utilizar a 4, mapeando um namespace para um diretório no qual todos os namespaces seguintes serão também transformados em diretórios. Além disso, podemos mapear classes que não implementem as PSRs por meio de um Classmap, e podemos adicionar arquivos isolados usando o files. Tudo isso será condensado em um único arquivo de autoload, e é somente ele que precisaremos incluir para instanciarmos todas as outras classes sem fazer o require dos arquivos individualmente.

Com o autoload pronto, está na hora de trabalharmos com algumas coisas mais interessantes. Por exemplo, o Composer tem uma ferramenta na linha de comando, e até mesmo conseguimos executar o composer list para listarmos todos os comandos disponíveis. Será possível trazermos para o nosso projeto, além de classes que nos fornecem algumas utilidades, outras ferramentas pela linha de comando? Por exemplo, para executarmos alguns testes em nosso código? Conversaremos sobre isso no próximo capítulo!

@@07
Projetos legados

É muito comum trabalharmos em projetos legados que não implementam a PSR-4 em sua estrutura de arquivos.
Qual a solução que o Composer nos entrega para conseguirmos utilizar um autoload nesses casos?

Files
 
Alternativa correta
PSR-0
 
Alternativa correta
Classmap
 
Alternativa correta! Com a chave classmap conseguimos informar arquivos que contenham classes para que o Composer as encontre mesmo que não sigam a PSR-4.

@@08
Consolidando o seu conhecimento

Chegou a hora de você seguir todos os passos realizados por mim durante esta aula. Caso já tenha feito, excelente. Se ainda não, é importante que você execute o que foi visto nos vídeos para poder continuar com a próxima aula.
1) No seu editor de código abra o arquivo buscador-cursos.php e apague a linha require 'src/Buscador.php';

2) Agora abra o arquivo composer.json e adicione no final a configuração do autoload (dentro das chaves principais) para mapear o namespace padrão:

"autoload": {
    "psr-4": {
        "Alura\\BuscadorDeCursos\\": "src/"
    }
}COPIAR CÓDIGO
3) Para gerar o arquivo autoload.php execute na linha de comando:

 composer dumpautoloadCOPIAR CÓDIGO
4) Agora execute na linha de comando:

php buscador-cursos.phpCOPIAR CÓDIGO
5) (Opcional) Experimente também o uso o classmap e e arquivos (files) no composer.json.

Continue com os seus estudos, e se houver dúvidas, não hesite em recorrer ao nosso fórum!

@@09
O que aprendemos?

Nesta aula, aprendemos: O que aprendemos?
Conhecemos a PSR-4 (Autoloader)
A PSR-4 define um padrão para o carregamento automático de classes
O namespace da classe tem partes:
O vendor namespace (ou namespace padrão ou namespace prefixo)
O vendor namespace fica mapeado para uma pasta do projeto dentro do arquivo composer.json
Podemos ter um sub-namespace que precisa ser representado através de pastas
Para atualizar o arquivo autoload.php baseado no composer.json, podemos rodar o comando composer dumpautoload
Para classes que não seguem o PSR-4, podemos definir um classmap dentro do composer.json
Para carregar um biblioteca de funções automaticamente, podemos adicionar uma entrada files no composer.json

#### 03/02/2024

@04-Ferramentas de qualidade de código

@@01
Projeto da aula anterior

Caso queira, você pode baixar aqui o projeto do curso no ponto em que paramos na aula anterior.

https://caelum-online-public.s3.amazonaws.com/1255-composer/04/1250-composer-aula3.zip

@@02
Instalando PHPUnit

Até o momento só temos visto dependências com as quais escrevemos código, como um cliente HTTP ou um crawler, e são todas dependências que vão para o nosso projeto em produção, ou seja, para o código que iremos disponibilizar. Mas e as ferramentas que só são utilizadas no momento do desenvolvimento, como o PHPUnit, utilizado para testar nossa aplicação? Será que não podemos utilizar o Composer para baixá-lo?
Sabemos que se for feito um composer require ou se adicionarmos o PHPUnit no nosso composer.json, ele também será instalado quando rodarmos o composer install no nosso servidor de produção, mesmo sendo uma ferramenta própria apenas ao ambiente de desenvolvimento. Será possível, então, separarmos as ferramentas de desenvolvimento e as de produção?

Na documentação do PHPUnit , na parte referente ao Composer, encontramos a seguinte instrução:

➜ composer require --dev phpunit/phpunit ^8

➜ ./vendor/bin/phpunit --version
PHPUnit 8.0.0 by Sebastian Bergmann and contributors.COPIAR CÓDIGO
Repare que na chamada do composer require foi adicionado um parâmetro --dev. Esse parâmetro informa que a dependência a ser instalada não deverá ser utilizada em ambiente de produção, mas apenas na máquina de desenvolvimento na qual estamos trabalhando no momento. Sendo assim, copiaremos o código composer require --dev phpunit/phpunit ^8 e o executaremos em nosso terminal.

Após a instalação, repare que o PHPUnit precisa instalar diversos componentes para funcionar, e ainda sugere alguns outros. Se o instalássemos no servidor de produção, acabaríamos gastando mais espaço, tempo e talvez até memória (no caso desses códigos serem utilizados).

Em ambiente de produção, quando precisarmos instalar as dependências do projeto, bastará executarmos composer install --no-dev para que sejam baixadas as dependências dentro de require no arquivo composer.json, mas não as dependências de require-dev, que foi adicionado quando instalamos o PHPUnit.

Já vimos como instalar uma ferramenta para o ambiente de desenvolvimento, no caso o PHPUnit. Caso não conheça, ele é uma ferramenta muto poderosa para testes - majoritariamente testes unitários, mas que disponibiliza recursos para realizar qualquer tipo de testes. Temos, inclusive, um curso de PHPUnit aqui na Alura! Se quiser se aprofundar mais nessa ferramenta, é interessante estudá-la.

Como já fizemos a instalação, que tal escrevermos um teste simples? Mas, antes disso, como podemos garantir que o PHPUnit realmente está instalado, se ele não fornece - pelo menos por enquanto - códigos?

Sempre que temos uma ferramenta que roda na linha de comando, como é o caso do PHPUnit, nos é fornecido um arquivo executável dentro da pasta "\vendor\bin" que pode ser rodado utilizando vendor\bin\phpunit, por exemplo. Para testarmos, verificaremos a versão que foi instalada chamando vendor\bin\phpunit --version.

PHPUnit 8.0.0 by Sebastian Bergmann and contributors.
Assim, conseguimos a confirmação de que o PHPUnit foi instalado e de que ele pode ser executado pela linha de comando. No próximo vídeo conversaremos sobre como executar um teste propriamente dito.

https://phpunit.de/getting-started/phpunit-8.html

https://packagist.org/packages/phpunit/phpunit

@@03
Arquivos executáveis

Vimos nesta aula que conseguimos ter dependências específicas para nos auxiliar no momento do desenvolvimento, que não serão utilizadas em código de produção, por quem baixar nosso pacote. Muitas dependências deste tipo fornecem arquivos executáveis.
Onde ficam os arquivos executáveis que o Composer traz com os pacotes?

Na pasta bin dentro da pasta vendor
 
Alternativa correta! O Composer organiza muito bem a pasta vendor e dentro dela há uma pasta chamada bin. Nesta ficam todos os arquivos executáveis que nossas dependências possam fornecer. O exemplo utilizado na aula foi a ferramenta de testes PHPUnit.
Alternativa correta
Na pasta com nome do pacote em questão, dentro da pasta vendor
 
Alternativa correta
Na raiz da pasta vendor

@@04
Escrevendo um teste

Já vimos que é possível definir dependências de desenvolvimento, ou seja, dependências que só são utilizadas durante o desenvolvimento do projeto, e que não são necessárias na produção. Além disso, já instalamos uma dessas dependências, o PHPUnit, uma ferramenta para executar testes automatizados.
Para vermos um código funcionando, nosso instrutor escreveu um teste. Como o foco desse treinamento não são os testes automatizados, vamos passar por ele rapidamente. No caso, teremos na raiz do projeto um diretório "tests" contendo o arquivo TestBuscadorDeCursos.php com o seguindo código:

<?php

namespace Alura\BuscadorDeCursos\Tests;

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

class TestBuscadorDeCursos extends TestCase
{
    private $httpClientMock;
    private $url = 'url-teste';

    protected function setUp(): void
    {
        $html = <<<FIM
        <html>
            <body>
                <span class="card-curso__nome">Curso Teste 1</span>
                <span class="card-curso__nome">Curso Teste 2</span>
                <span class="card-curso__nome">Curso Teste 3</span>
            </body>
        </html>
        FIM;


        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this
            ->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->url)
            ->willReturn($response);

        $this->httpClientMock = $httpClient;
    }

    public function testBuscadorDeveRetornarCursos()
    {
        $crawler = new Crawler();
        $buscador = new Buscador($this->httpClientMock, $crawler);
        $cursos = $buscador->buscar($this->url);

        $this->assertCount(3, $cursos);
        $this->assertEquals('Curso Teste 1', $cursos[0]);
        $this->assertEquals('Curso Teste 2', $cursos[1]);
        $this->assertEquals('Curso Teste 3', $cursos[2]);
    }
}
COPIAR CÓDIGO
Nele, temos uma função testBuscadorDeveRetornarCursos() que é, efetivamente, o nosso teste, e que garante que o buscador deve retornar cursos com base em um HTML de exemplo. Na função, instanciamos o Crawler e criamos um cliente HTTP "falso", como um dublê de testes (httpClientMock), para que a requisição não aconteça de verdade, fazendo com que o teste execute mais rápido e consigamos controlá-lo melhor - por exemplo, nosso teste não será afetado caso o site saia do ar.

A partir do buscador, executamos uma busca passando uma URL de teste e garantimos que determinados cursos serão retornados. Isso acontece pois, na função setUp(), definimos um HTML para ser utilizado no teste. Para isso, criamos um Mock, ou seja, um dublê de resposta, para a nossa clientInterface, a dependência do nosso buscador. Com a resposta, chamamos o getBody para retornarmos um stream do qual criaremos também um dublê de testes. Como esse stream é representado por uma string, criamos um Mock para o método __toString.

Resumindo, estamos garantindo que o HTML $html será parseado/lido corretamente, resultando em três cursos com os conteúdos definidos no teste. Para garantirmos que o teste funciona, basta, no PhpStorm, clicarmos no botão "Run" ao lado da definição da classe. Na saída, teremos os resultados do teste, mostrando que tudo ocorreu como deveria.

Podemos alterar o teste para fazer com que ele falhe de propósito, por exemplo mudando o valor de assertCount() para 2. Também podemos executar o teste pela linha de comando utilizando vendor\bin\phpunit seguido do caminho do teste, nesse caso tests\TesteBuscadorDeCursos.php.

vendor\bin\phpunit tests\TesteBuscadorDeCursos.phpCOPIAR CÓDIGO
Repare que dentro da pasta "vendor\bin" nós temos um arquivo executável que foi trazido pelo Composer. Ou seja, o Composer criou uma pasta onde ficarão os arquivos executáveis relacionados às nossas dependências. Em relação aos testes, se você quiser mais detalhes, pode conferir os cursos que temos aqui na Alura - inclusive de PHPUnit.

Feito tudo isso, já temos uma dependência de desenvolvimento, temos um teste para garantir que nossa aplicação funciona da forma esperada e podemos pensar em utilizar outras ferramentas, por exemplo uma que busque erros no nosso código. Imagine que digitemos errado o nome de alguma variável, como $httdpClient ao invés de $httpClient, poderíamos ter uma ferramenta apontando que a variável que estamos tentando acessar não existe, que um tipo definido não corresponde ao método que está sendo chamado ou mesmo que verifique se estamos seguindo as boas práticas de codificação, posicionamento das chaves, etc.

No próximo vídeo começaremos a conhecer um pouco mais essas ferramentas que podem ajudar na qualidade do nosso código.

@@05
Para saber mais: PHPUnit

Nesta aula vimos como é o código de um teste automatizado. Como este não é o foco do treinamento, não vamos entrar em detalhes sobre como desenvolver um teste automatizado, nem se o teste está escrito da melhor forma.
Caso você tenha interesse em aprender mais sobre teste (o que é super recomendado) pode conferir nosso cursos específicos deste assunto aqui na Alura. Temos cursos de PHPUnit na plataforma. :-D

https://cursos.alura.com.br/course/phpunit-tdd

@@06
Instalando o PHPCS

Já entendemos como separar as dependências de produção e desenvolvimento, e que, em um servidor de produção, podemos executar um composer install --no-dev para não trazer as dependências que estiverem listadas no require-dev, como o PHPUnit. Porém, o PHPUnit é somente uma das ferramentas que podemos rodar na linha de comando e utilizar no nosso ambiente de desenvolvimento, e gostaríamos de apresentar algumas outras.
Uma ferramente bastante interessante, que é um projeto bastante antigo, verifica se nosso código está dentro dos padrões. Por exemplo, existe uma PSR que dita que estruturas de controle, como loops e if, precisam ter as chaves sendo abertas na mesma linha em que a estrutura foi definida. Já métodos e classes têm as chaves abertas na linha de baixo. Para testarmos, faremos pequenas alterações no Buscador.php.

public function buscar(string $url): array {

    $resposta = $this->httpClient->request('GET', $url);

    $html = $resposta->getBody();
    $this->crawler->addHtmlContent($html);

    $elementosCursos = $this->crawler->filter('span.card-curso__nome');
    $cursos = [];

    foreach ($elementosCursos as $elemento)
    {
        $cursos[] = $elemento->textContent;
    }

    return $cursos;
}COPIAR CÓDIGO
Se quebrarmos esse padrão, o PHP não acusará nenhum erro. Queremos então uma ferramenta que nos mostre em que locais do código tal padrão está sendo quebrado. O nome dessa ferramenta é PHP_CodeSniffer, também, conhecida como PHPCS. Acessaremos o site da ferramenta, onde encontraremos as instruções de instalação com o Composer.

composer global require "squizlabs/php_codesniffer=*"COPIAR CÓDIGO
Repare que se utiliza o comando global require, o que significa que a dependência será instalada de forma global no sistema e poderá ser acessada de qualquer pasta. Porém, não queremos isso, mas sim que o PHPCS seja instalado somente no nosso projeto. Sendo assim, usaremos composer require --dev squizlabs/php_codesniffer para instalarmos esse componente como uma dependência de desenvolvimento.

Após o componente ter sido baixado, poderemos executá-lo pela linha de comando para verificarmos se nosso código está dentro de um padrão especificado. Feita a instalação, acessaremos vendor\bin\phpcs --help para exibir os comandos do PHPCS que nos são disponibilizados. Dentre as várias opções, queremos executar vendor\bin\phpcs --standard=PSR12 src\, ou seja, rodaremos o PHPCS com o padrão da PSR12 analisando o código dentro da pasta "src".

Rodando esse comando, três erros serão encontrados: primeiro que a quebra de linha do arquivo está utilizando o padrão do Windows; segundo que, na linha 25, estamos abrindo as chaves na mesma linha da função; e terceiro que, na linha 34 (que se torna a linha 35 após consertarmos o erro anterior), deveríamos abrir chaves na mesma linha do foreach.

Corrigindo esses erros, se executarmos novamente o PHPCS teremos apenas um erro, referente à quebra de linha do Windows. Para resolvê-lo, precisaríamos mudar a configuração do nosso editor de texto, mas é algo com que não iremos nos preocupar. Perceba, então, que conseguimos analisar e garantir que nosso projeto está seguindo determinado padrão de codificação, no caso a PSR12 (e existem vários outros), além de obtermos um feedback em relação a isso.

Entretanto, essa ferramenta não verifica se nosso código está errado, por exemplo se temos um nome de variável incorreto. No próximo vídeo conheceremos uma ferramenta que faz justamente essa busca por erros antes de executarmos o nosso código.

@@07
Para saber mais: PSR 12

Quando utilizamos Composer para gerenciar nosso projeto, normalmente significa que temos consciência do que estamos fazendo e somos pessoas que se preocupam com qualidade de código. Além de diversas boas práticas, existem também recomendações específicas sobre como organizar nosso código.
Em qual linha abrir as chaves, como nomear variáveis e métodos, etc. Tudo isso está bem descrito na PSR 12 e nesta aula instalamos uma ferramenta que verifica se o código que escrevemos está seguindo estas recomendações.

@@08
Instalando o Phan

Nesse capítulo temos conhecido algumas ferramentas interessantes para trabalharmos na linha de comando e, com isso, aprendemos a separar as dependências de produção das dependências de desenvolvimento, como escrever e rodar e um teste e como analisar o nosso código para garantirmos que ele siga determinado padrão (como o da PSR-12).
Porém, ainda não estamos detectando possíveis erros no nosso código. Para isso, podemos utilizar uma ferramenta chamada Phan. Para instalarmos, usaremos composer require --dev phan/phan, incluindo o --dev pois somente queremos instalá-la na nossa máquina de desenvolvimento.

Sempre que instalamos um pacote que possui uma ferramenta de linha de comando, o Composer a move para o diretório "vendor\bin" do composer. Sendo assim, os arquivos executáveis serão todos armazenados nessa pasta - que, no momento, conta com o phpunit e o phpcs que instalamos anteriormente. O PHPCS inclui ainda um phpcbf, outro comando capaz de consertar os erros identificados pelo PHPCS.

Anteriormente, o PHPCS identificou um erro em relação às quebras de linha no código. Esse erro é facilmente resolvido no PhpStorm, bastando mudarmos a opção CRLF para LF. Feito isso, se executarmos vendor\bin\phpcs --standard=PSR12 src\, não receberemos nenhum erro.

Para executarmos o novo pacote que baixamos, precisaremos habilitar uma extensão. No retorno da instalação, somos informados que se a extensão de ASTs não tiver habilitada, devemos rodar o comando com o parâmetro --allow-polyfill-parser. Para não entrarmos em detalhes sobre o uso de extensões, sempre rodaremos com esse parâmetro.

No terminal, executaremos vendor\bin\phan --help para obtermos a lista de comandos. Com ela, aprenderemos que, para executarmos os comandos, precisaremos passar as opções seguidas dos arquivos que deverão ser analisados, ou mesmo um diretório (utilizando -l). Por enquanto, como só temos um arquivo, usaremos a sintaxe [options] [files...].

vendor\bin\phan --allow-polyfill-parser src\Buscador.phpCOPIAR CÓDIGO
Feito isso, seremos informados alguns erros:

src\Buscador.php:13 PhanUndeclaredTypeProperty Property \Alura\BuscadorDeCursos\Buscador->httpClient has undeclared type \GuzzleHttp\ClientInterface
src\Buscador.php:17 PhanUndeclaredTypeProperty Property \Alura\BuscadorDeCursos\Buscador->crawler has undeclared type \Symfony\Component\DomCrawler\Crawler
src\Buscador.php:19 PhanUndeclaredTypeParameter Parameter $crawler has undeclared type \Symfony\Component\DomCrawler\Crawler
src\Buscador.php:19 PhanUndeclaredTypeParameter Parameter $httpClient has undeclared type \GuzzleHttp\ClientInterface
src\Buscador.php:27 PhanUndeclaredClassMethod Call to method request from undeclared class \GuzzleHttp\ClientInterface
src\Buscador.php:30 PhanUndeclaredClassMethod Call to method addHtmlContent from undeclared class \Symfony\Component\DomCrawler\Crawler
src\Buscador.php:32 PhanUndeclaredClassMethod Call to method filter from undeclared class \Symfony\Component\DomCrawler\CrawlerCOPIAR CÓDIGO
Os primeiros dizem que os tipos dos nossos objetos, ClientInterface e Crawler, não foram identificados. Isso acontece pois o Phan está analisando somente o nosso Buscador.php, e não o restante do projeto. Para corrigirmos isso, teremos que informar um arquivo de configuração do Phan, o que é feito criando um diretório .phan contendo um arquivo config.php.

De volta ao projeto, criaremos o arquivo especificado e colaremos nele o conteúdo mostrado na documentação. Note que, abaixo, os comentários já foram removidos.

<?php

return [

    "target_php_version" => null,

    'directory_list' => [
        'src',
        'vendor/symfony/console',
    ],

    "exclude_analysis_directory_list" => [
        'vendor/'
    ],

    'plugins' => [
        'AlwaysReturnPlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'DuplicateExpressionPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
        'SleepCheckerPlugin',
        'UnreachableCodePlugin',
        'UseReturnValuePlugin',
        'EmptyStatementListPlugin',
        'LoopVariableReusePlugin',
    ],
];COPIAR CÓDIGO
Em seguida, faremos as alterações relativas ao nosso projeto. Primeiro, a versão do PHP que estamos utilizando (targeted_php_version) é a 7.3. Os diretórios que queremos utilizar (directory_list) são "src", "vendor/symfony/dom-crawler" e "vendor/guzzlehttp/guzzle". Em exclude_analysis_directory_list, podemos informar quais diretórios deverão ser ignorados - nesse caso, toda a pasta "vendor", com exceção dos diretórios que passamos na opção anterior.

Por fim, existem diversos plugins que podemos habilitar, como AlwaysReturnPlugin< UnreachableCodePlugin, entre outros. Como o arquivo de configuração já deixou alguns habilitados, vamos mantê-los assim.

<?php

return [

    "target_php_version" => null,

    'directory_list' => [
        'src',
        'vendor/symfony/dom-crawler',
        'vendor/guzzlehttp/guzzle'
    ],

    "exclude_analysis_directory_list" => [
        'vendor/'
    ],

    'plugins' => [
        'AlwaysReturnPlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'DuplicateExpressionPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
        'SleepCheckerPlugin',
        'UnreachableCodePlugin',
        'UseReturnValuePlugin',
        'EmptyStatementListPlugin',
        'LoopVariableReusePlugin',
    ],
];COPIAR CÓDIGO
Feitas as alterações, se executarmos src\Buscador.php:29 PhanUndeclaredClassMethod Call to method getBody from undeclared class \Psr\Http\Message\ResponseInterface novamente, receberemos apenas um erro indicando que a ResponseInterface não foi identificada.

src\Buscador.php:29 PhanUndeclaredClassMethod Call to method getBody from undeclared class \Psr\Http\Message\ResponseInterfaceCOPIAR CÓDIGO
Isso acontece pois não informamos o caminho dessa classe. Sendo assim, voltaremos ao arquivo de configuração e incluiremos o caminho vendor/psr/http-message.

'directory_list' => [
    'src',
    'vendor/symfony/dom-crawler',
    'vendor/guzzlehttp/guzzle',
    'vendor/psr/http-message'
],
COPIAR CÓDIGO
Esse trabalho é um pouco maçante, mas estamos demonstrando para que você conheça essa ferramenta que analisa e encontra possíveis erros no projeto antes mesmo de rodarmos o código ou executarmos os testes. Rodando novamente o Phan no terminal, não teremos nenhum erro. Existem ainda muitas outras ferramentas que podem ser executadas, algumas que, inclusive, pegam erros que o Phan não consegue pegar (e vice-versa).

Até o momento já aprendemos como o Composer trabalha com arquivos binários, como ele trabalha com dependências de desenvolvimento, como instalar dependências de desenvolvimento e produção, como executar somente as dependências de produção (--no-dev), entre outros recursos bem interessantes desse gerenciador.

Mas ainda temos muitoo que aprender! Por exemplo, para executarmos o phan, estamos utilizando um comando bastante extenso (vendor\bin\phan --allow-polyfill-parser src\Buscador.php). O mesmo ocorre para o PHPCS e para o PHPUnit. É possível melhorarmos essas execuções, por exemplo definindo um comando composer test que executaria um script do PHPUnit, ou composer phan para rodarmos o Phan já com os parâmetros definidos, e assim por diante.

O Composer nos permite executar scripts dessa forma, e é sobre isso que conversaremos no próximo capítulo.

@@09
Para saber mais: Executáveis em PHP

Caso você tenha sido curioso e tentou abrir um dos arquivos executáveis que utilizamos nesta aula, talvez tenha ficado com alguma dúvida.
Os arquivos contém código PHP, mas para executá-los nem precisamos chamar o PHP.

Isso se dá pelo fato do início do arquivo conter uma informação que diz para o sistema operacional qual programa sabe executá-lo.

;-)

@@10
Consolidando o seu conhecimento

Chegou a hora de você seguir todos os passos realizados por mim durante esta aula. Caso já tenha feito, excelente. Se ainda não, é importante que você execute o que foi visto nos vídeos para poder continuar com a próxima aula.
1) Na linha de comando, dentro do seu projeto, execute o comando abaixo para baixar o PHPUnit:

composer require --dev phpunit/phpunitCOPIAR CÓDIGO
2) De volta no seu editor de código crie uma nova pasta chamada tests. Dentro dela adicione um novo arquivo TestBuscadorDeCursos.php com o conteúdo abaixo:

<?php

namespace Alura\BuscadorDeCursos\Tests;

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

class TestBuscadorDeCursos extends TestCase
{
    private $httpClientMock;
    private $url = 'url-teste';

    protected function setUp(): void
    {
        $html = <<<FIM
        <html>
            <body>
                <span class="card-curso__nome">Curso Teste 1</span>
                <span class="card-curso__nome">Curso Teste 2</span>
                <span class="card-curso__nome">Curso Teste 3</span>
            </body>
        </html>
        FIM;


        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this
            ->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->url)
            ->willReturn($response);

        $this->httpClientMock = $httpClient;
    }

    public function testBuscadorDeveRetornarCursos()
    {
        $crawler = new Crawler();
        $buscador = new Buscador($this->httpClientMock, $crawler);
        $cursos = $buscador->buscar($this->url);

        $this->assertCount(3, $cursos);
        $this->assertEquals('Curso Teste 1', $cursos[0]);
        $this->assertEquals('Curso Teste 2', $cursos[1]);
        $this->assertEquals('Curso Teste 3', $cursos[2]);
    }
}COPIAR CÓDIGO
4) Execute o teste na linha de comando:

vendor\bin\phpunit tests\TestBuscadorDeCursos.phpCOPIAR CÓDIGO
3) Agora vamos validar se nosso código segue alguns padrões definidos no PSR. Para tal, vamos usar o PHP Codesniffer. Na linha de comando execute:

composer require --dev squizlabs/php_codesniffer
composer require --dev phan/phan
COPIAR CÓDIGO
4) Uma vez baixado verifique o padrão PSR12 executando na linha de comando:

vendor\bin\phpcs --standard=PSR12 src\Buscador.phpCOPIAR CÓDIGO
Tente corrigir os erros relacionado com a estrutura PHP e chame novamente phpcs.

5) Vamos testar a ferramenta phan para encontrar possíveis erros no código.

Primeiramente crie uma nova pasta .phan na raiz do seu projeto.

6) Na pasta .phan, crie um novo arquivo config.php com as configurações abaixo:

<?php

return [
    "target_php_version" => '7.3',
    'directory_list' => [
        'src',
        'vendor/symfony/dom-crawler',
        'vendor/guzzlehttp/guzzle',
        'vendor/psr/http-message'
    ],
    "exclude_analysis_directory_list" => [
        'vendor/'
    ],
    'plugins' => [
        'AlwaysReturnPlugin',
        'UnreachableCodePlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
    ],
];COPIAR CÓDIGO
6) Agora execute o phan na linha de comando:

vendor\bin\phan --allow-polyfill-parserCOPIAR CÓDIGO

Continue com os seus estudos, e se houver dúvidas, não hesite em recorrer ao nosso fórum!

@@11
O que aprendemos?

Nesta aula, aprendemos: Nessa aula falamos sobre dependências e ferramentas que não são utilizadas em produção e sim no ambiente de desenvolvimento:
Através do flag --dev definimos que uma dependência não faz parte do ambiente de produção
Caso desejarmos baixar as dependências de "produção" apenas podemos usar o flag no-dev
Arquivos executáveis fornecidos por componentes instalados pelo composer ficam na pasta vendor/bin
Conhecemos três ferramentas do mundo PHP:
phpunit para rodar testes;
phpcs para verificar padrões de código;
phan para executar uma análise estática da sintaxe do nosso código.

#### 04/02/2024

@05-Automatizando processos com Scripts

@@01
Projeto da aula anterior

Caso queira, você pode baixar aqui o projeto do curso no ponto em que paramos na aula anterior.

https://caelum-online-public.s3.amazonaws.com/1255-composer/05/1250-composer-aula4.zip

@@02
Scripts no JSON

Nesse capítulo continuaremos falando sobre os scripts que podem ser executados na linha de comando, mas agora de forma um pouco diferente. Começaremos pelo teste que implementamos com o PHPUnit. Para executá-la, precisamos chamar vendor\bin\phpunit tests\TestBuscadorDeCursos.php, um comando bastante extenso e que pode facilmente ocasionar um erro. Além disso, poderíamos ter outras configurações envolvidas, dificultando ainda mais lembrarmos como é feita a execução manualmente.
A ideia é fazermos com que a execução do comando composer test chame todo o código que citamos acima. Isso não só é possível, como também é bem simples. No arquivo composer.json, podemos adicionar uma seção scripts. Nela, criaremos um script test cujo valor será aquilo que queremos executar. Porém, temos ainda outra vantagem: não precisamos digitar o diretório vendor\bin, pois, se o comando informado não existir no sistema operacional, o Composer irá buscá-lo diretamente nessa pasta.

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos.php"

}COPIAR CÓDIGO
Feito isso, se executarmos composer test no terminal, o TestBuscadorDeCursos.php será executado corretamente. Vamos repetir esse processo, dessa vez criando um script cs para phpcs --standard=PSR12, que é o nosso PHP CodeSniffer.

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos",
    "cs": "phpcs --standard=PSR12"
}COPIAR CÓDIGO
Feito isso, voltaremos ao terminal e executaremos composer csx. Como o comando está errado, mas é semelhante ao correto, o Composer nos sugerirá cs. Já se executarmos composer cs, receberemos uma mensagem de erro diferente:

Script phpcs --standard=PSR12 handling the cs event returned with error code 3COPIAR CÓDIGO
Isso acontece pois esquecemos de adicionar o diretório a ser verificado. Vamos corrigir isso:

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos",
    "cs": "phpcs --standard=PSR12 src/"
}COPIAR CÓDIGO
Feita a correção, a execução ocorrerá corretamente, nos retornando os erros de formatação no nosso código. Continuando, adicionaremos também um script phan com o comando vendor\bin\phan -allow-polyfill-parser. Assim, poderemos rodar composer phan no terminal, e ele nos retornará os erros encontrados no nosso projeto.

Já entendemos a definição de scripts no Composer, mas ela é mais poderosa do que isso. Imagine que tenhamos um pipeline de deploy e sempre que vamos enviar um código para produção, queremos executar uma sequência de scripts, por exemplo verificando que nosso código não tem erro, garantindo que ele está dentro do padrão definido e, em caso positivo, rodar os testes. Por fim, somente se tudo isso for executado corretamente, queremos que o commit seja feito, e do contrário teremos um erro.

Ou seja, a ideia é termos somente um comando executando vários scripts do Composer. Conversaremos sobre isso no próximo vídeo.

@@03
Pra que scripts?

Nesta aula nós configuramos os comandos da última aula para serem executados através do comando composer.
Que vantagem isso nos traz?

Isso faz com que os comandos consumam menos memória pois o Composer é muito leve.
 
Alternativa correta
Podemos digitar um comando simples para executar uma tarefa complexa, onde vários parâmetros podem ser informados
 
Alternativa correta! Separando um script no composer.json podemos dar um nome simples para um comando grande. Isso evita que esqueçamos algum parâmetro importante de algum comando. :-D
Alternativa correta
Isso faz com que os erros de algum comando não sejam exibidos na tela

@@04
Compondo Scripts

Já conseguimos um resultado interessante com os nossos scripts, e nosso objetivo agora é termos um único script que rode os três que criamos anteriormente. Felizmente, isso não é difícil. Teremos, em composer.json, um comando check que executará uma lista [] de scripts: primeiro o phan, depois o cs e por fim o test.
"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos.php",
    "cs": "phpcs --standard=PSR12 src/",
    "phan": "phan --allow-polyfill-parser",
    "check": [
        "phan",
        "cs",
        "test"
    ]
}COPIAR CÓDIGO
Se executarmos dessa forma, receberemos um erro. Isso acontece foi o comando check não invocou o script phan, mas sim o comando phan. Se removermos o phan da lista, ele tentará executar um comando chamado cs, que não existe. O que queremos, ao invés disso, é fazer referência aos scrips já existentes. Para isso, basta adicionarmos um @ na frente de cada script na lista:

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos.php",
    "cs": "phpcs --standard=PSR12 src/",
    "phan": "phan --allow-polyfill-parser",
    "check": [
        "@phan",
        "@cs",
        "@test"
    ]
}COPIAR CÓDIGO
Ao executarmos o composer check, os scripts serão executados com sucesso, na ordem que definimos. Se criarmos algum erro no código, a execução terminará no primeiro passo, que é o phan. Se não existir erro algum, mas nosso estilo de código não estiver passando, os testes não serão executados, terminando de rodar no cs. Por fim, podemos criar um $this->fail() no TestBuscadorDeCursos.php para fazermos o código falhar, evidenciando, no terminal, que ele também está sendo executado. Com isso, temos uma composição de scripts.

Outra coisa interessante é que, ao pegarmos um projeto já existente do Composer, podemos rodar composer list, e todos os comandos existentes serão exibidos - inclusive os scripts personalizados, no nosso caso cs, phan, test e check. Vamos analisar as descrições deles:

check                Runs the check script as defined in composer.json.
cs                   Runs the cs script as defined in composer.json.
phan                 Runs the phan script as defined in composer.json.
test                 Runs the test script as defined in composer.json.COPIAR CÓDIGO
Podemos adicionar descrições personalizadas para nossos scripts. Faremos isso para o check, que no momento é o mais importante. Para isso, basta adicionarmos, no composer.json, uma nova chave scripts-descriptions. Nela, definiremos o nome do script, nesse caso check, e uma descrição, que será "Roda as verificações do código. PHAN, PHPCS e PHPUNIT".

"scripts-descriptions": {
    "check": "Roda as verificações do código. PHAN, PHPCS e PHPUNIT"
}COPIAR CÓDIGO
Se executarmos o composer list novamente, teremos, dentre os scrips listados:

check                Roda as verificações do código. PHAN, PHPCS e PHPUNITCOPIAR CÓDIGO
Assim, além de conseguirmos executar alguns scripts, temos a possibilidade de criar uma composição de scripts e definir descrições para ela. Mas será que é possível criarmos um script do Composer para alguma ferramenta que não tenha sido baixada por meio dele? Conversaremos sobre isso no próximo vídeo.

@@05
Scripts compostos

Vimos que podemos, além de definir scripts, compor vários scripts e chamá-los através de um único comando.
Que tipo de vantagem isso nos traz?

Possibilidade de refatorar nosso código
 
Alternativa correta
Possibilidade de configurarmos um processo de build com um único comando
 
Alternativa correta! Desta forma, com um único comando podemos configurar para que nosso projeto seja testado, verificado e quaisquer outras tarefas necessárias para colocar nosso código em produção. E o melhor: com apenas um comando!
Alternativa correta
Velocidade na execução dos comandos

@@06
Mais sobre scripts

Já fizemos bastante coisa interessante com os nossos scripts do Composer, mas surgiu uma dúvida: será que só é possível executar comandos que o próprio Composer baixou? Na verdade não, e qualquer comando do sistema operacional pode ser executado por meio dele. Vamos a um exemplo.
Nosso instrutor é usuário de Linux e, por estar habituado a esse ambiente, de vez em quando tenta executar um ls no Windows, que equivale ao dir desse sistema operacional. Para resolver essa situação, é possível criar um script ls que executa o comando dir:

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos.php",
    "cs": "phpcs --standard=PSR12 src/",
    "phan": "phan --allow-polyfill-parser",
    "ls": "dir",
    "check": [
        "@phan",
        "@cs",
        "@test"
    ]
},COPIAR CÓDIGO
Com isso, se executarmos composer ls no terminal, o diretório atual será listado. Assim, aprendemos que é possível executar qualquer comando no Terminal utilizando o Composer. Como outro exemplo, imagine que temos no projeto um diretório "cache" e que estamos no Linux (pois nosso instrutor esqueceu, momentaneamente, qual o comando para remover um diretório no Windows). Nesse cenário, poderíamos criar um comando clear-cache que executaria o script rm -rf cache. Assim, mesmo que a forma de armazenar cache mude ou que o script a ser executado mude, a chamada sempre será composer clear-cache.

Na verdade, o comando clear-cache já existe no Composer, e limpa o cache de dependências já baixadas. Sendo assim, alteraremos o comando criado para limpa-cache e o comando a ser executado para del cache.

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos.php",
    "cs": "phpcs --standard=PSR12 src/",
    "phan": "phan --allow-polyfill-parser",
    "ls": "dir",,
    "limpa-cache": "del cache",
    "check": [
        "@phan",
        "@cs",
        "@test"
    ]
},COPIAR CÓDIGO
Se rodarmos composer limpa-cache no terminal, o del cache será executado, pedindo uma informação. Assim, já temos uma noção de quão poderosa essa funcionalidade do Composer pode ser. Outra funcionalidade é a possibilidade de executarmos um código PHP que criamos, por exemplo o Buscador.php. Se tivéssemos, por exemplo, uma classe que exibe os $cursos retornados na tela, poderíamos ter um comando exibe-cursos que chamaria o script Namespace\\Da\\Classe:metodo, e tal metodo seria executado na Classe referenciada.

Continuando a explorar essas possibilidades, seria interessante que o cache da nossa aplicação fosse limpo toda vez que rodássemos o composer update, de modo a eliminar qualquer preocupação em relação às alterações quando instalarmos as novas dependências. Seria ainda melhor se sempre que atualizássemos as dependências, rodássemos o script test. Já sabemos que é possível executar comandos trazidos pelo Composer ou do sistema operacional, além de compor comandos e executar códigos em PHP, mas isso tudo diretamente pela linha de comando. Porém, também é possível fazer tudo isso de forma automática, e esse será o tema do nosso próximo vídeo.

@@07
O que posso executar?

Neste vídeo tivemos uma compreensão maior sobre o que pode ser executado através de um script do Composer
O que o Composer consegue executar através de seus scripts?

Códigos em outra linguagem
 
Alternativa correta
Códigos em PHP
 
Alternativa correta! Podemos, por exemplo, executar um método de determinada classe a partir de um script do Composer
Alternativa correta
Comandos do sistema operacional
 
Alternativa correta! Conseguimos executar qualquer comando do sistema operacional através dos scripts do Composer

@@08
Eventos e scripts

Ao longo desse capítulo, não só criamos scripts personalizados como também produzimos um comando que roda todos os scripts do nosso pipeline - nesse caso, apenas três ferramentas relativamente famosas, mas poderíamos ter várias outras com as mais diversas configurações. Agora, nosso objetivo é que, sempre que rodarmos o composer update, o Composer busque as atualizações, instale-as (ou não) e execute o nosso script test que invoca o PHPUnit.
Na página sobre scripts do Composer, podemos encontrar a seção "Event names", que se refere a eventos e contém uma lista com diversos deles, como pre-install-cmd, que ocorre antes do composer install e na presença de um arquivo composer.lock, ou seja, da primeira vez que rodamos o composer install no projeto. Sendo assim, o script que for definido com esse nome será executado antes de trazermos as bibliotecas com as quais vamos trabalhar. Depois da instalação, é chamado o script definido com o nome post-install-cmd.

Supondo que já tenhamos um composer.lock e executamos um composer update, os scripts que serão chamados serão pre-update-cmd, antes do update ser executado, e post-update-cmd depois da execução desse update. Além disso, o pre-update-cmd também é chamado quando rodamos o composer install sem que um arquivo composer.lock esteja presente, antes da execução, e o post-update-cmd nas mesmas condições, mas após a execução.

Além desses, existem vários outros comandos que podemos chamar. Para que o funcionamento deles fique claro, vamos definir, na seção scripts do nosso composer.json, um comando post-update-cmd para executarmos algo depois que o update for feito. Esse comando receberá uma lista de scripts contendo somente o nosso @test, mas, se quiséssemos, poderíamos incluir vários outros.

"scripts": {
    "test": "phpunit tests\\TestBuscadorDeCursos.php",
    "cs": "phpcs --standard=PSR12 src/",
    "phan": "phan --allow-polyfill-parser",
    "ls": "dir",
    "limpa-cache": "del cache",
    "check": [
        "@phan",
        "@cs",
        "@test"
    ],
    "post-update-cmd": [
        "@test"
    ]
},COPIAR CÓDIGO
Após buscar as atualizações das dependências, o Composer rodará automaticamente o PHPUnit. Isso nos abre diversas possibilidades, como limpar o cache ou fazer diversas outras tarefas quando executamos uma instalação ou atualização.

Na página do Composer encontramos alguns exemplos de como definir scripts desse tipo, por exemplo definir um ou vários scripts a partir de uma classe chamando um método, definir de forma vista, chamando uma classe e um comando, e assim por diante.

{
    "scripts": {
        "post-update-cmd": "MyVendor\\MyClass::postUpdate",
        "post-package-install": [
            "MyVendor\\MyClass::postPackageInstall"
        ],
        "post-install-cmd": [
            "MyVendor\\MyClass::warmCache",
            "phpunit -c app/"
        ],
        "post-autoload-dump": [
            "MyVendor\\MyClass::postAutoloadDump"
        ],
        "post-create-project-cmd": [
            "php -r \"copy('config/local-example.php', 'config/local.php');\""
        ]
    }
}COPIAR CÓDIGO
Em algumas situações isso pode ser muito útil. Podemos ter um servidor de integração contínua - de forma resumida, um servidor que fica rodando para executar nossas tarefas de build/deploy, verificando se nosso código está pronto para a produção. Nesse caso, ao mandarmos nosso código para esse servidor, tudo que teremos que colocar na configuração é a execução de um script definido no Composer.

Com tudo isso, aprendemos o poder que o Composer tem de automatizar tarefas por meio de eventos, scripts, composições, entre outras possibilidades. Porém, ainda está faltando algo. Já desenvolvemos e testamos o nosso código utilizando ferramentas que garantem que ele esteja funcionando, mas como vamos disponibilizá-lo para que outras pessoas possam utilizá-lo? Conversaremos sobre isso na próxima aula.

https://getcomposer.org/doc/articles/scripts.md

@@09
Para saber mais: Eventos

Conhecemos e entendemos um pouco dos eventos que o Composer nos fornece. Com eles, podemos executar scripts em momentos específicos como, por exemplo, ao instalar ou atualizar um pacote.
Para a lista completa de quais os eventos disponíveis, acesse https://getcomposer.org/doc/articles/scripts.md

https://getcomposer.org/doc/articles/scripts.md

@@10
Consolidando o seu conhecimento

Chegou a hora de você seguir todos os passos realizados por mim durante esta aula. Caso já tenha feito, excelente. Se ainda não, é importante que você execute o que foi visto nos vídeos para poder continuar com a próxima aula.
1) No seu editor de código, abra o composer.json e adicione o final, mas dentro das chaves principais:

"scripts": {
        "test": "phpunit tests\\TestBuscadorDeCursos.php",
        "cs": "phpcs --standard=PSR12 src/",
        "phan": "phan --allow-polyfill-parser",
        "check": [
            "@phan",
            "@cs",
            "@test"
        ],
        "post-update-cmd": [
            "@test"
        ]
    },
    "scripts-descriptions": {
        "check": "Roda as verificações do código. PHAN, PHPCS e PHPUNIT"
    }COPIAR CÓDIGO
2) Na linha de comando, na raiz do seu projeto, chame um script específico:

composer phanCOPIAR CÓDIGO
Também tente chamar composer cs e composer test.

3) Ainda na linha de comando, execute a sequência de scripts:

composer check

Continue com os seus estudos, e se houver dúvidas, não hesite em recorrer ao nosso fórum!

@@11
O que aprendemos?

Nesta aula, aprendemos:
scripts são definidos dentro do composer.json;
scripts podem definir comandos que chamam ferramentas instaladas pelo Composer;
scripts podem chamar comandos do sistema operacional;
scripts podem chamar códigos PHP;
scripts podem ser associados ao evento.

#### 05/02/2024

@06-Publicando um pacote

@@01
Projeto da aula anterior

Caso queira, você pode baixar aqui o projeto do curso no ponto em que paramos na aula anterior.

https://caelum-online-public.s3.amazonaws.com/1255-composer/06/1250-composer-aula5.zip

@@02
Versionamento

Estamos no capítulo final do nosso treinamento de Composer! Já temos uma aplicação funcionando e utilizando dependências, aprendemos a gerar o autoload para nossas classes (além daquelas gerenciadas pelo Composer) e a trabalhar com ferramentas na linha de comando, além de estudarmos bastante sobre scripts. Porém, ainda faltam disponibilizarmos nosso componente para que ele seja utilizado por outras pessoas.
Antes disso, vamos conversar sobre versionamento, ou seja, a versão de cada um dos pacotes, algo que citamos anteriormente em relação aos caracteres ^ ao lado das versões das nossas dependências:

"require": {
    "guzzlehttp/guzzle": "^6.4",
    "symfony/dom-crawler": "^4.2",
    "symfony/css-selector": "^4.2"
},COPIAR CÓDIGO
Como o Composer está diretamente ligado com sistemas de controle de versão, como o Git, às vezes podemos acabar confundindo a versão de um pacote no Composer com o versionamento de cada commit no Git. No Composer, cada versão de um pacote, como as que definimos no composer.json, são informadas por meio de tags. Ou seja, podemos criar tags com nomes específicos que o Composer entenderá como a versão do pacote.

Como exemplo, usaremos a nossa própria aplicação, cuja pasta ("buscador-cursos-alura") já foi definida pelo nosso instrutor como um projeto do Git. Se você quiser fazer isso, mas não entende muito bem sobre o Git, não tem problema. Para começar, precisamos rodar o comando git init no diretório do projeto no terminal. Após isso, precisamos ter um arquiivo .gitignore na raiz do projeto, dessa forma:

.idea/
vendor/COPIAR CÓDIGO
Isso porque a pasta "vendor" não pode ser commitada, já que ela é instalada pelo Composer. A linha ".idea/" é referente à IDE do PhpStorm, e não é necessária em outras IDEs. O arquivo .gitignore serve justamente para que o Git ignore - ou seja, não gerencie - as entradas listadas dele.

Feitas essas configurações, chamaremos git add . no terminal e, em seguidda, git commit -m "Primeiro commit", onde "Primeiro commit" é uma mensagem personalizada. A partir desse ponto, foi definida a primeira versão do nosso projeto. Prosseguindo, podemos chamar git tag -a v1.0.0. O Composer consegue entender se chamarmos apenas git tag -a v1.0.0, mas utilizando a letra v na frente conseguimos evitar alguns problemas de compatibilidade com outros programas que podem ser essas tags.

Note que o número da versão não é aleatório! Existe um esquema de versionamento chamado SemVer, ou "Semantic Versioning". Ele define que o primeiro número é a versão princial ("MAJOR version" ou "versão maior"), que indica quebra de compatibilidade. Por exemplo, imagine que nosso buscador deixa de retornar um array e passa a retornar um Generator. Nesse caso, trocaríamos a versão de v1.0.0 para v2.0.0, afinal temos uma quebra de compatibilidade e quem está usando nosso sistema/pacote precisa saber disso.

Se adicionarmos alguma compatibilidade sem quebrar nada, utilizamos o segundo número, referente à "MINOR version" (ou "versão menor"). Nesse caso, adicionamos alguma funcionalidade, por exemplo, mas tudo que existia continua funcionando. Já quando temos mudanças menores ou correções de bugs, o terceiro e último número, conhecido como "PATCH version" ("versão de correção"), é utilizado. Assim, teremos:

2.0.0
MAJOR.MINOR.PATCHCOPIAR CÓDIGO
Tendo entendido o conceito de versionamento semântico, criaremos a nossa tag. Após pressionarmos "Enter" no terminal, poderemos definir uma mensagem para ela, como "Primeira versão do pacote". Terminada essa parte, ainda faltam alguns passos para conseguirmos instalar o pacote com o Composer. Antes de prosseguirmos, vamos conversar sobre os caracteres que citamos anteriormente, conhecidos como "constraints de versionamento".

Na documentação do Composer encontramos uma explicação mais aprofundada sobre o tema. É possível, por exemplo, definirmos as versões exatas dos pacotes que vamos baixar, como fizemos abaixo:

"symfony/dom-crawler":"4.2.7"COPIAR CÓDIGO
Se quisermos, podemos utilizar o hífen (-) para determinarmos um range (uma "faixa") de versões, como 1.0 - 2.0 - ou seja, da versão 1.0 até a versão 2.0. O * é utilizado para informar que, no local onde ele é inserido, qualquer valor será válido, por exemplo em 4.2.*, mostrando que queremos qualquer versão que comece com 4.2.

O til, como em ~1.2, é equivalente à sintaxe >=1.2 <2.0.0, ou seja, maior ou igual a 1.2 e menor, não incluindo, que 2.0.0. Podemos variar na especificação da versão, por exemplo passando ~1.2.3. Nesse caso, a sintaxe correspondente seria >=1.2.3 <1.3.0.

Por padrão, o Composer têm adicionado um circunflexo (^) nos nossos códigos. Ele é utilizado para informar que queremos baixar a versão especificada até a próxima "major version", ou seja, até o momento em que a compatibilidade quebra, o que é exatamente o que queríamos no nosso projeto: pegar, por exemplo, o dom-crawler da versão 4.2 até a versão 5.0. Assim, garantimos que nossas dependências estarão sempre atualizadas e sem nenhuma quebra.

Entendidos esses conceitos de como criar uma versão do nosso pacote utilizando tags do sistema de versionamento de arquivos e como funcionam os requirimentos de versão, é hora de publicarmos o nosso componente para que outras pessoas usem. Começarem a entrar em detalhes nesse assunto no próximo vídeo.

https://getcomposer.org/doc/articles/versions.md#exact-version-constraint

@@03
Para saber mais: Correção

Próximo ao momento 7:05, eu cito que a constraint ^4.2 me permitira usar "até a versão 5" do pacote. Esse "até" citado por mim foi no sentido de <5.0, ou seja, a versão 5 não estaria incluída. Ou seja, ^4.2 significa >4.2.0 <5.0.0.
Se quiser conferir cada operador possível na hora de definir as versões, você pode conferir o seguinte link da documentação: https://getcomposer.org/doc/articles/versions.md

https://getcomposer.org/doc/articles/versions.md

@@04
Para saber mais: GIT

Para disponibilizarmos nosso projeto de forma que terceiros possam baixá-lo e utilizá-lo, precisamos armazená-lo em algum repositório público.
O Composer consegue acessar diversos repositórios e para entender quais as versões disponíveis de cada pacote ele vê as tags do GIT em seu respectivo repositório.

O foco deste treinamento não é GIT, por isso não entraremos em detalhes, mas esta ferramenta é ESSENCIAL para qualquer pessoa que pretenda desenvolver qualquer coisa, utilizando qualquer tecnologia.

Temos treinamentos de GIT aqui na Alura caso tenha interesse em se aprofundar no assunto.

As tags devem ser organizadas seguindo o “Semantic Versioning”

https://semver.org/

@@05
Logando no Packagist

É hora de publicarmos o nosso pacote. Para isso, precisaremos de uma conta no GitHub. Se você ainda não tiver uma, basta acessar o site e criá-la. Feito isso, iremos criar um repositório para o qual enviaremos o nosso código, e é nesse repositório onde o Packagist buscará o componente.
Usaremos a opção "New Repository" e colocaremos o nome "curso-composer-alura-buscador-cursos" e a descrição "Projeto utilizado no curso de composer". É um nome comprido e, se você preferir, pode escolher outro que faça mais sentido para você. Deixaremos a opção "Public" marcada e clicaremos em "Create Repository". Com o repositório criado, o próprio Github nos informará como podemos fazer para adicioná-lo de modo a enviarmos o código. Nessa página, usaremos a opção HTTPS. e copiaremos o código disponibilizado mais abaixo:

git remote add origin https://github.com/CViniciusSDias/curso-composer-alura-buscador-cursos.gitCOPIAR CÓDIGO
Rodaremos esse código no terminal e, em seguida, chamaremos git push origin master. Uma janela do GitHub se abrirá pedindo login e senha, Após preenchermos, nosso código será enviado para o repositório do GitHub na branch "master". Após esse envio, faremos outro git push, dessa vez da nossa tag:

git push origin v1.0.0COPIAR CÓDIGO
Se atualizarmo a URL do GitHub no navegador, nosso código terá subido com sucesso, com todas as configurações do Composer que fizemos anteriormente. Voltaremos então ao site https://packagist.org e faremos login utilizando nossa própria conta do GitHub. Clicaremos em "Submit" e adicionaremos a URL do GitHub do nosso projeto, clicando em "Check" logo em seguida. Como retorno, o site nos mostrará que o nome do pacote será "cviniciussdias/buscador-cursos", onde "cviniciussdias" será substituído pelo seu nome de usuário. Para confirmarmos, clicaremos no botão "Submit">

O carregamento do pacote será feito em uma nova página, na qual teremos a mensagem "This package has not been crawled yet, some information is missing", nos avisando que algumas informações do pacote ainda estão faltando pois ele não foi totalmente acessado ainda. Para que a documentação do projeto apareça na página, teríamos que incluir no projeto um arquivo readme.md, mas não nos preocuparemos com isso agora.

No próximo vídeo verificaremos se é possível baixar o nosso pacote.

@@06
Baixando nosso pacote

Já é possível vermos nosso projeto no ar, listado no Packagist, o que é bem emocionante. Ainda temos um aviso informando que não fornecemos nenhuma licença para o projeto, mas a princípio isso não deve ser um problema. Para garantirmos que tudo está funcionando como deveria, criaremos um novo projeto no PhpStorm, que chamaremos de "projeto-2".
No terminal, acessaremos a pasta do projeto e chamaremos composer require cviniciussdias/buscador-cursos - claro, substituindo o nome do pacote pelo que você criou. Feito isso, o Composer irá baixar o buscador-cursos e todas as dependências que ele precisa para funcionar, ou seja, os componentes do Symfony, o GuzzleHttp, entre outros. Além disso, as dependências de desenvolvimento, como o PHPUnit e o Phan, não foram baixadas.

De volta ao PhpStorm, criaremos um arquivo teste.php no qual faremos o require do autoload e pegaremos os cursos por meio de um $buscador. Para utilizá-lo, precisaremos também de uma instância $client para a qual passaremos a base_uri, que é a URL da Alura, e um $crawler. Faremos também as importações das dependências necessárias.

<?php

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();
$buscador = new Buscador($client, $crawler);COPIAR CÓDIGO
Em seguida, pegaremos os $cursos chamando o método buscar() e passando como parâmetro a URL /cursos-online-programacao/php. Faremos então um foreach iterando por cada $curso e, por fim, os exibiremos na tela com um echo.

<?php

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar('/cursos-online-programacao/php');

foreach ($cursos as $curso) {
    echo $curso . PHP_EOL;
}COPIAR CÓDIGO
No terminal, executaremos php teste.php para testarmos. Como esperado, os cursos da Alura serão listados com sucesso. Ou seja, em um novo projeto, conseguimos utilizar o código que disponibilizados no Packagist. Porém, ainda podemos fazer melhorias. No projeto inicial buscador-cursos, temos um arquivo chamado buscar-cursos.php que executa o nosso código e exibe os cursos na tela. Queremos poder acessá-lo com vendor\bin\buscar-cursos.php, como fazíamos com algumas ferramentas do projeto anterior. Como podemos fornecer nosso arquivo executável diretamente na pasta "bin" do Composer? Esse será o tema do nosso próximo vídeo.

@@07
Bin (Bin)

Já disponibilizados o nosso componente para ser baixado e conseguimos instalá-lo em um novo projeto. Agora é hora de fazermos algumas alterações, e a primeira delas é adicionarmos uma licença de modo a remover a mensagem de erro que é exibida no Packagist. Como esse projeto não será continuado, adicionaremos uma licença qualquer. Se você quiser trabalhar em um projeto que será mantido, é recomendado pesquisar sobre as licenças de software disponíveis.
Em composer.json, adicionaremos a configuração licence com o valor GPL-3.0.

"license": "GPL-3.0"COPIAR CÓDIGO
Além disso, adicionaremos um arquivo README.md. Esse é um arquivo padrão que tanto o Github quanto o Packagist leem quando acessamos o repositório, e é nele que armazenaremos a documentação do nosso componente. Por enquanto vamos preenchê-lo com um texto qualquer.

# Documentação do componente

Este componente é SUPIMPA!!COPIAR CÓDIGO
Voltando ao assunto principal do vídeo, queremos informar que o arquivo buscar-cursos pode ser utilizado como um arquivo binário, permitindo a execução de vendor\bin buscar-cursos.php no terminal. Para isso, poderíamos acessar o composer.json e incluir como arquivo binário (bin), dentre vários possíveis, o buscar-cursos.php.

"license": "GPL-3.0",
"bin": ["buscar-cursos.php"]COPIAR CÓDIGO
Feito isso, quando atualizarmos nosso projeto, será possível acessarmos o terminal e chamarmos php vendor\bin\buscar-cursos.php. Porém, queremos chamar somene vendor\bin\buscar-cursos.php. Para que isso funcione, teremos que adicionar um #!/usr/bin/env php no início do arquivo buscar-cursos.php.

#!/usr/bin/env php
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
    echo exibeMensagem($curso);
}COPIAR CÓDIGO
O código #! indica que estamos informando que programa lerá esse arquivo, e /usr/bin/env php explicita que o arquivo deverá ser lido pelo PHP. Mas, mudando um pouco de assunto, você consegue perceber dois erros no nosso buscador-cursos.php? O primeiro deles é que estamos fazendo um echo na função exibeMensagem(), que já exibe um conteúdo na tela. Resolveremos isso simplesmente removendo o echo. O segundo erro é que o composer.json não tem mais o files, onde incluíamos o arquivo functions.php. Vamos corrigir adicionando o files no nosso autoload.

"autoload" : {
    "files": ["./functions.php"],
    "psr-4" : {
        "Alura\\BuscadorDeCursos\\": "src/"
    }
},COPIAR CÓDIGO
Feitas essas modificações, vamos commitar o projeto no Github para atualizarmos o componente. No terminal, voltaremos ao projeto "buscador-cursos-alura" e executaremos git status para exibir as alterações que fizemos. Em seguida, faremos git add . e git commit -m "Adicionando arquivo bin", onde o conteúdo entre aspas é uma mensagem personalizada sobre esse commit.

Após a execução, criaremos uma nova tag com git tag -a v1.1.0. Mas por que aumentaremos o segundo número (a "minor version") e não o terceiro? Faremos isso pois estamos disponibilizando um novo arquivo binário para o usuário, ou seja, uma nova funcionalidade, mas não corrigimos bugs ou modificamos um comportamento já existente (o que alteraria o "patch" e a "major version", respectivamente). Após pressionarmos "Enter", incluiremos o texto "Arquivo binário disponibilizado" e, por fim, faremos git push origin v1.1.0.

No Packagist, atualizaremos a página e perceberemos que a mensagem "There is no license information available for the latest version of this package" deixará de ser exibida. Abaixo, a nova versão, v1.1.0, terá sido incluída com sucesso. Porém, o arquivo README.md não estará disponível, pois não subimos o commit na master.

Para exibirmos com sucesso a documentação, voltaremos ao terminal e executaremos git push origin master. Ao atualizarmos a página do Packagist novamente, o texto do RADME aparecerá na tela. Voltaremos então ao "projeto-2" e executaremos composer update para baixarmos a atualização.

Feito isso, ao executarmos vendor\bin\cursos-cursos.php, o Windows tentará abrir o arquivo sugerindo alguns programas. Já se executarmos php vendor\bin\cursos-cursos.php, teremos um erro. Voltando ao nosso projeto, teremos dois arquivos na pasta "bin": buscar-cursos.php e buscar-cursos.php.bat, algo que foi feito pelo Composer. Se rodarmos vendor\bin\buscar-cursos.php.bat, conseguiremos listar os cursos com sucesso. Legal, não é? No próximo vídeo recapitularemos tudo o que fizemos ao longo do treinamento!

@@08
Nosso arquivo executável

Temos agora um pacote que pode ser baixado e utilizado por terceiros. Mas, e nosso arquivo executável? Nós temos um arquivo pronto para ser executado pela linha de comando.
O que é necessário fazer para que nosso arquivo seja disponibilizado na pasta vendor/bin?

Selecione uma alternativa

Disponibilizá-lo separadamente
 
Alternativa correta
Informar o caminho do arquivo na entrada bin do nosso composer.json
 
Alternativa correta! Com isso o Composer transformará nosso arquivo em executável e o disponibilizará nas pasta vendor/bin de quem utilizar nosso componente
Alternativa correta
Copiá-lo para a pasta bin no nosso projeto

@@09
Consolidando o seu conhecimento

Chegou a hora de você seguir todos os passos realizados por mim durante esta aula. Caso já tenha feito, excelente. Se ainda não, é importante que você execute o que foi visto nos vídeos para poder continuar com os próximos cursos que tenham este como pré-requisito.
Esse exercício assume que você já tenha GIT instalado.

1) Abra um terminal, entre no seu projeto e inicialize o repositório:

git initCOPIAR CÓDIGO
2) No seu editor de código, abra o arquivo .gitignore que fica na raiz do projeto. Nele adicione a linha:

vendor/COPIAR CÓDIGO
Isso faz o GIT ignorar a pasta vendor.

3) De volta na linha de comando, comite os arquivos:

git commit -am "Primeiro commit da aplicação BuscaCursos"COPIAR CÓDIGO
4) Agora crie um tag com GIT:

git tag -a v1.0.0COPIAR CÓDIGO
5) Na sua conta de Github, crie um novo repositório (por exemplo, alura-curso-composer-buscador-cursos).

6) Na linha de comando, adicione o repositório remoto como origin e envie o código e tag:

git add origin https://github.com/<seu-usuario>/<seu-repositorio>.git
git push origin master
git push origin v1.0.0COPIAR CÓDIGO
7) Acesse o site https://packagist.org e se logue com sua conta do Github. Clique no botão Submit e cole o nome do repositório no campo de texto. Confirme o envio do formulário para adicionar o seu repositório no packagist.

8) Crie um novo projeto para testar o pacote no seu editor de código.

Nesse novo projeto, crie um novo arquivo teste.php com o conteúdo abaixo:

<?php

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();

$buscardor = new Buscador($client, $crawler);
$cursos = $buscardor->buscar('/cursos-online-programacao/php');

foreach ($cursos as $curso) {
    exibeMensagem($curso);
}COPIAR CÓDIGO
9) Na linha de comando, entre na pasta do seu novo projeto e digite para baixar as dependências:

Obs: Você pode copiar esse comando da página do seu projeto no packagist).

composer require <seu-nome>/<seu-package>COPIAR CÓDIGO
10) Uma vez baixado o pacote, execute na linha de comando:

php teste.php

Continue com os seus estudos, e se houver dúvidas, não hesite em recorrer ao nosso fórum!

@@10
O que aprendemos?

Nesta aula, aprendemos:
O composer entende as tags de versão de um repositório Git
O composer segue o conceito do versionamento semântico (MAJOR.MINOR.PATCH)
No composer.json podemos definir constraints (mais detalhes em https://getcomposer.org/doc/articles/versions.md)
Para distribuir e disponibilizar o seu projeto devemos:
Criar um repositório no Github;
Usar o packgist e associar com o repositório no Github.

https://getcomposer.org/doc/articles/versions.md

@@11
Projeto do curso

Caso queira, você pode baixar aqui o projeto completo implementado neste curso.

https://caelum-online-public.s3.amazonaws.com/1255-composer/06/1250-composer-aula6-final.zip

@@12
Conclusão

Parabéns por ter concluído esse treinamento de Composer! Nesse curso, apesar de não termos trabalhado incessantemente com códigos, apresentamos um conteúdo muito importante para o dia-a-dia de quem desenvolve com PHP. Começamos aprendendo o que é o Composer, um gerenciador de dependências por pacotes individuais, e como instalá-lo.
Trabalhamos então com o composer.json, um arquivo a partir do qual o gerenciador lê todos os dados e ações que precisa executar. Iniciamos com uma estrutura inicial bem pequena (e também com um projeto pequeno), que foi crescendo conforme adicionamos dependências, implementamos um autoload (que pode funcionar por meio das PSRs, de um classmap ou de files) e separamos as dependências de produção e de desenvolvimento.

Aprendemos também a configurar scripts com o Composer, tornando mais simples a execução de determinadas tarefas. Criamos, inclusive, scripts compostos - ou seja, scripts feitos a partir de outros scripts, algo que nos trouxe algumas facilidades, e adicionamos descrições aos scripts que criamos. Ainda nesse assunto, aprendemos o que são e como trabalhar com os eventos do Composer. Ao final, sempre que rodarmos o composer update no nosso projeto, os testes do PHPUnit serão executados.

Em seguida, publicamos o no Github e no Packagist, um repositório público de pacotes utilizado pelo Composer, aprendendo a definir a licença, a incluir a documentação e a utilizar o projeto disponibilizado em um novo projeto. Para isso, criamos um novo projeto, fizemos o require do nosso buscador e ainda atualizamos a dependência depois de termos feito algumas alterações. Por fim, disponibilizamos um arquio binário na pasta "bin" do projeto de modo a permitir que ele fosse executado diretamente do Terminal com somente um comando.

Os conhecimentos adquiridos nesse curso certamente são o suficiente para você gerenciar as dependências da sua aplicação e entender como o Composer trabalha, mas ainda existem diversos conteúdos para serem estudados! Por isso, recomendamos que você pesquise outros materiais, como vídeos e palestras na internet, além da própria documentação do Composer.

Esperamos que você tenha tirado bastante proveito do treinamento. Se tiver qualquer dúvida, abra um tópico no nosso fórum, onde os instrutores e toda a comunidade da Alura poderão lhe ajudar. Bons estudos e até a próxima!