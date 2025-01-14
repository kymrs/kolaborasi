<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tidak Ditemukan</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>/assets/img/favicon.png">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .content {
            position: relative;
            bottom: 25px;
            text-align: center;
        }

        img {
            width: 300px;
            filter: drop-shadow(3px 3px 3px rgba(0, 0, 0, 0.4));
            animation: move-image 3s infinite linear;
        }

        h1 {
            margin: 0;
            position: relative;
            bottom: -25px;
            color: #F15623;
            font-weight: bold;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.2);
            margin-bottom: 50px;
        }

        a {
            padding: 15px 20px;
            border-radius: 15px;
            text-decoration: none;
            color: white;
            background-color: #F15623;
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
            display: inline-block;
            transition: 300ms;
        }

        a:hover {
            scale: 0.955;
        }

        @keyframes move-image {
            0% {
                scale: 1;
            }

            50% {
                scale: 0.955;
            }

            100% {
                scale: 1;
            }
        }

        @media (max-width: 564px) {
            body {
                font-size: 70%;
            }

            img {
                margin-bottom: 20px;
                width: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="content">
        <img src="<?= base_url('assets/backend/img/pengenumroh.png') ?>" alt="Logo Pengen Umroh">
        <h1>Data Tidak Ditemukan</h1>
        <a href="https://link.pengenumroh.com/">pengenumroh.com</a>
    </div>
</body>

</html>