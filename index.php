<?php
/**
 * Página principal - Lista todos os clientes
 * Inclui botões para adicionar, editar e apagar clientes
 */

// Incluir arquivo de conexão
require_once 'db.php';

// Buscar todos os clientes da base de dados
try {
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
    $clientes = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar clientes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de Clientes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            padding: 20px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="20" cy="20" r="2" fill="url(%23a)"/><circle cx="80" cy="40" r="1.5" fill="url(%23a)"/><circle cx="40" cy="80" r="1" fill="url(%23a)"/><circle cx="90" cy="90" r="2.5" fill="url(%23a)"/><circle cx="10" cy="60" r="1.2" fill="url(%23a)"/></svg>') repeat;
            opacity: 0.3;
            pointer-events: none;
            z-index: -1;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        }
        
        h1 {
            color: rgba(255, 255, 255, 0.95);
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: 300;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.5px;
        }
        
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin: 0;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            min-width: 100px;
            text-align: center;
            white-space: nowrap;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: rgba(0, 123, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-success {
            background: rgba(40, 167, 69, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-warning {
            background: rgba(255, 193, 7, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-danger {
            background: rgba(220, 53, 69, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 30px;
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        th {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        
        td {
            color: rgba(255, 255, 255, 0.8);
        }
        
        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .actions {
            white-space: nowrap;
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }
        
        .alert {
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .no-data {
            text-align: center;
            padding: 60px 40px;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .no-data h3 {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 15px;
            font-weight: 300;
        }
        
        .add-button-container {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .add-button-container .btn {
            padding: 14px 28px;
            font-size: 15px;
            min-width: 200px;
        }
        
        .stats {
            text-align: center;
            margin-top: 25px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestão de Clientes</h1>
        
        <!-- Botão para adicionar novo cliente -->
        <div class="add-button-container">
            <a href="create.php" class="btn btn-success">✨ Adicionar Novo Cliente</a>
        </div>
        
        <!-- Mostrar erro se houver -->
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <!-- Tabela de clientes -->
        <?php if (empty($clientes)): ?>
            <div class="no-data">
                <h3>Nenhum cliente encontrado</h3>
                <p>Clique no botão "Adicionar Novo Cliente" para começar.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning">✏️ Editar</a>
                                    <a href="delete.php?id=<?php echo $cliente['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja apagar este cliente?')">
                                       🗑️ Apagar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="stats">
                💼 Total de clientes: <?php echo count($clientes); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
