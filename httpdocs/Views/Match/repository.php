<h2>Match Results</h2>
<p>Based on your data from <span><?= $orig_repository['name']; ?></span>, here are some other projects you should look at:</p>
<ul>
<? foreach($repositories as $repository): ?>
<li><a href="<?= $repository['url']; ?>" title="<?= $repository['name']; ?> - Score: <?= $repository['score']; ?>"><?= $repository['name']; ?></a></li>
<? endforeach; ?>
</ul>