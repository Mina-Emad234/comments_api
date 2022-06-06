<?php
$page_title="Remember Me Example";

require "partials/header.php";
?>

<div class="container">
    <div class="jumbotron">
        <h1><?= $page_title ?> </h1>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue</h1>
            <div class="account-wall">

                <form method="post" action="sign-in" class="form-signin">
                    <?php
                    if (!empty($errors)) {
                        foreach ($errors as $error):
                            echo "<p class='alert alert-danger'>" . $error . "</p>";
                        endforeach;
                    }
                    ?>
                    <input class="form-control" type="email" name="email" placeholder="Email" required autofocus
                           tabindex="1"
                           value="<?php echo isset($_COOKIE["cook_name"]) ? $_COOKIE["cook_name"] : @htmlspecialchars($_POST['username']); ?>"/>
                    <input class="form-control" type="password" placeholder="Password" name="password" title="Password" tabindex="2"
                           value="<?php echo isset($_COOKIE["cook_pass"]) ? $_COOKIE["cook_pass"] : ''; ?>" required/>

                    <button class="btn btn-lg btn-primary btn-block" name="login" value="Login"  type="submit">Sign in</button>
                    <label class="checkbox pull-left">
                        <input type="checkbox" name="remember-me"  value="1"
                            <?php echo isset($_COOKIE["cook_rem"]) ? 'checked="checked"' : ''; ?>>
                        Remember me
                    </label>
                    <span class="clearfix"></span>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require "partials/footer.php";
