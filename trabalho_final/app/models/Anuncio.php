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
}