<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body
    style="
        margin:0;
        padding:40px;
        background:#fff7ed;
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

    <div
        style="
            background:
            linear-gradient(
                135deg,
                #f59e0b,
                #fb923c
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
            Coworking
        </h1>

        <p
            style="
                margin-top:12px;
            "
        >
            Reserva cancelada
        </p>

    </div>

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
            Tu reserva para el espacio

            <strong>
                {{
                    $reservation
                        ->space
                        ->name
                }}
            </strong>

            fue cancelada.
        </p>

        <div
            style="
                background:#fff7ed;
                border-left:
                4px solid #f59e0b;
                padding:20px;
                border-radius:12px;
                margin:30px 0;
            "
        >

            <strong>
                Motivo:
            </strong>

            <br><br>

            {{
                $reservation
                    ->admin_reason
            }}

        </div>

        <p
            style="
                color:#6b7280;
            "
        >
            Puedes reservar
            otro horario
            disponible 💖
        </p>

    </div>

</div>

</body>
</html>