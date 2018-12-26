<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Calvary Album -  Admin Panel</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='assets/css/fonts.css' rel='stylesheet' type='text/css'>



</head>

<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="#">
                        Admin
                    </a>
                </div>
                <div class="login-form">
                    <form id="loginForm">
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                        </div>
                                <div class="checkbox">
                                    <label class="pull-right">
                                <a href="#">Forgotten Password?</a>
                            </label>

                                </div>
                                <div id="formError"></div>
                                <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


</body>

</html>
<script type="text/javascript">
        jQuery(document).ready(function()
        {
            jQuery("#loginForm").submit(function(e)
            {
                e.preventDefault();
                var email = jQuery('#inputEmail').val();
                var password = jQuery('#inputPassword').val();
                if(email == '')
                {
                    jQuery('#formError').html('<div class="alert alert-danger"><strong>Error!</strong> Please enter email id.</div>');
                }
                if(email == '' && password == '')
                {
                    jQuery('#formError').html('<div class="alert alert-danger"><strong>Error!</strong> Please enter emal and  password.</div>');
                }
                if(email && password)
                {
                    jQuery.post("<?=base_url('login')?>",jQuery("#loginForm").serialize(),function(data){
                        if(JSON.parse(data).error == true)
                        {
                            jQuery('#formError').html('<div class="alert alert-danger"><strong>Error!</strong> '+JSON.parse(data).message+'</div>');
                        }
                        else
                        {
                            jQuery('#formError').html('<div class="alert alert-success"><strong>Success!</strong> Login success redirecting...</div>');
                            setTimeout(function(){ window.location = "<?=base_url('/')?>"; }, 1000);
                        }
                    });
                }
                
            });
            jQuery("#forgot-password").on('submit', function(e)
            {
                e.preventDefault();
                if(jQuery('#email').val() == '')
                {
                    jQuery('#success_message').html('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error!</strong> Please enter Email id.</div>');
                    return false;
                }
                jQuery.post("<?=base_url('forgot-password')?>",$("#forgot-password").serialize(),function(data){
                    var data = JSON.parse(data);
                    if(data.error == true)
                    {
                        jQuery('#success_message').html('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error!</strong> '+data.message+'</div>');
                    }
                    else
                    {
                        jQuery('#success_message').html('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Success!</strong> '+data.message+'.</div>');
                    }
                })
            })
        })
    </script>