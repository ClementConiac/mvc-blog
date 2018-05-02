<nav class="col-3 py-2 categories-nav">
    <?php if(isset($_SESSION['user'])): ?>
        <p class="h2 text-center">Salut <?php echo $_SESSION['user']; ?> !</p>
        <p>
            <a class="d-block btn btn-danger mb-4 mt-2" href="index.php?logout">Déconnexion</a>
            <?php if($_SESSION['is_admin'] == 1): ?>
                <a class="d-block btn btn-warning mb-4 mt-2" href="index.php?page=admin">Administration</a>
            <?php else: ?>
                <a class="d-block btn btn-warning mb-4 mt-2" href="index.php?page=profile">Profile</a>
            <?php endif; ?>
        </p>
    <?php else: ?>
        <a class="d-block btn btn-primary mb-4 mt-2" href="index.php?page=connection">Connexion / inscription</a>
    <?php endif; ?>
	<b>Catégories :</b>
	<ul>
		<li><a href="index.php?page=article_list">Tous les articles</a></li>
		<!-- liste des catégories -->
		<?php foreach($categories as $category): ?>
		<li><a href="index.php?page=article_list&category_id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
		<?php endforeach; ?>

	</ul>
</nav>
