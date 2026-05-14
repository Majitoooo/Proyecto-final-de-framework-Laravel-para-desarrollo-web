<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body
    style="
        margin:0;
        padding:40px;
        background:#fff1f2;
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
                #ef4444,
                #f87171
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
            Reserva rechazada
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
            Lamentamos informarte
            que tu solicitud
            de reserva para el espacio

            <strong>
                {{
                    $reservation
                        ->space
                        ->name
                }}
            </strong>

            no pudo ser aprobada.
        </p>

        <div
            style="
                background:#fef2f2;
                border-left:
                4px solid #ef4444;
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
            Puedes intentar
            nuevamente con otro
            horario disponible 💖
        </p>

    </div>

</div>

</body>
</html>