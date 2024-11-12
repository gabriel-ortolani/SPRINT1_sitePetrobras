CREATE DATABASE petrobras;
USE petrobras;
CREATE TABLE `cadastro_fornecedores` (
  `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(100) NOT NULL,
  `cnpj` int(14) NOT NULL,
  `endereco` varchar(350) NOT NULL,
  `telefone` int(12) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `descricao_fornecedor` varchar(200) NOT NULL,
  PRIMARY KEY (`id_fornecedor`)
);
CREATE TABLE `cadastro_produto` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome_produto` varchar(100) DEFAULT NULL,
  `codigo_produto` int(100) DEFAULT NULL,
  `descricao_produto` varchar(200) DEFAULT NULL,
  `quantidade_estoque` int(9) DEFAULT NULL,
  `preco` double DEFAULT NULL,
  fornecedor INT,
  FOREIGN KEY (fornecedor) REFERENCES cadastro_fornecedor(id_fornecedor)
);
CREATE TABLE `login` (
  `id_login` int(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
  `usuario` int(11) DEFAULT NULL,
  `senha` int(5) DEFAULT NULL
)