<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        Reserva finalizada
    </title>
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
                rgba(0,0,0,0.08);
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
                    opacity:.9;
                "
            >
                Gracias por usar
                nuestros espacios
            </p>

        </div>

        <!-- CONTENT -->

        <div
            style="
                padding:40px;
            "
        >

            <h2
                style="
                    color:#1f2937;
                    margin-top:0;
                "
            >
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
                Tu reserva del
                espacio
                <strong>
                    {{
                        $reservation
                            ->space
                            ->name
                    }}
                </strong>

                ha finalizado.
            </p>

            <p
                style="
                    color:#4b5563;
                    line-height:1.8;
                "
            >
                Esperamos que
                hayas tenido una
                gran experiencia
                con nosotros 💖
            </p>

            <div
                style="
                    text-align:center;
                    margin:40px 0;
                "
            >

                <div
                    style="
                        font-size:32px;
                        margin-bottom:20px;
                    "
                >
                    ⭐⭐⭐⭐⭐
                </div>

                <a
                    href="{{
                        url(
                            '/reservations/'
                            .
                            $reservation->id
                            .
                            '/review'
                        )
                    }}"
                    style="
                        display:inline-block;
                        background:#ec4899;
                        color:white;
                        text-decoration:none;
                        padding:16px 28px;
                        border-radius:999px;
                        font-weight:bold;
                    "
                >
                    Calificar servicio
                </a>

            </div>

            <div
                style="
                    background:#fdf2f8;
                    padding:20px;
                    border-radius:16px;
                    color:#6b7280;
                    font-size:14px;
                "
            >

                Reserva:

                <strong>
                    {{
                        $reservation
                            ->start_time
                    }}
                </strong>

                -
                <strong>
                    {{
                        $reservation
                            ->end_time
                    }}
                </strong>

            </div>

        </div>

    </div>

</body>
</html>