<div class="login">
      <h1>Register in Web App</h1>

      <form method="post" action="">
          <span class='field-error login-error'>
              <?=isset($errors['register']) ? $errors['register']: '' ?>
          </span>
          <p>
              <span class='field-error'>
                  <?=isset($errors['username']) ? $errors['username']: '' ?>
              </span>
              <input type="text" name="username" value="<?= isset($username) ? $username: '' ?>" placeholder="Username">
         </p>
         <p>
             <span class='field-error'>
                 <?=isset($errors['email']) ? $errors['email']: '' ?>
             </span>
             <input type="text" name="email" value="<?= isset($email) ? $email: '' ?>" placeholder="Email">
        </p>
         <p>
             <span class='field-error'>
                 <?=isset($errors['password']) ? $errors['password']: '' ?>
             </span>
            <input type="password" name="password" value="" placeholder="Password">
         </p>

        <p class="submit"><input type="submit" name="commit" value="Login"></p>
    </form>
</div>

<div class="login-help">
    <p>Forgot your password? <a href="">Click here to reset it</a>.</p>
</div>
