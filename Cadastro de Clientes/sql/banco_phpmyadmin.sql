-- Execute este arquivo com o banco grao_co_crm já selecionado no phpMyAdmin.

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS compras;
DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE compras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NOT NULL,
    produto VARCHAR(120) NOT NULL,
    quantidade INT UNSIGNED NOT NULL DEFAULT 1,
    valor DECIMAL(10,2) NOT NULL,
    data_compra DATE NOT NULL,
    CONSTRAINT fk_compras_clientes
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO clientes (nome, email, telefone, cpf) VALUES
('Mariana Pereira', 'mariana@email.com', '(11) 99999-1001', '111.111.111-11'),
('Ana Beatriz', 'ana@email.com', '(11) 99999-1002', '222.222.222-22'),
('Julia Costa', 'julia@email.com', '(11) 99999-1003', '333.333.333-33'),
('Lucas Braga', 'lucas@email.com', '(11) 99999-1004', '444.444.444-44');

INSERT INTO compras (cliente_id, produto, quantidade, valor, data_compra) VALUES
(1, 'Cappuccino', 1, 12.00, '2026-09-20'),
(1, 'Croissant', 2, 18.00, '2026-09-20'),
(2, 'Café Latte', 1, 10.00, '2026-09-19'),
(2, 'Bolo de chocolate', 1, 14.00, '2026-09-19'),
(3, 'Café coado', 2, 12.00, '2026-09-18'),
(4, 'Mocha', 1, 15.00, '2026-09-17');

SET FOREIGN_KEY_CHECKS = 1;
