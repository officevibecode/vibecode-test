<?php
/**
 * Script para testar conexão com MySQL remoto e criar tabela
 */

// Configurações da base de dados
$host = '185.187.169.13';
$username = 'admin_vibecode';
$password = 'admin_vibecode';
$database = 'admin_vibecode';

echo "<div class='container'>\n";
echo "<h2>🔗 Testando Conexão com MySQL Remoto</h2>\n";
echo "<p><strong>Host:</strong> $host</p>\n";
echo "<p><strong>Database:</strong> $database</p>\n";
echo "<p><strong>Username:</strong> $username</p>\n";
echo "<hr>\n";

try {
    // Tentar conectar
    echo "<p>🔄 Tentando conectar ao MySQL remoto...</p>\n";
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    
    // Configurar PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    echo "<p>✅ <strong>Conexão estabelecida com sucesso!</strong></p>\n";
    
    // Verificar se a tabela existe
    echo "<p>🔄 Verificando se a tabela 'clientes' existe...</p>\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'clientes'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "<p>✅ Tabela 'clientes' já existe!</p>\n";
        
        // Mostrar estrutura da tabela
        echo "<p>📋 Estrutura da tabela:</p>\n";
        $stmt = $pdo->query("DESCRIBE clientes");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "<td>{$column['Extra']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes");
        $count = $stmt->fetch();
        echo "<p>📊 Total de clientes na tabela: <strong>{$count['total']}</strong></p>\n";
        
    } else {
        echo "<p>⚠️ Tabela 'clientes' não existe. Criando...</p>\n";
        
        // Criar tabela
        $sql = "CREATE TABLE clientes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefone VARCHAR(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "<p>✅ <strong>Tabela 'clientes' criada com sucesso!</strong></p>\n";
        
        // Inserir dados de exemplo
        echo "<p>🔄 Inserindo dados de exemplo...</p>\n";
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)");
        
        $exemplos = [
            ['João Silva', 'joao.silva@email.com', '(11) 99999-1111'],
            ['Maria Santos', 'maria.santos@email.com', '(11) 99999-2222'],
            ['Pedro Oliveira', 'pedro.oliveira@email.com', '(11) 99999-3333']
        ];
        
        foreach ($exemplos as $exemplo) {
            $stmt->execute($exemplo);
        }
        
        echo "<p>✅ <strong>3 clientes de exemplo inseridos!</strong></p>\n";
    }
    
    // Mostrar todos os clientes
    echo "<h3>👥 Lista de Clientes na Base de Dados:</h3>\n";
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
    $clientes = $stmt->fetchAll();
    
    if (empty($clientes)) {
        echo "<p>Nenhum cliente encontrado.</p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>\n";
        echo "<tr style='background-color: #f0f0f0;'><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Criado em</th></tr>\n";
        foreach ($clientes as $cliente) {
            echo "<tr>";
            echo "<td>{$cliente['id']}</td>";
            echo "<td>{$cliente['nome']}</td>";
            echo "<td>{$cliente['email']}</td>";
            echo "<td>{$cliente['telefone']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($cliente['created_at'])) . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    echo "<hr>\n";
    echo "<p>🎉 <strong>Base de dados configurada e funcionando perfeitamente!</strong></p>\n";
    echo "<p><a href='index.php'>← Voltar ao Sistema de Gestão</a></p>\n";
    
} catch(PDOException $e) {
    echo "<p>❌ <strong>Erro na conexão:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p>Verifique:</p>\n";
    echo "<ul>\n";
    echo "<li>Se o servidor MySQL está online</li>\n";
    echo "<li>Se as credenciais estão corretas</li>\n";
    echo "<li>Se o firewall permite conexões na porta 3306</li>\n";
    echo "<li>Se o utilizador tem permissões adequadas</li>\n";
    echo "</ul>\n";
}

echo "</div>\n";
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexão MySQL</title>
    <link rel="stylesheet" href="dark-theme.css">
    <style>
        /* Estilos específicos do teste de conexão */
        p {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 10px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
        }

        strong {
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
        }

        ul {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            margin: 15px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        li {
            color: rgba(255, 255, 255, 0.8);
            margin: 8px 0;
            padding-left: 10px;
        }

        hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            margin: 30px 0;
        }

        a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 12px 24px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
            margin: 10px 0;
            transition: all 0.3s ease;
        }

        a:hover {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>
</body>
</html>
