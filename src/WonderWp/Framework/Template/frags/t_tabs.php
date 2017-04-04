<?php
$request = \WonderWp\Framework\HttpFoundation\Request::getInstance();

$current = $request->get('tab',1);

$newParams = array(
    'page'=> $request->get('page')
);

$request->query->remove('tab');
$baseUrl = $request->getBaseUrl().'?'.http_build_query($newParams);

$tabs = !empty($values['tabs']) ? $values['tabs'] : array();
?>
<h2 class="nav-tab-wrapper">
    <?php
    foreach ($tabs as $tab=>$tabInfos) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo '<a class="nav-tab'.$class.'" href="'.$baseUrl.'&tab='.$tab.'">'.$tabInfos['libelle'].'</a>';
    }
    ?>
</h2>
