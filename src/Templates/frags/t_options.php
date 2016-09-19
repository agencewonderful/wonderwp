<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/06/2016
 * Time: 18:33
 */

if(!empty($notification)){ echo $notification; }
$request = \WonderWp\HttpFoundation\Request::getInstance();
$options = !empty($options) ? $options : array();

?>
<?php
/* @var $options array */
if ($request->getMethod() == 'POST' && !empty($request->request->get('action') && $request->request->get('action') == 'save')) {
    foreach ($options as $value) {
        if (!empty($value['id'])) {
            update_option($value['id'], stripslashes($request->request->get($value['id'])));
        }
    }
}
?>
<div class="options-wrap">
<form method="post" action="" class="">

    <table class="form-table">
        <?php foreach ($options as $value) {

        switch ($value['type']) {
        case 'password' :
        case 'text':
            ?>
            <tr>
            <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name']); ?></label></th>
            <td>
                <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
                       type="<?php echo $value['type']; ?>" value="<?php if (get_option($value['id']) != "") {
                    echo get_option($value['id']);
                } else {
                    echo $value['std'];
                } ?>"/>
                <span class="description"><?php echo __($value['desc']); ?></span>
            </td>
            </tr><?php
            break;

        case 'select':
            ?>
            <tr>
            <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name']); ?></label></th>
            <td>
                <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                    <?php foreach ($value['options'] as $val => $option) { ?>
                        <option value="<?php echo $val; ?>" <?php if (get_option($value['id']) == $val) {
                            echo ' selected="selected"';
                        } elseif ($val == $value['std']) {
                            echo ' selected="selected"';
                        } ?>><?php echo $option; ?></option>
                    <?php } ?>
                </select>
            </td>
            </tr><?php
            break;

        case 'textarea':
            $ta_options = $value['options'];
            ?>
            <tr>
            <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name']); ?></label></th>
            <td>
				<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
                          cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php
                    if (get_option($value['id']) != "") {
                        echo __(stripslashes(get_option($value['id'])));
                    } else {
                        echo __($value['std']);
                    } ?></textarea>
                <span class="description"><?php echo __($value['desc']); ?></span>
            </td>
            </tr><?php
            break;

        case 'title':
        ?></table>
    <?php echo '<h3>' . __($value['desc']) . '</h3>';
    ?>
    <table class="form-table">
        <?php
        break;

        case 'radio':
            ?>
            <tr>
            <th scope="row"><?php echo __($value['name']); ?></th>
            <td>
                <?php foreach ($value['options'] as $key => $option) {
                    $radio_setting = get_option($value['id']);
                    if ($radio_setting != '') {
                        if ($key == get_option($value['id'])) {
                            $checked = "checked=\"checked\"";
                        } else {
                            $checked = "";
                        }
                    } else {
                        if ($key == $value['std']) {
                            $checked = "checked=\"checked\"";
                        } else {
                            $checked = "";
                        }
                    } ?>
                    <div class="blocRadioSetting">
                        <input type="radio" name="<?php echo $value['id']; ?>" id="<?php echo $value['id'] . $key; ?>"
                               value="<?php echo $key; ?>" <?php echo $checked; ?> /><label
                            for="<?php echo $value['id'] . $key; ?>"><?php echo $option; ?></label>
                    </div>
                <?php } ?>
            </td>
            </tr><?php
            break;

        case 'checkbox':
            ?>
            <tr>
            <th scope="row"><?php echo __($value['name']); ?></th>
            <td>
                <?php
                if (get_option($value['id'])) {
                    $checked = "checked=\"checked\"";
                } else {
                    $checked = "";
                }
                ?>
                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
                       value="true" <?php echo $checked; ?> />
                <label for="<?php echo $value['id']; ?>"><?php echo __($value['desc']); ?></label>
            </td>
            </tr><?php
            break;

        default:

            break;
        }
        }
        ?>
    </table>

    <p class="submit">
        <input name="save" type="submit" value="<?php _e('Sauvegarder'); ?>" class="button-primary"/>
        <input type="hidden" name="action" value="save"/>
    </p>
</form>
</div>