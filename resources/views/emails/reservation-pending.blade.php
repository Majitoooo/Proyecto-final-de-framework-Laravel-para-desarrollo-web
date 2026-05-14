<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body
    style="
        margin:0;
        padding:40px;
        background:#fdf2f8;
        font-family:Arial,sans-serif;
    "
>

<div
    style="
        max-width:600px;
        margin:auto;
        background:white;
        border-radius:24px;
        overflow:hidden;
        box-shadow:
            0 10px 30px
            rgba(0,0,0,.08);
    "
>

    <!-- HEADER -->

    <div
        style="
            background:
            linear-gradient(
                135deg,
                #ec4899,
                #f472b6
            );
            padding:40px;
            text-align:center;
            color:white;
        "
    >

        <h1
            style="
                margin:0;
                font-size:28px;
            "
        >
            Coworking ✨
        </h1>

        <p
            style="
                margin-top:12px;
            "
        >
            Solicitud recibida
        </p>

    </div>

    <!-- CONTENT -->

    <div
        style="
            padding:40px;
        "
    >

        <h2>
            Hola
            {{
                $reservation
                    ->user_name
            }}
            👋
        </h2>

        <p
            style="
                color:#4b5563;
                line-height:1.8;
            "
        >
            Hemos recibido tu
            solicitud de reserva
            para el espacio

            <strong>
                {{
                    $reservation
                        ->space
                        ->name
                }}
            </strong>
        </p>

        <div
            style="
                background:#fdf2f8;
                padding:24px;
                border-radius:20px;
                margin:30px 0;
            "
        >

            <p>
                <strong>
                    Inicio:
                </strong>

                {{
                    $reservation
                        ->start_time
                }}
            </p>

            <p>
                <strong>
                    Fin:
                </strong>

                {{
                    $reservation
                        ->end_time
                }}
            </p>

        </div>

        <div
            style="
                background:#fff7ed;
                border-left:
                4px solid #f59e0b;
                padding:20px;
                border-radius:12px;
                margin-top:30px;
            "
        >

            ⏳ Tu reserva está
            pendiente de aprobación
            por un administrador.

        </div>

    </div>

</div>

</body>
</html>