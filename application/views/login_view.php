<?php
$this->load->helper('html');
$this->load->helper('form');
?>
<?php echo doctype(); ?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/css/login.css" />
    </head>
    <body onLoad="document.getElementById('username').focus()">
        <div id="container">
            <div id="form-container">
                <?php
                echo validation_errors();
                echo form_open('user/login');
                ?>
                <div id="loginUsernameDiv">
                    <label for="username" title="Username">Username</label>
                    <?php echo form_input(array('name' => 'username', 'id' => 'username', 'size' => '18')); ?>
                </div>
                <div id="loginPasswordDiv">
                    <label for="password" title="Password">Password</label>
                    <?php echo form_password(array('name' => 'password', 'id' => 'password', 'size' => '18')); ?>
                </div>
                <div id="loginButtonDiv">
                    <?php echo form_submit('submit', 'Login'); ?>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </body>
</html>