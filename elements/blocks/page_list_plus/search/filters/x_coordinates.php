<?php 
defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Html\Service\Html as HtmlHelper;

$attributes = $controller->attributes;
$distances = trim($attributes[$filter->getAttributeKeyID()]['val1']);
$distanceOptions = array();
if ($distances) {
    foreach (explode(',', $distances) as $distance) {
        $distance = trim($distance);
        $distanceOptions[] = $distance;
    }
}
$distanceOptions = array_unique($distanceOptions);
$distanceOptions[] = '999999999';
$measurement = $attributes[$filter->getAttributeKeyID()]['val2']['measurement'] ? $attributes[$filter->getAttributeKeyID()]['val2']['measurement'] : 'miles';
$defaultValues = $controller->searchDefaults[$filter->getAttributeKeyID()];

$getValues = array();
if (isset($controller->searchDefaults[$filter->getAttributeKeyID()]) && !empty($controller->searchDefaults[$filter->getAttributeKeyID()])) {
    $getValues['distance'] = $controller->searchDefaults[$filter->getAttributeKeyID()]['distance'];
    $getValues['location'] = $controller->searchDefaults[$filter->getAttributeKeyID()]['location'];
    $getValues['zip_code'] = $controller->searchDefaults[$filter->getAttributeKeyID()]['zip_code'];
}
if (isset($controller->__GET[$filter->getAttributeKeyHandle()]['distance'])) {
    $getValues['distance'] = $controller->__GET[$filter->getAttributeKeyHandle()]['distance'];
    $getValues['location'] = $controller->__GET[$filter->getAttributeKeyHandle()]['location'];
    $getValues['zip_code'] = $controller->__GET[$filter->getAttributeKeyHandle()]['zip_code'];
}
$hideControl = false;
if (count($distanceOptions) == 0 || (count($distanceOptions == 1) && $distanceOptions[0] == '999999999')) {
    $hideControl = true;
}
?>
<div class="plp_coordinates_geolocation_control" style="display:<?php  echo $hideControl ? 'none' : ''; ?>"
     id="plp_coordinates_geolocation_control_<?php  echo $controller->getIdentifier(); ?>_<?php  echo $filter->getAttributeKeyHandle(); ?>">
    <select style="display:inline-block;width:11em;" name="<?php  echo $filter->getAttributeKeyHandle(); ?>[distance]"
            class="plp_coordinates_geolocation_control_distance">
        <?php 
        foreach ($distanceOptions as $distanceOption) {
            if ($distanceOption == '999999999') {
                echo '<option value="' . $distanceOption . '" ' . ($getValues['distance'] == $distanceOption ? 'selected' : '') . '>' . t('Everywhere') . '</option>';
            } else {
                echo '<option value="' . $distanceOption . '" ' . ($getValues['distance'] == $distanceOption ? 'selected' : '') . '>' . t('Within') . ' ' . $distanceOption . ' ' . $measurement . ' ' . t('of') . '</option>';
            }
        }
        ?>
    </select>
    <select style="display:inline-block;width:11em;" name="<?php  echo $filter->getAttributeKeyHandle(); ?>[location]"
            class="plp_coordinates_geolocation_control_location">
        <option
            value="map_center" <?php  echo in_array($getValues['location'], array('map_center', 'current_page')) ? 'selected' : ''; ?>><?php  echo t('Map Center'); ?></option>
        <option
            value="current_location" <?php  echo $getValues['location'] == 'current_location' ? 'selected' : ''; ?>><?php  echo t('Current Location'); ?></option>
        <option
            value="zip_code" <?php  echo $getValues['location'] == 'zip_code' ? 'selected' : ''; ?>><?php  echo t('Zip Code'); ?></option>
    </select>
    <input type="text" class="plp_coordinates_geolocation_control_zip_code"
           name="<?php  echo $filter->getAttributeKeyHandle(); ?>[zip_code]" style="display:inline-block;width:5em;"
           placeholder="<?php  echo t('Zip Code'); ?>" value="<?php  echo $getValues['zip_code']; ?>">
    <input type="hidden" class="plp_coordinates_geolocation_control_coords"
           name="<?php  echo $filter->getAttributeKeyHandle(); ?>[coords]" value="<?php  echo $getValues['coords']; ?>">
    <?php 
    $htmlHelper = new HtmlHelper();
    echo $htmlHelper->javascript('geoPosition.js', 'skybluesofa_page_list_plus');
    ?>
    <script>
        var current_position_<?php  echo $controller->getIdentifier(); ?> = '';
        $(function () {
            $sbs_plp_submitEvents_<?php  echo $controller->getIdentifier(); ?>[$sbs_plp_submitEvents_<?php  echo $controller->getIdentifier(); ?>.length] = function () {
                var $coordsControl = $('#sbs_plp_searchFilters_<?php  echo $controller->getIdentifier(); ?>');
                var $locationType = $coordsControl.find('.plp_coordinates_geolocation_control_location');
                if ($locationType.val() == 'zip_code' && $.trim($locationType.next().val()) == '') {
                    return false;
                } else if ($locationType.val() == 'map_center') {
                    var $coordsInput = $coordsControl.find('.plp_coordinates_geolocation_control_coords');
                    var $map = $('.sbs_page_list_plus_map:first');
                    var coords = $map.gmap('get', 'map').get('center');
                    $coordsInput.val(coords.lat() + ',' + coords.lng());
                } else if ($locationType.val() == 'current_location') {
                    if (current_position_<?php   echo $controller->getIdentifier(); ?> != '') {
                        var $coordsInput = $coordsControl.find('.plp_coordinates_geolocation_control_coords');
                        $coordsInput.val(current_position_<?php   echo $controller->getIdentifier(); ?>);
                    } else {
                        return false;
                    }
                }
                return true;
            };
            if (!geoPosition.init()) {
                $('.plp_coordinates_geolocate').hide();
            } else {
                geoPosition.getCurrentPosition(geoSuccess, geoError);
            }
            $('#sbs_plp_searchFilters_<?php   echo $controller->getIdentifier(); ?> .plp_coordinates_geolocation_control_location').each(function () {
                var $this = $(this);
                if ($this.val() == 'zip_code') {
                    $this.parent().find('.plp_coordinates_geolocation_control_zip_code').show();
                } else {
                    $this.parent().find('.plp_coordinates_geolocation_control_zip_code').hide();
                }
            });
        });
        $('#sbs_plp_searchFilters_<?php   echo $controller->getIdentifier(); ?> .plp_coordinates_geolocation_control_location').on('change', function () {
            var $this = $(this);

            if ($this.val() == 'zip_code') {
                $this.parent().find('.plp_coordinates_geolocation_control_zip_code').show();
            } else {
                $this.parent().find('.plp_coordinates_geolocation_control_zip_code').hide();
            }
        });
        function geoSuccess(p) {
            current_position_<?php   echo $controller->getIdentifier(); ?> = p.coords.latitude + ',' + p.coords.longitude;
        }
        function geoError() {
            current_position_<?php   echo $controller->getIdentifier(); ?> = '';
        }
    </script>
</div>
