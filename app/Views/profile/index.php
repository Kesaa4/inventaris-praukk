<h2>Profile Saya</h2>

<p>Email: <?= session()->get('email') ?></p>
<p>Role: <?= session()->get('role') ?></p>

<p>Nama: <?= $profile['nama'] ?? '-' ?></p>
<p>No HP: <?= $profile['no_hp'] ?? '-' ?></p>
<p>Alamat: <?= $profile['alamat'] ?? '-' ?></p>

<a href="/profile/edit">Edit Profile</a>

<a href="/dashboard">⬅ Kembali ke Dashboard</a>
