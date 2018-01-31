# WordPress rest to transient

This repository is a WordPress theme. Install it and test. It contains all the tools you need to create custom RestApi enpoints that are cached in transient.

WordPress RestApi if REALLY SLOW but there is a fix for that. If you only need the ability to get data for you presentation website.

This test creates Wp transient on for every page/post/custom_taxonomy created and retrieves it using the bare minimum of WordPress, thus removing unnecessary overhead. 

You directly open the file and not actually an endpoint. 

Like this:
[/wp-content/themes/wordpress-rest-transient/test.php?slug=sample-page&type=page](/wp-content/themes/wordpress-rest-transient/test.php?slug=sample-page&type=page)

## Test:
The test consists of one default WordPress installation version 4.9.2 on my local machine using Mamp server and PHP 7.1.8. Also, I have used pre-setup Sample page that comes with the fresh installation.

## Speeds:
* Default RestApi endpoint: **230 - 280 ms**
* Default RestApi endpoint with 17 randomly picked plugins added: **850 - 1900 ms**
* Cached version: **50 - 70 ms no matter how much plugins you have active**.

## Note:
This method can't be used to store or edit data. Only to retrieve it.

If you have any questions feal free to contact me on [ruzevic.ivan@gmail.com](https://github.com/iruzevic/)
