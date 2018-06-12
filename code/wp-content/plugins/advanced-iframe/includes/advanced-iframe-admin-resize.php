<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
 if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="rt"></div>
<?php } ?>
<h1 id="h1-rt"><?php _e('Resize the iframe to the content height/width', 'advanced-iframe'); ?></h1>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div>
    <h2><?php _e('Resize the iframe to the content height/width', 'advanced-iframe') ?></h2>
    <h3><?php _e('Options if the iframe is on the same domain', 'advanced-iframe') ?></h3>
   <div class="manage-menus nounderline hide-always">

    <?php _e('PLEASE READ THIS FIRST:', 'advanced-iframe') ?>
    <p><?php _e('Only if the content from the iframe comes from the <strong>same domain</strong> it is possible that the onload attribute can execute Javascript directly which does e.g. resize the iframe to the height of the content or scroll the parent window to the top. <br /> If this is the case you can use the settings below. If you want to include an iframe from a <strong>different domain</strong> please go to the "<a id="external-workaround-link" href="#xss">External workaround</a>" where I explain how this can be done if you can modify the web site that should be included. So if you are on a different domain and cannot edit the external iframe page no interaction between parent and iframe is possible!', 'advanced-iframe') ?>
    </p>
    <?php _e('Please note: The resize implementation for the same domain is the same for the pro and the free version. But the external workaround of the PRO version has additional options like you can define the element to measure and it has some additional tricks that the measurements do work. So if you have problems with the auto height on the same domain try the external workaround with including ai_external.js!', 'advanced-iframe') ?>
     </div>
 
    
    <table class="form-table">
<?php
        printTrueFalse(false,$devOptions, __('Resize iframe to content height', 'advanced-iframe'), 'onload_resize', __('If you like that the iframe is resized to the height of the content you should set this to \'Yes\'. Please note that this is done by Javascript! So if a user has Javascript deactivated the iframe does not get resized. Please set the default height of the iframe to the minimum pixels the iframe should have! In the shortcode you can also specify resize_min_height="". By default this is set to 1. The content is resized to this given minimum. Sometimes this does not work properly. Try bigger values then. If this still does not wok please look at some of the pro features like "Auto zoom iframe with a fix ratio" or "Responsive videos". This setting generates the code onload="aiResizeIframe(this);" to the iframe. Shortcode attribute: onload_resize="true" or onload_resize="false" ', 'advanced-iframe'));
        printNumberInput(false,$devOptions, __('i-20-Resize delay', 'advanced-iframe'), 'onload_resize_delay', __('Sometimes the external page does not have its full height after loading because e.g. parts of the page are build by Javascript. If this is the case you can define a timeout in millisecounds until the resize is called. Otherwise leave this field empty.. Shortcode attribute: onload_resize_delay=""', 'advanced-iframe'));

        printHeightTrueFalse($devOptions, __('i-20-Store height in cookie', 'advanced-iframe'), 'store_height_in_cookie', __('If you enable the dynamic resize the value is calculated each time when the page is loaded. So each time it took a little time until the resize of the iframe is done. And this is visible sometimes if the content page loads very slow or is on a different domain or depends on the browser. By enabling this option the last calculated height is stored in a cookie and available right away. The iframe is then first resized to this height and later on when the new height comes it is updated. By default this is disabled because when you have dynamic content in the iframe it is possible that the iframe does not shrink. So please try this setting with your destination page. <strong>If you use several iframes on one page please don\'t use this because currently only one cookie per page is supported. Also you cannot use this feature if you include the ai.js file at the bottom. If you use iframe on different pages different id are needed because the id is part of the cookie.</stong>. Shortcode attribute: store_height_in_cookie="true" or store_height_in_cookie="false" ', 'advanced-iframe'));
        printHeightNumberInput(false,$devOptions, __('i-20-Additional height', 'advanced-iframe'), 'additional_height', __('If you like that the iframe is higher than the calculated value you can add some extra height here. This number is then added to the calculated one. This is e.g. needed if one of your tested browsers displays a scrollbar because of 1 or 2 pixel. Or you have an invisible area that is shown by the click on a button that can increase the size of the page. This option is NOT possible when "Store height in cookie" is enabled because this would cause that the height will increase at each reload of the parent page. If you use several iframes please use the same setting for all of them because there is only one global variable. Shortcode attribute: additional_height=""', 'advanced-iframe'));
        printTrueFalse(false,$devOptions, __('i-20-Resize iframe to content width', 'advanced-iframe'), 'onload_resize_width', __('If you like that the iframe is resized to the width of the content you should set this to \'Yes\'. PLEASE NOTE: Normally this is NOT what you want. Most people like a width of 100%! If you have a responsive layout this setting should be false. If your iframe has only a width of 1px disable the feature! Please note that this is done by Javascript and only in combination with resizing the content height! So if a user has Javascript deactivated or a not supported browser the iframe does not get resized. This setting generates the code onload="aiResizeIframe(this, \'true\');" to the iframe. Shortcode attribute: onload_resize_width="true" or onload_resize_width="false" ', 'advanced-iframe'));
        printNumberInput(false,$devOptions, __('i-20-Resize on click events', 'advanced-iframe'), 'resize_on_click', __('If you like that the iframe is resized after clicks  in the iframe please enter the timeout here. Otherwise leave this field empty. The number is the timeout in milliseconds until the resize is called. This setting intercepts the clicks on the element specified below. Catching happens BEFORE the actual action on e.g. the link. Therefore you need to enter a number > 0 because the original action is done later. 100 is a good value to start with! If you have e.g. a slide down effect you should add the time here it takes to get the full height. This setting does only work on the SAME domain by default. If you like to get this working across different domains use the "Resize on Element resize" feature of the pro version. Shortcode attribute: resize_on_click=""', 'advanced-iframe'));
        printTextInput(false,$devOptions, __('i-40-Elements where the clicks are intercepted', 'advanced-iframe'), 'resize_on_click_elements', __('You can define the tags and ids where the clicks should be intercepted. By default all links "a" are intercepted. To define a specific id you have to add the id with a :. So intercepting all links with the id "testid" you have to enter "a:testid". The id you specify is compared with "contains". So if you use "a:test" all links with an id containing test are intercepted. You can add several tags separated by ",". So "a:test,button:submitid" would work fine. Always try to specify the elements as exactly as possible to avoid any problems with other Javascript on the site. If you leave this field empty resize on click events is NOT enabled at all! Shortcode attribute: resize_on_click_elements=""', 'advanced-iframe'));
        printNumberInput(false,$devOptions, __('i-20-Resize on AJAX events', 'advanced-iframe'), 'resize_on_ajax', __('If you like that the iframe is resized after each AJAX event in the iframe please enter a number here. Otherwise leave this field empty. The number is the timeout in milliseconds until the resize is called. This setting intercepts the AJAX call after the callback was executed. So for many pages 0 should work fine. But if you have e.g. a slide down effect you should add the time here to get the full height. Currently only jQuery and direct XMLHttpRequest are supported as AJAX calls on the included page! See the "AJAX events are jQuery" setting. This setting does only work on the SAME domain by default. If you like to get this working across different domains use the "Resize on Element resize" feature of the pro version. Shortcode attribute: resize_on_ajax=""', 'advanced-iframe'));
        printTrueFalse(false,$devOptions, __('i-40-AJAX events are jQuery', 'advanced-iframe'), 'resize_on_ajax_jquery', __('Currently only direct XMLHttpRequest and jQuery AJAX call can be intercepted. Please select true = jQuery, false = XMLHttpRequest. Shortcode attribute: resize_on_ajax_jquery="true" or resize_on_ajax_jquery="false" ', 'advanced-iframe'));
      

if ($evanto || $isDemo) {
        printTextInput(true,$devOptions, __('i-20-Resize on element resize', 'advanced-iframe'), 'resize_on_element_resize', __('With this setting you are able to detect if the size of an element changes. If this is the case than the iframe is resized. This can be on click, by an Ajax call, typing with the keyboard where a menu opens, a timer .... So actually any change of the size. The big advantage is that this feature is most of the time easier to configure than the options before and also more powerful. But it has the disadvantage that the change of the size is not send by an event but the defined elements are checked in a fix interval (see below). So e.g. every 100ms a certain div is checked and if the size has changed the iframe is resized.<br />If you only specify "body" then the iframe does enlarge nicely but does not get smaller anymore. Therefore you should not use this! The best way to configure this is to use the outermost element where the change can happen. Please see example 26 for a working example. This feature does also trigger all css/js modifications inside the iframe again! You can use the jQuery syntax to specify the elements. Most likely the outermost div (e.g. #main, #page, #wrap) is the one you need. This feature is also available in the external workaround while "Resize on click events" and "Resize on AJAX events" not yet! Shortcode attribute: resize_on_element_resize=""', 'advanced-iframe'),'text','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/resize-on-element-resize', true);
        printNumberInput(true,$devOptions, __('i-40-Poll interval for the resize detection', 'advanced-iframe'), 'resize_on_element_resize_delay', __('The invervall in ms the specified element is checked for a change of the size. The minimum polling time is 50ms. If you a smaller value the default of 250 is used. Shortcode attribute: resize_on_element_resize_delay=""', 'advanced-iframe'),'text','','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/resize-on-element-resize', true );
}
        printTextInput(false,$devOptions, __('Onload', 'advanced-iframe'), 'onload', __('Enter the \'onload\' script of the iframe you want to execute. You can enter Javascript that is executed when the iframe is loaded. Please check the settings before first! There you find a solution for iframe resize. Please note that the output is escaped for security reasons with the Wordpress function esc_js. So please define your Javascript functions in your parent page, read all needed parameters inside the functions and call this function here. I recommend to use only the following characters: a-zA-Z_0-9();. Also note that the 2 settings below also use the onload event. So if you set them to true the code is appended to your onload function. If you like a different order of the predefined functions (aiShowElementOnly(id,element); aiResizeIframe(this); and aiScrollToTop();) please set the settings below to \'No\' and enter them here directly. Shortcode attribute: onload=""', 'advanced-iframe'));

?>
    </table>
 <?php
    if ($evanto || $isDemo) {
 ?>    
     <h3><?php _e('Resize hidden iframes on tabs', 'advanced-iframe') ?></h3>
     <p><?php _e('Elements that are hidden with display:none return a size of 0 when the height is measured. This is very often the case when tabs are used and you place an iframe on a tab that is not shown by default. The next settings are needed for a workaround that moves the hidden element out of the viewport, shows and measures the iframe and moves everything back. To get this working you need to provide the id or class of the tab that is hidden and depending on the tabs plugin also the id or class of the tab that is visible by default to get the correct width. Please read the section "<a class="howto-id-link" href="#">How to find the id and the attributes</a>" to find the right id or class. E.g. Tabby Responsive Tabs and Post UI Tabs work fine with this solution. Even nested tabs do work! If you need a custom solution please contact me for an offer.', 'advanced-iframe') ?>
    </p>
    <p><?php _e('IMPORTANT: If you use this feature with the external workaround you NEED to set a resize delay because otherwise the height is measured while the element is still hidden. This can be done by setting "var onload_resize_delay = 200;" before the external workaround script. Depending on the size of your page you might have to increase this value. As the tab is hidden this should not be a problem. For details please see the "<a id="external-workaround-link" href="#xss">External workaround</a>".', 'advanced-iframe') ?>
    </p>
    <p><?php _e('Please note: Check the lazy load feature! It does also support hidden tabs and is maybe the better solution as you only load the iframe when it is really visible.', 'advanced-iframe') ?>
    </p>
    <p><?php _e('Please note: This feature is not supported for responsive iframes because the size of the hidden tabs are not calculated at each resize.', 'advanced-iframe') ?>
    </p>
    <table class="form-table">
<?php
      printTextInput(true,$devOptions, __('Hidden tab(s) with iframe', 'advanced-iframe'), 'tab_hidden', __('The id or class of the tab that is hidden. You need to define the element that has display:none set. E.g. For "Tabby Responsive Tabs" this would be #tablist1-panel2 if the iframe is on the 2nd tab. For "Post UI Tabs" it would be #tabs-1-2. If you have nested hidden elements all elements need to be defined here. You need to specify each hidden element starting from the outermost. e.g. #tablist1-panel2,#tabs-1-2 if you use "Tabby Responsive Tabs" and inside the tabs "Post UI Tabs. Shortcode attribute: tab_hidden=""', 'advanced-iframe'));
      printTextInput(true,$devOptions, __('Visible tab', 'advanced-iframe'), 'tab_visible', __('The id or class of the tab that is visible by default. This is needed to preserve the width of the first hidden tab. Depending on your css this is not needed but e.g. for "Tabby Responsive Tabs" you would need #tablist1-panel1 in the default setup. If you have defined several elements at "Hidden tab(s) with iframe" you need to specify the element that has the same width as the hidden element you have defined first above. Shortcode attribute: tab_visible=""', 'advanced-iframe'));      
      ?>
      </table> 
<?php
   }
 ?>
<?php if ($devOptions['single_save_button'] == 'false') { ?> 
    <p class="button-submit">
      <input id="xss" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?> 
</div>