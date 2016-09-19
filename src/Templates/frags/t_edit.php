<div class="edit-form-wrap">
<?php

    if(is_object($notification)){
        /** @var \WonderWp\Notification\AdminNotification $notification */
        echo $notification;
    }

    echo $formView->render();
?>
</div>
