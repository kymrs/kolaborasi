<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="<?= base_url() ?>assets/backend/icon/favicon.png" type="image/jpg">
    <link rel="stylesheet" href="<?= base_url() ?>assets/backend/plugins/sb2/vendor/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/00a12cb1bc.js" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/backend/plugins/style-login.css">
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="center">
        <div class="container">
            <div class="image">
                <!-- <img src="<?= base_url() ?>assets/backend/img/kolaborasi1.png" alt="Logo Kolaborasi" class="logo"> -->
                <h1>LOGIN</h1>
            </div>
            <div class="text-center">
                <?= $this->session->flashdata('message'); ?>
            </div>
            <div class="form">
                <form action="<?php echo base_url(); ?>auth/login" method="post">
                    <table>
                        <tr>
                            <td>
                                <label for="username">Username</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" id="username" name="username" placeholder="Username" autocomplete="off" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="password">Password</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="password" id="password" name="password" placeholder="Password" required>
                                <i class="fas fa-solid fa-eye" class="eye-fill" id="eye-fill"></i>
                                <i class="fas fa-solid fa-eye-slash" class="eye-slash" id="eye-slash"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="submit">
                                    Login
                                    <i class="fa-solid fa-right-to-bracket" style="color: #000"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $('#eye-fill').on('mousedown', function() {
        $("#password").prop('type', 'text');
        $("#eye-fill").css("display", "none");
        $("#eye-slash").css("display", "inline-block");
    });

    $('#eye-slash').on('mouseup', function() {
        $("#password").prop('type', 'password');
        $("#eye-fill").css("display", "inline-block");
        $("#eye-slash").css("display", "none");
    });
</script>