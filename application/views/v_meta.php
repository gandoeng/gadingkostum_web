<?php 
$keywords = 'Sewa kostum terbaru untuk anak dan dewasa di kelapa gading jakarta - kostum karakter, superhero, adat, profesi, negara, dll. Bisa dikirim / diantar'; 
if(isset($meta)){ 
if(isset($meta['keywords'])){ $keywords = html_entity_decode($meta['keywords']); } ?>
<title><?php echo $meta['title'] ;?></title>
    <meta name="keywords" content="<?php echo $keywords;?>"/>
    <meta name="description" content="<?php echo html_entity_decode($meta['description']);?>"/>
    <meta name="author" content="Gading Kostum"/>
    <link rel="canonical" href="<?php echo $meta['canonical'];?>" />
    <meta property="og:locale" content="<?php echo $meta['locale'];?>" />
    <meta property="og:type" content="<?php echo $meta['type'];?>" />
    <meta property="og:title" content="<?php echo $meta['title'];?>" />
    <meta property="og:description" content="<?php echo $meta['description'];?>" />
    <meta property="og:url" content="<?php echo $meta['url'];?>" />
    <meta property="og:site_name" content="<?php echo $meta['site_name'];?>" />
    <?php if(isset($meta['article_section'])){ ?>
<meta property="article:section" content="<?php echo $meta['article_section'];?>" />
    <meta property="article:published_time" content="<?php echo $meta['article_published_time'];?>" />
    <meta property="article:modified_time" content="<?php echo $meta['article_modified_time'];?>" />
    <meta property="og:updated_time" content="<?php echo $meta['updated_time'];?>" />
    <?php } ?>
    <?php if(isset($meta['image'])){ ?>
<meta property="og:image" content="<?php echo $meta['image'];?>" />
    <meta property="og:image:width" content="<?php echo $meta['image_width'];?>" />
    <meta property="og:image:height" content="<?php echo $meta['image_height'];?>" />
    <?php } ?>
<meta name="twitter:card" content="<?php echo $meta['card'];?>" />
    <meta name="twitter:description" content="<?php echo html_entity_decode($meta['description']);?>" />
    <meta name="twitter:title" content="<?php echo $meta['title'];?>" />
<?php } else { //DEFAULT ?>
    <title><?php echo (isset($title)) ? $title : 'Gading Kostum' ;?></title>
    <meta name="keywords" content="<?php echo $keywords;?>"/>
    <meta name="description" content="Sewa kostum terbaru untuk anak dan dewasa di kelapa gading jakarta - kostum karakter, superhero, adat, profesi, negara, dll. Bisa dikirim / diantar"/>
    <meta name="author" content="Gading Kostum"/>
    <link rel="canonical" href="<?php echo current_url();?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Gading Kostum - Sewa Kostum Anak & Dewasa - Costume Rental" />
    <meta property="og:description" content="Sewa kostum terbaru untuk anak dan dewasa di kelapa gading jakarta - kostum karakter, superhero, adat, profesi, negara, dll. Bisa dikirim / diantar" />
    <meta property="og:url" content="<?php echo current_url();?>" />
    <meta property="og:site_name" content="Gading Kostum" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="Sewa kostum terbaru untuk anak dan dewasa di kelapa gading jakarta - kostum karakter, superhero, adat, profesi, negara, dll. Bisa dikirim / diantar" />
    <meta name="twitter:title" content="Gading Kostum - Sewa Kostum Anak & Dewasa - Costume Rental" />
<?php } ?>
