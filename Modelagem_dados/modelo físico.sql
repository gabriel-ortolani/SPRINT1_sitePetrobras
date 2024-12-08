CREATE DATABASE petrobras;
USE petrobras;
CREATE TABLE `cadastro_fornecedores` (
  `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(100) NOT NULL,
  `cnpj` int(14) NOT NULL,
  `endereco` varchar(350) NOT NULL,
  `telefone` int(12) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `observacoes` varchar(200) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_fornecedor`)
);
CREATE TABLE `cadastro_produto` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(100) DEFAULT NULL,
  `codigo_produto` int(100) DEFAULT NULL,
  `descricao_produto` varchar(200) DEFAULT NULL,
  `quantidade_estoque` int(9) DEFAULT NULL,
  `preco` double DEFAULT NULL,
  `fornecedor` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `fornecedor` (`fornecedor`),
  CONSTRAINT `cadastro_produto_ibfk_1` FOREIGN KEY (`fornecedor`) REFERENCES `cadastro_fornecedores` (`id_fornecedor`)
);
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(50) DEFAULT NULL,
  `senha` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
);
INSERT INTO usuario(nome_usuario, senha)
VALUES
("Gabriel"MD5("1234"))
("Pedro"MD5("9999"))
("Felipe"MD5("1122"))
("Yago"MD5("4321"))