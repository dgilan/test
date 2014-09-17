<div class="container">

   <?php  if (isset($errors)) {  var_dump($errors);   }  ?>

    <form class="form-signin" role="form" method="post" action="/signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="email" class="form-control" placeholder="Email address" required autofocus name="email">
        <input type="password" class="form-control" placeholder="Password" required name="password">
        <label class="checkbox">
            <input type="checkbox" value="remember-me" name="remember"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

</div>