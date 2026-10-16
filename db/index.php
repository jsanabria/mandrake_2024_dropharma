<?php
// Tiempo de espera antes de entrar al sistema (milisegundos)
$delay = 10000;

// Ruta de acceso al sistema
$rutaSistema = 'mandrake/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mandrake | Iniciando sistema</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                         Roboto, Arial, sans-serif;

            background:
                radial-gradient(circle at top, #263b34 0%, #17241f 38%, #0d1512 100%);

            color: #ffffff;
        }

        .loader-container {
            width: 90%;
            max-width: 430px;
            text-align: center;
            padding: 45px 35px;
        }

        /* Icono */
        .logo-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 24px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 2px solid rgba(122, 184, 148, 0.55);
            border-radius: 18px;

            font-size: 32px;
            font-weight: 700;
            color: #8bc8a3;

            background: rgba(255, 255, 255, 0.03);

            box-shadow:
                0 0 30px rgba(83, 150, 110, 0.12),
                inset 0 0 20px rgba(255, 255, 255, 0.02);
        }

        /* Nombre */
        .brand {
            font-size: 30px;
            font-weight: 600;
            letter-spacing: 5px;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 13px;
            color: #93a59d;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 38px;
            line-height: 1.7;
        }

        .subtitle small {
            font-size: 10px;
            letter-spacing: 1px;
            color: #71837a;
        }

        /* Mensaje */
        .loading-text {
            font-size: 15px;
            font-weight: 400;
            color: #d4ddd9;
            margin-bottom: 14px;
        }

        /* Barra */
        .progress-wrapper {
            width: 100%;
            height: 5px;
            overflow: hidden;

            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
        }

        .progress-bar {
            width: 0%;
            height: 100%;

            background: linear-gradient(
                90deg,
                #5f9f78,
                #8bc8a3
            );

            border-radius: 10px;

            /* Se sincroniza con $delay */
            animation: loading <?php echo ($delay / 1000); ?>s ease-in-out forwards;

            box-shadow: 0 0 10px rgba(139, 200, 163, 0.35);
        }

        @keyframes loading {
            0% {
                width: 0%;
            }

            25% {
                width: 28%;
            }

            55% {
                width: 58%;
            }

            80% {
                width: 83%;
            }

            100% {
                width: 100%;
            }
        }

        /* Estado */
        .status {
            margin-top: 13px;

            display: flex;
            justify-content: space-between;

            font-size: 11px;
            color: #72837b;
        }

        .version {
            margin-top: 42px;
            font-size: 11px;
            color: #58665f;
        }

        /* Copyright */
        .copyright {
            margin-top: 24px;
            padding-top: 18px;

            border-top: 1px solid rgba(255, 255, 255, 0.05);

            font-size: 10px;
            line-height: 1.7;
            color: #526159;
        }

        .copyright .developer {
            color: #687a70;
        }

        .copyright a {
            color: #687a70;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .copyright a:hover {
            color: #8bc8a3;
        }

        /* Animación de puntos */
        .dots::after {
            content: "";
            animation: dots 1.4s infinite;
        }

        @keyframes dots {
            0%   { content: ""; }
            25%  { content: "."; }
            50%  { content: ".."; }
            75%  { content: "..."; }
            100% { content: ""; }
        }

        @media (max-width: 500px) {
            .loader-container {
                padding: 30px 20px;
            }

            .brand {
                font-size: 25px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>

<body>

    <main class="loader-container">

        <div class="logo-icon">
            M
        </div>

        <div class="brand">
            MANDRAKE
        </div>

        <div class="subtitle">
            Sistema de Gesti&oacute;n Empresarial
            <br>
            <small>Facturaci&oacute;n y Control de Inventario</small>
        </div>

        <div class="loading-text">
            Cargando Sistema Mandrake<span class="dots"></span>
        </div>

        <div class="progress-wrapper">
            <div class="progress-bar"></div>
        </div>

        <div class="status">
            <span>Inicializando m&oacute;dulos</span>
            <span id="porcentaje">0%</span>
        </div>

        <div class="version">
            Preparando entorno de trabajo
        </div>

        <div class="copyright">
            &copy; <?php echo date('Y'); ?> Mandrake
            <br>
            <span class="developer">
                Desarrollado por Lic. Junior Sanabria
            </span>
            <br>
            <a href="mailto:jsanabria44@gmail.com">
                jsanabria44@gmail.com
            </a>
        </div>

    </main>

    <script>
        const delay = <?php echo $delay; ?>;
        const porcentaje = document.getElementById('porcentaje');

        let inicio = Date.now();

        function actualizarProgreso() {

            let transcurrido = Date.now() - inicio;

            let progreso = Math.min(
                Math.round((transcurrido / delay) * 100),
                100
            );

            porcentaje.textContent = progreso + '%';

            if (progreso < 100) {
                requestAnimationFrame(actualizarProgreso);
            }
        }

        actualizarProgreso();

        setTimeout(function () {
            window.location.href = <?php echo json_encode($rutaSistema); ?>;
        }, delay);
    </script>

</body>
</html>