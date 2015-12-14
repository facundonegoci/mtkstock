<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Acceso Panel de Control</title>

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.mobile-1.1.0.min.css" />

        <style>
            /* App custom styles */
        </style>

        <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.mobile-1.1.0.min.js"></script> 


        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Custom Login Form Styling with CSS3" />
        <meta name="keywords" content="css3, login, form, custom, input, submit, button, html5, placeholder" />
        <meta name="author" content="MTKSTUDIO" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login/style.css" />
        <script src="<?php echo base_url(); ?>js/login/modernizr.custom.63321.js"></script>
        <!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
        <style>
            body {
                background: #eedfcc url(images/bg3.jpg) no-repeat center top;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                background-size: cover;
            }
            .container > header h1,
            .container > header h2 {
                color: #fff;
                text-shadow: 0 1px 1px rgba(0,0,0,0.5);
            }
            @media (max-width: 767px){
                .form-2 {
                    width: 294px;
                }
            }
        </style>


    </head>

    <body>

        <div class="container">

            <header>

                <div class="support-note">
                    <span class="note-ie">Sorry, only modern browsers.</span>
                </div>

            </header>

            <section class="main">

                <div class="form-2">

                    <div style="color:#ff0000;">

                        <?php echo validation_errors(); ?>

                    </div>
                    <br/>
                    <?php echo form_open('verifylogin'); ?>
                    <h1><span class="log-in">Log in</span> </h1>
                    <p class="float">
                        <label for="username"><i class="icon-user"></i>Usuario</label>
                        <input type="text" name="username" placeholder="Usuario.." required>
                    </p>
                    <p class="float">
                        <label for="password"><i class="icon-lock"></i>Password</label>
                        <input type="password" name="password" placeholder="Password" class="showpassword" required>
                    </p>

                    <p class="clearfix"> 

                        <input type="submit" name="submit" value="Log in">
                    </p>
                </div>​​
            </section>

        </div>
     
    </body>

</html>
