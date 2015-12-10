# Teste para desenvolvedor PHP da UpLexis

<b>Controllers e views</b> <br />
A aplicação contém 2 controllers: O controller index e o consulta.

- O <b>indexController</b> é responsável por fazer a requisição ao Sintegra e salvar as informações no banco de dados através do módulo da 
tabela Sintegra. 
O usuário informa o CNPJ (se desejado também pode informar a proxy), a aplicação cria uma requisição ao Sintegra passando como 
parâmetro o CNPJ, as informações são recebidas em HTML e parseadas via expressão regular (os dados de relevância ficam em uma 
tabela html com a classe "valor"), é combinado os dados com um array que se transforma nas chaves e o atual o valor. O array é 
convertido em json, depois é tratado e salvo no banco de dados e é exibido na tela o JSON em um blockquote.

- O <b>consultaController</b> contém a action do web service que fica na classe <b>AutoLoad_RestServ</b> <i>(library/AutoLoad/RestServ.php)</i>
e a action que faz a conexão com o web service e exibe os dados.
O web-service exige como parâmetro o CNPJ e uma chave de autenticação gerada com a criptografia de uma combinação de letras e a data e 
hora atual, se essa chave estiver incorreta não é retornado os dados. A consulta retorna um JSON com os dados do Registro (se existirem).
A action de consulta recebe do usuário o CNPJ e faz a conexão com o web service passando o CNPJ e a chave gerada na hora, com os dados 
obtidos o JSON é decodificado e transformado em array, os dados são passados para a view que gera um tabela semelhante a exibida no site 
da Sintegra.

<br />
<br />

<b> Módulos e banco de dados</b>

- O módulo responsável por tratar da tabela com os dados da Sintegra tem uma função para salvar o registro quando consultado pelo usuário,
essa função verifica se já existe algum registro com o CNPJ informado, se não existir cria um novo, se já existir somente atualiza o registro
atual.

- O banco tem apenas uma tabela com os campos referente aos dados retornados pelo Sintegra, tem como chave primária um id para caso a 
aplicação precise requisitar algum registro via parâmetro GET poder exibir na URL somente o ID em vez do CNPJ (estética).

<br />
<br />

<b>Ferramentas utilizadas:</b>
- ZendFramework 1.7.4;
- JQuery
- Bootstrap

------------------------------------------------------------------------------------------------------

<b><i>
Davi Souto
davi.souto@gmail.com</i></b>
