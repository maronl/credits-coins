credits-coins
=============

wordpress plugin to enable users with a wallet of credits to be spent on acquiring content on yur blog-website

Plugin still Work In Progress

==Steps to complete version 1.0==
- implement feedback user buying a post:
    - ok. everythign is fine you should be redirected to the post in x seconds. otherwise click here
    - ops there was some error buying the post. check you credits. if the error persist please contact us
- implement function for admin to save user credits movements as csv
- fix js with minify and versioning version
- implement better js validation in the options page
- implement e2e testing
- implement phpunit testing ( I know it should be done before the development )
- make message to buy a post customizable by the user (option or file?)

== Hard Working on :) ==

15/09/2014
- refine filter for the_content with a nice html alert to buy the post
- ajax to buy a post (sending post_id check user login, credits and nonce code)

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