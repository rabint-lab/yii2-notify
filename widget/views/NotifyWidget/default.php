<div class="pmMessageWidget">
    <?php foreach ($items as $item) { ?>
        <div class="messageItem">
           <a href="<?= $item->link; ?>"><?= $item->content; ?></a>
        </div>
    <?php } ?>
</div>