<h2>Login</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red;">
        <?= session()->getFlashdata('error') ?>
    </p>
<?php endif ?>

<form method="post" action="<?= base_url('/login') ?>">
    <?= csrf_field() ?>

    <p>
        <input type="email" name="email" id="email" placeholder="Email">
    </p>

    <p>
        <input type="password" name="password" id="password" placeholder="Password">

    <label>
    <input type="checkbox" onclick="togglePassword()">
    Tampilkan password
    </label>

    <script>
    function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
    }
    </script>

    </p>

    <button type="submit">Login</button>
</form>
