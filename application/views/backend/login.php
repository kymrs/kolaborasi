<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>

    <link rel="icon" href="<?= base_url() ?>assets/icon/favicon.png" type="image/jpg">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sb2/vendor/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="<?= base_url() ?>assets/backend/plugins/sb2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: rgb(30, 112, 255);
            overflow: hidden;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 500px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .eye-slash {
            position: absolute;
            bottom: 197px;
            left: 330px;
            display: none;
            cursor: pointer;
            color: white;
        }

        .eye-fill {
            position: absolute;
            bottom: 197px;
            left: 330px;
            cursor: pointer;
            color: white;
        }
    </style>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="<?php echo base_url(); ?>auth/login" method="post">
        <h3>Login Here</h3>

        <div class="text-center">
            <?= $this->session->flashdata('message'); ?>
        </div>

        <label for="username">Username</label>
        <input type="text" placeholder="Username" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password" required>

        <img src="<?= base_url() ?>/assets/icon/eye-fill.svg" class="eye-fill" id="eye-fill">
        <img src="<?= base_url() ?>/assets/icon/eye-slash.svg" class="eye-slash" id="eye-slash">

        <button type="submit">Log In</button>
    </form>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $('#eye-fill').on('mousedown', function() {
        $("#password").prop('type', 'text');
        $("#eye-fill").css("display", "none");
        $("#eye-slash").css("display", "block");
    });

    $('#eye-slash').on('mouseup', function() {
        $("#password").prop('type', 'password');
        $("#eye-fill").css("display", "block");
        $("#eye-slash").css("display", "none");
    });
</script>