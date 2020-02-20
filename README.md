# Page List+ addon for Concrete5

Compatible with concrete5 8.3.x and 8.4.x

Compatible with PHP 5.6, 7.0, 7.1 and 7.2

## What it does

### Page List+ lets you create a list of pages by:

* Choosing one or more page types, page templates and/or page themes
* Filtering by keywords
* Filtering by 'related to current page content'
* Filtering can be done in 4 modes: simple, fulltext, fulltext-boolean, and expanded
* Filtering by most standard page attribute (address and most custom attribute types are not supported)
	* Has/doesn't have a value
	* Text contains/doesn't contain a value
	* Numbers and Dates within/without a range
	* Images and Files by name
	* Checkbox yes/no
	* By select option
	* By topic
	* All attributes can be filtered to 'match' the current page, in effect creating a 'related to' list
	* Filters can be user-selected, allowing the visitor to narrow a search by criteria that you setup
* User-selectable filters are, by default, sorted alphabetically. They can also be sorted manually in any order.
* Limit the number of pages returned and pagination
* Optionally show a list Title at the top of your list
* Optionally show a 'Show All' link at the bottom of your list
* Sorting to three levels by:
    * Sitemap order
    * Alpha: a-z and z-a
    * Publish date: first-last and last-first
    * Page attributes: a-z and z-a
* Use as site search, allowing users to filter based on criteria that you setup

### Ajax Forms

Connect any number of Page List+ Forms and Lists together to refresh data without reloading the page

## Adding a Page List+ Block Page
At first, the many options of Page List+ may be daunting, but if you go through the tabs left to right, it's fairly straightforward.

There are 5 tabs for the block:

1. [Page Selection](#page-selection). Select which pages from your site that you'd like to start with. By default, all pages on your site are selected.
2. [Search](#search). Setup this block as a form and/or list of results.
3. [Filters](#filters). Starting with the pages selected from the Page Selection tab, you'll start filtering out pages that have attributes that match certain criteria.
4. [Sort](#sort). List the pages in some particular order.
5. [Display](#display). Options for the output of the block.

Along with the tabs is a live preview pane of the pages that will be shown.
### Page Selection

#### Page Types
Page types generally represent the type of content and attributes that exists on a page.

* Tick the 'All Page Types' box to add pages of all types to your list.
* Unticking the 'All Page Types' box allows you to select one or more specific individual page types.

#### Page Templates

Page templates represent how your content is formatted, such as left or right navigation.

* Tick the 'All Page Templates' box to add pages of all templates to your list.
* Unticking the 'All Page Templates' box allows you to select one or more specific individual page templates.

#### Page Themes

Page themes represent how your content looks, such as the default 'Elemental' theme or a different one you've selected for your site.

* Tick the 'All Page Themes' box to add pages of all themes to your list.
* Unticking the 'All Page Theme' box allows you to select one or more specific individual page themes.

#### Page Location

Choose where in your site you'd like the pages in the list to come from. 

* If you are using this block in a stack, the 'below this page' option might be helpful.
* If you are creating some type of navigation, the 'at the current level' option might be helpful.
* If you choose something other than 'everywhere', you also get the option to select from pages below the page specified in the dropdown.

#### Page Permissions

Sometimes you may want to show pages in the list even though the user does not actually have access to them.

#### Page Aliases

Sometimes a page is actually a 'pointer' to another page on your site; this is an alias. You can choose to hide or show these 'pointers'.

### Search

#### Filters

#### Keywords

Relate to Content from Area on Current Page

### Sort

### Display

#### List Title

You can put a title above the list.

#### Number of Results

By default, all results are shown. Put a number in to limit the number of results shown. If the 'Show Pagination' is ticked, then the pagination will use this number of results for each 'page' of results.

#### No Results Text

Text that is shown during search if no results are found.

#### No Results on Page Load

Text that is shown if no results are found when the page loads.

#### Show Thumbnail

For each result, if a thumbnail attribute is available, then show it. When this box is ticked, you'll see a text box where you can type in the attribute handles for file attributes, in the order you would like to search for this thumbnail.

#### Show Page Name

Show the name of the page in the results

#### Include Page Description

Show the description of the page in the results. It can be shorted (truncated) to a certain number of characters.

#### Show Dates

Show the date the page was first made available. Think of it as a 'page created' date.

#### Use Button for Link

Show a button below the result of each page. This is useful when creating 'callouts'. If this box is ticked, you'll see a text box where you can type in the text to show on the button, such as 'Find out more'.

#### Show 'See All' Link

When this box is ticked, you can specify a web address and text for the link so visitors can see the entire list of results.

#### Provide RSS Feed

When this box is ticked, a link to an RSS feed will be shown for your page list. You can change the title and description of the feed.

#### Show Debug Information

While you are creating a Page List+ block, sometimes it's helpful to see what's going on behind the scenes. If this box is ticked, you can change the setting to show in the console and/or on the page.