<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CipherVeil //</title>
    <!-- Подключаем шрифт Miki Nice Ainjoo -->
    <link href="https://fonts.googleapis.com/css2?family=Miki+Nice+Ainjoo&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
            font-family: 'Miki Nice Ainjoo', monospace;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            overflow-x: hidden;
        }

        h1 {
            font-size: 4rem;
            margin: 0;
            line-height: 1.2;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s ease-out forwards;
        }

        h1 span {
            display: block;
            opacity: 0;
            transform: translateY(20px);
            animation: wordAppear 0.8s ease-out forwards;
        }

        h1 span:nth-child(1) { animation-delay: 0.3s; }
        h1 span:nth-child(2) { animation-delay: 0.6s; }
        h1 span:nth-child(3) { animation-delay: 0.9s; }

        .cta-button {
            margin-top: 40px;
            padding: 15px 30px;
            font-size: 1.2rem;
            color: white;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: 
                buttonAppear 1s ease-out 1.2s forwards,
                glow 8s ease-in-out infinite;
            background: linear-gradient(
                120deg,
                #4f46e5,
                #6366f1,
                #818cf8,
                #a5b4fc,
                #c7d2fe,
                #a5b4fc,
                #818cf8,
                #6366f1,
                #4f46e5
            );
            background-size: 400% 400%;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border-radius: 8px;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255,255,255,0.15),
                transparent
            );
            transition: 0.5s;
            z-index: -1;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .hidden-block {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 30px;
            text-align: center;
        }

        .hidden-block.active {
            max-height: 200px;
            opacity: 1;
            transform: translateY(0);
        }

        .email {
            color: #ff4444;
            font-weight: bold;
            font-size: 1.5rem;
            margin: 20px 0;
            text-shadow: 0 0 10px rgba(255,68,68,0.3);
        }

        .login-link {
            margin-top: 10px;
            font-size: 1rem;
            color: #888;
        }

        .login-link a {
            color: #4f46e5;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #818cf8;
            text-decoration: underline;
        }

        footer {
            margin-top: 60px;
            font-size: 0.9rem;
            color: #888;
            opacity: 0;
            animation: fadeIn 1s ease-out 1.5s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes wordAppear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes buttonAppear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glow {
            0% {
                background-position: 0% 50%;
                box-shadow: 0 0 20px rgba(79,70,229,0.5);
            }
            50% {
                background-position: 100% 50%;
                box-shadow: 0 0 40px rgba(99,102,241,0.8);
            }
            100% {
                background-position: 0% 50%;
                box-shadow: 0 0 20px rgba(79,70,229,0.5);
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>
            <span>Forever.</span>
            <span>Anonymously.</span>
            <span>Crypted.</span>
        </h1>
    </header>
    <main>
        <button class="cta-button" onclick="toggleHiddenBlock()">Let's f*cking go</button>

        <!-- Скрытый блок -->
        <div id="hiddenBlock" class="hidden-block">
            <p>Ваша временная электронная почта для регистрации:</p>
            <div id="tempEmail" class="email"></div>
            <p>Отправьте на неё сообщение, чтобы завершить регистрацию.</p>
            <p class="login-link">Уже имеется аккаунт? <a href="#" onclick="openLoginModal()">Войти</a></p>
        </div>
    </main>
    <footer>
        <p>CipherVeil - Максимально анонимно.</p>
    </footer>

    <script>
        function generateTempEmail() {
            const randomString = Math.random().toString(36).substring(2, 10);
            return `anon-${randomString}@shadow-network.org`;
        }

        function toggleHiddenBlock() {
            const hiddenBlock = document.getElementById('hiddenBlock');
            const tempEmailElement = document.getElementById('tempEmail');
            
            if (!hiddenBlock.classList.contains('active')) {
                const tempEmail = generateTempEmail();
                tempEmailElement.textContent = tempEmail;
                hiddenBlock.classList.add('active');
            } else {
                hiddenBlock.classList.remove('active');
                setTimeout(() => {
                    tempEmailElement.textContent = '';
                }, 500);
            }
        }

        function openLoginModal() {
            window.location.href = '/auth/user/index.php'; // Замените на реальное модальное окно
        }
    </script>
</body>
</html>