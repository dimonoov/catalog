<?php require_once 'header.php' ?>

<div class="page-wrap"> <!-- class="page-wrap" -->   
    <div class="content"> <!-- class="content" -->
        <ul class="breadcrumbs">
            <?=$breadcrumbs_new?>
        </ul>                

<?php if($products): ?>
<?php foreach($products as $product): ?>
    <div class="product"> <!-- class="product" -->
        <h1><a href="<?=PATH?>product/<?=$product['alias']?>"><?=$product['title']?></a></h1>
        <div class="img-wrap">
            <img src="<?=PATH . PRODUCTIMG . $product['image']?>" alt="" />
        </div>
        <p class="price">Цена: <span><?=$product['price']?></span> руб</p>
        <p class="views"><img align="center"  src="<?=PATH . VIEW?>img/views.jpg" alt="" /> <span>680</span></p>
        <p class="comments"><img align="center" src="<?=PATH . VIEW?>img/comments.jpg" alt="" /> <span>61</span></p>
        <p class="permalink"><a href="<?=PATH?>product/<?=$product['alias']?>">подробнее</a></p>
    </div> <!-- class="product" -->
<?php endforeach; ?>
<?php else: ?>
    <p>В этой категории товаров пока нет...</p>
<?php endif; ?>

<div class="clr"></div>
        
    <?php if( $count_pages > 1 ): ?>
        <ul class="pagination">
            <?=$pagination?>
        </ul>    
    <?php endif; ?>
    
    </div> <!-- class="content" -->
    
<?php require_once 'sidebar.php' ?>
    
</div> <!-- class="page-wrap" -->

<?php require_once 'footer.php' ?>