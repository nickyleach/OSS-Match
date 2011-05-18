<h2>Match Results</h2>
<p>Based on the analysis of <span><?= $repository->name; ?></span>, here are some other projects you should look at:</p>
<ul>
<? foreach($repositories as $repo): ?>
<li><a href="<?= $repo->url; ?>" title="<?= $repo->name; ?> - Score: <?= $repo->score; ?>"><?= $repo->name; ?></a></li>
<? endforeach; ?>
</ul>