<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php if ($controller->submitOnChangeOfFilter || empty($controller->searchBoxButtonText)) { ?>
    <script>
        $sbs_plp_submitEvents_<?php echo $controller->getIdentifier(); ?> = [];
        $(function () {
            //$sbs_plp_searchFilters_<?php echo $controller->getIdentifier(); ?> = $('#sbs_plp_searchFilters_<?php echo $controller->getIdentifier(); ?>');
            var $sbs_plp_form_<?php echo $controller->getIdentifier(); ?> = $('#sbs_plp_form_<?php echo $controller->getIdentifier(); ?>');

            $sbs_plp_form_<?php echo $controller->getIdentifier(); ?>.find(':input').not('.sbs_plp_query').bind("keypress", function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false; // ignore default event
                }
            });
            $sbs_plp_form_<?php echo $controller->getIdentifier(); ?>.find(':input').not(':checkbox').bind('change', function () {
                var doSubmit = true;
                for (var eventId = 0; eventId < $sbs_plp_submitEvents_<?php echo $controller->getIdentifier(); ?>.length; eventId++) {
                    var shouldSubmit = $sbs_plp_submitEvents_<?php echo $controller->getIdentifier(); ?>[eventId]();
                    if (!shouldSubmit) {
                        return false;
                    }
                }
                if (doSubmit) {
                    $sbs_plp_form_<?php echo $controller->getIdentifier(); ?>.trigger('submit');
                }
            });
            $('#sbs_plp_form_<?php echo $controller->getIdentifier(); ?> input[type=submit]').bind('click', function () {
                var doSubmit = true;
                for (var eventId = 0; eventId < $sbs_plp_submitEvents_<?php echo $controller->getIdentifier(); ?>.length; eventId++) {
                    var shouldSubmit = $sbs_plp_submitEvents_<?php echo $controller->getIdentifier(); ?>[eventId]();
                    if (!shouldSubmit) {
                        return false;
                    }
                }
                if (doSubmit) {
                    $sbs_plp_form_<?php echo $controller->getIdentifier(); ?>.trigger('submit');
                }
            });
            $sbs_plp_form_<?php echo $controller->getIdentifier(); ?>.find(':checkbox').bind('click', function () {
                $sbs_plp_form_<?php echo $controller->getIdentifier(); ?>.trigger('submit');
            });
        });
    </script>
<?php }
