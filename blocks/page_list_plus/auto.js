var $container, $preview_container, $preview_loader, $preview_render;
var pageListPlus = {
    previewUrl: $("input[name=previewUrl]").val(),
    existingFeedHandles: $("input[name=existingFeedHandles]").val(),
    init: function () {
        this.blockForm = document.forms['ccm-block-form'];
        this.pageTypeSetup();
        this.pageTemplateSetup();
        this.pageThemeSetup();
        this.parentPageSetup();
        this.setupSearch();
        this.setupSearchDefaultClick();
        this.setupSearchFilterAllText();
        this.setupFilters();
        this.setupFulltextOption();
        this.setupSortFilters();
        this.setupSortOptions();
        this.setupDisplay();
        this.setupValidation();
    },
    pageTypeSetup: function () {
        $('#checkbox_pageType_all').bind('click', function (e) {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('TR.row_pageType_additional').hide();
                $('.row_pageType_additional .checkbox_pageType').prop('checked',false);
            } else {
                $('TR.row_pageType_additional').show();
            }
        });
        $('.row_pageType_additional .checkbox_pageType').bind('mouseup', function (e) {
            if ($('.row_pageType_additional .checkbox_pageType:checked').length > 0) {
                $('#checkbox_pageType_all').prop('checked',false).trigger('click');
            } else {
                $('#checkbox_pageType_all').attr('checked', 'checked').trigger('click');
            }
        });
    },
    pageTemplateSetup: function () {
        $('#checkbox_pageTemplate_all').bind('click', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('TR.row_pageTemplate_additional').hide();
                $('.row_pageTemplate_additional .checkbox_pageTemplate').prop('checked',false);
            } else {
                $('TR.row_pageTemplate_additional').show();
            }
        });
        $('.row_pageTemplate_additional .checkbox_pageTemplate').bind('mouseup', function () {
            if ($('.row_pageTemplate_additional .checkbox_pageTemplate:checked').length > 0) {
                $('#checkbox_pageTemplate_all').prop('checked',false).trigger('click');
            } else {
                $('#checkbox_pageTemplate_all').attr('checked', 'checked').trigger('click');
            }
        });
    },
    pageThemeSetup: function () {
        $('#checkbox_pageTheme_all').bind('click', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('TR.row_pageTheme_additional').hide();
                $('.row_pageTheme_additional .checkbox_pageTheme').prop('checked',false);
            } else {
                $('TR.row_pageTheme_additional').show();
            }
        });
        $('.row_pageTheme_additional .checkbox_pageTheme').bind('mouseup', function () {
            if ($('.row_pageTheme_additional .checkbox_pageTheme:checked').length > 0) {
                $('#checkbox_pageTheme_all').prop('checked',false).trigger('click');
            } else {
                $('#checkbox_pageTheme_all').attr('checked', 'checked').trigger('click');
            }
        });
    },
    parentPageSetup: function () {
        $('#parentPageId').bind('change', function () {
            pageListPlus.locationOtherShown();
            pageListPlus.includeAllDescendentsShown();
        });
    },
    locationOtherShown: function () {
        if ($('#parentPageId').val() == 'OTHER') {
            $('div.ccm-page-list-page-other').css({display: 'block', overflow: 'visible'});
        } else {
            $('div.ccm-page-list-page-other').slideUp();
        }
    },
    includeAllDescendentsShown: function () {
        var parentPageIdValue = $('#parentPageId').val();
        if (parentPageIdValue != 'EVERYWHERE' || parseInt(parentPageIdValue) > 0) {
            $('div.ccm-page-list-all-descendents').css({display: 'block', overflow: 'visible'});
        } else {
            $('div.ccm-page-list-all-descendents').slideUp();
        }
    },
    setupSearch: function () {
        $('#useForSearch').bind('change', function (event) {
            var $this = $(this);
            if ($this.is(':checked')) {
                $this.parent().parent().next().slideDown();
                pageListPlus.setupSearchDefault();
            } else {
                $this.parent().parent().next().slideUp();
                $('.sbs_plp_searchValue').attr('disabled', 'disabled');
            }
        }).trigger('change');

        $('#showSearchForm, #showSearchBox, #showSearchResults, #showSearchFilters, #hideSearchFilterTitles, #showSearchSelectsAsCheckbox').bind('change', function (event) {
            var $this = $(this);
            if ($this.is(':checked')) {
                $this.parent().parent().parent().next().slideDown();
            } else {
                $this.parent().parent().parent().next().slideUp();
            }
        });
    },
    setupSearchDefaultClick: function () {
        $('.searchDefault').bind('click', function () {
            pageListPlus.setupSearchDefault();
        });
    },
    setupSearchDefault: function () {
        $('.searchDefault').each(function () {
            var $searchDefault = $(this);
            var attributeId = $searchDefault.val();
            if ($searchDefault.is(':checked')) {
                $('#checkbox_pageAttribute_' + attributeId).parent().parent().parent().next().find('.sbs_plp_searchValue').removeAttr('disabled');
            } else {
                $('#checkbox_pageAttribute_' + attributeId).parent().parent().parent().next().find('.sbs_plp_searchValue').attr('disabled', 'disabled');
            }
        });
    },
    setupSearchFilterAllText: function () {
        $('#ccm-pagelist-searchFilterAllText-on').bind('click', function () {
            var $this = $(this);
            if ($this.is(":checked")) {
                $('#ccm-pagelist-searchFilterAllText').removeAttr('disabled');
            } else {
                $('#ccm-pagelist-searchFilterAllText').attr('disabled', 'disabled');
            }
        });
    },
    setupFilters: function () {
        $('#plp_resultsRelatedTo').bind('change', function() {
            var $resultsRelatedTo = $(this);
            var $resultsRelateTo = $('#results_relate_to');
            if ($resultsRelatedTo.val()!='') {
                $('.related_to_control').hide();
                $resultsRelateTo.show();
                $('#related_to_'+$resultsRelatedTo.val()).show();
            } else {
                $resultsRelateTo.hide();
            }
        }).trigger('change');
        $(':input.filterAttribute').bind('click', function () {
            var $checkbox = $(this);
            var $filterSelection = $checkbox.parent().parent().parent().next().find('.pageAttributeFilterSelection');
            var $initialSelector = $filterSelection.find('.pageAttributeInitialSelector');

            if ($checkbox.is(':checked')) {
                $initialSelector.trigger('change');
                $filterSelection.css({"padding-top": "7px", "padding-bottom": "7px"}).slideDown();
            } else {
                $filterSelection.css({"padding-top": "", "padding-bottom": ""}).slideUp();
            }
        });
        $('SELECT.pageAttributeInitialSelector').bind('change', function () {
            var $this = $(this);
            var val = $this.val();
            if (!$this.is('[data-additional-values~="' + val + '"]')) {
                $this.parent().find('DIV.pageAttributeAdditionalValueSelection').slideUp();
            } else {
                if ($this.is('[data-additional-values-multiple~="' + val + '"]')) {
                    var optionCount = $this.parent().find('DIV.pageAttributeAdditionalValueSelection SELECT OPTION').length;
                    if (optionCount>10) { optionCount = 10; }
                    $this.parent().find('DIV.pageAttributeAdditionalValueSelection SELECT').attr('multiple','multiple').attr('size',optionCount);
                } else {
                    $this.parent().find('DIV.pageAttributeAdditionalValueSelection SELECT').removeAttr('multiple').removeAttr('size');
                }
                $this.parent().find('DIV.pageAttributeAdditionalValueSelection').slideDown();
                if ($this.is('[data-additional-values-secondary~="' + val + '"]')) {
                    $this.parent().find('SPAN.pageAttributeAdditionalValueSelectionSecondary').slideDown();
                } else {
                    $this.parent().find('SPAN.pageAttributeAdditionalValueSelectionSecondary').slideUp();
                }
            }
            if ($this.is('[data-default-value~="' + val + '"]')) {
                $this.parent().find('DIV.pageAttributeDefaultValueSelection').slideDown();
                if ($this.is('[data-default-value-secondary~="' + val + '"]')) {
                    $this.parent().find('SPAN.pageAttributeDefaultValueSelectionSecondary').slideDown();
                } else {
                    $this.parent().find('SPAN.pageAttributeDefaultValueSelectionSecondary').slideUp();
                }
            } else {
                $this.parent().find('DIV.pageAttributeDefaultValueSelection').slideUp();
            }
        }).trigger('change');
        $('DIV.ccm-tab-content SELECT.pageAttributeAdditionalValueSelection_measurement').on('change', function () {
            var $this = $(this);
            $this.parent().next().find('.pageAttributeDefaultValueSelection_measurement').html($this.val());
        }).trigger('change');
        $('DIV.ccm-tab-content INPUT.pageAttributeAdditionalValueSelection_distance').on('change', function () {
            var $this = $(this);
            var $defaultValueSelect = $this.parent().next().find('.pageAttributeDefaultValueSelection_distance');
            var currentDefaultValue = $defaultValueSelect.val();
            var distances = $this.val();
            distances = distances.split(',');
            $defaultValueSelect.html('');
            for (var i = 0; i < distances.length; i++) {
                var distance = distances[i].trim();
                $defaultValueSelect.html($defaultValueSelect.html() + '<option value="' + distance + '">' + distance + '</option>');
            }
            $defaultValueSelect.val(currentDefaultValue);
        }).trigger('change');
        $('DIV.ccm-tab-content SELECT.pageAttributeDefaultValueSelection_location').on('change', function () {
            var $this = $(this);
            if ($this.val() == 'zip_code') {
                $this.next().slideDown();
            } else {
                $this.next().slideUp();
            }
        }).trigger('change');
    },
    setupFulltextOption: function () {
        $('#filter_useFulltextSearch').bind('click', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#filter_useFulltextSearch_options').slideDown();
            } else {
                $('#filter_useFulltextSearch_options').slideUp();
            }
        });
    },
    setupSortFilters: function () {
        $(".sortableAttributes").sortable();
    },
    setupSortOptions: function () {
        $('#orderBy0').bind('change', function (event) {
            var $this = $(this);
            var $userSortableTable = $('#userSortableTable');
            if ($this.val() == 'user_select') {
                if ($userSortableTable.is(':hidden')) {
                    $userSortableTable.slideDown();
                }
            } else {
                if ($userSortableTable.is(':visible')) {
                    $userSortableTable.slideUp();
                }
            }
        });

    },
    setupDisplay: function () {
        $('#useButtonForLink, #showSeeAllLink, #provideRssFeed, #showDebugInformation, #includeThumbnail, #includeDescription').bind('change', function (event) {
            var $this = $(this);
            if ($this.is(':checked')) {
                $this.parent().parent().next().slideDown();
            } else {
                $this.parent().parent().next().slideUp();
            }
        });
        $('#rssHandle').on('change',function() {
            if (pageListPlus.existingFeedHandles.indexOf(','+this.value.toLowerCase()+',')>=0) {
                alert('That feed handle is already in use.');
                this.value='';
            }
        });
        $('#ccm-pagelist-truncateSummariesOn').on('mouseup', function(){
            $('#ccm-pagelist-truncateChars').prop('disabled', (($(this).is(':checked')) ? true : false));
        });
    },
    loadPreview: function () {

        var loaderHTML = '<div style="padding: 20px; text-align: center"><img src="' + CCM_IMAGE_PATH + '/throbber_white_32.gif"></div>';
        $('#ccm-pagelistPane-preview').html(loaderHTML);
        var query = $(this.blockForm).serializeArray();
        query.push({
            name: "current_page",
            value: CCM_CID
        });

        $.post(this.previewUrl, query, function (msg) {
            $('.skybluesofa_page_list_plus_preview_column div.preview').find('div.render').html(msg);
            pageListPlus.hideLoader();
        }).fail(function () {
            pageListPlus.hideLoader();
        });
    },

    showLoader: function (element) {
        var position = element.position(),
            top = element.position().top,
            group, left, lineheight;

        lineheight = "34px";
        if (element.is('input[type=checkbox]')) {
            group = element.closest('div.checkbox');
            lineheight = "28px";
        } else if (element.is('input[type=radio]')) {
            group = element.closest('div.radio');
            lineheight = "28px";
        } else {
            group = element.closest('div.form-group');
        }

        if (typeof(group.position()) != 'undefined') {
            left = group.position().left + group.width() + 10;
        }

        $preview_loader.css({
            lineHeight: lineheight,
            left: left,
            top: top
        }).show();
    },
    hideLoader: function () {
        $preview_loader.hide();
    },
    setupValidation: function() {
        $('#ccm-form-submit-button').on('click', function(e){
            var formValid = pageListPlus.validate();

            if(!formValid){ // invalid fields
                e.preventDefault();  // stop form from submitting
                e.stopPropagation(); // stop anything else from listening to our event and screwing things up
            }
        });
    },
    validate: function() {
        if($('#provideRssFeed').is(':checked')) {
            if ($.trim($('#rssFeedTitle').val()) == '') {
                alert('You must provide a title for the RSS feed.');
                $('.skybluesofa_page_list_plus_edit_column A[data-tab="plp-display"]').trigger('click');
                $('#rssFeedTitle').focus();
                return false;
            } else if ($.trim($('#rssHandle').val()) == '') {
                alert('You must provide a URL slug for the RSS feed.');
                $('.skybluesofa_page_list_plus_edit_column A[data-tab="plp-display"]').trigger('click');
                $('#rssHandle').focus();
                return false;
            }
        }
        return true;
    }
};


Concrete.event.bind('skybluesofa_page_list_plus.edit.open', function () {
    pageListPlus.init();
    $container = $('.skybluesofa_page_list_plus_form_column');
    $preview_container = $container.find('.skybluesofa_page_list_plus_preview_column .preview');
    $preview_loader = $container.find('div.loader');
    $preview_render = $preview_container.children('div.render');

    var handle_event = _.debounce(function (event) {
        pageListPlus.showLoader($(event.target));
        pageListPlus.loadPreview();
    }, 250);

    $container.closest('form').change(handle_event).find('input.form-control, textarea').keyup(handle_event);
    $container.find('SELECT').bind('change', handle_event);
    $container.find('.ccm-page-selector INPUT').bind('change', handle_event);
    $container.find('.ccm-page-selector').on('click', '.ccm-page-selector-clear', handle_event);
    $container.find('.ccm-summary-selected-item INPUT').attr('abc', '123').bind('change', handle_event);
    _.defer(function () {
        pageListPlus.loadPreview();
    });
});
