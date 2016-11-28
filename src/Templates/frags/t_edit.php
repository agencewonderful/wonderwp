<div class="edit-form-wrap">
<?php

    /** @var \WonderWp\Notification\AdminNotification $notification */
    if(is_object($notification)){
        echo $notification;
    }

    /** @var \WonderWp\Forms\FormViewInterface $formView */
    echo $formView->render();
?>
</div>
