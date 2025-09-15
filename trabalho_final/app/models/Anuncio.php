<?php
require_once __DIR__ . '/../core/conexaoMysql.php';

class Anuncio
{
    
    public function createAdWithPhotos($adData, $files, $idAnunciante)
    {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            $uploadedFileNames = [];

            foreach ($files as $file) {
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception('Erro no upload de uma das fotos.');
                }
                
                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = uniqid('', true) . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    throw new Exception('Falha ao mover o arquivo para o destino.');
                }
                $uploadedFileNames[] = $newFileName;
            }

            $sqlAd = "INSERT INTO Anuncio (Marca, Modelo, Ano, Cor, Quilometragem, Descricao, Valor, DataHora, Estado, Cidade, IdAnunciante) 
                      VALUES (:marca, :modelo, :ano, :cor, :km, :descricao, :valor, NOW(), :estado, :cidade, :idAnunciante)";
            
            $stmtAd = $pdo->prepare($sqlAd);
            $stmtAd->execute([
                ':marca' => $adData['marca'],
                ':modelo' => $adData['modelo'],
                ':ano' => $adData['ano'],
                ':cor' => $adData['cor'],
                ':km' => $adData['km'],
                ':descricao' => $adData['descricao'],
                ':valor' => $adData['valor'],
                ':estado' => $adData['estado'],
                ':cidade' => $adData['cidade'],
                ':idAnunciante' => $idAnunciante
            ]);
            
            $newAdId = $pdo->lastInsertId();

            $sqlPhoto = "INSERT INTO Foto (IdAnuncio, NomeArqFoto) VALUES (:idAnuncio, :nomeFoto)";
            $stmtPhoto = $pdo->prepare($sqlPhoto);

            foreach ($uploadedFileNames as $fileName) {
                $stmtPhoto->execute([
                    ':idAnuncio' => $newAdId,
                    ':nomeFoto' => $fileName
                ]);
            }

            $pdo->commit();

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e; 
        }
    }

    public function findByAnuncianteId($idAnunciante)
    {
        $sql = "
            SELECT 
                A.*, 
                GROUP_CONCAT(F.NomeArqFoto) as Fotos
            FROM 
                Anuncio A
            LEFT JOIN 
                Foto F ON A.Id = F.IdAnuncio
            WHERE 
                A.IdAnunciante = :idAnunciante
            GROUP BY 
                A.Id
            ORDER BY 
                A.DataHora DESC
        ";
        
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':idAnunciante' => $idAnunciante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAdByIdAndOwner($adId, $ownerId)
    {
        $sql = "
            SELECT 
                A.*, 
                GROUP_CONCAT(F.NomeArqFoto) as Fotos
            FROM 
                Anuncio A
            LEFT JOIN 
                Foto F ON A.Id = F.IdAnuncio
            WHERE 
                A.Id = :adId AND A.IdAnunciante = :ownerId
            GROUP BY 
                A.Id
        ";
        
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':adId' => $adId, ':ownerId' => $ownerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findInterestsByAdAndOwner($adId, $ownerId)
    {
        $sql = "
            SELECT 
                I.*
            FROM 
                Interesse I
            INNER JOIN
                Anuncio A ON I.IdAnuncio = A.Id
            WHERE 
                I.IdAnuncio = :adId AND A.IdAnunciante = :ownerId
            ORDER BY 
                I.DataHora DESC
        ";
        
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':adId' => $adId, ':ownerId' => $ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAdByIdAndOwner($adId, $ownerId)
    {
        $pdo = Database::connect();
        $uploadDir = __DIR__ . '/../../public/uploads/';

        try {
            $pdo->beginTransaction();

            $sqlSelectPhotos = "
                SELECT F.NomeArqFoto 
                FROM Foto F
                INNER JOIN Anuncio A ON F.IdAnuncio = A.Id
                WHERE A.Id = :adId AND A.IdAnunciante = :ownerId";
            
            $stmtSelect = $pdo->prepare($sqlSelectPhotos);
            $stmtSelect->execute([':adId' => $adId, ':ownerId' => $ownerId]);
            $photoFiles = $stmtSelect->fetchAll(PDO::FETCH_COLUMN);

            $sqlDelete = "DELETE FROM Anuncio WHERE Id = :adId AND IdAnunciante = :ownerId";
            $stmtDelete = $pdo->prepare($sqlDelete);
            $stmtDelete->execute([':adId' => $adId, ':ownerId' => $ownerId]);

            if ($stmtDelete->rowCount() === 0) {
                throw new Exception('Anúncio não encontrado ou você não tem permissão para excluí-lo.');
            }

            foreach ($photoFiles as $fileName) {
                $filePath = $uploadDir . $fileName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function getDistinctField($field, $whereField = null, $whereValue = null) {
        $sql = "SELECT DISTINCT $field FROM Anuncio";
        $params = [];
        if ($whereField && $whereValue) {
            $sql .= " WHERE $whereField = :whereValue";
            $params[':whereValue'] = $whereValue;
        }
        $sql .= " ORDER BY $field ASC";
        
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getDistinctCities($marca, $modelo) {
        $sql = "SELECT DISTINCT Cidade, Estado FROM Anuncio WHERE Marca = :marca AND Modelo = :modelo ORDER BY Cidade ASC";
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':marca' => $marca, ':modelo' => $modelo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchAds($filters) {
        $baseSql = "
            SELECT A.*, GROUP_CONCAT(F.NomeArqFoto) as Fotos
            FROM Anuncio A
            LEFT JOIN Foto F ON A.Id = F.IdAnuncio
        ";
        $whereClauses = [];
        $params = [];

        if (!empty($filters['marca'])) {
            $whereClauses[] = "A.Marca = :marca";
            $params[':marca'] = $filters['marca'];
        }
        if (!empty($filters['modelo'])) {
            $whereClauses[] = "A.Modelo = :modelo";
            $params[':modelo'] = $filters['modelo'];
        }
        if (!empty($filters['localizacao'])) {
            list($cidade, $estado) = array_map('trim', explode('-', $filters['localizacao']));
            $whereClauses[] = "A.Cidade = :cidade AND A.Estado = :estado";
            $params[':cidade'] = $cidade;
            $params[':estado'] = $estado;
        }

        if (!empty($whereClauses)) {
            $baseSql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $baseSql .= " GROUP BY A.Id ORDER BY A.DataHora DESC LIMIT 20";

        $pdo = Database::connect();
        $stmt = $pdo->prepare($baseSql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAdByIdPublic($adId)
    {
        $sql = "
            SELECT A.*, GROUP_CONCAT(F.NomeArqFoto) as Fotos
            FROM Anuncio A
            LEFT JOIN Foto F ON A.Id = F.IdAnuncio
            WHERE A.Id = :adId
            GROUP BY A.Id
        ";
        
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':adId' => $adId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addInteresse($adId, $nome, $telefone, $mensagem)
    {
        $sql = "INSERT INTO Interesse (IdAnuncio, Nome, Telefone, Mensagem, DataHora) 
                VALUES (:idAnuncio, :nome, :telefone, :mensagem, NOW())";
        
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':idAnuncio' => $adId,
            ':nome' => $nome,
            ':telefone' => $telefone,
            ':mensagem' => $mensagem
        ]);
    }
}