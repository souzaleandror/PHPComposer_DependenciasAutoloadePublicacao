#### 01/02/2024

Curso de PHP Composer: Dependências, Autoload e Publicação

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