<?php
/**
 * Arquivo de conexão com a base de dados MySQL
 * Configurações para conexão remota
 */

// Configurações da base de dados
$host = '185.187.169.13:3307';
$username = 'admin_vibecode';
$password = 'admin_vibecode';
$database = 'admin_vibecode';

try {
    // Criar conexão PDO com MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    
    // Configurar PDO para mostrar erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar PDO para retornar arrays associativos
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // Em caso de erro na conexão, mostrar mensagem e parar execução
    die("Erro na conexão com a base de dados: " . $e->getMessage());
}

/**
 * Função para criar a tabela clientes se não existir
 */
function criarTabelaClientes($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    try {
        $pdo->exec($sql);
        return true;
    } catch(PDOException $e) {
        echo "Erro ao criar tabela: " . $e->getMessage();
        return false;
    }
}

// Criar a tabela automaticamente quando o arquivo for incluído
criarTabelaClientes($pdo);
?>
