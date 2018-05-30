<li>
    <a href="<?= yii\helpers\Url::to(['/catalog/category', 'catalias' => $category['alias']]) ?>">
        <?= $category['title'] ?><span class="badge bg-red pull-right"><?= $category{'artcount'} ?></span>
    </a>
</li>