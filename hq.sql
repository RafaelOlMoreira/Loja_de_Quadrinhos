CREATE DATABASE IF NOT EXISTS hq_plataforma;
USE hq_plataforma;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS Usuario(
    ID_Usuario int auto_increment primary key,
    Nome varchar(100) NOT NULL,
    Email varchar(100) UNIQUE NOT NULL,
    Senha varchar(255) NOT NULL,
    Tipo ENUM('Admin', 'Vendedor', 'Comprador', 'Organizador') NOT NULL
);

-- Tabela de Produtos
CREATE TABLE IF NOT EXISTS Produto(
    ID_Produto int auto_increment primary key,
    Titulo varchar(100) NOT NULL,
    Descricao TEXT,
    Preco decimal(10, 2) NOT NULL,
    Quantidade INT DEFAULT 1,
    Imagem varchar(255),
    ID_Vendedor INT NOT NULL,
    FOREIGN KEY (ID_Vendedor) REFERENCES Usuario(ID_Usuario)
);

-- Tabela de Compras
CREATE TABLE IF NOT EXISTS Compra(
    ID_Compra int auto_increment primary key,
    ID_Comprador INT NOT NULL,
    Data DATETIME DEFAULT CURRENT_TIMESTAMP,
    Valor_Total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_Comprador) REFERENCES Usuario(ID_Usuario)
);

-- Tabela de Itens de Compra
CREATE TABLE IF NOT EXISTS Item_Compra(
    ID_Item int auto_increment primary key,
    ID_Compra INT NOT NULL,
    ID_Produto INT NOT NULL,
    Quantidade INT NOT NULL,
    Preco_Unitario decimal(10, 2) NOT NULL,
    FOREIGN KEY (ID_Compra) REFERENCES Compra(ID_Compra),
    FOREIGN KEY (ID_Produto) REFERENCES Produto(ID_Produto)
);

-- Tabela de Eventos
CREATE TABLE IF NOT EXISTS Evento(
    ID_Evento int auto_increment primary key,
    ID_Organizador INT NOT NULL,
    Nome varchar(100) NOT NULL,
    Data datetime NOT NULL,
    Local_Evento varchar(255) NOT NULL, 
    FOREIGN KEY (ID_Organizador) REFERENCES Usuario(ID_Usuario)  
);

-- Tabela de Inscrições em Eventos
CREATE TABLE IF NOT EXISTS Inscricao_Evento(
    ID_Inscricao int auto_increment primary key,
    ID_Evento INT NOT NULL,
    ID_Usuario INT NOT NULL,
    Data_Inscricao datetime DEFAULT current_timestamp,
    FOREIGN KEY (ID_Evento) REFERENCES Evento(ID_Evento),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuario(ID_Usuario)
);

-- Tabela de Tickets de Suporte
CREATE TABLE IF NOT EXISTS Ticket(
    ID_Ticket int auto_increment primary key,
    ID_Usuario INT NOT NULL,
    Data_Abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    Status ENUM('Aberto', 'Em andamento', 'Resolvido') DEFAULT 'Aberto',
    Assunto VARCHAR(255) NOT NULL,
    FOREIGN KEY (ID_Usuario) REFERENCES Usuario(ID_Usuario)
);

-- Tabela de Avaliações
CREATE TABLE IF NOT EXISTS Avaliacao(
    ID_Avaliacao int auto_increment primary key,
    ID_Produto INT NOT NULL,
    Nota INT CHECK (Nota BETWEEN 1 AND 5),
    Comentario TEXT,
    FOREIGN KEY (ID_Produto) REFERENCES Produto(ID_Produto)
);

-- Procedimento para Registrar Compra
DROP PROCEDURE IF EXISTS RegistrarCompra;
DELIMITER //
CREATE PROCEDURE  RegistrarCompra(
    IN p_ID_Comprador INT,
    IN p_ID_Produto INT,
    IN p_Quantidade INT
)
BEGIN
    DECLARE v_Preco DECIMAL(10, 2);
    DECLARE v_Total DECIMAL(10, 2);
    DECLARE v_Estoque INT;
    
    -- Verifica se há estoque suficiente
    SELECT Quantidade INTO v_Estoque FROM Produto WHERE ID_Produto = p_ID_Produto;
    
    IF v_Estoque < p_Quantidade THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Estoque insuficiente para esta compra';
    ELSE
        -- Obtém o preço do produto
        SELECT Preco INTO v_Preco FROM Produto WHERE ID_Produto = p_ID_Produto;
        SET v_Total = v_Preco * p_Quantidade;
        
        -- Insere a compra
        INSERT INTO Compra (ID_Comprador, Valor_Total) VALUES (p_ID_Comprador, v_Total);
        
        -- Insere os itens da compra
        INSERT INTO Item_Compra (ID_Compra, ID_Produto, Quantidade, Preco_Unitario)
        VALUES (LAST_INSERT_ID(), p_ID_Produto, p_Quantidade, v_Preco);
        
        -- Atualiza o estoque
        UPDATE Produto SET Quantidade = Quantidade - p_Quantidade 
        WHERE ID_Produto = p_ID_Produto;
        
        SELECT 'Compra registrada com sucesso!' AS Mensagem;
    END IF;
END //
DELIMITER ;

-- Procedimento para Cadastrar Produto
DELIMITER //
DROP PROCEDURE IF EXISTS CadastrarProduto //
CREATE PROCEDURE CadastrarProduto(
    IN p_Titulo VARCHAR(100),
    IN p_Descricao TEXT,
    IN p_Preco DECIMAL(10, 2),
    IN p_Quantidade INT,
    IN p_Imagem VARCHAR(255),
    IN p_ID_Vendedor INT
)
BEGIN
    -- Verifica se o vendedor existe
    IF NOT EXISTS (SELECT 1 FROM Usuario WHERE ID_Usuario = p_ID_Vendedor AND Tipo = 'Vendedor') THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Vendedor não encontrado ou não tem permissão';
    ELSE
        -- Insere o novo produto
        INSERT INTO Produto (Titulo, Descricao, Preco, Quantidade, Imagem, ID_Vendedor)
        VALUES (p_Titulo, p_Descricao, p_Preco, p_Quantidade, p_Imagem, p_ID_Vendedor);
        
        SELECT 'Produto cadastrado com sucesso!' AS Mensagem;
    END IF;
END //
DELIMITER ;

-- Procedimento para Listar Produtos por Vendedor
DELIMITER //
DROP PROCEDURE IF EXISTS ListarProdutosPorVendedor //
CREATE PROCEDURE ListarProdutosPorVendedor(
    IN p_ID_Vendedor INT
)
BEGIN
    -- Verifica se o vendedor existe
    IF NOT EXISTS (SELECT 1 FROM Usuario WHERE ID_Usuario = p_ID_Vendedor) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Erro: Vendedor não encontrado no sistema';
    ELSE
        -- Retorna todos os produtos do vendedor
        SELECT 
            p.ID_Produto,
            p.Titulo,
            p.Descricao,
            p.Preco,
            FORMAT(p.Preco, 2, 'pt_BR') AS Preco,
            p.Quantidade,
            p.Imagem,
            COUNT(DISTINCT a.ID_Avaliacao) AS Total_Avaliacoes,
            IFNULL(FORMAT(AVG(a.Nota), 0), 'pt_BR') AS Media_Avaliacoes,
            CASE
                WHEN COUNT(a.ID_Avaliacao) = 0 THEN 'Sem avaliações'
                WHEN AVG(a.Nota) >= 4 THEN 'Excelente'
                WHEN AVG(a.Nota) >= 3 THEN 'Bom'
                ELSE 'A melhorar'
                 END AS Classificacao
        FROM Produto p
        LEFT JOIN Avaliacao a ON p.ID_Produto = a.ID_Produto
        WHERE p.ID_Vendedor = p_ID_Vendedor
        GROUP BY p.ID_Produto;
    END IF;
END //
DELIMITER ;

-- Procedimento para Registrar Evento
DELIMITER //
DROP PROCEDURE IF EXISTS RegistrarEvento //
CREATE PROCEDURE RegistrarEvento(
    IN p_ID_Organizador INT,
    IN p_Nome VARCHAR(100),
    IN p_Data DATETIME,
    IN p_Local VARCHAR(255)
)
BEGIN
    -- Verifica se o organizador existe
   DECLARE v_OrganizadorValido BOOLEAN;
    
    -- Verifica se o organizador existe e tem permissão
    SELECT COUNT(*) > 0 INTO v_OrganizadorValido
    FROM Usuario 
    WHERE ID_Usuario = p_ID_Organizador AND Tipo = 'Organizador';
    
    IF NOT v_OrganizadorValido THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Erro: Organizador não encontrado ou não possui permissão para criar eventos';
    ELSEIF p_Data < NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro: Data do evento não pode ser no passado';
    ELSE
        -- Insere o novo evento com validações adicionais
        INSERT INTO Evento (ID_Organizador, Nome, Data, Local_Evento)
        VALUES (p_ID_Organizador, p_Nome, p_Data, p_Local);
        
        -- Retorna informações completas do evento criado
        SELECT 
            'Evento cadastrado com sucesso!' AS Mensagem,
            e.ID_Evento,
            e.Nome,
            DATE_FORMAT(e.Data, '%d/%m/%Y %H:%i') AS DataFormatada,
            e.Local_Evento AS Local
        FROM Evento e
        WHERE e.ID_Evento = LAST_INSERT_ID();
    END IF;
END //
DELIMITER ;
-- Desativa o modo seguro temporariamente
SET SQL_SAFE_UPDATES = 0;

-- Deleta todos os registros da tabela Usuario
DELETE FROM Usuario WHERE Email IS NOT NULL;

-- Reseta o AUTO_INCREMENT
ALTER TABLE Usuario AUTO_INCREMENT = 1;

-- Insere os dados novamente
INSERT INTO Usuario (Nome, Email, Senha, Tipo) VALUES 
('Admin do Sistema', 'admin@hqplatform.com', '$2a$10$xJwL5vxZzK9yB2VQYQdZBeR9QJG7QUzUf7jUO5WnLq6t0dFJk8XaK', 'Admin'),
('Comic Shop BH', 'comicshop@hqplatform.com', '$2a$10$yH8eJ7vTq3R2fW4S5v6d7eB9cD0eF1gH2iJ3kL4mN5oP6qR7sT8uV', 'Vendedor'),
('Geek Collect', 'geekcollect@hqplatform.com', '$2a$10$zI9k0lM1n2O3p4Q5r6s7t8U9v0W1x2Y3zA4B5C6D7e8f9g0h1i2j3', 'Vendedor'),
('Zé Colecionador', 'ze@hqplatform.com', '$2a$10$aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uV1wX2yZ3A4B5C6D7e8f9g0', 'Comprador'),
('Mari Eventos', 'mari@hqplatform.com', '$2a$10$bC2d3eF4gH5iJ6kL7mN8oP9qR0sT1uV2wX3yZ4A5B6C7D8e9f0g1', 'Organizador'),
('Ana Fã de HQs', 'ana@hqplatform.com', '$2a$10$cD3e4fG5hI6jK7lM8nO9pQ0r1sT2uV3wX4yZ5A6B7C8D9e0f1g2', 'Comprador');

-- (Opcional) Ativa novamente o modo seguro
SET SQL_SAFE_UPDATES = 1;


-- 2. Inserção de produtos (vinculados aos vendedores)
INSERT INTO Produto (Titulo, Descricao, Preco, Quantidade, ID_Vendedor, Imagem) VALUES 
('Batman: Ano Um', 'Edição premium do clássico de Frank Miller', 129.90, 5, 2, 'batman-ano-um.jpg'),
('Homem-Aranha #300', 'Primeira aparição do Venom', 450.00, 2, 2, 'homem-aranha-300.jpg'),
('X-Men: Fênix Negra', 'Saga completa em capa dura', 89.90, 8, 3, 'xmen-fenix.jpg'),
('Turma da Mônica #1', 'Edição fac-símile rara', 199.90, 3, 3, 'turma-monica-1.jpg'),
('Watchmen', 'Graphic novel completa', 75.50, 10, 2, 'watchmen.jpg'),
('V de Vingança', 'Edição especial com extras', 65.00, 7, 3, 'v-de-vinganca.jpg');

-- 3. Inserção de eventos
INSERT INTO Evento (ID_Organizador, Nome, Data, Local_Evento) VALUES 
(5, 'Feira de Troca de HQs', '2024-11-15 10:00:00', 'Centro de Convenções de BH'),
(5, 'Lançamento Coleção DC 2024', '2024-12-05 19:00:00', 'Livraria Cultura - São Paulo'),
(1, 'HQ Fest 2024', '2025-03-20 09:00:00', 'Expominas - Belo Horizonte');

-- 4. Inscrições em eventos
INSERT INTO Inscricao_Evento (ID_Evento, ID_Usuario) VALUES 
(1, 4), -- Zé na Feira de Troca
(1, 6), -- Ana na Feira de Troca
(2, 4), -- Zé no Lançamento DC
(3, 4), -- Zé no HQ Fest
(3, 6); -- Ana no HQ Fest

-- 5. Compras e itens de compra (usando a stored procedure)
CALL RegistrarCompra(4, 1, 1); -- Zé compra Batman: Ano Um
CALL RegistrarCompra(4, 3, 2); -- Zé compra 2 X-Men: Fênix Negra
CALL RegistrarCompra(6, 5, 1); -- Ana compra Watchmen

-- 6. Avaliações de produtos
INSERT INTO Avaliacao (ID_Produto, Nota, Comentario) VALUES 
(1, 5, 'Edição linda e de alta qualidade!'),
(3, 4, 'Ótima história, mas a capa poderia ser melhor'),
(5, 5, 'Clássico absoluto, perfeito!'),
(1, 4, 'Muito bom, mas o preço é salgado');

-- 7. Tickets de suporte
INSERT INTO Ticket (ID_Usuario, Assunto, Status) VALUES 
(4, 'Produto não chegou', 'Em andamento'),
(6, 'Dúvida sobre evento', 'Resolvido'),
(4, 'Problema no pagamento', 'Aberto');