credits-coins
=============

wordpress plugin to enable users with a wallet of credits to be spent on acquiring content on yur blog-website

Plugin still Work In Progress

==Steps to complete version 1.0==
- implement code to install db structure during the plugin initialization
- implement phpunit testing ( I know it should be done before the development )
- make message to buy a post customizable by the user (option or file?)

step 1.0.1
- remove cost configuration of Credits. it should be managed by some other plugin that associate a cost to each credit and group of credits
- implement plugin credits-coins-recharge allow user to buy credits. actually create a bridge with paypal payments. make it extendible with other methods of payment
- selenium server used during test how is installed? composer? in git?


== Hard Working on :) ==

22/09/2014
- reset data scripts ... delete all the data in the test DB beloging to the credits coins plugin
    truncate plugin_dev.wp_credits_coins_movements;
    truncate plugin_dev.wp_credits_coins_purchases;
    delete from plugin_dev.wp_options where plugin_dev.wp_options.option_name = 'credits-coins-options';
    delete from plugin_dev.wp_postmeta where plugin_dev.wp_postmeta.meta_key like 'credits-coins-post-value';
    delete from plugin_dev.wp_usermeta where plugin_dev.wp_usermeta.meta_key like 'credits-coins-user-credits';
- test view latest 15 credits movements in the user profile
- add e2e testing to remove default credits values per page, post

- add e2e test per public side
 - content accessible for user logged that has bought the post
 - set credits for user before buy a post


19/09/2014
- added e2e tests
  - check if plugin submenu for setting is presetn
  - set defaults credits for page, post
  - check credit box value per existing post hello world is 0
  - check credit box value per new post is 4
  - check change credit post per hello world post
  - test options validation
- add e2e test per public side
  - content not accessible for user not logged
  - content not accessible for user logged without buying the post


17/09/2014
- implement better js validation in the options page
 - add jquery validation
 - implement rules for main form
- implement a bit of validation server side for options page
- add e2e framework and make hello worl test

16/09/2014
- implement function for admin to save user credits movements as csv
- fix js with minify and versioning
- added gruntfile and package.json
- added livereload functionality (you need to install https://wordpress.org/plugins/livereload/ )
- use not minified version on dev environment for js for debug purpose

15/09/2014
- refine filter for the_content with a nice html alert to buy the post
- ajax to buy a post (sending post_id check user login, credits and nonce code)
- implement feedback user buying a post:
    - ok. everythign is fine you should be redirected to the post in x seconds. otherwise click here
    - ops there was some error buying the post. check you credits. if the error persist please contact us

12/09/2014
- implement credits metabox to assign credits to each post (post, page, custom post) defined during plugin configuration
  - retrieve default values for new post, page or custom post
  - if not set a value define the default values only for new post
  - for old post if no value is defined set it to 0
  - negative value are not allowed (they will be saved as zero)
  - save data as post_meta (credits-coins-post-value)

- implement DB structure to store resource bought by users ... probably id, user_id, post_id, date, credits
- implement the functions to check if an user has bought a post
- implement the functions to store post bought by an user

- create filter for the_content() to show content only if user bought the post with credits

before 12/09/2014
- not tracked down