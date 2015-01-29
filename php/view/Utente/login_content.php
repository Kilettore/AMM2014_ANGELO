<p>Benvenuto <h3><?= $this->user->getNome().' '.$this->user->getCognome() ?>!</h3></p>

<form action="index.php" method="post">
        <input type="submit" name="logout" value="Logout">
</form>

