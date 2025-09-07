-- Tabela para os anunciantes
CREATE TABLE Anunciante (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255) NOT NULL,
    CPF VARCHAR(14) NOT NULL UNIQUE,
    Email VARCHAR(255) NOT NULL UNIQUE,
    SenhaHash VARCHAR(255) NOT NULL,
    Telefone VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para os anúncios
CREATE TABLE Anuncio (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Marca VARCHAR(100) NOT NULL,
    Modelo VARCHAR(100) NOT NULL,
    Ano INT NOT NULL,
    Cor VARCHAR(50),
    Quilometragem INT,
    Descricao TEXT,
    Valor DECIMAL(10, 2) NOT NULL,
    DataHora DATETIME NOT NULL,
    Estado VARCHAR(2) NOT NULL, -- Ex: 'MG', 'SP'
    Cidade VARCHAR(100) NOT NULL,
    IdAnunciante INT NOT NULL,
    FOREIGN KEY (IdAnunciante) REFERENCES Anunciante(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para as fotos dos anúncios (um anúncio pode ter várias fotos)
CREATE TABLE Foto (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    IdAnuncio INT NOT NULL,
    NomeArqFoto VARCHAR(255) NOT NULL,
    FOREIGN KEY (IdAnuncio) REFERENCES Anuncio(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para registrar interesse em um anúncio
CREATE TABLE Interesse (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255) NOT NULL,
    Telefone VARCHAR(20) NOT NULL,
    Mensagem TEXT NOT NULL,
    DataHora DATETIME NOT NULL,
    IdAnuncio INT NOT NULL,
    FOREIGN KEY (IdAnuncio) REFERENCES Anuncio(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;