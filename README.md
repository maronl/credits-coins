credits-coins
=============

wordpress plugin to enable users with a wallet of credits to be spent on acquiring content on yur blog-website

Plugin still Work In Progress

==Steps to complete version 1.0==

- implement credits metabox to assign credits to each post (post, page, custom post) defined during plugin configuration
- implement DB structure to store resource bought by users ... probably id, user_id, post_id, date, credits
- implement the functions to check if an user has bought a post
- implement the functions to store post bought by an user
- create filter for the_content() to show content only if user bought the post with credits
- implement function for admin to save user credits movements as csv
- fix js with minify version
- implement better js validation in the options page


