<?php

$cat_id = $id;
if(! $cat_id){
    include APP_ROOT . '/public/page_not_found.php';
}
$data['category'] = $cms->getCategory()->fetch($cat_id);
if(! $data['category']){
    include APP_ROOT . '/public/page_not_found.php';
}

$data['articles'] = $cms->getArticle()->fetchAll($cat_id);
$data['navigation'] = $cms->getCategory()->fetchNavigation();
$data['section'] = $cat_id;



echo $twig->render('category.html', $data);


