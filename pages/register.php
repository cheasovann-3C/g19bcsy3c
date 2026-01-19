<?php
$name = $username = $passwd ='';
$nameErr = $usernameErr =  $passwdErr ='';

if(isset($_POST["name"], $_POST["username"],$_POST['passwd'], $_POST['confirmPasswd'])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $passwd = $_POST['passwd'];
    $confirmPasswd = $_POST['confirmPasswd'];
    if (empty($name)){
        $nameErr = 'please input name !';
    }
     if (empty($username)){
        $usernameErr = 'please input your username !';
    }
     if (empty($passwd)){
        $passwdErr = 'please input your password !';
    }
     if (empty($passwd !== $confirmPasswd)){
        $passwdErr = 'your password not match !';
    }
}
?>
    <!-- form -->
    <section>
    <form method="post" action="./?page=register" class="col-md-8 col-lg-6 mx-auto">
        <h3>Register</h3>

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name"
                   value="<?php echo ($name); ?>"
                   type="text"
                   class="form-control <?php echo empty($nameErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback"><?php echo $nameErr; ?></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username"
                   value="<?php echo ($username); ?>"
                   type="text"
                   class="form-control <?php echo empty($usernameErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback"><?php echo $usernameErr; ?></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="passwd"
                   type="password"
                   class="form-control <?php echo empty($passwdErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback"><?php echo $passwdErr; ?></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input name="confirmPasswd" type="password" class="form-control">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</section>
