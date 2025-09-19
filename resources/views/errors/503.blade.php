<?php
// Opcional: Defina cabeçalho 503 (manutenção temporária)
http_response_code(503);
header('Retry-After: 3600'); // Cliente pode tentar novamente em 1 hora
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Santo.bet - Estamos em manutenção</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #0f172a; /* fundo escuro */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-direction: column;
        }
        img.logo {
            max-width: 220px;
            height: auto;
            margin-bottom: 20px; /* Espaço para a barra */
        }
        h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        p {
            font-size: 1rem;
            max-width: 300px;
            color: #cbd5e1;
        }
        .progress-container {
            width: 300px; /* Mesma largura máxima do texto */
            background-color: #1e293b; /* Tom mais claro que o fundo */
            border-radius: 5px;
            margin-bottom: 10px; /* Espaço para o texto abaixo */
            overflow: hidden;
        }
        .progress-bar {
            width: 0; /* Começa em 0% */
            height: 10px;
            background-color: #ffffff; /* Cor branca para a barra */
            border-radius: 5px;
            animation: progress-animation 2s ease-in-out forwards; /* Animação de 2 segundos */
        }
        .progress-text {
            font-size: 0.9rem;
            color: #cbd5e1; /* Mesma cor do texto secundário */
            margin-bottom: 20px; /* Espaço antes do título */
        }
        .instagram-section {
            margin-top: 20px; /* Espaço acima do aviso */
            max-width: 300px;
        }
        .instagram-text {
            font-size: 0.9rem;
            color: #cbd5e1;
            margin-bottom: 10px;
        }
        .instagram-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffffff; /* Cor branca para o botão */
            color: #0f172a; /* Texto escuro para contraste */
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            animation: pulse 2s infinite ease-in-out; /* Animação pulsante */
            transition: background-color 0.3s;
        }
        .instagram-button:hover {
            background-color: #cbd5e1; /* Cor mais clara ao passar o mouse */
        }
        @keyframes progress-animation {
            0% {
                width: 0; /* Começa em 0% */
            }
            100% {
                width: 90%; /* Termina em 90% */
            }
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 10px 5px rgba(255, 255, 255, 0.3);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
        }
    </style>
</head>
<body>
    <img src="https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/4848b4af-6abb-4c4b-ad19-9c541f567900/logo" alt="Logo" class="logo">
    <div class="progress-container">
        <div class="progress-bar"></div>
    </div>
    <div class="progress-text">0%</div>
    <h1>Estamos em manutenção</h1>
    <p>Estamos realizando atualizações importantes para melhorar sua experiência. Voltamos em breve!</p>
    <div class="instagram-section">
        <div class="instagram-text">Siga-nos no Instagram para atualizações!</div>
        <a href="https://www.instagram.com/santobetoficial_/" class="instagram-button" target="_blank">Seguir @santobetoficial_</a>
    </div>

    <script>
        const progressText = document.querySelector('.progress-text');
        let progress = 0;
        const targetProgress = 98; // Valor final da animação
        const duration = 2500; // Duração da animação em milissegundos (2s)
        const increment = targetProgress / (duration / 16); // Incremento por frame (aprox. 60fps)

        function updateProgress() {
            if (progress < targetProgress) {
                progress += increment;
                if (progress > targetProgress) progress = targetProgress; // Limita a 90%
                progressText.textContent = `${Math.round(progress)}%`;
                requestAnimationFrame(updateProgress); // Chama o próximo frame
            } else {
                progressText.textContent = '98% Concluído'; // Texto final
            }
        }

        // Inicia a animação
        requestAnimationFrame(updateProgress);
    </script>
</body>
</html>