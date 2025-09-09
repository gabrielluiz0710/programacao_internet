<?php
require_once __DIR__ . '/../core/conexaoMysql.php';

class Anuncio
{
    /**
     * Cria um anúncio e salva suas fotos usando uma transação.
     */
    public function createAdWithPhotos($adData, $files, $idAnunciante)
    {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            // --- 1. LIDAR COM UPLOAD DE ARQUIVOS ---
            $uploadDir = __DIR__ . '/../../public/uploads/';
            $uploadedFileNames = [];

            foreach ($files as $file) {
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception('Erro no upload de uma das fotos.');
                }
                
                // Gera um nome de arquivo único para evitar conflitos
                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = uniqid('', true) . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    throw new Exception('Falha ao mover o arquivo para o destino.');
                }
                $uploadedFileNames[] = $newFileName;
            }

            // --- 2. INSERIR NA TABELA `Anuncio` ---
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

            // --- 3. INSERIR NA TABELA `Foto` ---
            $sqlPhoto = "INSERT INTO Foto (IdAnuncio, NomeArqFoto) VALUES (:idAnuncio, :nomeFoto)";
            $stmtPhoto = $pdo->prepare($sqlPhoto);

            foreach ($uploadedFileNames as $fileName) {
                $stmtPhoto->execute([
                    ':idAnuncio' => $newAdId,
                    ':nomeFoto' => $fileName
                ]);
            }

            // --- 4. SE TUDO DEU CERTO, CONFIRMAR A TRANSAÇÃO ---
            $pdo->commit();

        } catch (Exception $e) {
            // --- 5. SE ALGO DEU ERRADO, DESFAZER TUDO ---
            $pdo->rollBack();
            // Opcional: deletar arquivos que já foram upados
            // foreach ($uploadedFileNames as $fileName) { unlink($uploadDir . $fileName); }
            throw $e; // Propaga a exceção para o Controller
        }
    }

    /**
     * Busca todos os anúncios de um anunciante específico, incluindo suas fotos.
     * @return array Retorna uma lista de anúncios.
     */
    public function findByAnuncianteId($idAnunciante)
    {
        // Esta query usa GROUP_CONCAT para juntar todos os nomes de arquivos de fotos
        // de um anúncio em uma única string, separados por vírgula.
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

    /**
     * Busca um único anúncio pelo seu ID, verificando se pertence ao anunciante logado.
     * @return array|false Retorna os dados do anúncio ou false se não for encontrado/permitido.
     */
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

    /**
     * Busca todos os interesses de um anúncio, verificando a posse do anúncio.
     * @return array Retorna uma lista de interesses.
     */
    public function findInterestsByAdAndOwner($adId, $ownerId)
    {
        // O JOIN com a tabela Anuncio garante que só possamos ver interesses
        // de anúncios que realmente pertencem ao usuário logado.
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
}