<h2>Login</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red;">
        <?= session()->getFlashdata('error') ?>
    </p>
<?php endif ?>

<form method="post" action="<?= base_url('/login') ?>">
    <?= csrf_field() ?>

    <p>
        <label>Email</label><br>
        <input type="email" name="email" id="">
    </p>

    <p>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </p>

    <button type="submit">Login</button>
</form>
