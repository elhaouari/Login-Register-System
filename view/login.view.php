<div class="login">
      <h1>Login to Web App</h1>

      <form method="post" action="">
          <span class='field-error login-error'><?=isset($errors['login']) ? $errors['login']: '' ?></span>
        <p><span class='field-error'><?=isset($errors['username']) ? $errors['username']: '' ?></span>
            <input type="text" name="username" value="<?= isset($username) ? $username: '' ?>" placeholder="Username or Email"></p>
        <p><span class='field-error'><?=isset($errors['password']) ? $errors['password']: '' ?></span>
            <input type="password" name="password" value="" placeholder="Password"></p>
        <p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me" <?= (isset($remember) && $remember == true)? 'checked' : '' ?>>
            Remember me on this computer
          </label>
        </p>
        <p class="submit"><input type="submit" name="commit" value="Login"></p>
      </form>
    </div>

    <div class="login-help">
      <p>Forgot your password? <a href="">Click here to reset it</a>.</p>
    </div>
