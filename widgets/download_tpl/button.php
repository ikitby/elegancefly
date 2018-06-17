<?php

use app\models\DownloadProject;
use app\models\Products;
use yii\helpers\Url;

?>

<?= DownloadProject::getFileSize($purchase->actionProd->project_path.$purchase->actionProd->file) ?>
<br>
<button class="btn btn-primary" data-id = "<?= $purchase->actionProd->id ?>" type="submit">
    <span class="glyphicon glyphicon-download-alt"></span> Download</button>