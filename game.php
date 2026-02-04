<?php
session_start();

// Inicializar estadísticas si es la primera vez
if (!isset($_SESSION['wins'])) {
    $_SESSION['wins'] = 0;
    $_SESSION['losses'] = 0;
    $_SESSION['draws'] = 0;
}

$result = '';
$playerChoice = '';
$computerChoice = '';
$message = '';

// Procesar la jugada del jugador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choice'])) {
    $playerChoice = strtolower($_POST['choice']);
    $validChoices = ['piedra', 'papel', 'tijera'];
    
    if (in_array($playerChoice, $validChoices)) {
        // Generar opción de la computadora
        $computerChoice = $validChoices[array_rand($validChoices)];
        
        // Determinar el resultado
        if ($playerChoice === $computerChoice) {
            $result = 'empate';
            $message = "¡Es un empate! Ambos eligieron $playerChoice";
            $_SESSION['draws']++;
        } elseif (
            ($playerChoice === 'piedra' && $computerChoice === 'tijera') ||
            ($playerChoice === 'papel' && $computerChoice === 'piedra') ||
            ($playerChoice === 'tijera' && $computerChoice === 'papel')
        ) {
            $result = 'ganaste';
            $message = "¡Ganaste! $playerChoice gana a $computerChoice";
            $_SESSION['wins']++;
        } else {
            $result = 'perdiste';
            $message = "¡Perdiste! $computerChoice gana a $playerChoice";
            $_SESSION['losses']++;
        }
    }
}

// Reiniciar estadísticas
if (isset($_POST['reset'])) {
    $_SESSION['wins'] = 0;
    $_SESSION['losses'] = 0;
    $_SESSION['draws'] = 0;
    $result = '';
    $playerChoice = '';
    $computerChoice = '';
    $message = '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piedra, Papel o Tijera</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: bold;
        }
        
        .choices {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .choice-btn {
            background: #38c414;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            transition: all 0.3s ease;
            text-transform: capitalize;
        }
        
        .choice-btn:hover {
            background: #e0e0e0;
            border-color: #667eea;
            transform: translateY(-5px);
        }
        
        .choice-btn:active {
            transform: translateY(-2px);
        }
        
        .result-section {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .result-section.ganaste {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }
        
        .result-section.perdiste {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }
        
        .result-section.empate {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
        }
        
        .choices-display {
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            font-size: 24px;
        }
        
        .choices-display div {
            text-align: center;
        }
        
        .vs {
            font-weight: bold;
            color: #667eea;
            font-size: 28px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .reset-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .reset-btn:hover {
            background: #c82333;
        }
        
        .emoji {
            font-size: 48px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 Piedra, Papel o Tijera</h1>
        <p class="subtitle">¡Juega contra la computadora!</p>
        
        <div class="stats">
            <div class="stat-box">
                <div class="stat-label">VICTORIAS</div>
                <div class="stat-value"><?php echo $_SESSION['wins']; ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">EMPATES</div>
                <div class="stat-value"><?php echo $_SESSION['draws']; ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">DERROTAS</div>
                <div class="stat-value"><?php echo $_SESSION['losses']; ?></div>
            </div>
        </div>
        
        <form method="POST">
            <div class="choices">
                <button type="submit" name="choice" value="piedra" class="choice-btn">
                    ✊ Piedra
                </button>
                <button type="submit" name="choice" value="papel" class="choice-btn">
                    ✋ Papel
                </button>
                <button type="submit" name="choice" value="tijera" class="choice-btn">
                    ✌️ Tijera
                </button>
            </div>
        </form>
        
        <?php if ($result): ?>
            <div class="result-section <?php echo $result; ?>">
                <div style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">
                    <?php echo $message; ?>
                </div>
                
                <div class="choices-display">
                    <div>
                        <div>Tu elección</div>
                        <div class="emoji">
                            <?php
                                echo match($playerChoice) {
                                    'piedra' => '✊',
                                    'papel' => '✋',
                                    'tijera' => '✌️',
                                };
                            ?>
                        </div>
                        <div style="text-transform: capitalize;"><?php echo $playerChoice; ?></div>
                    </div>
                    <div class="vs">VS</div>
                    <div>
                        <div>Computadora</div>
                        <div class="emoji">
                            <?php
                                echo match($computerChoice) {
                                    'piedra' => '✊',
                                    'papel' => '✋',
                                    'tijera' => '✌️',
                                };
                            ?>
                        </div>
                        <div style="text-transform: capitalize;"><?php echo $computerChoice; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="reset" class="reset-btn">🔄 Reiniciar Estadísticas</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
