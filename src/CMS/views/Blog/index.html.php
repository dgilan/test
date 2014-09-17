<?php

$max_pages_list = 5;
$current_page = $page;
$maxPage = ceil($total / $limit);

$count_pages = ceil($total / $limit);

$first_page = $current_page - (int)($max_pages_list / 2);
if ($first_page <= 1) {
    $first_page = 1;
} else {
    if ($count_pages - $first_page < $max_pages_list) {
        $first_page = $count_pages - $max_pages_list + 1;
        if ($first_page <= 1) {
            $first_page = 1;
        }
    }
}
$last_page = $first_page + $max_pages_list - 1;
if ($last_page > $count_pages) {
    $last_page = $count_pages;
}



?>

<div class="col-sm-8 blog-main">

    <?php foreach ($posts as $post) { ?>

        <div class="blog-post">
            <h2 class="blog-post-title"><a href="/posts/<?php echo $post->id ?>"> <?php echo $post->title ?></a></h2>

            <p class="blog-post-meta"><?php echo date('F j, Y', strtotime($post->updated_at)) ?> by <a
                    href="#"><?php echo $post->name ?></a>
            </p>

            <?php echo htmlspecialchars_decode($post->content) ?>
        </div>

    <?php } ?>

    <?php if ($count_pages > 1) { ?>
    <ul class="pagination">
        <li <?php if ($first_page == 1) echo 'class="disabled"'; ?>><a href="/?limit=<?php echo $limit ?>&page=1">&laquo;</a></li>

        <?php for ($i = $first_page;$i <= $last_page;$i++) { ?>
            <li <?php if ($i == $page) echo 'class="active"' ?>><a href="/?limit=<?php echo $limit ?>&page=<?php echo $i ?>"><?php echo $i ?> <span
                        class="sr-only">(current)</span></a></li>
        <?php } ?>
        <li <?php if ($last_page >= $count_pages)  echo 'class="disabled"' ?>><a href="/?limit=<?php echo $limit ?>&page=<?php echo $maxPage ?>">&raquo;</a></li>
    </ul>
    <?php } ?>

</div>
