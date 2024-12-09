# SPRINT1_sitePetrobras
Felipe Sartori Costa <br>
Gabriel Ortolani <br>
Pedro Henrique <br>
Yago Moreira <br>

Como atividade educacional foi nos proposto por nossos docentes criar um site da empresa Petrobras capaz de facilitar a compra e venda de podrutos de facil maneira para tanto adicionar novos produtos e fornecedores assim auxiliando a venda dos produtos.
<a href="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/Codigos_Pags/index.html"><img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/img/foto-site/pagina-inicial.jpeg" height="350"></a>
<a href="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/Codigos_Pags/login.html"><img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/img/foto-site/login.jpeg" height="350"></a>
<a href="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/Codigos_Pags/pag-principal.html"><img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/img/foto-site/SistemaCadastro.jpeg" height="350"></a>
<a href="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/Codigos_Pags/Cadastro.html"><img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/img/foto-site/CadastroFornecedor.jpeg" height="350"></a>
<a href="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/Codigos_Pags/Cadastro2.html"><img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/img/foto-site/Cadastro-produto.jpeg" height="350"></a>
<a href="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/Codigos_Pags/Listagem.html"><img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/img/foto-site/Lista-produtos.jpeg" height="350"></a>
# Fluxograma do codigo
<img src="https://github.com/gabriel-ortolani/SPRINT1_sitePetrobras/blob/main/fluxograma.jpeg" height="350">
Ao acessar o site, você será direcionado para a página inicial, onde haverá a opção "Login". Ao selecioná-la, você será redirecionado para a página de autenticação. Após realizar o login, será encaminhado para o sistema de cadastro. Na seção de cadastro, ao escolher a opção "Cadastro de Fornecedor", será possível registrar-se como fornecedor. Concluído o registro, você será levado à página de cadastro de produtos, onde poderá adicionar seus produtos e associá-los ao fornecedor correspondente. Por fim, você será direcionado ao catálogo de produtos, onde estarão disponíveis todos os itens cadastrados, incluindo aqueles que você adicionou.
# Sprint 2
Na Sprint 2 criamos o baco de dados e desenvolvemos a modelagem de dados para auxilio na criação do banco e entendimento da ligação entre as tabelas(produtos, forneçedores e usuarios) e seus respectivos atributos(nome, email, telefone e etc)
<img height="300" alt="login" src="https://github.com/user-attachments/assets/e6e2a32d-b962-45c9-9dc7-2771b6be2422"><br>
Este e um de nossos dicionarios de dados contendo as informações dos funcionarios que poderão entrar no site tornando possivel cadastrar apenas funcionarios autorizados para a utilização do nosso site.<br>
<img height="350" src="https://github.com/user-attachments/assets/953bceeb-67d3-4047-b0bc-e0575a3d814c"><br>
Esse é nosso modelo conceitual que mostra todas as tabelas(CADASTRO DE FORNECEDOR, CADASTRO DE PRODUTO e LOGIN) e seus atributos que são indicados a qual tabale pertencem pelas setas e tambem demostra a ligação entre elas como por exemplo o 1 e o n das baledas de fornecedor e produto onde 1 significa que a tabela produto pode ter apenas um fornecedor porem a tabela fornecedor pode conter uma quantidade ilimitada produtos sinbolizado pela letra n.<br>
<img width="323" alt="Modelo Lógico" src="https://github.com/user-attachments/assets/87ef5322-f7b3-47f3-83a6-75d38ba8fd35"><br>
Este é nosso modelo lógico esse modelo busca mostrar as informações do modelo conceitual só que com mais detalhes como por exemplo as chaves primarias e estrangeiras, os tipos dos atributos e etc.<br>
<img height="350" src="https://github.com/user-attachments/assets/960ee4c6-df55-4418-846e-3ea4889e1521"><br>
Esse é o modelo físico que mostra o codigo usado no MySQL Workbensh para a crição do banco de dados e das tabelas com os atributos.


# sprint 3
Com a finalização da Sprint 2 realizando a criação do banco de dados na Sprint 3 realizaremos a transformação dos arquivos html em php para linkar com o banco de dados assim armazenando e utilizando esses dados em nosso site.
Com a implementação do banco de dados foi necessario adicionar o php e atualizar o html e css com essa adição é possivel realizar o cadastro de fornecedores e produdos e tambem há uma aba para pesquisar por produtos especificos filtrando por nome do produto ou pelo fornecedor nas imagens abaixo pode ser visto esse site com essas abas

<img width="647" alt="image" src="https://github.com/user-attachments/assets/44e43304-1e20-4120-a9e4-cba8e917a571">

<img width="646" alt="image" src="https://github.com/user-attachments/assets/55ae7197-cb49-4616-9f15-fa43ad48427a">

<img width="644" alt="image" src="https://github.com/user-attachments/assets/7f87c5d8-5be0-41d6-a999-89c4fabe9cd8"><br>

Esse sitema faz com que sejá possivel cadastrar oa dados colocados na tabela no proprio banco de dados assim os guardando e como na listaem de produto buscando esse dados e exibindo-os.
Tambem foi fito um sistema de validação de sessão que inpede usuarios sem estarem cadastrados o código é mostrado logo abaixo

<img width="421" alt="image" src="https://github.com/user-attachments/assets/8a918818-5913-41bc-837c-c6dcc73093f2"><br>

Com esse sistema a invação de pessoas não autorizadas se torna muito mais dificil.

Logo abaixo temos o cronograma do projeto contendo todos as datas de entrega e finalização dos processos

<img width="478" alt="image" src="https://github.com/user-attachments/assets/37fce8d8-bb78-42b5-b30e-9f71194d01ad">

Em clocusão a realização dessa atividade contribuiu para o entendimento do banco de dados e a códificação em PHP assim nos dando um desafio tornando a aprendizagem mais intuitiva e dinamica assim podemos dizer q essa projeto como atividade educacional foi um succeso para nosso aprendizado.

