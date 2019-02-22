<h1><?= $title ?></h1>

<p><?= $description ?></p>

<? foreach ($news as $item): ?>
<ul>
    <li>ID: <?= $item->getId() ?></li>
    <li>Title: <?= $item->getTitle() ?></li>
    <li>Description: <?= $item->getDescription() ?></li>
    <li>Date: <?= $item->getDate() ?></li>
    <li>Category: <?= $item->getCategory() ? $item->getCategory()->getTitle() : "Category haven't been chosen" ?></li>
</ul>
<? endforeach; ?>