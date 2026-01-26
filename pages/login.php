     <?php
     $username = '';
     $usernameErr = ''; $passwordErr = '';
     if (isset($_POST["username"], $_POST["password"])) {

     
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        if(empty($username)){
            $usernameErr = 'please input your username !';
        }
        if(empty($password)){
            $passwordErr = 'please input your password !';
        }
        if (empty($usernamerr) && empty($passwordErr))

        }if(empty($usernameErr) && empty($passwordErr)) {
            $user = logUserIn($username, $password);
        }
        if($user !== false) {
            header('location: ./?page=dashboard');
        }else{
            echo '<div class="alert alert-danger" role="alert">
            login fail !
            </div>';
        }

     

     ?>
     
     
     <form method="post" action = "./?page=login"  class="mx-auto" style="max-width: 500px;">
        <h3>Login</h3>

        <div class="mb-3"> 
            <label class= "form-label">Username</label>
            <input name = "username"  value = "<?php echo $username; ?>" type="username" class="form-control
            <?php echo empty($usernameErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback"><?php echo $usernameErr; ?></div>
        </div>
        <div class="mb-3">
            <label class= "form-label">Password</label>
            <input name = "password" type="password" class="form-control <?php echo empty($passwordErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback"><?php echo $passwordErr; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
