<div class="edit-form-wrap">
<?php

    /** @var \WonderWp\Framework\Notification\AdminNotification $notification */
    if(is_object($notification)){
        echo $notification;
    }

    /** @var \WonderWp\Framework\Form\FormViewInterface $formView */
    echo $formView->render();
?>
</div>
